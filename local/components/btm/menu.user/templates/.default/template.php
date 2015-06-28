<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
    	<script type="text/javascript" src="/local/templates/personal_admin/choose_tips.js"></script>
        <div id="top_tips">
        <?
		$counterPoint = 1;
		foreach($arResult["MENU"] as $mainPoint){
			?>
            
        	<div <? if($mainPoint["ACTIVE"] == "Y"):?>class="active_tip"<? else:?>class="tip"<? endif;?> id="parent<?=$counterPoint?>"onclick="chooseParmenu('top_tips','submenu_cont','<?=$counterPoint?>'); return false;"><p><a href="<?=$mainPoint["LINK"]?>" onclick="chooseParmenu('top_tips','submenu_cont','<?=$counterPoint?>');"><?=$mainPoint["NAME"]?></a></p></div>
			<? 
		$counterPoint++;
		}
		?>
        </div>
        <div id="submenu_cont">
        <?
		$counterPoint = 1;
		foreach($arResult["MENU"] as $mainPoint){
			?>
        	<div class="submenu" id="sub<?=$counterPoint?>" <? if($mainPoint["ACTIVE"] == "Y"):?>style="display:block;"<? endif;?>>
			<?
            foreach($mainPoint["CHILDE"] as $childPoint){
                ?>
                <p <? if($childPoint["ACTIVE"] == "Y"):?>class="active"<? else:?>class="subpoint"<? endif;?>><a href="<?=$childPoint["LINK"]?>"><?=$childPoint["NAME"]?></a></p>
                <? 
            }
            ?>
            </div>
			<? 
			$counterPoint++;
		}
		?>
        </div>
	<?
}
?>