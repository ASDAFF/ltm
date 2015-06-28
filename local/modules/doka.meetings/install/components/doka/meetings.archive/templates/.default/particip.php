<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<table class="table" id="results">
	<thead>
		<tr>
			<th>РљРѕРјРїР°РЅРёСЏ Рё РїСЂРµРґСЃС‚Р°РІРёС‚РµР»СЊ</th>
			<th>РђРґСЂРµСЃР°С‚</th>
			<th>Р’СЂРµРјСЏ</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($arResult['MEET'] as $request):?>
			<tr>
				<td><?=$request['sender_rep']?> <?=$request['sender_company']?></td>
				<td><?=$request['receiver_rep']?> <?=$request['receiver_company']?></td>
				<td><?=$request['timeslot_name']?></td>
			</tr>
		<?endforeach;?>
	</tbody>
</table>
<?=$arResult['NAVIGATE'];?>