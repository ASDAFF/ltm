<?php require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
global $USER;

$arParams["GROUPS_ID"] = array(48);

$arUserFilter = array("GROUPS_ID"=>$arParams["GROUPS_ID"], "ACTIVE"=>"Y");
$rs = CUser::GetList(($by = "work_company"), ($order = "asc"), $arUserFilter, array("SELECT"=>array("UF_*"), "FIELDS"=>array("ID", "LOGIN")));
while($arUser = $rs->Fetch()){
    $pasAr = array('d', 'p', '!', 'l', '9', '#', 'm', 'A', 'r', '2');
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
}
?>