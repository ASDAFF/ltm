<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("PROFILE_TITLE"),
	"DESCRIPTION" => GetMessage("PROFILE_DESCR"),
	"ICON" => "/images/user_authform.gif",
	"PATH" => array(
			"ID" => "utility",
			"CHILD" => array(
				"ID" => "user",
				"NAME" => GetMessage("PROFILE_NAME")
			)
		),	
);
?>