<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Models\RequestTable;
use Spectr\Meeting\Models\SettingsTable;
use Spectr\Meeting\Models\TimeslotTable;
use Spectr\Meeting\Models\RegistrGuestTable;
use Bitrix\Main;
use Bitrix\Main\Entity;
use Bitrix\Main\Type\DateTime;

CBitrixComponent::includeComponentClass('ds:meetings.request');

class MeetingsRequestSend extends MeetingsRequest
{
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
            throw new Exception(Loc::getMessage(self::$userTypes[$this->arResult['USER_TYPE']].'_WRONG_RECEIVER_ID'));
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
                !$this->isAdmin($arUsers[$senderId]['GROUPS']) &&
                !($this->isGuest($arUsers[$senderId]['GROUPS']) && $arUsers[$senderId]['UF_MR']) &&
                !$this->isParticipant($arUsers[$senderId]['GROUPS'])
            ) {
                throw new Exception(Loc::getMessage('ERROR_WRONG_SENDER_RIGHTS'));
            }
        } else {
            throw new Exception(Loc::getMessage('ERROR_WRONG_SENDER_RIGHTS'));
        }

        if (isset($arUsers[$receiverId])) {
            if (
                !$this->isAdmin($arUsers[$receiverId]['GROUPS']) &&
                !($this->isGuest($arUsers[$receiverId]['GROUPS']) && $arUsers[$receiverId]['UF_MR']) &&
                !$this->isParticipant($arUsers[$receiverId]['GROUPS'])
            ) {
                throw new Exception(Loc::getMessage('ERROR_WRONG_RECEIVER_RIGHTS'));
            }
        } else {
            throw new Exception(Loc::getMessage('ERROR_WRONG_RECEIVER_RIGHTS'));
        }
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Exception
     */
    private function checkRequestExists()
    {
        $requests = RequestTable::getAllSlotsBetweenUsers(
            [$this->arResult['SENDER_ID'], $this->arResult['RECEIVER_ID']],
            [$this->arResult['APP_ID'], $this->arResult['APP_ID_OTHER']]
        );
        if ( !empty($requests)) {
            throw new Exception(Loc::getMessage(self::$userTypes[$this->arResult['USER_TYPE']].'_COMPANY_MEET_EXIST'));
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
            throw new Exception('ERROR_TIMESLOT_BUSY');
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
            'STATUS'        => $this->arResult['USER_TYPE'] === self::ADMIN_TYPE
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
                 ->getAppId()
                 ->getAppSettings()
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