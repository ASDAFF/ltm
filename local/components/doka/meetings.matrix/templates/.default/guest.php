<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<div class="table-results">
	<table class="table table-fixed">
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
					<?
					$values = [
						"%ID%" => $fromId,
						"%TO%" => $toId,
						"%TIME%" => $timeslot['id']
					];
					$params = str_replace(array_keys($values), array_values($values), $arResult["LINKS"]["reject"]["LINK_PARAMS"]);
					$linkURL = $arResult["LINKS"]["reject"]["LINK"]."?".http_build_query($params);
					?>
					<a href="<?$linkURL?>" target="_blank"
						 onclick="newWindConfirm('<?=$linkURL?>', 500, 400, 'Вы хотите отменить запрос?'); return false;">
						<?=$arResult["LINKS"]["reject"]["TITLE"]?>
					</a>
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
					<?
					$values = [
						"%ID%" => $fromId,
						"%TO%" => $toId,
						"%TIME%" => $timeslot['id']
					];
					$params = str_replace(array_keys($values), array_values($values), $arResult["LINKS"]["confirm"]["LINK_PARAMS"]);
					$linkURL = $arResult["LINKS"]["confirm"]["LINK"]."?".http_build_query($params);
					?>
					<a href="<?=$linkURL?>" target="_blank" onclick="newWind('<?=$linkURL?>', 500, 400); return false;)">
						<?=$arResult["LINKS"]["confirm"]["TITLE"]?>
					</a><br />
					<?
					$params = str_replace(array_keys($values), array_values($values), $arResult["LINKS"]["reject"]["LINK_PARAMS"]);
					$linkURL = $arResult["LINKS"]["reject"]["LINK"]."?".http_build_query($params);
					?>
					<a href="<?$linkURL?>" target="_blank"
						 onclick="newWindConfirm('<?=$linkURL?>', 500, 400, 'Вы хотите отменить запрос?'); return false;">
						<?=$arResult["LINKS"]["reject"]["TITLE"]?>
					</a>
			<?else:?>
				<td class="times-list">
					<?foreach($arResult["FREE_LINKS"] as $link_code => $link):?>
						<?
						$values = [
							"%ID%" => $user['id'],
							"%TIME%" => $timeslot['id']
						];
						$params = str_replace(array_keys($values), array_values($values), $link["LINK_PARAMS"]);
						$linkURL = $link["LINK"]."?".http_build_query($params);
						$classNames = array_merge([$link_code], $link["CLASS"]);
						?>
						<a href="<?=$linkURL?>" target="_blank" data-timeslot="<?=$timeslot['id']?>" class="<?=implode(" ", $classNames)?>">
							<?=$link["TITLE"]?>
						</a>
					<?endforeach;?>
					<span class="cancel">Отменить</span>
			<?endif;?>
			</td>
			<?endforeach;?>
		</tr>
		<?endforeach;?>
	</tbody>
</table>
</div>