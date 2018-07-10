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
        $result['USER_ID'] = intval($arParams['USER_ID']) ?: intval($this->request->get('USER_ID'));
        print_r($result);
        return $result;
    }

    public function executeComponent()
    {

    }
}
