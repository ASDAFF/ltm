<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	if($arResult["TYPE"] == "SENT"){
	?>
    <br />
    <br />
    <p style="padding-left:10px;">Your request has been successfully sent.</p>
	<script type='text/javascript'>top.opener.document.location.reload();</script>
    <script type="text/javascript" language="javascript"> window.setTimeout("self.close();", 5000); </script>
    <p style="padding-left:10px;">This window will close automatically in 5 second.</p>
    <br />
    <br />
	<?
	}
	else{
	?>
	<form action="<?=$arResult["LINK"]?>" method="post" name="appointment">
      <h2 class="reg_title">Send the request</h2>
      <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
        <tr class="chet">
          <td width="80"><strong>From</strong></td>
          <td><?=$arResult["SENDER"]["NAME"]?> Company: <?=$arResult["SENDER"]["COMPANY"]?></td>
        </tr>
        <tr>
          <td><strong>To</strong></td>
          <td><?=$arResult["RECIVER"]["NAME"]?> Company: <?=$arResult["RECIVER"]["COMPANY"]?><input name="id" type="hidden" value="<?=$arResult["RECIVER"]["ID"]?>" /></td>
        </tr>
        <tr class="chet">
          <td><strong>Time</strong></td>
          <td><?=$arResult["TIME"]["TITLE"]?><input name="time" type="hidden" value="<?=$arResult["TIME"]["ID"]?>" /></td>
        </tr>
      </table>
      <div align="right"><input name="form" type="hidden" value="send" /><input name="submit" type="submit" value="Submit" class="send_reg" /></div>
    </form>
	<?
	}
}
else{
	?>
    <br />
    <br />
    <p style="padding-left:10px;"><?=$arResult["ERROR_MESSAGE"]?></p>
    <script type="text/javascript" language="javascript"> window.setTimeout("self.close();", 5000); </script>
    <p style="padding-left:10px;">This window will close automatically in 5 second.</p>
    <br />
    <br />
	<?
}
?>