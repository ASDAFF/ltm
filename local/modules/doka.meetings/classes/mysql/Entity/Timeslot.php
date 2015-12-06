<?php
namespace Doka\Meetings\Entity;
IncludeModuleLangFile(__FILE__);

/**
 * Class Meetings
 */
class Timeslot
{

    const TYPE_MEET = 1;
    const TYPE_COFFEE = 2;

    var $LAST_ERROR = '';

    static protected $module = 'doka.meetings';

    static protected $sTableName = 'meetings_timeslots';

    static protected $types = array(
        self::TYPE_MEET => 'meet',
        self::TYPE_COFFEE => 'coffee'
    );

    static protected $arFields = array(
        'ID',
        'EXHIBITION_ID',
        'NAME',
        'SORT',
        'SLOT_TYPE',
        'TIME_FROM',
        'TIME_TO',
    );


    static public function getTypes()
    {
        return self::$types;
    }

    static public function getTypeCode($id)
    {
        $types = self::$types;

        return $types[$id];
    }

    /**
     * Р’РѕР·РІСЂР°С‰Р°РµС‚ С‚РёРїС‹ Р°РєС‚РёРІРЅС‹С… С‚Р°Р№РјСЃР»РѕС‚РѕРІ, РєРѕРіРґР° РјРѕР¶РµС‚ РїСЂРѕС…РѕРґРёС‚СЊ РІСЃС‚СЂРµС‡Р°
     * @return array РјР°СЃСЃРёРІ id С‚РёРїРѕРІ
     */
    static public function getMeetTypes()
    {
        $ids = array();

        $types = self::$types;
        foreach ($types as $id => $type) {
            if ($id != self::TYPE_COFFEE)
                $ids[] = $id;
        }

        return $ids;
    }

    /**
     * Р’РѕР·РІСЂР°С‰Р°РµС‚ РєРѕРґС‹ Р°РєС‚РёРІРЅС‹С… С‚Р°Р№РјСЃР»РѕС‚РѕРІ, РєРѕРіРґР° РјРѕР¶РµС‚ РїСЂРѕС…РѕРґРёС‚СЊ РІСЃС‚СЂРµС‡Р°
     * @return array РјР°СЃСЃРёРІ id С‚РёРїРѕРІ
     */
    static public function getMeetTypeCodes()
    {
        $codes = array();

        $types = self::$types;
        foreach ($types as $id => $type) {
            if ($id != self::TYPE_COFFEE)
                $codes[] = $type;
        }

        return $codes;
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
        return "<br>Class: Doka\Meetings\Timeslot<br>File: ".__FILE__;
    }
    
    function CheckFields($ACTION, &$arFields, $ID = 0)
    {
        global $DB, $APPLICATION;
    	$this->LAST_ERROR = '';

        if(($ID===false || is_set($arFields, "NAME")) && strlen($arFields["NAME"])<=0)
            $this->LAST_ERROR .= "BAD_ELEMENT_NAME" ."<br>";

        if(($ID===false || is_set($arFields, "NAME")) && preg_match('#\d{2}:\d{2}-\d{2}:\d{2}#si', $arFields["NAME"]) === 0)
            $this->LAST_ERROR .= "INCORRECT_NAME_PATTERN" ."<br>";

        if(($ID===false || is_set($arFields, "EXHIBITION_ID")) && intval($arFields["EXHIBITION_ID"]) == 0)
            $this->LAST_ERROR .= "BAD_EXHIBITION_ID" ."<br>";

		if(strlen($this->LAST_ERROR) > 0)
			return false;
		
        return true;
    }


    static public function GetList($arSort = array(), $arFilter = array(), $arSelectFields = array(), $join_settings = false)
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

