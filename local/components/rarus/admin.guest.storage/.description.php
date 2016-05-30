<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("RARUS_STORAGE_TITLE"),
	"DESCRIPTION" => GetMessage("RARUS_STORAGE_DESCR"),
	"ICON" => "/images/storage.gif",
	"PATH" => array(
			"ID" => "utility",
			"CHILD" => array(
				"ID" => "user",
				"NAME" => GetMessage("MAIN_USER_GROUP_NAME")
			),
		),
);
?>