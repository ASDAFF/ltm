<?
/*
$APPLICATION->IncludeComponent(
	"btm:forum.pm.edit",
	"guest",
	Array(
		"MID" => $_REQUEST["MID"],
		"FID" => $_REQUEST["FID"],
		"UID" => $_REQUEST["id"],
		"mode" => $_REQUEST["mode"],
        "GROUP_WRITE" => 12,
		"URL_TEMPLATES_PM_LIST" => "pm_list.php?FID=#FID#",
		"URL_TEMPLATES_PM_READ" => "pm_read.php?MID=#MID#",
		"URL_TEMPLATES_PM_EDIT" => "pm_edit.php?MID=#MID#",
		"URL_TEMPLATES_PM_SEARCH" => "pm_search.php?MID=#MID#",
		"URL_TEMPLATES_PROFILE_VIEW" => "profile_view.php?UID=#UID#",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"SET_NAVIGATION" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "0",
		"SET_TITLE" => "N"
	),
false
);
*/
?>

<?
$APPLICATION->IncludeComponent(
	"rarus:messages.new",
	"",
	Array(
		"HLID" => "2",
		"EID" => $exhibID,
		"COPY_TO_OUTBOX" => "Y",
		"SEND_EMAIL" => "Y",
		"MID" => $_REQUEST["mes"],
	    "UID" => $_REQUEST["id"],
	    "SET_TITLE" => "N",
        "URL_TEMPLATES_HLM_LIST" => "/cabinet/".$exhibCode."/messages/#FCODE#/",
        "URL_TEMPLATES_HLM_READ" => "/cabinet/".$exhibCode."/messages/read/?MID=#MID#",
        "URL_TEMPLATES_HLM_NEW" => "/cabinet/".$exhibCode."/messages/new/?id=#UID#",
        "URL_TEMPLATES_HLM_COMPANY_VIEW" => "/members/#CID#/",
	    "GROUP_WRITE" => $exhibPGroup,
	    //"GROUP_TYPE" => "GUEST",

	),
false
);?>