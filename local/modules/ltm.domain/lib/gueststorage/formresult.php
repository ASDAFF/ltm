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

    const HL2FORM_MAPPING = [
        'UF_COMPANY' => '612',
        'UF_ADDRESS' => '614',
        'UF_PRIORITY_AREAS' => '613',
        'UF_POSTCODE' => '615',
        'UF_CITY' => '616',
        'UF_COUNTRY' => '617',
        'UF_COUNTRY_OTHER' => '618',
        'UF_NAME' => '619',
        'UF_SURNAME' => '620',
        'UF_POSITION' => '621',
        'UF_PHONE' => '622',
        'UF_MOBILE' => '623',
        'UF_SKYPE' => '624',
        'UF_EMAIL' => '625',
        'UF_SITE' => '627',
        'UF_LOGIN' => '640',
        'UF_PASSWORD' => '641',
        'UF_DESCRIPTION' => '643',
        'UF_NORTH_AMERICA' => '644',
        'UF_EUROPE' => '645',
        'UF_SOUTH_AMERICA' => '646',
        'UF_AFRICA' => '647',
        'UF_ASIA' => '648',
        'UF_OCEANIA' => '649',
        'UF_MORNING' => '654',
        'UF_EVENING' => '655',
        'UF_PHOTO' => '656',
        'UF_PHOTO_MORNING' => '657',
        'UF_ROOM' => '658',
        'UF_TABLE' => '659',
        'UF_HOTEL' => '673',
        'UF_SALUTATION' => '667',
    ];

    public function getMapping()
    {
        return self::HL2FORM_MAPPING;
    }

    public function getQuestionArrays()
    {
        $rsQuestions = \CFormField::GetList(self::STORAGE_FORM, "N");
        $arQuestions = [];
        while ($arQuestion = $rsQuestions->Fetch()) {
            $question = [];
            $question['FIELD_TYPE'] = $arQuestion['FIELD_TYPE'];
            $question['QUESTIONS'] = $arQuestion['TITLE'];
            //$question['ANSWER_ID'] = $arQuestion['FIELD_TYPE'];
            $question['SID'] = $arQuestion['SID'];
            $question['VALUE'] = $arQuestion['VALUE'];
            $arQuestions[$arQuestion['ID']] = $question;
        }
        return $arQuestions;
    }

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
                    if (count($v) > 1) {
                        foreach($v as $k1=>$v1)
                        {
                            switch ($v1["FIELD_TYPE"]) {
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
                            $answer[$v[$k1]['FIELD_ID']][] = $v[$k1][$propAnswer];
                        }
                    } else {
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
            }
            $arFields = [];
            foreach(self::HL2FORM_MAPPING as $k => $v)
            {
                if(isset($answer[$v]))
                {
                    $arFields[$k] = $answer[$v];
                }
            }

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