<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	if($arResult["SHOW"] == "SENT"){
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
	<form action="" method="post" name="decline">
      <p style="padding-left:5px;"><strong>Компания:</strong> <?=$arResult["FROM_COMPANY"]?></p>
      <p style="padding-left:5px;"><strong>Представитель:</strong> <?=$arResult["FROM_NAME"]?></p>
    <br />
    <br />
      <p style="padding-left:5px;">У данного пользователя следующие встречи</p>
      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="form_edit">
        <tr>
          <td width="80"><strong>Время</strong></td>
          <td><strong>Компания</strong></td>
          <td><strong>Представитель</strong></td>
          <td><strong>Статус</strong></td>
        </tr>
      <?
	  for($i=0; $i < $arResult["MEETING"]["COUNT"]; $i++){
		  ?>
        <tr <? if($counter % 2){?>class="chet"<? }?>>
          <td><?=$arResult["MEETING"]["LIST"][$i]["TIME"]?></td>
          <td><?=$arResult["MEETING"]["LIST"][$i]["TO_COMPANY"]?></td>
          <td><?=$arResult["MEETING"]["LIST"][$i]["TO_NAME"]?></td>
          <td><?=$arResult["MEETING"]["LIST"][$i]["STATUS"]?></td>
        </tr>
		  <?
	  }
      ?>
      </table>
    <br />
    <br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="50%" valign="top">
              <p style="padding-left:5px;">В wish листе у данного пользователя следующие компании</p>
              <table width="100%" border="0" cellspacing="0" cellpadding="5" class="form_edit">
                <tr>
                  <td><strong>Компания</strong></td>
                  <td><strong>Представитель</strong></td>
                </tr>
              <?
              for($i=0; $i < $arResult["WISH_IN"]["COUNT"]; $i++){
                  ?>
                <tr <? if($counter % 2){?>class="chet"<? }?>>
                  <td><?=$arResult["WISH_IN"]["LIST"][$i]["COMPANY"]?></td>
                  <td><?=$arResult["WISH_IN"]["LIST"][$i]["REP"]?></td>
                </tr>
                  <?
              }
              ?>
              </table>
          </td>
          <td valign="top">
              <p style="padding-left:5px;">Данный пользователь в wish листе у следующих компаний</p>
              <table width="100%" border="0" cellspacing="0" cellpadding="5" class="form_edit">
                <tr>
                  <td><strong>Компания</strong></td>
                  <td><strong>Представитель</strong></td>
                </tr>
              <?
              for($i=0; $i < $arResult["WISH_OUT"]["COUNT"]; $i++){
                  ?>
                <tr <? if($counter % 2){?>class="chet"<? }?>>
                  <td><?=$arResult["WISH_OUT"]["LIST"][$i]["COMPANY"]?></td>
                  <td><?=$arResult["WISH_OUT"]["LIST"][$i]["REP"]?></td>
                </tr>
                  <?
              }
              ?>
              </table>
          </td>
        </tr>
      </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="form_edit">
       <tr>
            <td colspan="4"  class="send">
                <input name="form" type="hidden" value="send" /><input name="submit" type="submit" value="Отменить" class="send_reg" />
            </td>
        </tr>
      </table>
    </form>
	<?
	}
	//echo "<pre>"; print_r($arResult); echo "</pre>";
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