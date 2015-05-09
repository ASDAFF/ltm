<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Luxury Travel Mart");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
?>
<? 
$mes =array();

$mes["RU"]["TITLE"] = "Восстановление пароля";
$mes["EN"]["TITLE"] = "Password recovery";

$mes["RU"]["NO_LOGIN"] = "Вы должны ввести свой логин!";
$mes["EN"]["NO_LOGIN"] = "You must enter your login!";

$mes["RU"]["ER_LOGIN"] = "Логин задан неправильно!";
$mes["EN"]["ER_LOGIN"] = "Login is invalid!";

$mes["RU"]["PASS_SEND"] = "Ваш пароль отправлен Вам на e-mail.";
$mes["EN"]["PASS_SEND"] = "Your password has been sent to your e-mail.";

$mes["RU"]["ENTER_LOGIN"] = "Введите сюда свой логин";
$mes["EN"]["ENTER_LOGIN"] = "Enter your login here";

$mes["RU"]["REMIND"] = "Напомнить";
$mes["EN"]["REMIND"] = "Remind";

$lang = strtoupper(LANGUAGE_ID);

?>
<script type="text/javascript">

obMes = {
	noLogin:"<?= $mes[$lang]["NO_LOGIN"]?>",
	erLogin:"<?= $mes[$lang]["ER_LOGIN"]?>",
	passSend:"<?= $mes[$lang]["PASS_SEND"]?>",
	enterLogin:"<?= $mes[$lang]["ENTER_LOGIN"]?>",
	remind:"<?= $mes[$lang]["REMIND"]?>"
};
</script>
<link href="/remind/remind.css" type="text/css" rel="stylesheet" />
<h2><?= $mes[$lang]["TITLE"]?></h2>
<form id = "pas_form">
	<input type = "text" placeholder = "<?= $mes[$lang]["ENTER_LOGIN"]?>" class = "repas" />
	<input type = "button" value = "<?= $mes[$lang]["REMIND"]?>" class = "repasbut" />
	<span class = "pas_yes"></span>
</form>




<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>