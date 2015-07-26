<? if(!$arResult['IS_ACTIVE']){
?>
<p class="error">Appointments schedule blocked by the organizers</p>
<?
}?>
<table class="morning-time time-line">
    <tr>
      <th>Time</th>
      <th>Company</th>
      <th>Representative</th>
      <th>Status</th>
      <th>Notes</th>
  </tr>
	<? foreach ($arResult['SCHEDULE'] as $item):?>
	<? if($item['status'] != 'coffe'):?>
		<tr <?if($item['status'] == 'process' && !$item['sent_by_you']):?>class="unconfirmed"<?endif;?>>
			<td><?=$item['name']?></td>
			<?if (isset($item['company_name'])):?>
				<td><a href="/ajax/userInfo.php?id=<?=$item['company_id']?>&res=<?=$item['form_res']?>" class="user-info-wind fancybox.ajax"
					   target="_blank"><?=$item['company_name']?></a></td>
				<td><?=$item['company_rep']?></td>
			<?else:?>
				<td colspan="2">
                  <select name="company_id" class="chose-company" id="companys<?=$item['timeslot_id']?>">
                      <option value="0">Choose a company</option>
                    <?foreach ($item['list'] as $company):?>
                    <option value="<?=$company['id']?>"><?=$company['name']?></option>
                      <?endforeach;?>
                  </select>
				</td>
			<?endif;?>
			<td width="80">
				<?
				switch($item['status']) {
					case 'confirmed':
						echo 'confirmed';
						break;
					case 'process':
						if ($item['sent_by_you']):?>
                            <a href="<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$arResult['CURRENT_USER_ID']?>&to=<?=$item['company_id']?>&time=<?=$item['timeslot_id']?>&app=<?=$arResult['APP_ID']?>&type=p&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                                target="_blank"
                                onclick="newWind('<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$arResult['CURRENT_USER_ID']?>&to=<?=$item['company_id']?>&time=<?=$item['timeslot_id']?>&app=<?=$arResult['APP_ID']?>&type=p&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>', 500, 400); return false;">Cancel</a>
						<?else:?>
                            <a href="<?=$arResult['CONFIRM_REQUEST_LINK']?>?id=<?=$arResult['CURRENT_USER_ID']?>&to=<?=$item['company_id']?>&time=<?=$item['timeslot_id']?>&app=<?=$arResult['APP_ID']?>&type=p&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                                target="_blank"
                                onclick="newWind('<?=$arResult['CONFIRM_REQUEST_LINK']?>?id=<?=$arResult['CURRENT_USER_ID']?>&to=<?=$item['company_id']?>&time=<?=$item['timeslot_id']?>&app=<?=$arResult['APP_ID']?>&type=p&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>', 500, 400); return false;">Accept</a><br />
                            <a href="<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$item['company_id']?>&to=<?=$arResult['CURRENT_USER_ID']?>&time=<?=$item['timeslot_id']?>&app=<?=$arResult['APP_ID']?>&type=p&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                                target="_blank"
                                onclick="newWind('<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$item['company_id']?>&to=<?=$arResult['CURRENT_USER_ID']?>&time=<?=$item['timeslot_id']?>&app=<?=$arResult['APP_ID']?>&type=p&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>', 500, 400); return false;">Decline</a>
						<?
						endif;
						break;
					
					case 'free':
						if(!$arResult['IS_ACTIVE']){
						?>
						Blocked
						<?
						}
						else{
						?>
                        <a href="<?=$arResult['SEND_REQUEST_LINK']?>?id=<?=$arResult['CURRENT_USER_ID']?>&to=0&time=<?=$item['timeslot_id']?>&app=<?=$arResult['APP_ID']?>&type=p&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                            target="_blank"
                            onclick="newRequest('<?=$arResult['CURRENT_USER_ID']?>','<?=$item['timeslot_id']?>','<?=$arResult['APP_ID']?>','<?=$arResult['SEND_REQUEST_LINK']?>', 'p', '<?=$arResult['PARAM_EXHIBITION']['CODE']?>'); return false;">Send a request</a>
						<?
						}
						break;
				}
				?>
			</td>
			<td width="110"><?=$item['notes'];?></td>
		</tr>
    <? endif;?>
	<?endforeach;?>

</table>
					<div class="pull-overflow generate-file">
						<div class="pull-left">
                        	<a onclick="newWind('<?=$arResult['WISHLIST_LINK']?>_particip.php?id=<?=$arResult['CURRENT_USER_ID']?>&exhib=<?=$arResult['PARAM_EXHIBITION']['CODE']?>&app=<?=$arResult['APP_ID']?>&type=p&mode=pdf', 600, 700); return false;" target="_blank" href="<?=$arResult['WISHLIST_LINK']?>_particip.php?id=<?=$arResult['CURRENT_USER_ID']?>&exhib=<?=$arResult['PARAM_EXHIBITION']['CODE']?>&app=<?=$arResult['APP_ID']?>&type=p&mode=pdf">Generate wish-list PDF</a>
						</div>
						<div class="pull-right">
                        	<a onclick="newWind('<?=$arResult['SHEDULE_LINK']?>_particip.php?id=<?=$arResult['CURRENT_USER_ID']?>&exhib=<?=$arResult['PARAM_EXHIBITION']['CODE']?>&app=<?=$arResult['APP_ID']?>&type=p&mode=pdf', 600, 700); return false;" target="_blank" href="<?=$arResult['SHEDULE_LINK']?>_particip.php?id=<?=$arResult['CURRENT_USER_ID']?>&exhib=<?=$arResult['PARAM_EXHIBITION']['CODE']?>&app=<?=$arResult['APP_ID']?>&type=p&mode=pdf">Generate schedule PDF</a>
						</div>
					</div>
            <?
//var_dump($arResult);
            ?>
