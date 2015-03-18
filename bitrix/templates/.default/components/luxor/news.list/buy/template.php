<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<table id = "t_step2">
	<tr>
		<td style = "font-size: 12px;">српн</td>
		<td style = "font-size: 12px;">бевеп</td>
	</tr>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	//c($arItem);
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	$cl_m = '';
	$cl_e = '';
	$cl_all = '';
	$text = '';
	if($arItem['PROPERTIES']['STATUS_G_M']['VALUE_ENUM'] == 'Available'  && $arItem['PROPERTIES']['STATUS_G_E']['VALUE_ENUM'] == 'Available'){
		$cl_m = 'g_yes';
		$cl_e = 'g_yes';
		$cl_all = 'stat_ex_ok';
		$text = 'Available';
	}
	if($arItem['PROPERTIES']['STATUS_G_M']['VALUE_ENUM'] == 'Available'  && $arItem['PROPERTIES']['STATUS_G_E']['VALUE_ENUM'] == 'Sold out'){
		$cl_m = 'g_yes';
		$cl_e = 'g_not';
		$cl_all = 'stat_ex_ok';
		$text = 'Available';
	}
	if($arItem['PROPERTIES']['STATUS_G_M']['VALUE_ENUM'] == 'Available'  && $arItem['PROPERTIES']['STATUS_G_E']['VALUE_ENUM'] == 'Waiting list'){
		$cl_m = 'g_yes';
		$cl_e = 'g_yes';
		$cl_all = 'stat_ex_an';
		$text = 'Waiting list';
	}
	if($arItem['PROPERTIES']['STATUS_G_M']['VALUE_ENUM'] == 'Sold out'  && $arItem['PROPERTIES']['STATUS_G_E']['VALUE_ENUM'] == 'Waiting list'){
		$cl_m = 'g_not';
		$cl_e = 'g_yes';
		$cl_all = 'stat_ex_an';
		$text = 'Waiting list';
	}
	if($arItem['PROPERTIES']['STATUS_G_M']['VALUE_ENUM'] == 'Sold out'  && $arItem['PROPERTIES']['STATUS_G_E']['VALUE_ENUM'] == 'Sold out'){
		$cl_m = 'g_not';
		$cl_e = 'g_not';
		$cl_all = 'stat_ex_no';
		$text = 'Sold out';
	}
	if($arItem['PROPERTIES']['STATUS_G_M']['VALUE_ENUM'] == 'Waiting list'  && $arItem['PROPERTIES']['STATUS_G_E']['VALUE_ENUM'] == 'Waiting list'){
		$cl_m = 'g_yes';
		$cl_e = 'g_yes';
		$cl_all = 'stat_ex_an';
		$text = 'Waiting list';
	}
	if($arItem['PROPERTIES']['STATUS_G_M']['VALUE_ENUM'] == 'Waiting list'  && $arItem['PROPERTIES']['STATUS_G_E']['VALUE_ENUM'] == 'Sold out'){
		$cl_m = 'g_yes';
		$cl_e = 'g_not';
		$cl_all = 'stat_ex_an';
		$text = 'Waiting list';
	}
	if($arItem['PROPERTIES']['STATUS_G_M']['VALUE_ENUM'] == 'Waiting list'  && $arItem['PROPERTIES']['STATUS_G_E']['VALUE_ENUM'] == 'Available'){
		$cl_m = 'g_yes';
		$cl_e = 'g_yes';
		$cl_all = 'stat_ex_an';
		$text = 'Waiting list';
	}
	if($arItem['PROPERTIES']['STATUS_G_M']['VALUE_ENUM'] == 'Sold out'  && $arItem['PROPERTIES']['STATUS_G_E']['VALUE_ENUM'] == 'Available'){
		$cl_m = 'g_not';
		$cl_e = 'g_yes';
		$cl_all = 'stat_ex_ok';
		$text = 'Available';
	}
	?>
	<tr id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<td class = "mon_b">
			<? if($cl_m != 'g_not'){ ?>
			<span class = "chek2 <?=$cl_m?>" for = "<?=$arItem['ID']?>"></span>
			<input type="checkbox" name="ex" value="ex1" class = "none" />
			<? } ?>
		</td>
		<td class = "eve_b">
			<? if($cl_e != 'g_not'){ ?>
			<span class = "chek2 <?=$cl_e?>" for = "<?=$arItem['ID']?>"></span>
			<input type="checkbox" name="ex" value="ex1" class = "none" />
			<? } ?>
		</td>
		<td class = "nam_bu">
			<span class = "name_ex" for = "<?=$arItem['PROPERTIES']['NAME_EN']['VALUE']?>"><?=$arItem['NAME']?></span>
		</td>
		<?
		/*c($arItem['PROPERTIES']);
		switch($arItem['PROPERTIES']['STATUS']['VALUE_ENUM']){
			case 'Available' : $class = 'stat_ex_ok'; break;
			case 'Sold out' : $class = 'stat_ex_no'; break;
			case 'Waiting list' : $class = 'stat_ex_an'; break;
			default : $class = 'stat_ex_ok'; break;
		}*/
		?>
		<? /* ?>
		<td>
			<span class = "<?=$cl_all?>"><?=$text?></span>
		</td>
		<? */ ?>
	</tr>
<?endforeach;?>
</table>
