<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Register for LTM 2014");
?> <?$APPLICATION->IncludeComponent(
	"btm:form.result.new_year",
	"particip",
	Array(
		"ADMIN" => "1",
		"GROUP_ID" => "9",
		"AUTH_PAGE" => "/personal/login.php",
        "FORM_ID"	=> '10',
        "FORM_OLD_ID"	=> '1',
		"EVENT_TEMP" => "PARTICIP_CHANGE"
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>