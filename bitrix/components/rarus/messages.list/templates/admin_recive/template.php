<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
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
if ($arResult["NAV_PARAMS"]["PAGEN"] > 0):
?>
		<p><?=$arResult["NAV_STRING"]?></p>
<?
endif;
?>
    <table border="0" cellspacing="0" cellpadding="7" class="admin_info">
        <tr class="odd">
        	<td width="30">&nbsp;</td>
            <td width="210"><strong><?=GetMessage("HLM_HEAD_SUBJECT")?></strong><?=$arResult["SortingEx"]["SUBJECT"]?></td>
            <td width="210"><strong><?=GetMessage("HLM_HEAD_COMPANY")?></strong><?=$arResult["SortingEx"]["COMPANY"]?></td>
            <td width="140"><strong>
			<?if($arResult["StatusUser"] == "RECIPIENT"):?>
				<?=GetMessage("HLM_HEAD_SENDER")?>
			<?elseif ($arResult["StatusUser"] == "SENDER"):?>
				<?=GetMessage("HLM_HEAD_RECIPIENT")?>
			<?endif;?>
			</strong><?=$arResult["SortingEx"]["AUTHOR"]?></td>
            <td width="100"><strong><?=GetMessage("HLM_HEAD_DATE")?></strong><?=$arResult["SortingEx"]["POST_DATE"]?></td>
        </tr>
<?
if (empty($arResult["ITEMS"])):
?>
 				<tr>
					<td colspan="5"><?=GetMessage("HLM_EMPTY_FOLDER")?></td>
				</tr>
<?
else:
?>
			<tbody>
<?
	$iCount = 0;
	foreach ($arResult["ITEMS"] as $res):
	$class = ($iCount % 2)?"odd":"even";
	$class .= (!$res['IS_READ'])?" new-message":"";
?>
        <tr class="<?= $class?>">
        	<td align="center"><? if(!$res["IS_READ"]){?><img src="<?= SITE_TEMPLATE_PATH?>/images/envelope.gif" /><? }else{?>&nbsp;<? }?></td>
            <td>
            <?=$res["SUBJECT"]?><br />
            <strong><a href="<?=$res["URL_HLM_READ"]?>&EXHIBIT_CODE=<?= $_REQUEST["EXHIBIT_CODE"]?>" <?if($arParams["NEW_WINDOW"] == "Y"):?>target="_blank" onclick="newWind('<?=$res["URL_HLM_READ"]?>&EXHIBIT_CODE=<?= $_REQUEST["EXHIBIT_CODE"]?>',500,400); return false;"<? endif;?>><?=GetMessage("HLM_OPEN")?></a></strong>, <strong><a href="<?=$res["URL_HLM_NEW"]?>&mes=<?=$res["ID"]?>&EXHIBIT_CODE=<?= $_REQUEST["EXHIBIT_CODE"]?>" <?if($arParams["NEW_WINDOW"] == "Y"):?>target="_blank" onclick="newWind('<?=$res["URL_HLM_NEW"]?>&mes=<?=$res["ID"]?>&EXHIBIT_CODE=<?= $_REQUEST["EXHIBIT_CODE"]?>', 500, 400); return false;"<? endif;?>><?=GetMessage("HLM_REPLY")?></a></strong>
            </td>
            <? if ($arResult["StatusUser"] == "RECIPIENT"):?>
				<td><?=$res["AUTHOR"]["COMPANY_NAME"]?></td>
				<td><?=$res["AUTHOR"]["NAME"]?></td>
			<?elseif ($arResult["StatusUser"] == "SENDER"):?>
				<td><?=$res["RECIPIENT"]["COMPANY_NAME"]?></td>
				<td><?=$res["RECIPIENT"]["NAME"]?></td>
			<?endif;?>

            <td><?=date($arParams["DATE_FORMAT"],$res["POST_DATE"])?> <?=date($arParams["DATE_TIME_FORMAT"],$res["POST_DATE"])?></td>
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
if ($arResult["NAV_PARAMS"]["PAGEN"] > 0):
?>
		<p><?=$arResult["NAV_STRING"]?></p>
<?
endif;
?>