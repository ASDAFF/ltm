<?php
namespace Doka\Meetings\Entity;
IncludeModuleLangFile(__FILE__);

/**
 * Class Meetings
 */
class Request
{

    var $LAST_ERROR = '';

    const STATUS_EMPTY     = 0; // Статус не выбран, т.е. слот свободен
    const STATUS_PROCESS   = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_REJECTED  = 3;
    const STATUS_TIMEOUT   = 4;
	
	/*=== типы ошибок в встречах ===*/
	
	const ERROR_OTP_SEND_MES         = 'Даннный запрос уже занят с текущим таймслотом';
	const ERROR_POL_SEND_MES         = 'Данному пользователю уже был ранее отправлен запрос с текущим таймслотом';
	const ERROR_USER_NOT_FOUND       = 'Не существует пользователя с таким ID, которому отправлен запрос';
	const ERROR_USER_IS_GUEST        = 'Оба пользователя являются гостями';
	const ERROR_NO_MEET              = 'Ошибок не найдено';
	
	/**
	*нужно рассмотреть все виды ошибок
	*/
	
    static protected $module = 'doka.meetings';

    static protected $sTableName = 'meetings_requests';
	
	static protected $sTableUsers = 'b_user';
	
	static protected $sTableNameSet = 'meetings_settings';
	
	static protected $sTableNameTimeS = 'meetings_timeslots';

    static protected $sTableNameShedule = 'meetings_companies_schedule_';

    static protected $arStatuses = array(
        self::STATUS_EMPTY     => 'empty',
        self::STATUS_PROCESS   => 'process',
        self::STATUS_CONFIRMED => 'confirmed',
        self::STATUS_REJECTED  => 'rejected',
        self::STATUS_TIMEOUT   => 'timeout',
    );

    static protected $arFields = array(
        'ID',
        'CREATED_AT',
        'UPDATED_AT',
        'MODIFIED_BY',
        'SENDER_ID',
        'RECEIVER_ID',
        'TIMESLOT_ID',
        'STATUS',
        'EXHIBITION_ID',
    );

    static public function getTableName()
    {
        return self::$sTableName;
    }

    static public function getFields()
    {
        return self::$arFields;
    }

    static public function getStatusCode($id)
    {
        $arStatuses = self::$arStatuses;

        return $arStatuses[$id];
    }

    static public function getStatuses()
    {
        return self::$arStatuses;
    }

    function err_mess()
    {
        return "<br>Class: Doka\Meetings\Request<br>File: ".__FILE__;
    }
    
    function CheckFields($ACTION, &$arFields, $ID = 0)
    {
        global $DB, $APPLICATION;
        $this->LAST_ERROR = '';

        if(($ID===false || is_set($arFields, "NAME")) && strlen($arFields["NAME"])<=0)
            $this->LAST_ERROR .= "BAD_ELEMENT_NAME" ."<br>";

        if(($ID===false || is_set($arFields, "SENDER_ID")) && strlen($arFields["SENDER_ID"])<=0)
            $this->LAST_ERROR .= "BAD_SENDER_ID" ."<br>";

        if(($ID===false || is_set($arFields, "RECEIVER_ID")) && strlen($arFields["RECEIVER_ID"])<=0)
            $this->LAST_ERROR .= "BAD_RECEIVER_ID" ."<br>";

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

                $res = self::MkOperationFilter($key);
                $key = $res["FIELD"];
                $cOperationType = $res["OPERATION"];

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
                        case 'UPDATED_AT':
                            if(strlen($value)>0)
                                $arWhere[] = "(`" . $key . "` ".($cOperationType=="N"?">":"<=").$DB->CharToDateFunction($DB->ForSql($value), "FULL").($cOperationType=="N"?"":" OR `" . $key . "` IS NULL").")";
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
            if ($value == 'CREATED_AT' || $value == 'UPDATED_AT') {
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

            // Обновляем таблицу с занятостью компаний
            if ($ID > 0)
                $ID = $this->upsertCompanyShedule($ID, $arFields);
            
            return $ID;
        }

        return false;
    }

