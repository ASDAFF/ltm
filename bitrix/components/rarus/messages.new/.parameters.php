<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arComponentParameters = Array(
    "GROUPS" => array(
        "URL_TEMPLATES" => array(
            "NAME" => GetMessage("HLM_URL_TEMPLATES"),
        ),
    ),
	"PARAMETERS" => Array(
		"MID" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("HLM_DEFAULT_MID"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["MID"]}'),
		"FID" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("HLM_DEFAULT_FID"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["FID"]}'),
		"UID" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("HLM_DEFAULT_UID"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["UID"]}'),
		"mode" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("HLM_DEFAULT_MODE"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["mode"]}'),
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
	    "GROUP_WRITE" => Array(
	        "PARENT" => "BASE",
	        "NAME" => GetMessage("HLM_DEFAULT_GROUP_WRITE"),
	        "TYPE" => "STRING",
	        "DEFAULT" => ''),
        "GROUP_TYPE" => Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("HLM_DEFAULT_GROUP_TYPE"),
            "TYPE" => "STRING",
            "DEFAULT" => ''),
		"SET_NAVIGATION" => Array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("HLM_SET_NAVIGATION"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y"),
		"COPY_TO_OUTBOX" => Array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("HLM_COPY_TO_OUTBOX"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y"),
		"SEND_EMAIL" => Array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("HLM_SEND_EMAIL"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N"),
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
