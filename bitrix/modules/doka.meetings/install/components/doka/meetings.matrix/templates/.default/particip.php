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
					<?=$user['name']?> <?=$user['rep']?>
				</td>
				<?foreach($arResult['TIME'] as $timeslot):?>
						<?if($user['schedule'][$timeslot['id']]['is_busy']):?>
							<?
							if($user['schedule'][$timeslot['id']]['status'] == 'confirmed') {
								$class = "confirmed";
							} else if ($user['schedule'][$timeslot['id']]['user_is_sender']) {
								$class = "yellow";
							} else {
								$class = "red";
							}
							?>
							<td class="<?=$class?>">
								<?=$user['schedule'][$timeslot['id']]['company_name']?> <?=$user['schedule'][$timeslot['id']]['rep']?>
								<?var_dump($user['schedule'][$timeslot['id']]);?>
						<?else:?>
							<td>
								<?if(!count($timeslot['companies'])):?>
									Все таймслоты заняты
								<?else:?>
									<select>
										<?foreach($timeslot['companies'] as $company):?>
											<option value="<?=$company['id']?>"><?=$company['name']?></option>
										<?endforeach;?>
									</select>
								<?endif;?>
								<?var_dump($timeslot);?>
						<?endif;?>
					</td>
				<?endforeach;?>
			</tr>
		<?endforeach;?>
	</tbody>
</table>
