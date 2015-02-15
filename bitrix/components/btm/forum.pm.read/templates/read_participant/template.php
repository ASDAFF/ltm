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
	<div class="reed-letter pull-overflow">
		<form action="">
			<div class="head-letter pull-overflow">
				<div class="pull-left contact-info">
					<div><strong><?=GetMessage("PM_FROM")?>:</strong> <?=$arResult["MESSAGE"]["AUTHOR_NAME"]?></div>
					<div><strong><?=GetMessage("PM_TO")?>:</strong> <?=$arResult["MESSAGE"]["RECIPIENT_NAME"]?></div>
				</div>
				<div class="pull-right">
					<div class="date"><?=date("Y, dM g:i A",strtotime($arResult["MESSAGE"]["POST_DATE"]))?></div>
				</div>
				<div class="pull-overflow theme"><strong><?=GetMessage("PM_SUBJ")?>:</strong> <b><?=$arResult["MESSAGE"]["POST_SUBJ"];?></b></div>
			</div>
			<div class="message-text">
				<?=$arResult["MESSAGE"]["POST_MESSAGE"]?>
			</div>
			<div class="send">
				<a title="<?=GetMessage('PM_ACT_REPLY').' '.$arResult["MESSAGE"]["RECIPIENT_NAME"]?>" href="/cabinet/service/write.php?id=<?=$arResult["MESSAGE"]["AUTHOR_ID"]?>&mes=<?=$arResult["MESSAGE"]["ID"]?>"><?=GetMessage('PM_ACT_REPLY')?></a>
			</div>
		</form>
		</div>