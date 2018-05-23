<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?

use \Bitrix\Highloadblock as HL;
use \Bitrix\Main\Context;

if (CModule::IncludeModule("highloadblock")) {

    $request = Context::getCurrent()->getRequest();
    $userId = $request->getQuery('id') ?: false;
    $arResult = [];
    if ($userId) {
        $hlblock = HL\HighloadBlockTable::getById($arParams["HLBLOCK_REGISTER_GUEST_ID"])->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $arrFilter = ["UF_USER_ID" => $userId];
        $result = $entity_data_class::getList([
            'filter' => $arrFilter,
        ])->Fetch();
        if($result){
            $arResult["USER_DATA"] = $result;
            $rsData = CUserTypeEntity::GetList(array(), array("ENTITY_ID" => "HLBLOCK_" . $arParams["HLBLOCK_REGISTER_GUEST_ID"], 'LANG' => LANGUAGE_ID));
            $arHlBlockInfo = [];
            while ($arRes = $rsData->Fetch()) {
                $arHlBlockInfo[$arRes["FIELD_NAME"]] = $arRes;
                if ($arRes["USER_TYPE_ID"] == "hlblock") {
                    $hlblock = HL\HighloadBlockTable::getById($arRes["SETTINGS"]["HLBLOCK_ID"])->fetch();
                    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                    $entity_data_class = $entity->getDataClass();
                    $arrFilter = [];
                    if ($arRes["FIELD_NAME"] === "UF_COLLEAGUES") {
                        $arrFilter["ID"] = $arResult["USER_DATA"]["UF_COLLEAGUES"];
                        $ufEnum = CUserTypeEntity::GetList( array(), array("ENTITY_ID" => "HLBLOCK_" . 18, 'LANG' => LANGUAGE_ID, 'FIELD_NAME' => 'UF_DAYTIME') )->Fetch();
                        $rsDayTime = CUserFieldEnum::GetList(array(), array(
                            "USER_FIELD_ID" => $ufEnum['ID'],
                        ));
                        while ($dayTime = $rsDayTime->Fetch()){
                            $arHlBlockInfo[$arRes["FIELD_NAME"]]["DAY_TIMES"][$dayTime['ID']] = $dayTime;
                        }
                        $arHlBlockInfo[$arRes["FIELD_NAME"]]["HIDDEN_FIELDS"] = ["ID", "UF_GUEST_ID"];
                    }
                    $result = $entity_data_class::getList([
                        'filter' => $arrFilter,
                    ]);
                    while ($arElem = $result->Fetch()) {
                        $arHlBlockInfo[$arRes["FIELD_NAME"]]["ITEMS"][$arElem["ID"]] = $arElem;
                    }
                }
            }
            $arResult["FIELD_DATA"] = $arHlBlockInfo;
            $arResult["FIELD_DATA_CHECKED_ALL"] = ["UF_NORTH_AMERICA", "UF_EUROPE", "UF_SOUTH_AMERICA", "UF_AFRICA", "UF_ASIA", "UF_OCEANIA"];
        }
    } else {
        echo ShowError("НЕТУ ЮЗЕРА");
    }

    if($request->isPost()){
        if(check_bitrix_sessid()){
            $postValues = $request->getPostList()->toArray();
            unset($postValues['sessid']);
            unset($postValues['check_all']);
            $colleaguesIds = [];
            foreach ($postValues['COLLEAGUE'] as $key=>$colleague){
                $hlblock = HL\HighloadBlockTable::getById(18)->fetch();
                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();
                $result = $entity_data_class::update($colleague['ID'], $colleague);
                $colleaguesIds[] = $result->getId();
            }
            unset($postValues["COLLEAGUE"]);
            $postValues["UF_COLLEAGUES"] = $colleaguesIds;
            $hlblock = HL\HighloadBlockTable::getById(intval($arParams["HLBLOCK_REGISTER_GUEST_ID"]))->fetch();
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();
            $result = $entity_data_class::update($postValues['ID'], $postValues);
        }
    }

    $this->IncludeComponentTemplate();

} else {
    echo ShowError(GetMessage("FORM_MODULE_NOT_INSTALLED"));
}
?>