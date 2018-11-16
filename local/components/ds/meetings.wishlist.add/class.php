<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Spectr\Meeting\Models\WishlistTable;

Loader::includeModule('doka.meetings');

class MeetingsWishlistAdd extends CBitrixComponent
{
    private $componentTemplate = 'guest';

    public function onPrepareComponentParams(array $arParams): array
    {
        $result = $arParams;
        $result['EXHIBITION_ID'] = (int)$arParams['EXHIBITION_ID'];
        $result['SENDER_ID'] = (int)$arParams['SENDER_ID'];
        $result['RECEIVER_ID'] = (int)$arParams['RECEIVER_ID'];
        return $result;
    }

    public function executeComponent()
    {
        $result = WishlistTable::add([
            "SENDER_ID" => $this->arParams["SENDER_ID"],
            "RECEIVER_ID" => $this->arParams["RECEIVER_ID"],
            "REASON" => WishlistTable::REASON_SELECTED,
        ]);
        $this->arResult["RESULT"] = $result;
        $this->includeComponentTemplate();
    }

}