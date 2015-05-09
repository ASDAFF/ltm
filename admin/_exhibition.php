<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Кабинет администратора");
?>
<? $exhibCode = htmlspecialchars($_REQUEST["EXHIBIT_CODE"]);?>

<a href="/admin/<?= $exhibCode?>/messages/">Сообщения</a>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>