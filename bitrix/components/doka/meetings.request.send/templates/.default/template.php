<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<?if(!$arResult['IS_ACTIVE'] && $arResult['USER_TYPE'] != 'ADMIN'):?>
	<?echo GetMessage($arResult['USER_TYPE'] . '_EXHIBITION_BLOCKED');?>
<?elseif(isset($arResult['ERROR_MESSAGE'])):?>
	<? echo implode('<br>', $arResult['ERROR_MESSAGE']); ?>
<?elseif(isset($arResult['REQUEST_SENT'])):?>
	<? if (isset($arResult['FORM_ERROR'])){
	switch ($arResult['USER_TYPE']) {
		case 'ADMIN':
		case 'PARTICIP':
		echo "<p><b>Error sending request. Time slot is not available.</b></p> <p>Window will close after 5 sec.</p>";
			break;
		case 'GUEST':
		echo "<p><b>Ошибка отправки. Слот занят.</b></p><p> Закрытие через 5 секунд.</p>";
	}}
	else{
		switch ($arResult['USER_TYPE']) {
		case 'ADMIN':
		case 'PARTICIP':
			echo "<p>Request sent. Window will close after 5 sec.</p>";
			break;
		case 'GUEST':
			echo "<p>Запрос успешно отправлен. Закрытие через 5 секунд.</p>";
	}}?>
	<script type="text/javascript">
		setTimeout( function() { window.close(); }, 5000);
	</script>
	<script type='text/javascript'>top.opener.document.location.reload();</script>
<?else:?>
	<?
	switch ($arResult['USER_TYPE']) {
		case 'ADMIN':
			include_once(dirname(__FILE__) . '/particip.php');
			break;
		case 'PARTICIP':
			include_once(dirname(__FILE__) . '/particip.php');
			break;
		case 'GUEST':
			include_once(dirname(__FILE__) . '/guest.php');
	}
	?>
<?endif;?>
<?
//var_dump($arResult);
?>