<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
        <h2 class="reg_title"><?=GetMessage("AUTH_HEAD")?></h2>
<?
if($arResult['ERROR_MESSAGE'] != ''){
	echo "<p class='error'>".$arResult['ERROR_MESSAGE']."</p>";
}
if ($arResult["FORM_TYPE"] == "login"){
	?>
	<form method="post" target="_top" action="">
	  <input type="text" name="LOGIN" maxlength="50" value="<?=GetMessage("AUTH_LOGIN")?>" size="17" onfocus="if(this.value=='<?=GetMessage("AUTH_LOGIN")?>') this.value='';" /><br />
	  <input type="password" name="PASSWORD" maxlength="50" size="17" value="<?=GetMessage("AUTH_PASSWORD")?>" onfocus="if(this.value=='<?=GetMessage("AUTH_PASSWORD")?>') this.value='';" /><br />
	  <input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" class="send" />
	</form>	
    <p class="forgot"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></p>	
	<?
}
elseif($arResult["FORM_TYPE"] == 'is_auth'){
	?>
	<p><?=GetMessage("AUTH_DEJA")?></p>
	<?
}
elseif($arResult["FORM_TYPE"] == 'is_block'){
	?>
	<?
}
?>