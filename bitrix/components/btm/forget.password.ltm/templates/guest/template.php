<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if($arResult['ERROR_MESSAGE'] != ''){
	echo "<p class='error'>".$arResult['ERROR_MESSAGE']."</p>";
}
?>
        <h1><?=GetMessage("AUTH_HEAD")?></h1>
<?
if ($arResult["FORM_TYPE"] == "FORM"){
	?>
    <p><?=GetMessage("AUTH_MESSAGE")?></p>
    <div class="fog_pass">
    <form id="authform" name="authform" method="post" action="">
        <table width="100%" cellspacing="0" cellpadding="7" border="0" class="regist_info" style="border:0;">
          <tr>
            <td width="80"><?=GetMessage("AUTH_LOGIN")?></td>
            <td><input type="text" name="login" value="Login" onFocus="if(this.value=='Login') this.value='';" /></td>
          </tr>
        </table>
          <input type="hidden" value="Y" name="islog" />
          <input type="submit" name="Login" value="" class="send_reg" />
        </form>
    </div>
	<?
}
else{
	?>
	<p><?=$arResult['MESSAGE']?></p>
	<?
}
?>