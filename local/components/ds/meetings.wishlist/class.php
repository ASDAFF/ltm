<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Spectr\Meeting\Models\WishlistTable;

Loader::includeModule('doka.meetings');

class MeetingsWishlist extends CBitrixComponent
{
    private $componentTemplate = 'guest';

    public function onPrepareComponentParams(array $arParams): array
    {
        $result = $arParams;
        $result['EXHIBITION_ID'] = (int)$arParams['EXHIBITION_ID'];
        $result['USER_ID'] = (int)$arParams['USER_ID'];
        $result['ADD_LINK_TO_WISHLIST'] = $result['ADD_LINK_TO_WISHLIST'] ?: "cabinet/service/wish.php";
        return $result;
    }

    public function executeComponent()
    {
        $this->checkComponentTemplate();
        $this->arResult = [
            'WISHLIST_FOR_USER' => $this->getWithListForUser(),
            'WISHLIST_FROM_USER' => $this->getWithListFromUser(),
        ];
        $this->includeComponentTemplate();
    }

    public function checkComponentTemplate()
    {
        if ($this->arParams['USER_TYPE'] === 'PARTICIP') {
            $this->componentTemplate = 'particip';
        }
    }

    public function getWithListForUser(): array
    {
        $result = WishlistTable::getWishlistForUser($this->arParams['USER_ID'], $this->arParams['EXHIBITION_ID']);
        return $result;
    }

    public function getWithListFromUser(): array
    {
        $result = WishlistTable::getWishlistFromUser($this->arParams['USER_ID'], $this->arParams['EXHIBITION_ID']);
        return $result;
    }

}