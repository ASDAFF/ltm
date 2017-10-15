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
    const WORKING_FORM = 10;
    const STORAGE_FORM_GROUP = 59;
    const MORNING_VAL = 6;
    const EVENING_VAL = 7;

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

    const HL2FORM_ACTIVE_MAPPING = [
        'UF_COMPANY' => '107',
        'UF_ADDRESS' => '109',
        'UF_PRIORITY_AREAS' => '108',
        'UF_POSTCODE' => '110',
        'UF_CITY' => '111',
        'UF_COUNTRY' => '112',
        'UF_COUNTRY_OTHER' => '305',
        'UF_NAME' => '113',
        'UF_SURNAME' => '114',
        'UF_POSITION' => '115',
        'UF_PHONE' => '116',
        'UF_MOBILE' => '569',
        'UF_SKYPE' => '596',
        'UF_EMAIL' => '117',
        'UF_SITE' => '119',
        'UF_LOGIN' => '132',
        'UF_PASSWORD' => '133',
        'UF_DESCRIPTION' => '135',
        'UF_NORTH_AMERICA' => '136',
        'UF_EUROPE' => '137',
        'UF_SOUTH_AMERICA' => '138',
        'UF_AFRICA' => '139',
        'UF_ASIA' => '475',
        'UF_OCEANIA' => '476',
        'UF_MORNING' => '481',
        'UF_EVENING' => '482',
        'UF_PHOTO' => '494',
        'UF_PHOTO_MORNING' => '495',
        'UF_ROOM' => '570',
        'UF_TABLE' => '571',
        'UF_HOTEL' => '666',
        'UF_SALUTATION' => '660',
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
            $question['SID'] = $arQuestion['VARNAME'];
            $question['VALUE'] = $arQuestion['VALUE'];

            $rsAnswers = \CFormAnswer::GetList($arQuestion['ID']);
            while ($arAnswer = $rsAnswers->Fetch())
            {
                $question['ANSWERS'][$arAnswer['ID']] = ['ID' => $arAnswer['ID'], 'MESSAGE' => $arAnswer['MESSAGE'], 'FIELD_TYPE' => $arAnswer['FIELD_TYPE']];
            }
            $arQuestions[$arQuestion['ID']] = $question;
        }
        return $arQuestions;
    }

    public function getUserByResultId($resultId)
    {
        $arUserFilter = array(
            'ACTIVE' => 'Y',
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

    public function getActiveResultData($resultId)
    {
        $rsFormResult = \CFormResult::GetByID($resultId);

        if ($rsFormResult && $rsFormResult->SelectedRowsCount() > 0) {
            $resultData = [];

            \CForm::GetResultAnswerArray(
                self::WORKING_FORM,
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
                            $answer[$v[$k1]['FIELD_ID']] = $v[$k1][$propAnswer];
                        }
                    }

                }
            }

            $arFields = [];
            foreach(self::HL2FORM_ACTIVE_MAPPING as $k => $v)
            {
                if(isset($answer[$v]))
                {
                    $arFields[$k] = $answer[$v];
                }
            }
            if(!empty($arFields['UF_PRIORITY_AREAS']) && !is_array($arFields['UF_PRIORITY_AREAS']))
            {
                $arFields['UF_PRIORITY_AREAS'] = [$arFields['UF_PRIORITY_AREAS']];
            }
            if(!is_array($arFields['UF_NORTH_AMERICA']))
            {
                $arFields['UF_NORTH_AMERICA'] = [$arFields['UF_NORTH_AMERICA']];
            }
            if(!is_array($arFields['UF_EUROPE']))
            {
                $arFields['UF_EUROPE'] = [$arFields['UF_EUROPE']];
            }
            if(!is_array($arFields['UF_SOUTH_AMERICA']))
            {
                $arFields['UF_SOUTH_AMERICA'] = [$arFields['UF_SOUTH_AMERICA']];
            }
            if(!is_array($arFields['UF_AFRICA']))
            {
                $arFields['UF_AFRICA'] = [$arFields['UF_AFRICA']];
            }
            if(!is_array($arFields['UF_ASIA']))
            {
                $arFields['UF_ASIA'] = [$arFields['UF_ASIA']];
            }
            if(!is_array($arFields['UF_OCEANIA']))
            {
                $arFields['UF_OCEANIA'] = [$arFields['UF_OCEANIA']];
            }

            $colleagues = [];

            if (!empty($answer[120])) {
                $colleague = [
                    'UF_NAME' => trim($answer[120]),
                    'UF_SURNAME' => trim($answer[121]),
                    'UF_JOB_TITLE' => $answer[122],
                    'UF_EMAIL' => $answer[123],
                    'UF_SALUTATION' => $answer[661],
                    'UF_DAYTIME' => [self::EVENING_VAL],
                ];
                if(!isset($colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']])) $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']] = $colleague;
            }
            if (!empty($answer[124])) {
                $colleague = [
                    'UF_NAME' => trim($answer[124]),
                    'UF_SURNAME' => trim($answer[125]),
                    'UF_JOB_TITLE' => $answer[127],
                    'UF_EMAIL' => $answer[126],
                    'UF_SALUTATION' => $answer[662],
                    'UF_DAYTIME' => [self::EVENING_VAL],
                ];
                if(!isset($colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']])) $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']] = $colleague;
            }
            if (!empty($answer[128])) {
                $colleague = [
                    'UF_NAME' => trim($answer[128]),
                    'UF_SURNAME' => trim($answer[129]),
                    'UF_JOB_TITLE' => $answer[130],
                    'UF_EMAIL' => $answer[131],
                    'UF_SALUTATION' => $answer[663],
                    'UF_DAYTIME' => [self::EVENING_VAL],
                ];
                if(!isset($colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']])) $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME']] = $colleague;
            }
            if (!empty($answer[477])) {
                $colleague = [
                    'UF_NAME' => trim($answer[477]),
                    'UF_SURNAME' => trim($answer[478]),
                    'UF_JOB_TITLE' => $answer[479],
                    'UF_EMAIL' => $answer[480],
                    'UF_SALUTATION' => $answer[665],
                    'UF_PHOTO' => $answer[495],
                    'UF_DAYTIME' => [self::MORNING_VAL],
                ];
                if (!isset($colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']])) {
                    $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']] = $colleague;
                } else {
                    $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']]['UF_DAYTIME'][] = self::MORNING_VAL;
                }
                $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']]['UF_DAYTIME'] = array_unique($colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']]['UF_DAYTIME']);
            }

            $resultData['fields'] = $arFields;
            $resultData['colleagues'] = $colleagues;

            return $resultData;
        }

        return false;
    }

    public function getResultData($resultId)
    {
        $rsFormResult = \CFormResult::GetByID($resultId);

        if ($rsFormResult && $rsFormResult->SelectedRowsCount() > 0) {
            $arFormResult = $rsFormResult->Fetch();
            $resultData = [];

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
                            $answer[$v[$k1]['FIELD_ID']] = $v[$k1][$propAnswer];
                        }
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
            if(!is_array($arFields['UF_PRIORITY_AREAS']))
            {
                $arFields['UF_PRIORITY_AREAS'] = [$arFields['UF_PRIORITY_AREAS']];
            }
            if(!is_array($arFields['UF_NORTH_AMERICA']))
            {
                $arFields['UF_NORTH_AMERICA'] = [$arFields['UF_NORTH_AMERICA']];
            }
            if(!is_array($arFields['UF_EUROPE']))
            {
                $arFields['UF_EUROPE'] = [$arFields['UF_EUROPE']];
            }
            if(!is_array($arFields['UF_SOUTH_AMERICA']))
            {
                $arFields['UF_SOUTH_AMERICA'] = [$arFields['UF_SOUTH_AMERICA']];
            }
            if(!is_array($arFields['UF_AFRICA']))
            {
                $arFields['UF_AFRICA'] = [$arFields['UF_AFRICA']];
            }
            if(!is_array($arFields['UF_ASIA']))
                {
                $arFields['UF_ASIA'] = [$arFields['UF_ASIA']];
            }
            if(!is_array($arFields['UF_OCEANIA']))
            {
                $arFields['UF_OCEANIA'] = [$arFields['UF_OCEANIA']];
            }

            $colleagues = [];
            if (!empty($answer[628])) {
                $colleague = [
                    'UF_NAME' => trim($answer[628]),
                    'UF_SURNAME' => trim($answer[629]),
                    'UF_JOB_TITLE' => $answer[630],
                    'UF_EMAIL' => $answer[631],
                    'UF_SALUTATION' => $answer[668],
                    'UF_DAYTIME' => [self::EVENING_VAL],
                ];
                if(!isset($colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']])) $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME']] = $colleague;
            }
            if (!empty($answer[632])) {
                $colleague = [
                    'UF_NAME' => trim($answer[632]),
                    'UF_SURNAME' => trim($answer[633]),
                    'UF_JOB_TITLE' => $answer[635],
                    'UF_EMAIL' => $answer[634],
                    'UF_SALUTATION' => $answer[669],
                    'UF_DAYTIME' => [self::EVENING_VAL],
                ];
                if(!isset($colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']])) $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME']] = $colleague;
            }
            if (!empty($answer[636])) {
                $colleague = [
                    'UF_NAME' => trim($answer[636]),
                    'UF_SURNAME' => trim($answer[637]),
                    'UF_JOB_TITLE' => $answer[638],
                    'UF_EMAIL' => $answer[639],
                    'UF_SALUTATION' => $answer[670],
                    'UF_DAYTIME' => [self::EVENING_VAL],
                ];
                if(!isset($colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']])) $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME']] = $colleague;
            }
            if (!empty($answer[650])) {
                $colleague = [
                    'UF_NAME' => trim($answer[650]),
                    'UF_SURNAME' => trim($answer[651]),
                    'UF_JOB_TITLE' => $answer[652],
                    'UF_EMAIL' => $answer[653],
                    'UF_SALUTATION' => $answer[672],
                    'UF_PHOTO' => $answer[657],
                    'UF_DAYTIME' => [self::MORNING_VAL],
                ];
                if (!isset($colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']])) {
                    $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']] = $colleague;
                } else {
                    $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']]['UF_DAYTIME'][] = self::MORNING_VAL;
                }
                $colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']]['UF_DAYTIME'] = array_unique($colleagues[$colleague['UF_NAME'].' '.$colleague['UF_SURNAME'].' '.$colleague['UF_EMAIL']]['UF_DAYTIME']);
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
            ($by="id"),
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