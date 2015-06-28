<?php
namespace Doka\Meetings\Entity;
IncludeModuleLangFile(__FILE__);

/**
 * Class Meetings
 */
class Setting
{

    var $LAST_ERROR = '';

    static protected $module = 'doka.meetings';

    static protected $sTableName = 'meetings_settings';

    static protected $sTableTimeslots = 'meetings_timeslots';

    static protected $arFields = array(
        'ID',
        'IS_LOCKED',
        'ACTIVE',
        'NAME',
        'CODE',
        'GUESTS_GROUP',
        'IS_GUEST',
        'IS_HB',
        'MEMBERS_GROUP',
        'ADMINS_GROUP',
        'EVENT_SENT',
        'EVENT_REJECT',
        'EVENT_TIMEOUT',
        'TIMEOUT_VALUE',
        'REPR_PROP_ID',
        'REPR_PROP_CODE',
        'FORM_ID',
        'FORM_RES_CODE',
    );

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
        return "<br>Class: Doka\Meetings\Setting<br>File: ".__FILE__;
    }
    
    function CheckFields($ACTION, &$arFields, $ID = 0)
    {
        global $DB, $APPLICATION;
    	$this->LAST_ERROR = '';

		if(($ID===false || is_set($arFields, "NAME")) && strlen($arFields["NAME"])<=0)
			$this->LAST_ERROR .= "BAD_ELEMENT_NAME" ."<br>";

        if(($ID===false || is_set($arFields, "REPR_PROP_ID")) && strlen($arFields["REPR_PROP_ID"])<=0)
            $this->LAST_ERROR .= "BAD_REPR_PROP_ID" ."<br>";

		if(strlen($this->LAST_ERROR) > 0)
			return false;

        return true;
    }


    static public function GetList($arSort = array(), $arFilter = array(), $arSelectFields = array())
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
							$arWhere[] = '`B`.`'.$key.'` LIKE "%'.$DB->ForSqlLike($value).'%"';
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
                                $arWhere[] = '`B`.`'.$key.'` IN ("'.implode('", "', $in).'")';
                            } else {
                                $arWhere[] = '`B`.`'.$key.'` = "'.$DB->ForSql($value).'"';
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
        
        $arInsert = $DB->PrepareInsert(self::$sTableName, $arFields);
        if (!empty($arInsert)) {
            $sSql = 'INSERT INTO `'.self::$sTableName.'` ('.$arInsert[0].') VALUES('.$arInsert[1].')';
            $DB->Query($sSql, false, 'File: '.__FILE__.'<br />Line: '.__LINE__);
            $ID = $DB->LastID();

            // РџСЂРё СѓСЃРїРµС€РЅРѕРј СЃРѕР·РґР°РЅРёРё - РґРѕР±Р°РІРёРј С‚Р°Р±Р»РёС†Сѓ СЃ Р·Р°РЅСЏС‚РѕСЃС‚СЊСЋ СЃР»РѕС‚РѕРІ
            if ($ID > 0) {
                $sSql = '
                    CREATE TABLE IF NOT EXISTS `meetings_companies_schedule_'.$ID.'` (
                      `USER_ID` int(18) NOT NULL,
                      UNIQUE KEY `total` (`USER_ID`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=1 ;
                ';
                $DB->Query($sSql, false, 'File: '.__FILE__.'<br />Line: '.__LINE__);

                return $ID;
            }
            
        }

        return false;
    }

    public function Update($ID, $arFields)
    {
        global $DB;
        
        $ID = intval($ID);
        if (!$this->CheckFields("UPDATE", $arFields, $ID))
            return false;
        
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

        // РЈРґР°Р»СЏРµРј С‚Р°Р±Р»РёС†Сѓ СЃ СЂР°СЃРїРёСЃР°РЅРёРµРј РєРѕРјРїР°РЅРёР№
        $DB->Query('DROP TABLE IF EXISTS `meetings_companies_schedule_'.$ID.'`');
        // РЈРґР°Р»СЏРµРј РІСЃРµ С‚Р°Р№РјСЃР»РѕС‚С‹
        $DB->Query('DELETE FROM `'.self::$sTableTimeslots.'` WHERE `EXHIBITION_ID`="'.$ID.'"');
        $DB->Query('DELETE FROM `'.self::$sTableName.'` WHERE `ID`="'.$ID.'"');
        return true;
    }

}