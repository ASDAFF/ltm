<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
?>


		</div>
	</div>
	<footer>
		<div class="layoutCenterWrapper" style="border-top: 1px solid #c7c7c7;">
			<div class="org"><img src="<?=SITE_TEMPLATE_PATH?>/images/polanskiy_x2.png" alt="����� ���������" width="62" height="65"><img src="<?=SITE_TEMPLATE_PATH?>/images/icon-tmx2.png" alt="Travel Media" width="110" height="48"></div>
			
			
			
			<div id="adress">
			<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", Array(
				"ROOT_MENU_TYPE" => "bottom",	// ��� ���� ��� ������� ������
				"MENU_CACHE_TYPE" => "A",	// ��� �����������
				"MENU_CACHE_TIME" => "3600",	// ����� ����������� (���.)
				"MENU_CACHE_USE_GROUPS" => "Y",	// ��������� ����� �������
				"MENU_CACHE_GET_VARS" => "",	// �������� ���������� �������
				"MAX_LEVEL" => "1",	// ������� ����������� ����
				"CHILD_MENU_TYPE" => "bottom_".LANGUAGE_ID,	// ��� ���� ��� ��������� �������
				"USE_EXT" => "Y",	// ���������� ����� � ������� ���� .���_����.menu_ext.php
				"DELAY" => "N",	// ����������� ���������� ������� ����
				"ALLOW_MULTI_SELECT" => "N",	// ��������� ��������� �������� ������� ������������
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