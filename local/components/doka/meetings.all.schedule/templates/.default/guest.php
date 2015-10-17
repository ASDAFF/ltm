<? if(!$arResult['IS_ACTIVE']){
?>
<p class="error">Назначение встреч заблокировано администрацией</p>
<?
}?>
<table class="morning-time time-line">
    <tr>
      <th>Время</th>
      <th>Компания</th>
      <th>Представитель</th>
      <th>Статус</th>
      <th>Заметки</th>
  </tr>
	<? foreach ($arResult['SCHEDULE'] as $item):?>
	<? if($item['status'] != 'coffe'):?>
		<tr <?if($item['status'] == 'process' && !$item['sent_by_you']):?>class="unconfirmed"<?endif;?>>
			<td><?=$item['name']?></td>
			<?if (isset($item['company_name'])):?>
				<td><?=$item['company_name']?></td>
				<td><?=$item['company_rep']?></td>
			<?else:?>
				<td colspan="2">
                  <select name="company_id" class="chose-company" id="companys<?=$item['timeslot_id']?>">
                      <option value="0">Выберите компанию</option>
                    <?foreach ($item['list'] as $company):?>
                    <option value="<?=$company['id']?>"><?=$company['name']?></option>
                      <?endforeach;?>
                  </select>
				</td>
			<?endif;?>
			<td width="110">
				<?
				switch($item['status']) {
					case 'confirmed':
						echo 'Подтвержден';
						break;
					case 'process':
						if ($item['sent_by_you']):?>
                            <a href="<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$arResult['CURRENT_USER_ID']?>&to=<?=$item['company_id']?>&time=<?=$item['timeslot_id']?>&app=<?=$arResult['APP_ID']?>&type=p&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                                target="_blank"
                                onclick="newWind('<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$arResult['CURRENT_USER_ID']?>&to=<?=$item['company_id']?>&time=<?=$item['timeslot_id']?>&app=<?=$arResult['APP_ID']?>&type=p&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>', 500, 400); return false;">Отменить</a>
						<?else:?>
                            <a href="<?=$arResult['CONFIRM_REQUEST_LINK']?>?id=<?=$arResult['CURRENT_USER_ID']?>&to=<?=$item['company_id']?>&time=<?=$item['timeslot_id']?>&app=<?=$arResult['APP_ID']?>&type=p&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                                target="_blank"
                                onclick="newWind('<?=$arResult['CONFIRM_REQUEST_LINK']?>?id=<?=$arResult['CURRENT_USER_ID']?>&to=<?=$item['company_id']?>&time=<?=$item['timeslot_id']?>&app=<?=$arResult['APP_ID']?>&type=p&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>', 500, 400); return false;">Подтвердить</a><br />
                            <a href="<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$item['company_id']?>&to=<?=$arResult['CURRENT_USER_ID']?>&time=<?=$item['timeslot_id']?>&app=<?=$arResult['APP_ID']?>&type=p&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                                target="_blank"
                                onclick="newWind('<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$item['company_id']?>&to=<?=$arResult['CURRENT_USER_ID']?>&time=<?=$item['timeslot_id']?>&app=<?=$arResult['APP_ID']?>&type=p&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>', 500, 400); return false;">Отменить</a>
						<?
						endif;
						break;
					
					case 'free':
						if(!$arResult['IS_ACTIVE']){
						?>
						Заблокировано
						<?
						}
						else{
						?>
                        <a href="<?=$arResult['SEND_REQUEST_LINK']?>?id=<?=$arResult['CURRENT_USER_ID']?>&to=0&time=<?=$item['timeslot_id']?>&app=<?=$arResult['APP_ID']?>&type=p&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                            target="_blank"
                            onclick="newRequest('<?=$arResult['CURRENT_USER_ID']?>','<?=$item['timeslot_id']?>','<?=$arResult['APP_ID']?>','<?=$arResult['SEND_REQUEST_LINK']?>', 'p', '<?=$arResult['PARAM_EXHIBITION']['CODE']?>'); return false;">Послать запрос</a>
						<?
						}
						break;
				}
				?>
			</td>
            <?
            ?>
			<td width="105"><?=$item['notes'];?></td>
		</tr>
    <? endif;?>
	<?endforeach;?>
</table>
					<div class="pull-overflow generate-file">
						<div class="pull-left">
                        	<a onclick="newWind('<?=$arResult['WISHLIST_LINK']?>_guest.php?id=<?=$arResult['CURRENT_USER_ID']?>&exhib=<?=$arResult['PARAM_EXHIBITION']['CODE']?>&app=<?=$arResult['APP_ID']?>&type=g&mode=pdf', 600, 700); return false;" target="_blank" href="<?=$arResult['WISHLIST_LINK']?>_guest.php?id=<?=$arResult['CURRENT_USER_ID']?>&exhib=<?=$arResult['PARAM_EXHIBITION']['CODE']?>&app=<?=$arResult['APP_ID']?>&type=g&mode=pdf">Генерировать вишлист PDF</a>
						</div>
						<div class="pull-right">
                        	<a onclick="newWind('<?=$arResult['SHEDULE_LINK']?>_guest.php?id=<?=$arResult['CURRENT_USER_ID']?>&exhib=<?=$arResult['PARAM_EXHIBITION']['CODE']?>&app=<?=$arResult['APP_ID']?>&type=g&mode=pdf', 600, 700); return false;" target="_blank" href="<?=$arResult['SHEDULE_LINK']?>_guest.php?id=<?=$arResult['CURRENT_USER_ID']?>&exhib=<?=$arResult['PARAM_EXHIBITION']['CODE']?>&app=<?=$arResult['APP_ID']?>&type=g&mode=pdf">Генерировать расписание PDF</a>
						</div>
					</div>