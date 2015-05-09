<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div id="events">
	<ul>
		<?$i=0;foreach($arResult["EXHIB"] as $arExibit):
			$exhibName = $arExibit["PROPERTY_MENU_EN_VALUE"];
			if(LANGUAGE_ID != 'ru') {
				$exhibName = $arExibit["PROPERTY_MENU_RU_VALUE"];
			}
			if($i++>0 && ($pos = strpos($exhibName, ','))) {
				$exibitText = substr($exhibName, 0, $pos);
			}elseif($pos = strpos($exhibName, '.')) {
				$exibitText = substr($exhibName, 0, $pos);
			}else {
				$exibitText = $exhibName;
			}?>
			<li><a href="/members/<?=$arExibit["CODE"]?>/" class="js-filter-exibit<?if(isset($arExibit["SELECTED"])):?> chosen<?endif?>""><?=$exibitText?></a></li>
		<?endforeach?>
	</ul>
</div>
<div class="events-filter">
	<div class="filter-item">
		<a class="category-filter chosen js-open-tab js-show-category" data-tab-class="tab1" href="javascript:void(0)"><?=GetMessage("BY_CATEGORY")?></a>
		<a class="country-filter js-open-tab js-show-country" data-tab-class="tab2" href="javascript:void(0)"><?=GetMessage("BY_COUNTRY")?></a>
		<a class="all-filter js-open-tab js-show-all" data-tab-class="tab3" href="javascript:void(0)"><?=GetMessage("BY_ALL")?></a>
	</div>
	<div class="filter-item">
		<ul class="alphabetic-filter">
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="#">#</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="a">a</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="b">b</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="c">c</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="d">d</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="e">e</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="f">f</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="g">g</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="h">h</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="i">i</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="j">j</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="k">k</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="l">l</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="m">m</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="n">n</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="o">o</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="p">p</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="q">q</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="r">r</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="s">s</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="t">t</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="u">u</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="v">v</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="w">w</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="x">x</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="y">y</a></li>
			<li><a href="javascript:void(0)" class="js-filter-letter js-open-tab" data-tab-class="tab3" data-filter="z">z</a></li>
		</ul>
	</div>
</div>

<div class="filter-result">
	<div class="chosen">
		<ul class="js-tab tab1">
			<?foreach($arResult["AVAILABLE_CATEGORIES"] as $name):?>
				<li><a href="javascript:void(0)" class="js-filter-category" data-filter="<?=$name?>"><?=$name?></a></li>
			<?endforeach?>
		</ul>
		<ul class="js-tab tab2" style="display:none;">
			<?foreach($arResult["AVAILABLE_PRIORAREA"] as $name):?>
				<li><a href="javascript:void(0)" class="js-filter-priorarea" data-filter="<?=$name?>"><?=$name?></a></li>
			<?endforeach?>
		</ul>
		<div class="chosen letter js-very-big-letter js-tab tab3" style="display:none;">D</div>
	</div>
	<div class="chosen">
		<ul class="js-data">
			<?foreach($arResult["ITEMS_ID_BY_CATEGORY"] as $categoryName=>$arItemsId):?>
				<li class="title" data-category-name="<?=$categoryName?>"><?=$categoryName?></li>
				<?foreach($arItemsId as $arItemId):?>
					<li><a href="/members/<?=$arResult["ITEMS"][$arItemId]["ID"]?>/" title="<?=$arResult["ITEMS"][$arItemId]["NAME"]?>">
						<?=$arResult["ITEMS"][$arItemId]["NAME"]?>
					</a></li>
				<?endforeach;?>
			<?endforeach?>
		</ul>
	</div>
</div>

<script>
	membersData = <?=CUtil::PhpToJSObject($arResult["ITEMS"]);?>;
</script>