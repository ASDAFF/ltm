<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arUserTypes = array(
	'PARTICIP' => 'PARTICIP',
	'GUEST' => 'GUEST'
);

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"APP_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("APP_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["APP_ID"]}',
		),
	),
);
?>