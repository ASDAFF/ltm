<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<h3>С вами хотели бы встретиться</h3>
<table class="table table-striped" id="results">
	<thead>
		<tr>
			<th>N</th>
			<th>Компания</th>
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

<h3>Вы хотели бы видеть</h3>
<table class="table table-striped" id="results">
	<thead>
		<tr>
			<th>N</th>
			<th>Компания</th>
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