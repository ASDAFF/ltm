<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

CBitrixComponent::includeComponentClass('ds:meetings.request');

class MeetingsTimeReserve extends MeetingsRequest
{
    protected function checkRestRequestParams()
    {
        $this->checkTimeSlotInRequest();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function prepareFields()
    {
        $this->getTimeslot();
        $this->getUserId();

        return $this;
    }

    private function getUserId()
    {
        global $USER;
        $userId = (int)$_REQUEST['id'];
        if ($userId <= 0 || $this->arResult['USER_TYPE'] !== self::ADMIN_TYPE) {
            $this->arResult['USER_ID'] = $USER->GetID();
        } else {
            $this->arResult['USER_ID'] = $userId;
        }
    }

    /**
     * @throws Exception
     */
    private function checkReservePossibility()
    {
        $this->checkBlocking();
        $this->checkIsParticipant();
        $this->checkTimeSlotIsBusy();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function checkIsParticipant()
    {
        if ($this->arResult['USER_TYPE'] !== self::PARTICIPANT_TYPE && $this->arResult['USER_TYPE'] !== self::ADMIN_TYPE) {
            throw new Exception(Loc::getMessage('ERROR_NOT_PARTICIPANT'));
        }
    }

    /**
     * TODO need to implement
     */
    private function checkTimeSlotIsBusy()
    {
    }

    /**
     * TODO need to implement
     */
    private function reserveOrRejectRequest()
    {
        return $this;
    }

    public function executeComponent()
    {
        parent::executeComponent();
        $this->onIncludeComponentLang();
        try {
            $this->checkModules()
                 ->checkAuth()
                 ->getAppId()
                 ->getAppSettings()
                 ->getUserType()
                 ->checkRestRequestParams()
                 ->prepareFields()
                 ->checkReservePossibility()
                 ->reserveOrRejectRequest()
                 ->includeComponentTemplate();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }
}