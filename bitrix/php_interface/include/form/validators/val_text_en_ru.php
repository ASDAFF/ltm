<?
CModule::IncludeModule("form");

class CFormValidatorTextEnRu
{
	function GetDescription()
	{
		return array(
			"NAME" => "text_en_ru", // unique validator string ID
			"DESCRIPTION" => "Только латиница (ру)", // validator description
			"TYPES" => array("text", "textarea", "password"), //  list of types validator can be applied.
			"HANDLER" => array("CFormValidatorTextEnRu", "DoValidate") // main validation method
		);
	}

	function DoValidate($arParams, $arQuestion, $arAnswers, $arValues)
	{
		global $APPLICATION;

		foreach ($arValues as $value)
		{
			if (preg_match("|^[a-z0-9 ,\"\'-]+$|i", $value))
			{
				return true;
			}
			elseif(!$value){
				return true;
			}
			else{
				$APPLICATION->ThrowException("#FIELD_NAME#: Только латинские буквы");
				return false;
			}
		}
		
	}
}
AddEventHandler("form", "onFormValidatorBuildList", array("CFormValidatorTextEnRu", "GetDescription"));
?>