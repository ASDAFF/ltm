<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Spectr\Meeting\Models\SettingsTable;
use Bitrix\Main\Localization\Loc;

set_time_limit(0);
ignore_user_abort(true);
session_write_close();

class MeetingsAllSchedule extends CBitrixComponent
{
    const DEFAULT_ERROR = '404 Not Found';
    const ADMIN_TYPE = 0;
    const GUEST_TYPE = 1;
    const PARTICIPANT_TYPE = 2;
    static $userTypes = [
        self::ADMIN_TYPE        => 'ADMIN',
        self::GUEST_TYPE        => 'GUEST',
        self:: PARTICIPANT_TYPE => 'PARTICIPANT',
    ];

    public function onPrepareComponentParams($arParams): array
    {
        return [
            'CACHE_TIME'           => isset($arParams["CACHE_TIME"]) ? (int)$arParams['CACHE_TIME'] : 3600,
            'EXHIBITION_IBLOCK_ID' => (int)$arParams['EXHIBITION_IBLOCK_ID'],
            'EXHIBITION_CODE'      => (string)$arParams['EXHIBITION_CODE'],
            'USER_TYPE'            => isset($arParams['USER_TYPE']) ? $arParams['USER_TYPE'] : self::$userTypes[self::PARTICIPANT_TYPE],
            'EMAIL'                => isset($arParams['EMAIL']) ? $arParams['EMAIL'] : 'info@luxurytravelmart.ru',
            'IS_HB'                => isset($arParams['IS_HB']) && $arParams['IS_HB'] === 'Y' ? true : false,
            'CUT'                  => $arParams['CUT'],
        ];
    }

