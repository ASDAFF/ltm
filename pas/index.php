<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<script src = "/pas/pas.js"></script>
<link href="/pas/pas.css" type="text/css" rel="stylesheet" />
<form id = "pas_form">
	<input type = "text" placeholder = "������� ���� ���� ������" class = "depas" />
	<input type = "button" value = "������������" class = "pasbut" />
	<input type = "text" placeholder = "�������������� ������" class = "enpas" />
</form>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>