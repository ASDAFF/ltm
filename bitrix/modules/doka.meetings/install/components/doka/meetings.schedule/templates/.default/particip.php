<? if(!$arResult['IS_ACTIVE']):?>
	<?=$arResult['MESSAGE'];?>
<? endif;?>

<table class="table">
	<tr>
		<th>Time</th>
		<th>Company</th>
		<th>Representative</th>
		<th>Status</th>
		<th>Notes</th>
	</tr>
	<? foreach ($arResult['SCHEDULE'] as $item):?>
		<tr>
			<td><?=$item['name']?></td>
			<?if (isset($item['company_name'])):?>
				<td><?=$item['company_name']?></td>
				<td><?=$item['company_rep']?></td>
			<?else:?>
				<td colspan="2">
					<?if (!count($item['list'])):?>
						Все слоты заняты
					<?else:?>
						<select name="company_id" class="form-control">
							<?foreach ($item['list'] as $company):?>
								<option value="<?=$company['id']?>"><?=$company['name']?></option>
							<?endforeach;?>
						</select>
					<?endif;?>
				</td>
			<?endif;?>
			<td>
				<?
				switch($item['status']) {
					case 'confirmed':
						echo 'confirmed';
						break;
					case 'process':
						if ($item['sent_by_you']):?>
							<a onClick="window.open('<?=$arResult['REJECT_REQUEST_LINK']?>?timeslot_id=<?=$item['timeslot_id']?>&receiver_id=<?=$item['company_id']?>', 'newWindow', 'width=620,height=430,resizable=yes,scrollbars=yes,status=yes'); return false;" 
								href="<?=$arResult['REJECT_REQUEST_LINK']?>?timeslot_id=<?=$item['timeslot_id']?>&receiver_id=<?=$item['company_id']?>">Отменить</a>
						<?else:?>
							<a onClick="window.open('<?=$arResult['REJECT_CONFIRM_LINK']?>?timeslot_id=<?=$item['timeslot_id']?>&receiver_id=<?=$item['company_id']?>', 'newWindow', 'width=620,height=430,resizable=yes,scrollbars=yes,status=yes'); return false;" 
							href="<?=$arResult['CONFIRM_REQUEST_LINK']?>?timeslot_id=<?=$item['timeslot_id']?>&receiver_id=<?=$item['company_id']?>">Подтвердить</a> <a href="#">Отменить</a>
						<?
						endif;
						break;
					
					case 'free':
						?>
						<a href="<?=$arResult['SEND_REQUEST_LINK']?>?timeslot_id=<?=$item['timeslot_id']?>&receiver_id=">Послать запрос</a>
						<?
						break;
				}
				?>
			</td>
			<td><?=$item['notes'];?></td>
		</tr>
	<?endforeach;?>

</table>