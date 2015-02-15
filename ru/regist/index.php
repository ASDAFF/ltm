<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?> 
 <?$APPLICATION->IncludeComponent(
	"btm:form.result.new_ltm",
	"template",
	Array(
		"SEF_MODE" => "Y",
		"WEB_FORM_ID" => "4",
		"LIST_URL" => "",
		"EDIT_URL" => "",
		"SUCCESS_URL" => "/ru/regist/tnx/",
		"CHAIN_ITEM_TEXT" => "",
		"CHAIN_ITEM_LINK" => "",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"USE_EXTENDED_ERRORS" => "Y",
		"REGIST_USER" => "Y",
		"EMAIL_FIELD" => "form_email_159",
		"PASSWORD_FIELD" => "form_password_168",
		"LOGIN_FIELD" => "form_text_167",
		"REGIST_GROUP" => "5",
        "EVENING_GROUP" => "13",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"SEF_FOLDER" => "/",
		"VARIABLE_ALIASES" => Array(
		)
	)
);?>

<br />
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>