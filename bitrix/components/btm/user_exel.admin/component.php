<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/

$arResult["ERROR_MESSAGE"] = "";
$arResult["MESSAGE"] = "";

if(strLen($arParams["PATH_TO_KAB"])<=0){
	$arParams["PATH_TO_KAB"] = "/admin/";
}

if(strLen($arParams["GROUP_ID"])<=0){
	$arParams["GROUP_ID"] = "1";
}

if(strLen($arParams["AUTH_PAGE"])<=0){
	$arParams["AUTH_PAGE"] = "/admin/login.php";
}

if(strLen($arParams["GUEST_OFF"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ���������������� ������!<br />";
}

if(strLen($arParams["PARTICIP_OFF"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ���������������� ����������!<br />";
}

if(strLen($arParams["GUEST_ACCEPT"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ������!<br />";
}

if(strLen($arParams["PARTICIP_ACCEPT"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ����������!<br />";
}

if(strLen($arParams["GUEST_SPAM"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ������ ��� ����� ������!<br />";
}

if(strLen($arParams["PARTICIP_SPAM"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ������ ��� ����� ����������!<br />";
}

if(strLen($arParams["GUEST_FORM_ID"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ����������� ������!<br />";
}

if(strLen($arParams["PARTICIP_FORM_ID"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ����������� ����������!<br />";
}

if(!($USER->IsAuthorized()))
{
	LocalRedirect($arParams["AUTH_PAGE"]);
}

/*---------------------------------------------------*/
//           ������ ������������ ������              //
/*---------------------------------------------------*/
$userExel = array();
	//�������������� ���������
	$userExel[0]["USER"] = $arParams["PARTICIP_ACCEPT"];
	$userExel[0]["FORM"] = $arParams["PARTICIP_FORM_ID"];
	$userExel[0]["TYPE"] = "PARTICIP";
	$userExel[0]["STATUS"] = "ON";

	//���������������� ���������
	$userExel[1]["USER"] = $arParams["PARTICIP_OFF"];
	$userExel[1]["FORM"] = $arParams["PARTICIP_FORM_ID"];
	$userExel[1]["TYPE"] = "PARTICIP";
	$userExel[1]["STATUS"] = "OFF";

	//���� ���������
	$userExel[2]["USER"] = $arParams["PARTICIP_SPAM"];
	$userExel[2]["FORM"] = $arParams["PARTICIP_FORM_ID"];
	$userExel[2]["TYPE"] = "PARTICIP";
	$userExel[2]["STATUS"] = "SPAM";

	//��� �������������� ���������
	$userExel[3]["USER"] = $arParams["PARTICIP_ACCEPT"];
	$userExel[3]["FORM"] = $arParams["PARTICIP_FORM_ID"];
	$userExel[3]["TYPE"] = "PARTICIP";
	$userExel[3]["STATUS"] = "ALL";

	//�������������� ����� ����
	$userExel[4]["USER"] = $arParams["GUEST_ACCEPT"];
	$userExel[4]["FORM"] = $arParams["GUEST_FORM_ID"];
	$userExel[4]["TYPE"] = "GUEST";
	$userExel[4]["STATUS"] = "MORNING";

	//���������������� �����
	$userExel[5]["USER"] = $arParams["GUEST_OFF"];
	$userExel[5]["FORM"] = $arParams["GUEST_FORM_ID"];
	$userExel[5]["TYPE"] = "GUEST";
	$userExel[5]["STATUS"] = "OFF";

	//�������������� ����� �����
	$userExel[6]["USER"] = $arParams["GUEST_EVENING"];
	$userExel[6]["FORM"] = $arParams["GUEST_FORM_ID"];
	$userExel[6]["TYPE"] = "GUEST";
	$userExel[6]["STATUS"] = "EVENING";

	//�������������� ����� Hosted Buyers
	$userExel[7]["USER"] = $arParams["GUEST_HB"];
	$userExel[7]["FORM"] = $arParams["GUEST_FORM_ID"];
	$userExel[7]["TYPE"] = "GUEST";
	$userExel[7]["STATUS"] = "HB";

	//��� �������������� ����� ����
	$userExel[8]["USER"] = $arParams["GUEST_ACCEPT"];
	$userExel[8]["FORM"] = $arParams["GUEST_FORM_ID"];
	$userExel[8]["TYPE"] = "GUEST";
	$userExel[8]["STATUS"] = "ALL_MORNING";

	//��� �������������� ����� �����
	$userExel[9]["USER"] = $arParams["GUEST_EVENING"];
	$userExel[9]["FORM"] = $arParams["GUEST_FORM_ID"];
	$userExel[9]["TYPE"] = "GUEST";
	$userExel[9]["STATUS"] = "ALL_EVENING";

	//��� �������������� ����� Hosted Buyers
	$userExel[10]["USER"] = $arParams["GUEST_HB"];
	$userExel[10]["FORM"] = $arParams["GUEST_FORM_ID"];
	$userExel[10]["TYPE"] = "GUEST";
	$userExel[10]["STATUS"] = "ALL_HB";

/*---------------------------------------------------*/
//          �������� ������������ ������             //
/*---------------------------------------------------*/
if(isset($_GET["excel"])){
	switch ($_GET["excel"]){
		case "part_on":
			$curExel = $userExel[0];
			break;
		case "part_of":
			$curExel = $userExel[1];
			break;
		case "part_spam":
			$curExel = $userExel[2];
			break;
		case "part_all":
			$curExel = $userExel[3];
			break;
		case "user_morning":
			$curExel = $userExel[4];
			break;
		case "user_of":
			$curExel = $userExel[5];
			break;
		case "user_evening":
			$curExel = $userExel[6];
			break;
		case "user_hb":
			$curExel = $userExel[7];
			break;
		case "user_morning_all":
			$curExel = $userExel[8];
			break;
		case "user_evening_all":
			$curExel = $userExel[9];
			break;
		case "user_hb_all":
			$curExel = $userExel[10];
			break;
	}
}
else{
	$arResult["ERROR_MESSAGE"] = "�� ������ ��� ������������� Excel!<br />";
}

//print_r($curExel);

/*---------------------------------------------------*/
//           ��������� ����� ��� �������             //
/*---------------------------------------------------*/
if($arResult["ERROR_MESSAGE"] == '')
{
	$userId= $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_ID"], $userGroups)){
		//������ �������������
		$filter = Array(
			"GROUPS_ID"  => Array($curExel["USER"])
		);
		$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_*"))); // �������� �������������
		if($curExel["STATUS"] == "ALL_EVENING" || $curExel["STATUS"] == "EVENING"){
			$rsUsers->NavStart(300); // ��������� ����������� �� 300 �������
			$arResult["NAVIGATE_STR"] = $rsUsers->GetPageNavStringEx($navComponentObject, "������������", "");
		}
                $countUsers = 0;
		$resultFormId = "";
		while($arUsersTemp=$rsUsers->Fetch()){
			$arUsers[$countUsers]["ID"] = $arUsersTemp["ID"];
			$arUsers[$countUsers]["UF_ANKETA"] = $arUsersTemp["UF_ANKETA"];
			$arUsers[$countUsers]["UF_PAY_COUNT"] = $arUsersTemp["UF_PAY_COUNT"];
			$arUsers[$countUsers]["PASS"] = $arUsersTemp["ADMIN_NOTES"];
			$arUsers[$countUsers]["LOGIN"] = $arUsersTemp["LOGIN"];
			$resultFormId .= " | ".$arUsersTemp["UF_ANKETA"];
			$countUsers++;
		}
		$resultFormId = substr($resultFormId, 3);
		$arResult["USERS"]["COUNT"] = $countUsers;

		//���������� �������������
		CForm::GetResultAnswerArray($curExel["FORM"], $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $resultFormId));
		
		//������ ������� ��� �������
		$countColumns = 0;
		$countReal = 0;
		$arResult["FIELDS"]["COUNT"]=0;
		foreach($arrColumns as $columnName){
			$arResult["FIELDS"][$countReal]["ID"] = $columnName["ID"];
			$arResult["FIELDS"][$countReal]["TITLE"] = $columnName["TITLE"];
			$arResult["FIELDS"][$countReal]["OTHER"] = "N";
			if($curExel["TYPE"] == "PARTICIP"){
				if($countReal!=0 && ($columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (other)")){
					$arResult["FIELDS"][$countReal]["OTHER"] = "Y";
				}
				else{
					$countColumns++;
				}
			}
			else{
				if($countReal!=0 && ($columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (������)" || $columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (������)" || $columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (������)" || $columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (������)")){
					$arResult["FIELDS"][$countReal]["OTHER"] = "Y";
				}
				else{
					$countColumns++;
				}
			}
			$countReal++;
		}
		$arResult["FIELDS"]["COUNT"] = $countColumns;
		$realFieldTemp = array();
		$k=0;
		//������ ������������� � ������
		for($i=0; $i<$countUsers; $i++){
			$arResult["USERS"][$i+$k]["ID"] = $arUsers[$i]["ID"];
			$arResult["USERS"][$i+$k]["ANKETA"] = $arUsers[$i]["UF_ANKETA"];
			$arResult["USERS"][$i+$k]["PAY"] = $arUsers[$i]["UF_PAY_COUNT"];
			$arResult["USERS"][$i+$k]["PASS"] = $arUsers[$i]["PASS"];
			$arResult["USERS"][$i+$k]["LOGIN"] = $arUsers[$i]["LOGIN"];
			$CollegeTitle = '';
			$CollegeName = '';
			$CollegeLastName = '';
			$CollegeJob = '';
			$CollegeEmail = '';
			$CollegesAr = array();
			$countCollege = 0;			
			$sdvig = 0;
			for($j=0; $j<$countReal; $j++){
				if($arResult["FIELDS"][$j]["OTHER"] == "Y"){
					$sdvig++;
					$tempMean = "";
					foreach($arrAnswers[$arUsers[$i]["UF_ANKETA"]][$arResult["FIELDS"][$j]["ID"]] as $ansMeaning){
						if($ansMeaning["USER_TEXT"]){
							$tempMean .= ", ".$ansMeaning["USER_TEXT"];
						}
						else{
							$tempMean .= ", ".$ansMeaning["ANSWER_TEXT"];
						}
					}
					$tempMean = trim($tempMean);
					$tempMean = substr($tempMean, 1);
					if($tempMean){
						$arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig] = $tempMean;
					}
				}
				else{
					$arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig] = "";
					foreach($arrAnswers[$arUsers[$i]["UF_ANKETA"]][$arResult["FIELDS"][$j]["ID"]] as $ansMeaning){
						if($ansMeaning["USER_TEXT"]){
							$arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig] .= ", ".$ansMeaning["USER_TEXT"];
						}
						else{
							$arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig] .= ", ".$ansMeaning["ANSWER_TEXT"];
						}
					}
					$arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig] = substr($arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig], 2);
					$realFieldTemp[$j-$sdvig]["ID"] = $arResult["FIELDS"][$j]["ID"];
					if($arParams["USER_TYPE"] == "PARTICIP"){
						if(strpos($arResult["FIELDS"][$j]["TITLE"], "Short company description") !== false){
							$realFieldTemp[$j-$sdvig]["TITLE"] = "Description";
						}
						else{
							$realFieldTemp[$j-$sdvig]["TITLE"] = $arResult["FIELDS"][$j]["TITLE"];
						}
					}
					else{
						if(strpos($arResult["FIELDS"][$j]["TITLE"], "�����") !== false){
							$realFieldTemp[$j-$sdvig]["TITLE"] = "�����";
						}
						elseif(strpos($arResult["FIELDS"][$j]["TITLE"], "�������") !== false){
							$realFieldTemp[$j-$sdvig]["TITLE"] = "�������";
						}
						else{
							$realFieldTemp[$j-$sdvig]["TITLE"] = $arResult["FIELDS"][$j]["TITLE"];
						}
					}
					if($curExel["STATUS"] == "ALL" && $curExel["TYPE"] == "PARTICIP" && substr($arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig], 2)){
						switch($realFieldTemp[$j-$sdvig]["TITLE"]){
							case "Title College":
								$CollegeTitle = $arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig];
								break;
							case "First Name College":
								$CollegeName = $arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig];
								break;
							case "Last Name College":
								$CollegeLastName =$arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig];
								break;
							case "Job Title College":
								$CollegeJob = $arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig];
								break;
							case "Email College":
								$CollegeEmail = $arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig];
								break;
						}
					}
					elseif($curExel["TYPE"] == "GUEST" && ($curExel["STATUS"] == "ALL_MORNING" || $curExel["STATUS"] == "ALL_HB") && substr($arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig], 2)){
						switch($realFieldTemp[$j-$sdvig]["TITLE"]){
							case "��� �������":
								$CollegeName = $arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig];
								break;
							case "������� �������":
								$CollegeLastName =$arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig];
								break;
							case "��������� �������":
								$CollegeJob = $arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig];
								break;
							case "E-mail �������":
								$CollegeEmail = $arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig];
								break;
						}
					}
					elseif($curExel["TYPE"] == "GUEST" && $curExel["STATUS"] == "ALL_EVENING" && substr($arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig], 2)){
						switch($realFieldTemp[$j-$sdvig]["TITLE"]){
							case "��� ������� (�����)":
								if(isset($CollegesAr[$countCollege])){
									$countCollege++;
								}
								$CollegesAr[$countCollege]["NAME"] = $arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig];
								$CollegesAr[$countCollege]["SURNAME"] = "";
								$CollegesAr[$countCollege]["JOB"] = "";
								$CollegesAr[$countCollege]["EMAIL"] = "";
								break;
							case "������� ������� (�����)":
								$CollegesAr[$countCollege]["SURNAME"] = $arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig];
								break;
							case "��������� ������� (�����)":
								$CollegesAr[$countCollege]["JOB"] = $arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig];
								break;
							case "E-mail ������� (�����)":
								$CollegesAr[$countCollege]["EMAIL"] = $arResult["USERS"][$i+$k]["FIELDS"][$j-$sdvig];
								break;
						}
					}
				}
			}
			if($CollegeName && $curExel["TYPE"] == "PARTICIP"){
				$k++;
				$arResult["USERS"][$i+$k]["ID"] = $arUsers[$i]["ID"];
				$arResult["USERS"][$i+$k]["ANKETA"] = $arUsers[$i]["UF_ANKETA"];
				$arResult["USERS"][$i+$k]["PAY"] = $arUsers[$i]["UF_PAY_COUNT"];
				$arResult["USERS"][$i+$k]["FIELDS"] = $arResult["USERS"][$i+$k-1]["FIELDS"];
				$arResult["USERS"][$i+$k]["FIELDS"][4] = $CollegeName;
				$arResult["USERS"][$i+$k]["FIELDS"][5] = $CollegeLastName;
				$arResult["USERS"][$i+$k]["FIELDS"][6] = $CollegeTitle;
				$arResult["USERS"][$i+$k]["FIELDS"][7] = $CollegeJob;
				$arResult["USERS"][$i+$k]["FIELDS"][10] = $CollegeEmail;
			}
			elseif($CollegeName && $curExel["TYPE"] == "GUEST"){
				$k++;
				$arResult["USERS"][$i+$k]["ID"] = $arUsers[$i]["ID"];
				$arResult["USERS"][$i+$k]["ANKETA"] = $arUsers[$i]["UF_ANKETA"];
				$arResult["USERS"][$i+$k]["PAY"] = $arUsers[$i]["UF_PAY_COUNT"];
				$arResult["USERS"][$i+$k]["FIELDS"] = $arResult["USERS"][$i+$k-1]["FIELDS"];
				$arResult["USERS"][$i+$k]["FIELDS"][0] = $CollegeName;
				$arResult["USERS"][$i+$k]["FIELDS"][1] = $CollegeLastName;
				$arResult["USERS"][$i+$k]["FIELDS"][3] = $CollegeJob;
				$arResult["USERS"][$i+$k]["FIELDS"][11] = $CollegeEmail;
			}
			elseif(!empty($CollegesAr) && $curExel["TYPE"] == "GUEST"){
				foreach($CollegesAr as $College){
				$k++;
				$arResult["USERS"][$i+$k]["ID"] = $arUsers[$i]["ID"];
				$arResult["USERS"][$i+$k]["ANKETA"] = $arUsers[$i]["UF_ANKETA"];
				$arResult["USERS"][$i+$k]["PAY"] = $arUsers[$i]["UF_PAY_COUNT"];
				$arResult["USERS"][$i+$k]["FIELDS"] = $arResult["USERS"][$i+$k-1]["FIELDS"];
				$arResult["USERS"][$i+$k]["FIELDS"][0] = $College["NAME"];
				$arResult["USERS"][$i+$k]["FIELDS"][1] = $College["SURNAME"];
				$arResult["USERS"][$i+$k]["FIELDS"][3] = $College["JOB"];
				$arResult["USERS"][$i+$k]["FIELDS"][11] = $College["EMAIL"];
				}
			}
		}
		$countColumns = $arResult["FIELDS"]["COUNT"];
		$arResult["USERS"]["COUNT"] = $countUsers + $k;
		$arResult["FIELDS"] = $realFieldTemp;
		$arResult["FIELDS"]["COUNT"] = $countColumns;
		$arResult["THIS_USER"] = $curExel;
	}
	else{
		$arResult["ERROR_MESSAGE"] = "� ��� ������������ ���� ��� ��������� ������ ��������!";
	}
}
//print_r($arResult);
$this->IncludeComponentTemplate();
?>