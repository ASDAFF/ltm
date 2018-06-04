<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Loader;

Loader::includeModule('highloadblock');

class XlsxGenerator extends CBitrixComponent
{

    public function onPrepareComponentParams($arParams)
    {
        $result = $arParams;
        return $result;
    }

    public function executeComponent()
    {
        $parameters = [
            'filter' => [
                'UF_EXHIB_ID' => $this->arParams['EXHIBITION_ID']
            ],
        ];
        $result = $this->getGuestCompanyInfo($parameters);
        c($this->getFieldsData(15));
        $this->includeComponentTemplate();
    }

    private function getEntityClass($id)
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

    public function getGuestCompanyInfo($parameters)
    {
        $result = [];
        $entity = $this->getEntityClass($this->arParams['REGISTER_GUEST_ENTITY_ID']);
        if ($entity !== null) {
            try {
                $rsData = $entity::getList($parameters);
            } catch (Exception $exception) {
                return [];
            }
            while ($data = $rsData->fetch()) {
                $user = CUser::GetByID($data['UF_USER_ID'])->Fetch();
                $data['USER'] = $user;
                $result[] = $data;
            }
        }
        return $result;
    }

    public function getGuestPeopleInfo()
    {
        $entity = $this->getEntityClass($this->arParams['REGISTER_GUEST_ENTITY_ID']);
    }

    public function getFieldsData($hlblockId)
    {
        $result = [];
        if (intval($hlblockId) !== 0) {
            $userTypes = CUserTypeEntity::GetList(array(), array('ENTITY_ID' => 'HLBLOCK_' . $hlblockId, 'LANG' => LANGUAGE_ID));
            while ($data = $userTypes->Fetch()) {
                switch ($data['USER_TYPE_ID']) {
                    case 'hlblock':
                        
                        break;
                    case 'enumeration':
                        $enData = CUserFieldEnum::GetList(array('ID' => 'ASC'), array(
                            'USER_FIELD_ID' => $data['ID'],
                        ));
                        while ($enDataItem = $enData->Fetch()) {
                            $data['ITEMS'][$enDataItem['ID']] = $enDataItem;
                        }
                        $result[$data['FIELD_NAME']] = $data;
                        break;
                    default:
                        $result[$data['FIELD_NAME']] = $data;
                        break;
                }
            }
        }
        return $result;
    }
}
