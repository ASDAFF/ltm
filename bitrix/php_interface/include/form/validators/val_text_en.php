<?
CModule::IncludeModule("form");
IncludeModuleLangFile(__FILE__);

class CFormValidatorTextEn
{
	function GetDescription()
	{
		return array(
			"NAME" => "text_en", // unique validator string ID
			"DESCRIPTION" => "Только латиница", // validator description
			"TYPES" => array("text", "textarea", "password", "email", "url"), //  list of types validator can be applied.
			"HANDLER" => array("CFormValidatorTextEn", "DoValidate") // main validation method
		);
	}

	function DoValidate($arParams, $arQuestion, $arAnswers, $arValues)
	{
		global $APPLICATION;

		foreach ($arValues as $value)
		{
			if (preg_match("|^[a-z0-9 ,\"\'\-\&\(\)/]+$|i", $value))
			{
				return true;
			}
			elseif(!$value){
				return true;
			}
			else{
				$APPLICATION->ThrowException("#FIELD_NAME#: Only in english!");
				return false;
			}
		}
		
	}
}
AddEventHandler("form", "onFormValidatorBuildList", array("CFormValidatorTextEn", "GetDescription"));
?>