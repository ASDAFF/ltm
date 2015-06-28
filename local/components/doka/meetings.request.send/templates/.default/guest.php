<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<form action="" method="POST">
	<input type="hidden" name="time" value="<?=$arResult['TIMESLOT']['id']?>" />
	<input type="hidden" name="id" value="<?=$arResult['SENDER']['company_id']?>" />
	<input type="hidden" name="to" value="<?=$arResult['RECEIVER']['company_id']?>" />
	<input type="hidden" name="app" value="<?=$arResult['APP']?>" />
    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="form_edit">
		<tr>
        	<td width="130">От:</td>
			<td>
			<?=$arResult['SENDER']['company_name']?><br />
			<?=$arResult['SENDER']['repr_name']?>
            </td>
		</tr>
		<tr>
        	<td>Кому:</td>
			<td>
			<?=$arResult['RECEIVER']['company_name']?><br />
			<?=$arResult['RECEIVER']['repr_name']?>
            </td>
		</tr>
		<tr>
        	<td>Время:</td>
			<td><?=$arResult['TIMESLOT']['name']?></td>
		</tr>
     </table>
     <p><input type="submit" name="submit"/></p>
</form>
