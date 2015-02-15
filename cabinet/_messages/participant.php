<?$APPLICATION->IncludeComponent(
	"btm:forum.pm.list",
	"recive",
	Array(
		"FID" => "0",
		"URL_TEMPLATES_PM_LIST" => "pm_list.php?FID=#FID#",
		"URL_TEMPLATES_PM_READ" => "pm_read.php?MID=#MID#",
		"URL_TEMPLATES_PM_EDIT" => "pm_edit.php?MID=#MID#&mode=#mode#",
		"URL_TEMPLATES_PM_FOLDER" => "pm_folder.php",
		"URL_TEMPLATES_PROFILE_VIEW" => "profile_view.php?UID=#UID#",
		"PAGE_NAVIGATION_TEMPLATE" => "",
		"PM_PER_PAGE" => "20",
		"DATE_FORMAT" => "d.m.Y",
		"DATE_TIME_FORMAT" => "d.m.Y H:i:s",
		"SET_NAVIGATION" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "0",
		"CACHE_NOTES" => "",
		"SET_TITLE" => "Y"
	)
);?>
