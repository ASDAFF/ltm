<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Helpers\User;
use Spectr\Meeting\Models\TimeslotTable;
use Spectr\Meeting\Models\RequestTable;
use Spectr\Meeting\Models\RegistrGuestTable;

CBitrixComponent::includeComponentClass('ds:meetings.request');

class MeetingsMatrix extends MeetingsRequest
{
    private $paginationId = 'matrix';

    public function onPrepareComponentParams($arParams): array
    {
        return [
            'EXHIBITION_CODE'      => (string)$arParams['EXHIBITION_CODE'],
            'EXHIBITION_IBLOCK_ID' => (int)$arParams['EXHIBITION_IBLOCK_ID'],
            'USER_TYPE'            => (string)$arParams['USER_TYPE'],
            'IS_HB'                => isset($arParams['IS_HB']) && $arParams['IS_HB'] === 'Y' ? true : false,
            'USERS_COUNT_PER_PAGE' => (int)$arParams['USERS_COUNT_PER_PAGE'],
        ];
    }

    /**
     * @throws Exception
     */
    private function checkParams()
    {
        if (empty($this->arParams['USER_TYPE'])) {
            throw new Exception(Loc::getMessage('ERROR_EMPTY_USER_TYPE'));
        }

        return $this;
    }

    private function setLinks()
    {
        if ($this->arParams['IS_HB']) {
            $this->arResult['SEND_REQUEST_LINK']    = '/admin/service/appointment_hb.php';
            $this->arResult['CONFIRM_REQUEST_LINK'] = '/admin/service/appointment_hb_confirm.php';
            $this->arResult['REJECT_REQUEST_LINK']  = '/admin/service/appointment_hb_del.php';
        } else {
            $this->arResult['SEND_REQUEST_LINK']    = '/admin/service/appointment.php';
            $this->arResult['CONFIRM_REQUEST_LINK'] = '/admin/service/appointment_confirm.php';
            $this->arResult['REJECT_REQUEST_LINK']  = '/admin/service/appointment_del.php';
        }
        $this->arResult['RESERVE_REQUEST_LINK'] = '/admin/service/appointment_reserve.php';

        return $this;
    }

    protected function getUserType()
    {
        $userTypeName                         = $this->arParams['USER_TYPE'];
        $searchedUserType                     = array_filter(User::$userTypes, function ($item) use ($userTypeName) {
            return $item === $userTypeName;
        });
        $this->arResult['USER_TYPE']          = key($searchedUserType);
        $this->arResult['USER_TYPE_NAME']     = $userTypeName;
        $this->arResult['OLD_USER_TYPE_NAME'] = $this->arResult['USER_TYPE'] === User::PARTICIPANT_TYPE
            ? $this->templateNameForParticipant
            : $userTypeName;

        return $this;
    }

