<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
    	<div class="admin_messages">
        <h1><?=GetMessage("ADMIN_MAIN")?></h1>
        <p><?=GetMessage("ADMIN_MAIN_GUEST")?><strong><?=$arResult["GUEST"]["COUNT"]?></strong>. <a href="<?=$arResult["GUEST"]["LINK"]?>"><?=GetMessage("ADMIN_MAIN_MORE")?></a></p>
        <p><?=GetMessage("ADMIN_MAIN_PARTICIP")?><strong><?=$arResult["PARTICIP"]["COUNT"]?></strong>. <a href="<?=$arResult["PARTICIP"]["LINK"]?>"><?=GetMessage("ADMIN_MAIN_MORE")?></a></p>
        <p><?=GetMessage("ADMIN_MAIN_PAY")?><strong><?=$arResult["PAY"]["COUNT"]?></strong>. <a href="<?=$arResult["PAY"]["LINK"]?>"><?=GetMessage("ADMIN_MAIN_MORE")?></a></p>
        <p><?=GetMessage("ADMIN_MAIN_GUEST_MORNING")?><strong><?=$arResult["GUEST_MORNING"]["COUNT"]?></strong>. <a href="<?=$arResult["GUEST_MORNING"]["LINK"]?>"><?=GetMessage("ADMIN_MAIN_MORE")?></a></p>
        <p><?=GetMessage("ADMIN_MAIN_GUEST_EVENING")?><strong><?=$arResult["GUEST_EVENING"]["COUNT"]?></strong>. <a href="<?=$arResult["GUEST_EVENING"]["LINK"]?>"><?=GetMessage("ADMIN_MAIN_MORE")?></a></p>
        <p><?=GetMessage("ADMIN_MAIN_GUEST_HB")?><strong><?=$arResult["GUEST_HB"]["COUNT"]?></strong>. <a href="<?=$arResult["GUEST_HB"]["LINK"]?>"><?=GetMessage("ADMIN_MAIN_MORE")?></a></p>
        </div>
	<?
}
else{
	echo "<p>".$arResult["ERROR_MESSAGE"]."</p>";
}
?>