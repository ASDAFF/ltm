<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

CFile::InputFile();
$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS"  =>  array(
		"WIDTH"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PHOTO_INPUT_WIDTH"),
			"TYPE" => "STRING",
			"DEFAULT" => "500",
		),
		"HEIGHT"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PHOTO_INPUT_HEIGHT"),
			"TYPE" => "STRING",
			"DEFAULT" => '500',
		),
		"INPUT_NAME"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PHOTO_INPUT_NAME"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
		"FILE_ID"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PHOTO_INPUT_FILE_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => '',
		),
	),
);
?>
