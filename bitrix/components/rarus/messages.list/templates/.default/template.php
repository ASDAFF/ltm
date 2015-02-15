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
    <table class="morning-time inbox-list">
        <tr>
            <th><?=GetMessage("HLM_HEAD_SUBJECT")?></th>
            <th><?=GetMessage("HLM_HEAD_COMPANY")?></th>
            <th>
			<?if($arResult["StatusUser"] == "RECIPIENT"):?>
				<?=GetMessage("HLM_HEAD_SENDER")?>
			<?elseif ($arResult["StatusUser"] == "SENDER"):?>
				<?=GetMessage("HLM_HEAD_RECIPIENT")?>
			<?endif;?>
			</th>
            <th class='date'><?=GetMessage("HLM_HEAD_DATE")?></th>
        </tr>
<?
if (empty($arResult["ITEMS"])):
?>
 				<tr>
					<td colspan="4"><?=GetMessage("HLM_EMPTY_FOLDER")?></td>
				</tr>
<?
else:
?>
			<tbody>
<?
	$iCount = 0;
	foreach ($arResult["ITEMS"] as $res):
?>
        <tr <?=($res['IS_READ'] == 0)?'class="new-message"':""?>>
        	<!--<td align="center"><? if($res["IS_READ"] == 'N'){?><img src="/bitrix/templates/personal/images/envelope.gif" /><? }else{?>&nbsp;<? }?></td>-->
            <td>
	            <a href="<?=$res["URL_HLM_READ"]?>" <?if($arParams["NEW_WINDOW"] == "Y"):?>target="_blank" onclick="newWind('<?=$res["URL_HLM_READ"]?>', 500, 400); return false;"<? endif;?>>
	            <? $subject = trim($res["SUBJECT"]);?>
	            <?=(strlen($subject) > 0)?$subject:GetMessage("HLM_NO_SUBJECT")?>
	            </a>
	        </td>
            <? if ($arResult["StatusUser"] == "RECIPIENT"):?>
				<td class="company">
				<? if($res["AUTHOR"]["COMPANY_ID"]):?>
    				<a href='<?=$res["URL_HLM_COMPANY_VIEW"]?>'>
    				    <?=$res["AUTHOR"]["COMPANY_NAME"]?>
    	            </a>
    	       <? else:?>
    	           <?=$res["AUTHOR"]["COMPANY_NAME"]?>
    	       <? endif;?>
				</td>
				<td><?=$res["AUTHOR"]["NAME"]?></td>
			<?elseif ($arResult["StatusUser"] == "SENDER"):?>
				<td class="company"><?=$res["RECIPIENT"]["COMPANY_NAME"]?></td>
				<td><?=$res["RECIPIENT"]["NAME"]?></td>
			<?endif;?>
            <td><?=date($arParams["DATE_FORMAT"],$res["POST_DATE"])?><br /><?=date($arParams["DATE_TIME_FORMAT"],$res["POST_DATE"])?></td>
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