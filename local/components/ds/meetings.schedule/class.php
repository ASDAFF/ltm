<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Models\SettingsTable;
use Spectr\Meeting\Helpers\UserTypes;

class MeetingsSchedule extends CBitrixComponent
{
    const DEFAULT_ERROR = '404 Not Found';
    /** @var UserTypes */
    private $userTypes;

    public function onPrepareComponentParams($arParams): array
    {
        return [
            'CACHE_TYPE'           => 'A',
            'CACHE_TIME'           => 3600,
            'APP_ID'               => (int)$_REQUEST['app'],
            'USER_TYPE'            => isset($arParams['USER_TYPE']) ? (string)$arParams['USER_TYPE'] : '',
            'USER_ID'              => (int)$_REQUEST['id'],
            'EXHIBITION_CODE'      => (string)$_REQUEST['exhib'],
            'MESSAGE_LINK'         => (string)$arParams['MESSAGE_LINK'],
            'SEND_REQUEST_LINK'    => (string)$arParams['SEND_REQUEST_LINK'],
            'CONFIRM_REQUEST_LINK' => (string)$arParams['CONFIRM_REQUEST_LINK'],
            'REJECT_REQUEST_LINK'  => (string)$arParams['REJECT_REQUEST_LINK'],
        ];
    }

    /**
     * @throws Exception
     **/
    private function checkModules()
    {
        if (
            !Loader::includeModule('doka.meetings') ||
            !Loader::includeModule('iblock') ||
            !Loader::includeModule('form')
        ) {
            throw new Exception(self::DEFAULT_ERROR);
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getApp()
    {
        if ($this->arParams['EXHIBITION_CODE']) {
            $arFilter                           = [
                'IBLOCK_ID' => $this->arParams['EXHIBITION_IBLOCK_ID'],
                'CODE'      => $this->arParams['EXHIBITION_CODE'],
            ];
            $exhibition                         = SettingsTable::getExhibition($arFilter);
            $this->arResult['PARAM_EXHIBITION'] = $exhibition['PARAM_EXHIBITION'];
            if ($this->arParams['IS_HB']) {
                $this->arResult['APP_ID'] = $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['APP_HB_ID']['VALUE'];
            } else {
                $this->arResult['APP_ID'] = $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['APP_ID']['VALUE'];
            }
        }
        if ((int)$this->arResult['APP_ID'] <= 0) {
            throw new Exception(self::DEFAULT_ERROR);
        } else {
            $this->userTypes = new UserTypes($this->arResult['APP_ID']);
        }

        return $this;
    }

    private function getAppSettings()
    {
        $this->arResult['APP_SETTINGS'] = SettingsTable::getById($this->arResult['APP_ID'])->fetch();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getUserId()
    {
        global $USER;
        if ( !$this->arParams['USER_ID']) {
            $this->arParams['USER_ID'] = $USER->GetID();
        }

        if ( !$USER->IsAuthorized() || $this->arParams['USER_ID'] <= 0) {
            throw new Exception(Loc::getMessage('ERROR_EMPTY_USER_ID'));
        }

        return $this;
    }

    private function getUserType()
    {
        global $USER;
        if ( !$this->arParams['USER_TYPE']) {
            if ($USER->IsAdmin()) {
                $this->arResult['USER_TYPE'] = $this->userTypes->getUserTypeById($_REQUEST['id']);
            }
            if ( !$this->arResult['USER_TYPE']) {
                $this->arResult['USER_TYPE'] = $this->userTypes->getUserType();
            }
        } else {
            $this->arResult['USER_TYPE'] = $this->arParams['USER_TYPE'];
        }
        $this->arResult['USER_TYPE_NAME'] = UserTypes::$userTypes[$this->arResult['USER_TYPE']];

        return $this;
    }


    public function executeComponent()
    {
        parent::executeComponent();
        $this->onIncludeComponentLang();
        try {
            $this->checkModules()
                 ->getApp()
                 ->getAppSettings()
                 ->getUserId()
                 ->getUserType()
                 ->includeComponentTemplate();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }
}