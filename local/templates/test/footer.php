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
				<?//Авторизация ?>
				<?$APPLICATION->IncludeComponent("rarus:auth.form", "", array(
                 	"REGISTER_URL" => "/registr/",
                   	"FORGOT_PASSWORD_URL" => "/remind/",
                   	"PROFILE_URL" => "/cabinet/",
                   	"SHOW_ERRORS" => "Y",
				    "PARTICPANT_GROUPS_ID" => array("10","9", "12", "11", "14","13", "16", "15", "18", "17", "21", "20", "43", "42", "46", "47", "50", "51", "55", "56", "61", "62"),
				    "GUESTS_GROUPS_ID" => array("22", "25", "23", "26", "24", "27", "19", "44", "48", "52", "57", "63"),
                   	),
                   	false
                );?>
                <? if(!(stripos($APPLICATION->GetCurDir(), "cabinet") !== false && $_SESSION["USER_TYPE"] == "PARTICIPANT")):?>
                <figure id = "banner">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_TEMPLATE_PATH."/include/banner_".LANGUAGE_ID.".php"), false);?>
				</figure>
				<?$APPLICATION->IncludeComponent(
	"rarus:members.last", 
	".default", 
	array(
		"USER_GROUP_ID" => array(
			0 => "14",
			1 => "16",
            2 => "18",
		),
		"FORM_FIELD_COMPANY_NAME_ID" => "17",
		"FORM_FIELD_LOGIN_ID" => "18",
		"ELEMENT_COUNT" => "10",
		"URL_TEMPLATE" => "/members/#ELEMENT_ID#/",
		"CACHE_TYPE" => "Y",
		"CACHE_TIME" => "36000",
		"CACHE_GROUPS" => "Y",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>
				<? endif;?>
				<figure id = "ltm">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_TEMPLATE_PATH."/include/ltm_".LANGUAGE_ID.".php"), false);?>
				</figure>
			</div>
		<? } ?>
	</div>
	<div class="footer_space"></div>
	<footer>
		<div class="layoutCenterWrapper">
			<div class="org">
				<img src="<?=SITE_TEMPLATE_PATH?>/images/polanskiy_x2.png" alt="Артем Поланский" width="62" height="65"><br>
				<img src="<?=SITE_TEMPLATE_PATH?>/images/icon-tmx2.png" alt="Travel Media" width="110" height="48">
			</div>
			<?$APPLICATION->IncludeComponent("bitrix:menu", "bottom", Array(
				"ROOT_MENU_TYPE" => "bottom_".LANGUAGE_ID,	// Тип меню для первого уровня
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
			<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
	"AREA_FILE_SHOW" => "file",
	"PATH" => SITE_TEMPLATE_PATH."/include/help_".LANGUAGE_ID.".php",
	"EDIT_TEMPLATE" => ""
	),
	false
);?>
			<? /*$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
	"AREA_FILE_SHOW" => "file",
	"PATH" => SITE_TEMPLATE_PATH."/include/adress_".LANGUAGE_ID.".php",
	"EDIT_TEMPLATE" => ""
	),
	false
); */?>
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
			
		</div>
	</footer>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_TEMPLATE_PATH."/include/scroller_".LANGUAGE_ID.".php"), false);?>
    
	<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter34646180 = new Ya.Metrika({
                    id:34646180,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/34646180" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</div>
</body>
</html>