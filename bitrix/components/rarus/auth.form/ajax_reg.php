<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
//echo "<pre>" . print_r($_REQUEST, true) . "</pre>";

if(!isset($_REQUEST["exhibID"]) || !isset($_REQUEST["userID"]) || !isset($_REQUEST["SID"]) || !check_bitrix_sessid("SID")) //если ошибки в сессии или данных
{
	echo "ERROR";
}

$exhibID = str_code(base64_decode($_REQUEST["exhibID"]), "luxoran");
$userID = str_code(base64_decode($_REQUEST["userID"]), "luxoran");

//получение id Группы неподтвержденных пользователей на данную выставку

if($exhibID && $userID)
{
	$user = new CUser();

	CModule::IncludeModule("iblock");

	$rsExhib = CIBlockElement::GetByID($exhibID);
	$obExhib = $rsExhib->GetNextElement();
	$arProps = $obExhib->GetProperty("UC_PARTICIPANTS_GROUP");

	$ucExhibGroupID = $arProps["VALUE"];

	$arUserGroups = $user->GetUserGroup($userID);

	//почтовые события

	//получение данных пользователя

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
		case "361" : $sendType = "REG_NEW_E_MOSSP"; break; //Москва, Россия. 13 марта 2014
		case "360" : $sendType = "REG_NEW_E_KIEV"; break; //Киев, Украина. 23 сентября 2014
		case "357" : $sendType = "REG_NEW_E_BAK"; break; //Баку, Азербайджан. 10 апреля 2014
		case "359" : $sendType = "REG_NEW_E_ALM"; break; //Алматы, Казахстан. 26 сентября 2014
		case "358" : $sendType = "REG_NEW_E_MOSOT"; break; //Москва, Россия. 2 октября 2014
		case "488" : $sendType = "REG_NEW_E_MOSSP15"; break; //Москва, Россия. 12 марта 2015
		case "3521" : $sendType = "REG_NEW_E_ALM15"; break; //Алматы, Казахстан. 2015
		case "3522" : $sendType = "REG_NEW_E_KIEV15"; break; //Киев, Украина. 22 сентября 2015
		case "3523" : $sendType = "REG_NEW_E_MOSOT15"; break; //Москва, Россия 2015
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