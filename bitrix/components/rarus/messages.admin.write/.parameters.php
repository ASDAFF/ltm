<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = Array(
	"GROUPS" => array(
		"MAIN_SETTINGS" => array(
			"NAME" => GetMessage("ADMIN_MAIN_MAIN_SETTINGS"),
		),
	    "URL_TEMPLATES" => array(
	        "NAME" => GetMessage("HLM_URL_TEMPLATES"),
	    ),
	),
	"PARAMETERS" => Array(
	    "HLID" => Array(
	        "PARENT" => "BASE",
	        "NAME" => GetMessage("HLM_DEFAULT_HLID"),
	        "TYPE" => "STRING",
	        "DEFAULT" => ''),

		"PATH_TO_KAB" => Array(
			"NAME" => GetMessage("ADMIN_MAIN_PATH_TO_KAB"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"AUTH_PAGE" => Array(
			"NAME" => GetMessage("ADMIN_MAIN_AUTH_PAGE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GROUP_ID" => Array(
			"NAME" => GetMessage("ADMIN_MAIN_GROUP_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GUEST" => Array(
			"NAME" => GetMessage("ADMIN_MAIN_GUEST"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GUEST_HB" => Array(
			"NAME" => GetMessage("ADMIN_MAIN_GUEST_HB"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"PARTICIP" => Array(
			"NAME" => GetMessage("ADMIN_MAIN_PARTICIP"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"MESSAGE" => Array(
			"NAME" => GetMessage("ADMIN_MAIN_MESSAGE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
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
	    "COPY_TO_OUTBOX" => Array(
	        "PARENT" => "ADDITIONAL_SETTINGS",
	        "NAME" => GetMessage("HLM_COPY_TO_OUTBOX"),
	        "TYPE" => "CHECKBOX",
	        "DEFAULT" => "Y"),
	)
);
?>