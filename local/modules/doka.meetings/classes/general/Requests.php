<?php
namespace Doka\Meetings;

require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/doka.meetings/classes/mysql/Entity/Request.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/doka.meetings/classes/mysql/Entity/Timeslot.php");

use Doka\Meetings\Entity\Request as DokaRequest;
use Doka\Meetings\Entity\Timeslot as DokaTimeslot;
use Bitrix\Main;
use Bitrix\Main\Entity;

IncludeModuleLangFile(__FILE__);

/**
 * Class Meetings
 * Методы
 * getStatusesFree()
 * getStatusesBusy()
 * getOption($name) - свойство выставки
 * getOptions()
 * getUserInfo($user_id) и getUserInfoFull($user_id, $form_id, $fio_result, $fio) - переделать
 * getTimeslots()
 * getTimeslot($id)
 * getMeetTimeslot($id)
 * GetByID($ID) - ?
 * getUserType() и getUserTypeById($user_id)
 * getUnconfirmedRequestsTotal($user_id = false)
 * getTimslotsStatuses($db_data)
 * getFreeTimeslots($user_id = false)
 * getFreeTimesAppoint($user_id = false)
 * getSortedFreeTimesAppoint($user_id = false)
 * getTotalFreeTimesAppoint($user_id = false)
 * getMeetTimeslotsIds()
 * getAllMeetTimeslots()
 * getBusyCompanies($search_group = '')
 * getFreeCompanies($search_group = '')
 * getFreeCompByTime($timeslot_id, $group_id)
 * getFreeTimesByComp($user_id) - дублирует getFreeTimesAppoint??
 * getFreeTimesIdsByComp($user_id)
 * getAllTimesByComp($user_id) и getAllTimesByCompNamed($user_id, $form_id, $result_id, $fio)
 * getFreeTimesByGroup($group_id)
 * getUsersFreeTimesByGroup($group_id)
 * checkTimeslotIsFree($timeslot_id, $user_arr)
 * confirmRequest($request)
 * rejectRequest($request)
 * timeoutRequest($request)
 * getActiveRequest($timeslot_id, $sender_id, $receiver_id)
 * getAllMeetTimesByGroup($group_id)
 * getRejectedRequests($group_id)
 * getAllCompaniesMeet($user_id) Возвращает массив содержащий все id компаний с которыми у компании $user_id есть встречи
 * getAllSlotsBetween($user1, $user2)
 * getUserGroubSimple($user_id)
 */
class Requests extends DokaRequest
{
    private $timeslots = array();

    private $options = array();

    private $timeslots_meet_total = 0;

    public function __construct($app_id, $new = false)
    {
        global $USER;

        $this->user_id = (int)$USER->GetID();

        $this->app_id = (int)$app_id;
        if ($new && $this->app_id <= 0) die('WRONG APP ID');

        // Получаем список таймслотов для текущей выставки
        $res = DokaTimeslot::GetList(array('TIME_FROM' => 'ASC'), array('EXHIBITION_ID' => $this->app_id), array('ID', 'NAME', 'SLOT_TYPE'), true);
        while ($item = $res->Fetch()) {
            if (!isset($this->options['IS_LOCKED']))
                $this->options = array(
                    'IS_LOCKED' => (bool)$item['IS_LOCKED'],
                    'GUESTS_GROUP' => $item['GUESTS_GROUP'],
                    'IS_GUEST' => $item['IS_GUEST'],
                    'IS_HB' => $item['IS_HB'],
                    'MEMBERS_GROUP' => $item['MEMBERS_GROUP'],
                    'ADMINS_GROUP' => $item['ADMINS_GROUP'],
                    'EVENT_REJECT' => $item['EVENT_REJECT'],
                    'EVENT_SENT' => $item['EVENT_SENT'],
                    'EXHIBITION_NAME' => $item['EXHIBITION_NAME'],
                    'EXHIBITION_CODE' => $item['EXHIBITION_CODE'],
                    'REPR_PROP_ID' => $item['REPR_PROP_ID'],
                    'REPR_PROP_CODE' => $item['REPR_PROP_CODE'],
                    'FORM_ID' => $item['FORM_ID'],
                    'FORM_RES_CODE' => $item['FORM_RES_CODE'],
                    'TIMEOUT_VALUE' => $item['TIMEOUT_VALUE'],
                );
            $this->timeslots[$item['ID']] = array(
                'name' => $item['NAME'],
                'type' => $item['SLOT_TYPE']
            );
            if ($item['SLOT_TYPE'] == DokaTimeslot::getTypeCode(DokaTimeslot::TYPE_MEET))
                $this->timeslots_meet_total++;
        }

        if (!count($this->timeslots)) {
            throw new \Exception("EMPTY TIMESLOTS", 1);
        }

    }

    /**
     * Возвращает статусы, считающиеся свободными
     * @return array id статусов
     */
    public function getStatusesFree()
    {
        return array(self::STATUS_EMPTY, self::STATUS_REJECTED, self::STATUS_TIMEOUT);
    }

    /**
     * Возвращает статусы, считающиеся занятыми
     * @return array id статусов
     */
    public function getStatusesBusy()
    {
        return array(self::STATUS_PROCESS, self::STATUS_CONFIRMED);
    }

