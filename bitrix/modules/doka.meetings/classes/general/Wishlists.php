<?php
namespace Doka\Meetings;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/doka.meetings/classes/mysql/Entity/Wishlist.php");


use Doka\Meetings\Entity\Wishlist as DokaWishlist;
IncludeModuleLangFile(__FILE__);

class Wishlists extends DokaWishlist
{
    public function __construct($exhibition_id)
    {
        // \CModule::IncludeModule("iblock");
        $this->options = array();

        $this->app_id = (int)$exhibition_id;
        if ($this->app_id <= 0) die('WRONG APP ID');
    }

    public function getOption($name)
    {
        if (isset($this->options[$name]))
            return $this->options[$name];

        return false;
    }

    function GetByID($ID)
    {
        return self::GetList(Array(), Array("ID" => $ID));
    }

    public function getWishlists($user_id)
    {
        $user_id = (!$user_id) ? $this->user_id : (int)$user_id;
        if ($user_id <= 0) 
            return false;

        global $DB;

        $results = array(
            'WISH_IN' => array(),
            'WISH_OUT' => array(),
        );

        $sSQL = 'SELECT bu1.WORK_COMPANY as sender_name, bu1.ID as sender_id, bu1.NAME as sender_rep_name, bu1.LAST_NAME as sender_rep_lname, bu2.WORK_COMPANY as receiver_name, bu2.ID as receiver_id, bu2.NAME as receiver_rep_name, bu2.LAST_NAME as receiver_rep_lname' .
            ' FROM `' . self::$sTableName . '` wl INNER JOIN `b_user` bu1 ON bu1.ID=wl.SENDER_ID INNER JOIN `b_user` bu2 ON bu2.ID=wl.RECEIVER_ID' . 
            ' WHERE wl.EXHIBITION_ID=' . $DB->ForSql($this->app_id) . ' AND (RECEIVER_ID=' . $DB->ForSql($user_id) . ' OR SENDER_ID=' . $DB->ForSql($user_id) . ')';

        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        while ($data = $res->Fetch()) {
            if ($data['sender_id'] == $user_id) {
                $results['WISH_IN'][$data['receiver_id']] = array(
                    'company_id' => $data['receiver_id'],
                    'company_name' => $data['receiver_name'],
                    'company_rep' => $data['receiver_rep_name']." ".$data['receiver_rep_lname'],
                );
            } else {
                $results['WISH_OUT'][$data['sender_id']] = array(
                    'company_id' => $data['sender_id'],
                    'company_name' => $data['sender_name'],
                    'company_rep' => $data['sender_rep_name']." ".$data['sender_rep_lname'],
                );
            }
        }
        //var_dump($results);

        return $results;
    }

    public function getWishlistsFull($user_id, $form_id, $result_id, $fio)
    {
        $user_id = (!$user_id) ? $this->user_id : (int)$user_id;
        if ($user_id <= 0) 
            return false;

        global $DB;

        $results = array(
            'WISH_IN' => array(),
            'WISH_OUT' => array(),
        );

        $sSQL = 'SELECT bu1.WORK_COMPANY as sender_name, bu1.ID as sender_id, bu1.NAME as sender_rep_name, bu1.LAST_NAME as sender_rep_lname, buts1.'.$result_id.' as sender_repr_fio, bu2.WORK_COMPANY as receiver_name, bu2.ID as receiver_id, bu2.NAME as receiver_rep_name, bu2.LAST_NAME as receiver_rep_lname, buts2.'.$result_id.' as receiver_repr_fio' .
            ' FROM `' . self::$sTableName . '` wl INNER JOIN `b_user` bu1 ON bu1.ID=wl.SENDER_ID INNER JOIN `b_user` bu2 ON bu2.ID=wl.RECEIVER_ID LEFT JOIN b_uts_user buts1 ON buts1.VALUE_ID=bu1.ID  LEFT JOIN b_uts_user buts2 ON buts2.VALUE_ID=bu2.ID' . 
            ' WHERE wl.EXHIBITION_ID=' . $DB->ForSql($this->app_id) . ' AND (RECEIVER_ID=' . $DB->ForSql($user_id) . ' OR SENDER_ID=' . $DB->ForSql($user_id) . ')';

        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        while ($data = $res->Fetch()) {
            if($data['sender_id'] == $user_id){
                    $arAnswer = \CFormResult::GetDataByID(
                        $data['receiver_repr_fio'], 
                        array(),  // вопрос "Какие области знаний вас интересуют?" 
                        $arResultTmp, 
                        $arAnswer2);
                    $results['WISH_IN'][$data['receiver_id']] = array(
                        'company_id' => $data['receiver_id'],
                        'company_name' => $data['receiver_name'],
                        'company_rep' => trim($arAnswer2[$fio[0][0]][$fio[0][1]]["USER_TEXT"])." ".trim($arAnswer2[$fio[1][0]][$fio[1][1]]["USER_TEXT"]),
                    );
            }
            else {
                    $arAnswer = \CFormResult::GetDataByID(
                        $data['sender_repr_fio'], 
                        array(),  // вопрос "Какие области знаний вас интересуют?" 
                        $arResultTmp, 
                        $arAnswer2);
                    $results['WISH_OUT'][$data['sender_id']] = array(
                        'company_id' => $data['sender_id'],
                        'company_name' => $data['sender_name'],
                        'company_rep' => trim($arAnswer2[$fio[0][0]][$fio[0][1]]["USER_TEXT"])." ".trim($arAnswer2[$fio[1][0]][$fio[1][1]]["USER_TEXT"]),
                    );
            }
        }


        //var_dump($results);

        return $results;
    }

    public function getInWishlists($user_id)
    {
        $user_id = (!$user_id) ? $this->user_id : (int)$user_id;
        if ($user_id <= 0) 
            return false;

        global $DB;

        $sSQL = 'SELECT bu1.WORK_COMPANY as sender_name, bu1.ID as sender_id, bu1.NAME as sender_rep_name, bu1.LAST_NAME as sender_rep_lname, bu2.WORK_COMPANY as receiver_name, bu2.ID as receiver_id, bu2.NAME as receiver_rep_name, bu2.LAST_NAME as receiver_rep_lname' .
            ' FROM `' . self::$sTableName . '` wl INNER JOIN `b_user` bu1 ON bu1.ID=wl.SENDER_ID INNER JOIN `b_user` bu2 ON bu2.ID=wl.RECEIVER_ID' . 
            ' WHERE wl.EXHIBITION_ID=' . $DB->ForSql($this->app_id) . ' AND (RECEIVER_ID=' . $DB->ForSql($user_id) . ')';

        $res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);

        while ($data = $res->Fetch()) {
            $results[$data['receiver_id']] = array(
                    'company_id' => $data['receiver_id'],
                    'company_name' => $data['receiver_name'],
                    'company_rep' => $data['receiver_rep_name']." ".$data['receiver_rep_lname'],
                );
        }
        
        //var_dump($results);

        return $results;
    }
}