<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Photos");
$title = "";
if(LANGUAGE_ID == "ru")
{
	$title = "ФОТО";
}
elseif(LANGUAGE_ID == "en")
{
	$title = "PHOTOS";
}
$APPLICATION->SetPageProperty("title", $title);
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");
?>
<? /*
<div class="album-preview">
  <div class="album-title">
  <a id="get-popup" href="javascript:void(0)" >LTM Moscow Autumn&rsquo;2013</a></div>

  <div class="album-preview-bl">
   		<a class="photo-gallery popup-link img-md" rel="group" href="img-sm.png" >
   		 			<img src="img-md.png"  />
   		</a>
   		<a class="photo-gallery popup-link img-sm" rel="group" href="img-sm.png" >
   		 			<img src="img-sm.png"  />
   		</a>
   		<a class="photo-gallery popup-link img-sm" rel="group" href="img-sm.png" >
   					<img src="img-sm.png"  />
   		</a>
   </div>

  <div class="album-preview-bl">
   		<a class="photo-gallery popup-link img-md" rel="group" href="img-sm.png" >
   					<img src="img-md.png"  />
   		</a>
   		<a class="photo-gallery popup-link img-sm" rel="group" href="img-sm.png" >
   		 			<img src="img-sm.png"  />
   		</a>
   		 <a class="photo-gallery popup-link img-sm" rel="group" href="img-sm.png" >
   		  			<img src="img-sm.png"  />
   		</a>
   </div>

 	<a class="photo-gallery popup-link img-lg" rel="group" href="img-sm.png" >
 	 		<img src="img-lg.png"  />
 	</a>
</div>

<div class="album-preview">
  <div class="album-title"><a id="get-popup" href="javascript:void(0)" >LTM Moscow Autumn’2013</a></div>

  <div class="album-preview-bl">
  <a class="photo-gallery popup-link img-md" rel="group" href="img-sm.png" >
  			<img src="img-md.png"  />
  </a>
  <a class="photo-gallery popup-link img-sm" rel="group" href="img-sm.png" >
  			<img src="img-sm.png"  />
  </a>
  <a class="photo-gallery popup-link img-sm" rel="group" href="img-sm.png" >
  			<img src="img-sm.png"  />
  </a>
</div>

  <div class="album-preview-bl">
  		<a class="photo-gallery popup-link img-md" rel="group" href="img-sm.png" >
  		 			<img src="img-md.png"  />
  		</a>
  		<a class="photo-gallery popup-link img-sm" rel="group" href="img-sm.png" >
  		 			<img src="img-sm.png"  />
  		</a>
  		<a class="photo-gallery popup-link img-sm" rel="group" href="img-sm.png" >
  		 			<img src="img-sm.png"  />
  		</a>
  </div>
 	<a class="photo-gallery popup-link img-lg" rel="group" href="img-sm.png" >
 	 		<img src="img-lg.png"  />
 	</a>
</div>

<div class="popup-gallery">
  <div class="photo-gallery-wrap">
   		<a class="photo-gallery" rel="group" href="img-sm.png" >
   			<img src="img-sm.png"  />
   		</a>
   		<a class="photo-gallery" rel="group" href="img-sm.png" >
   			<img src="img-sm.png"  />
   		</a>
   		<a class="photo-gallery" rel="group" href="img-sm.png" >
   		    <img src="img-sm.png"  />
   		</a>
   		<a class="photo-gallery" rel="group" href="img-sm.png" >
   		 			<img src="img-sm.png"  />
   		</a>
   		<a class="photo-gallery" rel="group" href="img-sm.png" >
   		    <img src="img-sm.png"  />
   		</a>
   		<a class="photo-gallery" rel="group" href="img-sm.png" >
   			<img src="img-sm.png"  />
   		</a>
   		<a class="photo-gallery" rel="group" href="img-sm.png" >
   		    <img src="img-sm.png"  />
   		</a>
   		<a class="photo-gallery" rel="group" href="img-sm.png" >
   		    <img src="img-sm.png"  />
   		</a>
  </div>

  <div class="gallery-nav"> 		<b>1</b> 		<a >2</a> 		<a >3</a> 		<a >4</a> 	</div>

  <div class="name-album">
    <div class="title">Luxury Travel Mart Moscow Autumn’2013</div>
   		The Ritz-Carlton Moscow 	</div>
 </div>

<script>

$(document).ready(function() {
	$(".fancybox-thumb").fancybox({
			helpers	: {
			    thumbs	: {
					width	: 50,
					height	: 50
				}
			}
	});
});

$(document).on("click", "a[id^=get-popup_]", function(){
	var id = $(this).attr("id");
	id = id.replace(/get-popup_/,"");
console.log(id);
	$("#popup-gallery_" + id).show();
	$( "body" ).append( "<div class='fancybox-overlay fancybox-overlay-fixed'></div>");
	$(".fancybox-overlay").show();
});
$(document).on("click", ".popup-gallery .photo-gallery", function(){
	$(".popup-gallery").hide();
	$(".fancybox-overlay").hide();
});

</script>

<div>
  <br />
</div>
*/?>

