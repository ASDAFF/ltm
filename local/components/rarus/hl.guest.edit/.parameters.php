<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("form")) return;

$arYesNo = array("Y" => GetMessage("FORM_COMP_VALUE_YES"), "N" => GetMessage("FORM_COMP_VALUE_NO"));
			
$arComponentParameters = array(
	"GROUPS" => array(
		"FORM_PARAMS" => array(
			"NAME" => GetMessage("COMP_FORM_GROUP_PARAMS")
		),
	),	

	"PARAMETERS" => array(
        "HLBLOCK_REGISTER_GUEST_ID" => array(
            "NAME" => GetMessage("COMP_FORM_PARAMS_RESULT_ID"),
            "TYPE" => "STRING",
            "DEFAULT" => "15",
            "PARENT" => "DATA_SOURCE",
        ),
        "HLBLOCK_REGISTER_GUEST_COLLEAGUE_ID" => array(
            "NAME" => GetMessage("COMP_FORM_PARAMS_RESULT_ID"),
            "TYPE" => "STRING",
            "DEFAULT" => "15",
            "PARENT" => "DATA_SOURCE",
        ),
	),
);
?>