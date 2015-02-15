<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Read a message");
?>
<? 
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
$page = "/admin/" . $exhibCode . "/messages/";

$arExhib = CHLMFunctions::GetExhibByCode($exhibCode);
$exhibID = $arExhib["ID"];
?>
<? $APPLICATION->IncludeComponent(
	"rarus:messages.read",
	"",
	Array(
		"SET_TITLE" => "Y",
		"SET_NAVIGATION" => "Y",
		"URL_TEMPLATES_HLM_LIST" => "/admin/messages/#FCODE#/",
		"URL_TEMPLATES_HLM_READ" => "/admin/service/read.php?MID=#MID#",
		"URL_TEMPLATES_HLM_NEW" => "/admin/service/write.php?id=#UID#&EXHIBIT_CODE={$exhibCode}",
		"URL_TEMPLATES_HLM_COMPANY_VIEW" => "/members/#CID#/",
        "DATE_FORMAT" => "Y, dM",
        "DATE_TIME_FORMAT" => "g:i A",
		"MID" => $_REQUEST["mes"],
        "HLID" => "2",
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>