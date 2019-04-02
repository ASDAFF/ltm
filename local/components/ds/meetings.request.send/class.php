<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Models\RequestTable;
use Spectr\Meeting\Helpers\User;
use Bitrix\Main;
use Bitrix\Main\Entity;
use Bitrix\Main\Type\DateTime;

CBitrixComponent::includeComponentClass('ds:meetings.request');

class MeetingsRequestSend extends MeetingsRequest
{
    /**
     * @throws Exception
     */
    private function prepareFields()
    {
        global $USER;
        $this->arResult['RECEIVER_ID'] = (int)$_REQUEST['to'];
        if (isset($_REQUEST['id']) && ($this->arResult['USER_TYPE'] === User::ADMIN_TYPE)) {
            $this->arResult['SENDER_ID'] = (int)$_REQUEST['id'];
        } else {
            $this->arResult['SENDER_ID'] = $USER->GetID();
        }
        if (
            $this->arResult['USER_TYPE'] === User::PARTICIPANT_TYPE ||
            ($this->arResult['USER_TYPE'] === User::ADMIN_TYPE && isset($_REQUEST['type']) && $_REQUEST['type'] === 'p')
        ) {
            $this->arResult['SENDER']   = $this->user->getUserInfo($this->arResult['SENDER_ID'], true);
            $this->arResult['RECEIVER'] = $this->user->getUserInfo($this->arResult['RECEIVER_ID'], false);
        } else {
            $this->arResult['SENDER']   = $this->user->getUserInfo($this->arResult['SENDER_ID'], false);
            $this->arResult['RECEIVER'] = $this->user->getUserInfo($this->arResult['RECEIVER_ID'], true);
        }
        $this->checkSenderAndReceiver();
        $this->getTimeslot();

        return $this;
    }

    /**
     * @throws Main\ArgumentException
     * @throws Exception
     */
    private function checkCreatingRequestPossibility()
    {
        $this->checkSendingRequestToHimself();
        $this->checkUsersRights($this->arResult['RECEIVER_ID'], $this->arResult['SENDER_ID']);
        $this->checkRequestExists();
        $this->checkTimeslotsIsFree();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function checkSendingRequestToHimself()
    {
        if ($this->arResult['RECEIVER_ID'] === $this->arResult['SENDER_ID']) {
            throw new Exception(Loc::getMessage(User::$userTypes[$this->arResult['USER_TYPE']].'_WRONG_RECEIVER_ID'));
        }
    }

    /**
     * @throws Main\ArgumentException
     * @throws Exception
     *
     * @param int $receiverId
     * @param int $senderId
     *
     */
    private function checkUsersRights($receiverId, $senderId = 0)
    {
        $arSelect = ['ID', 'UF_MR', 'GROUPS'];
        $arFilter = ['@ID' => [$senderId, $receiverId]];
        $rsUsers  = Main\UserTable::getList([
            'select'  => $arSelect,
            'filter'  => $arFilter,
            'runtime' => [
                new Entity\ReferenceField(
                    'GROUP',
                    '\Bitrix\Main\UserGroupTable',
                    ['=this.ID' => 'ref.USER_ID']
                ),
                new Entity\ExpressionField(
                    'GROUPS',
                    'GROUP_CONCAT(%s SEPARATOR \'##\')',
                    ['GROUP.GROUP_ID'],
                    [
                        'fetch_data_modification' => function () {
                            return [
                                function ($value) {
                                    $elements = explode('##', $value);

                                    return $elements;
                                },
                            ];
                        },
                    ]
                ),
            ],
        ]);

        $arUsers = [];
        while ($arUser = $rsUsers->fetch()) {
            $arUsers[$arUser['ID']] = $arUser;
        }

        if (isset($arUsers[$senderId])) {
            if (
                !$this->user->isAdmin($arUsers[$senderId]['GROUPS']) &&
                !($this->user->isGuest($arUsers[$senderId]['GROUPS']) && $arUsers[$senderId]['UF_MR']) &&
                !$this->user->isParticipant($arUsers[$senderId]['GROUPS'])
            ) {
                throw new Exception(Loc::getMessage('ERROR_WRONG_RIGHTS'));
            }
        } else {
            throw new Exception(Loc::getMessage('ERROR_WRONG_RIGHTS'));
        }

        if (isset($arUsers[$receiverId])) {
            if (
                !$this->user->isAdmin($arUsers[$receiverId]['GROUPS']) &&
                !($this->user->isGuest($arUsers[$receiverId]['GROUPS']) && $arUsers[$receiverId]['UF_MR']) &&
                !$this->user->isParticipant($arUsers[$receiverId]['GROUPS'])
            ) {
                throw new Exception(Loc::getMessage('ERROR_WRONG_RIGHTS'));
            }
        } else {
            throw new Exception(Loc::getMessage('ERROR_WRONG_RIGHTS'));
        }
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws Exception
     */
    private function checkTimeslotsIsFree()
    {
        $result = RequestTable::checkTimeslotIsFree(
            $this->arResult['TIMESLOT']['ID'],
            [$this->arResult['SENDER_ID'], $this->arResult['RECEIVER_ID'],]
        );

        if ($result) {
            throw new Exception(Loc::getMessage('ERROR_TIMESLOT_BUSY'));
        }
    }

    /**
     * @throws Exception
     */
    private function addRequest()
    {
        $dateTime = new DateTime();
        $fields   = [
            'RECEIVER_ID'   => $this->arResult['RECEIVER_ID'],
            'SENDER_ID'     => $this->arResult['SENDER_ID'],
            'CREATED_AT'    => $dateTime,
            'UPDATED_AT'    => $dateTime,
            'MODIFIED_BY'   => $this->arResult['SENDER_ID'],
            'EXHIBITION_ID' => $this->arResult['APP_ID'],
            'TIMESLOT_ID'   => $this->arResult['TIMESLOT']['ID'],
            'STATUS'        => $this->arResult['USER_TYPE'] === User::ADMIN_TYPE
                ? RequestTable::STATUS_CONFIRMED
                : RequestTable::STATUS_PROCESS,
        ];
        if (isset($_POST['submit'])) {

            $result                         = RequestTable::add($fields);
            $this->arResult['REQUEST_SENT'] = $result->isSuccess();
        }

        return $this;
    }

    private function sendEmail()
    {
        if ($this->arResult['REQUEST_SEND']) {
            $arFieldsMes = [
                'EMAIL'         => $this->arResult['RECEIVER']['EMAIL'],
                'EXIB_NAME_RU'  => $this->arResult['PARAM_EXHIBITION']['NAME'],
                'EXIB_NAME_EN'  => $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['NAME_EN']['VALUE'],
                'EXIB_SHORT_RU' => $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['V_RU']['VALUE'],
                'EXIB_SHORT_EN' => $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['V_EN']['VALUE'],
                'EXIB_DATE'     => $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['DATE']['VALUE'],
                'EXIB_PLACE'    => $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['VENUE']['VALUE'],
            ];
            CEvent::Send($this->arResult['APP_SETTINGS']['EVENT_SENT'], 's1', $arFieldsMes);
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
                 ->checkCreatingRequestPossibility()
                 ->addRequest()
                 ->sendEmail()
                 ->includeComponentTemplate();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }

}