    private function setIsHB()
    {
        $this->arResult['IS_HB'] = $this->arParams['IS_HB'] ? 'Y' : 'N';

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getTimeslots()
    {
        $this->arResult['TIMESLOTS'] = TimeslotTable::getList([
            'filter' => [
                'EXHIBITION_ID' => $this->arResult['APP_ID'],
                'SLOT_TYPE'     => TimeslotTable::$types[TimeslotTable::TYPE_MEET],
            ],
        ])->fetchAll();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getRequests()
    {
        $this->arResult['REQUESTS'] = RequestTable::getList([
            'filter' => [
                '=EXHIBITION_ID' => $this->arResult['APP_ID'],
                '!=STATUS'       => array_map(function ($status) {
                    return RequestTable::$statuses[$status];
                }, RequestTable::$freeStatuses),
            ],
        ])->fetchAll();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getTimeslotsWithFreeCompanies()
    {
        $this->arResult['TIMESLOTS_WITH_FREE_COMPANIES'] = [];
        $isGuest                                         = $this->arResult['USER_TYPE'] !== User::PARTICIPANT_TYPE;
        $oppositeUsers                                   = $isGuest ? $this->arResult['PARTICIPANTS'] : $this->arResult['GUESTS'];

        foreach ($this->arResult['TIMESLOTS'] as $timeslot) {
            $timeslotRequests = array_filter($this->arResult['REQUESTS'],
                function ($item) use ($timeslot) {
                    return (int)$item['TIMESLOT'] === (int)$timeslot['ID'];
                });
            $deniedReceivers  = array_map(function ($item) {
                return $item['RECEIVER_ID'];
            }, $timeslotRequests);
            $deniedSenders    = array_map(function ($item) {
                return $item['SENDER_ID'];
            }, $timeslotRequests);
            $deniedCompanies  = array_unique(array_merge($deniedReceivers, $deniedSenders));
            $freeCompanies    = array_filter($oppositeUsers, function ($item) use ($deniedCompanies) {
                return !in_array($item, $deniedCompanies);
            });

            $this->arResult['TIMESLOTS_WITH_FREE_COMPANIES'][$timeslot['ID']] = [
                'ID'        => $timeslot['ID'],
                'NAME'      => $timeslot['NAME'],
                'COMPANIES' => array_map(function ($item) {
                    return [
                        'ID'   => $item['ID'],
                        'NAME' => $item['COMPANY'],
                    ];
                }, $freeCompanies),
            ];
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getUsers()
    {
        $this->arResult['GUESTS']       = $this->getGuests($this->arParams['IS_HB']);
        $this->arResult['PARTICIPANTS'] = $this->getParticipants();
        $this->arResult['USERS']        = [];

        array_walk($this->arResult['GUESTS'], array('self', 'saveToUsersList'));
        array_walk($this->arResult['PARTICIPANTS'], array('self', 'saveToUsersList'));

        return $this;
    }

    /**
     * @param bool $isHb
     *
     * @throws Exception
     * @return array
     */
    private function getGuests($isHb = false)
    {
        $onlyHb      = false;
        $onlyMorning = true;
        if ($isHb) {
            $onlyHb      = true;
            $onlyMorning = false;
        }
        $this->arResult['GUESTS_ID'] = CGroup::GetGroupUser($this->arResult['APP_SETTINGS']['GUESTS_GROUP']);

        return $this->user->getUsersInfo($this->arResult['GUESTS_ID'], false, $onlyHb, $onlyMorning);
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getParticipants()
    {
        $this->arResult['PARTICIPANTS_ID'] = CGroup::GetGroupUser($this->arResult['APP_SETTINGS']['MEMBERS_GROUP']);

        return $this->user->getUsersInfo($this->arResult['PARTICIPANTS_ID'], true);
    }

    private function saveToUsersList($user)
    {
        $this->arResult['USERS'][$user['ID']] = $user;
    }

    /**
     * @throws Exception
     */
    private function getMatrix()
    {
        global $APPLICATION;
        $isGuest     = $this->arResult['USER_TYPE'] !== User::PARTICIPANT_TYPE;
        $navNum      = $GLOBALS['NavNum'] ?: 1;
        $nav         = new \Bitrix\Main\UI\PageNavigation($this->paginationId);
        $currentPage = $_REQUEST["PAGEN_{$navNum}"] ?: 1;
        $showAll     = (int)$_REQUEST["SHOWALL_{$navNum}"];
        $nav->allowAllRecords(true)
            ->setPageSize($this->arParams['USERS_COUNT_PER_PAGE'])
            ->setCurrentPage($currentPage);
        // hack for page with all elements. See $requiredParams in getBaseLink
        if ($showAll > 0) {
            $nav->initFromUri();
        }
        if ($isGuest) {
            $filter = ['UF_USER_ID' => $this->arResult['GUESTS_ID']];
            if ($this->arParams['IS_HB']) {
                $filter['UF_HB'] = 1;
            } else {
                $filter['UF_MORNING'] = 1;
            }
            $rsUsers = RegistrGuestTable::getList([
                'select'      => ['*', 'UF_HB' => 'USER.UF_HB'],
                'filter'      => $filter,
                'order'       => ['UF_COMPANY' => 'ASC'],
                "count_total" => true,
                "offset"      => $nav->getOffset(),
                "limit"       => $nav->getLimit(),
            ]);
        } else {
            $rsUsers = \Bitrix\Main\UserTable::getList([
                'select'      => ['ID', 'WORK_COMPANY'],
                'filter'      => ['ID' => $this->arResult['PARTICIPANTS_ID']],
                'order'       => ['WORK_COMPANY' => 'ASC'],
                'count_total' => true,
                'offset'      => $nav->getOffset(),
                'limit'       => $nav->getLimit(),
            ]);
        }
        $nav->setRecordCount($rsUsers->getCount());
        $dbResult                 = new CDBResult();
        $dbResult->NavPageCount   = $nav->getPageCount();
        $dbResult->NavPageNomer   = $nav->getCurrentPage();
        $dbResult->NavNum         = $navNum;
        $dbResult->NavPageSize    = $nav->getPageSize();
        $dbResult->NavRecordCount = $nav->getRecordCount();
        $dbResult->bShowAll       = true;
        if ($showAll) {
            $dbResult->NavShowAll = true;
        }

        ob_start();
        $APPLICATION->IncludeComponent('bitrix:system.pagenavigation', '', [
            'NAV_RESULT'  => $dbResult,
            'SHOW_ALWAYS' => 'Y',
            'NAV_TITLE'   => Loc::getMessage('USERS'),
            'BASE_LINK'   => $this->getBaseLink($navNum),
        ]);

        $this->arResult['PAGINATION'] = @ob_get_clean();

        while ($user = $rsUsers->fetch()) {
            $userId = $isGuest ? $user['UF_USER_ID'] : $user['ID'];
            $company = [
                'ID'       => $userId,
                'NAME'     => $this->arResult['USERS'][$userId]['COMPANY'],
                'REP'      => $this->arResult['USERS'][$userId]['NAME'],
                'SCHEDULE' => [],
            ];

            foreach ($this->arResult['TIMESLOTS'] as $timeslot) {
                $schedule   = [
                    'TIMESLOT_ID'   => $timeslot['ID'],
                    'TIMESLOT_NAME' => $timeslot['NAME'],
                ];
                $arRequests = array_filter($this->arResult['REQUESTS'], function ($item) use ($timeslot, $userId) {
                    return (int)$item['TIMESLOT_ID'] === (int)$timeslot['ID'] &&
                           ((int)$userId === (int)$item['RECEIVER_ID'] || (int)$userId === (int)$item['SENDER_ID']);
                });
                if (count($arRequests) > 0) {
                    $request                    = array_values($arRequests)[0];
                    $schedule['STATUS']         = $request['STATUS'];
                    $schedule['IS_BUSY']        = true;
                    $isSender                   = (int)$userId === (int)$request['SENDER_ID'];
                    $schedule['USER_IS_SENDER'] = $isSender;
                    $oppositeUserId             = $isSender ? $request['RECEIVER_ID'] : $request['SENDER_ID'];
                    $oppositeUserId             = (int)$oppositeUserId;
                    $schedule['COMPANY_ID']     = $oppositeUserId;
                    $schedule['COMPANY_NAME']   = $this->arResult['USERS'][$oppositeUserId]['COMPANY'];
                    $schedule['REP']            = $this->arResult['USERS'][$oppositeUserId]['NAME'];
                } else {
                    $schedule['STATUS']  = RequestTable::$freeStatusName;
                    $schedule['IS_BUSY'] = false;
                }
                $company['SCHEDULE'][$timeslot['ID']] = $schedule;
            }
            $this->arResult['MATRIX'][] = $company;
        }

        return $this;
    }

    private function getBaseLink($navNum)
    {
        $sUrlPath       = GetPagePath(false, false);
        $delParam       = array_merge(
            [
                "PAGEN_{$navNum}",
                "SIZEN_{$navNum}",
                "SHOWALL_{$navNum}",
                "PHPSESSID",
            ],
            \Bitrix\Main\HttpRequest::getSystemParameters()
        );
        $navQueryString = htmlspecialcharsbx(DeleteParam($delParam));
        $requiredParams = "{$this->paginationId}=page-all";

        return $sUrlPath.'?'.$requiredParams.'&'.($navQueryString <> '' ? $navQueryString.'&' : '');
    }

    public function executeComponent()
    {
        $this->onIncludeComponentLang();
        try {
            $this->checkModules()
                 ->init()
                 ->getApp()
                 ->checkParams()
                 ->getUserType()
                 ->setLinks()
                 ->setIsHB()
                 ->getTimeslots()
                 ->getUsers()
                 ->getRequests()
                 ->getTimeslotsWithFreeCompanies()
                 ->getMatrix()
                 ->includeComponentTemplate();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }
}