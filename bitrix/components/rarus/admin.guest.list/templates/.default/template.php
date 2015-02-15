<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? /*?>
������������� 	�������� 	��������� 	����� 	����� 	������ 	������ 	������� 	E-mail 	Web-site �������� 	�������� ���
�������� ������������ �������� 	������������ ����������� 	����� ���������
����������� ���� 	����������� ����� 	����������� HB
������������� 	����*/?>
<?
$arShowedTableCols = array(
		"ID"=>"ID",
		"��������"=>0,
		"�������������"=>array(7, 8),
		"���������"=>9,
		"�����"=>2,
		"�����"=>4,
		"������"=>array(5, 6),
		"������"=>3,
		"�������"=>10,
		"�����"=>11,
		"���-����"=>13
);
$arShowedTableColsBool = array();
switch($arParams["ACT"]) {
	case "off":
		$arShowedTableCols["�������� ������������"] = "29";
		$arShowedTableCols["����� ���������"] =  array(40, 41);
		$arShowedTableColsBool = array("�����. ����"=>"UF_MR", "�����. �����"=>"UF_EV", "�����. ��"=>"UF_HB");
	break;

	case "evening":
		$arShowedTableCols["����� ���������"] =  array(40, 41);
		$arShowedTableCols["��� ������� (�����)"] = "14";
		$arShowedTableCols["������� ������� (�����)"] = "15";
		$arShowedTableCols["��������� ������� (�����)"] = "16";
		$arShowedTableCols["����� ������� (�����)"] = "17";
		$arShowedTableCols["��� �������2 (�����)"] = "18";
		$arShowedTableCols["������� �������2 (�����)"] = "19";
		$arShowedTableCols["����� �������2 (�����)"] = "20";
		$arShowedTableCols["��������� �������2 (�����)"] = "21";
		$arShowedTableCols["��� �������3 (�����)"] = "22";
		$arShowedTableCols["������� �������3 (�����)"] = "23";
		$arShowedTableCols["��������� �������3 (�����)"] = "24";
		$arShowedTableCols["����� �������3 (�����)"] = "25";
	break;

	case "morning":case"hostbuy":
		$arShowedTableCols["�����"] = "LOGIN";
		$arShowedTableCols["������"] = "27";
		$arShowedTableCols["��� ������� �� ����"] = "36";
		$arShowedTableCols["������� ������� �� ����"] = "37";
		$arShowedTableCols["��������� �������"] = "38";
		$arShowedTableCols["����� �������"] = "39";
		$arShowedTableCols["�������� ������������"] = "29";
		$arShowedTableCols["���������. �������."] = array(30, 31, 32, 33, 34, 35);
		$arShowedTableCols["����� ���������"] =  array(40, 41);
	break;
}

