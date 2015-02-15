		<?
		$arAlphabet = array("NUM" =>"#", "A" =>"A", "B" =>"B", "C" =>"C", "D" =>"D", "E" =>"E", "F" =>"F", "G" =>"G", "H" =>"H", "I" =>"I", "J" =>"J", "K" =>"K", "L" =>"L", "M" =>"M", "N" =>"N", "O" =>"O", "P" =>"P", "Q" =>"Q", "R" =>"R", "S" =>"S", "T" =>"T", "U" =>"U", "V" =>"V", "W" =>"W", "X" =>"X", "Y" =>"Y", "Z" =>"Z");
		$by = htmlspecialcharsEx(trim($_REQUEST["by"]));
		$type = htmlspecialcharsEx(trim($_REQUEST["type"]));

		//определение из какой группы брать данные
		switch ($by)
		{
			case "alphabet" : $sort = "BY_ALPHABET"; break;
			case "priority_areas" : $sort = "BY_PRIORITY_AREAS"; break;
			case "city" : $sort = "BY_CITY"; break;
			case "slots" : $sort = "BY_SLOTS"; break;
			case "all" : $sort = "BY_ALL"; break;
			default:  $sort = "BY_ALL";
		}
		?>
<div id="session-tab-2">
	<div class="pull-overflow sorting-company">
		<div class="list">
			<a href="<?= $APPLICATION->GetCurPageParam("by=alphabet", array("by", "EXHIBIT_CODE", "type"));?>" title="">By alphabet</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=city", array("by", "EXHIBIT_CODE", "type"));?>" title="">By city of origin</a>
			<a href="<?= $APPLICATION->GetCurPageParam("by=all", array("by", "EXHIBIT_CODE", "type"));?>" title="">All</a>
		</div>
			<?if($by == "alphabet"):?>
			<div class="alphabet">
			<? foreach ($arAlphabet as $code => $letter):?>
			    <a href="<?= $APPLICATION->GetCurPageParam("type=". $code, array("type", "EXHIBIT_CODE"));?>" title="" <?= ($code == $type)?"class='selected'":"";?>><?= $letter?></a>
			<? endforeach;?>
			</div>
		<? elseif($by == "slots"):?>
		<div class="list">
	        <? foreach ($arResult["SESSION"][$arParams["TYPE"]][$sort] as $code => $data):?>
	            <a href="<?= $APPLICATION->GetCurPageParam("type=". $code, array("EXHIBIT_CODE", "type"));?>" title="" <?= ($code == $type)?"class='selected'":"";?>><?= $data["name"]?></a>
	        <? endforeach;?>
		</div>
		<? elseif($sort != "BY_ALL"):?>
		<div class="list">
		    <? $first = true;?>
	        <? foreach ($arResult["SESSION"][$arParams["TYPE"]][$sort] as $data):?>
	            <a href="<?= $APPLICATION->GetCurPageParam("type=". $data["NAME"], array("EXHIBIT_CODE", "type"));?>" title="" <?= (($data["NAME"] == $type) || (!$type && $first))?"class='selected'":"";?>><?= $data["NAME"]?></a>
	            <? $first = false;?>
	        <? endforeach;?>
		</div>
		<?endif;?>

	</div>

	<table class="sorting-company">
		<tr>
			<th>Company</th>
			<th>Representative</th>
			<th>Collegues</th>
		</tr>

		<?
		//определение из какой группы брать данные
		switch ($by)
		{
			case "alphabet" : $sort = "BY_ALPHABET"; break;
			case "priority_areas" : $sort = "BY_PRIORITY_AREAS"; break;
			case "city" : $sort = "BY_CITY"; break;
			case "slots" : $sort = "BY_SLOTS"; break;
			case "all" : $sort = "BY_ALL"; break;
			default:  $sort = "BY_ALL";
		}

		if($sort != "BY_ALL")
		{
			foreach ($arResult["SESSION"][$arParams["TYPE"]][$sort] as $data)
			{
				if($type)
				{
					if($type != $data["NAME"])
					{
						continue;
					}
				}
				foreach ($data["ID"] as $id)
				{
					$arUser = $arResult["SESSION"][$arParams["TYPE"]]["USERS"][$id];
					$userFormData = $arUser["FORM_DATA"];
					?>
					<tr>
						<td class="company">
							<? if($userFormData["COMPANY"]["LINK"]):?>
							<a class="company-name" href="<?= $userFormData["COMPANY"]["LINK"]?>" title="<?= $userFormData["COMPANY"]["NAME"]?>"><?= $userFormData["COMPANY"]["NAME"]?></a>
							<? else:?>
							<div class="company-name"><?= $userFormData["COMPANY"]["NAME"]?></div>
							<? endif;?>
							<div><?= implode(", ", $userFormData["BUSINESS_TYPE"])?></div>
						</td>
						<td class="representative"><?= $userFormData["FIO"]?></td>
						<td class="collegues"><?
						foreach($userFormData["COLLEAGUE_E"] as $colleague)
						{
							if($fio = trim($colleague["FIO"]))
							{
								echo $fio . "<br/>";
							}
						}
						?></td>
					</tr>
					<?
				}
				if(!$type && $by == "priority_areas")//если нет типа выводим первый и выходим, а то выведет всех по много раз
				{
					break;
				}
			}
		}
		else
		{
			foreach ($arResult["SESSION"][$arParams["TYPE"]][$sort] as $data):
			//Для всех пользователей
			$arUser = $arResult["SESSION"][$arParams["TYPE"]]["USERS"][$data];
			$userFormData = $arUser["FORM_DATA"];
			?>
				<tr>
					<td class="company">
						<? if($userFormData["COMPANY"]["LINK"]):?>
						<a class="company-name" href="<?= $userFormData["COMPANY"]["LINK"]?>" title="<?= $userFormData["COMPANY"]["NAME"]?>"><?= $userFormData["COMPANY"]["NAME"]?></a>
						<? else:?>
						<div class="company-name"><?= $userFormData["COMPANY"]["NAME"]?></div>
						<? endif;?>
						<div><?= implode(", ", $userFormData["BUSINESS_TYPE"])?></div>
					</td>
					<td class="representative"><?= $userFormData["FIO"]?></td>
					<td class="collegues"><?
						foreach($userFormData["COLLEAGUE_E"] as $colleague)
						{
							if($fio = trim($colleague["FIO"]))
							{
								echo $fio . "<br/>";
							}
						}
						?></td>
				</tr>
			<? endforeach;
		}
		?>
	</table>
</div>