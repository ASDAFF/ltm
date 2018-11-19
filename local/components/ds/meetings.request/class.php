<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Models\TimeslotTable;
use Spectr\Meeting\Models\RequestTable;
use Spectr\Meeting\Helpers\User;
use Spectr\Meeting\Helpers\App;

class MeetingsRequest extends CBitrixComponent
{
    const DEFAULT_ERROR = '404 Not Found';
    /** @var User */
    protected $user;
    /** @var App */
    protected $app;

    public function onPrepareComponentParams($arParams): array
    {
        return [
            'APP_ID'               => (int)$arParams['APP_ID'],
            'EXHIBITION_IBLOCK_ID' => (int)$arParams['EXHIBITION_IBLOCK_ID'],
            'IS_HB'                => isset($arParams['IS_HB']) && $arParams['IS_HB'] === 'Y' ? true : false,
            'NEED_RELOAD'          => isset($arParams['NEED_RELOAD']) && $arParams['NEED_RELOAD'] === 'Y' ? true : false,
            'CODE'                 => $_REQUEST['exhib'] ? trim($_REQUEST['exhib']) : trim($_REQUEST['EXHIBIT_CODE']),
        ];
    }

    /**
     * @throws Exception
     **/
    protected function checkModules()
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
    protected function checkAuth()
    {
        global $USER;
        if ( !$USER->IsAuthorized()) {
            throw new Exception(Loc::getMessage('ERROR_USER_IS_NOT_AUTHORIZED'));
        }

        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function init()
    {
        $params     = [
            'IBLOCK_ID' => $this->arParams['EXHIBITION_IBLOCK_ID'],
            'CODE'      => $this->arParams['EXHIBITION_CODE'],
            'ID'        => $this->arParams['APP_ID'],
            'IS_HB'     => $this->arParams['IS_HB'],
        ];

        $this->app  = new App($params);
        $this->user = new User($this->app);

        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function getApp()
    {
        $this->arResult['APP_ID']           = $this->app->getId();
        $this->arResult['APP_ID_OTHER']     = $this->app->getOtherId();
        $this->arResult['APP_SETTINGS']     = $this->app->getSettings();
        $this->arResult['PARAM_EXHIBITION'] = $this->app->getData();
        if ((int)$this->arResult['APP_ID'] <= 0) {
            throw new Exception(self::DEFAULT_ERROR);
        }

        return $this;
    }

    protected function getUserType()
    {
        global $USER;

        if (isset($_REQUEST['type']) && $USER->GetID() == 1) {
            if ($_REQUEST['type'] === 'p') {
                $userType = User::PARTICIPANT_TYPE;
            } else {
                $userType = User::GUEST_TYPE;
            }
        } else {
            $userType = $this->user->getUserType();
        }

        $this->arResult['USER_TYPE']      = $userType;
        $this->arResult['USER_TYPE_NAME'] = User::$userTypes[$userType];

        return $this;
    }

    /**
     * @throws Exception
     */
    protected function checkRestRequestParams()
    {
        $this->checkTimeSlotInRequest();
        $this->checkReceiverInRequest();

        return $this;
    }

    /**
     * @throws Exception
     */
    protected function checkTimeSlotInRequest()
    {
        if ((int)$_REQUEST['time'] <= 0) {
            throw new Exception(Loc::getMessage('ERROR_EMPTY_TIMESLOT_ID'));
        }
    }

    /**
     * @throws Exception
     */
    protected function checkReceiverInRequest()
    {
        if ((int)$_REQUEST['to'] <= 0) {
            if ($this->arResult['USER_TYPE'] === User::PARTICIPANT_TYPE) {
                throw new Exception(Loc::getMessage('ERROR_WRONG_RECEIVER_PARTICIPANT_ID'));
            } else {
                throw new Exception(Loc::getMessage('ERROR_WRONG_RECEIVER_ID'));
            }
        }
    }

    /**
     * @throws Exception
     */
    protected function checkSenderAndReceiver()
    {
        if (empty($this->arResult['SENDER'])) {
            throw new Exception(Loc::getMessage('WRONG_SENDER_ID'));
        }
        if (empty($this->arResult['RECEIVER'])) {
            throw new Exception(Loc::getMessage('WRONG_RECEIVER_ID'));
        }
    }

    /**
     * @throws Exception
     */
    protected function getTimeslot()
    {
        $this->arResult['TIMESLOT'] = TimeslotTable::getTimeslotForMeet((int)$_REQUEST['time']);
        if ( !$this->arResult['TIMESLOT']) {
            throw new Exception(Loc::getMessage('WRONG_TIMESLOT_ID'));
        }
    }

    /**
     * @throws Exception
     */
    protected function getActiveRequest()
    {
        $request = RequestTable::getList([
            'select' => ['*'],
            'filter' => [
                'TIMESLOT_ID'   => $this->arResult['TIMESLOT']['ID'],
                'SENDER_ID'     => $this->arResult['SENDER_ID'],
                'RECEIVER_ID'   => $this->arResult['RECEIVER_ID'],
                'EXHIBITION_ID' => $this->arResult['APP_ID'],
            ],
            'order'  => ['ID' => 'DESC'],
        ]);

        if ($request->getSelectedRowsCount()) {
            $this->arResult['REQUEST'] = $request->fetch();
        } else {
            throw new Exception(Loc::getMessage('REQUEST_NOT_FOUND'));
        }
    }

    /**
     * @param int $limit
     *
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Exception
     */
    protected function checkRequestExists($limit = 0)
    {
        $requests = RequestTable::getAllSlotsBetweenUsers(
            [$this->arResult['SENDER_ID'], $this->arResult['RECEIVER_ID']],
            [$this->arResult['APP_ID'], $this->arResult['APP_ID_OTHER']]
        );

        if ( !empty($requests) && count($requests) > (int)$limit) {
            throw new Exception(Loc::getMessage(User::$userTypes[$this->arResult['USER_TYPE']].'_COMPANY_MEET_EXIST'));
        }
    }

    /**
     * @throws Exception
     */
    protected function checkSenderGroups()
    {
        $valid = false;
        switch ($this->arResult['USER_TYPE']) {
            case User::ADMIN_TYPE:
                $valid = true;
                break;
            case User::GUEST_TYPE:
            case User::PARTICIPANT_TYPE:
                $arSenderGroups = CUser::GetUserGroup($this->arResult['SENDER_ID']);
                $valid          = $this->user->isGuest($arSenderGroups) || $this->user->isParticipant($arSenderGroups);
                break;
        }
        if ( !$valid) {
            throw new Exception(Loc::getMessage('ERROR_GROUP_SENDER'));
        }
    }

    /**
     * @throws Exception
     */
    protected function checkStatus()
    {
        if ($this->arResult['USER_TYPE'] !== User::ADMIN_TYPE &&
            $this->arResult['REQUEST']['STATUS'] !== RequestTable::$statuses[RequestTable::STATUS_PROCESS]) {
            throw new Exception(Loc::getMessage('ERROR_STATUS'));
        }
    }

    /**
     * @throws Exception
     */
    protected function checkBlocking()
    {
        if ($this->arResult['APP_SETTINGS']['IS_LOCKED'] && $this->arResult['USER_TYPE'] !== User::ADMIN_TYPE) {
            throw new Exception(Loc::getMessage('ERROR_APPOINTMENT_LOCKED'));
        }
    }

    public function executeComponent()
    {
        $this->onIncludeComponentLang();
    }
}