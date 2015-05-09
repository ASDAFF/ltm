<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<h3>РЎ РІР°РјРё С…РѕС‚РµР»Рё Р±С‹ РІСЃС‚СЂРµС‚РёС‚СЊСЃСЏ</h3>
<table class="table table-striped" id="results">
	<thead>
		<tr>
			<th>N</th>
			<th>РљРѕРјРїР°РЅРёСЏ</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($arResult['WISH_OUT'] as $item):?>
			<tr>
				<td><?=$item['company_id']?></td>
				<td> <?=$item['company_name']?></td>
			</tr>
		<?endforeach;?>
	</tbody>
</table>

<h3>Р’С‹ С…РѕС‚РµР»Рё Р±С‹ РІРёРґРµС‚СЊ</h3>
<table class="table table-striped" id="results">
	<thead>
		<tr>
			<th>N</th>
			<th>РљРѕРјРїР°РЅРёСЏ</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($arResult['WISH_IN'] as $item):?>
			<tr>
				<td><?=$item['company_id']?></td>
				<td> <?=$item['company_name']?></td>
			</tr>
		<?endforeach;?>
	</tbody>
</table>
<?=$arResult['NAVIGATE'];?>