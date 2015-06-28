<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<table id = "t_step2">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	//c($arItem);
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<tr id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<td>
			<span class = "chek2" for = "<?=$arItem['ID']?>"></span>
			<input type="checkbox" name="ex" value="ex1" class = "none" />
		</td>
		<?
		switch($arItem['PROPERTIES']['STATUS']['VALUE_ENUM']){
			case 'Available' : $class = 'stat_ex_ok'; $class2 = 'dar_e'; break;
			case 'Sold out' : $class = 'stat_ex_no'; $class2 = 'lig_e'; break;
			case 'Waiting list' : $class = 'stat_ex_an'; $class2 = 'dar_e'; break;
			default : $class = 'stat_ex_ok'; $class2 = 'dar_e'; break;
		}
		?>
		<td>
			<span class = "name_ex <?=$class2?>"><?=$arItem['NAME']?></span>
		</td>
		<td>
			<span class = "<?=$class?>"><?=$arItem['PROPERTIES']['STATUS']['VALUE_ENUM']?></span>
		</td>
	</tr>
<?endforeach;?>
</table>
