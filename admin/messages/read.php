<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
?>
<?$APPLICATION->IncludeComponent(
	"btm:forum.pm.read",
	"read",
	Array(
		"SET_TITLE" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "0",
		"SET_NAVIGATION" => "Y",
		"URL_TEMPLATES_PM_LIST" => "pm_list.php?FID=#FID#",
		"URL_TEMPLATES_PM_READ" => "read.php?mes=#MID#",
		"URL_TEMPLATES_PM_EDIT" => "pm_edit.php?MID=#MID#",
		"URL_TEMPLATES_PROFILE_VIEW" => "profile_view.php?UID=#UID#",
		"FID" => $_REQUEST["FID"],
		"MID" => $_REQUEST["mes"],
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/"
	),
false
);?>