//��������� ���� ��� � ���� ��� HB
if($arParams["ACT"] == "hostbuy")
{
	$arShowedTableCols["���"] = "42";
	$arShowedTableCols["����"] = "43";
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
	� ������ ��������� ��� ������
<?else:?>
<form action="" method="post">
	<?switch($arParams["ACT"]):
		case "off":?>
			<input class="custom-buttom confirm-participate-button-mass" type="button" name="confirm" value="����������� �������">
			<? /*
			<input class="custom-buttom" type="button" name="edit" disabled value="�������������">
			*/?>
			<input class="custom-buttom spam-guest-button-mass" type="button" name="spam"  value="� ����">
			<input type="hidden" name="SPAM_TYPE"  value="Y">
            <a class="custom-buttom" href="/exel/guest.php?type=guests_no&app=<?=$arResult["EXHIB"]["CODE"]?>">������������ Excel</a>

		<?break?>
		<?case "spam":?>
			<input class="custom-buttom spam-guest-button-mass" type="button" name="spam"  value="������������">
			<input type="hidden" name="SPAM_TYPE"  value="N">

		<?break?>
		<?case "evening":?>
		    <a class="custom-buttom" href="/exel/guest.php?type=guests_ev&app=<?=$arResult["EXHIB"]["CODE"]?>">������������ Excel</a>
            <a class="custom-buttom" href="/exel/guest.php?type=guests_ev_all&app=<?=$arResult["EXHIB"]["CODE"]?>">Excel (��� ����)</a>
            <? /*
			<input class="custom-buttom" type="button" name="edit" disabled value="�������������">
			<input class="custom-buttom" type="button" name="spam" disabled value="� ����">
			<input class="custom-buttom unconfirm-participate-button-mass" type="button" name="unconfirm" value="��������� � ����������������">
			*/?>
		<?break?>
		<?case "morning":?>		
            <a class="custom-buttom" href="/exel/guest.php?type=guests&app=<?=$arResult["EXHIB"]["CODE"]?>">������������ Excel</a>
            <a class="custom-buttom" href="/exel/guest.php?type=guests_all&app=<?=$arResult["EXHIB"]["CODE"]?>">Excel (��� ����)</a>
			<? /*<input class="custom-buttom" type="button" name="edit" disabled value="�������������">$arParams["EXHIBIT_CODE"]
			<input class="custom-buttom" type="button" name="generate-schedule" disabled value="������������ ����������">
			<input class="custom-buttom" type="button" name="generate-wishlist" disabled value="������������ �������">
			<input class="custom-buttom unconfirm-participate-button-mass" type="button" name="unconfirm" value="��������� � ����������������">
			<input class="custom-buttom" type="button" name="cancell-participation" disabled value="�������� �������">
			*/?>
		<?break?>
		<?case "hostbuy":?>
		    <a class="custom-buttom" href="/exel/guest.php?type=guests_hb&app=<?=$arResult["EXHIB"]["CODE"]?>">������������ Excel</a>
            <a class="custom-buttom" href="/exel/guest.php?type=guests_hb_all&app=<?=$arResult["EXHIB"]["CODE"]?>">Excel (��� ����)</a>
			<? /*<input class="custom-buttom" type="button" name="edit" disabled value="�������������">$arParams["EXHIBIT_CODE"]
			<input class="custom-buttom" type="button" name="generate-schedule" disabled value="������������ ����������">
			<input class="custom-buttom" type="button" name="generate-wishlist" disabled value="������������ �������">
			<input class="custom-buttom unconfirm-participate-button-mass" type="button" name="unconfirm" value="��������� � ����������������">
			<input class="custom-buttom" type="button" name="cancell-participation" disabled value="�������� �������">
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
			<th width="75px">��������� ��������</th>
			<?foreach($arShowedTableCols as $key=>$val):?>
				<th><?=$key?></th>
			<?endforeach?>
			<?/*foreach($arFormPos as $key=>$val):?>
				<th><?=$key?></th>
			<?endforeach*/?>
			<?foreach($arShowedTableColsBool as $key=>$val):?>
				<th><?=$key?></th>
			<?endforeach?>
			<th>��������</th>
		</tr>
		<?$i=0;foreach($arResult["USERS_LIST"] as $arUser):?>
			<tr class="<?if($i++%2):?>odd<?else:?>even<?endif?>">
				<td class="text-center"><input type="checkbox" name="SELECTED_USERS[]" value="<?=$arUser["ID"]?>"></td>
				<?foreach($arShowedTableCols as $key=>$val):?>
					<td>
					<? if($key != "������"):?>
						<?=printVal(returnVal($arUser, $val, $arParams["GUEST_FORM_ID"]), "<br>")?>
					<? else:?>
						<?=$arUser["UF_PAS"]?>
					<br /><? $href = "/admin/service/pass.php?uid=" . $arUser["ID"]?>
					<a
                        href="<?= $href?>"
                        target="_blank"
                        onclick="newWind('<?= $href?>', 500, 300); return false;"
                        >������������� ������</a>
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
									<li><a href="" data-user-id="<?=$arUser["ID"]?>" class="confirm-participate-button">�����������&nbsp;�������</a></li>
									<li><a href="<?= $edithrefUconf?>" target="_blank" onclick="newWind('<?= $edithrefUconf?>', 500, 600); return false;" >�������������</a></li>
									<li><? $href = "/admin/service/pass.php?uid=" . $arUser["ID"]?>
                                        <a
                                            href="<?= $href?>"
                                            target="_blank"
                                            onclick="newWind('<?= $href?>', 500, 300); return false;"
                                            >������������� ������</a>
                                    </li>
									<li><a href="" data-user-id="<?=$arUser["ID"]?>" class="spam-guest-button">� ����</a></li>
								<?break?>
									<? case "spam":?>

									<li><a href="<?= $edithrefUconf?>" target="_blank" onclick="newWind('<?= $edithrefUconf?>', 500, 600); return false;" >�������������</a></li>
									<li><? $href = "/admin/service/pass.php?uid=" . $arUser["ID"]?>
                                        <a
                                            href="<?= $href?>"
                                            target="_blank"
                                            onclick="newWind('<?= $href?>', 500, 300); return false;"
                                            >������������� ������</a>
                                    </li>
									<li><a href="" data-user-id="<?=$arUser["ID"]?>" class="spam-guest-button">������������</a></li>
								<?break?>
								<?case "evening":?>
									<li><a href="" data-user-id="<?=$arUser["ID"]?>" class="unconfirm-participate-button">��������� � ����������������</a></li>
									<li><a href="<?= $edithrefConf?>" target="_blank" onclick="newWind('<?= $edithrefConf?>', 500, 600); return false;" >�������������</a></li>
									<li><? $href = "/admin/service/pass.php?uid=" . $arUser["ID"]?>
                                        <a
                                            href="<?= $href?>"
                                            target="_blank"
                                            onclick="newWind('<?= $href?>', 500, 300); return false;"
                                            >������������� ������</a>
                                    </li>
									<? /*?>
									<input class="custom-buttom" type="submit" name="spam" disabled value="� ����">
									<input class="custom-buttom" type="submit" name="unconfirm" disabled value="��������� � ����������������">*/?>
								<?break?>
								<?case "morning":case "hostbuy":?>
									<li><a href="" data-user-id="<?=$arUser["ID"]?>" class="unconfirm-participate-button">��������� � ����������������</a></li>
									<li><a href="<?= $edithrefConf?>" target="_blank" onclick="newWind('<?= $edithrefConf?>', 500, 600); return false;" >�������������</a></li>
									<li><? $href = "/admin/service/pass.php?uid=" . $arUser["ID"]?>
                                        <a
                                            href="<?= $href?>"
                                            target="_blank"
                                            onclick="newWind('<?= $href?>', 500, 300); return false;"
                                            >������������� ������</a>
                                    </li>
									<li><a
                            	href="/admin/service/pdf_shedule_guest.php?id=<?=$arUser["ID"]?>&app=<?=$arResult["EXHIB"]["PROPERTIES"]["APP_ID"]["VALUE"]?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf"
                                target="_blank"
                                onclick="newWind('/admin/service/pdf_shedule_guest.php?id=<?=$arUser["ID"]?>&app=<?=$arResult["EXHIB"]["PROPERTIES"]["APP_ID"]["VALUE"]?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf', 600, 700); return false;">������������ ����������</a></li>
									<li><a
                            	href="/admin/service/wishlist_guest.php?id=<?=$arUser["ID"]?>&app=<?=$arResult["EXHIB"]["PROPERTIES"]["APP_ID"]["VALUE"]?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf"
                                target="_blank"
                                onclick="newWind('/admin/service/wishlist_guest.php?id=<?=$arUser["ID"]?>&app=<?=$arResult["EXHIB"]["PROPERTIES"]["APP_ID"]["VALUE"]?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf', 600, 700); return false;">������������ �������</a></li>
                                <? if($arParams["ACT"] == "hostbuy" && (isset($arResult["EXHIB"]["PROPERTIES"]["APP_HB_ID"]["VALUE"]) && $arResult["EXHIB"]["PROPERTIES"]["APP_HB_ID"]["VALUE"] != '')){
                                	$appId = $arResult["EXHIB"]["PROPERTIES"]["APP_HB_ID"]["VALUE"];
                                	?>
                                	<li><a
                            	href="/admin/service/pdf_shedule_guest_hb.php?id=<?=$arUser["ID"]?>&app=<?=$appId?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf"
                                target="_blank"
                                onclick="newWind('/admin/service/pdf_shedule_guest_hb.php?id=<?=$arUser["ID"]?>&app=<?=$appId?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf', 600, 700); return false;">������������ ���������� HB</a></li>
									<li><a
                            	href="/admin/service/wishlist_guest_hb.php?id=<?=$arUser["ID"]?>&app=<?=$appId?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf"
                                target="_blank"
                                onclick="newWind('/admin/service/wishlist_guest_hb.php?id=<?=$arUser["ID"]?>&app=<?=$appId?>&exhib=<?=$arResult['EXHIB']['CODE']?>&type=p&mode=pdf', 600, 700); return false;">������������ ������� HB</a></li>
                                <?
                                }
                                ?>
									<? /*?>
									<input class="custom-buttom" type="submit" name="unconfirm" disabled value="��������� � ����������������">
									<input class="custom-buttom" type="submit" name="cancell-participation" disabled value="�������� �������">*/?>
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
//� ��������������
$(document).on("click", ".confirm-participate-button", function() {
	return sendAjaxUpdate("/admin/guest/guest-set-confirmed.php",
			"USER_ID="+$(this).data("user-id")+"&"+$(this).closest("form").serialize());
});
$(document).on("click", ".confirm-participate-button-mass", function() {
	return sendAjaxUpdate("/admin/guest/guest-set-confirmed.php",
			$(this).closest("form").serialize())
});

//� ����������������
$(document).on("click", ".unconfirm-participate-button", function() {
	return sendAjaxUpdate("/admin/guest/guest-set-unconfirmed.php",
			"USER_ID="+$(this).data("user-id")+"&"+$(this).closest("form").serialize())
});
$(document).on("click", ".unconfirm-participate-button-mass", function() {
	return sendAjaxUpdate("/admin/guest/guest-set-unconfirmed.php",
			$(this).closest("form").serialize())
});

//� ����
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