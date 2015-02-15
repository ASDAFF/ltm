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
	    echo "OK";
	}
	else
	{
		echo "ERROR: GROUP";
	}

}
?>