<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Models\SettingsTable;
use Spectr\Meeting\Helpers\UserHelper;
use Spectr\Meeting\Models\TimeslotTable;
use Spectr\Meeting\Models\RequestTable;

class MeetingsSchedule extends CBitrixComponent
{
    const DEFAULT_ERROR = '404 Not Found';
    /** @var UserHelper */
    private $userHelper;
    private $freeMeetType = 'free';
    private $tableSid = 'SIMPLE_QUESTION_148';
    private $hallSid = 'SIMPLE_QUESTION_732';

    public function onPrepareComponentParams($arParams): array
    {
        return [
            'CACHE_TYPE'           => 'A',
            'CACHE_TIME'           => 3600,
            'APP_ID'               => (int)$_REQUEST['app'],
            'USER_TYPE'            => isset($_REQUEST["type"]) && $_REQUEST["type"] !== ''
                ? $_REQUEST['type'] === 'p' ? UserHelper::PARTICIPANT_TYPE : UserHelper::GUEST_TYPE
                : UserHelper::GUEST_TYPE,
            'USER_ID'              => (int)$_REQUEST['id'],
            'EXHIBITION_IBLOCK_ID' => $arParams['EXHIBITION_IBLOCK_ID'],
            'EXHIBITION_CODE'      => trim($_REQUEST["EXHIBIT_CODE"]),
            'MESSAGE_LINK'         => (string)$arParams['MESSAGE_LINK'],
            'SEND_REQUEST_LINK'    => (string)$arParams['SEND_REQUEST_LINK'],
            'CONFIRM_REQUEST_LINK' => (string)$arParams['CONFIRM_REQUEST_LINK'],
            'REJECT_REQUEST_LINK'  => (string)$arParams['REJECT_REQUEST_LINK'],
            'IS_HB'                => isset($arParams['IS_HB']) && $arParams['IS_HB'] === 'Y' ? true : false,
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
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function createHelperInstance()
    {
        $this->userHelper = new UserHelper($this->arResult['APP_ID']);

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
            $this->arResult['USER_ID'] = $USER->GetID();
        } else {
            $this->arResult['USER_ID'] = $this->arParams['USER_ID'];
        }

        if ( !$USER->IsAuthorized() || $this->arResult['USER_ID'] <= 0) {
            throw new Exception(Loc::getMessage('ERROR_EMPTY_USER_ID'));
        }

        return $this;
    }

    private function getUserType()
    {
        global $USER;
        if ( !$this->arParams['USER_TYPE']) {
            if ($USER->IsAdmin()) {
                $this->arResult['USER_TYPE'] = $this->userHelper->getUserTypeById($_REQUEST['id']);
            }
            if ( !$this->arResult['USER_TYPE']) {
                $this->arResult['USER_TYPE'] = $this->userHelper->getUserType();
            }
        } else {
            $this->arResult['USER_TYPE'] = $this->arParams['USER_TYPE'];
        }
        $this->arResult['USER_TYPE_NAME'] = UserHelper::$userTypes[$this->arResult['USER_TYPE']];

        return $this;
    }

    private function getLinks()
    {
        $this->arResult['MESSAGE_LINK']         = "/cabinet".$this->arParams['MESSAGE_LINK'];
        $this->arResult['SEND_REQUEST_LINK']    = "/cabinet".$this->arParams['SEND_REQUEST_LINK'];
        $this->arResult['CONFIRM_REQUEST_LINK'] = "/cabinet".$this->arParams['CONFIRM_REQUEST_LINK'];
        $this->arResult['REJECT_REQUEST_LINK']  = "/cabinet".$this->arParams['REJECT_REQUEST_LINK'];
        $this->arResult['RESERVE_REQUEST_LINK'] = $this->arParams['RESERVE_REQUEST_LINK'];
        if ($this->arParams['IS_HB']) {
            $this->arResult['WISHLIST_LINK'] = '/cabinet/service/wishlist_hb';
            $this->arResult['SCHEDULE_LINK'] = '/cabinet/service/shedule_hb';
        } else {
            $this->arResult['WISHLIST_LINK'] = '/cabinet/service/wishlist';
            $this->arResult['SCHEDULE_LINK'] = '/cabinet/service/shedule';
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getTimeslots()
    {
        $this->arResult['TIMESLOTS'] = TimeslotTable::getList(
            ['filter' => ['EXHIBITION_ID' => $this->arResult['APP_ID']]]
        )->fetchAll();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getRequests()
    {
        $this->arResult['USER_REQUESTS']  = [];
        $this->arResult['OTHER_REQUESTS'] = [];

        $requests = RequestTable::getList([
            'filter' => [
                '=EXHIBITION_ID' => $this->arResult['APP_ID'],
                '!=STATUS'       => array_map(function ($status) {
                    return RequestTable::$statuses[$status];
                }, RequestTable::$freeStatuses),
            ],
        ]);

        while ($request = $requests->fetch()) {
            if ($request['SENDER_ID'] === $this->arResult['USER_ID'] || $request['RECEIVER_ID'] === $this->arResult['USER_ID']) {
                $this->arResult['USER_REQUESTS'][] = $request;
            } else {
                $this->arResult['OTHER_REQUESTS'][$request['TIMESLOT_ID']][] = $request;
            }
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getInfoAboutUsers()
    {
        $users = array_map(function ($request) {
            if ($request['SENDER_ID'] === $this->arResult['USER_ID']) {
                $user = $request['RECEIVER_ID'];
            } else {
                $user = $request['SENDER_ID'];
            }

            return ['USER_ID' => $user, 'USER_TYPE' => $this->userHelper->getUserTypeById($user)];
        }, $this->arResult['USER_REQUESTS']);

        $guests = array_filter($users, function ($userType) {
            return $userType['USER_TYPE'] === UserHelper::GUEST_TYPE;
        });

        $participants = array_filter($users, function ($userType) {
            return $userType['USER_TYPE'] === UserHelper::PARTICIPANT_TYPE;
        });

        $this->arResult['GUESTS'] = $this->userHelper->getUsersInfo(array_map(function ($user) {
            return $user['USER_ID'];
        }, $guests));

        $this->arResult['PARTICIPANTS'] = $this->userHelper->getUsersInfo(array_map(function ($user) {
            return $user['USER_ID'];
        }, $participants), true);

        $this->arResult['USERS'] = [];

        array_walk($this->arResult['GUESTS'], function ($value) {
            $this->arResult['USERS'][$value['ID']] = $value;
        });
        array_walk($this->arResult['PARTICIPANTS'], function ($value) {
            $this->arResult['USERS'][$value['ID']] = $value;
        });

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getInfoFromWebForm()
    {
        $formId    = $this->arResult['APP_SETTINGS']['FORM_ID'];
        $userField = $this->arResult['APP_SETTINGS']['FORM_RES_CODE'];

        if ($this->arResult['USER_TYPE'] === UserHelper::PARTICIPANT_TYPE) {
            $filterId = $this->arResult['USER_ID'];
        } else {
            $filterId = array_map(function ($user) {
                return $user['ID'];
            }, $this->arResult['PARTICIPANTS']);
        }
        $arUsers = \Bitrix\Main\UserTable::getList([
            'select' => ['ID', 'NAME', $userField],
            'filter' => ['ID' => $filterId],
        ])->fetchAll();

        $arAnswersId = array_map(function ($user) use ($userField) {
            return $user[$userField];
        }, $arUsers);
        $arFilter    = ['RESULT_ID' => implode('|', $arAnswersId)];
        CForm::GetResultAnswerArray(
            $formId,
            $columns,
            $answers,
            $answersSID,
            $arFilter
        );

        foreach ($arUsers as $user) {
            $tableFieldSid = CFormMatrix::getSIDRelBase($this->tableSid, $formId);
            $hallFieldSid  = CFormMatrix::getSIDRelBase($this->hallSid, $formId);
            if (isset($answersSID[$user[$userField]][$tableFieldSid][0])) {
                $this->arResult['USERS'][$user['ID']]['TABLE'] = $answersSID[$user[$userField]][$tableFieldSid][0]['USER_TEXT'];
            }
            if (isset($answersSID[$user[$userField]][$hallFieldSid][0])) {
                $this->arResult['USERS'][$user['ID']]['HALL'] = $answersSID[$user[$userField]][$hallFieldSid][0]['ANSWER_TEXT'];
            }
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getReceivers()
    {
        $isParticipant = $this->arResult['USER_TYPE'] === UserHelper::PARTICIPANT_TYPE;
        if ($this->arResult['USER_TYPE'] === UserHelper::PARTICIPANT_TYPE) {
            $users = CGroup::GetGroupUser($this->arResult['APP_SETTINGS']['GUESTS_GROUP']);
        } else {
            $users = CGroup::GetGroupUser($this->arResult['APP_SETTINGS']['MEMBERS_GROUP']);
        }
        if ( !empty($users)) {
            $this->arResult['LIST'] = $this->userHelper->getUsersInfo($users, !$isParticipant);
        }

        return $this;
    }

    private function createSchedule()
    {
        $user = ['ID' => $this->arResult['USER_ID'], 'USER_TYPE' => $this->arResult['USER_TYPE_NAME']];
        foreach ($this->arResult['TIMESLOTS'] as $timeslot) {
            $arRequest = array_filter(
                $this->arResult['USER_REQUESTS'], function ($request) use ($timeslot) {
                return $request['TIMESLOT_ID'] === $timeslot['ID'];
            });
            if (count($arRequest)) {
                $arRequest = array_values($arRequest);
                $request   = $arRequest[0];
            } else {
                $request = [];
            }
            $isReceiver = $request['RECEIVER_ID'] === $this->arResult['USER_ID'];
            $schedule   = [
                'TIMESLOT_ID' => $timeslot['ID'],
                'SLOT_TYPE'   => $timeslot['SLOT_TYPE'],
                'STATUS'      => $timeslot['SLOT_TYPE'] === TimeslotTable::$types[TimeslotTable::TYPE_MEET]
                    ? $request['STATUS'] ?: $this->freeMeetType
                    : '',
                'NAME'        => $timeslot['NAME'],
                'NOTES'       => $this->getNote($request, $user),
                'SENT_BY_YOU' => $isReceiver,
            ];

            if ( !empty($request)) {
                $userId                   = $isReceiver ? $request['SENDER_ID'] : $request['RECEIVER_ID'];
                $schedule['COMPANY_NAME'] = $this->arResult['USERS'][$userId]['NAME'];
                $schedule['COMPANY_REP']  = $this->arResult['USERS'][$userId]['COMPANY'];
                $schedule['COMPANY_ID']   = $this->arResult['USERS'][$userId]['ID'];
                $schedule['FORM_RES']     = $this->arResult['USERS'][$userId]['FORM_RES'];
                $schedule['HALL']         = $this->arResult['USERS'][$userId]['HALL'];
                $schedule['TABLE']        = $this->arResult['USERS'][$userId]['TABLE'];

                $timeLeft              = $this->arResult['APP_SETTINGS']['TIMEOUT_VALUE'] - floor((time() - strtotime($request['UPDATED_AT'])) / 3600);
                $schedule['TIME_LEFT'] = $timeLeft > 0 ? $timeLeft : 0;
            } else {
                if (
                    isset($this->arResult['OTHER_REQUESTS'][$timeslot['ID']]) &&
                    !empty($this->arResult['OTHER_REQUESTS'][$timeslot['ID']])
                ) {
                    $sendersId        = array_map(function ($request) {
                        return $request['SENDER_ID'];
                    }, $this->arResult['OTHER_REQUESTS'][$timeslot['ID']]);
                    $receiversId      = array_map(function ($request) {
                        return $request['RECEIVER_ID'];
                    }, $this->arResult['OTHER_REQUESTS'][$timeslot['ID']]);
                    $busyUsers        = array_merge($sendersId, $receiversId);
                    $schedule['LIST'] = array_filter($this->arResult['LIST'], function ($user) use ($busyUsers) {
                        return !in_array($user['ID'], $busyUsers);
                    });
                } else {
                    $schedule['LIST'] = $this->arResult['LIST'];
                }
            }
            $this->arResult['SCHEDULE'][] = $schedule;
        }

        return $this;
    }

    /**
     * @param array $request
     * @param array $user <br/>
     *
     * It should has following structure: <br/>
     * ID - int <br/>
     * USER_TYPE - string <br/>
     *
     * @return string
     */
    private function getNote($request, $user): string
    {
        switch ($request['STATUS']) {
            case RequestTable::$statuses[RequestTable::STATUS_PROCESS]:
                if ($user['ID'] === $request['MODIFIED_BY']) {
                    $msg = Loc::getMessage("{$user['USER_TYPE']}_SENT_BY_YOU");
                } else {
                    $msg = Loc::getMessage("${user['USER_TYPE']}_SENT_TO_YOU");
                }
                break;
            case RequestTable::$statuses[RequestTable::STATUS_CONFIRMED]:
                if ($user['ID'] === $request['MODIFIED_BY']) {
                    $msg = Loc::getMessage("${user['USER_TYPE']}_CONFIRMED_SELF");
                } else {
                    if ($request['MODIFIED_BY'] === $request['SENDER_ID'] || $request['MODIFIED_BY'] === $request['RECEIVER_ID']) {
                        $msg = Loc::getMessage("${user['USER_TYPE']}_CONFIRMED");
                    } else {
                        $msg = Loc::getMessage("${$user['USER_TYPE']}_CONFIRMED_BY_ADMIN");
                    }
                }
                break;
            case RequestTable::$statuses[RequestTable::STATUS_RESERVE]:
                $msg = '';
                break;
            default:
                $msg = Loc::getMessage("${user['USER_TYPE']}_SLOT_EMPTY");
                break;
        }

        return $msg;
    }

    public function executeComponent()
    {
        $this->onIncludeComponentLang();
        try {
            $this->checkModules()
                 ->getApp()
                 ->getAppSettings()
                 ->createHelperInstance()
                 ->getUserId()
                 ->getUserType()
                 ->getLinks()
                 ->getTimeslots()
                 ->getRequests()
                 ->getInfoAboutUsers()
                 ->getInfoFromWebForm()
                 ->getReceivers()
                 ->createSchedule()
                 ->includeComponentTemplate();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }
}