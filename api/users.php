<?php
/**
 * Created by IntelliJ IDEA.
 * User: dimich
 * Date: 11/03/14
 * Time: 18:05
 */

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$by = "";
$order = "";
$db_res = CUser::GetList($by, $order, "" ,array("FIELDS" => array("ID","LOGIN","IS_ONLINE","NAME","LAST_NAME","WORK_COMPANY")));
$users = array();
while($item = $db_res->Fetch())
    array_push($users, $item);

echo json_encode($users);
