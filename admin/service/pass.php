<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("��������� ������");
?><?$APPLICATION->IncludeComponent(
	"rarus:admin.user.password",
	"",
	Array(
		"PATH_TO_KAB" => "/admin/",
		"GROUP_ID" => "1",
		"USER" => $_REQUEST["uid"],
	)
);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>