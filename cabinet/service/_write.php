<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Write a message");

$APPLICATION->IncludeComponent(
	"rarus:messages.new",
	"",
	Array(
		"HLID" => "2",
		"EID" => "360",
		"COPY_TO_OUTBOX" => "N",
		"SEND_EMAIL" => "N",
		"MID" => $_REQUEST["mes"],
	    "UID" => $_REQUEST["id"],
	    "SET_TITLE" => "N",
    	"URL_TEMPLATES_HLM_LIST" => "/cabinet/messages/#FCODE#/",
		"URL_TEMPLATES_HLM_READ" => "/cabinet/service/_read.php?MID=#MID#",
		"URL_TEMPLATES_HLM_NEW" => "/cabinet/service/_write.php?id=#UID#",
		"URL_TEMPLATES_HLM_COMPANY_VIEW" => "/members/#CID#/",

	),
false
);


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>