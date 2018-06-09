<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
use \Bitrix\Highloadblock as HL;
use \Bitrix\Main\Context;

const FILE_DIR = 'hlguest';

if (CModule::IncludeModule("highloadblock")) {

    $request = Context::getCurrent()->getRequest();
    $userId = $request->getQuery('id')?: $arParams["USER_ID"];
    $arResult = [];

    if($request->isPost()){
        if(check_bitrix_sessid()){
            $postValues = $request->getPostList()->toArray();
            $files = $request->getFileList()->toArray();
            $guestId = $postValues['ID'];
            unset($postValues['sessid']);
            unset($postValues['check_all']);
            foreach ($files["COLLEAGUE"] as $field => $dataField) {
                foreach ($dataField as $id => $value) {
                    $newFiles[$id]["UF_PHOTO"][$field] = reset($value);
                }
            }
            foreach ($newFiles as $id => $item) {
                foreach ($item as $key => $file) {
                    $fileId = CFile::SaveFile($file, FILE_DIR);
                    if ($fileId) {
                        $postValues['COLLEAGUE'][$id][$key] = $fileId;
                    }
                }
            }
            unset($files["COLLEAGUE"]);
            foreach ($files as $key => $file){
                if($postValues[$key.'_del']){
                    $postValues[$key] = false;
                    unset($postValues[$key.'_del']);
                    continue;
                }
                $fileId = CFile::SaveFile($file, FILE_DIR);
                if($fileId){
                    $postValues[$key] = $fileId;
                }
            }
            if($arParams["FIELD_TO_SHOW"]){
                foreach ($postValues as $key=>$value){
                    if(!in_array($key, $arParams["FIELD_TO_SHOW"])){
                        unset($postValues[$key]);
                    }
                }
            }
            if ($postValues['COLLEAGUE']) {
                $colleaguesIds = [];
                foreach ($postValues['COLLEAGUE'] as $key => $colleague) {
                    $hlblock = HL\HighloadBlockTable::getById($arParams['HLBLOCK_REGISTER_GUEST_COLLEAGUE_ID'])->fetch();
                    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                    $entity_data_class = $entity->getDataClass();
                    if ($colleague['ID']) {
                        $result = $entity_data_class::update($colleague['ID'], $colleague);
                    } else {
                        if(count(array_filter($colleague)) > 1){
                            $result = $entity_data_class::add($colleague);
                        }
                    }
                    if($result){
                        if($result->isSuccess()){
                            $colleaguesIds[] = $result->getId();
                        }else{
                            $arResult['ERRORS'][] = $result->getErrors();
                        }
                    }
                }
                unset($postValues["COLLEAGUE"]);
                $postValues["UF_COLLEAGUES"] = $colleaguesIds;
            }
            $hlblock = HL\HighloadBlockTable::getById(intval($arParams["HLBLOCK_REGISTER_GUEST_ID"]))->fetch();
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();
            $result = $entity_data_class::update($guestId, $postValues);
            if($result->isSuccess()){
                $arResult['SAVED'] = true;
            }else{
                $arResult['ERRORS'][] = $result->getErrors();
            }
        }
    }

    if ($userId) {
        $hlblock = HL\HighloadBlockTable::getById($arParams["HLBLOCK_REGISTER_GUEST_ID"])->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $arrFilter = ["UF_USER_ID" => $userId];
        if($arParams["FIELD_TO_SHOW"] && is_array($arParams["FIELD_TO_SHOW"])){
            $arrSelect = $arParams["FIELD_TO_SHOW"];
        }
        $result = $entity_data_class::getList([
            'filter' => $arrFilter,
        ])->Fetch();
        if($result){
            $result["USER"] = CUser::GetByID($userId)->Fetch();
            $result['ALL_COLLEAGUES'] = 0;
            if ($result['UF_MORNING']) {
                $result['ALL_COLLEAGUES'] += 1;
            }
            if ($result['UF_EVENING']) {
                $result['ALL_COLLEAGUES'] += 1;
            }
            $arResult["USER_DATA"] = $result;
            $rsData = CUserTypeEntity::GetList(array("SORT" => "ASC"), array("ENTITY_ID" => "HLBLOCK_" . $arParams["HLBLOCK_REGISTER_GUEST_ID"], 'LANG' => LANGUAGE_ID, 'EDIT_IN_LIST' => !(is_array($arParams["FIELD_TO_SHOW"]) && $arParams["FIELD_TO_SHOW"])?'Y':''));
            $arHlBlockInfo = [];
            while ($arRes = $rsData->Fetch()) {
                if(is_array($arParams["FIELD_TO_SHOW"]) && $arParams["FIELD_TO_SHOW"]){
                    if(!in_array($arRes["FIELD_NAME"], $arParams["FIELD_TO_SHOW"])){
                        continue;
                    }
                }
                $arHlBlockInfo[$arRes["FIELD_NAME"]] = $arRes;
                if ($arRes["USER_TYPE_ID"] == "hlblock") {
                    $hlblock = HL\HighloadBlockTable::getById($arRes["SETTINGS"]["HLBLOCK_ID"])->fetch();
                    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                    $entity_data_class = $entity->getDataClass();
                    $arrFilter = [];
                    if ($arRes["FIELD_NAME"] === "UF_COLLEAGUES") {
                        $arrFilter["ID"] = $arResult["USER_DATA"]["UF_COLLEAGUES"];
                        $rsDataColleague = CUserTypeEntity::GetList(array(), array('ENTITY_ID' => 'HLBLOCK_' . $arParams['HLBLOCK_REGISTER_GUEST_COLLEAGUE_ID'], 'LANG' => LANGUAGE_ID, 'EDIT_IN_LIST' => !(is_array($arParams["FIELD_TO_SHOW"]) && $arParams["FIELD_TO_SHOW"])?'Y':''));
                        $arHlBlockInfoColleague = [];
                        while ($arResColleague = $rsDataColleague->Fetch()) {
                            if (is_array($arParams['FIELD_TO_SHOW']) && $arParams['FIELD_TO_SHOW']) {
                                if (!in_array($arResColleague['FIELD_NAME'], $arParams['FIELD_TO_SHOW'])) {
                                    continue;
                                }
                            }
                            $arHlBlockInfoColleague[$arResColleague['FIELD_NAME']] = $arResColleague;
                            if ($arResColleague['USER_TYPE_ID'] === 'hlblock') {
                                $hlblockColleague = HL\HighloadBlockTable::getById($arResColleague['SETTINGS']['HLBLOCK_ID'])->fetch();
                                $entityColleague = HL\HighloadBlockTable::compileEntity($hlblockColleague);
                                $entity_data_classColleague = $entityColleague->getDataClass();
                                $result = $entity_data_classColleague::getList();
                                while ($arElem = $result->Fetch()) {
                                    $arHlBlockInfoColleague[$arResColleague['FIELD_NAME']]['ITEMS'][$arElem['ID']] = $arElem;
                                }
                            } elseif ($arResColleague['USER_TYPE_ID'] === 'enumeration') {
                                $rsDayTime = CUserFieldEnum::GetList(array('ID' => 'ASC'), array(
                                    'USER_FIELD_ID' => $arResColleague['ID'],
                                ));
                                while ($dayTime = $rsDayTime->Fetch()) {
                                    $arResColleague[$arResColleague['FIELD_NAME']]['DAY_TIMES'][$dayTime['ID']] = $dayTime;
                                }
                            }
                        }
                        $ufEnum = CUserTypeEntity::GetList( array(), array("ENTITY_ID" => "HLBLOCK_" . $arParams["HLBLOCK_REGISTER_GUEST_COLLEAGUE_ID"], 'LANG' => LANGUAGE_ID, 'FIELD_NAME' => 'UF_DAYTIME') )->Fetch();
                        $rsDayTime = CUserFieldEnum::GetList(array(), array(
                            "USER_FIELD_ID" => $ufEnum['ID'],
                        ));
                        while ($dayTime = $rsDayTime->Fetch()){
                            $arHlBlockInfo[$arRes["FIELD_NAME"]]["DAY_TIMES"][$dayTime['ID']] = $dayTime;
                        }
                        $arHlBlockInfo[$arRes["FIELD_NAME"]]["FIELDS"] = $arHlBlockInfoColleague;
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
        echo ShowError("ERROR");
    }

    $arResult['FORM_URL'] = $request->getRequestUri();
    $this->IncludeComponentTemplate();

} else {
    echo ShowError(GetMessage("FORM_MODULE_NOT_INSTALLED"));
}
?>