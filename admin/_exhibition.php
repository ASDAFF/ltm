<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("������� ��������������");
?>
<? $exhibCode = htmlspecialchars($_REQUEST["EXHIBIT_CODE"]);?>

<a href="/admin/<?= $exhibCode?>/messages/">���������</a>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>