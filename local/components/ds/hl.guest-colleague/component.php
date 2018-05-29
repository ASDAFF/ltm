<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<?

use \Bitrix\Highloadblock as HL;
use \Bitrix\Main\Context;

const FILE_DIR = 'hlguest_colleague';

if (CModule::IncludeModule('highloadblock')) {

    $request = Context::getCurrent()->getRequest();
    $userId = $request->getQuery('id') ?: $arParams['USER_ID'];
    $arResult = [];

    $rsData = CUserTypeEntity::GetList(array(), array('ENTITY_ID' => 'HLBLOCK_' . $arParams['HLBLOCK_REGISTER_GUEST_COLLEAGUE_ID'], 'LANG' => LANGUAGE_ID));
    $arHlBlockInfo = [];
    while ($arRes = $rsData->Fetch()) {
        if (is_array($arParams['FIELD_TO_SHOW']) && $arParams['FIELD_TO_SHOW']) {
            if (!in_array($arRes['FIELD_NAME'], $arParams['FIELD_TO_SHOW'])) {
                continue;
            }
        }
        $arHlBlockInfo[$arRes['FIELD_NAME']] = $arRes;
        if ($arRes['USER_TYPE_ID'] === 'hlblock') {
            $hlblock = HL\HighloadBlockTable::getById($arRes['SETTINGS']['HLBLOCK_ID'])->fetch();
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();
            $result = $entity_data_class::getList();
            while ($arElem = $result->Fetch()) {
                $arHlBlockInfo[$arRes['FIELD_NAME']]['ITEMS'][$arElem['ID']] = $arElem;
            }
        } elseif ($arRes['USER_TYPE_ID'] === 'enumeration') {
            $rsDayTime = CUserFieldEnum::GetList(array('ID' => 'ASC'), array(
                'USER_FIELD_ID' => $arRes['ID'],
            ));
            while ($dayTime = $rsDayTime->Fetch()) {
                $arHlBlockInfo[$arRes['FIELD_NAME']]['DAY_TIMES'][$dayTime['ID']] = $dayTime;
            }
        }
    }
    $arResult['FIELD_DATA'] = $arHlBlockInfo;


    if ($request->isPost()) {
        if (check_bitrix_sessid()) {
            $postValues = $request->getPostList()->toArray();
            $files = $request->getFileList()->toArray();
            $newFiles = [];
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
            if ($postValues['COLLEAGUE']) {
                $colleaguesIds = [];
                foreach ($postValues['COLLEAGUE'] as $key => $colleague) {
                    $hlblock = HL\HighloadBlockTable::getById($arParams['HLBLOCK_REGISTER_GUEST_COLLEAGUE_ID'])->fetch();
                    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                    $entity_data_class = $entity->getDataClass();
                    if ($colleague['ID']) {
                        $result = $entity_data_class::update($colleague['ID'], $colleague);
                    } else {
                        $result = $entity_data_class::add($colleague);
                    }
                    $colleaguesIds[] = $result->getId();
                }
            }
            $data['UF_COLLEAGUES'] = $colleaguesIds;
            $hlblock = HL\HighloadBlockTable::getById(intval($arParams['HLBLOCK_REGISTER_GUEST_ID']))->fetch();
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();
            $result = $entity_data_class::update($postValues['ID'], $data);
        }
    }

    if ($userId) {
        $hlblock = HL\HighloadBlockTable::getById($arParams['HLBLOCK_REGISTER_GUEST_ID'])->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $arrParams = [
            'filter' => [
                'UF_USER_ID' => $userId
            ],
            'select' => [
                'UF_MORNING',
                'UF_EVENING',
                'UF_COLLEAGUES',
                'ID',
            ],
        ];
        $result = $entity_data_class::getList($arrParams)->Fetch();
        if ($result) {
            $result['ALL_COLLEAGUES'] = 0;
            if ($result['UF_MORNING']) {
                $result['ALL_COLLEAGUES'] += 1;
            }
            if ($result['UF_EVENING']) {
                $result['ALL_COLLEAGUES'] += 1;
            }
            $hlblock = HL\HighloadBlockTable::getById($arParams['HLBLOCK_REGISTER_GUEST_COLLEAGUE_ID'])->fetch();
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();
            $colleagues = $entity_data_class::getList([
                'filter' => ['ID' => $result['UF_COLLEAGUES']],
                'order' => [
                    'UF_DAYTIME' => 'ASC'
                ]
            ]);
            while ($colleague = $colleagues->Fetch()) {
                $colleague['UF_DAYTIME'] = reset($colleague['UF_DAYTIME']);
                $result['COLLEAGUES'][$colleague['ID']] = $colleague;
            }
            $arResult['USER_DATA'] = $result;
        }
    }
    $arResult['FORM_URL'] = $request->getRequestUri();
    $this->IncludeComponentTemplate();

} else {
    echo ShowError(GetMessage('FORM_MODULE_NOT_INSTALLED'));
}
?>