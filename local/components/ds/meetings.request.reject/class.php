<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Spectr\Meeting\Models\RequestTable;
use Spectr\Meeting\Models\WishlistTable;
use Spectr\Meeting\Helpers\User;
use Bitrix\Main\Type\DateTime;

CBitrixComponent::includeComponentClass('ds:meetings.request');

class MeetingsRequestReject extends MeetingsRequest
{
    /**
     * @throws Exception
     */
    protected function prepareFields()
    {
        global $USER;
        $this->arResult['RECEIVER_ID'] = (int)$_REQUEST['to'];
        $this->arResult['SENDER_ID']   = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : $USER->GetID();
        $senderType                    = $this->user->getUserTypeById($this->arResult['SENDER_ID']);
        if ($senderType === User::GUEST_TYPE) {
            $this->arResult['SENDER']   = $this->user->getUserInfo($this->arResult['SENDER_ID'], false);
            $this->arResult['RECEIVER'] = $this->user->getUserInfo($this->arResult['RECEIVER_ID'], true);
        } else {
            $this->arResult['SENDER']   = $this->user->getUserInfo($this->arResult['SENDER_ID'], true);
            $this->arResult['RECEIVER'] = $this->user->getUserInfo($this->arResult['RECEIVER_ID'], false);
        }
        $this->checkSenderAndReceiver();
        $this->getTimeslot();
        $this->getActiveRequest();

        return $this;
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
    private function rejectRequest()
    {
        global $USER;
        $dateTime = new DateTime();
        $field    = [
            'STATUS'      => RequestTable::STATUS_REJECTED,
            'UPDATED_AT'  => $dateTime,
            'MODIFIED_BY' => $USER->GetID(),
        ];
        $result   = RequestTable::update($this->arResult['REQUEST']['ID'], $field);

        $this->arResult['REQUEST_REJECTED'] = $result->isSuccess();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function addCompanyToWishlist()
    {
        global $USER;
        if ((int)$USER->GetID() !== (int)$this->arResult['SENDER_ID']) {
            $dateTime                            = new DateTime();
            $result                              = WishlistTable::add([
                'SENDER_ID'     => $this->arResult['SENDER_ID'],
                'RECEIVER_ID'   => $this->arResult['RECEIVER_ID'],
                'REASON'        => WishlistTable::REASON_REJECTED,
                'EXHIBITION_ID' => $this->arResult['APP_ID'],
                'CREATED_AT'    => $dateTime,
            ]);
            $this->arResult['ADDED_TO_WISHLIST'] = $result->isSuccess();
        }

        return $this;
    }

    private function sendEmail()
    {
        global $USER;
        if ($this->arResult['USER_TYPE'] !== User::ADMIN_TYPE && $this->arResult['SENDER_ID'] !== $USER->GetID()) {
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
                 ->init()
                 ->getApp()
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