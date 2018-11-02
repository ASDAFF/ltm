<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Models\RequestTable;
use Spectr\Meeting\Helpers\UserHelper;
use Bitrix\Main\Type\DateTime;

CBitrixComponent::includeComponentClass('ds:meetings.request');

class MeetingsTimeReserve extends MeetingsRequest
{
    public function onPrepareComponentParams($arParams): array
    {
        return [
            'APP_ID'               => (int)$arParams['APP_ID'],
            'EXHIBITION_IBLOCK_ID' => (int)$arParams['EXHIBITION_IBLOCK_ID'],
            'IS_HB'                => $arParams['IS_HB'] === 'Y' ? true : false,
        ];
    }

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
        $this->arResult['REQUEST'] = $this->getRequest();

        return $this;
    }

    private function getUserId()
    {
        global $USER;
        $userId = (int)$_REQUEST['id'];
        if ($userId <= 0 || $this->arResult['USER_TYPE'] !== UserHelper::ADMIN_TYPE) {
            $this->arResult['USER_ID'] = $USER->GetID();
        } else {
            $this->arResult['USER_ID'] = $userId;
        }
    }

    /**
     * @throws Exception
     * @return array
     */
    private function getRequest()
    {
        $filter = [
            '!=STATUS'       => array_map(function ($status) {
                return RequestTable::$statuses[$status];
            }, RequestTable::$freeStatuses),
            '=TIMESLOT_ID'   => $this->arResult['TIMESLOT']['ID'],
            [
                'LOGIC'        => 'OR',
                '=SENDER_ID'   => $this->arResult['USER_ID'],
                '=RECEIVER_ID' => $this->arResult['USER_ID'],
            ],
            '=EXHIBITION_ID' => $this->arResult['APP_ID'],
        ];

        return RequestTable::getRow(['select' => ['*'], 'filter' => $filter]);
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
        if ($this->arResult['USER_TYPE'] !== UserHelper::PARTICIPANT_TYPE && $this->arResult['USER_TYPE'] !== UserHelper::ADMIN_TYPE) {
            throw new Exception(Loc::getMessage('ERROR_NOT_PARTICIPANT'));
        }
    }

    /**
     * @throws Exception
     */
    private function checkTimeSlotIsBusy()
    {
        if ( !empty($this->arResult['REQUEST'])) {
            if (
                $this->arResult['REQUEST']['STATUS'] !== RequestTable::$statuses[RequestTable::STATUS_RESERVE] ||
                (int)$this->arResult['REQUEST']['SENDER_ID'] !== (int)$this->arResult['USER_ID'] ||
                (int)$this->arResult['REQUEST']['RECEIVER_ID'] !== (int)$this->arResult['USER_ID']
            ) {
                throw new Exception(Loc::getMessage('ERROR_TIMESLOT_BUSY'));
            }
        }
    }

    /**
     * @throws Exception
     */
    private function reserveOrRejectRequest()
    {
        $dateTime = new DateTime();
        $fields   = [
            'UPDATED_AT'  => $dateTime,
            'MODIFIED_BY' => $this->arResult['USER_ID'],
        ];
        if ( !empty($this->arResult['REQUEST'])) {
            $this->arResult['TO_RESERVE']       = false;
            $fields['STATUS']                   = RequestTable::STATUS_REJECTED;
            $result                             = RequestTable::update($this->arResult['REQUEST']['ID'], $fields);
            $this->arResult['REQUEST_REJECTED'] = $result->isSuccess();
        } else {
            $this->arResult['TO_RESERVE'] = true;
            $fields['RECEIVER_ID']        = $this->arResult['USER_ID'];
            $fields['SENDER_ID']          = $this->arResult['USER_ID'];
            $fields['CREATED_AT']         = $dateTime;
            $fields['EXHIBITION_ID']      = $this->arResult['APP_ID'];
            $fields['TIMESLOT_ID']        = $this->arResult['TIMESLOT']['ID'];
            $fields['STATUS']             = RequestTable::STATUS_RESERVE;
            if (isset($_REQUEST['confirm']) && $_REQUEST['confirm'] === 'Y') {

                $result                            = RequestTable::add($fields);
                $this->arResult['REQUEST_RESERVE'] = $result->isSuccess();
            }
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
                 ->createHelperInstance()
                 ->getUserType()
                 ->checkRestRequestParams()
                 ->prepareFields()
                 ->checkReservePossibility()
                 ->reserveOrRejectRequest()
                 ->includeComponentTemplate();
        } catch (\Exception $e) {
            $this->arResult['ERROR_MESSAGE'][] = $e->getMessage();
            $this->includeComponentTemplate();
        }
    }
}