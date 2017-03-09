<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$data = $arResult["DATA"];
$user = $arResult["USER"];

if($arResult["ERROR_MESSAGE"] == ''){
	if($arResult["TYPE"] == "FORM"){
	?>
	<form action="" method="post" name="count">
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td width="150"><strong>Номер счета</strong></td>
        <td>
            <input type="text" name="<?= CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_539", $arResult["FORM_ID"])?>" value="<?= ($data["PAY_NAME"])?$data["PAY_NAME"]:$user["ID"];?>"> <? /*<div class="edit-button"></div>*/?>
        </td>
      </tr>
      <tr>
        <td width="150"><strong>Сумма</strong></td>
        <td>
            <input type="text" name="<?= CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_680", $arResult["FORM_ID"])?>" value="<?= $data["PAY_COUNT"]?>">
        </td>
      </tr>
      <tr>
        <td><strong>Получатель платежа</strong></td>

         <td>
             <? $selected = $data["PAY_REQUISITE"];?>
            <? foreach ($arResult["PAY_REQUISITE"] as $arRequisite):?>
                <input type="radio" name="<?= CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_667", $arResult["FORM_ID"])?>" value="<?= $arRequisite["ID"]?>" <?= (("checked" == $arRequisite["FIELD_PARAM"] && !$selected) || ($selected == $arRequisite["ID"]))?'checked="checked"':"";?> id="PAY_REQUISITE_<?= $arRequisite["ID"]?>">
                <label for="PAY_REQUISITE_<?= $arRequisite["ID"]?>"><?= $arRequisite["MESSAGE"]?></label>
                <br />
            <? endforeach;?>
		</td>
      </tr>
      <tr>
        <td><strong>Название компании</strong></td>
        <td><?= $data["COMPANY_NAME_INVOICE"]?></td>
      </tr>
      <tr>
        <td><strong>Представитель</strong></td>
        <td><?= $data["FIRST_NAME"]?> <?= $data["LAST_NAME"]?></td>
      </tr>
      <tr>
        <td><strong>Адрес компании</strong></td>
        <td><?= $data["ADDRESS"]?></td>
      </tr>
      <tr class="send">
        <td><input type="submit" value="Посмотреть счет" name="button_look"/></td>
        <td><input type="submit" value="Выставить счет" name="make"/>&nbsp; &nbsp; &nbsp;<input type="submit" value="Сохранить" name="save"/></td>
      </tr>
    </table>
        <input name="RESULT_ID" type="hidden" value="<?= $arResult["USER_RESULT_ID"]?>" />
        <input name="FORM_ID" type="hidden" value="<?= $arResult["FORM_ID"]?>" />
    </form>
	<?
	}
	elseif($arResult["TYPE"] == "SENT"){
	?>
    <br />
    <br />
    <p style="padding-left:10px;">Счет был успешно отправлен по адресу <?=$data['EMAIL']?></p>
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
    <br />
    <br />
	<?
}
?>