<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
?>
<? if(stristr($APPLICATION->GetCurPage(), "/cabinet/")):?>
<? $exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
if($exhibCode)
{?>
    </div> <!-- <div  class="exhibition-session"> -->
<? }?>

</div> <!-- <div class="exhibition-block"> -->
<? endif;?>

			</div>
			<? if(!LuxorConfig::isMeetPage()){ ?>
			<div id = "right">
				    <?//����������� ?>
					<?$APPLICATION->IncludeComponent("rarus:auth.form", "", array(
                    	"REGISTER_URL" => "/registr/",
                    	"FORGOT_PASSWORD_URL" => "/remind/",
                    	"PROFILE_URL" => "/cabinet/",
                    	"SHOW_ERRORS" => "Y",
					    "PARTICPANT_GROUPS_ID" => array("10","9", "12", "11", "14","13", "16", "15", "18", "17", "21", "20"),
					    "GUESTS_GROUPS_ID" => array("22", "25", "23", "26", "24", "27", "19"),
                    	),
                    	false
                    );?>
					<?//$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_TEMPLATE_PATH."/include/auth.php"), false);?>
				<figure id = "banner">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_TEMPLATE_PATH."/include/banner_".LANGUAGE_ID.".php"), false);?>
				</figure>
				<?$APPLICATION->IncludeComponent("rarus:members.last", ".default", array(
					"USER_GROUP_ID" => array(
						0 => "18",
						1 => "12",
						2 => "16",
						3 => "10",
						4 => "14",
					),
					"FORM_FIELD_COMPANY_NAME_ID" => "17",
					"FORM_FIELD_LOGIN_ID" => "18",
					"ELEMENT_COUNT" => "10",
					"URL_TEMPLATE" => "/members/#ELEMENT_ID#/",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"CACHE_GROUPS" => "Y"
					),
					false
				);?>
				<figure id = "ltm">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_TEMPLATE_PATH."/include/ltm_".LANGUAGE_ID.".php"), false);?>
				</figure>
			</div>
			<? } ?>
			<div class = "clear"></div>
		</div>
	</div>
	<footer>
		<div id = "info">
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_TEMPLATE_PATH."/include/footer.php"), false);?>
			<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", Array(
				"ROOT_MENU_TYPE" => "bottom_".LANGUAGE_ID,	// ��� ���� ��� ������� ������
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
			<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
	"AREA_FILE_SHOW" => "file",
	"PATH" => SITE_TEMPLATE_PATH."/include/copyright_".LANGUAGE_ID.".php",
	"EDIT_TEMPLATE" => ""
	),
	false
);?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
	"AREA_FILE_SHOW" => "file",
	"PATH" => SITE_TEMPLATE_PATH."/include/design_".LANGUAGE_ID.".php",
	"EDIT_TEMPLATE" => ""
	),
	false
);?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
	"AREA_FILE_SHOW" => "file",
	"PATH" => SITE_TEMPLATE_PATH."/include/develop_".LANGUAGE_ID.".php",
	"EDIT_TEMPLATE" => ""
	),
	false
);?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
	"AREA_FILE_SHOW" => "file",
	"PATH" => SITE_TEMPLATE_PATH."/include/help_".LANGUAGE_ID.".php",
	"EDIT_TEMPLATE" => ""
	),
	false
);?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
	"AREA_FILE_SHOW" => "file",
	"PATH" => SITE_TEMPLATE_PATH."/include/adress_".LANGUAGE_ID.".php",
	"EDIT_TEMPLATE" => ""
	),
	false
);?>
		</div>
	</footer>
</body>
</html>