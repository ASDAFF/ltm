<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Highloadblock as HL,
    Bitrix\Main\Loader,
    PhpOffice\PhpSpreadsheet\Spreadsheet,
    Bitrix\Main\Data\Cache,
    PhpOffice\PhpSpreadsheet\IOFactory;

Loader::includeModule('highloadblock');
Loader::includeModule('iblock');

class XlsxGenerator extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $result = $arParams;
        return $result;
    }

    public function executeComponent()
    {
        $this->generateXlsx();
    }

    public function getEntityClass($id)
    {
        $id = intval($id);
        if ($id !== 0 && Loader::includeModule('highloadblock')) {
            $hlBlock = HL\HighloadBlockTable::getById($id)->fetch();
            try {
                $entity = HL\HighloadBlockTable::compileEntity($hlBlock);
            } catch (Exception $e) {
                return null;
            }
            $entity_data_class = $entity->getDataClass();
            return $entity_data_class;
        }
    }

    public function getData($hlblock, $parameters = [], $prefix = '')
    {
        $prefix = $prefix ? $prefix . '_' : '';
        $fields = $this->getFieldsData($hlblock);
        $result = [];
        $entity = $this->getEntityClass($hlblock);
        if ($entity !== null) {
            try {
                $rsData = $entity::getList($parameters);
            } catch (Exception $exception) {
                return [];
            }
            while ($data = $rsData->fetch()) {
                if ($hlblock === $this->arParams['REGISTER_GUEST_ENTITY_ID']) {
                    $exib = $this->getExhibition();
                    $arUserFilter = ['ID' => $data['UF_USER_ID'], 'ACTIVE' => 'Y', 'GROUPS_ID' => $exib['PROPERTY']['C_GUESTS_GROUP']['VALUE']];
                    switch ($this->arParams['GUEST_TYPE']) {
                        case 'MORNING':
                            $arUserFilter['UF_MR'] = true;
                            break;
                        case 'EVENING':
                            $arUserFilter['UF_EV'] = true;
                            break;
                        case 'HB':
                            $arUserFilter['UF_HB'] = true;
                            break;
                        case 'SPAM':
                            $arUserFilter['GROUPS_ID'] = $exib['PROPERTY']['GUEST_SPAM_GROUP']['VALUE'];
                            break;
                        case 'UNCONFIRMED':
                            $arUserFilter['GROUPS_ID'] = $exib['PROPERTY']['UC_GUESTS_GROUP']['VALUE'];
                            break;
                    }
                    $rsUser = CUser::GetList(
                        $by = 'ID',
                        $order = 'ASC',
                        $arUserFilter,
                        array(
                            'SELECT' => array('UF_*'),
                            'FIELDS' => array('ID', 'LOGIN', 'DATE_REGISTER')
                        )
                    );
                    if ($user = $rsUser->Fetch()) {
                        foreach ($user as $key => $value){
                            $data['USER_' . $key] = $value;
                        }
                    }else{
                        continue;
                    }
                }
                $tmpData = [];
                foreach ($data as $key => $value) {
                    $tmpData['ID'] = $data['ID'];
                    if ($field = $fields[$prefix . $key]) {
                        switch ($field['USER_TYPE_ID']) {
                            case 'hlblock':
                                if ($value) {
                                    $parameters['filter'] = ['ID' => $value];
                                    if ($field['SETTINGS']['HLBLOCK_ID'] === $this->arParams['REGISTER_GUEST_COLLEAGUES_ENTITY_ID']) {
                                        $daytimes = $fields[$key . '_UF_DAYTIME']['ITEMS'];
                                        $daytime = reset($daytimes);
                                        switch ($this->arParams['GUEST_TYPE']) {
                                            case 'EVENING':
                                                foreach ($daytimes as $item) {
                                                    if ($item['XML_ID'] === 'evening') {
                                                        $daytime = $item;
                                                    }
                                                }
                                                $parameters['filter']['UF_DAYTIME'] = [$daytime['ID']];
                                                break;
                                            case 'MORNING':
                                            case 'HB':
                                                foreach ($daytimes as $item) {
                                                    if ($item['XML_ID'] === 'morning') {
                                                        $daytime = $item;
                                                    }
                                                }
                                                $parameters['filter']['UF_DAYTIME'] = [$daytime['ID']];
                                                break;
                                            default:
                                                $parameters['filter']['UF_DAYTIME'] = [$daytime['ID']];
                                                break;
                                        }
                                    }
                                    $items = $this->getData($field['SETTINGS']['HLBLOCK_ID'], $parameters, $key);

                                    if ($field['SETTINGS']['HLBLOCK_ID'] === $this->arParams['REGISTER_GUEST_COLLEAGUES_ENTITY_ID']) {
                                        if ($this->arParams['FORMAT_TYPE'] === 'PEOPLE') {
                                            $tmpData[$key] = $items;
                                            continue;
                                        }
                                        $tmpData[$key] = reset($items);
                                        foreach ($tmpData[$key] as $ckey => $value) {
                                            $tmpData[$key . '_' . $ckey] = $value;
                                        }
                                    } else {
                                        if ($field['MULTIPLE'] !== 'Y') {
                                            $tmpData[$key] = reset($items)['UF_VALUE']?:reset($items)['UF_NAME'];
                                            if ($key === 'UF_COUNTRY' && $tmpData[$key] === 'other') {
                                                $tmpData[$key] = $data['UF_COUNTRY_OTHER'];
                                            }
                                        } else {
                                            $tmpData[$key] = $items;
                                        }
                                    }
                                }
                                break;
                            case 'enumeration':
                                $tmpData[$key] = $field['ITEMS'][$value]['VALUE'];
                                break;
                            default:
                                $tmpData[$key] = $value;
                                break;
                        }
                    }
                }
                $result[] = $tmpData;
            }
        }
        return $result;
    }

    public function getFieldsData($hlblockId, $prefix = '')
    {

        $cache = Cache::createInstance();
        if ($cache->initCache(7200, $this->arParams['FORMAT_TYPE'] . '_' . $this->arParams['GUEST_TYPE'] .'_fieldData_'. serialize($this->arParams['SHOW_FIELDS_IN_FILE']) . $prefix)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $result = [];
            $prefix = $prefix ? $prefix . '_' : '';
            if (intval($hlblockId) !== 0) {
                $userTypes = CUserTypeEntity::GetList(array(), array('ENTITY_ID' => 'HLBLOCK_' . $hlblockId, 'LANG' => 'ru'));
                while ($data = $userTypes->Fetch()) {
                    switch ($data['USER_TYPE_ID']) {
                        case 'hlblock':
                            $result[$prefix . $data['FIELD_NAME']] = $data;
                            $fields = $this->getFieldsData($data['SETTINGS']['HLBLOCK_ID'], $prefix . $data['FIELD_NAME']);
                            $result = array_merge($result, $fields);
                            break;
                        case 'enumeration':
                            $enData = CUserFieldEnum::GetList(array('ID' => 'ASC'), array(
                                'USER_FIELD_ID' => $data['ID'],
                            ));
                            while ($enDataItem = $enData->Fetch()) {
                                $data['ITEMS'][$enDataItem['ID']] = $enDataItem;
                            }
                            $result[$prefix . $data['FIELD_NAME']] = $data;
                            break;
                        default:
                            $result[$prefix . $data['FIELD_NAME']] = $data;
                            break;
                    }
                }
                if($hlblockId === $this->arParams['REGISTER_GUEST_ENTITY_ID']){
                    //Получаем пользовательские поля сущьности USER
                    $userTypes = CUserTypeEntity::GetList(array(), array('ENTITY_ID' => 'USER', 'LANG' => 'ru'));
                    while ($data = $userTypes->Fetch()) {
                        switch ($data['USER_TYPE_ID']) {
                            case 'hlblock':
                                $result['USER_' . $data['FIELD_NAME']] = $data;
                                $fields = $this->getFieldsData($data['SETTINGS']['HLBLOCK_ID'], $prefix . $data['FIELD_NAME']);
                                $result = array_merge($result, $fields);
                                break;
                            case 'enumeration':
                                $enData = CUserFieldEnum::GetList(array('ID' => 'ASC'), array(
                                    'USER_FIELD_ID' => $data['ID'],
                                ));
                                while ($enDataItem = $enData->Fetch()) {
                                    $data['ITEMS'][$enDataItem['ID']] = $enDataItem;
                                }
                                $result['USER_' . $data['FIELD_NAME']] = $data;
                                break;
                            default:
                                $result['USER_' . $data['FIELD_NAME']] = $data;
                                break;
                        }
                    }
                    $ufields = Bitrix\Main\UserTable::getMap();
                    foreach ($ufields as $key => $ufield){
                        $result['USER_' . $key] = $ufield;
                    }
                }
            }
            $cache->endDataCache($result);
        }
        return $result;
    }

    public function generateArray()
    {
        $header = $this->getHeader();
        $result = [];
        $parameters = [
            'filter' => [
                'UF_EXHIB_ID' => $this->arParams['EXHIBITION_ID']
            ],
        ];
        $data = $this->getData($this->arParams['REGISTER_GUEST_ENTITY_ID'], $parameters);
        foreach ($data as $item) {
            $tmpData = [];
            $tmpColleague = [];
            foreach ($header as $headCode => $head) {
                if(is_array($head)){
                    foreach ($head as $headInnerCode){
                        $tmpItem = $item[$headInnerCode];
                        if(is_array($tmpItem)){
                            $prev = $item[$headCode];
                            $item[$headCode] .= array_reduce($tmpItem, function ($carry, $item){
                                if($carry){
                                    $carry .= ', ' . $item['UF_VALUE'];
                                }else{
                                    $carry = $item['UF_VALUE'];
                                }
                                return $carry;
                            }, $prev);
                        }
                    }
                }
                if(is_array($item[$headCode])){
                    $tmpData[$headCode] = array_reduce($item[$headCode], function ($carry, $item){
                        if($carry){
                            $carry .= ', ' . $item['UF_VALUE'];
                        }else{
                            $carry = $item['UF_VALUE'];
                        }
                        return $carry;
                    });
                }else{
                    $tmpData[$headCode] = $item[$headCode];
                }
                if ($this->arParams['FORMAT_TYPE'] === 'PEOPLE') {
                    foreach ($item['UF_COLLEAGUES'] as $ckey => $colleague) {
                        switch ($headCode) {
                            case 'UF_POSITION':
                                $tmpColleague[$ckey]['UF_POSITION'] = $colleague['UF_JOB_TITLE'];
                                break;
                            case 'UF_MOBILE':
                                $tmpColleague[$ckey]['UF_MOBILE'] = $colleague['UF_JOB_TITLE'];
                                break;
                            default:
                                if ($colleague[$headCode]) {
                                    $tmpColleague[$ckey][$headCode] = $colleague[$headCode];
                                } else {
                                    $tmpColleague[$ckey][$headCode] = $item[$headCode];
                                }
                                break;
                        }
                    }
                }
            }
            $result[] = $tmpData;
            if ($this->arParams['FORMAT_TYPE'] === 'PEOPLE') {
                foreach ($tmpColleague as $colleague) {
                    $result[] = $colleague;
                }
            }
        }
        return $result;
    }

    public function getHeader($noArray = false)
    {
        $fieldsGuest = $this->getFieldsData($this->arParams['REGISTER_GUEST_ENTITY_ID']);
        $result = [];
        foreach ($this->arParams['SHOW_FIELDS_IN_FILE'] as $key => $item) {
            if (array_key_exists($item, $fieldsGuest)) {
                $field = $fieldsGuest[$item];
                $result[$item] = $field['LIST_COLUMN_LABEL'] ?: $item;
            }elseif(is_array($item)){
                if($noArray){
                    $result[$key] = $key;
                }else{
                    $result[$key] = $item;
                }
            }
        }
        return $result;
    }

    public function generateXlsx()
    {
        $alphabet = range('A', 'Z');
        foreach ($alphabet as $key){
            foreach ($alphabet as $value){
                $manyAlphabet[] = $key.$value;
            }
        }
        $alphabet = array_merge($alphabet, $manyAlphabet);
        $header = $this->getHeader(true);
        $data = $this->generateArray();
        $spreadsheet = new Spreadsheet();
        try {
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->fromArray($header, '', 'A1');
            $sheet->fromArray($data, null, 'A2');
            for ($i = 0; $i < count($header); $i++) {
                $sheet->getColumnDimension($alphabet[$i])->setAutoSize(true);
            }
            $sheet->setAutoFilter('A1:' . $alphabet[count($header) - 1] . 1);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $this->getFileName() . '"');
            header('Cache-Control: max-age=0');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        } catch (Exception $exception) {
            c($exception);
            die($exception->getMessage());
        }
    }

    public function getFileName()
    {
        $exib = $this->getExhibition();
        $result = 'Гости ' . $exib['NAME'];
        switch ($this->arParams['GUEST_TYPE']) {
            case 'MORNING':
                $result .= ' (Утро)';
                break;
            case 'EVENING':
                $result .= ' (Вечер)';
                break;
            case 'HB':
                $result .= ' (HB)';
                break;
            case 'SPAM':
                $result .= ' (Спам)';
                break;
            case 'UNCONFIRMED':
                $result .= ' (Неподтверждёные)';
                break;
        }
        if ($this->arParams['FORMAT_TYPE'] === 'COMPANY') {
            $result .= ' - по компаниям.xlsx';
        } elseif ($this->arParams['FORMAT_TYPE'] === 'PEOPLE') {
            $result .= ' - по людям.xlsx';
        } else {
            $result .= '.xlsx';
        }
        return $result;
    }

    public function getExhibition()
    {
        $cache = Cache::createInstance();
        if ($cache->initCache(7200, $this->arParams['FORMAT_TYPE'] . '_' . $this->arParams['GUEST_TYPE'] . 'exhib' . $this->arParams['EXHIBITION_ID'])) {
            $exib = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $exib = CIBlockElement::GetByID($this->arParams['EXHIBITION_ID'])->Fetch();
            $rsProps = CIBlockElement::GetProperty($exib['IBLOCK_ID'], $exib['ID']);
            while ($prop = $rsProps->Fetch()) {
                $exib['PROPERTY'][$prop['CODE']] = $prop;
            }

            $cache->endDataCache($exib);
        }
        return $exib;
    }
}
