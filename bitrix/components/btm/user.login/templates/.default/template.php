<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if ($arResult["FORM_TYPE"] == "login"):?>
<?
if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
{
	ShowMessage($arResult['ERROR_MESSAGE']);
}
?>
<h2>AUTHORIZATION</h2>
<form method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
	<?
	if (strlen($arResult["BACKURL"]) > 0)
	{
	?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<?
	}
	?>
	<?
	foreach ($arResult["POST"] as $key => $value)
	{
	?>
	<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
	<?
	}
	?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="AUTH" />
	<input type="hidden" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" />


    <input type="text" name="USER_LOGIN" maxlength="50" value="<?=GetMessage("AUTH_LOGIN")?>" size="17" onfocus="if(this.value=='<?=GetMessage("AUTH_LOGIN")?>') this.value='';" /><br />
    <input type="password" name="USER_PASSWORD" maxlength="50" size="17" value="<?=GetMessage("AUTH_PASSWORD")?>" onfocus="if(this.value=='<?=GetMessage("AUTH_PASSWORD")?>') this.value='';" /><br />
    <input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" class="send" />
	<!-- <p><noindex><a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a></noindex></p> -->
</form>
<?elseif ($arResult["FORM_TYPE"] == "logout"):?>
<h2>AUTHORIZATION</h2>
<form action="<?=$arResult["AUTH_URL"]?>">
	<p><?=GetMessage("AUTH_DEJA")?></p>
	<p><?=GetMessage("AUTH_LOGIN")?>: <?=$arResult["USER_LOGIN"]?></p>
    <?foreach ($arResult["GET"] as $key => $value):?>
        <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
    <?endforeach?>
    <input type="hidden" name="logout" value="yes" />
    <input type="submit" name="logout_butt" value="<?=GetMessage("AUTH_LOGOUT_BUTTON")?>" class="send" />
</form>
<?endif?>