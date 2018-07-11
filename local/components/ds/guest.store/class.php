<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Spectr\Models\RegistrGuestTable;

Loader::includeModule('highloadblock');

class GuestStore extends CBitrixComponent
{
    const MOVE_TO_STORAGE = 1;
    const MOVE_FROM_STORAGE = 2;

    public function onPrepareComponentParams($arParams)
    {
        $result = $arParams;
        $result['USER_ID'] = intval($arParams['USER_ID']) ?: intval($this->request->get('USER_ID'));
        $result['USER_IDS'] = $this->request->get('USERS_LIST') ?: [$result['USER_ID']];
        return $result;
    }

    public function executeComponent()
    {
        switch ($this->arParams['MOVE_TO']) {
            case self::MOVE_TO_STORAGE:
                RegistrGuestTable::moveToStorage($this->arParams['USER_IDS']);
                break;
            case self::MOVE_FROM_STORAGE:
                break;
        }
    }
}
