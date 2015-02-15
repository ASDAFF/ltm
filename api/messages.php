<?php
/**
 * Created by IntelliJ IDEA.
 * User: dimich
 * Date: 28/02/14
 * Time: 22:31
 */

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/forum/include.php");


switch($mode) {
    case 'compose':
    {
        $arFields = Array(
            "AUTHOR_ID"    => CUser::GetId(),
            "POST_MESSAGE" => $message,
            "POST_SUBJ" => $subj,
            "USER_ID"      => $user_id,
            "FOLDER_ID"    => 1,
            "IS_READ"      => "N",
            "AUTHOR_NAME"  => CUser::GetLogin()
        );
        $ID = CForumPrivateMessage::Send($arFields);
        $result = array();
        $result['status'] = true;
        $result['message'] = '';
        if (IntVal($ID)<=0) {
            $result['status'] = false;
        }
    }
        break;
    case 'list':
        $db_res = CForumPrivateMessage::GetList(array("ID"=>"DESC"),array("FOLDER_ID"=>"1", "RECIPIENT_ID"=>CUser::getId()) ,"" ,"1");
        $messages = array();
        while($item = $db_res->Fetch())
            array_push($messages, $item);

        echo json_encode($messages);
        break;
}

