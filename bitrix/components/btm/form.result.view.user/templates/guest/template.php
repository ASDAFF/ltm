<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?=$arResult["FormErrors"]?><?=$arResult["FORM_NOTE"]?>
<?
if (strlen($arParams["EDIT_URL"]) > 0) 
{
	$href = $arParams["SEF_MODE"] == "Y" ? str_replace("#RESULT_ID#", $arParams["RESULT_ID"], $arParams["EDIT_URL"]) : $arParams["EDIT_URL"];
}
if($arResult["EDIT_ACT"] != "N"){
	?><p class="reg_update"><a href="<?=$arParams["EDIT_URL"]?>">Редактировать информацию</a></p><?
	}
?>
    <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
      <tr class="chet">
        <td width="250"><strong>Имя</strong></td>
        <td><?=$arResult["RESULT"]["name"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr>
        <td><strong>Фамилия</strong></td>
        <td><?=$arResult["RESULT"]["surname"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr class="chet">
        <td><strong>Компания</strong></td>
        <td><?=$arResult["RESULT"]["company"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr>
        <td><strong>Должность</strong></td>
        <td><?=$arResult["RESULT"]["job"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr class="chet">
        <td><strong>Адрес</strong></td>
        <td><?=$arResult["RESULT"]["adress"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr>
        <td><strong>Город</strong></td>
        <td><?=$arResult["RESULT"]["city"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr class="chet">
        <td><strong>Страна</strong></td>
        <td><?
        if($arResult["RESULT"]["select_choose"]["ANSWER_VALUE"][0]["ANSWER_TEXT"] == "Другая" && isset($arResult["RESULT"]["select_choose_ans"]["ANSWER_VALUE"][0]["USER_TEXT"])){
			echo $arResult["RESULT"]["select_choose_ans"]["ANSWER_VALUE"][0]["USER_TEXT"];
		}
		else{
			echo $arResult["RESULT"]["select_choose"]["ANSWER_VALUE"][0]["ANSWER_TEXT"];
		}
        ?></td>
      </tr>
      <tr>
        <td><strong>Индекс</strong></td>
        <td><?=$arResult["RESULT"]["index"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr class="chet">
        <td><strong>Телефон</strong></td>
        <td><?=$arResult["RESULT"]["phone"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr>
        <td><strong>E-mail</strong></td>
        <td><?=$arResult["RESULT"]["email"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr class="chet">
        <td><strong>Альтернативный e-mail</strong></td>
        <td><?=$arResult["RESULT"]["email_alt"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
      <tr>
        <td><strong>Web-сайт компании</strong></td>
        <td><? if(isset($arResult["RESULT"]["site"]["ANSWER_VALUE"][0]["USER_TEXT"])){echo '<a href="http://'.$arResult["RESULT"]["site"]["ANSWER_VALUE"][0]["USER_TEXT"].'">'.$arResult["RESULT"]["site"]["ANSWER_VALUE"][0]["USER_TEXT"].'</a>';}?>&nbsp;</td>
      </tr>
      <tr class="chet">
        <td><strong>Описание деятельности компании</strong></td>
        <td><?=$arResult["RESULT"]["company_desc"]["ANSWER_VALUE"][0]["USER_TEXT"]?></td>
      </tr>
    </table>
    <h2 class="reg_title">Коллега на утреннюю сессию</h2>
    <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
      <tr class="chet">
        <td width="250"><strong>Имя коллеги</strong></td>
        <td><? if(isset($arResult["RESULT"]["college_name"]["ANSWER_VALUE"][0]["USER_TEXT"])){echo $arResult["RESULT"]["college_name"]["ANSWER_VALUE"][0]["USER_TEXT"];}?>&nbsp;</td>
      </tr>
      <tr>
        <td><strong>Фамилия коллеги</strong></td>
        <td><? if(isset($arResult["RESULT"]["college_surname"]["ANSWER_VALUE"][0]["USER_TEXT"])){echo $arResult["RESULT"]["college_surname"]["ANSWER_VALUE"][0]["USER_TEXT"];}?>&nbsp;</td>
      </tr>
      <tr class="chet">
        <td><strong>Должность коллеги</strong></td>
        <td><? if(isset($arResult["RESULT"]["college_job"]["ANSWER_VALUE"][0]["USER_TEXT"])){echo $arResult["RESULT"]["college_job"]["ANSWER_VALUE"][0]["USER_TEXT"];}?>&nbsp;</td>
      </tr>
      <tr>
        <td><strong>E-mail коллеги</strong></td>
        <td><? if(isset($arResult["RESULT"]["college_email"]["ANSWER_VALUE"][0]["USER_TEXT"])){echo $arResult["RESULT"]["college_email"]["ANSWER_VALUE"][0]["USER_TEXT"];}?>&nbsp;</td>
      </tr>
    </table>
<?
if($arResult["EDIT_ACT"] != "N"){
	?><p class="reg_update"><a href="<?=$arParams["EDIT_URL"]?>">Редактировать информацию</a></p><?
	}
?>
