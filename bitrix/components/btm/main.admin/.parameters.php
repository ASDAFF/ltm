<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = Array(
	"GROUPS" => array(
		"MAIN_SETTINGS" => array(
			"NAME" => GetMessage("ADMIN_MAIN_MAIN_SETTINGS"),
		),
	),
	"PARAMETERS" => Array(
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
		"GUEST_ACCEPT" => Array(
			"NAME" => GetMessage("ADMIN_MAIN_GUEST_ACCEPT"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GUEST_EVENING" => Array(
			"NAME" => GetMessage("ADMIN_MAIN_GUEST_EVENING"),
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
		"PARTICIP_ACCEPT" => Array(
			"NAME" => GetMessage("ADMIN_MAIN_PARTICIP_ACCEPT"),
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
	)
);
?>