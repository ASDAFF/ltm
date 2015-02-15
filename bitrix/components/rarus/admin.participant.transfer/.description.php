<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("P_TRANSFER_NAME"),
	"DESCRIPTION" => GetMessage("P_TRANSFER_DESCR"),
	"ICON" => "/images/user_authform.gif",
	"PATH" => array(
			"ID" => "utility",
			"CHILD" => array(
				"ID" => "user",
				"NAME" => GetMessage("P_TRANSFER_NAME")
			)
		),
);
?>