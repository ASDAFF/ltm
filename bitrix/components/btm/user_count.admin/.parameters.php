<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = Array(
	"GROUPS" => array(
		"MAIN_SETTINGS" => array(
			"NAME" => GetMessage("ADMIN_USER_COUNT_MAIN_SETTINGS"),
		),
	),
	"PARAMETERS" => Array(
		"PATH_TO_KAB" => Array(
			"NAME" => GetMessage("ADMIN_USER_COUNT_PATH_TO_KAB"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GROUP_ID" => Array(
			"NAME" => GetMessage("ADMIN_USER_COUNT_GROUP_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
			"PARENT" => "MAIN_SETTINGS",
		),
		"USER_TYPE" => Array(
			"NAME" => GetMessage("ADMIN_USER_COUNT_TYPE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"USER" => Array(
			"NAME" => GetMessage("ADMIN_USER_COUNT_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
	)
);
?>