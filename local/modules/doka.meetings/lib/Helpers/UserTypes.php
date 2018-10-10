<?php

namespace Spectr\Meeting\Helpers;

use Spectr\Meeting\Models\SettingsTable;

class UserTypes
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
}