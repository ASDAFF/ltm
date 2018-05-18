<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
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

$rsData = CUserTypeEntity::GetList( array(), array("ENTITY_ID" => "HLBLOCK_" . $arParams["GUEST_HL_BLOCK_ID"], 'LANG' => LANGUAGE_ID) );
while($arRes = $rsData->Fetch())
{
    $arResult['FORM_DATA_GUEST'][$arRes["FIELD_NAME"]] = $arRes;
    if($arRes["USER_TYPE_ID"] == "hlblock"){
        $hlblock = HL\HighloadBlockTable::getById($arRes["SETTINGS"]["HLBLOCK_ID"])->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $result = $entity_data_class::getList();
        while ($arElem = $result->Fetch()){
            $arResult['FORM_DATA_GUEST'][$arRes["FIELD_NAME"]]["ITEMS"][] = $arElem;
        }
    }
}

$rsData = CUserTypeEntity::GetList( array(), array("ENTITY_ID" => "HLBLOCK_" . $arParams["GUEST_COLLEAGUE_HL_BLOCK_ID"], 'LANG' => LANGUAGE_ID) );
while($arRes = $rsData->Fetch())
{
    $arResult['FORM_DATA_GUEST_COLLEAGUE'][$arRes["FIELD_NAME"]] = $arRes;
    if($arRes["USER_TYPE_ID"] == "hlblock"){
        $hlblock = HL\HighloadBlockTable::getById($arRes["SETTINGS"]["HLBLOCK_ID"])->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $result = $entity_data_class::getList();
        while ($arElem = $result->Fetch()){
            $arResult['FORM_DATA_GUEST_COLLEAGUE'][$arRes["FIELD_NAME"]]["ITEMS"][] = $arElem;
        }
    }
}

?>