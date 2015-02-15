<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Read a message");

$APPLICATION->IncludeComponent(
	"rarus:messages.list",
	"admin_recive",
	Array(
		"HLID" => "2",
		"EID" => "361",
		"FID" => "3",
	    "SET_TITLE" => "N",
		"PM_PER_PAGE" => "10",
		"DATE_FORMAT" => "d.m.Y",
		"DATE_TIME_FORMAT" => "H:m:i",
        "URL_TEMPLATES_HLM_LIST" => "/cabinet/messages/#FCODE#/",
        "URL_TEMPLATES_HLM_READ" => "/cabinet/service/_read.php?MID=#MID#",
        "URL_TEMPLATES_HLM_NEW" => "/cabinet/service/_write.php?id=#UID#",
        "URL_TEMPLATES_HLM_COMPANY_VIEW" => "/members/#CID#/",

	),
false
);?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>