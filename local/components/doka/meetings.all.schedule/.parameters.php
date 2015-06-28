<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


$arUserTypes = array(
	'PARTICIP' => 'PARTICIP',
	'GUEST' => 'GUEST',
	'ADMIN' => 'ADMIN',
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
		"SEND_REQUEST_LINK" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("SEND_REQUEST_LINK"),
			"TYPE" => "STRING",
			"DEFAULT" => "/ru/personal/service/write.php",
		),
		"CONFIRM_REQUEST_LINK" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("CONFIRM_REQUEST_LINK"),
			"TYPE" => "STRING",
			"DEFAULT" => "/ru/personal/service/write.php",
		),
		"REJECT_REQUEST_LINK" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("REJECT_REQUEST_LINK"),
			"TYPE" => "STRING",
			"DEFAULT" => "/ru/personal/service/write.php",
		),
		"CUT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("CUT"),
			"TYPE" => "STRING",
			"DEFAULT" => "10",
		),
		"HALL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("HALL"),
			"TYPE" => "STRING",
			"DEFAULT" => "10",
		),
		"TABLE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TABLE"),
			"TYPE" => "STRING",
			"DEFAULT" => "10",
		),

	),
);
?>