    /**
     * @throws Exception
     **/
    protected function checkModules()
    {
        if ( !Loader::includeModule('doka.meetings') || !Loader::includeModule('iblock')) {
            throw new Exception(self::DEFAULT_ERROR);
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getApp()
    {
        if ($this->arParams['EXHIBITION_CODE']) {
            $rsExhibition = CIBlockElement::GetList(
                [],
                [
                    'IBLOCK_ID' => $this->arParams['EXHIBITION_IBLOCK_ID'],
                    'CODE'      => $this->arParams['EXHIBITION_CODE'],
                ],
                false,
                false,
                ['ID', 'CODE', 'IBLOCK_ID', 'PROPERTY_*']
            );
            while ($oExhibition = $rsExhibition->GetNextElement(true, false)) {
                $this->arResult['PARAM_EXHIBITION']               = $oExhibition->GetFields();
                $this->arResult['PARAM_EXHIBITION']['PROPERTIES'] = $oExhibition->GetProperties();
                if ($this->arParams['IS_HB']) {
                    $this->arResult['APP_ID'] = $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['APP_HB_ID']['VALUE'];
                } else {
                    $this->arResult['APP_ID'] = $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['APP_ID']['VALUE'];
                }
                if ((int)$this->arResult['APP_ID'] <= 0) {
                    throw new Exception(self::DEFAULT_ERROR);
                }
            }
        }

        return $this;
    }

    private function getAppSettings()
    {
        $this->arResult['APP_SETTINGS'] = SettingsTable::getById($this->arResult['APP_ID'])->fetch();

        return $this;
    }

    private function setPDFSettings()
    {
        $this->arResult['PDF_SETTINGS'] = [
            'USER_TYPE' => $this->arParams['USER_TYPE'],
            'APP_ID'    => $this->arParams['APP_ID'],
            'IS_ACTIVE' => !$this->arResult['APP_SETTINGS']['IS_LOCKED'],
            'CUT'       => $this->arParams['CUT'],
            'HALL'      => '',
            'TABLE'     => '',
            'CITY'      => '',
        ];

        return $this;
    }

    private function setExhibitionPDFSettings()
    {
        $this->arResult['EXHIBITION_PDF_SETTINGS'] = [
            'IS_HB'    => $this->arParams['IS_HB'],
            'HB_EXIST' => $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['HB_EXIST']['VALUE'],
            'CUT'      => $this->arParams['CUT'],
        ];

        if ($this->arParams['IS_HB']) {
            $this->arResult['EXHIBITION_PDF_SETTINGS']['TITLE']    .= ' Hosted Buyers session';
            $this->arResult['EXHIBITION_PDF_SETTINGS']["TITLE_RU"] .= ' Hosted Buyers сессия';
        } else {
            $this->arResult['EXHIBITION_PDF_SETTINGS']['TITLE']    = $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['V_EN']['VALUE'];
            $this->arResult['EXHIBITION_PDF_SETTINGS']['TITLE_RU'] = $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['V_RU']['VALUE'];

        }

        return $this;
    }

    /** @TODO need to implement */
    private function getTimeslotsForApp()
    {
        return $this;
    }

    /** @TODO need to implement */
    private function getParticipantsList()
    {
        return $this;
    }

    /** @TODO need to implement */
    private function getGuestsList()
    {
        return $this;
    }

    private function deleteExistingArchive()
    {
        return $this;
    }

    /** TODO need to implement */
    private function generatePDF()
    {
        return $this;
    }

    private function makeArchive($pdfFolder, $fileName)
    {
        MakeZipArchive($pdfFolder, $fileName);

        return $this;
    }

    private function sendEmail($fileName)
    {
        if (file_exists($fileName) && is_file($fileName) && filesize($fileName) > 0) {
            $arEventFields = array(
                "EMAIL"     => $this->arParams["EMAIL"],
                "EXIBITION" => $this->arResult['EXHIBITION_PDF_SETTINGS']['TITLE'],
                "TYPE"      => "расписание",
                "USER_TYPE" => strtolower($this->arParams["USER_TYPE"]),
                "LINK"      => "http://".$_SERVER['SERVER_NAME'].$fileName,
            );
            CEvent::SendImmediate("ARCHIVE_READY", "s1", $arEventFields, $Duplicate = "Y");
        }

        return $this;
    }

    private function cleanFolder($path, $t = "1")
    {
        $rtrn = "1";
        if (file_exists($path) && is_dir($path)) {
            $dirHandle = opendir($path);
            while (false !== ($file = readdir($dirHandle))) {
                if ($file != '.' && $file != '..') {
                    $tmpPath = $path.'/'.$file;
                    chmod($tmpPath, 0777);
                    if (is_dir($tmpPath)) {
                        fullRemove_ff($tmpPath);
                    } else {
                        if (file_exists($tmpPath)) {
                            unlink($tmpPath);
                        }
                    }
                }
            }
            closedir($dirHandle);
            if ($t == "1") {
                if (file_exists($path)) {
                    rmdir($path);
                }
            }
        } else {
            $rtrn = "0";
        }

        return $rtrn;
    }

    private function getNote($meet, $user_type, $curUser)
    {
        switch ($meet['status']) {
            case 'process':
                if ($meet['modified_by'] == $curUser) {
                    $msg = Loc::getMessage($user_type.'_SENT_BY_YOU');
                } else {
                    $msg = Loc::getMessage($user_type.'_SENT_TO_YOU');
                }
                break;
            case 'confirmed':
                if ($meet['modified_by'] == $curUser) {
                    $msg = Loc::getMessage($user_type.'_CONFIRMED_SELF');
                } elseif ($meet['modified_by'] == $meet['company_id']) {
                    $msg = Loc::getMessage($user_type.'_CONFIRMED');
                } else {
                    $msg = Loc::getMessage($user_type.'_CONFIRMED_BY_ADMIN');
                }
                break;

            default:
                $msg = Loc::getMessage($user_type.'_SLOT_EMPTY');
                break;
        }

        return $msg;
    }

    public function executeComponent()
    {
        parent::executeComponent();
        $this->onIncludeComponentLang();
        try {
            $this->checkModules()
                 ->getApp()
                 ->getAppSettings()
                 ->setPDFSettings()
                 ->setExhibitionPDFSettings()
                 ->getTimeslotsForApp()
                 ->getParticipantsList()
                 ->getGuestsList()
                 ->generatePDF()
                 ->includeComponentTemplate();
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }
}
