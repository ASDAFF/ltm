<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<form action="" method="POST">
	<input type="hidden" name="timeslot_id" value="<?=$arResult['TIMESLOT']['id']?>" />
	<input type="hidden" name="sender_id" value="<?=$arResult['SENDER']['company_id']?>" />
	<input type="hidden" name="receiver_id" value="<?=$arResult['RECEIVER']['company_id']?>" />
	From: <?=$arResult['SENDER']['repr_name']?> <?=$arResult['SENDER']['company_name']?><BR>
	To: <?=$arResult['RECEIVER']['repr_name']?> <?=$arResult['RECEIVER']['company_name']?><br>
	TIME: <?=$arResult['TIMESLOT']['name']?>
	<input type="submit" name="submit"/>
</form>
