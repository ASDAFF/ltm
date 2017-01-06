<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<?if(isset($arResult['ERROR_MESSAGE'])):?>
	<? echo implode('<br>', $arResult['ERROR_MESSAGE']); ?>
<?else:?>
	<? if($arResult['TO_RESERVE'] == 'Y'){
		?><p>Selected timeslot will be reserved for your purpose and won't be available to make appointment request until you release it. Window will close after 5 sec.</p><?
	}
	else{
		?><p>You're releasing timeslot and it can be used for to make an appointment. Window will close after 5 sec.</p><?
	}?>
	<script type="text/javascript">
		setTimeout( function() { window.close(); }, 5000);
	</script>
	<?if(empty($arParams["RELOAD"]) || $arParams["RELOAD"] != 'N'):?>
		<script type='text/javascript'>top.opener.document.location.reload();</script>
	<?endif;?>
<?endif;?>