<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("My registration info");
	$rsUser = CUser::GetByID($USER->GetID());
	$arUser = $rsUser->Fetch();
?><?$APPLICATION->IncludeComponent(
	"btm:form.user.edit",
	"guest",
	Array(
		"ADMIN" => "1",
		"GROUP_ID" => "6",
		"AUTH_PAGE" => "/ru/personal/login.php",
        "FORM_ID"	=> '4',
		"EVENT_TEMP" => "PARTICIP_CHANGE",
        "IS_ACTIVE" => "N"
	),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>