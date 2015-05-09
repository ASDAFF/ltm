<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arUserTypes = array(
	'PARTICIP' => 'PARTICIP',
	'GUEST' => 'GUEST',
);
$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(

		"CACHE_TIME"  =>  Array("DEFAULT"=>3600),
		"APP_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("APP_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["APP_ID"]}',
		),
		"USER_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("USER_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arUserTypes,
			"REFRESH" => "N",
		),
		"USER_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("USER_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '={$_REQUEST["USER_ID"]}',
		),
		"MESSAGE_LINK" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("MESSAGE_LINK"),
			"TYPE" => "STRING",
			"DEFAULT" => "/ru/personal/service/write.php",
		),
	),
);
?>