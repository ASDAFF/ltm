<?php
/**
 * Created by PhpStorm.
 * User: Anatoliy Kim
 * Date: 23.09.2017
 * Time: 0:05
 */
namespace Ltm\Domain\GuestStorage;

class FormResult
{
    const STORAGE_FORM = 31;
    const STORAGE_FORM_GROUP = 59;

    public function getUserByResultId($resultId)
    {
        $arUserFilter = array(
            'ACTIVE' => 'Y',
            'GROUPS_ID' => self::STORAGE_FORM_GROUP,
            'UF_ID_COMP' => $resultId,
        );
        $rsUsers = \CUser::GetList(
            $by = 'id',
            $order = 'asc',
            $arUserFilter,
            array(
                'FIELDS' => array("ID", "LOGIN", "EMAIL"),
                'SELECT' => array("UF_*"),
            )
        );
        if ($arUser = $rsUsers->Fetch()) {
            return $arUser;
        }

        return false;
    }

    public function getResultData($resultId)
    {
        $rsFormResult = \CFormResult::GetByID($resultId);

        if ($rsFormResult && $rsFormResult->SelectedRowsCount() > 0) {
            $resultData = [];

            $arFormResult = $rsFormResult->Fetch();
            \CForm::GetResultAnswerArray(
                self::STORAGE_FORM,
                $arQuestions,
                $arAnswer,
                $arAnswer2,
                array("RESULT_ID" => $resultId)
            );
            foreach ($arAnswer2 as $i => $arAnswerArr) {
                $answer = [];
                foreach ($arAnswerArr as $k => $v) {
                    switch ($v[0]["FIELD_TYPE"]) {
                        case "dropdown" :
                        case "checkbox" :
                            $propAnswer = "ANSWER_TEXT";
                            break;
                        case "text" :
                            $propAnswer = "USER_TEXT";
                            break;
                        case "image" :
                            $propAnswer = "USER_FILE_ID";
                            break;
                        default:
                            $propAnswer = "USER_TEXT";
                    }
                    $answer[$v[0]['FIELD_ID']] = $v[0][$propAnswer];
                }
            }
            $arFields = [
                'UF_COMPANY' => $answer[612],
                'UF_ADDRESS' => $answer[614],
                'UF_PRIORITY_AREAS' => $answer[613],
                'UF_POSTCODE' => $answer[615],
                'UF_CITY' => $answer[616],
                'UF_COUNTRY' => $answer[617],
                'UF_COUNTRY_OTHER' => $answer[618],
                'UF_NAME' => $answer[619],
                'UF_SURNAME' => $answer[620],
                'UF_POSITION' => $answer[621],
                'UF_PHONE' => $answer[622],
                'UF_MOBILE' => $answer[623],
                'UF_SKYPE' => $answer[624],
                'UF_EMAIL' => $answer[625],
                'UF_SITE' => $answer[627],
                'UF_LOGIN' => $answer[640],
                'UF_PASSWORD' => $answer[641],
                'UF_DESCRIPTION' => $answer[643],
                'UF_NORTH_AMERICA' => $answer[644],
                'UF_EUROPE' => $answer[645],
                'UF_SOUTH_AMERICA' => $answer[646],
                'UF_AFRICA' => $answer[647],
                'UF_ASIA' => $answer[648],
                'UF_OCEANIA' => $answer[649],
                'UF_MORNING' => $answer[654],
                'UF_EVENING' => $answer[655],
                'UF_PHOTO' => $answer[656],
                'UF_PHOTO_MORNING' => $answer[657],
                'UF_ROOM' => $answer[658],
                'UF_TABLE' => $answer[659],
                'UF_HOTEL' => $answer[673],
                'UF_SALUTATION' => $answer[667],
            ];

            $colleagues = [];
            if (!empty($answer[628])) {
                $colleague = [
                    'UF_NAME' => trim($answer[628]),
                    'UF_SURNAME' => trim($answer[629]),
                    'UF_JOB_TITLE' => $answer[630],
                    'UF_EMAIL' => $answer[631],
                    'UF_SALUTATION' => $answer[668],
                ];
                $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME']] = $colleague;
            }
            if (!empty($answer[632])) {
                $colleague = [
                    'UF_NAME' => trim($answer[632]),
                    'UF_SURNAME' => trim($answer[633]),
                    'UF_JOB_TITLE' => $answer[635],
                    'UF_EMAIL' => $answer[634],
                    'UF_SALUTATION' => $answer[669],
                ];
                $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME']] = $colleague;
            }
            if (!empty($answer[636])) {
                $colleague = [
                    'UF_NAME' => trim($answer[636]),
                    'UF_SURNAME' => trim($answer[637]),
                    'UF_JOB_TITLE' => $answer[638],
                    'UF_EMAIL' => $answer[639],
                    'UF_SALUTATION' => $answer[670],
                ];
                $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME']] = $colleague;
            }
            if (!empty($answer[650])) {
                $colleague = [
                    'MORNING' => true,
                    'UF_NAME' => trim($answer[650]),
                    'UF_SURNAME' => trim($answer[651]),
                    'UF_JOB_TITLE' => $answer[652],
                    'UF_EMAIL' => $answer[653],
                    'UF_SALUTATION' => $answer[672],
                    'UF_PHOTO' => $answer[657],
                ];
                $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME']] = $colleague;
            }

            $resultData['fields'] = $arFields;
            $resultData['colleagues'] = $colleagues;

            return $resultData;
        }

        return false;
    }

    public function getResultList()
    {
        $rsResults = \CFormResult::GetList(self::STORAGE_FORM,
            ($by="s_timestamp"),
            ($order="desc"),
            $arFilter,
            $is_filtered,
            "Y",
            10000);
        $resultIDs = [];
        while ($arResult = $rsResults->Fetch())
        {
            $resultIDs[] = $arResult['ID'];
        }
        return $resultIDs;
    }

}