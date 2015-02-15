<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	?>
    <script type="text/javascript" src="/bitrix/components/btm/user.list/templates/.default/script.js"></script>
    <p><?=$arResult["FILTER"]["ALP"]?></p>
    <p class="index">
    <?
	for($j=0; $j<$arResult["COUNT"]; $j++){
			?>
			<a href="#cat<?=$j?>"><?=$arResult["CATEGORIES"][$j]["TITLE"]?></a><br />
            <?
	}	
	?>
    </p>
	<?
	for($j=0; $j<$arResult["COUNT"]; $j++){
		if($arResult["CATEGORIES"][$j]["COUNT"] > 0){
			?>
			<p class="area_title"><a name="cat<?=$j?>"></a><?=$arResult["CATEGORIES"][$j]["TITLE"]?></p>
			<?
			for($i=0; $i<$arResult["CATEGORIES"][$j]["COUNT"]; $i++){
				?>
				<p class="comp_title"><a href="http://<?=$arResult["CATEGORIES"][$j]["COMPANYS"]["SITE"][$i]?>" target="_blank"><?=$arResult["CATEGORIES"][$j]["COMPANYS"]["COMPANY"][$i]?></a> <span><a href="#" onclick="TopMenuOver('cat<?=$j?>_comp<?=$i?>'); return false;" id="cat<?=$j?>_comp<?=$i?>par">info+</a></span></p>
				<p class="descr" id="cat<?=$j?>_comp<?=$i?>"><?= nl2br($arResult["CATEGORIES"][$j]["COMPANYS"]["DESC"][$i]);?></p>
				<?
			}
		}
		else{
			?>
			<p class="area_title"><a name="cat<?=$j?>"></a><?=$arResult["CATEGORIES"][$j]["TITLE"]?></p>
            <p>There are no companies in this category yet.</p>
			<?
		}
	}
}
?>