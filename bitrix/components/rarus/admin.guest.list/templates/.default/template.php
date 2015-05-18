
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? /*?>
Представитель 	Компания 	Должность 	Адрес 	Город 	Страна 	Индекс 	Телефон 	E-mail 	Web-site компании 	Гостевое имя
Описание деятельности компании 	Приоритетные направления 	Форма посещения
Подтвердить утро 	Подтвердить вечер 	Подтвердить HB
Редактировать 	Спам*/?>
<?
//значение из CFormMatrix::$arFormGuestQuestions
$arShowedTableCols = array(
		"ID"=>"ID",
		"Компания"=>0,
		"Представитель"=>array(7, 8),
		"Должность"=>9,
		"Адрес"=>2,
		"Город"=>4,
		"Страна"=>array(5, 6),
		"Индекс"=>3,
		"Телефон"=>10,
		"Мобильный телефон"=>11,
		"Скайп"=>12,
		"Емейл"=>13,
		"Веб-сайт"=>15
);
$arShowedTableColsBool = array();
switch($arParams["ACT"]) {
	case "off":
		$arShowedTableCols["Описание деятельности"] = "31";
		$arShowedTableCols["Форма посещения"] =  array(42, 43);
		$arShowedTableColsBool = array("Подтв. утро"=>"UF_MR", "Подтв. вечер"=>"UF_EV", "Подтв. НВ"=>"UF_HB");
	break;

	case "evening":
		$arShowedTableCols["Форма посещения"] =  array(42, 43);
		$arShowedTableCols["Имя коллеги (вечер)"] = "16";
		$arShowedTableCols["Фамилия коллеги (вечер)"] = "17";
		$arShowedTableCols["Должность коллеги (вечер)"] = "18";
		$arShowedTableCols["Емейл коллеги (вечер)"] = "19";
		$arShowedTableCols["Имя коллеги2 (вечер)"] = "20";
		$arShowedTableCols["Фамилия коллеги2 (вечер)"] = "21";
		$arShowedTableCols["Емейл коллеги2 (вечер)"] = "22";
		$arShowedTableCols["Должность коллеги2 (вечер)"] = "23";
		$arShowedTableCols["Имя коллеги3 (вечер)"] = "24";
		$arShowedTableCols["Фамилия коллеги3 (вечер)"] = "25";
		$arShowedTableCols["Должность коллеги3 (вечер)"] = "26";
		$arShowedTableCols["Емейл коллеги3 (вечер)"] = "27";
	break;

	case "morning":case"hostbuy":
		$arShowedTableCols["Логин"] = "LOGIN";
		$arShowedTableCols["Пароль"] = "29";
		$arShowedTableCols["Имя коллеги на утро"] = "38";
		$arShowedTableCols["Фамилия коллеги на утро"] = "39";
		$arShowedTableCols["Должность коллеги"] = "40";
		$arShowedTableCols["Емейл коллеги"] = "41";
		$arShowedTableCols["Описание деятельности"] = "31";
		$arShowedTableCols["Приоритет. направл."] = array(32, 33, 34, 35, 36, 37);
		$arShowedTableCols["Форма посещения"] =  array(42, 43);
	break;
}

//добавляем поля зал и стол для HB
if($arParams["ACT"] == "hostbuy")
{
	$arShowedTableCols["Зал"] = "44";
	$arShowedTableCols["Стол"] = "45";
}

function getValById($ar, $id, $formId)
{
	if(isset($ar[$id])) {
		return $ar[$id];
	}

	$v = CFormMatrix::getFormQuestionIdByFormIDAndQuestionName($formId, $id);
	if($v && isset($ar[$v])) {
		return $ar[$v];
	}

	return "";
}

function returnVal($ar, $val, $formId)
{
	if(is_array($val)) {
		$result = array();
		foreach($val as $valval) {
			$result[] = returnVal($ar, $valval, $formId);
		}
	} else {
		$result = array(getValById($ar, $val, $formId));
	}

	return $result;
}

function printVal($ar, $glue)
{
		$result = "";

		if(is_array($ar)) {
			foreach ($ar as $val) {
				if($val) {
					if($result) {
						$result .= $glue;
					}
					$result .= printVal($val, $glue);
				}
			}
		} else {
			$result = $ar;
		}

		return $result;
}
?>
<?if(empty($arResult["USERS_LIST"])):?>
	В данной категории нет гостей
