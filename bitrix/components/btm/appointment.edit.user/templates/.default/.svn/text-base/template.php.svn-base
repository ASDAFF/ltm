<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	if($arResult["TYPE"] == "FORM"){
	?>
	<form action="<?=$arResult["LINK"]?>" method="post" name="appointment">
      <h2 class="reg_title">Send the request</h2>
      <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
        <tr class="chet">
          <td width="120"><strong>From</strong></td>
          <td></td>
        </tr>
        <tr>
          <td><strong>To</strong></td>
          <td></td>
        </tr>
        <tr class="chet">
          <td><strong>Time</strong></td>
          <td></td>
        </tr>
        <tr class="send">
          <td>&nbsp;</td>
          <td><input type="submit" value="Submit" name="submit"/><input name="form" type="hidden" value="send" /></td>
        </tr>
      </tr>
    </table>
    </form>
	<?
	}
	elseif($arResult["TYPE"] == "SENT"){
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