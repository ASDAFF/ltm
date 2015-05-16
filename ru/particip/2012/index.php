<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Участники 2012");
?><h1>Участники 2012</h1>
 <?$APPLICATION->IncludeComponent(
	"btm:user.list",
	"",
	Array(
		"PATH_TO_KAB" => "",
		"AUTH_PAGE" => "",
		"GROUP_ID" => "PREV",
		"USER" => "18",
		"FORM_ID" => "12"
	)
);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>