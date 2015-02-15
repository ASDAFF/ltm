<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//получаем данные вебформы компании

$FORM_COMPANY_ID = $arParams["COMPANY_FORM_ID"];
CForm::GetDataByID(
	$FORM_COMPANY_ID, 
    $arResult["COMPANY_FORM"]["FORM"],
    $arResult["COMPANY_FORM"]["QUESTIONS"],
    $arResult["COMPANY_FORM"]["ANSWERS"],
    $arResult["COMPANY_FORM"]["DROPDOWN"],
    $arResult["COMPANY_FORM"]["MULTISELECT"]
);

//получаем данные вебформы Участника

$FORM_PARTICIPANT_ID = $arParams["PARTICIPANT_FORM_ID"];
CForm::GetDataByID(
	$FORM_PARTICIPANT_ID,
	$arResult["PARTICIPANT_FORM"]["FORM"],
	$arResult["PARTICIPANT_FORM"]["QUESTIONS"],
	$arResult["PARTICIPANT_FORM"]["ANSWERS"],
	$arResult["PARTICIPANT_FORM"]["DROPDOWN"],
	$arResult["PARTICIPANT_FORM"]["MULTISELECT"]
);

//pre($arResult["PARTICIPANT_FORM"]["QUESTIONS"]);



?>