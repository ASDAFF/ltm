<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
    <div class="menu_personal">
        <?
		$counterPoint = 0;
		foreach($arResult["MENU"] as $mainPoint){
			switch ($counterPoint % 4){
				case 0:
					?>
					<div class="left_but"><p <? if($mainPoint["ACTIVE"] == "Y"):?>class="active_tip"<? else:?>class="tip"<? endif;?>><a href="<?=$mainPoint["LINK"]?>"><?=$mainPoint["NAME"]?></a></p></div>
					<? 
					break;
				case 3:
					?>
					<div class="right_but"><p <? if($mainPoint["ACTIVE"] == "Y"):?>class="active_tip"<? else:?>class="tip"<? endif;?>><a href="<?=$mainPoint["LINK"]?>"><?=$mainPoint["NAME"]?></a></p></div>
					<? 
					if($counterPoint == 3){
						?><div class="clear"></div><?
					}
					break;
				default:
					?>
					<div><p <? if($mainPoint["ACTIVE"] == "Y"):?>class="active_tip"<? else:?>class="tip"<? endif;?>><a href="<?=$mainPoint["LINK"]?>"><?=$mainPoint["NAME"]?></a></p></div>
					<? 
			}
		$counterPoint++;
		}
		?>
    </div>
	<?
}
else{
	echo $arResult["ERROR_MESSAGE"];
}
?>