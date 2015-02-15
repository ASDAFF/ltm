<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
    <p><strong><?=$arResult["MESSAGE"]?></strong></p>
	<script type="text/javascript" language="javascript"> window.setTimeout("self.close();", 5000); </script>
    <p>This window will close automatically in 5 second.</p>
    <br />
      <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
        <tr class="chet">
          <td width="80"><strong>From</strong></td>
          <td><?=$arResult["APPOINT"]["FROM"]["NAME"]?> Company: <?=$arResult["APPOINT"]["FROM"]["COMPANY"]?></td>
        </tr>
        <tr>
          <td><strong>To</strong></td>
          <td><?=$arResult["APPOINT"]["TO"]["NAME"]?> Company: <?=$arResult["APPOINT"]["TO"]["COMPANY"]?></td>
        </tr>
        <tr class="chet">
          <td><strong>Time</strong></td>
          <td><?=$arResult["APPOINT"]["TIME"]["TITLE"]?></td>
        </tr>
      </table>
	<?
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