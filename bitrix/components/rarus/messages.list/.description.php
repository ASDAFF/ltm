<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = array(
	"NAME" => GetMessage("HLM_LIST"),
	"DESCRIPTION" => GetMessage("HLM_LIST_DESCRIPTION"),
	"ICON" => "/images/icon.gif",
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "communication",
		"CHILD" => array(
			"ID" => "highloadblock",
			"NAME" => GetMessage("communication"),
			"CHILD" => array(
				"ID" => "pm",
				"NAME" => GetMessage("PM"),
			)
		)
	),
);
?>