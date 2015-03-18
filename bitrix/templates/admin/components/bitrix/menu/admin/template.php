<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="table-responsive main-tab clearfix">
	<?if (!empty($arResult)):?>
		<table class="table">
			<tr>
				<?$prevLvl = 0;?>
				<?foreach($arResult as $arItem):?>
					<?if($prevLvl && $arItem["DEPTH_LEVEL"] == 1):?>
					</ul></td>
				<?endif?>
	
				<?if($arItem["DEPTH_LEVEL"] == 1):?>
					<td>
						<div class="tab-item <?if($arItem["SELECTED"]):?>active<?endif?>">
						<div class="town-name">
							<a href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>" class="town">
								<?=$arItem["TEXT"]?></a>
						</div>
					<ul>
				<?else:?>
					<li class="<?if($arItem["SELECTED"]):?>active<?endif?>">
						<a href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>">
							<?=$arItem["TEXT"]?></a>
					</li>
				<?endif?>
				<?$prevLvl = $arItem["DEPTH_LEVEL"]?>
			<?endforeach?>
	
			<?if($prevLvl > 1):?>
				</ul>
				</div>
				</td>
			<?endif?>
	</tr></table>
	<?endif?>
</div>