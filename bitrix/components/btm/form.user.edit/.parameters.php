<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = Array(
	"GROUPS" => array(
		"MAIN_SETTINGS" => array(
			"NAME" => GetMessage("ADMIN_MENU_MAIN_SETTINGS"),
		),
	),
	"PARAMETERS" => Array(
		"ADMIN" => Array(
			"NAME" => GetMessage("ADMIN_GROUP_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GROUP_ID" => Array(
			"NAME" => GetMessage("USER_GROUP_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
			"PARENT" => "MAIN_SETTINGS",
		),
		"AUTH_PAGE" => Array(
			"NAME" => GetMessage("AUTH_PAGE"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
			"PARENT" => "MAIN_SETTINGS",
		),
		"FORM_ID" => Array(
			"NAME" => GetMessage("FORM_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
			"PARENT" => "MAIN_SETTINGS",
		),
		"EVENT_TEMP" => Array(
			"NAME" => GetMessage("EVENT_TEMP"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
			"PARENT" => "MAIN_SETTINGS",
		),
	)
);
?>