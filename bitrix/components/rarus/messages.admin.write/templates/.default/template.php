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
    <form action="<?=$arResult["URL"]?>" method="post" name="admin_message">
    <h1>Адресаты</h1>
    <table width="700" border="0" cellspacing="0" cellpadding="7" class="admin_info">
      <tr class="odd">
        <td width="50" class="text-center"><input name="GUEST_M" type="checkbox" value="1" style="width:20px;"></td>
        <td><strong>Всем гостям утренней сессии</strong></td>
      </tr>
      <tr class="even">
        <td width="50" class="text-center"><input name="GUEST_HB" type="checkbox" value="1" style="width:20px;"></td>
        <td><strong>Всем гостям HB</strong></td>
      </tr>
      <tr class="odd" class="text-center">
        <td class="text-center"><input name="PARTICIPANT" type="checkbox" value="1" style="width:20px;"></td>
        <td><strong>Всем участникам</strong></td>
      </tr>
    </table>
    <? printTable($arResult["GUEST_M"], "Гости утренней сессии", "guest")?>
    <? printTable($arResult["GUEST_HB"], "Гости HB", "hb")?>
    <? printTable($arResult["PARTICIPANT"], "Участники", "particip")?>

    <h1>Сообщение</h1>
    <table width="700" border="0" cellspacing="0" cellpadding="7" class="admin_info">
      <tr>
        <td><strong>Тема</strong></td>
        <td><input name="subj" type="text" value="<?= $arResult["SUBJECT"]?>" size="110"/></td>
      </tr>
      <tr>
        <td><strong>Текст сообщения</strong></td>
        <td><textarea name="message_text" cols="112" rows="10"><?= $arResult["MESSAGE_TEXT"]?></textarea></td>
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

function printTable($arUserShow, $sTitle, $type)
{?>

    <p class="link" style="text-align:left;"><a href="#" onclick="toggle_desc('<?= $type?>'); return false;"><?= $sTitle?></a></p>
    <div id="desc_<?= $type?>" style="display:none;">
    <? if(!empty($arUserShow)):?>
    <table border="0" cellspacing="0" cellpadding="7" class="admin_info">
    <tr class="odd">
    <td width="50"><strong>Написать</strong></td>
    <td width="265"><strong>Представитель и Компания</strong></td>
    <td width="50"><strong>Написать</strong></td>
    <td width="265"><strong>Представитель и Компания</strong></td>
    </tr>
          <? $index = 1;?>
          <? for ($i = 0, $cnt = count($arUserShow); $i < $cnt; $i = $i + 2):?>
          <? $arUser = $arUserShow[$i];?>
          <tr class="<?= (($index++ % 2) != 0)?"even":"odd"?>">
    		<td class="text-center">
    		    <input name="UIDS[<?=$arUser["ID"]?>]" type="checkbox" value="<?=$arUser["ID"]?>" style="width:20px;">
    		</td>
    		<td>
    		    <?=$arUser["UF_FIO"]?><br />
    			<strong><?=$arUser['WORK_COMPANY']?></strong>
    		</td>

    		<? $arUser = $arUserShow[$i + 1];?>
    		<? if($arUser):?>
    		    <td class="text-center">
    			    <input name="UIDS[<?=$arUser["ID"]?>]" type="checkbox" value="<?=$arUser["ID"]?>" style="width:20px;">
    			</td>
    			<td>
    			    <?=$arUser["UF_FIO"]?><br />
    				<strong><?=$arUser['WORK_COMPANY']?></strong>
    			</td>
    		<? else:?>
    		<td>&nbsp;</td>
    		<td>&nbsp;</td>
    		<? endif;?>

        </tr>
        <? endfor;?>
    </table>
    <? else:?>
        В данной категории пользователей нет.
    <? endif;?>
    </div>
        <?
}
?>