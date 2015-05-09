<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = Array(
	"GROUPS" => array(
		"MAIN_SETTINGS" => array(
			"NAME" => GetMessage("USER_MAIN_SETTINGS"),
		),
	),
	"PARAMETERS" => Array(
		"PATH_TO_KAB" => Array(
			"NAME" => GetMessage("USER_PATH_TO_KAB"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GROUP_SENDER_ID" => Array(
			"NAME" => GetMessage("GROUP_SENDER_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GROUP_RECIVER_ID" => Array(
			"NAME" => GetMessage("GROUP_RECIVER_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
			"PARENT" => "MAIN_SETTINGS",
		),
		"ADMIN_ID" => Array(
			"NAME" => GetMessage("ADMIN_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
			"PARENT" => "MAIN_SETTINGS",
		),
		"USER_TYPE" => Array(
			"NAME" => GetMessage("USER_TYPE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"USER" => Array(
			"NAME" => GetMessage("USER_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"APP_ID" => Array(
			"NAME" => GetMessage("APP_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"APP_TYPE" => Array(
			"NAME" => GetMessage("APP_TYPE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"IS_ACTIVE" => Array(
			"NAME" => GetMessage("IS_ACTIVE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
	)
);
?>