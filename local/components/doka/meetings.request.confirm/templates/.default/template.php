<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<?if(!$arResult['IS_ACTIVE'] && $arResult['USER_TYPE'] != 'ADMIN'):?>
	<?echo GetMessage($arResult['USER_TYPE'] . '_EXHIBITION_BLOCKED');?>
<?elseif(isset($arResult['ERROR_MESSAGE'])):?>
	<? echo implode('<br>', $arResult['ERROR_MESSAGE']); ?>
<?elseif($arResult['CONFIRM_SUCCESS']):?>
	<?
	switch ($arResult['USER_TYPE']) {
		case 'ADMIN':
			include_once(dirname(__FILE__) . '/admin.php');
			break;
		case 'PARTICIP':
			include_once(dirname(__FILE__) . '/particip.php');
			break;
		case 'GUEST':
			include_once(dirname(__FILE__) . '/guest.php');
	}
	?>
	<script type="text/javascript">
		setTimeout( function() { window.close(); }, 5000);
	</script>
	<?if(empty($arParams["RELOAD"]) || $arParams["RELOAD"] != 'N'):?>
		<script type='text/javascript'>top.opener.document.location.reload();</script>
	<?endif;?>
<?endif;?>
<?
//var_dump($arResult);
?>