    /**
     * Добавляем/обн. данные в таблицу с занятостью компаний
     * Внимание, в MEET_N в конце записывается управляющий байт - кто кому отсылал запрос
     */
    private function upsertCompanyShedule($ID, $arFields)
    {
        global $DB, $USER;

        // Получим группу юзера, ограничение на то, что группа только одна
        $sender_id = $arFields['SENDER_ID'];
        $receiver_id = $arFields['RECEIVER_ID'];

        $timeslot_types = array_flip(self::$arStatuses);

        $sender_fields = array(
            'USER_ID' => $sender_id,
            'STATUS_'.$arFields['TIMESLOT_ID'] => $timeslot_types[ $arFields['STATUS'] ],
            'MEET_'.$arFields['TIMESLOT_ID'] => $arFields['RECEIVER_ID'] . '1',
        );

        $receiver_fields = $sender_fields;
        $receiver_fields['USER_ID'] = $receiver_id;
        $receiver_fields['MEET_'.$arFields['TIMESLOT_ID']] = $arFields['SENDER_ID'] . '0';

        // Обновим занятость для отправителя и получателя
        $arInsert = $DB->PrepareInsert(self::$sTableNameShedule.$arFields['EXHIBITION_ID'], $sender_fields);
        $arInsert2 = $DB->PrepareInsert(self::$sTableNameShedule.$arFields['EXHIBITION_ID'], $receiver_fields);

        $sSql = 'INSERT INTO `' . self::$sTableNameShedule.$arFields['EXHIBITION_ID'] . '` ('.$arInsert[0].') VALUES('.$arInsert[1].'), ('
            .$arInsert2[1].') ON DUPLICATE KEY UPDATE STATUS_'.$arFields['TIMESLOT_ID'] . '=VALUES(STATUS_'.$arFields['TIMESLOT_ID']. '), MEET_'.$arFields['TIMESLOT_ID'] . '=VALUES(MEET_'.$arFields['TIMESLOT_ID']. ')';
        //print_r($sSql);
        $q = $DB->Query($sSql, false, 'File: '.__FILE__.'<br />Line: '.__LINE__);
        if ($q) {
            return $ID;
        }

        return false;
    }

    public function Update($ID, $arFields)
    {
        global $DB;
        
        $ID = intval($ID);
        if (!$this->CheckFields("UPDATE", $arFields, $ID))
            return false;

        $arFields = $this->prepareCustomFields($arFields, 'UPDATE');
        
        if (!empty($ID)) {
            $sUpdate = $DB->PrepareUpdate(self::$sTableName, $arFields);
            if (!empty($sUpdate)) {
                $sSql = 'UPDATE `'.self::$sTableName.'` SET '.$sUpdate.' WHERE `ID`="'.$ID.'"';
                $DB->Query($sSql, false, 'File: '.__FILE__.'<br />Line: '.__LINE__);

                // TODO:: апдейтим значения статуса, переделать под отдельный запрос!
                $ID = $this->upsertCompanyShedule($ID, $arFields);
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

    /**
     * Дополняет массив полями FROM и TO, данные берем из поля NAME
     * @param  array $arFields
     * @return array $arFields
     */
    private function prepareCustomFields($arFields, $action = '')
    {
        global $USER;

        $arFields['UPDATED_AT'] = ConvertTimeStamp(time(), "FULL", "ru");

        if ($USER->GetID() > 0)
            $arFields['MODIFIED_BY'] = $USER->GetID();

        if ($action == 'ADD')
            $arFields['CREATED_AT'] = ConvertTimeStamp(time(), "FULL", "ru");


        return $arFields;
    }

    public static function MkOperationFilter($key)
    {
        static $triple_char = array(
            "!><"=>"NB", //not between
        );
        static $double_char = array(
            "!="=>"NI", //not Identical
            "!%"=>"NS", //not substring
            "><"=>"B",  //between
            ">="=>"GE", //greater or equal
            "<="=>"LE", //less or equal
        );
        static $single_char = array(
            "="=>"I", //Identical
            "%"=>"S", //substring
            "?"=>"?", //logical
            ">"=>"G", //greater
            "<"=>"L", //less
            "!"=>"N", // not field LIKE val
        );
        if(array_key_exists($op = substr($key,0,3), $triple_char))
            return Array("FIELD"=>substr($key,3), "OPERATION"=>$triple_char[$op]);
        elseif(array_key_exists($op = substr($key,0,2), $double_char))
            return Array("FIELD"=>substr($key,2), "OPERATION"=>$double_char[$op]);
        elseif(array_key_exists($op = substr($key,0,1), $single_char))
            return Array("FIELD"=>substr($key,1), "OPERATION"=>$single_char[$op]);
        else
            return Array("FIELD"=>$key, "OPERATION"=>"E"); // field LIKE val
    }

}