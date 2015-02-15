<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = array(
	"PARAMETERS" => array(

		"AUTH" => array(
			"NAME" => GetMessage("REGISTER_AUTOMATED_AUTH"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"PARENT" => "ADDITIONAL_SETTINGS",
		),

		"USE_BACKURL" => array(
			"NAME" => GetMessage("REGISTER_USE_BACKURL"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"PARENT" => "ADDITIONAL_SETTINGS",
		),

		"SUCCESS_PAGE" => array(
			"NAME" => GetMessage("REGISTER_SUCCESS_PAGE"),
			"TYPE" => "STRING",
			"DEFAULT" => "/registr/yes_e.php",
			"PARENT" => "ADDITIONAL_SETTINGS",
		),

		"EXHIBIT_IBLOCK_ID" => array(
				"NAME" => GetMessage("R_PROP_EXHIBIT_IBLOCK_ID"),
				"TYPE" => "STRING",
				"DEFAULT" => "15",
				"PARENT" => "ADDITIONAL_SETTINGS",
		),

		"GUEST_FORM_ID" => array(
				"NAME" => GetMessage("R_PROP_GUEST_FORM_ID"),
				"TYPE" => "STRING",
				"DEFAULT" => "10",
				"PARENT" => "ADDITIONAL_SETTINGS",
		),
			
		"COMPANY_FORM_ID" => array(
				"NAME" => GetMessage("R_PROP_COMPANY_FORM_ID"),
				"TYPE" => "STRING",
				"DEFAULT" => "3",
				"PARENT" => "ADDITIONAL_SETTINGS",
		),
			
		"PARTICIPANT_FORM_ID" => array(
				"NAME" => GetMessage("R_PROP_PARTICIPANT_FORM_ID"),
				"TYPE" => "STRING",
				"DEFAULT" => "4",
				"PARENT" => "ADDITIONAL_SETTINGS",
		),
			
		"IBLOCK_PHOTO" => array(
				"NAME" => GetMessage("R_PROP_IBLOCK_PHOTO"),
				"TYPE" => "STRING",
				"DEFAULT" => "16",
				"PARENT" => "ADDITIONAL_SETTINGS",
		),
			
		"TERMS_LINK" => array(
				"NAME" => GetMessage("R_PROP_TERMS_LINK"),
				"TYPE" => "STRING",
				"DEFAULT" => "/terms/",
				"PARENT" => "ADDITIONAL_SETTINGS",
		),	
	),

);
?>