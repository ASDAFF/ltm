<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Spectr\Meeting\Helpers\User;
use Spectr\Meeting\Helpers\Utils;
use Bitrix\Main\Localization\Loc;
use Spectr\Meeting\Models\RegistrGuestColleagueTable;

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
            'EXHIBITION_CODE'      => (string)$arParams['EXHIBITION_CODE'],
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

    /**
     * @throws Exception
     */
    private function getUsers()
    {
        if ($this->arResult['USER_TYPE'] !== User::PARTICIPANT_TYPE) {
            $groups         = $this->arResult['APP_SETTINGS']['GUESTS_GROUP'];
            $isParticipants = false;
            if ($this->arParams['IS_HB']) {
                $onlyHB      = true;
                $onlyMorning = false;
            } else {
                $onlyHB      = false;
                $onlyMorning = true;
            }
        } else {
            $groups         = $this->arResult['APP_SETTINGS']['MEMBERS_GROUP'];
            $isParticipants = true;
            $onlyHB         = false;
            $onlyMorning    = false;
        }

        $users = CGroup::GetGroupUser($groups);


        $this->arResult['USERS'] = $this->user->getUsersInfo($users, $isParticipants, $onlyHB, $onlyMorning);

        return $this;
    }

    private function setArchiveSettings()
    {
        $this->setArchivePath()->setArchiveName();

        return $this;
    }

    private function setArchivePath()
    {
        $this->arResult['PATH'] = [];
        $isHB                   = '';
        if ($this->arParams['IS_HB']) {
            $isHB = '_hb';
        }
        $userTypeName                       = strtolower($this->arResult['USER_TYPE_NAME']);
        $exhibitionCode                     = strtolower($this->arParams['EXHIBITION_CODE']);
        $this->arResult['PATH']['BASE']     = "/upload/pdf/{$userTypeName}/";
        $this->arResult['PATH']['RELATIVE'] = "{$this->arResult['PATH']['BASE']}wish_{$exhibitionCode}{$isHB}/";
        $this->arResult['PATH']['ABSOLUTE'] = $_SERVER['DOCUMENT_ROOT'].$this->arResult['PATH']['RELATIVE'];
        $this->arResult['PATH']['IS_HB']    = $isHB;

        return $this;
    }

    private function setArchiveName()
    {
        $this->arResult['ARCHIVE_NAME']          = ['SHORT' => '', 'INNER' => '', 'OUTER' => ''];
        $this->arResult['ARCHIVE_NAME']['SHORT'] =
            "{$this->arResult['PATH']['BASE']}wish_{$this->arParams['EXHIBITION_CODE']}{$this->arResult['PATH']['IS_HB']}.zip";
        $this->arResult['ARCHIVE_NAME']['INNER'] = "{$_SERVER['DOCUMENT_ROOT']}{$this->arResult['ARCHIVE_NAME']['SHORT']}";
        $this->arResult['ARCHIVE_NAME']['OUTER'] = "http://{$_SERVER['SERVER_NAME']}{$this->arResult['ARCHIVE_NAME']['SHORT']}";

        return $this;
    }

    public function generatePDF()
    {
        global $APPLICATION;
        $isParticipant = $this->arResult['USER_TYPE'] === User::PARTICIPANT_TYPE;
        $this->createDirectories()->deleteExistingArchive()->loadFunctionsForCreatePDF($isParticipant);
        $APPLICATION->RestartBuffer();
        $this->arResult['WISH_IN']  = [];
        $this->arResult['WISH_OUT'] = [];
        array_walk($this->arResult['USERS'], function ($item) {
            $isParticipant = $this->arResult['USER_TYPE'] !== User::PARTICIPANT_TYPE;
            $company       = [
                'id'             => $item['ID'],
                'name'           => $item['COMPANY'],
                'rep'            => $item['NAME'],
                'col_rep'        => '',
                'city'           => $item['CITY'],
                'path'           => $this->arResult['PATH']['ABSOLUTE'].$this->getNameOfPdfByUser($item),
                'exhib'          => $this->arResult['EXHIBITION_PDF_SETTINGS'],
                'is_hb'          => $item['IS_HB'],
                'wish_in'        => $this->getWishListIn($item['ID'], $isParticipant),
                'wish_out'       => $this->getWishListOut($item['ID'], $isParticipant),
                'STATUS_REQUEST' => [
                    'empty'    => '',
                    'rejected' => Loc::getMessage($this->arResult['USER_TYPE_NAME'].'_REJECTED'),
                    'timeout'  => Loc::getMessage($this->arResult['USER_TYPE_NAME'].'_TIMEOUT'),
                    'selected' => Loc::getMessage($this->arResult['USER_TYPE_NAME'].'_SELECTED'),
                ],
            ];

            if (is_array($item['COLLEAGUES']) && !empty($item['COLLEAGUES'])) {
                $colleagues = RegistrGuestColleagueTable::getList(['filter' => ['ID' => $item['COLLEAGUES']]]);
                if ($colleague = $colleagues->fetch()) {
                    $company['col_rep'] = "{$colleague['UF_NAME']} {$colleague['UF_SURNAME']}";
                }
            }

            DokaGeneratePdf($company);
        });

        return $this;
    }

    private function createDirectories()
    {
        CheckDirPath($this->arResult['PATH']['ABSOLUTE']);

        return $this;
    }

    private function deleteExistingArchive()
    {
        Utils::deleteFile($this->arResult['ARCHIVE_NAME']['INNER']);

        return $this;
    }

    private function loadFunctionsForCreatePDF($isParticipant)
    {
        $userName = $isParticipant ? $this->templateNameForParticipant : $this->arResult['USER_TYPE_NAME'];
        require(DOKA_MEETINGS_MODULE_DIR.'/classes/pdf/tcpdf.php');
        require_once(DOKA_MEETINGS_MODULE_DIR."/classes/pdf/templates/wishlist_all_{$userName}.php");

        return $this;
    }

    /**
     * @throws Exception
     */
    private function makeArchive()
    {
        include_once($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/lib/pclzip.lib.php');
        $this->arResult['ARCHIVE']        = new PclZip($this->arResult['ARCHIVE_NAME']['INNER']);
        $this->arResult['ARCHIVE_RESULT'] = $this->arResult['ARCHIVE']->create(
            $this->arResult['PATH']['ABSOLUTE'],
            PCLZIP_OPT_REMOVE_PATH,
            $_SERVER['DOCUMENT_ROOT'].$this->arResult['PATH']['RELATIVE']
        );
        if ($this->arResult['ARCHIVE_RESULT'] === 0) {
            throw new Exception($this->arResult['ARCHIVE']->errorInfo(true));
        }

        return $this;
    }

    private function sendEmail()
    {
        $arEventFields = array(
            'EMAIL'     => $this->arParams['EMAIL'],
            'EXIBITION' => $this->arResult['EXHIBITION_PDF_SETTINGS']['TITLE'],
            'TYPE'      => 'вишлист',
            'USER_TYPE' => strtolower($this->arResult['USER_TYPE_NAME']),
            'LINK'      => $this->arResult['ARCHIVE_NAME']['OUTER'],
        );
        CEvent::SendImmediate('ARCHIVE_READY', 's1', $arEventFields, 'Y');

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
                 ->sendEmail();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
            @define('ERROR_404', 'Y');
            CHTTP::SetStatus('404 Not Found');
        }
    }
}