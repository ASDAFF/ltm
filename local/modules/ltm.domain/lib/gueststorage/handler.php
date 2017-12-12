<?php
namespace Ltm\Domain\GuestStorage;

class Handler
{
    public function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] === 25) {

        }
    }

    // создаем обработчик события "OnAfterIBlockElementDelete"
    public function OnAfterIBlockElementDeleteHandler($arFields)
    {
        if ($arFields['IBLOCK_ID'] === 25) {

        }
    }

    // создаем обработчик события "OnAfterIBlockElementAdd"
    public function OnAfterIBlockElementAddHandler(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] === 25) {

        }
    }

    public function OnBeforeIBlockElementAddHandler(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] === 25) {
        }
    }

    public function OnBeforeIBlockElementDeleteHandler($ID)
    {
    }

    public function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] === 25) {
        }
    }
}