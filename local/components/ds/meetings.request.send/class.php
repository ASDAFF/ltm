<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Models\RequestTable;

class MeetingsRequestSend extends CBitrixComponent
{
    const DEFAULT_ERROR = '404 Not Found';
    const ADMIN_TYPE = 0;
    const GUEST_TYPE = 1;
    const PARTICIPANT_TYPE = 2;
    static $userTypes = [
        self::ADMIN_TYPE        => 'ADMIN',
        self::GUEST_TYPE        => 'GUEST',
        self:: PARTICIPANT_TYPE => 'PARTICIPANT',
    ];

    public function onPrepareComponentParams($arParams): array
    {
        return [
            'APP_ID'               => (int)$arParams['APP_ID'],
            'EXHIBITION_IBLOCK_ID' => (int)$arParams['EXHIBITION_IBLOCK_ID'],
            'IS_HB'                => isset($arParams['IS_HB']) && (bool)$arParams['IS_HB'] ? true : false,
        ];
    }

    /**
     * @throws Exception
     **/
    private function checkModules()
    {
        if ( !Loader::includeModule('doka.meetings') || !Loader::includeModule('iblock')) {
            throw new Exception(self::DEFAULT_ERROR);
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function checkAuth()
    {
        global $USER;
        if ( !$USER->IsAuthorized()) {
            throw new Exception(Loc::getMessage('ERROR_EMPTY_USER_ID'));
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function checkRequestParams()
    {
        if ((int)$_REQUEST['time'] <= 0) {
            throw new Exception(Loc::getMessage("ERROR_EMPTY_TIMESLOT_ID"));
        }

        if ((int)$_REQUEST['to'] <= 0) {
            if ($this->getUserType() === self::PARTICIPANT_TYPE) {
                throw new Exception(Loc::getMessage('ERROR_WRONG_RECEIVER_PARTICIP_ID'));
            } else {
                throw new Exception(Loc::getMessage('ERROR_WRONG_RECEIVER_ID'));
            }
        }

        return $this;
    }

    /** TODO need to implement */
    private function getUserType()
    {
        return self::GUEST_TYPE;
    }

    /**
     * TODO need to implement
     * @throws Exception
     */
    private function prepareFields()
    {
        $this->getAppIdFromIblock();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getAppIdFromIblock()
    {
        if ($this->arParams['APP_ID'] > 0 && $this->arParams['EXHIBITION_IBLOCK_ID'] > 0) {
            $arFilter = array("IBLOCK_ID" => $this->arParams['EXHIBITION_IBLOCK_ID']);
            if (isset($_REQUEST['exib_code']) && $_REQUEST['exib_code'] !== '') {
                $arFilter['CODE'] = $_REQUEST['exib_code'];
            }
            if ($this->arParams['IS_HB']) {
                $arFilter['PROPERTY_APP_HB_ID'] = $this->arParams['APP_ID'];
            } else {
                $arFilter['PROPERTY_APP_ID'] = $this->arParams['APP_ID'];
            }
            $rsExhib = CIBlockElement::GetList(
                ['SORT' => 'ASC'],
                $arFilter,
                false,
                false,
                ['ID', 'CODE', 'NAME', 'IBLOCK_ID', 'PROPERTY_*',]
            );
            while ($oExhib = $rsExhib->GetNextElement(true, false)) {
                $this->arResult['PARAM_EXHIBITION']               = $oExhib->GetFields();
                $this->arResult['PARAM_EXHIBITION']['PROPERTIES'] = $oExhib->GetProperties();
                if ($this->arParams['IS_HB']) {
                    $this->arResult['APP_ID']       = $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['APP_HB_ID']['VALUE'];
                    $this->arResult['APP_ID_OTHER'] = $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['APP_ID']['VALUE'];
                } else {
                    $this->arResult['APP_ID']       = $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['APP_ID']['VALUE'];
                    $this->arResult['APP_ID_OTHER'] = $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['APP_HB_ID']['VALUE'];
                }
                if ((int)$this->arResult['APP_ID'] <= 0) {
                    throw new Exception(self::DEFAULT_ERROR);
                }
            }
        } else {
            throw new Exception(self::DEFAULT_ERROR);
        }

        return $this;
    }

    /**
     * TODO need to implement
     */
    private function checkCreatingRequestPossibility()
    {
        return $this;
    }

    /**
     * TODO need to implement
     */
    private function addRequest()
    {
        return $this;
    }

    /**
     * TODO need to implement
     */
    private function sendEmail()
    {
        return $this;
    }

    public function executeComponent()
    {
        try {
            $this->checkModules()
                 ->checkAuth()
                 ->checkRequestParams()
                 ->prepareFields()
                 ->checkCreatingRequestPossibility()
                 ->addRequest()
                 ->sendEmail()
                 ->includeComponentTemplate();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }

}