<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	
	?>
    <script type="text/javascript">
        function toggle_desc(id)
        {
          if (document.getElementById('desc_' + id).style.display == 'none') document.getElementById('desc_' + id).style.display = 'block';
          else document.getElementById('desc_' + id).style.display = 'none';
        }
    </script>
    <? echo "<p class='link' style='color:#ff0000;'><strong>".$arResult["MESSAGE"]."</strong></p>";?>
    <form action="" method="post" name="reg_update">
    <h1>Адресаты</h1>
    <table width="700" border="0" cellspacing="0" cellpadding="7" class="admin_info">
      <tr class="chet">
        <td width="50"><input name="all_guest" type="checkbox" value="1" style="width:20px;"></td>
        <td><strong>Всем гостям утренней сессии</strong></td>
      </tr>
      <tr>
        <td width="50"><input name="all_hb" type="checkbox" value="1" style="width:20px;"></td>
        <td><strong>Всем гостям HB</strong></td>
      </tr>
      <tr class="chet">
        <td><input name="all_particip" type="checkbox" value="1" style="width:20px;"></td>
        <td><strong>Всем участникам</strong></td>
      </tr>
    </table>
    <p class="link" style="text-align:left;"><a href="#" onclick="toggle_desc('guest'); return false;">Гости утренней сессии</a></p>
    <div id="desc_guest" style="display:none;">
      <table border="0" cellspacing="0" cellpadding="7" class="admin_info">
          <tr class="chet">
              <td width="50"><strong>Написать</strong></td>
              <td width="265"><strong>Представитель и Компания</strong></td>
              <td width="50"><strong>Написать</strong></td>
              <td width="265"><strong>Представитель и Компания</strong></td>
          </tr>
          <tr>
	<?
	  for($i=0; $i<$arResult["GUEST"]["COUNT"]; $i++){
		if(!($i % 2)){
		?>
		  </tr>
		  <tr <? if($i % 4 == 2){?>class="chet"<? }?>>
		<?
        }
        ?>
			<td><input name="guests[]" type="checkbox" value="<?=$arResult["GUEST"]["LIST"][$i]['ID']?>" style="width:20px;"><input name="email<?=$arResult["GUEST"]["LIST"][$i]['ID']?>" type="hidden" value="<?=$arResult["GUEST"]["LIST"][$i]['EMAIL']?>" /></td>
			<td><?=$arResult["GUEST"]["LIST"][$i]['REP']?><br />
				<strong><?=$arResult["GUEST"]["LIST"][$i]['COMPANY']?></strong>
			</td>
		<?
	  }
		if($arResult["GUEST"]["COUNT"] % 2){
		?>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		<?
        }
    ?>
        </tr>
    </table>
    </div>
    <p class="link" style="text-align:left;"><a href="#" onclick="toggle_desc('hb'); return false;">Гости HB</a></p>
    <div id="desc_hb" style="display:none;">
      <table border="0" cellspacing="0" cellpadding="7" class="admin_info">
          <tr class="chet">
              <td width="50"><strong>Написать</strong></td>
              <td width="250"><strong>Представитель и Компания</strong></td>
              <td width="50"><strong>Написать</strong></td>
              <td width="250"><strong>Представитель и Компания</strong></td>
          </tr>
          <tr>
	<?
	  for($i=0; $i<$arResult["HB"]["COUNT"]; $i++){
		if(!($i % 2)){
		?>
		  </tr>
		  <tr <? if($i % 4 == 2){?>class="chet"<? }?>>
		<?
        }
        ?>
			<td><input name="hb[]" type="checkbox" value="<?=$arResult["HB"]["LIST"][$i]['ID']?>" style="width:20px;"><input name="email<?=$arResult["HB"]["LIST"][$i]['ID']?>" type="hidden" value="<?=$arResult["HB"]["LIST"][$i]['EMAIL']?>" /></td>
			<td><?=$arResult["HB"]["LIST"][$i]['REP']?><br />
				<strong><?=$arResult["HB"]["LIST"][$i]['COMPANY']?></strong>
			</td>
		<?
	  }
		if($arResult["HB"]["COUNT"] % 2){
		?>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		<?
        }
    ?>
        </tr>
    </table>
    </div>
    <p class="link" style="text-align:left;"><a href="#" onclick="toggle_desc('particip'); return false;">Участники</a></p>
    <div id="desc_particip" style="display:none;">
      <table border="0" cellspacing="0" cellpadding="7" class="admin_info">
          <tr class="chet">
              <td width="50"><strong>Написать</strong></td>
              <td width="250"><strong>Представитель и Компания</strong></td>
              <td width="50"><strong>Написать</strong></td>
              <td width="250"><strong>Представитель и Компания</strong></td>
          </tr>
          <tr>
	<?
	  for($i=0; $i<$arResult["PARTICIP"]["COUNT"]; $i++){
		if(!($i % 2)){
		?>
		  </tr>
		  <tr <? if($i % 4 == 2){?>class="chet"<? }?>>
		<?
        }
        ?>
			<td><input name="particip[]" type="checkbox" value="<?=$arResult["PARTICIP"]["LIST"][$i]['ID']?>" style="width:20px;"><input name="email<?=$arResult["PARTICIP"]["LIST"][$i]['ID']?>" type="hidden" value="<?=$arResult["PARTICIP"]["LIST"][$i]['EMAIL']?>" /></td>
			<td><?=$arResult["PARTICIP"]["LIST"][$i]['REP']?><br />
				<strong><?=$arResult["PARTICIP"]["LIST"][$i]['COMPANY']?></strong>
			</td>
		<?
	  }
		if($arResult["PARTICIP"]["COUNT"] % 2){
		?>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		<?
        }
    ?>
        </tr>
    </table>
    </div>
    <h1>Сообщение</h1>
    <table width="700" border="0" cellspacing="0" cellpadding="7" class="admin_info">
      <tr class="chet">
        <td><strong>Тема</strong></td>
        <td><input name="subj" type="text" value="<?=$arResult["MESS"]["SUBJ"]?>" /></td>
      </tr>
      <tr>
        <td><strong>Текст сообщения</strong></td>
        <td><textarea name="message_text"><?=$arResult["MESS"]["TEXT"]?></textarea></td>
      </tr>
    </table>
    <input name="mes" type="hidden" value="write" />
    <div><input name="submit" type="submit" value="Отправить" class="send_reg" /></div>
    </form>
	<?
}
else{
	echo "<p>".$arResult["ERROR_MESSAGE"]."</p>";
}
?>