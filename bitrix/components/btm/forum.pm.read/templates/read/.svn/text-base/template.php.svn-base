<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
if (!$this->__component->__parent || empty($this->__component->__parent->__name)):
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/themes/blue/style.css');
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/components/bitrix/forum/templates/.default/styles/additional.css');
endif;
/********************************************************************
				Input params
********************************************************************/
/***************** BASE ********************************************/
$iIndex = rand();
$arResult["FOLDERS"] = array();
for ($ii = 1; $ii <= $arResult["SystemFolder"]; $ii++)
{
	if (($arResult["version"] == 2 && $ii == 2) || $ii == $arParams["FID"])
		continue;
	$arResult["FOLDERS"][] = array("ID" => $ii, "TITLE" => GetMessage("PM_FOLDER_ID_".$ii));
}
if (is_array($arResult["UserFolder"]) && !empty($arResult["UserFolder"]))
{
	foreach ($arResult["UserFolder"] as $res)
	{
		if ($res["ID"] = $arParams["FID"])
			continue;
		$arResult["FOLDERS"][] = array("ID" => $res["ID"], "TITLE" => $res["TITLE"]);
	}
}
/********************************************************************
				/Input params
********************************************************************/
if (!empty($arResult["ERROR_MESSAGE"])): 
?>
<p class="error"><?=ShowError($arResult["ERROR_MESSAGE"], "forum-note-error");?></p>
<?
endif;
if (!empty($arResult["OK_MESSAGE"])): 
?>
<p class="error"><?=ShowNote($arResult["OK_MESSAGE"], "forum-note-success")?></p>
<?
endif;
?>
<a name="postform"></a>
    <h2 class="reg_title"><?=GetMessage("PM_HEAD")?></h2>
    <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
      <tr class="chet">
        <td width="120"><strong><?=GetMessage("PM_FROM")?></strong></td>
        <td><?=$arResult["MESSAGE"]["AUTHOR_NAME"]?></td>
      </tr>
      <tr>
        <td><strong><?=GetMessage("PM_TO")?></strong></td>
        <td><?=$arResult["MESSAGE"]["RECIPIENT_NAME"]?></td>
      </tr>
      <tr class="chet">
        <td width="120"><strong><?=GetMessage("PM_DATA")?></strong></td>
        <td><?=$arResult["MESSAGE"]["POST_DATE"]?></td>
      </tr>
      <tr>
        <td width="120"><strong><?=GetMessage("PM_SUBJ")?></strong></td>
        <td><?=$arResult["MESSAGE"]["POST_SUBJ"];?></td>
      </tr>
      <tr class="chet">
        <td width="120"><strong><?=GetMessage("PM_MESS")?></strong></td>
        <td><?=$arResult["MESSAGE"]["POST_MESSAGE"]?></td>
      </tr>
	</table>
