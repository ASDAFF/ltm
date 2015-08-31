<?
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class CHLMFunctions
{
    function GetUserName($USER_ID)
    {
        $ar_res = false;
        if (IntVal($USER_ID)>0)
        {
            $db_res = CUser::GetByID(IntVal($USER_ID));
            $ar_res = $db_res->Fetch();
        }

        if (!$ar_res)
        {
            $db_res = CUser::GetByLogin($USER_ID);
            $ar_res = $db_res->Fetch();
        }

        $USER_ID = IntVal($ar_res["ID"]);
        $f_LOGIN = htmlspecialcharsex($ar_res["LOGIN"]);


        if ((strlen(trim($ar_res["NAME"]))>0 || strlen(trim($ar_res["LAST_NAME"]))>0))
        {
            return trim(htmlspecialcharsex($ar_res["NAME"])." ". htmlspecialcharsex($ar_res["LAST_NAME"])." Company: ". htmlspecialcharsex($ar_res["WORK_COMPANY"]));
        }
        else
            return $f_LOGIN;
    }

    function GetUserInfoForm($USER_ID, $arExhib)
    {
        $arUserGroups = CUser::GetUserGroup($USER_ID);
        $rsUser = CUser::GetByID($USER_ID);
        $arUser = $rsUser->Fetch();

        if(in_array($arExhib["PROPERTY_USER_GROUP_ID_VALUE"], $arUserGroups))//участник
        {
            return self::GetParticipantInfo($arUser, $arExhib);
        }

        if(in_array($arExhib["PROPERTY_C_GUESTS_GROUP_VALUE"], $arUserGroups))//гость
        {
            return self::GetGuestInfo($arUser, $arExhib);
        }
        
        if(1 == $USER_ID)
        {
        	return $arUser;
        }

    }

    private function GetParticipantInfo($arUser, $arExhib)
    {
        CModule::IncludeModule("form");
        $formID = 3;//форма компании представители всех выставок
        $companyResultID = $arUser["UF_ID_COMP"];

        //получение названия компании
        $arAnswer = CFormResult::GetDataByID(
            $companyResultID,
            array(
                "SIMPLE_QUESTION_163",// описание компании
                "SIMPLE_QUESTION_988"//название компании
            ),
            $arResult,
            $arAnswerDescr
            );
        $arAnswerCompName = reset($arAnswerDescr["SIMPLE_QUESTION_988"]);
        $compName = $arAnswerCompName["USER_TEXT"];

        //получаем данные пользователя
        $formID = CFormMatrix::getPFormIDByExh($arExhib["ID"]);
        $formPropName = CFormMatrix::getPropertyIDByExh($arExhib["ID"]);//получение имени свойства пользователя для текущей выставки
        $resultId = $arUser[$formPropName];

        $FieldSID = array(
            "NAME" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_446",$formID),//Participant first name
            "LAST_NAME" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_551",$formID),//Participant last name
            "JOB_TITLE" =>CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_729",$formID),//Job title
            "PHONE" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_394",$formID),//Telephone
            "EMAIL" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_859",$formID),//E-mail
            "EMAIL_CONF" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_585",$formID),//Please confirm your e-mail
            "EMAIL_ALT" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_749",$formID),//Alternative e-mail
            "PHOTO" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_575",$formID),//Персональное фото
            "SALUTATION" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_889",$formID),//Salutation
        );

        $arAnswer = CFormResult::GetDataByID(
            $resultId,
            array(
                $FieldSID["NAME"],
                $FieldSID["LAST_NAME"],
                $FieldSID["JOB_TITLE"],
                $FieldSID["PHONE"],
                $FieldSID["EMAIL"],
                $FieldSID["EMAIL_CONF"],
                $FieldSID["EMAIL_ALT"],
                $FieldSID["PHOTO"],
                $FieldSID["SALUTATION"],
            ),
            $arResult,
            $arAnswerSID
            );

        $userFields = array();

        foreach ($FieldSID as $name => $sid)
        {
            if(isset($arAnswerSID[$sid]))
            {
                $resName = "";
                $tmp = reset($arAnswerSID[$sid]);
                switch ($tmp["FIELD_TYPE"])
                {
                    case "dropdown" : $resName = "ANSWER_TEXT";break;
                    case "image" : $resName = "USER_FILE_ID"; break;
                    case "text" : $resName = "USER_TEXT"; break;
                }

                ;
                $userFields[$name] = $tmp[$resName];
            }
        }

        $userFields["COMPANY_NAME"] = $compName;
        return  $userFields;
    }

    private function GetGuestInfo($arUser, $arExhib)
    {
        CModule::IncludeModule("form");
        $formID = 10; //одна форма для всех гостей
        $formPropName = CFormMatrix::getPropertyIDByExh($arExhib["ID"]);//получение имени свойства пользователя для текущей выставки
        $userResultID = $arUser[$formPropName];

               //получение результата заполнени формы пользователя
        $arResultAnswerUser = array("RESULTS"=>array(), "QUESTIONS"=>array(), "ANSWERS"=>array(), "ANSWERS2"=>array());

        CForm::GetResultAnswerArray(
        $formID,
        $arResultAnswerUser["QUESTIONS"],
        $arResultAnswerUser["ANSWERS"],
        $arResultAnswerUser["ANSWERS2"],
        array("RESULT_ID" => $userResultID)
        );

        $arProfile = array();
        $arUserAnswer = $arResultAnswerUser["ANSWERS"][$userResultID];

        //заполненные данные из фомры
        $arProfile["NAME"] = $arUserAnswer[113][216]["USER_TEXT"];//FIELD_ID 113 , ANSWER_ID 216, TITLE => Имя
        $arProfile["LAST_NAME"] = $arUserAnswer[114][217]["USER_TEXT"];//FIELD_ID 114 , ANSWER_ID 217, TITLE => Фамилия
        $arProfile["COMPANY_NAME"] = $arUserAnswer[107][204]["USER_TEXT"];//FIELD_ID 107 , ANSWER_ID 204, TITLE => Название компании
        $arProfile["EMAIL"] = $arUserAnswer[117][220]["USER_TEXT"];//FIELD_ID 117 , ANSWER_ID 220, TITLE => Е-mail

        return $arProfile;
    }

    function GetUserCompany($USER_ID)
    {
        $ar_res = false;
        if (IntVal($USER_ID)>0)
        {
            $db_res = CUser::GetByID(IntVal($USER_ID));
            $ar_res = $db_res->Fetch();
        }

        if (!$ar_res)
        {
            $db_res = CUser::GetByLogin($USER_ID);
            $ar_res = $db_res->Fetch();
        }

        $USER_ID = IntVal($ar_res["ID"]);
        $f_LOGIN = htmlspecialcharsex($ar_res["LOGIN"]);

        if (strlen(trim($ar_res["WORK_COMPANY"]))>0)
        {
            return trim(htmlspecialcharsex($ar_res["WORK_COMPANY"]));
        }
        else
            return $f_LOGIN;
    }


    function GetUserCompanyID($USER_ID)
    {
        $ar_res = false;
        if (!empty($USER_ID) && IntVal($USER_ID)>0)
        {
            $db_res = CUser::GetByID($USER_ID);
        }
        else if (!empty($USER_ID) && IntVal($USER_ID)==0)
        {
            $db_res = CUser::GetByLogin($USER_ID);
        }

        if($db_res->SelectedRowsCount()>0)
        {
            if($ar_res = $db_res->Fetch())
            {
                $USER_ID = IntVal($ar_res["ID"]);
                $f_LOGIN = htmlspecialcharsex($ar_res["LOGIN"]);
                return trim(htmlspecialcharsex($ar_res['UF_ID_COMP']));
            }
        }
    }

    function GetEXhibByCode($code)
    {
        if(strlen(trim($code)) <= 0)
        {
            return null;
        }
        Cmodule::IncludeModule("iblock");

        $rsElement = CIBlockElement::GetList(array(), array("CODE"=>$code), false, array("nTopCount"=>1), array("ID","IBLOCK_ID", "CODE", "NAME", "PROPERTY_USER_GROUP_ID", "PROPERTY_UC_PARTICIPANTS_GROUP", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_UC_GUESTS_GROUP", "PROPERTY_SHORT_NAME"));
        if($arElement = $rsElement->Fetch())
        {
            return $arElement;
        }

    }

    function GetEXhibByID($id)
    {
        if(intval($id) <= 0)
        {
            return null;
        }
        Cmodule::IncludeModule("iblock");

        $rsElement = CIBlockElement::GetList(array(), array("ID"=>$id), false, array("nTopCount"=>1), array("ID","IBLOCK_ID", "CODE", "NAME", "PROPERTY_USER_GROUP_ID", "PROPERTY_UC_PARTICIPANTS_GROUP", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_UC_GUESTS_GROUP", "PROPERTY_SHORT_NAME"));
        if($arElement = $rsElement->Fetch())
        {
            return $arElement;
        }

    }

    function GetMessagesCount($hlid = 2, $EID, $UID, $FID, $bIsRead = false)
    {
        // начало ********************* highloadblock init ***************************************

        $hlblock = HL\HighloadBlockTable::getById($hlid)->fetch();
        // получаем сущность
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);

        // конец ********************* highloadblock init *****************************************

        $filter = array(
            "UF_EXHIBITION" => intval($EID),
            "UF_FOLDER" =>intval($FID),
            "UF_IS_READ" => $bIsRead,
        );

        if($FID == 3)//входящие
        {
            $filter["UF_RECIPIENT"] = $UID;
        }
        elseif ($FID == 4)
        {
            $filter["UF_AUTHOR"] = $UID;
        }

        $countQuery = new Entity\Query($entity);
        $countQuery->setSelect(array('CNT'=>array('expression' => array('COUNT(1)'), 'data_type'=>'integer')));
        $countQuery->setFilter($filter);
        $totalCount = $countQuery->setLimit(null)->setOffset(null)->exec()->fetch();
        $totalCount = intval($totalCount['CNT']);

        return $totalCount;
    }

}

?>