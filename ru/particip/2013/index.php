<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("��������� 2013");
?><h1>��������� 2013</h1>
 <?$APPLICATION->IncludeComponent(
	"btm:user.list",
	"",
	Array(
		"PATH_TO_KAB" => "",
		"AUTH_PAGE" => "",
		"GROUP_ID" => "CURE",
		"USER" => "4",
		"FORM_ID" => "1"
	)
);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>