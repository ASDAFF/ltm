<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = Array(
	"GROUPS" => array(
		"MAIN_SETTINGS" => array(
			"NAME" => GetMessage("ADMIN_USER_OFF_MAIN_SETTINGS"),
		),
	),
	"PARAMETERS" => Array(
		"PATH_TO_KAB" => Array(
			"NAME" => GetMessage("ADMIN_USER_OFF_PATH_TO_KAB"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"AUTH_PAGE" => Array(
			"NAME" => GetMessage("ADMIN_USER_OFF_AUTH_PAGE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GROUP_ID" => Array(
			"NAME" => GetMessage("ADMIN_USER_OFF_GROUP_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GUEST_OFF" => Array(
			"NAME" => GetMessage("ADMIN_GUEST_OFF"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"PARTICIP_OFF" => Array(
			"NAME" => GetMessage("ADMIN_PARTICIP_OFF"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GUEST_ACCEPT" => Array(
			"NAME" => GetMessage("ADMIN_GUEST_ACCEPT"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"PARTICIP_ACCEPT" => Array(
			"NAME" => GetMessage("ADMIN_PARTICIP_ACCEPT"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GUEST_SPAM" => Array(
			"NAME" => GetMessage("ADMIN_GUEST_SPAM"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"PARTICIP_SPAM" => Array(
			"NAME" => GetMessage("ADMIN_PARTICIP_SPAM"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GUEST_FORM_ID" => Array(
			"NAME" => GetMessage("ADMIN_GUEST_FORM_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"PARTICIP_FORM_ID" => Array(
			"NAME" => GetMessage("ADMIN_PARTICIP_FORM_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
	)
);
?>