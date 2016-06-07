<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? IncludeTemplateLangFile(__FILE__);?>
<!DOCTYPE html>
<html class="<?= LANGUAGE_ID?>">
<head>

	<title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/js/bootstrap/bootstrap.css')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery-1.10.2.min.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/bootstrap/bootstrap.min.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/fancybox/jquery.fancybox.js')?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/js/fancybox/jquery.fancybox.css',true)?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/script.js')?>
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<?$APPLICATION->ShowHead()?>
</head>
<body>
	<div class="clearfix">
		<?/*<div id="panel" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?$APPLICATION->ShowPanel();?></div>*/ ?>
	</div>
	<? if(!$USER->IsAdmin()){LocalRedirect("/");}?>
	<?//Открыли контейнер для кастомной админки ?>
	<div class="admin-container clearfix">

		<?//Открыли общий контейнер ?>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<? if(strpos($APPLICATION->GetCurDir(), "service")  === false):?>
			<?$APPLICATION->IncludeComponent("bitrix:menu", "admin", Array(
				"ROOT_MENU_TYPE" => "admin.top",	// Тип меню для первого уровня
				"MAX_LEVEL" => "2",	// Уровень вложенности меню
				"CHILD_MENU_TYPE" => "",	// Тип меню для остальных уровней
				"USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
				"DELAY" => "N",	// Откладывать выполнение шаблона меню
				"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
				"MENU_CACHE_TYPE" => "N",	// Тип кеширования
				"MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
				"MENU_CACHE_USE_GROUPS" => "N",	// Учитывать права доступа
				"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
				),
				false
			);?>
		<? endif;?>
		<?//Октрыли обищий контейнер?>
		<div class="row content">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<script>

				$(document).ready(function(){

    				$(".action img").click(function(e) {
    					var $popup = $(this).next();
    					var id = $(this).parent().attr("id");

        				if ($popup.css('display') != 'block') {
        					$popup.show();


    				        var firstClick = true;
    				        $(document).bind('click.myEvent_'+ id, function(e) {
    				           if (!firstClick && $(e.target).closest('ul.ul-popup').length == 0) {
    				            	$popup.hide();
    				                $(document).unbind('click.myEvent_' + id);
    				            }
    				            firstClick = false;
    				        });

        				    }
        				else
        				{
        					$popup.hide();
			                $(document).unbind('click.myEvent_' + id);
            			}
        				    e.preventDefault();
        				});
				});
			</script>