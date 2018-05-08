<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$page_start = microtime();

global $USER;

echo "sdg ";

CModule::IncludeModule('form');
CModule::IncludeModule('iblock');
$filter = Array("!UF_AUTH_TOKEN"=>false);
$rsUsers = CUser::GetList(($by="ID"), ($order="desc"), $filter);
while($arUsers = $rsUsers->GetNext()) {
    echo "<br>".$arUsers["ID"];

    $user = new CUser;
    $fieldsUP = Array("UF_AUTH_TOKEN" => array());
    $user->Update($arUsers["ID"], $fieldsUP);
$cnt++;
}

echo "count: ".$cnt;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>