<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<script src = "/pas/pas.js"></script>
<link href="/pas/pas.css" type="text/css" rel="stylesheet" />
<form id = "pas_form">
	<input type = "text" placeholder = "Введите сюда свой пароль" class = "depas" />
	<input type = "button" value = "Расшифровать" class = "pasbut" />
	<input type = "text" placeholder = "Расшифрованный пароль" class = "enpas" />
</form>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>