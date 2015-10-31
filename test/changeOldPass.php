<?
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$filter = Array("<=ID"=>4886);
$params = array("SELECT"=>array("UF_PAS"), "FIELDS"=>array("ID","EMAIL"));
$rsUsers = CUser::GetList(($by="ID"), ($order="desc"), $filter, $params);
while ($arUser = $rsUsers->Fetch()):
    if($arUser["ID"] < 4887){
        $arUser["PASSWORD"] = makePassDeCodeOld($arUser["UF_PAS"]);
        $arUser["PASSWORD_NEW"] = makePassCode($arUser["PASSWORD"]);
        echo "<pre>";
        print_r($arUser);
        echo "</pre>";

        $user = new CUser;
        $fields = array("UF_PAS"=>$arUser["PASSWORD_NEW"]);
        $user->Update($arUser["ID"], $fields);/**/
    }

endwhile;
?>