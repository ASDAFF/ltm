<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?=$arResult["FormErrors"]?><?=$arResult["FORM_NOTE"]?>
<?
if ($arResult["isAccessFormResultEdit"] == "Y" && strlen($arParams["EDIT_URL"]) > 0) 
{
	$href = $arParams["SEF_MODE"] == "Y" ? str_replace("#RESULT_ID#", $arParams["RESULT_ID"], $arParams["EDIT_URL"]) : $arParams["EDIT_URL"].(strpos($arParams["EDIT_URL"], "?") === false ? "?" : "&")."RESULT_ID=".$arParams["RESULT_ID"]."&WEB_FORM_ID=".$arParams["WEB_FORM_ID"];
?>
	<p class="reg_update"><a href="<?=$href?>">Do you want to update?</a></p>
<?
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
		<?
		$countField = 0;
		foreach ($arResult["RESULT"] as $FIELD_SID => $arQuestion)
		{
        if($arQuestion["CAPTION"] == 'Title College'){
			?>
                </table>
                <h2 class="reg_title">Colleague</h2>
                <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
			<?
        }
		?>
      <tr <?if($countField % 2 > 0){?>class="chet"<?}?>>
        <td width="250"><strong><?=$arQuestion["CAPTION"]?></strong></td>
        <td><?//=$arQuestion["ANSWER_HTML_CODE"]?>
			<?
			if (is_array($arQuestion['ANSWER_VALUE'])):
			foreach ($arQuestion['ANSWER_VALUE'] as $key => $arAnswer)
			{
			?>
			<?if (strlen($arAnswer["USER_TEXT"]) > 0):?>
				<?=$arAnswer["USER_TEXT"]?>
			<?else:?>
				<?if (strlen($arAnswer["ANSWER_TEXT"])>0):?>
				<?=$arAnswer["ANSWER_TEXT"]?>
					<?if (strlen($arAnswer["ANSWER_VALUE"])>0):?>&nbsp;(<?=$arAnswer["ANSWER_VALUE"]?>)<br /><?endif?>
				
				<?endif;?>
			<?endif;?>
			<?
			} //foreach ($arQuestions)
			endif;
			?>
			</td>
		</tr>
		<?
		$countField++;
		} // foreach ($arResult["RESULT"])
		?>
	</tbody>
</table>
<?
	//print_r($arResult["RESULT"]);
?>