<?else:?>
<form action="" method="post">
	<?switch($arParams["ACT"]):
		case "off":?>
			<input class="custom-buttom confirm-participate-button-mass" type="button" name="confirm" value="Подтвердить участие">
			<? /*
			<input class="custom-buttom" type="button" name="edit" disabled value="Редактировать">
			*/?>
			<input class="custom-buttom spam-guest-button-mass" type="button" name="spam"  value="В спам">
			<input type="hidden" name="SPAM_TYPE"  value="Y">
            <a class="custom-buttom" href="/exel/guest.php?type=guests_no&app=<?=$arResult["EXHIB"]["CODE"]?>">Генерировать Excel</a>

		<?break?>
		<?case "spam":?>
			<input class="custom-buttom spam-guest-button-mass" type="button" name="spam"  value="Восстановить">
			<input type="hidden" name="SPAM_TYPE"  value="N">

		<?break?>
		<?case "evening":?>
		    <a class="custom-buttom" href="/exel/guest.php?type=guests_ev&app=<?=$arResult["EXHIB"]["CODE"]?>">Генерировать Excel</a>
            <a class="custom-buttom" href="/exel/guest.php?type=guests_ev_all&app=<?=$arResult["EXHIB"]["CODE"]?>">Excel (все люди)</a>
            <? /*
			<input class="custom-buttom" type="button" name="edit" disabled value="Редактировать">
			<input class="custom-buttom" type="button" name="spam" disabled value="В СПАМ">
			<input class="custom-buttom unconfirm-participate-button-mass" type="button" name="unconfirm" value="Отправить в Неподтвержденные">
			*/?>
		<?break?>
		<?case "morning":?>		
            <a class="custom-buttom" href="/exel/guest.php?type=guests&app=<?=$arResult["EXHIB"]["CODE"]?>">Генерировать Excel</a>
            <a class="custom-buttom" href="/exel/guest.php?type=guests_all&app=<?=$arResult["EXHIB"]["CODE"]?>">Excel (все люди)</a>
			<? /*<input class="custom-buttom" type="button" name="edit" disabled value="Редактировать">$arParams["EXHIBIT_CODE"]
			<input class="custom-buttom" type="button" name="generate-schedule" disabled value="Генерировать расписание">
			<input class="custom-buttom" type="button" name="generate-wishlist" disabled value="Генерировать вишлист">
			<input class="custom-buttom unconfirm-participate-button-mass" type="button" name="unconfirm" value="Отправить в Неподтвержденные">
			<input class="custom-buttom" type="button" name="cancell-participation" disabled value="Отменить участие">
			*/?>
		<?break?>
		<?case "hostbuy":?>
		    <a class="custom-buttom" href="/exel/guest.php?type=guests_hb&app=<?=$arResult["EXHIB"]["CODE"]?>">Генерировать Excel</a>
            <a class="custom-buttom" href="/exel/guest.php?type=guests_hb_all&app=<?=$arResult["EXHIB"]["CODE"]?>">Excel (все люди)</a>
			<? /*<input class="custom-buttom" type="button" name="edit" disabled value="Редактировать">$arParams["EXHIBIT_CODE"]
			<input class="custom-buttom" type="button" name="generate-schedule" disabled value="Генерировать расписание">
			<input class="custom-buttom" type="button" name="generate-wishlist" disabled value="Генерировать вишлист">
			<input class="custom-buttom unconfirm-participate-button-mass" type="button" name="unconfirm" value="Отправить в Неподтвержденные">
			<input class="custom-buttom" type="button" name="cancell-participation" disabled value="Отменить участие">
			*/?>
		<?break?>
	<?endswitch?>
	<?foreach($arResult["USERS_LIST"] as $arUser):?>
		<input type="hidden" name="USERS_LIST[]" value="<?=$arUser["ID"]?>">
	<?endforeach?>
	<input type="hidden" name="EXHIB_ID" value="<?=$arResult["EXHIB"]["ID"]?>">
	<div class="navigate"><?=$arResult["NAVIGATE"]?></div>
	<table class="list" style="min-width: 100%;">
		<tr class="odd">
			<th width="75px">Групповые действия</th>
			<?foreach($arShowedTableCols as $key=>$val):?>
				<th><?=$key?></th>
			<?endforeach?>
			<?/*foreach($arFormPos as $key=>$val):?>
				<th><?=$key?></th>
			<?endforeach*/?>
			<?foreach($arShowedTableColsBool as $key=>$val):?>
				<th><?=$key?></th>
			<?endforeach?>
			<th>Действия</th>
		</tr>
		<?$i=0;foreach($arResult["USERS_LIST"] as $arUser):?>
			<tr class="<?if($i++%2):?>odd<?else:?>even<?endif?>">
				<td class="text-center"><input type="checkbox" name="SELECTED_USERS[]" value="<?=$arUser["ID"]?>"></td>
				<?foreach($arShowedTableCols as $key=>$val):?>
					<td>
					<? if($key != "Пароль"):?>
						<?=printVal(returnVal($arUser, $val, $arParams["GUEST_FORM_ID"]), "<br>")?>
					<? else:?>
						<?=$arUser["UF_PAS"]?>
					<br /><? $href = "/admin/service/pass.php?uid=" . $arUser["ID"]?>
					<a
                        href="<?= $href?>"
                        target="_blank"
                        onclick="newWind('<?= $href?>', 500, 300); return false;"
                        >Редактировать пароль</a>
					<? endif;?>
					</td>
				<?endforeach?>
				<?/*foreach($arFormPos as $key=>$val):?>
					<td>
						<?foreach($val as $v):?>
							<?if(($k = CFormMatrix::getFormQuestionIdByFormIDAndQuestionName($arParams["GUEST_FORM_ID"], $v))
									&& isset($arUser[$k])):?>
								<?=$arUser[$k]?>
							<?endif?>
						<?endforeach?>
					</td>
				<?endforeach*/?>
				<?foreach($arShowedTableColsBool as $key=>$val):?>
					<td class="text-center">
						<?if(isset($arUser[ $val ]) && $arUser[ $val ]):?>
							<input type="checkbox" name="CONFIRM_<?=$val?>_<?=$arUser["ID"]?>" value="on" checked>
						<?else:?>
							<input type="checkbox" name="CONFIRM_<?=$val?>_<?=$arUser["ID"]?>" value="on">
						<?endif?>
					</td>
				<?endforeach?>
				<td class="text-center">
					<div class="action" id="action_<?=$arUser["ID"]?>">
						<img src="/bitrix/templates/admin/images/edit.png">
						<ul class="ul-popup">
						    <? $edithrefConf = "/admin/service/edit.php?id=" . $arUser["ID"]."&result=". $arUser["UF_ID_COMP"] . "&type=G";?>
						    <? $edithrefUconf = "/admin/service/edit.php?id=" . $arUser["ID"]."&result=". $arUser["UF_ID_COMP"] . "&type=G";?>
							<?switch($arParams["ACT"]):
								case "off":?>
									<li><a href="" data-user-id="<?=$arUser["ID"]?>" class="confirm-participate-button">Подтвердить&nbsp;участие</a></li>
									<li><a href="<?= $edithrefUconf?>" target="_blank" onclick="newWind('<?= $edithrefUconf?>', 500, 600); return false;" >Редактировать</a></li>
									<li><? $href = "/admin/service/pass.php?uid=" . $arUser["ID"]?>
                                        <a
                                            href="<?= $href?>"
                                            target="_blank"
                                            onclick="newWind('<?= $href?>', 500, 300); return false;"
                                            >Редактировать пароль</a>
                                    </li>
									<li><a href="" data-user-id="<?=$arUser["ID"]?>" class="spam-guest-button">В спам</a></li>
								<?break?>
									<? case "spam":?>

									<li><a href="<?= $edithrefUconf?>" target="_blank" onclick="newWind('<?= $edithrefUconf?>', 500, 600); return false;" >Редактировать</a></li>
									<li><? $href = "/admin/service/pass.php?uid=" . $arUser["ID"]?>
                                        <a
                                            href="<?= $href?>"
                                            target="_blank"
                                            onclick="newWind('<?= $href?>', 500, 300); return false;"
                                            >Редактировать пароль</a>
                                    </li>
									<li><a href="" data-user-id="<?=$arUser["ID"]?>" class="spam-guest-button">Восстановить</a></li>
								<?break?>
								<?case "evening":?>
									<li><a href="" data-user-id="<?=$arUser["ID"]?>" class="unconfirm-participate-button">Отправить в Неподтвержденные</a></li>
									<li><a href="<?= $edithrefConf?>" target="_blank" onclick="newWind('<?= $edithrefConf?>', 500, 600); return false;" >Редактировать</a></li>
									<li><? $href = "/admin/service/pass.php?uid=" . $arUser["ID"]?>
                                        <a
                                            href="<?= $href?>"
                                            target="_blank"
                                            onclick="newWind('<?= $href?>', 500, 300); return false;"
                                            >Редактировать пароль</a>
                                    </li>
									<? /*?>
									<input class="custom-buttom" type="submit" name="spam" disabled value="В СПАМ">
									<input class="custom-buttom" type="submit" name="unconfirm" disabled value="Отправить в Неподтвержденные">*/?>
								<?break?>
								<?case "morning":case "hostbuy":?>
									<li><a href="" data-user-id="<?=$arUser["ID"]?>" class="unconfirm-participate-button">Отправить в Неподтвержденные</a></li>
									<li><a href="<?= $edithrefConf?>" target="_blank" onclick="newWind('<?= $edithrefConf?>', 500, 600); return false;" >Редактировать</a></li>
									<li><? $href = "/admin/service/pass.php?uid=" . $arUser["ID"]?>
                                        <a
                                            href="<?= $href?>"
                                            target="_blank"
                                            onclick="newWind('<?= $href?>', 500, 300); return false;"
                                            >Редактировать пароль</a>
                                    </li>
									<li><a
                            	href="/admin/service/pdf_shedule_guest.php?id=<?=$arUser["ID"]?>&app=<?=$arResult["EXHIB"]["PROPERTIES"]["APP_ID"]["VALUE"]?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf"
                                target="_blank"
                                onclick="newWind('/admin/service/pdf_shedule_guest.php?id=<?=$arUser["ID"]?>&app=<?=$arResult["EXHIB"]["PROPERTIES"]["APP_ID"]["VALUE"]?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf', 600, 700); return false;">Генерировать расписание</a></li>
									<li><a
                            	href="/admin/service/wishlist_guest.php?id=<?=$arUser["ID"]?>&app=<?=$arResult["EXHIB"]["PROPERTIES"]["APP_ID"]["VALUE"]?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf"
                                target="_blank"
                                onclick="newWind('/admin/service/wishlist_guest.php?id=<?=$arUser["ID"]?>&app=<?=$arResult["EXHIB"]["PROPERTIES"]["APP_ID"]["VALUE"]?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf', 600, 700); return false;">Генерировать вишлист</a></li>
                                <? if($arParams["ACT"] == "hostbuy" && (isset($arResult["EXHIB"]["PROPERTIES"]["APP_HB_ID"]["VALUE"]) && $arResult["EXHIB"]["PROPERTIES"]["APP_HB_ID"]["VALUE"] != '')){
                                	$appId = $arResult["EXHIB"]["PROPERTIES"]["APP_HB_ID"]["VALUE"];
                                	?>
                                	<li><a
                            	href="/admin/service/pdf_shedule_guest_hb.php?id=<?=$arUser["ID"]?>&app=<?=$appId?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf"
                                target="_blank"
                                onclick="newWind('/admin/service/pdf_shedule_guest_hb.php?id=<?=$arUser["ID"]?>&app=<?=$appId?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf', 600, 700); return false;">Генерировать расписание HB</a></li>
									<li><a
                            	href="/admin/service/wishlist_guest_hb.php?id=<?=$arUser["ID"]?>&app=<?=$appId?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf"
                                target="_blank"
                                onclick="newWind('/admin/service/wishlist_guest_hb.php?id=<?=$arUser["ID"]?>&app=<?=$appId?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf', 600, 700); return false;">Генерировать вишлист HB</a></li>
                                <?
                                }
                                ?>
									<? /*?>
									<input class="custom-buttom" type="submit" name="unconfirm" disabled value="Отправить в Неподтвержденные">
									<input class="custom-buttom" type="submit" name="cancell-participation" disabled value="Отменить участие">*/?>
								<?break?>
							<?endswitch?>
						</ul>
					</div>
				</td>
			</tr>
		<?endforeach?>
	</table>
