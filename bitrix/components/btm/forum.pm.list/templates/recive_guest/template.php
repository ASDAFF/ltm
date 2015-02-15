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
    <table class="morning-time inbox-list">
        <tr>
            <th><?=GetMessage("PM_HEAD_SUBJ")?><?//=$arResult["SortingEx"]["POST_SUBJ"]?></th>
            <th><?=GetMessage("PM_HEAD_COMPANY")?><?//=$arResult["SortingEx"]["COMPANY"]?></th>
            <th><?
			if ($arResult["StatusUser"] == "RECIPIENT"):
				?><?=GetMessage("PM_HEAD_RECIPIENT")?><?
			elseif ($arResult["StatusUser"] == "SENDER"):
				?><?=GetMessage("PM_HEAD_SENDER")?><?
			else:
				?><?=GetMessage("PM_HEAD_AUTHOR")?><?
			endif;
			?><?//=$arResult["SortingEx"]["AUTHOR_NAME"]?></th>
            <th class='date'><?=GetMessage("PM_HEAD_DATE")?><?//=$arResult["SortingEx"]["POST_DATE"]?></th>
        </tr>
<?
if ($arResult["MESSAGE"] == "N" || empty($arResult["MESSAGE"])):
?>
 				<tr>
					<td colspan="4"><?=GetMessage("PM_EMPTY_FOLDER")?></td>
				</tr>
<?
else:
?>
			<tbody>
<?
	$iCount = 0;
	foreach ($arResult["MESSAGE"] as $res):
?>
        <tr <?=($res['IS_READ'] == 'N')?'class="new-message"':""?>>
        	<!--<td align="center"><? if($res["IS_READ"] == 'N'){?><img src="/bitrix/templates/personal/images/envelope.gif" /><? }else{?>&nbsp;<? }?></td>-->
            <td>
            <a href="/cabinet/service/read.php?mes=<?=$res["ID"]?>" target="_blank" onclick="newWind('/cabinet/service/read.php?mes=<?=$res["ID"]?>'); return false;">
            <?=$res["POST_SUBJ"]?>
            </a>            </td>
            <td class="company">
            <? if (!empty($res["SHOW_COMPANY_ID"])):?>
	            <a href='/members/<?=$res["SHOW_COMPANY_ID"]?>/'>
	            <?=$res["SHOW_COMPANY"]?>
	            </a>
            <?else:?>
              <?=$res["SHOW_COMPANY"]?>
            <? endif;?>
            </td>
            <td><?=$res["SHOW_NAME"]?></td>
            <td><?=date("Y, dM",strtotime($res["POST_DATE"]))?><br /><?=date("g:i A",strtotime($res["POST_DATE"]))?></td>
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

<? /*
            <table class="morning-time inbox-list">
                <tr>
                    <th>Тема</th>
                    <th>Компания</th>
                    <th>Отправитель</th>
                    <th>Дата</th>
                </tr>
                <tr>
                    <td>New proposals and terms of cooperation with our regular customers to our hotels</td>
                    <td class="company"><a href="" title="">Starwoods Hotels & Resorts</a></td>
                    <td>John Tudesk</td>
                    <td class="date">2014, 12 Feb. 9:20 PM</td>
                </tr>
                <tr>
                    <td class="unread">New proposals and terms of cooperation with our regular customers to our hotels</td>
                    <td class="company"><a href="" title="">Starwoods Hotels & Resorts</a></td>
                    <td>John Tudesk</td>
                    <td class="date">2014, 12 Feb. 9:20 PM</td>
                </tr>
                <tr>
                    <td>New proposals and terms of cooperation with our regular customers to our hotels</td>
                    <td class="company"><a href="" title="">Starwoods Hotels & Resorts</a></td>
                    <td>John Tudesk</td>
                    <td class="date">2014, 12 Feb. 9:20 PM</td>
                </tr>
            </table>

*/
?>