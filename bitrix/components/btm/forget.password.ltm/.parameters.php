<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = Array(
	"GROUPS" => array(
		"MAIN_SETTINGS" => array(
			"NAME" => GetMessage("ADMIN_MENU_MAIN_SETTINGS"),
		),
	),
	"PARAMETERS" => Array(
		"REGISTER_URL" => Array(
			"NAME" => GetMessage("REGISTER_URL"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "MAIN_SETTINGS",
		),
		"GUEST_ID" => Array(
			"NAME" => GetMessage("GUEST_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
			"PARENT" => "MAIN_SETTINGS",
		),
		"PARTICIP_ID" => Array(
			"NAME" => GetMessage("PARTICIP_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
			"PARENT" => "MAIN_SETTINGS",
		),
	)
);
?>