    public function getOption($name)
    {
        if (isset($this->options[$name]))
            return $this->options[$name];

        return false;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getUserInfo($user_id)
    {
        global $USER;
        if ($user_id <= 0) return false;

        $filter = array( 'ID' => $user_id );
        $select = array(
            'SELECT' => array($this->getOption('REPR_PROP_CODE')),
            'FIELDS' => array('WORK_COMPANY', 'ID', 'EMAIL')
        );
        $rsUser = \CUser::GetList(($by="id"), ($order="desc"), $filter, $select);
        if ($arUser = $rsUser->Fetch()) {
            return array(
                'company_id' => $arUser['ID'],
                'company_name' => $arUser['WORK_COMPANY'],
                'repr_name' => $arUser[$this->getOption('REPR_PROP_CODE')],
                'email' => $arUser['EMAIL'],
            );
        }

        return false;
    }

    public function getUserInfoFull($user_id, $form_id, $fio_result, $fio)
    {
        global $USER;
        if ($user_id <= 0) return false;

        $filter = array( 'ID' => $user_id );
        $select = array(
            'SELECT' => array($fio_result),
            'FIELDS' => array('WORK_COMPANY', 'ID', 'EMAIL')
        );
        $rsUser = \CUser::GetList(($by="id"), ($order="desc"), $filter, $select);
        if ($arUser = $rsUser->Fetch()) {
            $arAnswer = \CFormResult::GetDataByID(
                        $arUser[$fio_result], 
                        array(),  // вопрос "Какие области знаний вас интересуют?" 
                        $arResultTmp, 
                        $arAnswer2);
            return array(
                'company_id' => $arUser['ID'],
                'company_name' => $arUser['WORK_COMPANY'],
                'repr_name' => trim($arAnswer2[$fio[0][0]][$fio[0][1]]["USER_TEXT"])." ".trim($arAnswer2[$fio[1][0]][$fio[1][1]]["USER_TEXT"]),
                'email' => trim($arAnswer2[$fio[2][0]][$fio[2][1]]["USER_TEXT"]),
            );
        }
        return false;
    }

    public function getTimeslots()
    {
        return $this->timeslots;
    }

    /**
     * Возвращает инфо по таймслоту по его id
     * @param  int $id $timeslot_id
     * @return mixed
     */
    public function getTimeslot($id)
    {
        if (array_key_exists($id, $this->timeslots)) {
            $timeslot_arr = $this->timeslots[$id];
            
            return array_merge($timeslot_arr, array('id' => $id));
        }

        return false;
    }

    /**
     * Возвращает инфу только для таймслота типа "Встреча"
     */
    public function getMeetTimeslot($id)
    {
        if ($timeslot = $this->getTimeslot($id)) {
            if (in_array($timeslot['id'], $this->getMeetTimeslotsIds()))
                return $timeslot;
        }

        return false;
    }

    function GetByID($ID)
    {
        return self::GetList(array(), array("ID" => $ID));
    }

    public function getUserType()
    {
        global $USER;

        $arGroups = $USER->GetUserGroupArray();
        if (in_array($this->getOption('ADMINS_GROUP'), $arGroups) || $USER->IsAdmin())
            $group_type = 'ADMIN';
        else if (in_array($this->getOption('GUESTS_GROUP'), $arGroups))
            $group_type = 'GUEST';
        else
            $group_type = 'PARTICIP';

        return $group_type;
    }

    public function getUserTypeById($user_id)
    {
        global $USER;
        
        $arGroups = \CUser::GetUserGroup($user_id);
        if (in_array($this->getOption('ADMINS_GROUP'), $arGroups) || $USER->IsAdmin())
            $group_type = 'ADMIN';
        else if (in_array($this->getOption('GUESTS_GROUP'), $arGroups))
            $group_type = 'GUEST';
        else
            $group_type = 'PARTICIP';

        return $group_type;
    }

    /**
     * Возвращает количество неподтвержденных запросов на встречи для пользователя по его id.
     * @param  int $user_id id пользователя
     * @return array массив с количеством отправленных и входящих неподтвержденных запросов
     */
    public function getUnconfirmedRequestsTotal($user_id = false)
    {
        global $DB;
        $user_id = (!$user_id) ? $this->user_id : (int)$user_id;
        if ($user_id <= 0) 
            return false;

        $sSQL = 'SELECT COUNT(*) AS TOTAL, SUM(SENDER_ID = '.$user_id.') AS SENT FROM ' . self::$sTableName
            . ' WHERE (RECEIVER_ID=' . $DB->ForSql($user_id) . ' OR SENDER_ID=' . $DB->ForSql($user_id) . ') AND STATUS = ' . self::STATUS_PROCESS . ' AND EXHIBITION_ID=' . $this->app_id;
        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
        if ($data = $res->Fetch()) {
            return array(
                'sent' => (int)$data['SENT'],
                'incoming' => $data['TOTAL'] - $data['SENT'],
                'total' => (int)$data['TOTAL']
            );
            
        }

        return false;
    }

    /**
     * Возвращает массив статусов на основании полей, полученных из БД
     * @return array timeslot_id => status_id
     */
    public function getTimslotsStatuses($db_data)
    {
        $timeslots_statuses = array();

        foreach ($db_data as $column => $status_id) {
            if (strpos($column, 'STATUS_') === 0) {
                $timeslot_id = (int)str_replace('STATUS_', '', $column);
                $timeslots_statuses[$timeslot_id] = $status_id;
            }
        }

        return $timeslots_statuses;
    }

    /**
     * Возвращает незанятые таймслоты для компании user_id
     * Незанятые - если не было запросов или статусы "Отменен" или "Отменен по таймауту"
     * @param  int $user_id id пользователя-компании
     * @return array
     */
    public function getFreeTimeslots($user_id = false)
    {
        global $DB;
        $user_id = (!$user_id) ? $this->user_id : (int)$user_id;
        if ($user_id <= 0) 
            return false;
        $free_timeslots = array();

        // Статусы для незанятых таймслотов
        $statuses_free = $this->getStatusesFree();

        $sSQL = 'SELECT * FROM ' . self::$sTableNameShedule.$this->app_id . ' WHERE USER_ID=' . $DB->ForSql($user_id);

        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        // Сформируем удобочитаемый массив статусов запросов для пользователя
        $timeslot_statuses = array();
        if ($data = $res->Fetch()) {
            $timeslot_statuses = $this->getTimslotsStatuses($data);
        }
        // Получим незанятые таймслоты
        foreach ($timeslot_statuses as $timeslot_id => $status_id) {
            if (in_array($status_id, $statuses_free))
                $free_timeslots[] = array(
                    'id' => $timeslot_id,
                    'name' => $this->timeslots[$timeslot_id]['name'],
                    'type' => $this->timeslots[$timeslot_id]['type'],
                );
        }

        return $free_timeslots;
    }

    /**
     * Возвращает массив содержащий доступные таймслоты типа Встреча
     * @param  int $user_id id компании
     * @return array (id, name)
     */
    public function getFreeTimesAppoint($user_id = false)
    {
        $free_timeslots = $this->getFreeTimeSlots($user_id);
        $slot_type_meet_code = DokaTimeslot::getTypeCode(DokaTimeslot::TYPE_MEET);
        foreach ($free_timeslots as $key => $timeslot) {
            if ($timeslot['type'] != $slot_type_meet_code)
                unset($free_timeslots[$key]);
            unset($free_timeslots[$key]['type']);
        }

        return $free_timeslots;
    }

    /**
     * Возвращает массив содержащий доступные таймслоты типа Встреча в порядке сортировки
     * @param  int $user_id id компании
     * @return array (id, name)
     */
    public function getSortedFreeTimesAppoint($user_id = false)
    {
        $free_timeslots = $this->getFreeTimeSlots($user_id);
		    $allTimes = $this->getTimeslots();
        $slot_type_meet_code = DokaTimeslot::getTypeCode(DokaTimeslot::TYPE_MEET);
        if(empty($free_timeslots)){
          $counter = 0;
          foreach ($allTimes as $key => $timeslot) {
            if($timeslot['type'] == $slot_type_meet_code){
            $free_timeslots[$counter]["id"] = $key;
            $free_timeslots[$counter]["name"] = $timeslot['name'];
            $counter++;
            }
          }
          return $free_timeslots;
        }
        foreach ($free_timeslots as $key => $timeslot) {
            if ($timeslot['type'] == $slot_type_meet_code){
				$allTimes[$timeslot['id']]['free'] = 1;
			}
        }
		$free_timeslots = array();
		$counter = 0;
        foreach ($allTimes as $key => $timeslot) {
            if (isset($timeslot['free']) && $timeslot['free'] == 1){
				$free_timeslots[$counter]["id"] = $key;
				$free_timeslots[$counter]["name"] = $timeslot['name'];
				$counter++;
			}
        }
        return $free_timeslots;
    }

    /**
     * Возвращает количество  доступных таймслотов типа Встреча
     * @param  int $user_id id пользователя
     * @return int кол-во свободных таймслотов
     */
    public function getTotalFreeTimesAppoint($user_id = false)
    {
        return count($this->getFreeTimesAppoint($user_id));
    }

    /**
     * возвращает массив id таймслотов типа "Встреча"
     */
    public function getMeetTimeslotsIds()
    {
        $meet_timeslots_ids = array();
        foreach ($this->timeslots as $id => $item) {
            if ($item['type'] == DokaTimeslot::getTypeCode(DokaTimeslot::TYPE_MEET)) {
                $meet_timeslots_ids[] = $id;
            }
        }

        return $meet_timeslots_ids;
    }

    /**
     * возвращает массив таймслотов типа "Встреча"
     */
    public function getAllMeetTimeslots()
    {
		$allTimes = $this->getTimeslots();
        $slot_type_meet_code = DokaTimeslot::getTypeCode(DokaTimeslot::TYPE_MEET);
        foreach ($allTimes as $key => $timeslot) {
            if ($timeslot['type'] != $slot_type_meet_code){
				 unset($allTimes[$key]);
			}
			unset($allTimes[$key]['type']);
        }
        return $allTimes;
    }

    /*
    * Возвразщает все встречи(id) между 2 пользователями для выставок $exib
    */
    public function getAllSlotsBetween($user1, $user2, $exib){
        global $DB;

        $sWhere = ' WHERE EXHIBITION_ID IN (' . implode(',', $exib) . ')';
        $sWhere .= ' AND STATUS IN ('.implode(',', $this->getStatusesBusy()).')';
        $sql = "SELECT ID FROM ". self::$sTableName . $sWhere. " AND sender_id=$user1 AND receiver_id=$user2";
        $sql .= " UNION ";
        $sql .= "SELECT ID FROM ". self::$sTableName . $sWhere. " AND sender_id=$user2 AND receiver_id=$user1";
        $res = $DB->Query($sql, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        // Сформируем удобочитаемый массив
        $resTimes = array();
        if ($data = $res->Fetch()) {
            $resTimes = $data;
        }
        return $resTimes;
    }

    /*
     * Возвращает компании у которых заняты все слоты для выставки
     * @param  id $search_group id группы
     * @return array компании с полями (id, name)
     */
    public function getBusyCompanies($search_group = '')
    {
        global $DB;
        $companies = array();

        // Соберем в кучу все таймслоты с типом встреча
        $meet_timeslots_ids = $this->getMeetTimeslotsIds();
        // Статусы, которые означают, что слот свободен
        $statuses_free = $this->getStatusesFree();
        $sWhere = ' WHERE ';
        $leftJoin = ' LEFT JOIN b_user ON b_user.ID=ms.USER_ID ';
		if($search_group){
			$leftJoin .= 'LEFT JOIN b_user_group bug ON b_user.ID=bug.USER_ID';
			$sWhere .= ' bug.GROUP_ID=' . $DB->ForSql($search_group) . ' AND ';
		}
        $arStatusWhere = array();
        foreach ($meet_timeslots_ids as $timeslot_id) {
            $arStatusWhere[] = 'STATUS_' . $timeslot_id . ' NOT IN (' . implode(',', $statuses_free) . ')';
        }
        $sSQL = 'SELECT ms.USER_ID as company_id, b_user.WORK_COMPANY as company_name FROM ' . self::$sTableNameShedule.$this->app_id . ' ms ' . $leftJoin . $sWhere . implode(' AND ', $arStatusWhere). ' ORDER BY company_name';
        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
        return $res;
    }

    /**
     * Возвращает компании у которых есть свободные слоты
     * @param  id $search_group id группы
     * @return array компании с полями (id, name)
     */
    public function getFreeCompanies($search_group = '')
    {
        global $DB;

        $companies = array();

        // Соберем в кучу все таймслоты с типом встреча
        $meet_timeslots_ids = $this->getMeetTimeslotsIds();

        // Статусы, которые означают, что слот свободен
        $statuses_free = $this->getStatusesFree();
        $sWhere = ' WHERE ';
        $leftJoin = ' LEFT JOIN b_user ON b_user.ID=ms.USER_ID ';
        if($search_group){
            $leftJoin .= 'LEFT JOIN b_user_group bug ON b_user.ID=bug.USER_ID';
            $sWhere .= ' bug.GROUP_ID=' . $DB->ForSql($search_group) . ' AND ';
        }
        $arStatusWhere = array();
        foreach ($meet_timeslots_ids as $timeslot_id) {
            $arStatusWhere[] = 'STATUS_' . $timeslot_id . ' IN (' . implode(',', $statuses_free) . ')';
        }

        $sSQL = 'SELECT ms.USER_ID as company_id, b_user.WORK_COMPANY as company_name FROM ' . self::$sTableNameShedule.$this->app_id . ' ms ' . $leftJoin . $sWhere .' ( '. implode(' OR ', $arStatusWhere).' ) ORDER BY company_name';
        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        return $res;
    }
    /**
     * Возвращает массив, содержащий компании, у которых свободен слот $timeslot_id
     * @param  int $timeslot_id id таймслота
     * @return array массив компаний с полями (id, name)
     */
    public function getFreeCompByTime($timeslot_id, $group_id)
    {
        if ( (int)$timeslot_id <= 0 ) 
            return false;

        global $DB;

        $companies = array();

        // Статусы, которые означают, что слот свободен
        $statuses_free = $this->getStatusesFree();
        $sWhere = ' WHERE bug.GROUP_ID=' . $DB->ForSql($group_id);

		if($this->getOption('GUESTS_GROUP') == $group_id){
            $guestField = "UF_MR";
            if($this->getOption('IS_HB')){
                $guestField = "UF_HB";
            }
			$sWhere = ' LEFT JOIN b_uts_user ON b_uts_user.VALUE_ID=bu.ID'.$sWhere.' AND b_uts_user.'.$guestField.'=1';
		}

        $sSQL = 'SELECT bu.WORK_COMPANY, bu.ID, ms.USER_ID, ms.STATUS_' . $timeslot_id . ' FROM `b_user` bu INNER JOIN `b_user_group` bug ON bu.ID=bug.USER_ID LEFT JOIN `' . self::$sTableNameShedule.$this->app_id 
            . '` ms ON bu.ID=ms.USER_ID'. $sWhere. ' GROUP BY bu.ID ORDER BY bu.WORK_COMPANY';


        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        while ($data = $res->Fetch()) {
            if ($data['USER_ID'] === null) {//Это значит все таймслоты свободны
				$companies[] = array(
					'id' => $data['ID'],
					'name' => $data['WORK_COMPANY']
				);
            }
			elseif(in_array($data['STATUS_' . $timeslot_id ], $statuses_free)){
			  $companies[] = array(
				  'id' => $data['ID'],
				  'name' => $data['WORK_COMPANY']
			  );
            }
        }

        return $companies;
    }

    /**
     * Возвращает массив содержащий свободные таймслоты типа Встреча для компании user_id
     * @param  int $user_id id представителя компании
     * @return array массив таймслотов с полями (id, name)
     */
    public function getFreeTimesByComp($user_id)
    {
        $user_id = (!$user_id) ? $this->user_id : (int)$user_id;
        if ($user_id <= 0) 
            return false;

        global $DB;

        $timeslots = array();

        $sWhere = ' WHERE USER_ID=' . $user_id;

        $sSQL = 'SELECT * FROM ' . self::$sTableNameShedule.$this->app_id . $sWhere;

        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        if ($data = $res->Fetch()) {
            // слоты типа встреча
            $meet_timeslots_ids = $this->getMeetTimeslotsIds();
            // статусы слотов на основании данных из БД
            $timeslot_statuses = $this->getTimslotsStatuses($data);
            foreach ($timeslot_statuses as $timeslot_id => $status_id) {
                // если слот типа "встреча"
                if (in_array($timeslot_id, $meet_timeslots_ids))
                    $timeslots[] = array(
                        'id' => $timeslot_id,
                        'name' => $this->timeslots[$timeslot_id]['name']
                    );
            }
        }

        return $timeslots;
    }

    /**
     * Возвращает массив содержащий id свободных таймслотов типа Встреча для компании user_id
     * @param  int $user_id id представителя компании
     * @return array массив таймслотов id
     */
    public function getFreeTimesIdsByComp($user_id)
    {
        $user_id = (!$user_id) ? $this->user_id : (int)$user_id;
        if ($user_id <= 0) 
            return false;

        global $DB;

        $timeslots = array();

        $sWhere = ' WHERE USER_ID=' . $user_id;

        $sSQL = 'SELECT * FROM ' . self::$sTableNameShedule.$this->app_id . $sWhere;

        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        if ($data = $res->Fetch()) {
            // слоты типа встреча
            $meet_timeslots_ids = $this->getMeetTimeslotsIds();
            // статусы слотов на основании данных из БД
            $timeslot_statuses = $this->getTimslotsStatuses($data);
            foreach ($timeslot_statuses as $timeslot_id => $status_id) {
                // если слот типа "встреча"
                if (in_array($timeslot_id, $meet_timeslots_ids))
                    $timeslots[] = $timeslot_id;
            }
        }

        return $timeslots;
    }

    /**
     * Возвращает массив содержащий все таймслоты типа Встреча для компании $user_id. В массив не попадают запросы со статусом Отменен
     * @param  int $user_id id представителя компании
     * @return array массив таймслотов
     */
    public function getAllTimesByComp($user_id)
    {
        $user_id = (!$user_id) ? $this->user_id : (int)$user_id;
        if ($user_id <= 0) 
            return false;

        global $DB;

        $timeslots = array();

        $statuses_rejected = array(self::STATUS_REJECTED, self::STATUS_TIMEOUT);

        // сортировка по убыванию ID в каком-то смысле гарантирует, что будет актуальный статус
        $sSQL = 'SELECT t1.*, b_uts_user.'.$this->getOption("REPR_PROP_CODE").' as repr_fio, b_user.WORK_COMPANY, t1.SENDER_ID as repr_id FROM ' . self::$sTableName . ' t1 LEFT JOIN b_user ON b_user.ID=SENDER_ID LEFT JOIN b_uts_user ON b_uts_user.VALUE_ID=SENDER_ID WHERE RECEIVER_ID=' . $DB->ForSql($user_id) 
                . ' AND STATUS NOT IN (' . implode(',', $statuses_rejected) . ') AND EXHIBITION_ID=' . $this->app_id .
                ' UNION ALL ' . 
                'SELECT t2.*, b_uts_user.'.$this->getOption("REPR_PROP_CODE").' as repr_fio, b_user.WORK_COMPANY, t2.RECEIVER_ID as repr_id FROM ' . self::$sTableName . ' t2 LEFT JOIN b_user ON b_user.ID=RECEIVER_ID LEFT JOIN b_uts_user ON b_uts_user.VALUE_ID=RECEIVER_ID WHERE SENDER_ID=' . $DB->ForSql($user_id) 
                . ' AND STATUS NOT IN (' . implode(',', $statuses_rejected) . ') AND EXHIBITION_ID=' . $this->app_id . ' ORDER BY ID DESC';

        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        while ($data = $res->Fetch()) {
            if (!array_key_exists($data['TIMESLOT_ID'], $timeslots)) {
                $time_left = $this->options["TIMEOUT_VALUE"] - floor((time() - strtotime($data['UPDATED_AT']))/3600);
                if($time_left < 0){
                    $time_left = 0;
                }
                $timeslots[$data['TIMESLOT_ID']] = array(
                    'id' => $data['TIMESLOT_ID'],
                    'name' => $this->timeslots[$data['TIMESLOT_ID']]['name'],
                    'meet' => array(
                        'request_id' => $data['ID'],
                        'status' => $data['STATUS'],
                        'company_id' => $data['repr_id'],
                        'company_rep' => $data['repr_fio'],
                        'form_res' => $data['repr_fio'],
                        'company_name' => $data['WORK_COMPANY'],
                        'modified_by' => $data['MODIFIED_BY'],
                        'date' => $time_left
                    )
                );
            }
        }

        return $timeslots;
    }

    /**
     * Возвращает массив содержащий все таймслоты типа Встреча для компании $user_id. В массив не попадают запросы со статусом Отменен. Используются правильные ФИО представителей
     * @param  int $user_id id представителя компании
     * @param  int $form_id id формы для представителя
     * @param  int $result_id название поля с id результата формы для представителя
     * @param  array $fio id поля формы для представителя с именем
     * @return array массив таймслотов
     */
    public function getAllTimesByCompNamed($user_id, $form_id, $result_id, $fio)
    {
        $user_id = (!$user_id) ? $this->user_id : (int)$user_id;
        if ($user_id <= 0) 
            return false;

        global $DB;

        $timeslots = array();

        $statuses_rejected = array(self::STATUS_REJECTED, self::STATUS_TIMEOUT);

        // сортировка по убыванию ID в каком-то смысле гарантирует, что будет актуальный статус
        $sSQL = 'SELECT t1.*, b_uts_user.'.$result_id.' as repr_fio, b_user.WORK_COMPANY, t1.SENDER_ID as repr_id FROM ' . self::$sTableName . ' t1 LEFT JOIN b_user ON b_user.ID=SENDER_ID LEFT JOIN b_uts_user ON b_uts_user.VALUE_ID=SENDER_ID WHERE RECEIVER_ID=' . $DB->ForSql($user_id)
                . ' AND STATUS NOT IN (' . implode(',', $statuses_rejected) . ') AND EXHIBITION_ID=' . $this->app_id .
                ' UNION ALL ' . 
                'SELECT t2.*, b_uts_user.'.$result_id.' as repr_fio, b_user.WORK_COMPANY, t2.RECEIVER_ID as repr_id FROM ' . self::$sTableName . ' t2 LEFT JOIN b_user ON b_user.ID=RECEIVER_ID LEFT JOIN b_uts_user ON b_uts_user.VALUE_ID=RECEIVER_ID WHERE SENDER_ID=' . $DB->ForSql($user_id) 
                . ' AND STATUS NOT IN (' . implode(',', $statuses_rejected) . ') AND EXHIBITION_ID=' . $this->app_id . ' ORDER BY ID DESC';

        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        while ($data = $res->Fetch()) {
            if (!array_key_exists($data['TIMESLOT_ID'], $timeslots)) {
                if(\CModule::IncludeModule("form")){
                    $arAnswer = \CFormResult::GetDataByID(
                        $data['repr_fio'], 
                        array(),  // вопрос "Какие области знаний вас интересуют?" 
                        $arResultTmp, 
                        $arAnswer2);
                }
                foreach($arAnswer2[$fio[3][0]] as $value){
                    $hall = $value["MESSAGE"];
                }
                $time_left = $this->options["TIMEOUT_VALUE"] - floor((time() - strtotime($data['UPDATED_AT']))/3600);
                if($time_left < 0){
                    $time_left = 0;
                }
                if(!isset($fio[0][1])){
                    foreach($arAnswer2[$fio[0][0]] as $value){
                        $name = $value["USER_TEXT"];
                    }
                    foreach($arAnswer2[$fio[1][0]] as $value){
                        $suname = $value["USER_TEXT"];
                    }
                    foreach($arAnswer2[$fio[2][0]] as $value){
                        $table = $value["USER_TEXT"];
                    }
                    $timeslots[$data['TIMESLOT_ID']] = array(
                        'id' => $data['TIMESLOT_ID'],
                        'name' => $this->timeslots[$data['TIMESLOT_ID']]['name'],
                        'meet' => array(
                            'request_id' => $data['ID'],
                            'status' => $data['STATUS'],
                            'company_id' => $data['repr_id'],
                            'form_res' => $data['repr_fio'],
                            'company_rep' => trim($name)." ".trim($suname),
                            'hall' => trim($hall),
                            'table' => trim($table),
                            'company_name' => $data['WORK_COMPANY'],
                            'modified_by' => $data['MODIFIED_BY'],
                            'date' => $time_left
                        )
                    );
                }
                else{
                    $timeslots[$data['TIMESLOT_ID']] = array(
                        'id' => $data['TIMESLOT_ID'],
                        'name' => $this->timeslots[$data['TIMESLOT_ID']]['name'],
                        'meet' => array(
                            'request_id' => $data['ID'],
                            'status' => $data['STATUS'],
                            'company_id' => $data['repr_id'],
                            'form_res' => $data['repr_fio'],
                            'company_rep' => trim($arAnswer2[$fio[0][0]][$fio[0][1]]["USER_TEXT"])." ".trim($arAnswer2[$fio[1][0]][$fio[1][1]]["USER_TEXT"]),
                            'hall' => trim($hall),
                            'table' => trim($arAnswer2[$fio[2][0]][$fio[2][1]]["USER_TEXT"]),
                            'company_name' => $data['WORK_COMPANY'],
                            'modified_by' => $data['MODIFIED_BY'],
                            'date' => $time_left
                        )
                    );                    
                }
            }
        }

        return $timeslots;
    }

    /**
     * Возвращает массив содержащий все id компаний с которыми у компании $user_id есть встречи
     * @param  int $user_id id представителя компании
     * @return array массив id компаний
     */
    public function getAllCompaniesMeet($user_id)
    {
        $user_id = (!$user_id) ? $this->user_id : (int)$user_id;
        if ($user_id <= 0)
            return false;

        global $DB;

        $companies = array();

        $statuses_rejected = array(self::STATUS_REJECTED, self::STATUS_TIMEOUT);

        // сортировка по убыванию ID в каком-то смысле гарантирует, что будет актуальный статус
        $sSQL = 'SELECT t1.*, t1.SENDER_ID as repr_id FROM ' . self::$sTableName . ' t1 WHERE RECEIVER_ID=' . $DB->ForSql($user_id)
            . ' AND STATUS NOT IN (' . implode(',', $statuses_rejected) . ')
AND EXHIBITION_ID=' . $this->app_id .
            ' UNION ALL ' .
            'SELECT t2.*, t2.RECEIVER_ID as repr_id FROM ' . self::$sTableName . ' t2 WHERE SENDER_ID=' . $DB->ForSql($user_id)
            . ' AND STATUS NOT IN (' . implode(',', $statuses_rejected) . ') AND EXHIBITION_ID=' . $this->app_id . ' ORDER BY ID DESC';

        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        while ($data = $res->Fetch()) {
            $companies[ $data["repr_id"] ] = $data["repr_id"];
        }

        return $companies;
    }

    /**
     * Dозвращает список таймслотов и компаний со свободными таймслотами(упорядочено по таймслотам), входящих в группу group_id, исключая текущего юзера
     * @param  id $group_id id группы
     * @return array
     */
    public function getFreeTimesByGroup($group_id)
    {
        global $DB, $USER;

        $timeslots = array();

        $statuses_free = $this->getStatusesFree();

        $meet_timeslots = $this->getMeetTimeslotsIds();

		if($this->getOption('GUESTS_GROUP') == $group_id){
            $guestField = "UF_MR";
            if($this->getOption('IS_HB')){
                $guestField = "UF_HB";
            }
            $sSQL = 'SELECT bu.WORK_COMPANY, bu.ID, ms.* FROM `b_user` bu INNER JOIN `b_user_group` bug ON bu.ID=bug.USER_ID LEFT JOIN `' . self::$sTableNameShedule.$this->app_id 
			  . '` ms ON bu.ID=ms.USER_ID LEFT JOIN b_uts_user ON b_uts_user.VALUE_ID=bu.ID WHERE bug.GROUP_ID=' . $DB->ForSql($group_id) . ' AND b_uts_user.'.$guestField.'=1 GROUP BY bu.ID ORDER BY bu.WORK_COMPANY';
		}
		else{
		  $sSQL = 'SELECT bu.WORK_COMPANY, bu.ID, ms.* FROM `b_user` bu INNER JOIN `b_user_group` bug ON bu.ID=bug.USER_ID LEFT JOIN `' . self::$sTableNameShedule.$this->app_id 
			  . '` ms ON bu.ID=ms.USER_ID WHERE bug.GROUP_ID=' . $DB->ForSql($group_id) . ' GROUP BY bu.ID ORDER BY bu.WORK_COMPANY';
		}

        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        while ($data = $res->Fetch()) {
            if ($data['ID'] == $USER->GetID()) continue; // самого себя пропускаем
            // Если пользователя нет в таблице занятости, значит у него все слоты свободны
            if ($data['USER_ID'] === null) {
                foreach ($meet_timeslots as $timeslot_id) {
                    $timeslots[$timeslot_id][] = array(
                        'id' => $data['ID'],
                        'name' => $data['WORK_COMPANY']
                    );
                }
            } else {
                $statuses = $this->getTimslotsStatuses($data);
                foreach ($statuses as $timeslot_id => $status_id) {
                    if ( in_array($timeslot_id, $meet_timeslots) && in_array($status_id, $statuses_free)) {
                        $timeslots[$timeslot_id][] = array(
                            'id' => $data['ID'],
                            'name' => $data['WORK_COMPANY']
                        );
                    }
                }
            }
        }

        return $timeslots;
    }

    /**
     * Dозвращает список компаний со свободными таймслотами, входящих в группу group_id, исключая текущего юзера
     * @param  id $group_id id группы
     * @return array
     */
    public function getUsersFreeTimesByGroup($group_id)
    {
        global $DB, $USER;

        $userList = array();

        $statuses_free = $this->getStatusesFree();

        $meet_timeslots = $this->getMeetTimeslotsIds();

        if($this->getOption('GUESTS_GROUP') == $group_id){
            $guestField = "UF_MR";
            if($this->getOption('IS_HB')){
                $guestField = "UF_HB";
            }
          $sSQL = 'SELECT bu.WORK_COMPANY, bu.ID, ms.* FROM `b_user` bu INNER JOIN `b_user_group` bug ON bu.ID=bug.USER_ID LEFT JOIN `' . self::$sTableNameShedule.$this->app_id 
              . '` ms ON bu.ID=ms.USER_ID LEFT JOIN b_uts_user ON b_uts_user.VALUE_ID=bu.ID WHERE bug.GROUP_ID=' . $DB->ForSql($group_id) . ' AND b_uts_user.'.$guestField.'=1 GROUP BY bu.ID ORDER BY bu.WORK_COMPANY';
        }
        else{
          $sSQL = 'SELECT bu.WORK_COMPANY, bu.ID, ms.* FROM `b_user` bu INNER JOIN `b_user_group` bug ON bu.ID=bug.USER_ID LEFT JOIN `' . self::$sTableNameShedule.$this->app_id 
              . '` ms ON bu.ID=ms.USER_ID WHERE bug.GROUP_ID=' . $DB->ForSql($group_id) . ' GROUP BY bu.ID ORDER BY bu.WORK_COMPANY';
        }

        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        while ($data = $res->Fetch()) {
            if ($data['ID'] == $USER->GetID()) continue; // самого себя пропускаем
            // Если пользователя нет в таблице занятости, значит у него все слоты свободны
            if ($data['USER_ID'] === null) {
                $userList[$data['ID']]["TIMES"] = $meet_timeslots;
                /*foreach ($meet_timeslots as $timeslot_id) {
                    $timeslots[$timeslot_id][] = array(
                        'id' => $data['ID'],
                        'name' => $data['WORK_COMPANY']
                    );
                }*/
            } else {
                $statuses = $this->getTimslotsStatuses($data);
                foreach ($statuses as $timeslot_id => $status_id) {
                    if ( in_array($timeslot_id, $meet_timeslots) && in_array($status_id, $statuses_free)) {
                        $userList[$data['ID']]["TIMES"][]=$timeslot_id;
                        /*$timeslots[$timeslot_id][] = array(
                            'id' => $data['ID'],
                            'name' => $data['WORK_COMPANY']
                        );*/
                    }
                }
            }
        }

        return $userList;
    }

    /**
     * FIXME: это проверять можно по таблице занятости!!!
     */
    public function checkTimeslotIsFree($timeslot_id, $user_arr)
    {
        global $DB;

        if (!is_array($user_arr))
            $user_arr = array($user_arr);

        $statuses_rejected = array(self::STATUS_REJECTED, self::STATUS_TIMEOUT);


        $sSql = 'SELECT COUNT(*) AS count FROM ' . self::$sTableName . ' WHERE STATUS NOT IN (' . implode(',', $statuses_rejected) . ') AND TIMESLOT_ID=' . 
            $DB->ForSql($timeslot_id) . ' AND EXHIBITION_ID=' . $DB->ForSql($this->app_id) 
            . ' AND ( SENDER_ID IN (' . implode(',', $user_arr) . ') OR RECEIVER_ID IN (' . implode(',', $user_arr) . ') )';

        $res = $DB->Query($sSql, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        if ($data = $res->Fetch())
            return !($data['count'] > 0);

        return true;
    }

    /**
     * Подтверждаем запрос
     */
    public function confirmRequest($request)
    {
       $request['STATUS'] = self::$arStatuses[self::STATUS_CONFIRMED];

       return self::Update($request['ID'], $request);
    }

    /**
     * Отменяет запрос
     */
    public function rejectRequest($request)
    {
       $request['STATUS'] = self::$arStatuses[self::STATUS_REJECTED];

       return self::Update($request['ID'], $request);
    }

    /**
     * Отменяет запрос по таймауту
     */
    public function timeoutRequest($request)
    {
       $request['STATUS'] = self::$arStatuses[self::STATUS_TIMEOUT];

       return self::Update($request['ID'], $request);
    }

    /**
     * Возвращает последний запрос для таймслота
     * @param  int $timeslot_id 
     * @param  int $sender_id   
     * @param  int $receiver_id 
     * @return mixed
     */
    public function getActiveRequest($timeslot_id, $sender_id, $receiver_id)
    {
        $filter = array(
            "TIMESLOT_ID" => $timeslot_id,
            "SENDER_ID" => $sender_id,
            "RECEIVER_ID" => $receiver_id,
            "EXHIBITION_ID" => $this->app_id,
        );
        $rs = self::GetList(array('ID' => 'DESC'), $filter);
        if ($data = $rs->Fetch())
            return $data;

        return false;
    }


    /**
     * Dозвращает массив содержащий все таймслоты типа Встреча для компаний входящих в группу group_id. 
     * В массив не попадают запросы со статусом Отменен.
     * @param  id $group_id id группы
     * @return array
     */
    public function getAllMeetTimesByGroup($group_id)
    {
        global $DB;

 		if($this->getOption('GUESTS_GROUP') == $group_id){
            $guestField = "UF_MR";
            if($this->getOption('IS_HB')){
                $guestField = "UF_HB";
            }
         $sSQL = 'SELECT bu.WORK_COMPANY, bu.ID, ms.* FROM `b_user` bu INNER JOIN `b_user_group` bug ON bu.ID=bug.USER_ID LEFT JOIN `' . self::$sTableNameShedule.$this->app_id 
              . '` ms ON bu.ID=ms.USER_ID LEFT JOIN b_uts_user ON b_uts_user.VALUE_ID=bu.ID WHERE bug.GROUP_ID=' . $DB->ForSql($group_id) . ' AND b_uts_user.'.$guestField.'=1 GROUP BY bu.ID ORDER BY bu.WORK_COMPANY';
        }
		else{
		 $sSQL = 'SELECT bu.WORK_COMPANY, bu.ID, ms.* FROM `b_user` bu INNER JOIN `b_user_group` bug ON bu.ID=bug.USER_ID LEFT JOIN `' . self::$sTableNameShedule.$this->app_id 
			  . '` ms ON bu.ID=ms.USER_ID WHERE bug.GROUP_ID=' . $DB->ForSql($group_id) . ' GROUP BY bu.ID ORDER BY bu.WORK_COMPANY';
		}

        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        return $res;
    }

    /**
     * Собирает отмененные встречи
     * @return CDBResult
     */
    public function getRejectedRequests($group_id)
    {
        global $DB;

        $statuses_rejected = array(self::STATUS_REJECTED, self::STATUS_TIMEOUT);

        $sSQL = 'SELECT mrs.SENDER_ID as sender_id, mrs.RECEIVER_ID as receiver_id, mrs.ID AS id, mrs.TIMESLOT_ID AS timeslot_id,
            buts1.' . $this->getOption("REPR_PROP_CODE") . ' AS sender_rep, bu1.WORK_COMPANY as sender_company, 
            buts2.' . $this->getOption("REPR_PROP_CODE") . ' AS receiver_rep, bu2.WORK_COMPANY as receiver_company
            FROM ' . self::$sTableName . ' mrs 
            INNER JOIN b_user bu1 ON bu1.ID=mrs.SENDER_ID LEFT JOIN b_uts_user buts1 ON buts1.VALUE_ID=mrs.SENDER_ID INNER JOIN b_user_group bug1 ON bu1.ID=bug1.USER_ID
            INNER JOIN b_user bu2 ON bu2.ID=mrs.RECEIVER_ID LEFT JOIN b_uts_user buts2 ON buts2.VALUE_ID=mrs.RECEIVER_ID INNER JOIN b_user_group bug2 ON bu2.ID=bug2.USER_ID
            WHERE bug1.GROUP_ID=' . $DB->ForSql($group_id) . ' AND mrs.STATUS IN (' . implode(',', $statuses_rejected) . ') AND EXHIBITION_ID=' . $this->app_id;

        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        return $res;
    }

    /**
     * Возвращает массив содержащий все таймслоты типа Встреча для компаний входящих в группу group_id.
     * @param  id $group_id id группы
     * @return array
     */
    public function getAllMeetChooseByGroup($group_id)
    {
        global $DB;

        $timeslots = array();

        $meet_timeslots = $this->getMeetTimeslotsIds();
        if($this->getOption('GUESTS_GROUP') == $group_id){
            $guestField = "UF_MR";
            if($this->getOption('IS_HB')){
                $guestField = "UF_HB";
            }
         $sSQL = 'SELECT ms.* FROM `'.self::$sTableNameShedule.$this->app_id
         .'` ms LEFT JOIN `b_user_group` bug ON ms.USER_ID=bug.USER_ID LEFT JOIN b_uts_user ON b_uts_user.VALUE_ID=bug.USER_ID WHERE bug.GROUP_ID='
         .$DB->ForSql($group_id). ' AND b_uts_user.'.$guestField.'=1';
        }
        else{
         $sSQL = 'SELECT ms.* FROM `'.self::$sTableNameShedule.$this->app_id
         .'` ms LEFT JOIN `b_user_group` bug ON ms.USER_ID=bug.USER_ID WHERE bug.GROUP_ID='.$DB->ForSql($group_id);
        }

        

        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        return $res;
    }

    public function checkMeetingRights($receiverId, $senderId = 0)
    {
        global $USER;

        if(!$senderId) {
            $senderId = $USER->GetID();
        }

        $arSelect = ["ID", "UF_HB", "UF_MR", "GROUPS"];
        $arFilter = [
          "@ID" => array($senderId, $receiverId)
        ];

        $res = Main\UserTable::getList(Array(
          "select"=>$arSelect,
          "filter"=>$arFilter,
          'runtime' => [
            new Entity\ReferenceField(
              'GROUP',
              '\Bitrix\Main\UserGroupTable',
              ['=this.ID' => 'ref.USER_ID']
            ),
            new Entity\ExpressionField(
              'GROUPS',
              'GROUP_CONCAT(%s SEPARATOR \'##\')',
              ['GROUP.GROUP_ID'],
              [ 'fetch_data_modification' => function () {
                  return [function ($value) {
                      $elements = explode("##", $value);
                      return $elements;
                  }];
              }]
            ),
          ],
        ));

        $curUser = [];
        while ($arRes = $res->fetch()) {
            $curUser[$arRes["ID"]] = $arRes;
        }

        if(!isset($curUser[$senderId]) || !isset($curUser[$receiverId])) {
            return false;
        }

        $res = array(
            "SENDER" => false,
            "RECEIVER" => false
        );
        if(in_array($this->getOption('ADMINS_GROUP'), $curUser[$senderId]["GROUPS"]) || $USER->IsAdmin()) {
            $res["SENDER"] = true;
        } elseif(in_array($this->getOption('GUESTS_GROUP'), $curUser[$senderId]["GROUPS"]) && $curUser[$senderId]["UF_MR"]) {
            $res["SENDER"] = true;
        } elseif(in_array($this->getOption('MEMBERS_GROUP'), $curUser[$senderId]["GROUPS"])) {
            $res["SENDER"] = true;
        }

        if(in_array($this->getOption('ADMINS_GROUP'), $curUser[$receiverId]["GROUPS"]) || $USER->IsAdmin()) {
            $res["RECEIVER"] = true;
        } elseif(in_array($this->getOption('GUESTS_GROUP'), $curUser[$receiverId]["GROUPS"]) && $curUser[$receiverId]["UF_MR"]) {
            $res["RECEIVER"] = true;
        } elseif(in_array($this->getOption('MEMBERS_GROUP'), $curUser[$receiverId]["GROUPS"])) {
            $res["RECEIVER"] = true;
        }
        return $res;
    }

	/*=== дописываю методы под ТЗ ===*/
	/**
	* Метод находит все запросы на выставки
	*/
	function getAllMeet(){
		global $DB;
		$arName = array();
		$sSQL = 'SELECT * FROM '.self::$sTableName;
		$res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($data = $res->Fetch()){
			$meetsInfo[] = $data;
		}
		return $meetsInfo;
	}
	
	/**
	* Получаем массив выстовок
	*/
	function getMeetThis(){
		global $DB;
		$arName = array();
		$sSQL = 'SELECT * FROM '.self::$sTableName;
		$res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($data = $res->Fetch()){
			$meetsInfo[] = $data;
		}
		foreach($meetsInfo as $k=>$v){
			if(!in_array($v['EXHIBITION_ID'], $arName)){
				$arName[] = $v['EXHIBITION_ID'];
				$meetsMod[$k]['NAME_E'] = $v['EXHIBITION_ID'];
			}		
		}
		return $meetsMod;
	}
	
	/**
	*Метод находит название выставки в запросе
	*/
	function getNameEx($id){
		global $DB;
		if ($id < 1)
			return false;
		$sSQL = 'SELECT NAME, ID FROM '.self::$sTableNameSet. ' WHERE id = '.$id;
		$res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($data = $res->Fetch()){
			$namesEx = $data['NAME'];
		}
		return $namesEx;
	}
	
	/**
	*Метод находит таймслот в запросе по id запроса и id самого таймслота в нём
	*/
	function getTimeSlotAr($id_ex, $id){
		global $DB;
		if ($id_ex < 1 || $id < 1)
			return false;
		$sSQL = 'SELECT NAME, EXHIBITION_ID, ID FROM '.self::$sTableNameTimeS. ' WHERE EXHIBITION_ID = '.$id_ex. ' AND ID = '.$id;
		$res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($data = $res->Fetch()){
			$timeS = $data['NAME'];
		}
		return $timeS;
	}
	
	/**
	*Метод находит ошибки в встречах
	*/
	function getErrorsMeet($id){
		global $DB;
		if ($id < 1)
			return false;
		//получаем все запросы	
		$sSQL = 'SELECT * FROM '.self::$sTableName;
		$res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($data = $res->Fetch()){
			$allM[] = $data;
		}
		
		$sSQLSub = 'SELECT * FROM '.self::$sTableName.' WHERE id = '.$id;
		$resSub = $DB->Query($sSQLSub, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($dataSub = $resSub->Fetch()){
			$meetTake[] = $dataSub;
		}
		
		$erOtp = 0;
		$erPol = 0;
		$erUs = 0;
		/*echo '<pre>'; print_r($allM); echo '</pre>';
		echo '<pre>'; print_r($meetTake); echo '</pre>';
		*/
		foreach($allM as $k=>$v){
			if($v['SENDER_ID'] == $meetTake[0]['SENDER_ID'] && $v['TIMESLOT_ID'] == $meetTake[0]['TIMESLOT_ID'] && $v['STATUS'] != 'rejected' && $meetTake[0]['STATUS'] != 'rejected' && $v['STATUS'] != 'timeout' && $meetTake[0]['STATUS'] != 'timeout'){
				$erOtp++;
			}
			if($v['RECEIVER_ID'] == $meetTake[0]['RECEIVER_ID'] && $v['TIMESLOT_ID'] == $meetTake[0]['TIMESLOT_ID'] && $v['STATUS'] != 'rejected' && $meetTake[0]['STATUS'] != 'rejected' && $v['STATUS'] != 'timeout' && $meetTake[0]['STATUS'] != 'timeout'){
				$erPol++;
			}
			/*echo '<pre>'; print_r($meetTake[0]); echo '</pre>';
			echo '<pre>'; print_r($v); echo '</pre>';
			echo '<pre>'; print_r($erOtp); echo '</pre>';
			echo '<hr>';*/
		}
		/*echo '<pre>'; print_r($erOtp); echo '</pre>';
		echo '<pre>'; print_r($erPol); echo '</pre>';
		*/
		if($erOtp > 1){
			return self::ERROR_OTP_SEND_MES;
		}elseif($erPol > 1){
			return self::ERROR_POL_SEND_MES;
		}elseif(in_array(self::getUserGroubSimple($meetTake[0]['RECEIVER_ID']), array(19, 22, 23, 24, 25, 26, 27)) && in_array(self::getUserGroubSimple($meetTake[0]['SENDER_ID']), array(19, 22, 23, 24, 25, 26, 27))){
			return self::ERROR_USER_IS_GUEST;
		}else{
			return self::ERROR_NO_MEET;
		}
	}
	
	/**
	//Получаем id группы по id пользователя (класс битрикса тут не вызвать)
	*/
	function getUserGroubSimple($user_id){
		global $DB;
		if ($user_id < 1)
			return false;
		$sSQL = 'SELECT GROUP_ID FROM b_user_group WHERE USER_ID = '.$user_id;
		$resSub = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($dataSub = $resSub->Fetch()){
			$userGr = $dataSub['GROUP_ID'];
		}
		if($userGr != ''){
			return $userGr;
		}else{
			return false;
		}
	}
	


}