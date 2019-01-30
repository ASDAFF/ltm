<?php
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Spectr\Meeting\Helpers\User;
use Spectr\Meeting\Models\RegistrGuestColleagueTable;
use Spectr\Meeting\Models\TimeslotTable;
use Spectr\Meeting\Models\RequestTable;

set_time_limit(0);
ignore_user_abort(true);
session_write_close();

CBitrixComponent::includeComponentClass('ds:meetings.schedule');

class MeetingsAllSchedule extends MeetingsSchedule
{
    public function onPrepareComponentParams($arParams): array
    {
        return [
            'CACHE_TIME'           => isset($arParams['CACHE_TIME']) ? (int)$arParams['CACHE_TIME'] : 3600,
            'EXHIBITION_IBLOCK_ID' => (int)$arParams['EXHIBITION_IBLOCK_ID'],
            'EXHIBITION_CODE'      => (string)$arParams['EXHIBITION_CODE'],
            'EMAIL'                => isset($arParams['EMAIL']) ? $arParams['EMAIL'] : 'info@luxurytravelmart.ru',
            'IS_HB'                => isset($arParams['IS_HB']) && $arParams['IS_HB'] === 'Y' ? true : false,
            'CUT'                  => $arParams['CUT'],
        ];
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
            $this->arResult['EXHIBITION_PDF_SETTINGS']['TITLE_RU'] .= ' Hosted Buyers сессия';
        } else {
            $this->arResult['EXHIBITION_PDF_SETTINGS']['TITLE']    = $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['V_EN']['VALUE'];
            $this->arResult['EXHIBITION_PDF_SETTINGS']['TITLE_RU'] = $this->arResult['PARAM_EXHIBITION']['PROPERTIES']['V_RU']['VALUE'];
        }

        return $this;
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

