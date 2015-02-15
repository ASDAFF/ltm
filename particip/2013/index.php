<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Participants 2013");
?><h1>Participants 2013</h1>
 <?$APPLICATION->IncludeComponent(
	"btm:user.list",
	"",
	Array(
		"PATH_TO_KAB" => "",
		"AUTH_PAGE" => "",
		"GROUP_ID" => "PREV",
		"USER" => "18",
		"FORM_ID" => "14"
	)
);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>