<?php

namespace Spectr\Meeting\Helpers;

use Spectr\Meeting\Models\SettingsTable;
use Spectr\Meeting\Models\RegistrGuestTable;

class UserHelper
{
    const ADMIN_TYPE = 0;
    const GUEST_TYPE = 1;
    const PARTICIPANT_TYPE = 2;
    static $userTypes = [
        self::ADMIN_TYPE        => 'ADMIN',
        self::GUEST_TYPE        => 'GUEST',
        self:: PARTICIPANT_TYPE => 'PARTICIPANT',
    ];

    private $appSettings = [];

    /**
     * @param int $appId
     *
     * @throws \Exception
     */
    public function __construct($appId)
    {
        if ((int)$appId) {
            $this->appSettings = SettingsTable::getById($appId)->fetch();
        } else {
            throw new \Exception('App id is not set');
        }
    }

    /**
     * @param array $userGroups
     *
     * @return bool
     */
    public function isAdmin($userGroups = [])
    {
        global $USER;

        return in_array($this->appSettings['ADMINS_GROUP'], $userGroups) || $USER->IsAdmin();
    }

    /**
     * @param array $userGroups
     *
     * @return bool
     */
    public function isGuest($userGroups = [])
    {
        return in_array($this->appSettings['GUESTS_GROUP'], $userGroups);
    }

    /**
     * @param array $userGroups
     *
     * @return bool
     */
    public function isParticipant($userGroups = [])
    {
        return in_array($this->appSettings['MEMBERS_GROUP'], $userGroups);
    }

    /**
     * @param int $userId
     *
     * @return string
     **/
    public function getUserTypeById(int $userId)
    {
        global $USER;

        $arGroups = \CUser::GetUserGroup($userId);
        if ($this->isAdmin($arGroups) || $USER->IsAdmin()) {
            $userType = self::ADMIN_TYPE;
        } elseif ($this->isGuest($arGroups)) {
            $userType = self::GUEST_TYPE;
        } else {
            $userType = self::PARTICIPANT_TYPE;
        }

        return $userType;
    }

    public function getUserType()
    {
        global $USER;

        $arGroups = $USER->GetUserGroupArray();
        if (in_array($this->appSettings['ADMINS_GROUP'], $arGroups) || $USER->IsAdmin()) {
            $userType = self::ADMIN_TYPE;
        } elseif (in_array($this->appSettings['GUESTS_GROUP'], $arGroups)) {
            $userType = self::GUEST_TYPE;
        } else {
            $userType = self::PARTICIPANT_TYPE;
        }

        return $userType;
    }

    /**
     * @param int $userId
     * @param bool $isParticipant
     *
     * @throws \Bitrix\Main\ArgumentException
     * @return array
     */
    public function getUserInfo($userId, $isParticipant = false)
    {
        if ($isParticipant) {
            $arUser = \Bitrix\Main\UserTable::getList([
                'select' => ['ID', 'EMAIL', 'WORK_COMPANY', 'NAME', 'LAST_NAME', 'UF_ID_COMP', 'UF_HB'],
                'filter' => ['=ID' => $userId],
            ])->fetchAll();
            if ( !empty($arUser)) {
                return [
                    'ID'       => $userId,
                    'NAME'     => "{$arUser[0]['NAME']} {$arUser[0]['LAST_NAME']}",
                    'COMPANY'  => $arUser[0]['WORK_COMPANY'],
                    'EMAIL'    => $arUser[0]['EMAIL'],
                    'FORM_RES' => $arUser[0]['UF_ID_COMP'],
                    'IS_HB'    => $arUser[0]['UF_HB'],
                ];
            }
        } else {
            $arUser = RegistrGuestTable::getRowByUserID($userId);
            if ( !empty($arUser)) {
                return [
                    'ID'          => $userId,
                    'NAME'        => "{$arUser['UF_NAME']} {$arUser['UF_SURNAME']}",
                    'COMPANY'     => $arUser['UF_COMPANY'],
                    'EMAIL'       => $arUser['UF_EMAIL'],
                    'CITY'        => $arUser['UF_CITY'],
                    'HALL'        => $arUser['UF_HALL'],
                    'TABLE'       => $arUser['UF_TABLE'],
                    'MOB'         => $arUser['UF_MOBILE'],
                    'PHONE'       => $arUser['UF_PHONE'],
                    'COLLEAGUES'  => $arUser['UF_COLLEAGUES'],
                    'DESCRIPTION' => $arUser['UF_DESCRIPTION'],
                    'IS_HB'       => $arUser['UF_HB'],
                ];
            }
        }

        return [];
    }

    /**
     * @param array $arUserId
     * @param bool $isParticipant
     *
     * @throws \Bitrix\Main\ArgumentException
     * @return array
     */
    public function getUsersInfo($arUserId, $isParticipant = false)
    {
        if ($isParticipant) {
            $arUsers = \Bitrix\Main\UserTable::getList([
                'select' => ['ID', 'EMAIL', 'WORK_COMPANY', 'NAME', 'LAST_NAME', 'UF_ID_COMP', 'UF_HB'],
                'filter' => ['ID' => $arUserId],
                'order'  => ['WORK_COMPANY' => 'ASC'],
            ])->fetchAll();
            if ( !empty($arUsers)) {
                return array_map(function ($user) {
                    return [
                        'ID'       => $user['ID'],
                        'NAME'     => "{$user['NAME']} {$user['LAST_NAME']}",
                        'COMPANY'  => $user['WORK_COMPANY'],
                        'EMAIL'    => $user['EMAIL'],
                        'FORM_RES' => $user['UF_ID_COMP'],
                        'IS_HB'    => $user['UF_HB'],
                    ];
                }, $arUsers);
            }
        } else {
            $arUsers = RegistrGuestTable::getList([
                'select' => ['*', 'UF_HB' => 'USER.UF_HB'],
                'filter' => ['UF_USER_ID' => $arUserId],
                'order'  => ['UF_COMPANY' => 'ASC'],
            ])->fetchAll();
            if ( !empty($arUsers)) {
                return array_map(function ($user) {
                    return [
                        'ID'          => $user['UF_USER_ID'],
                        'NAME'        => "{$user['UF_NAME']} {$user['UF_SURNAME']}",
                        'COMPANY'     => $user['UF_COMPANY'],
                        'EMAIL'       => $user['UF_EMAIL'],
                        'CITY'        => $user['UF_CITY'],
                        'HALL'        => $user['UF_HALL'],
                        'TABLE'       => $user['UF_TABLE'],
                        'MOB'         => $user['UF_MOBILE'],
                        'PHONE'       => $user['UF_PHONE'],
                        'DESCRIPTION' => $user['UF_DESCRIPTION'],
                        'COLLEAGUES'  => $user['UF_COLLEAGUES'],
                    ];
                }, $arUsers);
            }
        }

        return [];
    }
}