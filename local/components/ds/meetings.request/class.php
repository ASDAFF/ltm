<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Models\SettingsTable;
use Spectr\Meeting\Models\RegistrGuestTable;
use Spectr\Meeting\Models\TimeslotTable;

class MeetingsRequest extends CBitrixComponent
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
    protected function checkModules()
    {
        if ( !Loader::includeModule('doka.meetings') || !Loader::includeModule('iblock')) {
            throw new Exception(self::DEFAULT_ERROR);
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    protected function checkAuth()
    {
        global $USER;
        if ( !$USER->IsAuthorized()) {
            throw new Exception(Loc::getMessage('ERROR_USER_IS_NOT_AUTHORIZED'));
        }

        return $this;
    }

    /** @throws Exception */
    protected function getAppId()
    {
        if (
            $this->arParams['EXHIBITION_IBLOCK_ID'] > 0 &&
            ($this->arParams['APP_ID'] > 0 || (isset($_REQUEST['exib_code']) && (string)$_REQUEST['exib_code'] !== ''))
        ) {
            $arFilter = array("IBLOCK_ID" => $this->arParams['EXHIBITION_IBLOCK_ID']);
            if (isset($_REQUEST['exib_code']) && (string)$_REQUEST['exib_code'] !== '') {
                $arFilter['CODE'] = $_REQUEST['exib_code'];
            }
            if ($this->arParams['APP_ID'] > 0) {
                if ($this->arParams['IS_HB']) {
                    $arFilter['PROPERTY_APP_HB_ID'] = $this->arParams['APP_ID'];
                } else {
                    $arFilter['PROPERTY_APP_ID'] = $this->arParams['APP_ID'];
                }
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

    protected function getAppSettings()
    {
        $this->arResult['APP_SETTINGS'] = SettingsTable::getById($this->arResult['APP_ID'])->fetch();

        return $this;
    }

    protected function getUserType()
    {
        global $USER;

        if (isset($_REQUEST['type']) && $USER->GetID() == 1) {
            if ($_REQUEST['type'] === 'p') {
                $userType = self::PARTICIPANT_TYPE;
            } else {
                $userType = self::GUEST_TYPE;
            }
        } else {
            $arGroups = $USER->GetUserGroupArray();
            if ($this->isAdmin($arGroups)) {
                $userType = self::ADMIN_TYPE;
            } elseif ($this->isGuest($arGroups)) {
                $userType = self::GUEST_TYPE;
            } else {
                $userType = self::PARTICIPANT_TYPE;
            }
        }

        $this->arResult['USER_TYPE']      = $userType;
        $this->arResult['USER_TYPE_NAME'] = self::$userTypes[$userType];

        return $this;
    }

    /**
     * @param array $userGroups
     *
     * @return bool
     */
    protected function isAdmin($userGroups = [])
    {
        global $USER;

        return in_array($this->arResult['APP_SETTINGS']['ADMINS_GROUP'], $userGroups) || $USER->IsAdmin();
    }

    /**
     * @param array $userGroups
     *
     * @return bool
     */
    protected function isGuest($userGroups = [])
    {
        return in_array($this->arResult['APP_SETTINGS']['GUESTS_GROUP'], $userGroups);
    }

    /**
     * @param array $userGroups
     *
     * @return bool
     */
    protected function isParticipant($userGroups = [])
    {
        return in_array($this->arResult['APP_SETTINGS']['MEMBERS_GROUP'], $userGroups);
    }

    /**
     * @throws Exception
     */
    protected function checkRestRequestParams()
    {
        if ((int)$_REQUEST['time'] <= 0) {
            throw new Exception(Loc::getMessage("ERROR_EMPTY_TIMESLOT_ID"));
        }

        if ((int)$_REQUEST['to'] <= 0) {
            if ($this->arResult['USER_TYPE'] === self::PARTICIPANT_TYPE) {
                throw new Exception(Loc::getMessage('ERROR_WRONG_RECEIVER_PARTICIPANT_ID'));
            } else {
                throw new Exception(Loc::getMessage('ERROR_WRONG_RECEIVER_ID'));
            }
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    protected function prepareFields()
    {
        global $USER;
        if (isset($_REQUEST['id']) && $this->arResult['USER_TYPE'] === self::ADMIN_TYPE) {
            $this->arResult['SENDER_ID'] = (int)$_REQUEST['id'];
        } else {
            $this->arResult['SENDER_ID'] = $USER->GetID();
        }
        $this->arResult['RECEIVER_ID'] = (int)$_REQUEST['to'];
        $this->arResult['SENDER']      = $this->getUserInfo(
            $this->arResult['SENDER_ID'],
            $this->arResult['USER_TYPE'] === self::PARTICIPANT_TYPE
        );
        $this->arResult['RECEIVER']    = $this->getUserInfo(
            $this->arResult['RECEIVER_ID'],
            $this->arResult['USER_TYPE'] !== self::PARTICIPANT_TYPE
        );

        if (empty($this->arResult['SENDER'])) {
            throw new Exception(Loc::getMessage(self::$userTypes[$this->arResult['USER_TYPE']].'_WRONG_SENDER_ID'));
        }
        if (empty($this->arResult['RECEIVER'])) {
            throw new Exception(Loc::getMessage(self::$userTypes[$this->arResult['USER_TYPE']].'_WRONG_RECEIVER_ID'));
        }
        $this->arResult['TIMESLOT'] = $this->getTimeslot();
        if ( !$this->arResult['TIMESLOT']) {
            throw new Exception(Loc::getMessage(self::$userTypes[$this->arResult['USER_TYPE']].'_WRONG_TIMESLOT_ID'));
        }

        return $this;
    }

    /**
     * @param int $userId
     * @param bool $isParticipant
     *
     * @throws \Bitrix\Main\ArgumentException
     * @return array
     */
    protected function getUserInfo($userId, $isParticipant = false)
    {
        if ($isParticipant) {
            $arUser = \Bitrix\Main\UserTable::getList([
                'select' => ['ID', 'EMAIL', 'WORK_COMPANY', 'NAME', 'LAST_NAME'],
                'filter' => ['=ID' => $userId],
            ])->fetchAll();
            if ( !empty($arUser)) {
                return [
                    'ID'      => $userId,
                    'NAME'    => "{$arUser[0]['NAME']} {$arUser[0]['LAST_NAME']}",
                    'COMPANY' => $arUser[0]['WORK_COMPANY'],
                    'EMAIL'   => $arUser[0]['EMAIL'],
                ];
            }
        } else {
            $arUser = RegistrGuestTable::getRowByUserID($userId);
            if ( !empty($arUser)) {
                return [
                    'ID'      => $userId,
                    'NAME'    => "{$arUser['UF_NAME']} {$arUser['UF_SURNAME']}",
                    'COMPANY' => $arUser['UF_COMPANY'],
                    'EMAIL'   => $arUser['UF_EMAIL'],
                ];
            }
        }

        return [];
    }

    protected function getTimeslot()
    {
        return TimeslotTable::getTimeslotForMeet((int)$_REQUEST['time']);
    }

    public function executeComponent()
    {
        $this->onIncludeComponentLang();
    }
}