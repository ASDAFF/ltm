<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<table class="table" id="results">
	<thead>
		<tr>
			<th>Компания и представитель</th>
			<?foreach ($arResult['TIME'] as $timeslot):?>
				<th><?=$timeslot['name']?></th>
			<?endforeach;?>
		</tr>
	</thead>
	<tbody>
		<? foreach ($arResult['USERS'] as $user):?>
			<tr>
				<td>
					<?=$user['name']?><br />
					<?=$user['rep']?>
				</td>
				<?foreach($arResult['TIME'] as $timeslot):
					$timeSlotInfo = $user['schedule'][$timeslot['id']];
					$scheduleStatus = $timeSlotInfo['status'];
					?>
						<?if($timeSlotInfo['is_busy'] && $scheduleStatus == 'confirmed'):?>
							<? if($timeSlotInfo['user_is_sender']) {
								$fromId = $user['id'];
								$toId = $timeSlotInfo['company_id'];
							} else {
								$fromId = $timeSlotInfo['company_id'];
								$toId = $user['id'];
							}?>
							<td class="confirmed">
								<?=$timeSlotInfo['company_name']?><br />
								<?=$timeSlotInfo['rep']?><br />
                                <a href="<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$fromId?>&to=<?=$toId?>&time=<?=$timeslot['id']?>&app=<?=$arResult['APP']?>&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                                    target="_blank"
                                    onclick="newWindConfirm('<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$fromId?>&to=<?=$toId?>&time=<?=$timeslot['id']?>&app=<?=$arResult['APP']?>&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>', 500, 400, 'Вы хотите отменить запрос?'); return false;">Отменить</a>
                                <? //var_dump($timeSlotInfo);?>
						<?elseif($scheduleStatus == 'reserve'):
						$fromId = $user['id'];
						$reserveLink = $arResult['RESERVE_REQUEST_LINK']."?id=".$fromId."&time=".$timeslot['id']."&app=".$arResult['APP']."&type=p&exib_code=".$arResult['PARAM_EXHIBITION']['CODE'];?>
							<td class="reserved">
								Забронирован<br />
								<a href="<?=$reserveLink?>" onclick="newWind('<?=$reserveLink?>', 500, 200); return false;"
									 target="_blank">Освободить</a>
							</td>
						<?elseif($timeSlotInfo['is_busy']):?>
							<?
							if($timeSlotInfo['user_is_sender']) {
								$class = "yellow";
								$fromId = $user['id'];
								$toId = $timeSlotInfo['company_id'];
							} else {
								$class = "red";
								$fromId = $timeSlotInfo['company_id'];
								$toId = $user['id'];
							}
							?>
							<td class="<?=$class?>">
								<?=$timeSlotInfo['company_name']?><br />
								<?=$timeSlotInfo['rep']?><br />
								<a href="<?=$arResult['CONFIRM_REQUEST_LINK']?>?id=<?=$fromId?>&to=<?=$toId?>&time=<?=$timeslot['id']?>&app=<?=$arResult['APP']?>&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                                    target="_blank"
                                    onclick="newWind('<?=$arResult['CONFIRM_REQUEST_LINK']?>?id=<?=$fromId?>&to=<?=$toId?>&time=<?=$timeslot['id']?>&app=<?=$arResult['APP']?>&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>', 500, 400); return false;">Подтвердить</a><br />
                                <a href="<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$fromId?>&to=<?=$toId?>&time=<?=$timeslot['id']?>&app=<?=$arResult['APP']?>&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                                    target="_blank"
                                    onclick="newWindConfirm('<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$fromId?>&to=<?=$toId?>&time=<?=$timeslot['id']?>&app=<?=$arResult['APP']?>&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>', 500, 400, 'Вы хотите отменить запрос?'); return false;">Отменить</a>
                                <? //var_dump($timeSlotInfo);?>
						<?else:?>
							<td class="times-list">
								<a href="<?=$arResult['SEND_REQUEST_LINK']?>?id=<?=$user['id']?>&time=<?=$timeslot['id']?>&app=<?=$arResult['APP']?>&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                                    target="_blank" data-timeslot="<?=$timeslot['id']?>">Назначить</a>
						<?endif;?>
					</td>
				<?endforeach;?>
			</tr>
		<?endforeach;?>
	</tbody>
</table>
