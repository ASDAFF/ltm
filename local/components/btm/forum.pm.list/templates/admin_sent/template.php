<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
if (!$this->__component->__parent || empty($this->__component->__parent->__name)):
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/themes/blue/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/styles/additional.css');
endif;
$GLOBALS['APPLICATION']->AddHeadString('<script src="/bitrix/js/main/utils.js"></script>', true);
$GLOBALS['APPLICATION']->AddHeadString('<script src="/bitrix/components/bitrix/forum.interface/templates/.default/script.js"></script>', true);
/********************************************************************
				Input params
********************************************************************/
/***************** BASE ********************************************/
$iIndex = rand();
//$arResult["FID"] = (is_array($arResult["FID"]) ? $arResult["FID"] : array($arResult["FID"]));
/********************************************************************
				/Input params
********************************************************************/
if (!empty($arResult["ERROR_MESSAGE"])):
?>
<p style="color:#F00;"><?=ShowError($arResult["ERROR_MESSAGE"], "forum-note-error");?></p>
<?
endif;
if (!empty($arResult["OK_MESSAGE"])):
?>
<p style="color:#F00;"><?=ShowNote($arResult["OK_MESSAGE"], "forum-note-success")?></p>
<?
endif;
?>
<?
if ($arResult["NAV_RESULT"]->NavPageCount > 0):
?>
		<p><?=$arResult["NAV_STRING"]?></p>
<?
endif;
?>
    <script type="text/javascript">
	  function newWind(reciver){
		  var recHref = reciver;
		  window.open(recHref,'particip_appoint', 'scrollbars=yes,resizable=yes,width=500, height=400, left='+(screen.availWidth/2-250)+', top='+(screen.availHeight/2-200)+'');
		  return false;
	  }
    </script>
    <table border="0" cellspacing="0" cellpadding="7" class="admin_info">
        <tr class="odd">
            <td width="210"><strong><?=GetMessage("PM_HEAD_SUBJ")?></strong><?=$arResult["SortingEx"]["POST_SUBJ"]?></td>
            <td width="210"><strong><?=GetMessage("PM_HEAD_COMPANY")?></strong><?=$arResult["SortingEx"]["COMPANY"]?></td>
            <td width="140"><strong><?
			if ($arResult["StatusUser"] == "RECIPIENT"):
				?><?=GetMessage("PM_HEAD_RECIPIENT")?><?
			elseif ($arResult["StatusUser"] == "SENDER"):
				?><?=GetMessage("PM_HEAD_SENDER")?><?
			else:
				?><?=GetMessage("PM_HEAD_AUTHOR")?><?
			endif;
			?></strong><?=$arResult["SortingEx"]["AUTHOR_NAME"]?></td>
            <td width="100"><strong><?=GetMessage("PM_HEAD_DATE")?></strong><?=$arResult["SortingEx"]["POST_DATE"]?></td>
        </tr>
<?
if ($arResult["MESSAGE"] == "N" || empty($arResult["MESSAGE"])):
?>
 				<tr>
					<td colspan="3"><?=GetMessage("PM_EMPTY_FOLDER")?></td>
				</tr>
<?
else:
?>
			<tbody>
<?
	$iCount = 0;
	foreach ($arResult["MESSAGE"] as $res):
	$class = ($iCount % 2)?"odd":"even";
?>
        <tr class="<?= $class?>">
            <td>
            <?=$res["POST_SUBJ"]?><br />
            <strong><a href="/admin/service/read.php?mes=<?=$res["ID"]?>" target="_blank" onclick="newWind('/admin/service/read.php?mes=<?=$res["ID"]?>'); return false;">Open</a></strong>, <strong><a href="/admin/service/write.php?id=<?=$res["RECIPIENT_ID"]?>&mes=<?=$res["ID"]?>" target="_blank" onclick="newWind('/admin/service/write.php?id=<?=$res["RECIPIENT_ID"]?>&mes=<?=$res["ID"]?>'); return false;">Reply</a></strong>
            </td>
            <td><?=$res["SHOW_COMPANY"]?></td>
            <td><?=$res["SHOW_NAME"]?></td>
            <td><?=$res["POST_DATE"]?></td>
        </tr>
		<?
		$iCount++;
	endforeach;
?>
			</tbody>
<?
endif;
?>
			</table>
<?
if ($arResult["NAV_RESULT"]->NavPageCount > 0):
?>
		<p><?=$arResult["NAV_STRING"]?></p>
<?
endif;
?>
<script>
if (typeof oText != "object")
	var oText = {};
oText['no_data'] = '<?=CUtil::addslashes(GetMessage('JS_NO_MESSAGES'))?>';
oText['del_message'] = '<?=CUtil::addslashes(GetMessage("JS_DEL_MESSAGE"))?>';
</script>
