<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Read a message");

$APPLICATION->IncludeComponent(
	"rarus:messages.read",
	"",
	Array(
		"SET_TITLE" => "Y",
		"SET_NAVIGATION" => "Y",
		"URL_TEMPLATES_HLM_LIST" => "/cabinet/messages/#FCODE#/",
		"URL_TEMPLATES_HLM_READ" => "/cabinet/service/_read.php?MID=#MID#",
		"URL_TEMPLATES_HLM_NEW" => "/cabinet/service/_write.php?id=#UID#",
		"URL_TEMPLATES_HLM_COMPANY_VIEW" => "/members/#CID#/",
        "DATE_FORMAT" => "Y, dM",
        "DATE_TIME_FORMAT" => "g:i A",
		"MID" => $_REQUEST["mes"],
        "HLID" => "2",
	    "EID" => "360",
	),
false
);?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>