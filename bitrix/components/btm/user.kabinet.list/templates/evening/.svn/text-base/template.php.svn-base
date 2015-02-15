<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
    <table width="100%" border="0" cellspacing="5" cellpadding="0">
      <tr>
        <td><p class="reg_update" style="text-align:left;"><? if($arResult["SORT"] == 'ABC'){?><strong style="color:#66CCFF;">In alphabetical order</strong><? } else{?><a href="<?=$arResult["LINK"]?>?ussort=abc" style=" color:#FFF;">In alphabetical order</a><? }?></p></td>
        <td><p class="reg_update"><? if($arResult["SORT"] == "CITY"){?><strong style="color:#66CCFF;">By city of origin</strong><? } else{?><a href="<?=$arResult["LINK"]?>?ussort=city" style=" color:#FFF;">By city of origin</a><? }?></p></td>
        <td width="350"><p class="reg_update"><? if($arResult["SORT"] == "ALL"){?><strong style="color:#66CCFF;">All</strong><? } else{?><a href="<?=$arResult["LINK"]?>?ussort=all" style=" color:#FFF;">All</a><? }?></p></td>
      </tr>
    </table>
    <p class="filter_block"><?=$arResult["FILTER"]["SUB"]?></p>
    <div class="filter_block"><?=$arResult["NAVIGATE"]?></div>
    <table width="100%" border="0" cellspacing="0" cellpadding="7" class="regist_info">
        <tr class="chet">
            <td width="280"><strong>Company</strong></td>
            <td width="160"><strong>Representative</strong></td>
            <td style="text-align:center;" colspan="2"><strong>Collegues</strong></td>
        </tr>
	<?
    $countUsers = 0;
    for($j=0; $j<$arResult["COUNT"]; $j++){
        ?>
        <tr <? if($countUsers % 2){?>class="chet"<? }?>>
            <td>
            <strong><?=$arResult["USERS"][$j]["FIELDS"]["COMPANY"]?></strong><br />
            <strong>Site</strong>: <a href="http://<?=$arResult["USERS"][$j]["FIELDS"]["SITE"]?>" target="_blank"><?=$arResult["USERS"][$j]["FIELDS"]["SITE"]?></a>
            </td>
            <td><?=$arResult["USERS"][$j]["FIELDS"]["NAME"]?></td>
            <td>
				<?=$arResult["USERS"][$j]["FIELDS"]["COLLEGE"][0]?><br />
                <?=$arResult["USERS"][$j]["FIELDS"]["COLLEGE"][2]?>
            </td>
            <td>
				<?=$arResult["USERS"][$j]["FIELDS"]["COLLEGE"][1]?><br />
                <?=$arResult["USERS"][$j]["FIELDS"]["COLLEGE"][3]?>
            </td>
        </tr>
        <?
		$countUsers++;
    }
    ?>
    </table>
    <div class="filter_block"><?=$arResult["NAVIGATE"]?></div>
  <?
}
//echo "<pre>"; print_r($arResult["USERS"]); echo "</pre>";
?>