<?php require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
global $USER;

set_time_limit(0);
ignore_user_abort(true);
session_write_close();

$arParams["GROUPS_ID"] = array(48);

$arUserFilter = array("GROUPS_ID"=>$arParams["GROUPS_ID"], "ACTIVE"=>"Y");
$rs = CUser::GetList(($by = "work_company"), ($order = "asc"), $arUserFilter, array("SELECT"=>array("UF_*"), "FIELDS"=>array("ID", "LOGIN")));
while($arUser = $rs->Fetch()){
    $pasAr = array('d', 'p', 'l', '9', 'K', 'm', 'A', 'r', '2');
    shuffle($pasAr);
    $password = trim(implode("", $pasAr));
    $fields = array(
        "ADMIN_NOTES"    => $password,
        "PASSWORD"       => $password,
        "CONFIRM_PASSWORD" => $password,
        "UF_PAS" => makePassCode($password),
    );
    $user = new CUser;
    $user->Update($arUser["ID"], $fields);
    $strError = $user->LAST_ERROR;
    echo "<pre>";
    print_r($fields);
    echo "</pre>";
}
?>