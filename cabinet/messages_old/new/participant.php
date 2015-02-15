<?$APPLICATION->IncludeComponent(
	"btm:forum.pm.edit",
	"particip",
	Array(
		"MID" => $_REQUEST["MID"],
		"FID" => $_REQUEST["FID"],
		"UID" => $_REQUEST["id"],
		"mode" => $_REQUEST["mode"],
        "GROUP_WRITE" => 22,
		"URL_TEMPLATES_PM_LIST" => "pm_list.php?FID=#FID#",
		"URL_TEMPLATES_PM_READ" => "pm_read.php?MID=#MID#",
		"URL_TEMPLATES_PM_EDIT" => "pm_edit.php?MID=#MID#",
		"URL_TEMPLATES_PM_SEARCH" => "pm_search.php?MID=#MID#",
		"URL_TEMPLATES_PROFILE_VIEW" => "profile_view.php?UID=#UID#",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"SET_NAVIGATION" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "0",
		"SET_TITLE" => "Y"
	),
false
);?>
