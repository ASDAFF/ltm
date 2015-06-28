<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
if ($arResult["ERROR_MESSAGE"] == '')
{
	?>
    <div class="hello_div">
        <div class="welcome">
            <p><strong><?=$arResult["WELCOME"]?></strong></p>
            <p><?=$arResult["WELCOME2"]?></p>
        </div>
        <div class="logout"><a href="<?=$arResult["USER"]["LOGOUT"]?>"><img src="/local/templates/personal/images/logout.gif" width="63" height="21" alt="Log Out" border="0" /></a></div>
    </div>
	<?
}
else{
	echo $arResult["ERROR_MESSAGE"];
}
?>