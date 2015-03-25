<?
$arConfirmUsersID = $_REQUEST["ACTION"];

$answerid = array("text", "textarea", "password", "date", "file", "image", "hidden");
$answersid = array("dropdown", "checkbox", "multiselect", "radio");

$skipFields = array(
		"SIMPLE_QUESTION_732", 		//���
		"SIMPLE_QUESTION_148", 		//����
		"SIMPLE_QUESTION_539", 		//����� �����
		"SIMPLE_QUESTION_680",		//����� �����
		"SIMPLE_QUESTION_667"		//���������
);

foreach($arConfirmUsersID as $userID)
{
//pre($userID);
	//�������� ������ ������������
    $arGroups = CUser::GetUserGroup($userID);

    $groupConfirmID = $arResult["EXHIBITION"]["PROPERTIES"]["USER_GROUP_ID"]["VALUE"];
    $groupUConfirmID = $arResult["EXHIBITION"]["PROPERTIES"]["UC_PARTICIPANTS_GROUP"]["VALUE"];

    if(!$groupConfirmID || !$groupUConfirmID)//����� ���� �� ������ ������ ����������
    {
    	break;
    }

    $needUpdate = false;
    foreach ($arGroups as $index => $group)//��������� ������������ �� ������ ���������������� �������������� � ��������������
    {
    	if($group == $groupUConfirmID)
    	{
    		unset($arGroups[$index]);
    		$arGroups[] = $groupConfirmID;

    		$needUpdate = true;
    	}
    }

    $memberOneID = CFormMatrix::getPropertyIDByExh($arResult["EXHIBITION"]["ID"],0);
    $memberTwoID = CFormMatrix::getPropertyIDByExh($arResult["EXHIBITION"]["ID"],1);

    $arUser = $arResult["EXHIBITION"]["PARTICIPANT"][$userID];
    $formID = CFormMatrix::getPFormIDByExh($arResult["EXHIBITION"]["ID"]);

    if(empty($arUser["FORM_USER"]))
    {
    	continue;
    }


	//���� ����������� ��������� �������� �� ������ ��������, �� ��������� ������������ � ������ ��� ��� �������
    if(empty($arUser[$memberOneID]))
    {
        $arNewFields = array();

        foreach ($arUser["FORM_USER"] as $question)//���������� ������� ��� �����
        {
        	if(in_array($question["SID"], $skipFields))
        	{
        		continue;
        	}
        	
        	
            $fieldType = "";
            if(in_array($question["FIELD_TYPE"], $answerid))
            {
                $fieldType = "ANSWER_ID";
            }
            elseif(in_array($question["FIELD_TYPE"], $answersid))
            {
                $fieldType = "SID";
            }
            else
            {
            	break;
            }

            $fieldName = "form_" . $question["FIELD_TYPE"] . "_" . CFormMatrix::getAnswerRelBase($question[$fieldType], $formID);


            if("image" == $question["FIELD_TYPE"])
            {
                $value = CFile::MakeFileArray($question["VALUE"]);
            }
            elseif("dropdown" == $question["FIELD_TYPE"] && $question["SID"] == "SIMPLE_QUESTION_889")
            {
                $value = CFormMatrix::getAnswerSalutationRelBase($question["ANSWER_ID"], $formID);
            }
            elseif("radio" == $question["FIELD_TYPE"] && $question["SID"] == "SIMPLE_QUESTION_667")
            {
                $value = CFormMatrix::getIndexRequisiteRelBase($question["ANSWER_ID"], $formID);
            }
            else
            {
                $value = $question["VALUE"];
            }

            $arNewFields[$fieldName] = $value;

        }
        
       if(!empty($arNewFields))
       {
           $resMemberOneID = CFormResult::Add($formID, $arNewFields);
           $arUserFields = array($memberOneID => $resMemberOneID);

           $b = $USER->Update($userID, $arUserFields);
       }
       
       //��������� ������� ������������ ������ ��������� ��������
       $resMemberTwoID = CFormResult::Add($formID);
       $USER->Update($userID, array($memberTwoID => $resMemberTwoID));

       if($needUpdate && $resMemberOneID && $resMemberTwoID)
       {
           CUser::SetUserGroup($userID, $arGroups);
       }

    }//���� ��������� ����, �� ��� ���������� �������, ��������� ������ ��������� �������
    elseif(empty($arUser[$memberTwoID]))
    {
    	//��������� ������� ������������ ������ ��������� ��������
        $resMemberTwoID = CFormResult::Add($formID);
        $USER->Update($userID, array($memberTwoID => $resMemberTwoID));

        if($needUpdate && $resMemberTwoID)
        {
            CUser::SetUserGroup($userID, $arGroups);
        }
    }//���� ���� ��� ����������, ������ ���������� ������������ � ����� �������������� ������
    else
    {
        CUser::SetUserGroup($userID, $arGroups);
    }

    //��������� � ������������ ������ � �������
       $name = $arUser["FORM_USER"][32]["VALUE"]; //32 id Participant first name
       $lastName = $arUser["FORM_USER"][33]["VALUE"]; //33 id Participant last name
       $workCompany = $arUser["FORM_DATA"][17]["VALUE"]; //17 id Company or hotel name

	   $arUserFields = array(
           "NAME" => $name,
           "LAST_NAME" => $lastName,
           "WORK_COMPANY" => $workCompany,
		   "UF_FIO" => $name.' '.$lastName
       );
       $b = $USER->Update($userID, $arUserFields);
}

$url = $APPLICATION->GetCurPageParam("", array("ACTION","CONFIRM","EXHIBIT_CODE", "SPAM", "SPAM_TYPE"));

LocalRedirect($url);

?>