<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?IncludeTemplateLangFile(__FILE__);?>
<? 
CModule::IncludeModule('iblock');
global $USER;

if (!$USER->isAdmin() AND substr_count($_SERVER["REQUEST_URI"],"/")>2) LocalRedirect("/administrator/");
define('EXHIBITION_ID',1);
?>
<!DOCTYPE html>
<html class="<?= LANGUAGE_ID?>">
<head>
	<title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery-1.10.2.min.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery-ui-1.10.4.custom.min.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/fancybox/jquery.fancybox.js')?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/js/fancybox/jquery.fancybox.css',true)?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/js/fancybox/jquery.fancybox-thumbs.css')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/fancybox/jquery.fancybox-thumbs.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/ajaxupload.3.5.js')?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/js/validity/jquery.validity.css')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/validity/jquery.validity.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/script.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/normalize.css')?>
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<?$APPLICATION->ShowHead()?>
</head>
<body>
	<? //if ($USER->isAdmin())$APPLICATION->ShowPanel();?>
	<header>
		<div class="layoutCenterWrapper">
			<a href = "/administrator/" id = "logo" alt = "Luxury Travel Mart" /></a>
			<span id = "title">APP.</span>
			<div id = "lang"><a class = "en" href="/?logout=yes">Exit</a></div>
		</div>
	</header>
	<div class="exhibitions_wrap">
	<div class="exhibitions">
<?
$arSelect = Array("ID", "NAME", "PROPERTY_TAB_TITLE");
$arFilter = Array("IBLOCK_ID"=>15, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>50), $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    if (!$_REQUEST["exhb"]) LocalRedirect("?exhb=".$arFields["ID"]);
?>
    <span <? if ($_REQUEST["exhb"]==$arFields["ID"]) {?>class="selected"<?}?>><a href="?exhb=<?=$arFields["ID"]?>"><?=$arFields["PROPERTY_TAB_TITLE_VALUE"]?></a><i></i></span>
<?}?>
	
	</div>
	</div>
	
	<?$APPLICATION->IncludeComponent("bitrix:menu", "top", array(
       	"ROOT_MENU_TYPE" => "top",
       	"MENU_CACHE_TYPE" => "A",
       	"MENU_CACHE_TIME" => "3600",
       	"MENU_CACHE_USE_GROUPS" => "Y",
       	"MENU_CACHE_GET_VARS" => array("UID","EXHIBIT_CODE"
       	),
       	"MAX_LEVEL" => "1",
       	"CHILD_MENU_TYPE" => "top_".LANGUAGE_ID,
       	"USE_EXT" => "Y",
       	"DELAY" => "N",
       	"ALLOW_MULTI_SELECT" => "N"
       	),
      	false
    );?>
	<?
	$arSelect = Array("ID", "NAME", "PROPERTY_APP_OFF");
	$arFilter = Array("IBLOCK_ID"=>24, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>13909);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
	while($ob = $res->GetNextElement()) {
		$arFields = $ob->GetFields();
		$APP_OFF = $arFields["PROPERTY_APP_OFF_VALUE"];
	}
	?>

    <div class="switch-off"><a href="/administrator/?exhb=<?=$_REQUEST["exhb"]?>&off=<? if ($APP_OFF) echo "N"; else echo "Y";?>">APPLICATIONS <? if (!$APP_OFF OR $APP_OFF==0) {?><strong>ON</strong><?}else{echo "ON";}?>/<? if ($APP_OFF) {?><strong>OFF</strong><?}else{echo "OFF";}?></a></div>
	<div id = "content" class="layoutCenterWrapper clearfix">
		<div id = "center">