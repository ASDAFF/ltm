<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
//echo "<pre>" . print_r($_REQUEST, true) . "</pre>";

if(!isset($_REQUEST["exhibID"]) || !isset($_REQUEST["userID"]) || !isset($_REQUEST["SID"]) || !check_bitrix_sessid("SID")) //���� ������ � ������ ��� ������
{
	echo "ERROR";
}

$exhibID = str_code(base64_decode($_REQUEST["exhibID"]), "luxoran");
$userID = str_code(base64_decode($_REQUEST["userID"]), "luxoran");

//��������� id ������ ���������������� ������������� �� ������ ��������

if($exhibID && $userID)
{
	$user = new CUser();

	CModule::IncludeModule("iblock");

	$rsExhib = CIBlockElement::GetByID($exhibID);
	$obExhib = $rsExhib->GetNextElement();
	$arProps = $obExhib->GetProperty("UC_PARTICIPANTS_GROUP");

	$ucExhibGroupID = $arProps["VALUE"];

	$arUserGroups = $user->GetUserGroup($userID);

	//�������� �������

	//��������� ������ ������������

	$rsUser = $user->GetByID($userID);
	$arUser = $rsUser->Fetch();


	$arEventFields = array(
	    "LOGIN"            => $arUser["LOGIN"],
	    "MAIL"             => $arUser["EMAIL"],
	    "COMP_NAME"        => $arUser["WORK_COMPANY"],
	    "PASSWORD"         => $aruser["UF_PAS"]
	);

	$sendType;
	switch ($exhibID)
	{
		case "361" : $sendType = "REG_NEW_E_MOSSP"; break; //������, ������. 13 ����� 2014
		case "360" : $sendType = "REG_NEW_E_KIEV"; break; //����, �������. 23 �������� 2014
		case "357" : $sendType = "REG_NEW_E_BAK"; break; //����, �����������. 10 ������ 2014
		case "359" : $sendType = "REG_NEW_E_ALM"; break; //������, ���������. 26 �������� 2014
		case "358" : $sendType = "REG_NEW_E_MOSOT"; break; //������, ������. 2 ������� 2014
		case "488" : $sendType = "REG_NEW_E_MOSSP15"; break; //������, ������. 12 ����� 2015
		case "3521" : $sendType = "REG_NEW_E_ALM15"; break; //������, ���������. 2015
		case "3522" : $sendType = "REG_NEW_E_KIEV15"; break; //����, �������. 22 �������� 2015
		case "3523" : $sendType = "REG_NEW_E_MOSOT15"; break; //������, ������ 2015
	}

	if($ucExhibGroupID && !array_search($ucExhibGroupID, $arUserGroups))
	{
	    $arUserGroups[] = $ucExhibGroupID;
	    $user->SetUserGroup($userID, $arUserGroups);

	    if($sendType && !empty($arEventFields))
	    {
	        CEvent::Send($sendType, 's1', $arEventFields); 
	    }
	    
	    #�������� �������� �������� � ������� 
	    copyExhDataToDefault($userID);
	    echo "OK";
	}
	else
	{
		echo "ERROR: GROUP";
	}

}

function copyExhDataToDefault($userId)
{
	global $USER;

	if(!is_object($USER))
	{
		$USER = new CUser();
	}

	#������� ��������� �������� � ������� ����������� ������������
	CModule::IncludeModule("iblock");
	CModule::IncludeModule("form");

	$arUserGroup = $USER->GetUserGroup($userId);
	$arUser = $USER->GetByID($userId)->Fetch();

	$arExhibFilter = array(
			"IBLOCK_ID" => 15,
			"PROPERTY_USER_GROUP_ID" => $arUserGroup,
	);
	$arSelect = array(
			"ID",
			"IBLOCK",
			"PROPERTY_USER_GROUP_ID"
	);



	$rsExhib = CIBlockElement::GetList(array("sort" => "desc"), $arExhibFilter, false, array("nTopCount" => 1), $arSelect);
	if($arExhib = $rsExhib->Fetch())
	{
		#�������� ������ ������� �� ��� ��������
		#id �����
		$formID = CFormMatrix::getPFormIDByExh($arExhib["ID"]);
		$formPropName = CFormMatrix::getPropertyIDByExh($arExhib["ID"]);//��������� ����� �������� ������������ ��� ������� ��������

		#�������� ���� ����������
		$resultId = $arUser[$formPropName];
		$baseResultID = $arUser["UF_ID"];

		$FieldSID = array(
				"NAME" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_446",$formID),//Participant first name
				"LAST_NAME" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_551",$formID),//Participant last name
				"JOB_TITLE" =>CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_729",$formID),//Job title
				"PHONE" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_394",$formID),//Telephone
				"EMAIL" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_859",$formID),//E-mail
				"EMAIL_CONF" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_585",$formID),//Please confirm your e-mail
				"EMAIL_ALT" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_749",$formID),//Alternative e-mail
				"PHOTO" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_575",$formID),//������������ ����
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
				$arAnswerSID);

		$newArAnswerSID = array();

		foreach ($FieldSID as $name => $sid)
		{
			if(isset($arAnswerSID[$sid]))
			{
				$resName = "";
				$tmp = reset($arAnswerSID[$sid]);
				switch ($tmp["FIELD_TYPE"])
				{
					case "dropdown" : $resName = "ANSWER_ID";break;
					case "image" : $resName = "USER_FILE_ID"; break;
					case "text" : $resName = "USER_TEXT"; break;
				}

				$newArAnswerSID[$name] = $tmp[$resName];
			}
		}

		/*��������� ������ ��� �������� ���������*/
		$arPersonalFormFields = array(
				"form_text_84" => $newArAnswerSID["NAME"], 								//Participant first name
				"form_text_85" => $newArAnswerSID["LAST_NAME"], 						//Participant last name
				"form_text_87" => $newArAnswerSID["JOB_TITLE"], 						//Job title
				"form_text_88" => $newArAnswerSID["PHONE"], 							//Telephone
				"form_text_89" => $newArAnswerSID["EMAIL"],								//E-mail
				"form_text_90" => $newArAnswerSID["EMAIL_CONF"],						//Please confirm your e-mail
				"form_text_91" => $newArAnswerSID["EMAIL_ALT"],							//Alternative e-mail
				"form_dropdown_SIMPLE_QUESTION_889" =>
				CFormMatrix::getAnswerSalutationBase($newArAnswerSID["SALUTATION"],$formID),	//Salutation
				"form_image_195" => Cfile::MakeFileArray($newArAnswerSID["PHOTO"]),		//������������ ����
		);

		//��������� ���������
		CFormResult::Update($baseResultID, $arPersonalFormFields, "N", "N");
	}
}
?>