<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
?>


		</div>
	</div>
	<footer>
		<div class="layoutCenterWrapper" style="border-top: 1px solid #c7c7c7;">
			<div class="org"><img src="<?=SITE_TEMPLATE_PATH?>/images/polanskiy_x2.png" alt="Артем Поланский" width="62" height="65"><img src="<?=SITE_TEMPLATE_PATH?>/images/icon-tmx2.png" alt="Travel Media" width="110" height="48"></div>
			
			
			
			<div id="adress">
			<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", Array(
				"ROOT_MENU_TYPE" => "bottom",	// Тип меню для первого уровня
				"MENU_CACHE_TYPE" => "A",	// Тип кеширования
				"MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
				"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
				"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
				"MAX_LEVEL" => "1",	// Уровень вложенности меню
				"CHILD_MENU_TYPE" => "bottom_".LANGUAGE_ID,	// Тип меню для остальных уровней
				"USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
				"DELAY" => "N",	// Откладывать выполнение шаблона меню
				"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
				),
				false
			);?>
			</div>
			<div style="clear:both"></div>
			<br><br>
			<span id="design">Design and developed by <a target="_blank" href="http://startbrand.ru">STARTBRAND&reg;</a></span>
			
		</div>
	</footer>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_TEMPLATE_PATH."/include/scroller_".LANGUAGE_ID.".php"), false);?>
</body>
</html>