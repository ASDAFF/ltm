<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//получаем данные вебформы для гостей

$FORM_ID = $arParams["GUEST_FORM_ID"];
CForm::GetDataByID(
	$FORM_ID, 
    $arResult["GUEST_FORM"]["FORM"],
    $arResult["GUEST_FORM"]["QUESTIONS"],
    $arResult["GUEST_FORM"]["ANSWERS"],
    $arResult["GUEST_FORM"]["DROPDOWN"],
    $arResult["GUEST_FORM"]["MULTISELECT"]
);

//pre($arResult["GUEST_FORM"]["QUESTIONS"]);


$buyer = new Ltm\Domain\Data\Buyer();
$arResult['GUEST_FORM_QUESTIONS'] = $buyer->getMap();
pre($arResult);

?>