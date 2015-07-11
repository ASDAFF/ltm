<?php
namespace Doka\Meetings\Entity;
IncludeModuleLangFile(__FILE__);

class Wishlist
{

    const REASON_EMPTY     = 0;
    const REASON_REJECTED  = 1;
    const REASON_TIMEOUT   = 2;
    const REASON_SELECTED  = 3;

    var $LAST_ERROR = '';

    static protected $module = 'doka.meetings';

    static protected $sTableName = 'meetings_wishlist';

    static protected $arReasons = array(
        self::REASON_EMPTY    => 'empty',
        self::REASON_REJECTED => 'rejected',
        self::REASON_TIMEOUT  => 'timeout',
        self::REASON_SELECTED => 'selected',
    );

    static protected $arFields = array(
        'ID',
        'CREATED_AT',
        'SENDER_ID',
        'RECEIVER_ID',
        'REASON',
        'EXHIBITION_ID',
    );


    static public function getReasons()
    {
        return self::$arReasons;
    }

    static public function getReasonCode($id)
    {
        return self::$arReasons[$id];
    }

    static public function getTableName()
    {
        return self::$sTableName;
    }

    static public function getFields()
    {
        return self::$arFields;
    }

    function err_mess()
    {
        return "<br>Class: Doka\Meetings\Wishlist<br>File: ".__FILE__;
    }
    
    function CheckFields($ACTION, &$arFields, $ID = 0)
    {
        global $DB, $APPLICATION;
    	$this->LAST_ERROR = '';

        if(($ID===false || is_set($arFields, "REASON")) && strlen($arFields["REASON"])<=0)
            $this->LAST_ERROR .= "BAD_REASON" ."<br>";
        
        if(($ID===false || is_set($arFields, "SENDER_ID")) && strlen($arFields["SENDER_ID"])<=0)
            $this->LAST_ERROR .= "BAD_SENDER_ID" ."<br>";    
                
        if(($ID===false || is_set($arFields, "RECEIVER_ID")) && strlen($arFields["RECEIVER_ID"])<=0)
            $this->LAST_ERROR .= "BAD_RECEIVER_ID" ."<br>";

		if(strlen($this->LAST_ERROR) > 0)
			return false;
		
        return true;
    }


    static public function GetList($arSort = array(), $arFilter = array(), $arSelectFields = array() )
    {
        global $DB;

        $arWhere = array();
        $arOrderBy = array();
        $sLimit = '';

        if (!empty($arFilter)) {
            foreach($arFilter as $key => $value) {
                $key = addslashes($key);
                if (!empty($value)) {
                    switch($key) {
                        case 'NAME':
							$arWhere[] = '`'.$key.'` LIKE "%'.$DB->ForSqlLike($value).'%"';
							break;
                        case 'LIMIT':
                            if (is_array($value)) {
                                $sLimit = intval($value[0]);
                                if (!empty($value[1])) {
                                    $sLimit .= ', '.intval($value[1]);
                                }
                            } else {
                                $sLimit = intval($value);
                            }
                            break;
                        default:
                            if (is_array($value)) {
                                $in = array();
                                foreach ($value as $v) {
                                    $in[] = $DB->ForSql($v);
                                }
                                $arWhere[] = '`'.$key.'` IN ("'.implode('", "', $in).'")';
                            } else {
                                $arWhere[] = '`'.$key.'` = "'.$DB->ForSql($value).'"';
                            }
                    }
            	}
            }
        }

        if (!empty($arSort)) {
            foreach($arSort as $by => $order) {
                $by = strtoupper($by);
                $order = strtoupper($order);
                if (in_array($by, self::$arFields) && in_array($order, array('ASC', 'DESC'))) {
                    $arOrderBy[] = '`'.$DB->ForSql($by).'` '.$order;
                }
            }
        }

        if (!empty($arSelectFields)) {
            $arFields = array('ID');
            foreach($arSelectFields as $field) {
                $field = strtoupper($field);
                if (in_array($field, self::$arFields) && !in_array($field, $arFields)) {
                    $arFields[] = $field;
                }
            }
        } else {
            $arFields = self::$arFields;
        }

        $arSelect = array();
        foreach ($arFields as $value) {
            if ($value == 'ACTIVE_FROM' || $value == 'ACTIVE_TO') {
                $arSelect[] = $DB->DateToCharFunction('`B`.`'.$value.'`', 'FULL').' AS `'.$value.'`';
            } else {
                $arSelect[] = '`B`.`'.$value.'`';
            }
        }

        $sSQL = 'SELECT '.implode(', ', $arSelect).' FROM `'.self::$sTableName.'` AS `B`';


        if (!empty($arWhere)) {
            $sSQL .= ' WHERE '.implode(' AND ', $arWhere);
        }
        if (!empty($arOrderBy)) {
            $sSQL .= ' ORDER BY '.implode(', ', $arOrderBy);
        }
        if (!empty($sLimit)) {
            $sSQL .= ' LIMIT '.$sLimit;
        }

        return $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
    }

    public function Add($arFields)
    {
        global $DB;
        
        if (!$this->CheckFields("ADD", $arFields, 0))
            return false;
        
        $arFields = $this->prepareCustomFields($arFields, 'ADD');

        $arInsert = $DB->PrepareInsert(self::$sTableName, $arFields);
        if (!empty($arInsert)) {
            $sSql = 'INSERT INTO `'.self::$sTableName.'` ('.$arInsert[0].') VALUES('.$arInsert[1].')';
            $DB->Query($sSql, false, 'File: '.__FILE__.'<br />Line: '.__LINE__);

            $ID = $DB->LastID();

            return $ID;
        }

        return false;
    }

    /**
     * Р”РѕРїРѕР»РЅСЏРµС‚ РјР°СЃСЃРёРІ РЅРµРѕР±С…РѕРґРёРјС‹РјРё РїРѕР»СЏРјРё
     * @param  array $arFields
     * @return array $arFields
     */
    private function prepareCustomFields($arFields, $action = '')
    {
        $arFields['EXHIBITION_ID'] = $this->app_id;

        if ($action == 'ADD')
            $arFields['CREATED_AT'] = ConvertTimeStamp(time(), "FULL", "ru");

        return $arFields;
    }

    public function Update($ID, $arFields)
    {
        global $DB;
        
        $ID = intval($ID);
        if (!$this->CheckFields("UPDATE", $arFields, $ID))
            return false;

        $arFields = $this->prepareCustomFields($arFields);
        
        if (!empty($ID)) {
            $sUpdate = $DB->PrepareUpdate(self::$sTableName, $arFields);
            if (!empty($sUpdate)) {
                $sSql = 'UPDATE `'.self::$sTableName.'` SET '.$sUpdate.' WHERE `ID`="'.$ID.'"';
                $DB->Query($sSql, false, 'File: '.__FILE__.'<br />Line: '.__LINE__);

                return $ID;
            }
        }
        return false;
    }

    static public function Delete($ID)
    {
        global $DB;

        $ID = intval($ID);
        $DB->Query('DELETE FROM `'.self::$sTableName.'` WHERE `ID`="'.$ID.'"');

        return true;
    }

}