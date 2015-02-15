<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

$arComponentParameters = array(
	"PARAMETERS" => array(
		"GUEST_URL" => array(
			"NAME" => GetMessage("COMP_AUTH_GUEST_URL"), 
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"PARTICIP_URL" => array(
			"NAME" => GetMessage("COMP_AUTH_PARTICIP_URL"), 
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"ADMIN_URL" => array(
			"NAME" => GetMessage("COMP_AUTH_ADMIN_URL"), 
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"GUEST_GROUP" => array(
			"NAME" => GetMessage("COMP_AUTH_GUEST_GROUP"), 
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"PARTICIP_GROUP" => array(
			"NAME" => GetMessage("COMP_AUTH_PARTICIP_GROUP"), 
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"ADMIN_GROUP" => array(
			"NAME" => GetMessage("COMP_AUTH_ADMIN_GROUP"), 
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"USER_TYPE" => array(
			"NAME" => GetMessage("COMP_AUTH_TYPE"), 
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"USER_GROUP" => array(
			"NAME" => GetMessage("COMP_AUTH_GROUP"), 
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"USER_FORM" => array(
			"NAME" => GetMessage("COMP_AUTH_FORM"), 
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"SHOW_ERRORS" => array(
			"NAME" => GetMessage("COMP_AUTH_SHOW_ERRORS"), 
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
	),
);
?>