        $sJoin = '';
        if ($join_settings) {
            $arSelect[] = '`S`.`IS_LOCKED`';
            $arSelect[] = '`S`.`GUESTS_GROUP`';
            $arSelect[] = '`S`.`IS_GUEST`';
            $arSelect[] = '`S`.`IS_HB`';
            $arSelect[] = '`S`.`MEMBERS_GROUP`';
            $arSelect[] = '`S`.`ADMINS_GROUP`';
            $arSelect[] = '`S`.`EVENT_REJECT`';
            $arSelect[] = '`S`.`EVENT_SENT`';
            $arSelect[] = '`S`.`TIMEOUT_VALUE`';
            $arSelect[] = '`S`.`NAME` AS `EXHIBITION_NAME`';
            $arSelect[] = '`S`.`CODE` AS `EXHIBITION_CODE`';
            $arSelect[] = '`S`.`REPR_PROP_ID`';
            $arSelect[] = '`S`.`REPR_PROP_CODE`';
            $arSelect[] = '`S`.`FORM_ID`';
            $arSelect[] = '`S`.`FORM_RES_CODE`';
            $arSelect[] = '`S`.`TIMEOUT_VALUE`';
            $sJoin = ' LEFT JOIN meetings_settings AS `S` ON `S`.`ID` = `B`.`EXHIBITION_ID` ';
        }

        $sSQL = 'SELECT '.implode(', ', $arSelect).' FROM `'.self::$sTableName.'` AS `B`' . $sJoin;


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
        
        $arFields = $this->prepareCustomFields($arFields);

        $arInsert = $DB->PrepareInsert(self::$sTableName, $arFields);
        if (!empty($arInsert)) {
            $sSql = 'INSERT INTO `'.self::$sTableName.'` ('.$arInsert[0].') VALUES('.$arInsert[1].')';
            $DB->Query($sSql, false, 'File: '.__FILE__.'<br />Line: '.__LINE__);
            $ID = $DB->LastID();
            
            // РџСЂРё СѓСЃРїРµС€РЅРѕРј СЃРѕР·РґР°РЅРёРё - РґРѕР±Р°РІРёРј СЃС‚РѕР»Р±РµС† РІ С‚Р°Р±Р»РёС†Сѓ СЃ Р·Р°РЅСЏС‚РѕСЃС‚СЊСЋ РєРѕРјРїР°РЅРёР№
            if ($ID > 0) {
                $sSql = '
                    ALTER TABLE  `meetings_companies_schedule_'.$arFields['EXHIBITION_ID'].'` ADD  `STATUS_'.$ID.'` INT NOT NULL DEFAULT 0, ADD  `MEET_'.$ID.'` INT NOT NULL DEFAULT 0;
                ';
                $DB->Query($sSql, false, 'File: '.__FILE__.'<br />Line: '.__LINE__);

                return $ID;
            }
        }

        return false;
    }

    /**
     * Р”РѕРїРѕР»РЅСЏРµС‚ РјР°СЃСЃРёРІ РїРѕР»СЏРјРё FROM Рё TO, РґР°РЅРЅС‹Рµ Р±РµСЂРµРј РёР· РїРѕР»СЏ NAME
     * @param  array $arFields
     * @return array $arFields
     */
    private function prepareCustomFields($arFields)
    {
        $time_arr = explode('-', $arFields['NAME']);
        $left = explode(':', $time_arr[0]);
        $right = explode(':', $time_arr[1]);

        $arFields['TIME_FROM'] = 3600 * (int)$left[0]  + (int)$left[1];
        $arFields['TIME_TO']   = 3600 * (int)$right[0] + (int)$right[1];

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
        // РЈРґР°Р»СЏРµРј СЃС‚РѕР»Р±РµС† РІ С‚Р°Р±Р»РёС†Рµ Р·Р°РЅСЏС‚РѕСЃС‚Рё РєРѕРјРїР°РЅРёР№
        $res = self::GetList(array(), array('ID' => $ID), array('EXHIBITION_ID'));
        $data = $res->Fetch();
        $DB->Query('ALTER TABLE  `meetings_companies_schedule_'.$data['EXHIBITION_ID'].'` DROP  `STATUS_'.$ID.'`, DROP  `MEET_'.$ID.'`');
        $DB->Query('DELETE FROM `'.self::$sTableName.'` WHERE `ID`="'.$ID.'"');
        return true;
    }

}