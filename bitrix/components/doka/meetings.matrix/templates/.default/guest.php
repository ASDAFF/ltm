<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
	<script type="text/javascript">
    </script>
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
			<?foreach($arResult['TIME'] as $timeslot):?>
			<?if($user['schedule'][$timeslot['id']]['is_busy'] && $user['schedule'][$timeslot['id']]['status'] == 'confirmed'):?>
				<? if($user['schedule'][$timeslot['id']]['user_is_sender']) {
					$fromId = $user['id'];
					$toId = $user['schedule'][$timeslot['id']]['company_id'];
				} else {
					$fromId = $user['schedule'][$timeslot['id']]['company_id'];
					$toId = $user['id'];
				}?>
				<td class="confirmed">
					<?=$user['schedule'][$timeslot['id']]['company_name']?><br />
					<?=$user['schedule'][$timeslot['id']]['rep']?><br />
                    <a href="<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$fromId?>&to=<?=$toId?>&time=<?=$timeslot['id']?>&app=<?=$arResult['APP']?>&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                        target="_blank"
                        onclick="newWind('<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$fromId?>&to=<?=$toId?>&time=<?=$timeslot['id']?>&app=<?=$arResult['APP']?>&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>', 500, 400); return false;">Отменить</a>
                    <? //var_dump($user['schedule'][$timeslot['id']]);?>
			<?elseif($user['schedule'][$timeslot['id']]['is_busy']):?>
				<?
				if($user['schedule'][$timeslot['id']]['user_is_sender']) {
					$class = "red";
					$fromId = $user['id'];
					$toId = $user['schedule'][$timeslot['id']]['company_id'];
				} else {
					$class = "yellow";
					$fromId = $user['schedule'][$timeslot['id']]['company_id'];
					$toId = $user['id'];
				}
				?>
				<td class="<?=$class?>">
					<?=$user['schedule'][$timeslot['id']]['company_name']?><br />
					<?=$user['schedule'][$timeslot['id']]['rep']?><br />
					<a href="<?=$arResult['CONFIRM_REQUEST_LINK']?>?id=<?=$fromId?>&to=<?=$toId?>&time=<?=$timeslot['id']?>&app=<?=$arResult['APP']?>&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                        target="_blank"
                        onclick="newWind('<?=$arResult['CONFIRM_REQUEST_LINK']?>?id=<?=$fromId?>&to=<?=$toId?>&time=<?=$timeslot['id']?>&app=<?=$arResult['APP']?>&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>', 500, 400); return false;">Подтвердить</a><br />
                    <a href="<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$fromId?>&to=<?=$toId?>&time=<?=$timeslot['id']?>&app=<?=$arResult['APP']?>&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>"
                        target="_blank"
                        onclick="newWind('<?=$arResult['REJECT_REQUEST_LINK']?>?id=<?=$fromId?>&to=<?=$toId?>&time=<?=$timeslot['id']?>&app=<?=$arResult['APP']?>&exib_code=<?=$arResult['PARAM_EXHIBITION']['CODE']?>', 500, 400); return false;">Отменить</a>
                    <?// var_dump($user['schedule'][$timeslot['id']]);?>
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
