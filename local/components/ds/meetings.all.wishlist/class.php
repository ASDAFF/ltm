<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Spectr\Meeting\Helpers\User;

set_time_limit(0);
ignore_user_abort(true);
session_write_close();

CBitrixComponent::includeComponentClass('ds:meetings.wishlist');

class MeetingsAllWishlist extends MeetingsWishlist
{
    public function onPrepareComponentParams($arParams): array
    {
        global $USER;

        return [
            'USER_ID'              => (int)$USER->GetID(),
            'EXHIBITION_IBLOCK_ID' => (int)$arParams['EXHIBITION_IBLOCK_ID'],
            'EMAIL'                => (string)$arParams['EMAIL'] ?: 'info@luxurytravelmart.ru',
            'IS_HB'                => isset($arParams['IS_HB']) && $arParams['IS_HB'] === 'Y' ? true : false,
        ];
    }

    protected function getUserType()
    {
        $type = strtoupper($_REQUEST['type']);
        if ($type === User::$userTypes[User::GUEST_TYPE]) {
            $this->arResult['USER_TYPE']      = User::GUEST_TYPE;
            $this->arResult['USER_TYPE_NAME'] = User::$userTypes[User::GUEST_TYPE];
        } else {
            $this->arResult['USER_TYPE']      = User::PARTICIPANT_TYPE;
            $this->arResult['USER_TYPE_NAME'] = User::$userTypes[User::PARTICIPANT_TYPE];
        }

        return $this;
    }

    private function setExhibitionPDFSettings()
    {

        $this->arResult['EXHIBITION_PDF_SETTINGS'] = [
            'IS_HB'    => $this->arParams['IS_HB'],
            'APP_ID'   => $this->arResult['APP_ID'],
            'TITLE'    => $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['V_EN']['VALUE'],
            'TITLE_RU' => $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['V_RU']['VALUE'],
            'HB_EXIST' => $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['HB_EXIST']['VALUE'],
        ];

        if ($this->arParams['IS_HB']) {
            $this->arResult['EXHIBITION_PDF_SETTINGS']['TITLE']    .= ' Hosted Buyers session';
            $this->arResult['EXHIBITION_PDF_SETTINGS']['TITLE_RU'] .= ' Hosted Buyers сессия';
        }

        return $this;
    }

    private function getUsers()
    {
        $this->arResult['USERS'] = [];

        return $this;
    }

    private function setArchiveSettings()
    {
        $this->arResult['ARCHIVE_NAME'] = '';

        return $this;
    }

    public function generatePDF()
    {
        return $this;
    }

    private function makeArchive()
    {
        return $this;
    }

    private function sendEmail()
    {
        return $this;
    }

    private function cleanFolder()
    {
        return $this;
    }

    public function executeComponent()
    {
        $this->onIncludeComponentLang();
        try {
            $this->checkModules()
                 ->init()
                 ->getApp()
                 ->getUserType()
                 ->setExhibitionPDFSettings()
                 ->getUsers()
                 ->setArchiveSettings()
                 ->generatePDF()
                 ->makeArchive()
                 ->sendEmail()
                 ->cleanFolder();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }
}