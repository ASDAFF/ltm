<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("My registration info");
	$rsUser = CUser::GetByID($USER->GetID());
	$arUser = $rsUser->Fetch();
?><?$APPLICATION->IncludeComponent(
	"btm:form.user.edit",
	"particip",
	Array(
		"ADMIN" => "1",
		"GROUP_ID" => "4",
		"AUTH_PAGE" => "/personal/login.php",
        "FORM_ID"	=> '1',
		"EVENT_TEMP" => "PARTICIP_CHANGE",
        "IS_ACTIVE" => "Y"
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>