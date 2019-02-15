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
        $userTypeName                    = strtolower($this->arResult['USER_TYPE_NAME']);
        $exhibitionCode                  = strtolower($this->arParams['EXHIBITION_CODE']);
        $this->arResult['PATH']['BASE']  = "/upload/pdf/{$userTypeName}/";
        $this->arResult['PATH']['SHORT'] = "{$this->arResult['ARCHIVE_SETTINGS']['PATH']['BASE']}wish_{$exhibitionCode}{$isHB}/";
        $this->arResult['PATH']['FULL']  = $_SERVER['DOCUMENT_ROOT'].$this->arResult['ARCHIVE_SETTINGS']['PATH']['SHORT'];
        $this->arResult['PATH']['IS_HB'] = $isHB;

        return $this;
    }

    private function setArchiveName()
    {
        $this->arResult['ARCHIVE_NAME']          = ['SHORT' => '', 'INNER' => '', 'OUTER' => ''];
        $this->arResult['ARCHIVE_NAME']['SHORT'] =
            "{$this->arResult['PATH']['SHORT']}wish_{$this->arParams['EXHIBITION_CODE']}{$this->arResult['PATH']['IS_HB']}.zip";
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


        return $this;
    }

    private function createDirectories()
    {
        CheckDirPath($this->arResult['PATH']['FULL']);

        return $this;
    }

    private function deleteExistingArchive()
    {

        @unlink($this->arResult['ARCHIVE_NAME']['INNER']);

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
            $this->arResult['PATH']['FULL'],
            PCLZIP_OPT_REMOVE_PATH,
            $_SERVER['DOCUMENT_ROOT'].$this->arResult['PATH']['SHORT']
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

    private function cleanFolder($path, $t = '1')
    {
        $rtrn = '1';
        if (file_exists($path) && is_dir($path)) {
            $dirHandle = opendir($path);
            while (false !== ($file = readdir($dirHandle))) {
                if ($file != '.' && $file != '..') {
                    $tmpPath = $path.'/'.$file;
                    chmod($tmpPath, 0777);
                    if (is_dir($tmpPath)) {
                        $this->cleanFolder($tmpPath);
                    } else {
                        if (file_exists($tmpPath)) {
                            unlink($tmpPath);
                        }
                    }
                }
            }
            closedir($dirHandle);
            if ($t == '1') {
                if (file_exists($path)) {
                    rmdir($path);
                }
            }
        } else {
            $rtrn = '0';
        }

        return $rtrn;
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
            $this->cleanFolder($this->arResult['PATH']['FULL']);
        } catch (\Exception $e) {
            $this->cleanFolder($this->arResult['PATH']['FULL']);
            ShowError($e->getMessage());
            @define('ERROR_404', 'Y');
            CHTTP::SetStatus('404 Not Found');
        }
    }
}