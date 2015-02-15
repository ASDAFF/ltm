<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	if(isset($arResult["MESSAGE"]) && $arResult["MESSAGE"] != ''){
		echo '<p class="error">'.$arResult["MESSAGE"].'</p>';
	}
	?>
    <script type="text/javascript">
		$(document).ready(function(){
		   $("select").change(function () {
				$changeDiv = $("div."+$(this).attr("name")+"_other");
				if($("option:selected",this).text() == 'Другая'){
					$changeDiv.show();
				}
				else{
					$changeDiv.hide();
				}
			})
		});	
    </script>
      <form action="" method="post" name="reg_update">
      <?
      	if($arResult["EDIT_ACT"] != "N"){
			?><div align="right"><input name="reset" type="reset" value="Отменить изменения" class="send_reg" /> <input name="submit" type="submit" value="Применить изменения" class="send_reg" /></div><br /><?
		}
	  ?>
        
        <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
          <tr class="chet">
            <td width="250"><strong>Имя</strong></td>
            <td><input name="name" type="text" value="<?=$arResult["QUEST"]["name"]["VALUE"]?>" /><input name="OLD_name" type="hidden" value="<?=$arResult["QUEST"]["name"]["VALUE"]?>" /></td>
          </tr>
          <tr>
            <td><strong>Фамилия</strong></td>
            <td><input name="surname" type="text" value="<?=$arResult["QUEST"]["surname"]["VALUE"]?>" /><input name="OLD_surname" type="hidden" value="<?=$arResult["QUEST"]["surname"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>Компания</strong></td>
            <td><input name="company" type="text" value="<?=$arResult["QUEST"]["company"]["VALUE"]?>" readonly="readonly" style="background:#999999"/><input name="OLD_company" type="hidden" value="<?=$arResult["QUEST"]["company"]["VALUE"]?>" /></td>
          </tr>
          <tr>
            <td><strong>Должность</strong></td>
            <td><input name="job" type="text" value="<?=$arResult["QUEST"]["job"]["VALUE"]?>"/><input name="OLD_job" type="hidden" value="<?=$arResult["QUEST"]["job"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>Адрес</strong></td>
            <td><input name="adress" type="text" value="<?=$arResult["QUEST"]["adress"]["VALUE"]?>"/><input name="OLD_adress" type="hidden" value="<?=$arResult["QUEST"]["adress"]["VALUE"]?>" />
            </td>
          </tr>
          <tr>
            <td><strong>Город</strong></td>
            <td><input name="city" type="text" value="<?=$arResult["QUEST"]["city"]["VALUE"]?>" readonly="readonly" style="background:#999999"/><input name="OLD_city" type="hidden" value="<?=$arResult["QUEST"]["city"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>Страна</strong></td>
            <td><input name="country" type="text" value="<?=$arResult["QUEST"]["select_choose"]["VALUE"]?>" readonly="readonly" style="background:#999999"/></td>
          </tr>
          <tr>
            <td><strong>Индекс</strong></td>
            <td><input name="index" type="text" value="<?=$arResult["QUEST"]["index"]["VALUE"]?>" /><input name="OLD_index" type="hidden" value="<?=$arResult["QUEST"]["index"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>Телефон</strong></td>
            <td><input name="phone" type="text" value="<?=$arResult["QUEST"]["phone"]["VALUE"]?>" /><input name="OLD_phone" type="hidden" value="<?=$arResult["QUEST"]["phone"]["VALUE"]?>" /></td>
          </tr>
          <tr>
            <td><strong>E-mail</strong></td>
            <td><input name="email" type="text" value="<?=$arResult["QUEST"]["email"]["VALUE"]?>" /><input name="OLD_email" type="hidden" value="<?=$arResult["QUEST"]["email"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>Альтернативный e-mail</strong></td>
            <td><input name="email_alt" type="text" value="<?=$arResult["QUEST"]["email_alt"]["VALUE"]?>" /><input name="OLD_email_alt" type="hidden" value="<?=$arResult["QUEST"]["email_alt"]["VALUE"]?>" /></td>
          </tr>
          <tr>
            <td><strong>Web-сайт компании</strong></td>
            <td><input name="site" type="text" value="<?=$arResult["QUEST"]["site"]["VALUE"]?>" /><input name="OLD_site" type="hidden" value="<?=$arResult["QUEST"]["site"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>Описание деятельности компании</strong></td>
            <td><textarea name="company_desc"><?=$arResult["QUEST"]["company_desc"]["VALUE"]?></textarea><input name="OLD_company_desc" type="hidden" value="<?=$arResult["QUEST"]["company_desc"]["VALUE"]?>" /></td>
          </tr>
        </table>
        <h2 class="reg_title">Коллега на утреннюю сессию</h2>
        <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
          <tr class="chet">
            <td width="250"><strong>Имя коллеги</strong></td>
            <td><input name="college_name" type="text" value="<?=$arResult["QUEST"]["college_name"]["VALUE"]?>" /><input name="OLD_college_name" type="hidden" value="<?=$arResult["QUEST"]["college_name"]["VALUE"]?>" /></td>
          </tr>
          <tr>
            <td><strong>Фамилия коллеги</strong></td>
            <td><input name="college_surname" type="text" value="<?=$arResult["QUEST"]["college_surname"]["VALUE"]?>" /><input name="OLD_college_surname" type="hidden" value="<?=$arResult["QUEST"]["college_surname"]["VALUE"]?>" /></td>
          </tr>
          <tr class="chet">
            <td><strong>Должность коллеги</strong></td>
            <td><input name="college_job" type="text" value="<?=$arResult["QUEST"]["college_job"]["VALUE"]?>" /><input name="OLD_college_job" type="hidden" value="<?=$arResult["QUEST"]["college_job"]["VALUE"]?>" /></td>
          </tr>
          <tr>
            <td><strong>E-mail коллеги</strong></td>
            <td><input name="college_email" type="text" value="<?=$arResult["QUEST"]["college_email"]["VALUE"]?>" /><input name="OLD_college_email" type="hidden" value="<?=$arResult["QUEST"]["college_email"]["VALUE"]?>" /></td>
          </tr>
        </table>
        <input name="usact" type="hidden" value="update" />
      <?
      	if($arResult["EDIT_ACT"] != "N"){
			?><div align="right"><input name="reset" type="reset" value="Отменить изменения" class="send_reg" /> <input name="submit" type="submit" value="Применить изменения" class="send_reg" /></div><br /><?
		}
	  ?>
        </form>
	<?
}
?>