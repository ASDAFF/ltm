<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("PHOTO_INPUT_NAME"),
	"DESCRIPTION" => GetMessage("PHOTO_INPUT_DESC"),
	"ICON" => "/images/news_line.gif",
	"SORT" => 10,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "content",
		"CHILD" => array(
			"ID" => "news",
			"NAME" => GetMessage("PHOTO_INPUT_NAME"),
			"SORT" => 10,
		)
	),
);

?>