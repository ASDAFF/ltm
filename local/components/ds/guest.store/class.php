<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Loader;

try {
    Loader::includeModule('highloadblock');
    Loader::includeModule('iblock');
} catch (Exception $exception) {
    die($exception->getMessage());
}


class GuestStore extends CBitrixComponent
{

    public function onPrepareComponentParams($arParams)
    {
        $result = $arParams;
        $result['USER_ID'] = intval($arParams['USER_ID']) ?: intval($this->request->get('user_id'));
        return $result;
    }

    public function executeComponent()
    {
        $userData = $this->putGuestInStorage($this->arParams['USER_ID']);

//        c($userData);
    }

    public function getEntityClass($id)
    {
        $id = intval($id);
        if ($id !== 0) {
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

    /**
     * @param $user_id
     * @return array|null
     */
    public function getGuestData($user_id)
    {
        $entity = $this->getEntityClass($this->arParams['REGISTER_GUEST_HLBLOCK_ID']);
        $parameters = [
            'filter' => [
                'UF_USER_ID' => $user_id
            ]
        ];
        try {
            $rsData = $entity::getList($parameters);
            if ($data = $rsData->fetch()) {
                return $data;
            }
        } catch (Exception $exception) {
            return null;
        }
        return null;
    }

    public function getColleaguesData($colleagues_ids){
        $entity = $this->getEntityClass($this->arParams['REGISTER_COLLEAGUES_HLBLOCK_ID']);
        $parameters = [
            'filter' => [
                'ID' => $colleagues_ids
            ]
        ];
        try{
            $rsData = $entity::getList($parameters);
            $result = [];
            while ($data = $rsData->fetch()){
                $result[$data['ID']] = $data;
            }
            return $result ?: null;
        }catch (Exception $exception){
            return null;
        }
        return null;
    }

    public function transferColleagues($colleagues_ids){
        $colleagues = $this->getColleaguesData($colleagues_ids);
        c($colleagues);
    }

    public function putGuestInStorage($user_id)
    {
        $guestData = $this->getGuestData($user_id);
        if(!is_null($guestData) && is_array($guestData)){
            if($guestData['UF_COLLEAGUES']){
                $newColleaguesIds = $this->transferColleagues($guestData['UF_COLLEAGUES']);
            }
        }
    }
}
