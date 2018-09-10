<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Models\SettingsTable;
use Spectr\Meeting\Models\TimeslotTable;
use Spectr\Meeting\Models\RegistrGuestTable;
use Spectr\Meeting\Models\RequestTable;
use Spectr\Meeting\Models\WishlistTable;

CBitrixComponent::includeComponentClass('ds:meetings.request');

class MeetingsRequestReject extends MeetingsRequest
{
    /**
     * @throws Exception
     */
    protected function prepareFields()
    {
        parent::prepareFields();
        $this->arResult['REQUEST'] = $this->getActiveRequest();
        if ( !$this->arResult['REQUEST']) {
            throw new Exception(Loc::getMessage(self::$userTypes[$this->arResult['USER_TYPE']].'_REQUEST_NOT_FOUND'));
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getActiveRequest()
    {
        $request = RequestTable::getList([
            'select' => ['*'],
            'filter' => [
                'TIMESLOT_ID'   => $this->arResult['TIMESLOT']['ID'],
                'SENDER_ID'     => $this->arResult['SENDER_ID'],
                'RECEIVER_ID'   => $this->arResult['RECEIVER_ID'],
                'EXHIBITION_ID' => $this->arResult['APP_ID'],
            ],
        ]);
        if ($request->getSelectedRowsCount()) {
            return $request->fetch();
        }

        return false;
    }

    /**
     * @throws Exception
     */
    private function checkRejectPossibility()
    {
        $this->checkSenderGroups();
        $this->checkStatus();
        $this->checkBlocking();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function checkSenderGroups()
    {
        $valid          = false;
        $arSenderGroups = CUser::GetUserGroup($this->arResult['SENDER_ID']);
        switch ($this->arResult['USER_TYPE']) {
            case self::ADMIN_TYPE:
                $valid = true;
                break;
            case self::GUEST_TYPE:
            case self::PARTICIPANT_TYPE:
                $valid = $this->isGuest($arSenderGroups) || $this->isParticipant($arSenderGroups);
                break;
        }
        if ( !$valid) {
            throw new Exception(Loc::getMessage('ERROR_GROUP_SENDER'));
        }
    }

    /**
     * @throws Exception
     */
    private function checkStatus()
    {
        if ($this->arResult['USER_TYPE'] !== self::ADMIN_TYPE &&
            $this->arResult['REQUEST']['STATUS'] !== RequestTable::$statuses[RequestTable::STATUS_PROCESS]) {
            throw new Exception(Loc::getMessage('ERROR_STATUS'));
        }
    }

    /**
     * @throws Exception
     */
    private function checkBlocking()
    {
        if ($this->arResult['APP_SETTINGS']['IS_LOCKED'] && $this->arResult['USER_TYPE'] !== self::ADMIN_TYPE) {
            throw new Exception(Loc::getMessage('ERROR_APPOINTMENT_LOCKED'));
        }
    }

    /**
     * @throws Exception
     */
    private function rejectRequest()
    {
        $field                              = ['STATUS' => RequestTable::STATUS_REJECTED];
        $result                             = RequestTable::update($this->arResult['REQUEST']['ID'], $field);
        $this->arResult['REQUEST_REJECTED'] = $result->isSuccess();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function addCompanyToWishlist()
    {
        $result                              = WishlistTable::add([
            'SENDER_ID'   => $this->arResult['SENDER_ID'],
            "RECEIVER_ID" => $this->arResult['RECEIVER_ID'],
            "REASON"      => WishlistTable::REASON_REJECTED,
        ]);
        $this->arResult['ADDED_TO_WISHLIST'] = $result->isSuccess();

        return $this;
    }

    private function sendEmail()
    {
        global $USER;
        if ($this->arResult['USER_TYPE'] !== self::ADMIN_TYPE && $this->arResult['SENDER_ID'] !== $USER->GetID()) {
            $arFieldsMes = array(
                'EMAIL'         => $this->arResult['RECEIVER']['EMAIL'],
                'COMPANY'       => $this->arResult['RECEIVER']['COMPANY'],
                'USER'          => $this->arResult['RECEIVER']['NAME'],
                'EXIB_NAME_RU'  => $this->arResult['PARAM_EXHIBITION']['NAME'],
                'EXIB_NAME_EN'  => $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['NAME_EN']['VALUE'],
                'EXIB_SHORT_RU' => $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['V_RU']['VALUE'],
                'EXIB_SHORT_EN' => $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['V_EN']['VALUE'],
                'EXIB_DATE'     => $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['DATE']['VALUE'],
                'EXIB_PLACE'    => $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['VENUE']['VALUE'],
            );
            CEvent::Send($this->arResult['APP_SETTINGS']['EVENT_REJECT'], 's1', $arFieldsMes);
        }

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
                 ->checkRejectPossibility()
                 ->rejectRequest()
                 ->addCompanyToWishlist()
                 ->sendEmail()
                 ->includeComponentTemplate();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }
}