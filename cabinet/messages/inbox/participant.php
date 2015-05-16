<?
/*
$APPLICATION->IncludeComponent(
	"btm:forum.pm.list",
	"recive_particip",
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
		"SET_TITLE" => "N"
	)
);

*/?>

<?
$APPLICATION->IncludeComponent(
	"rarus:messages.list",
	"",
	Array(
		"HLID" => "2",
		"EID" => $exhibID,
		"FID" => "3",
	    "SET_TITLE" => "N",
		"PM_PER_PAGE" => "20",
		"DATE_FORMAT" => "d.m.Y",
		"DATE_TIME_FORMAT" => "H:i:s",
        "URL_TEMPLATES_HLM_LIST" => "/cabinet/".$exhibCode."/messages/#FCODE#/",
        "URL_TEMPLATES_HLM_READ" => "/cabinet/".$exhibCode."/messages/read/?MID=#MID#",
        "URL_TEMPLATES_HLM_NEW" => "/cabinet/".$exhibCode."/messages/new/?id=#UID#",
        "URL_TEMPLATES_HLM_COMPANY_VIEW" => "/members/#CID#/",

	),
false
);?>