</form>
<?endif?>

<script>
//в подтвержденные
$(document).on("click", ".confirm-participate-button", function() {
	return sendAjaxUpdate("/admin/guest/guest-set-confirmed.php",
			"USER_ID="+$(this).data("user-id")+"&"+$(this).closest("form").serialize());
});
$(document).on("click", ".confirm-participate-button-mass", function() {
	return sendAjaxUpdate("/admin/guest/guest-set-confirmed.php",
			$(this).closest("form").serialize())
});

//в неподтвержденные
$(document).on("click", ".unconfirm-participate-button", function() {
	return sendAjaxUpdate("/admin/guest/guest-set-unconfirmed.php",
			"USER_ID="+$(this).data("user-id")+"&"+$(this).closest("form").serialize())
});
$(document).on("click", ".unconfirm-participate-button-mass", function() {
	return sendAjaxUpdate("/admin/guest/guest-set-unconfirmed.php",
			$(this).closest("form").serialize())
});

//в спам
$(document).on("click", ".spam-guest-button", function() {
	return sendAjaxUpdate("/admin/guest/guest-set-spam.php",
			"USER_ID="+$(this).data("user-id")+"&"+$(this).closest("form").serialize())
});
$(document).on("click", ".spam-guest-button-mass", function() {
	return sendAjaxUpdate("/admin/guest/guest-set-spam.php",
			$(this).closest("form").serialize())
});


function sendAjaxUpdate(url, data) {
	$.ajax({
		url: url,
		method: "POST",
		data: data,
		success: function(){document.location.reload(true)},
		error: function(d){alert("error: "+d.responseText)}
		});
	return false;
}
</script>