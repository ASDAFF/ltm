<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Написать сообщение");
?>
<? 
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
$page = "/admin/" . $exhibCode . "/messages/";

$arExhib = CHLMFunctions::GetExhibByCode($exhibCode);
$exhibID = $arExhib["ID"];
?>


<?
$APPLICATION->IncludeComponent(
	"rarus:messages.new",
	"admin",
	Array(
		"HLID" => "2",
		"EID" => $exhibID,
		"COPY_TO_OUTBOX" => "Y",
		"SEND_EMAIL" => "Y",
		"MID" => $_REQUEST["mes"],
	    "UID" => $_REQUEST["id"],
	    "SET_TITLE" => "N",
        "URL_TEMPLATES_HLM_LIST" => "/admin/".$exhibCode."/messages/#FCODE#/",
        "URL_TEMPLATES_HLM_READ" => "/admin/service/read.php?MID=#MID#",
        "URL_TEMPLATES_HLM_NEW" => "/admin/service/write.php?id=#UID#",
        "URL_TEMPLATES_HLM_COMPANY_VIEW" => "/members/#CID#/",
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>