    /**
     * @throws Exception
     */
    private function getUsers()
    {
        $this->getParticipants()->getGuests();

        foreach ($this->arResult['PARTICIPANTS'] as $user) {
            $this->arResult['USERS'][$user['ID']] = $user;
        }

        $this->arResult['COLLEAGUES_ID'] = [];
        foreach ($this->arResult['GUESTS'] as $user) {
            $this->arResult['USERS'][$user['ID']] = $user;
            if ( !empty($user['COLLEAGUES'])) {
                $this->arResult['COLLEAGUES_ID'] = array_merge($this->arResult['COLLEAGUES_ID'], $user['COLLEAGUES']);
            }
        }
        $this->getColleagues();

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getParticipants()
    {
        $users = CGroup::GetGroupUser($this->arResult['APP_SETTINGS']['MEMBERS_GROUP']);
        if ( !empty($users)) {
            $this->arResult['PARTICIPANTS'] = $this->user->getUsersInfo($users, true);
        } else {
            $this->arResult['PARTICIPANTS'] = [];
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getGuests()
    {
        $isHbExhibition = $this->arResult['APP_SETTINGS']['IS_HB'];
        $users          = CGroup::GetGroupUser($this->arResult['APP_SETTINGS']['GUESTS_GROUP']);
        if ( !empty($users)) {
            $this->arResult['GUESTS'] = $this->user->getUsersInfo($users, false, $isHbExhibition, !$isHbExhibition);
        } else {
            $this->arResult['GUESTS'] = [];
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    private function getColleagues()
    {
        $this->arResult['COLLEAGUES_ID'] = array_unique($this->arResult['COLLEAGUES_ID']);

        $colleagues = RegistrGuestColleagueTable::getList(['filter' => ['ID' => $this->arResult['COLLEAGUES_ID']]]);
        while ($colleague = $colleagues->fetch()) {
            $this->arResult['COLLEAGUES'][$colleague['ID']] = $colleague;
        }

        return $this;
    }

    /**
     * @param int $userId
     *
     * @throws Exception
     * @return array
     */
    private function getRequestsForUser($userId)
    {
        $requests   = [];
        $dbRequests = RequestTable::getList([
            'filter' => [
                '=EXHIBITION_ID' => $this->arResult['APP_ID'],
                '!=STATUS'       => array_map(function ($status) {
                    return RequestTable::$statuses[$status];
                }, RequestTable::$freeStatuses),
                [
                    'LOGIC'        => 'OR',
                    '=SENDER_ID'   => $userId,
                    '=RECEIVER_ID' => $userId,
                ],
            ],
        ]);

        while ($req = $dbRequests->fetch()) {
            if ( !isset($requests[$req['TIMESLOT_ID']])) {
                $requests[$req['TIMESLOT_ID']] = [];
            }
            $isSender                      = (int)$req['SENDER_ID'] === (int)$userId;
            $requests[$req['TIMESLOT_ID']] = [
                'USER_ID'     => $isSender ? $req['RECEIVER_ID'] : $req['SENDER_ID'],
                'IS_SENDER'   => $isSender,
                'STATUS'      => $req['STATUS'],
                'MODIFIED_BY' => $req['MODIFIED_BY'],
                'SENDER_ID'   => $req['SENDER_ID'],
                'RECEIVER_ID' => $req['RECEIVER_ID'],
            ];
        }

        return $requests;
    }

    private function setFolder()
    {
        $this->arResult['PDF_FOLDER'] = $_SERVER['DOCUMENT_ROOT'].$this->getRelativePathToSourceFolder();
        CheckDirPath($this->arResult['PDF_FOLDER']);

        return $this;
    }

    private function setArchiveName()
    {
        $this->arResult['ARCHIVE_NAME'] = $_SERVER['DOCUMENT_ROOT'].$this->getRelativePathToArchive();

        return $this;
    }

    private function getSourcePath()
    {
        return '/upload/pdf/'.strtolower($this->arResult['USER_TYPE_NAME']).'/';
    }

    private function getRelativePathToArchive()
    {
        return $this->getSourcePath().$this->arResult['PARAM_EXHIBITION']['CODE'].$this->getPostfix().'.zip';
    }

    private function getRelativePathToSourceFolder()
    {
        return $this->getSourcePath().strtolower($this->arResult['PARAM_EXHIBITION']['CODE']).$this->getPostfix().'/';
    }

    private function getPostfix()
    {
        $postfix = '';
        if ($this->arParams['IS_HB']) {
            $postfix = '_hb';
        }

        return $postfix;
    }

    private function generatePDF()
    {
        global $APPLICATION;
        $this->deleteExistingArchive();
        $isParticipant = $this->arResult['USER_TYPE'] === User::PARTICIPANT_TYPE;
        $this->loadFunctionsForCreatePDF($isParticipant);
        $targetUsers = $isParticipant ? $this->arResult['PARTICIPANTS'] : $this->arResult['GUESTS'];
        $APPLICATION->RestartBuffer();
        array_walk($targetUsers,
            function ($item) {
                $user = [
                    'id'         => $item['ID'],
                    'name'       => $item['COMPANY'],
                    'rep'        => $item['NAME'],
                    'col_rep'    => '',
                    'mob'        => $this->arResult['USERS'][$item['ID']]['MOB'],
                    'phone'      => $this->arResult['USERS'][$item['ID']]['PHONE'],
                    'hall'       => $this->arResult['USERS'][$item['ID']]['HALL_MESSAGE'] ?: '',
                    'table'      => $this->arResult['USERS'][$item['ID']]['TABLE'],
                    'city'       => $this->arResult['USERS'][$item['ID']]['CITY'],
                    'is_hb'      => $this->arResult['USERS'][$item['ID']]['IS_HB'],
                    'path'       => $this->arResult['PDF_FOLDER'].$this->getNameOfPdfByUser($item),
                    'schedule'   => $this->getSchedule($item),
                    'exhib'      => $this->arResult['EXHIBITION_PDF_SETTINGS'],
                    'APP_ID'     => $this->arResult['APP_ID'],
                    'exhibition' => $this->arResult['APP_SETTINGS'],
                ];
                if ( !empty($item['COLLEAGUES'])) {
                    foreach ($item['COLLEAGUES'] as $colleague) {
                        if ($this->arResult['COLLEAGUES'][$colleague]) {
                            $user['col_rep'] = "{$colleague['UF_NAME']} {$colleague['UF_SURNAME']}";
                            break;
                        }
                    }
                }

                DokaGeneratePdf($user);
            });

        return $this;
    }

    /**
     * @param bool $isParticipant
     */
    private function loadFunctionsForCreatePDF($isParticipant = false)
    {
        $userName = $isParticipant ? $this->templateNameForParticipant : $this->arResult['USER_TYPE_NAME'];
        require(DOKA_MEETINGS_MODULE_DIR.'/classes/pdf/tcpdf.php');
        require_once(DOKA_MEETINGS_MODULE_DIR."/classes/pdf/templates/schedule_all_{$userName}.php");
    }

    private function getNameOfPdfByUser($user)
    {
        $name = "{$user['COMPANY']}_{$user['ID']}.pdf";
        $name = $this->removeSlashes($name);
        $name = $this->removeSpaces($name);
        $name = $this->removeStars($name);

        return $name;
    }

    private function removeSpaces($str)
    {
        return str_replace(' ', '_', $str);
    }

    private function removeSlashes($str)
    {
        return str_replace('/', '_', $str);
    }

    private function removeStars($str)
    {
        return str_replace('*', '_', $str);
    }

    /**
     * @param int $user
     *
     * @throws Exception
     * @return array
     */
    private function getSchedule($user)
    {
        $schedule = [];
        $reqs     = $this->getRequestsForUser($user['ID']);
        foreach ($this->arResult['TIMESLOTS'] as $timeslot) {
            $request = $reqs[$timeslot['ID']] ?: [];
            $item    = [
                'timeslot_id'   => $timeslot['ID'],
                'timeslot_name' => $timeslot['NAME'],
            ];
            if ( !empty($request)) {
                $item['is_busy']        = true;
                $item['user_is_sender'] = $request['IS_SENDER'];
                $item['status']         = $request['STATUS'];
                $item['company_id']     = $request['USER_ID'];
                $item['company_name']   = $this->arResult['USERS'][$request['USER_ID']]['COMPANY'];
                $item['company_rep']    = $this->arResult['USERS'][$request['USER_ID']]['NAME'];
                $item['hall']           = $this->arResult['USERS'][$request['USER_ID']]['HALL'];
                $item['table']          = $this->arResult['USERS'][$request['USER_ID']]['TABLE'];

                $arUser        = ['ID' => $user['ID'], 'USER_TYPE' => $this->arResult['USER_TYPE_NAME']];
                $item['notes'] = $this->getNote($request, $arUser);
            } else {
                $item['is_busy']        = false;
                $item['user_is_sender'] = false;
                if ($timeslot['SLOT_TYPE'] === TimeslotTable::$types[TimeslotTable::TYPE_MEET]) {
                    $item['status'] = TimeslotTable::$types[TimeslotTable::TYPE_FREE];
                } else {
                    $item['status'] = $timeslot['SLOT_TYPE'];
                    $item['notes']  = $timeslot['SLOT_TYPE'];
                }
            }
            $schedule[] = $item;
        }

        return $schedule;
    }

    private function makeArchive()
    {
        MakeZipArchive($this->arResult['PDF_FOLDER'], $this->arResult['ARCHIVE_NAME']);

        return $this;
    }

    private function sendEmail()
    {
        if (
            file_exists($this->arResult['ARCHIVE_NAME']) &&
            is_file($this->arResult['ARCHIVE_NAME']) &&
            filesize($this->arResult['ARCHIVE_NAME']) > 0
        ) {
            $arEventFields = [
                'EMAIL'     => $this->arParams['EMAIL'],
                'EXIBITION' => $this->arResult['EXHIBITION_PDF_SETTINGS']['TITLE'],
                'TYPE'      => 'расписание',
                'USER_TYPE' => strtolower($this->arResult['USER_TYPE_NAME']),
                'LINK'      => 'http://'.$_SERVER['SERVER_NAME'].$this->getRelativePathToArchive(),
            ];
            CEvent::SendImmediate('ARCHIVE_READY', 's1', $arEventFields, 'Y');
        }

        return $this;
    }

    private function deleteExistingArchive()
    {
        @unlink($this->arResult['ARCHIVE_NAME']);

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
                 ->setPDFSettings()
                 ->setExhibitionPDFSettings()
                 ->getUserType()
                 ->getTimeslots()
                 ->getUsers()
                 ->setFolder()
                 ->setArchiveName()
                 ->generatePDF()
                 ->makeArchive();
            $this->sendEmail();
            $this->cleanFolder($this->arResult['PDF_FOLDER']);
        } catch (\Exception $e) {
            $this->cleanFolder($this->arResult['PDF_FOLDER']);
            ShowError($e->getMessage());
        }
    }
}
