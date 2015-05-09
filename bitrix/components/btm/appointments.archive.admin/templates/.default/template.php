<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
    <p>Список неподтвержденных встреч, отправленных участниками.</p>
    <table border="0" cellspacing="0" cellpadding="5" class="admin_info">
        <tr class="chet">
            <td width="210"><strong>Компания и Представитель</strong></td>
            <td width="210"><strong>Адресат</strong></td>
            <td width="100"><strong>Время</strong></td>
        </tr>
    <?
	for ($j = 0; $j < $arResult["MEETINGS"]["COUNT"]; $j++) {
		?>
        <tr <? if($j % 2){?>class="chet"<? }?>>
            <td>
				<?=$arResult["MEETINGS"]["LIST"][$j]["FROM_COMPANY"]?><br />
                <?=$arResult["MEETINGS"]["LIST"][$j]["FROM_REP"]?>
            </td>
            <td>
				<?=$arResult["MEETINGS"]["LIST"][$j]["TO_COMPANY"]?><br />
                <?=$arResult["MEETINGS"]["LIST"][$j]["TO_REP"]?>
            </td>
            <td>
				<?=$arResult["MEETINGS"]["LIST"][$j]["TIME"]?>
            </td>
        </tr>
		<?
	  }
	  ?>
    </table>
    <p>&nbsp;</p>
	<?
	//echo "<pre>"; print_r($arResult["MEETINGS"]["LIST"]); echo "</pre>";
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