<div class="photo">
<?Cmodule::IncludeModule("iblock");

$rsSections = CIBlockSection::GetList(
    Array("SORT"=>"ASC"),
    array(
        "GLOBAL_ACTIVE" => "Y",
        "SECTION_ID" => false,
        "IBLOCK_ID" => 23,
        "IBLOCK_TYPE" => "photo",
    ),
    false,
    array("ID", "CODE", "NAME", "IBLOCK_ID")
);

while($arSection = $rsSections->GetNext(true, false))
{
    $APPLICATION->IncludeComponent("bitrix:news.list", "photo_ingroup", array(
	"IBLOCK_TYPE" => "photo",
	"IBLOCK_ID" => "23",
	"NEWS_COUNT" => "1000",
	"SORT_BY1" => "ACTIVE_FROM",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"FILTER_NAME" => "",
	"FIELD_CODE" => array(
		0 => "NAME",
		1 => "DETAIL_PICTURE",
		2 => "",
	),
	"PROPERTY_CODE" => array(
		0 => "",
		1 => "",
	),
	"CHECK_DATES" => "Y",
	"DETAIL_URL" => "",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "Y",
	"CACHE_TIME" => "36000000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"PREVIEW_TRUNCATE_LEN" => "",
	"ACTIVE_DATE_FORMAT" => "d.m.Y",
	"SET_TITLE" => "N",
	"SET_STATUS_404" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",
	"PARENT_SECTION" => $arSection["ID"],
	"PARENT_SECTION_CODE" => "",
	"INCLUDE_SUBSECTIONS" => "N",
	"PAGER_TEMPLATE" => ".default",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "N",
	"PAGER_TITLE" => "Новости",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_DESC_NUMBERING" => "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	"PAGER_SHOW_ALL" => "N",
	"DISPLAY_DATE" => "Y",
	"DISPLAY_NAME" => "Y",
	"DISPLAY_PICTURE" => "Y",
	"DISPLAY_PREVIEW_TEXT" => "Y",
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);
}
?>
</div>
<?/*
 }
 else
{
$APPLICATION->IncludeComponent("bitrix:news.list", "photo", array(
	"IBLOCK_TYPE" => "exhib",
	"IBLOCK_ID" => "15",
	"NEWS_COUNT" => "20",
	"SORT_BY1" => "ACTIVE_FROM",
	"SORT_ORDER1" => "DESC",
	"SORT_BY2" => "SORT",
	"SORT_ORDER2" => "ASC",
	"FILTER_NAME" => "",
	"FIELD_CODE" => array(
		0 => "",
		1 => "",
	),
	"PROPERTY_CODE" => array(
		0 => "",
		1 => "V_EN",
		2 => "V_RU",
		3 => "MORE_PHOTO",
		4 => "",
	),
	"CHECK_DATES" => "Y",
	"DETAIL_URL" => "",
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_FILTER" => "N",
	"CACHE_GROUPS" => "Y",
	"PREVIEW_TRUNCATE_LEN" => "",
	"ACTIVE_DATE_FORMAT" => "d.m.Y",
	"SET_TITLE" => "N",
	"SET_STATUS_404" => "N",
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	"ADD_SECTIONS_CHAIN" => "N",
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",
	"PARENT_SECTION" => "",
	"PARENT_SECTION_CODE" => "",
	"INCLUDE_SUBSECTIONS" => "N",
	"PAGER_TEMPLATE" => ".default",
	"DISPLAY_TOP_PAGER" => "N",
	"DISPLAY_BOTTOM_PAGER" => "N",
	"PAGER_TITLE" => "Новости",
	"PAGER_SHOW_ALWAYS" => "N",
	"PAGER_DESC_NUMBERING" => "N",
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	"PAGER_SHOW_ALL" => "N",
	"DISPLAY_DATE" => "Y",
	"DISPLAY_NAME" => "Y",
	"DISPLAY_PICTURE" => "Y",
	"DISPLAY_PREVIEW_TEXT" => "Y",
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);
}
*/?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>