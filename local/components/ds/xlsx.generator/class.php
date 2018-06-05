<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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
                $tmpData = [];
                foreach ($data as $key => $value) {
                    $tmpData['ID'] = $data['ID'];
                    if ($field = $fields[$prefix . $key]) {
                        switch ($field['USER_TYPE_ID']) {
                            case 'hlblock':
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
                                            $parameters['filter']['UF_DAYTIME'] = $daytime['ID'];
                                            break;
                                        case 'MORNING':
                                        case 'HB':
                                            foreach ($daytimes as $item) {
                                                if ($item['XML_ID'] === 'morning') {
                                                    $daytime = $item;
                                                }
                                            }
                                            $parameters['filter']['UF_DAYTIME'] = $daytime['ID'];
                                            break;
                                        default:
                                            $parameters['filter']['UF_DAYTIME'] = $daytime['ID'];
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
                                        if ($ckey === 'UF_SALUTATION') {
                                            $tmpData[$key . '_' . $ckey] = reset($value)['UF_VALUE'];
                                        } else {
                                            $tmpData[$key . '_' . $ckey] = $value;
                                        }
                                    }
                                } else {
                                    if ($field['MULTIPLE'] !== 'Y') {
                                        $tmpData[$key] = reset($items)['UF_VALUE'];
                                        if($key === 'UF_COUNTRY' && $tmpData[$key] === 'other'){
                                            $tmpData[$key] = $data['UF_COUNTRY_OTHER'];
                                        }
                                    } else {
                                        $tmpData[$key] = $items;
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
        if ($cache->initCache(7200, "fieldData" . $prefix)) {
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
            foreach ($header as $head) {
                $tmpData[$head] = $item[$head];
                foreach ($item['UF_COLLEAGUES'] as $ckey => $colleague) {
                    switch ($head) {
                        case 'UF_POSITION':
                            $tmpColleague[$ckey]['UF_POSITION'] = $colleague['UF_JOB_TITLE'];
                            break;
                        case 'UF_MOBILE':
                            $tmpColleague[$ckey]['UF_MOBILE'] = $colleague['UF_JOB_TITLE'];
                            break;
                        default:
                            if($colleague[$head]){
                                $tmpColleague[$ckey][$head] = $colleague[$head];
                            }else{
                                $tmpColleague[$ckey][$head] = $item[$head];
                            }
                            break;
                    }
                }
            }
            $result[] = $tmpData;
            foreach ($tmpColleague as $colleague) {
                $result[] = $colleague;
            }
        }
        return $result;
    }

    public function getHeader()
    {
        $fieldsGuest = $this->getFieldsData($this->arParams['REGISTER_GUEST_ENTITY_ID']);
        $result = [];
        foreach ($this->arParams['SHOW_FIELDS_IN_FILE'] as $item) {
            if (array_key_exists($item, $fieldsGuest)) {
                $field = $fieldsGuest[$item];
                $result[] = $field['LIST_COLUMN_LABEL'] ?: $item;
            }
        }
        array_unshift($result, 'ID');
        return $result;
    }

    public function generateXlsx()
    {
        $alphabet = range('A', 'Z');
        $header = $this->getHeader();
        $data = $this->generateArray();
        $spreadsheet = new Spreadsheet();
        try {
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->fromArray($header, '', 'A1');
            $sheet->fromArray($data, null, 'A2');
            foreach ($header as $key => $value) {
                $sheet->getColumnDimension($alphabet[$key])->setAutoSize(true);
            }
            $sheet->setAutoFilter('A1:' . $alphabet[count($header) - 1] . 1);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $this->getFileName() . '"');
            header('Cache-Control: max-age=0');
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        } catch (Exception $exception) {
            die($exception->getMessage());
        }
    }

    public function getFileName(){
        $cache = Cache::createInstance();
        if ($cache->initCache(7200, "exhib" . $this->arParams['EXHIBITION_ID'])) {
            $exib = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $exib = CIBlockElement::GetByID($this->arParams['EXHIBITION_ID'])->Fetch();
            $cache->endDataCache($exib);
        }
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
        }else{
            $result .= '.xlsx';
        }
        return $result;
    }
}
