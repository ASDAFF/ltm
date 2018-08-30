<table width="100%" border="0" cellspacing="0" cellpadding="5" class="form_edit">
		<tr>
        	<td width="130">From:</td>
			<td>
			<?=$arResult['SENDER']['company_name']?><br />
			<?=$arResult['SENDER']['repr_name']?>
            </td>
		</tr>
		<tr>
        	<td>To:</td>
			<td>
			<?=$arResult['RECEIVER']['company_name']?><br />
			<?=$arResult['RECEIVER']['repr_name']?>
            </td>
		</tr>
		<tr>
        	<td>Time:</td>
			<td><?=$arResult['TIMESLOT']['name']?></td>
		</tr>
     </table>
     <p><input type="submit" name="submit" value="Send"/></p>
</form>