<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arComponentParameters = Array(
    "GROUPS" => array(
        "URL_TEMPLATES" => array(
            "NAME" => GetMessage("HLM_URL_TEMPLATES"),
        ),
    ),
	"PARAMETERS" => Array(
		"FID" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("HLM_DEFAULT_FID"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["FID"]}'),
		"HLID" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("HLM_DEFAULT_HLID"),
			"TYPE" => "STRING",
			"DEFAULT" => ''),
		"EID" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("HLM_DEFAULT_EID"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["EID"]}'),
	    "PAGE_NAVIGATION_TEMPLATE" => Array(
	        "PARENT" => "ADDITIONAL_SETTINGS",
	        "NAME" => GetMessage("HLM_PAGE_NAVIGATION_TEMPLATE"),
	        "TYPE" => "STRING",
	        "DEFAULT" => ""),
        "PM_PER_PAGE" => Array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("PM_PER_PAGE"),
            "TYPE" => "STRING",
            "DEFAULT" => 20),
        "DATE_FORMAT" => CComponentUtil::GetDateFormatField(GetMessage("HLM_DATE_FORMAT"), "ADDITIONAL_SETTINGS"),
        "DATE_TIME_FORMAT" => CComponentUtil::GetDateTimeFormatField(GetMessage("HLM_DATE_TIME_FORMAT"), "ADDITIONAL_SETTINGS"),
		"SET_NAVIGATION" => Array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("HLM_SET_NAVIGATION"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y"),
	    "URL_TEMPLATES_HLM_LIST" => Array(
	        "PARENT" => "URL_TEMPLATES",
	        "NAME" => GetMessage("HLM_LIST_TEMPLATE"),
	        "TYPE" => "STRING",
	        "MULTIPLE" => "N",
	        "DEFAULT" => "/cabinet/messages/#FCODE#/",
	        "COLS" => 25
	    ),

	    "URL_TEMPLATES_HLM_READ" => Array(
	        "PARENT" => "URL_TEMPLATES",
	        "NAME" => GetMessage("HLM_READ_TEMPLATE"),
	        "TYPE" => "STRING",
	        "MULTIPLE" => "N",
	        "DEFAULT" => "/cabinet/services/read.php?MID=#MID#",
	        "COLS" => 25
	    ),

	    "URL_TEMPLATES_HLM_NEW" => Array(
	        "PARENT" => "URL_TEMPLATES",
	        "NAME" => GetMessage("HLM_NEW_TEMPLATE"),
	        "TYPE" => "STRING",
	        "MULTIPLE" => "N",
	        "DEFAULT" => "/cabinet/services/write.php?id=#UID#",
	        "COLS" => 25
	    ),

	    "URL_TEMPLATES_HLM_COMPANY_VIEW" => Array(
	        "PARENT" => "URL_TEMPLATES",
	        "NAME" => GetMessage("HLM_COMPANY_VIEW_TEMPLATE"),
	        "TYPE" => "STRING",
	        "MULTIPLE" => "N",
	        "DEFAULT" => "/members/#CID#/",
	        "COLS" => 25
	    ),
	    "NEW_WINDOW" => Array(
	        "PARENT" => "URL_TEMPLATES",
	        "NAME" => GetMessage("HLM_NEW_WINDOW"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N"
	    ),

		"CACHE_TIME" => Array(),
		"SET_TITLE" => Array(),
	)
);
?>
