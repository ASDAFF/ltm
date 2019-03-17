<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Spectr\Meeting\Models\RequestTable;
use Bitrix\Main\Type\DateTime;
use Spectr\Meeting\Helpers\UserHelper;

CBitrixComponent::includeComponentClass('ds:meetings.request');

class MeetingsRequestConfirm extends MeetingsRequest
{
    /**
     * @throws Exception
     */
    protected function prepareFields()
    {
        global $USER;
        if ($this->arResult['USER_TYPE'] === UserHelper::ADMIN_TYPE) {
            $this->arResult['RECEIVER_ID'] = (int)$_REQUEST['to'];
            $this->arResult['SENDER_ID']   = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : $USER->GetID();
        } else {
            $this->arResult['SENDER_ID']   = (int)$_REQUEST['to'];
            $this->arResult['RECEIVER_ID'] = $USER->GetID();
        }

        switch ($this->arResult['USER_TYPE']) {
            case UserHelper::GUEST_TYPE:
                $this->arResult['SENDER']   = $this->userHelper->getUserInfo($this->arResult['SENDER_ID'], true);
                $this->arResult['RECEIVER'] = $this->userHelper->getUserInfo($this->arResult['RECEIVER_ID'], false);
                break;
            case UserHelper::PARTICIPANT_TYPE:
                $this->arResult['SENDER']   = $this->userHelper->getUserInfo($this->arResult['SENDER_ID'], false);
                $this->arResult['RECEIVER'] = $this->userHelper->getUserInfo($this->arResult['RECEIVER_ID'], true);
                break;
            default:
                $senderType = $this->userHelper->getUserTypeById($this->arResult['SENDER_ID']);
                if ($senderType === UserHelper::GUEST_TYPE) {
                    $this->arResult['SENDER']   = $this->userHelper->getUserInfo($this->arResult['SENDER_ID'], true);
                    $this->arResult['RECEIVER'] = $this->userHelper->getUserInfo($this->arResult['RECEIVER_ID'], false);
                } else {
                    $this->arResult['SENDER']   = $this->userHelper->getUserInfo($this->arResult['SENDER_ID'], false);
                    $this->arResult['RECEIVER'] = $this->userHelper->getUserInfo($this->arResult['RECEIVER_ID'], true);
                }
        }

        $this->checkSenderAndReceiver();
        $this->getTimeslot();
        $this->getActiveRequest();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function checkConfirmPossibility()
    {
        $this->checkRequestExists(1);
        $this->checkSenderGroups();
        $this->checkStatus();
        $this->checkBlocking();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function confirmRequest()
    {
        global $USER;
        $dateTime = new DateTime();
        $field    = [
            'STATUS'      => RequestTable::STATUS_CONFIRMED,
            'UPDATED_AT'  => $dateTime,
            'MODIFIED_BY' => $USER->GetID(),
        ];
        $result   = RequestTable::update($this->arResult['REQUEST']['ID'], $field);

        $this->arResult['REQUEST_CONFIRMED'] = $result->isSuccess();

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
                 ->createHelperInstance()
                 ->getUserType()
                 ->checkRestRequestParams()
                 ->prepareFields()
                 ->checkConfirmPossibility()
                 ->confirmRequest()
                 ->includeComponentTemplate();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }
}