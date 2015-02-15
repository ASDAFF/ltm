<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = Array(
	"GROUPS" => array(
		"MAIN_SETTINGS" => array(
			"NAME" => GetMessage("ADMIN_USER_MAIN_SETTINGS"),
		),
	),
	"PARAMETERS" => Array(
		"PATH_TO_KAB" => Array(
			"NAME" => GetMessage("ADMIN_USER_PATH_TO_KAB"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"AUTH_PAGE" => Array(
			"NAME" => GetMessage("ADMIN_USER_AUTH_PAGE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"USER_TYPE" => Array(
			"NAME" => GetMessage("ADMIN_USER_TYPE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"USER" => Array(
			"NAME" => GetMessage("ADMIN_USER_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GROUP" => Array(
			"NAME" => GetMessage("ADMIN_USER_ON_GROUP_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
			"PARENT" => "MAIN_SETTINGS",
		),
		"APP_ID" => Array(
			"NAME" => GetMessage("ADMIN_USER_APP_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GROUP_OFF" => Array(
			"NAME" => GetMessage("ADMIN_USER_OFF_GROUP_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"APP_DECLINE" => Array(
			"NAME" => GetMessage("ADMIN_USER_APP_DECLINE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GROUP_DECLINE" => Array(
			"NAME" => GetMessage("ADMIN_USER_GROUP_DECLINE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
	)
);
?>