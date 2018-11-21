<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Spectr\Meeting\Models\WishlistTable;
use Bitrix\Main\Type\DateTime;

class MeetingsWishlistAdd extends CBitrixComponent
{

    public function onPrepareComponentParams($params = []): array
    {
        global $USER;

        return [
            'EXHIBITION_ID' => (int)$_REQUEST['app'],
            'SENDER_ID'     => (int)$USER->GetID(),
            'RECEIVER_ID'   => (int)$_REQUEST['to'],
        ];
    }

    /**
     * @throws \Bitrix\Main\LoaderException
     */
    private function loadModules()
    {
        Loader::includeModule('doka.meetings');

        return $this;
    }

    /**
     * @throws Exception
     */
    private function checkParams()
    {
        if ($this->arParams['EXHIBITION_ID'] <= 0) {
            throw new Exception('REQUIRED PARAMETER EXHIBITION_ID NOT SET');
        }
        if ($this->arParams['SENDER_ID'] <= 0) {
            throw new Exception('REQUIRED PARAMETER SENDER_ID NOT SET');
        }
        if ($this->arParams['RECEIVER_ID'] <= 0) {
            throw new Exception('REQUIRED PARAMETER RECEIVER_ID NOT SET');
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function addItemToWishlist()
    {
        $dateTime                 = new DateTime();
        $result                   = WishlistTable::add([
            'SENDER_ID'     => $this->arParams['SENDER_ID'],
            'RECEIVER_ID'   => $this->arParams['RECEIVER_ID'],
            'REASON'        => WishlistTable::REASON_SELECTED,
            'EXHIBITION_ID' => $this->arParams['EXHIBITION_ID'],
            'CREATED_AT'    => $dateTime,
        ]);
        $this->arResult['RESULT'] = $result;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function executeComponent()
    {
        $this->onIncludeComponentLang();
        try {
            $this->loadModules()
                 ->checkParams()
                 ->addItemToWishlist()
                 ->includeComponentTemplate();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }

}