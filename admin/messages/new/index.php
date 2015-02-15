<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Write a message");
?>
<?
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
$page = "/admin/" . $exhibCode . "/messages/";
?>
<div class="menu">
    <ul>
        <li><a href="<?= $page?>inbox/" class="custom-buttom">Входящие</a></li>
        <li><a href="<?= $page?>sent/" class="custom-buttom" >Исходящие</a></li>
        <li class="active" ><a href="<?= $page?>new/" class="custom-buttom">Написать</a></li>
    </ul>
</div>

<?$APPLICATION->IncludeComponent(
	"rarus:messages.admin.write",
	"",
	Array(
		"PATH_TO_KAB" => "/admin/",
		"AUTH_PAGE" => "/admin/login.php",
        "IBLOCK_ID" => "15",
        "EXHIB_CODE" => "$exhibCode",
		"MESSAGE" => "",
	    "HLID" => "2",
		"COPY_TO_OUTBOX" => "N",
		"SEND_EMAIL" => "Y",
	),
false
);?>

<?
/*
$APPLICATION->IncludeComponent(
	"rarus:admin.messages.write",
	"",
	Array(
		"PATH_TO_KAB" => "/admin/",
		"AUTH_PAGE" => "/admin/login.php",
        "IBLOCK_ID" => "15",
        "EXHIB_CODE" => $exhibCode,
		"MESSAGE" => ""
	),
false
);
*/
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>