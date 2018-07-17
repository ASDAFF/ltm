<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

Loader::includeModule('doka.meetings');

class MeetingsWishlist extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $result = $arParams;

        return $result;
    }

    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }
}