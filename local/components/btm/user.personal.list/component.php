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

if(strLen($arParams["USER"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Пользователям!<br />";
}

if(strLen($arParams["FORM_ID"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Результатам пользователей!<br />";
}
/*---------------------------------------------------*/
//        ФОРМИРУЕМ ФИЛЬТРЫ ВЕРХНЯЯ СТРОКА           //
/*---------------------------------------------------*/
	$thisUrl = str_replace('index.php','',$APPLICATION->GetCurPage());
	$arResult["FILTERS"]["MAIN"][0]["ID"] = 'abc';
	$arResult["FILTERS"]["MAIN"][0]["NAME"] = 'In alphabetical order';
	$arResult["FILTERS"]["MAIN"][0]["LINK"] = $thisUrl."?ussort=".$arResult["FILTERS"]["MAIN"][0]["ID"];
	$arResult["FILTERS"]["MAIN"][0]["ACTIVE"] = 'N';
	
	$arResult["FILTERS"]["MAIN"][1]["ID"] = 'country';
	$arResult["FILTERS"]["MAIN"][1]["NAME"] = 'By country of interest';
	$arResult["FILTERS"]["MAIN"][1]["LINK"] = $thisUrl."?ussort=".$arResult["FILTERS"]["MAIN"][1]["ID"];
	$arResult["FILTERS"]["MAIN"][1]["ACTIVE"] = 'N';
	
	$arResult["FILTERS"]["MAIN"][2]["ID"] = 'city';
	$arResult["FILTERS"]["MAIN"][2]["NAME"] = 'By city of origin';
	$arResult["FILTERS"]["MAIN"][2]["LINK"] = $thisUrl."?ussort=".$arResult["FILTERS"]["MAIN"][2]["ID"];
	$arResult["FILTERS"]["MAIN"][2]["ACTIVE"] = 'N';
	
	$arResult["FILTERS"]["MAIN"][3]["ID"] = 'frtimes';
	$arResult["FILTERS"]["MAIN"][3]["NAME"] = 'By available slots';
	$arResult["FILTERS"]["MAIN"][3]["LINK"] = $thisUrl."?ussort=".$arResult["FILTERS"]["MAIN"][3]["ID"];
	$arResult["FILTERS"]["MAIN"][3]["ACTIVE"] = 'N';
	
	$arResult["FILTERS"]["MAIN"][4]["ID"] = 'all';
	$arResult["FILTERS"]["MAIN"][4]["NAME"] = 'All';
	$arResult["FILTERS"]["MAIN"][4]["LINK"] = $thisUrl."?ussort=".$arResult["FILTERS"]["MAIN"][4]["ID"];
	$arResult["FILTERS"]["MAIN"][4]["ACTIVE"] = 'N';

/*---------------------------------------------------*/
//        ФОРМИРУЕМ ФИЛЬТРЫ НИЖНЯЯ СТРОКА           //
/*---------------------------------------------------*/
	$realAnkets = '';
	$realUsers = array();
	$realUsers["COUNT"] = 0;
	//---------------------- АЛФАВИТ --------------------------
	if(((isset($_GET['ussort'])) and ($_GET['ussort'] == $arResult["FILTERS"]["MAIN"][0]["ID"])) || !isset($_GET['ussort'])){
		$filterTmp = Array(
			"GROUPS_ID"  => Array($arParams["USER"])
		);
		$rsUsersTmp = CUser::GetList(($by="id"), ($order="asc"), $filterTmp, array("SELECT"=>array("UF_ANKETA", "ID"))); // выбираем пользователей
		$resultFormIdTmp = "";
		$countUsers = 0;
		while($arUsersTemp=$rsUsersTmp->Fetch()){
			$resultFormIdTmp .= " | ".$arUsersTemp["UF_ANKETA"];
			$tempUsers[$countUsers]["ANKETA"] = $arUsersTemp["UF_ANKETA"];
			$tempUsers[$countUsers]["ID"] = $arUsersTemp["ID"];
			$countUsers++;
		}
		$resultFormIdTmp = substr($resultFormIdTmp, 3);
	
		//РЕЗУЛЬТАТЫ ПОЛЬЗОВАТЕЛЕЙ
		CForm::GetResultAnswerArray($arParams["FORM_ID"], $arrColumnsTmp, $arrAnswersTmp, $arrAnswersVarnameTmp, array("RESULT_ID" => $resultFormIdTmp, "FIELD_ID" => 55));

		$letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0";
		$letter_filt = '<p>';
		$isLetter = true;
		$thisUrl = str_replace('index.php','',$APPLICATION->GetCurPage())."?ussort=".$arResult["FILTERS"]["MAIN"][0]["ID"]."&";
		$arResult["FILTERS"]["MAIN"][0]["ACTIVE"] = 'Y';
		for ($i = 0; $i < 27; $i++)
		{
		  if (isset($_REQUEST['id']) && $_REQUEST['id'] == $letters[$i]){
			if($i == 26){
				$letter_filt .= '<span style="margin:0 3px 0; color:#FF7900; font-weight:bold;">0-9</span>';
				$isLetter = false;
				for($j=0; $j<$countUsers; $j++){
					if(ctype_digit($arrAnswersVarnameTmp[$tempUsers[$j]["ANKETA"]]["SIMPLE_QUESTION_297"][0]["USER_TEXT"][0])){
						$realAnkets .= " | ".$tempUsers[$j]["ANKETA"];
						$realUsers["USERS"][$realUsers["COUNT"]]["ID"] = $tempUsers[$j]["ID"];
						$realUsers["USERS"][$realUsers["COUNT"]]["UF_ANKETA"] = $tempUsers[$j]["ANKETA"];
						$realUsers["COUNT"]++;
					};
				}
			}
			else{
				$letter_filt .= '<span style="margin:0 3px 0; color:#FF7900; font-weight:bold;">'.$letters[$i].'</span>';
				$isLetter = false;
				for($j=0; $j<$countUsers; $j++){
					if($arrAnswersVarnameTmp[$tempUsers[$j]["ANKETA"]]["SIMPLE_QUESTION_297"][0]["USER_TEXT"][0] == $letters[$i]){
						$realAnkets .= " | ".$tempUsers[$j]["ANKETA"];
						$realUsers["USERS"][$realUsers["COUNT"]]["ID"] = $tempUsers[$j]["ID"];
						$realUsers["USERS"][$realUsers["COUNT"]]["UF_ANKETA"] = $tempUsers[$j]["ANKETA"];
						$realUsers["COUNT"]++;
					};
				}
			}
		  }
		  else
		  {
			  if($i == 26){
				  $letter_filt .= '<a href="'.$thisUrl.'id='.$letters[$i].'" style="margin:0 3px 0;">0-9</a>';
			  }
			  else{
				  $letter_filt .= '<a href="'.$thisUrl.'id='.$letters[$i].'" style="margin:0 3px 0;">'.$letters[$i].'</a>';
			  }
		  }
		}
		if($isLetter){
		 $letter_filt = $letter_filt.'<span style="margin:0 3px 0; color:#FF7900; font-weight:bold;">All</span>';
		 for($j=0; $j<$countUsers; $j++){
		 	$realAnkets .= " | ".$tempUsers[$j]["ANKETA"];
			$realUsers["USERS"][$realUsers["COUNT"]]["ID"] = $tempUsers[$j]["ID"];
			$realUsers["USERS"][$realUsers["COUNT"]]["UF_ANKETA"] = $tempUsers[$j]["ANKETA"];
			$realUsers["COUNT"]++;
		 }		 
		}
		else{
		 $letter_filt = $letter_filt.'<a href="'.$thisUrl.'" style="margin:0 3px 0;">All</a>';
		}
		$arResult["FILTERS"]["SUB"] = $letter_filt."</p>";
		$realAnkets = substr($realAnkets, 3);
		$arResult["USERS"]["COUNT"] = $realUsers["COUNT"];
	}
	//---------------------- ОБЛАСТЬ ИНТЕРЕСОВ --------------------------
	elseif((isset($_GET['ussort'])) and ($_GET['ussort'] == $arResult["FILTERS"]["MAIN"][1]["ID"])){
		//СПИСОК ПОЛЬЗОВАТЕЛЕЙ
		$filterTmp = Array(
			"GROUPS_ID"  => Array($arParams["USER"])
		);
		$rsUsersTmp = CUser::GetList(($by="id"), ($order="asc"), $filterTmp, array("SELECT"=>array("UF_ANKETA", "ID"))); // выбираем пользователей
		$resultFormIdTmp = "";
		$countUsers = 0;
		while($arUsersTemp=$rsUsersTmp->Fetch()){
			$resultFormIdTmp .= " | ".$arUsersTemp["UF_ANKETA"];
			$tempUsers[$countUsers]["ANKETA"] = $arUsersTemp["UF_ANKETA"];
			$tempUsers[$countUsers]["ID"] = $arUsersTemp["ID"];
			$countUsers++;
		}
		$resultFormIdTmp = substr($resultFormIdTmp, 3);
	
		//РЕЗУЛЬТАТЫ ПОЛЬЗОВАТЕЛЕЙ
		CForm::GetResultAnswerArray($arParams["FORM_ID"], $arrColumnsTmp, $arrAnswersTmp, $arrAnswersVarnameTmp, array("RESULT_ID" => $resultFormIdTmp, "FIELD_ID" => 77));
		
		$country = array();
		$countryPoint = array();
		$countryStr = '';
		$allCounties = 0;
		
		for($i=0; $i<$countUsers; $i++){
			$tempUsers[$i]["COUNTRY"] = strtoupper($arrAnswersVarnameTmp[$tempUsers[$i]["ANKETA"]]["SIMPLE_QUESTION_691"][0]["USER_TEXT"]);

			if(strpos($tempUsers[$i]["COUNTRY"], ',')){
				$countryPoint = explode(",", $tempUsers[$i]["COUNTRY"]);
			}
			elseif(strpos($tempUsers[$i]["COUNTRY"], '/')){
				$countryPoint = explode("/", $tempUsers[$i]["COUNTRY"]);
			}
			elseif(strpos($tempUsers[$i]["COUNTRY"], '&')){
				$countryPoint = explode("&", $tempUsers[$i]["COUNTRY"]);
			}
			elseif(strpos($tempUsers[$i]["COUNTRY"], ' - ')){
				$countryPoint = explode(" - ", $tempUsers[$i]["COUNTRY"]);
			}
			if($countryPoint){
				foreach($countryPoint as $countryPart){
					if(strpos($countryStr, trim($countryPart)) === false){
						$countryStr .= $countryPart.", ";
						$country[] = trim($countryPart);
						$allCounties++;
					}
				}
			}
			else{
				if(strpos($countryStr, trim($tempUsers[$i]["COUNTRY"])) === false){
					$countryStr .= $tempUsers[$i]["COUNTRY"].", ";
					$country[] = trim($tempUsers[$i]["COUNTRY"]);
					$allCounties++;
				}
			}
			$countryPoint = array();
		}
		sort($country);
		$letter_filt = '<p>';
		$isLetter = true;
		$thisUrl = str_replace('index.php','',$APPLICATION->GetCurPage())."?ussort=".$arResult["FILTERS"]["MAIN"][1]["ID"]."&";
		$arResult["FILTERS"]["MAIN"][1]["ACTIVE"] = 'Y';
		for ($i = 0; $i < $allCounties; $i++)
		{
		  if (isset($_REQUEST['id']) && $_REQUEST['id'] == $country[$i]){
			  $letter_filt .= '<span style="margin:0 3px 0; color:#FF7900; font-weight:bold;">'.$country[$i].'</span>';
			  $isLetter = false;
			  for($j=0; $j<$countUsers; $j++){
				  if(strpos($arrAnswersVarnameTmp[$tempUsers[$j]["ANKETA"]]["SIMPLE_QUESTION_691"][0]["USER_TEXT"], $country[$i]) !== false){
					  $realAnkets .= " | ".$tempUsers[$j]["ANKETA"];
					  $realUsers["USERS"][$realUsers["COUNT"]]["ID"] = $tempUsers[$j]["ID"];
					  $realUsers["USERS"][$realUsers["COUNT"]]["UF_ANKETA"] = $tempUsers[$j]["ANKETA"];
					  $realUsers["COUNT"]++;
				  };
			  }
		  }
		  else
		  {
			  $letter_filt .= '<a href="'.$thisUrl.'id='.$country[$i].'" style="margin:0 3px 0;">'.$country[$i].'</a>';
		  }
		}
		if($isLetter){
		 $letter_filt = $letter_filt.'<span style="margin:0 3px 0; color:#FF7900; font-weight:bold;">All</span>';
		 for($j=0; $j<$countUsers; $j++){
			 $realAnkets .= " | ".$tempUsers[$j]["ANKETA"];
			 $realUsers["USERS"][$realUsers["COUNT"]]["ID"] = $tempUsers[$j]["ID"];
			 $realUsers["USERS"][$realUsers["COUNT"]]["UF_ANKETA"] = $tempUsers[$j]["ANKETA"];
			 $realUsers["COUNT"]++;		 
		 }
		}
		else{
		 $letter_filt = $letter_filt.'<a href="'.$thisUrl.'" style="margin:0 3px 0;">All</a>';
		}
		$arResult["FILTERS"]["SUB"] = $letter_filt."</p>";
		$realAnkets = substr($realAnkets, 3);
		$arResult["USERS"]["COUNT"] = $realUsers["COUNT"];
	}
	//---------------------- ГОРОД --------------------------
	elseif((isset($_GET['ussort'])) and ($_GET['ussort'] == $arResult["FILTERS"]["MAIN"][2]["ID"])){
		//СПИСОК ПОЛЬЗОВАТЕЛЕЙ
		$filterTmp = Array(
			"GROUPS_ID"  => Array($arParams["USER"])
		);
		$rsUsersTmp = CUser::GetList(($by="id"), ($order="asc"), $filterTmp, array("SELECT"=>array("UF_ANKETA", "ID"))); // выбираем пользователей
		$resultFormIdTmp = "";
		$countUsers = 0;
		while($arUsersTemp=$rsUsersTmp->Fetch()){
			$resultFormIdTmp .= " | ".$arUsersTemp["UF_ANKETA"];
			$tempUsers[$countUsers]["ANKETA"] = $arUsersTemp["UF_ANKETA"];
			$tempUsers[$countUsers]["ID"] = $arUsersTemp["ID"];
			$countUsers++;
		}
		$resultFormIdTmp = substr($resultFormIdTmp, 3);
	
		//РЕЗУЛЬТАТЫ ПОЛЬЗОВАТЕЛЕЙ
		CForm::GetResultAnswerArray($arParams["FORM_ID"], $arrColumnsTmp, $arrAnswersTmp, $arrAnswersVarnameTmp, array("RESULT_ID" => $resultFormIdTmp, "FIELD_ID" => 58));

		$country = array();
		$countryPoint = array();
		$countryStr = '';
		$allCounties = 0;
		
		for($i=0; $i<$countUsers; $i++){
			$tempUsers[$i]["COUNTRY"] = strtolower($arrAnswersVarnameTmp[$tempUsers[$i]["ANKETA"]]["SIMPLE_QUESTION_154"][0]["USER_TEXT"]);
			
			if(strpos($countryStr, trim($tempUsers[$i]["COUNTRY"])) === false && $tempUsers[$i]["COUNTRY"]){
				$countryStr .= $tempUsers[$i]["COUNTRY"].", ";
				$country[] = trim($tempUsers[$i]["COUNTRY"]);
				$allCounties++;
			}
		}
		sort($country);
		$letter_filt = '<p>';
		$isLetter = true;
		$thisUrl = str_replace('index.php','',$APPLICATION->GetCurPage())."?ussort=".$arResult["FILTERS"]["MAIN"][2]["ID"]."&";
		$arResult["FILTERS"]["MAIN"][2]["ACTIVE"] = 'Y';
		for ($i = 0; $i < $allCounties; $i++)
		{
		  if (isset($_REQUEST['id']) && $_REQUEST['id'] == $i){
			  $letter_filt .= '<span style="margin:0 3px 0; color:#FF7900; font-weight:bold;">'.$country[$i].'</span>';
			  $isLetter = false;
			  for($j=0; $j<$countUsers; $j++){
				  if(strtolower($arrAnswersVarnameTmp[$tempUsers[$j]["ANKETA"]]["SIMPLE_QUESTION_154"][0]["USER_TEXT"]) == $country[$i]){
					  $realAnkets .= " | ".$tempUsers[$j]["ANKETA"];
					  $realUsers["USERS"][$realUsers["COUNT"]]["ID"] = $tempUsers[$j]["ID"];
					  $realUsers["USERS"][$realUsers["COUNT"]]["UF_ANKETA"] = $tempUsers[$j]["ANKETA"];
					  $realUsers["COUNT"]++;
				  };
			  }
		  }
		  else
		  {
			  $letter_filt .= '<a href="'.$thisUrl.'id='.$i.'" style="margin:0 3px 0;">'.$country[$i].'</a>';
		  }
		}
		if($isLetter){
		 $letter_filt = $letter_filt.'<span style="margin:0 3px 0; color:#FF7900; font-weight:bold;">All</span>';
		 for($j=0; $j<$countUsers; $j++){
			 $realAnkets .= " | ".$tempUsers[$j]["ANKETA"];
			 $realUsers["USERS"][$realUsers["COUNT"]]["ID"] = $tempUsers[$j]["ID"];
			 $realUsers["USERS"][$realUsers["COUNT"]]["UF_ANKETA"] = $tempUsers[$j]["ANKETA"];
			 $realUsers["COUNT"]++;		 
		 }
		}
		else{
		 $letter_filt = $letter_filt.'<a href="'.$thisUrl.'" style="margin:0 3px 0;">All</a>';
		}
		$arResult["FILTERS"]["SUB"] = $letter_filt."</p>";
		$realAnkets = substr($realAnkets, 3);
		$arResult["USERS"]["COUNT"] = $realUsers["COUNT"];
	}
	elseif((isset($_GET['ussort'])) and ($_GET['ussort'] == $arResult["FILTERS"]["MAIN"][4]["ID"])){
		$filterTmp = Array(
			"GROUPS_ID"  => Array($arParams["USER"])
		);
		$rsUsersTmp = CUser::GetList(($by="id"), ($order="asc"), $filterTmp, array("SELECT"=>array("UF_ANKETA", "ID"))); // выбираем пользователей
		$resultFormIdTmp = "";
		$countUsers = 0;
		while($arUsersTemp=$rsUsersTmp->Fetch()){
			$resultFormIdTmp .= " | ".$arUsersTemp["UF_ANKETA"];
			$realUsers["USERS"][$countUsers]["UF_ANKETA"] = $arUsersTemp["UF_ANKETA"];
			$realUsers["USERS"][$countUsers]["ID"] = $arUsersTemp["ID"];
			$countUsers++;
		}
		$realAnkets = substr($resultFormIdTmp, 3);
		$arResult["USERS"]["COUNT"] = $countUsers;
		$realUsers["COUNT"]= $countUsers;	
		$thisUrl = str_replace('index.php','',$APPLICATION->GetCurPage())."?ussort=".$arResult["FILTERS"]["MAIN"][2]["ID"]."&";
		$arResult["FILTERS"]["MAIN"][4]["ACTIVE"] = 'Y';
	}
/*---------------------------------------------------*/
//           ФОРМИРУЕМ ВЫВОД ДЛЯ ШАБЛОНА             //
/*---------------------------------------------------*/
if($arResult["ERROR_MESSAGE"] == '' && $arResult["USERS"]["COUNT"] != 0)
{
	//РЕЗУЛЬТАТЫ ПОЛЬЗОВАТЕЛЕЙ
	CForm::GetResultAnswerArray($arParams["FORM_ID"], $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $realAnkets));
	
	//СПИСОК КОЛОНОК ДЛЯ ТАБЛИЦЫ
	$countColumns = 0;
	$countReal = 0;
	$arResult["FIELDS"]["COUNT"]=0;
	foreach($arrColumns as $columnName){
		$arResult["FIELDS"][$countReal]["ID"] = $columnName["ID"];
		$arResult["FIELDS"][$countReal]["TITLE"] = $columnName["TITLE"];
		$arResult["FIELDS"][$countReal]["OTHER"] = "N";
		if($arParams["USER_TYPE"] == "PARTICIP"){
			if($countReal!=0 && ($columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (other)")){
				$arResult["FIELDS"][$countReal]["OTHER"] = "Y";
			}
			else{
				$countColumns++;
			}
		}
		else{
			if($countReal!=0 && ($columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (другой)" || $columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (другая)" || $columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (другие)" || $columnName["TITLE"] == $arResult["FIELDS"][$countReal-1]["TITLE"]." (другое)")){
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
	
	
	//СПИСОК ПОЛЬЗОВАТЕЛЕЙ С ПОЛЯМИ
	for($i=0; $i<$realUsers["COUNT"]; $i++){
		$arResult["USERS"][$i]["ID"] = $realUsers["USERS"][$i]["ID"];
		$arResult["USERS"][$i]["ANKETA"] = $realUsers["USERS"][$i]["UF_ANKETA"];
		$sdvig = 0;
		for($j=0; $j<$countReal; $j++){
			if($arResult["FIELDS"][$j]["OTHER"] == "Y"){
				$sdvig++;
				$tempMean = "";
				foreach($arrAnswers[$realUsers["USERS"][$i]["UF_ANKETA"]][$arResult["FIELDS"][$j]["ID"]] as $ansMeaning){
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
					echo $arResult["USERS"][$i]["FIELDS"][$j-$sdvig]."<br />";
					$arResult["USERS"][$i]["FIELDS"][$j-$sdvig] = $tempMean;
				}
			}
			else{
				$arResult["USERS"][$i]["FIELDS"][$j-$sdvig] = "";
				foreach($arrAnswers[$realUsers["USERS"][$i]["UF_ANKETA"]][$arResult["FIELDS"][$j]["ID"]] as $ansMeaning){
					if($ansMeaning["USER_TEXT"]){
						$arResult["USERS"][$i]["FIELDS"][$j-$sdvig] .= ", ".$ansMeaning["USER_TEXT"];
					}
					else{
						$arResult["USERS"][$i]["FIELDS"][$j-$sdvig] .= ", ".$ansMeaning["ANSWER_TEXT"];
					}
				}
				$arResult["USERS"][$i]["FIELDS"][$j-$sdvig] = substr($arResult["USERS"][$i]["FIELDS"][$j-$sdvig], 2);
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
					if(strpos($arResult["FIELDS"][$j]["TITLE"], "Адрес") !== false){
						$realFieldTemp[$j-$sdvig]["TITLE"] = "Адрес";
					}
					elseif(strpos($arResult["FIELDS"][$j]["TITLE"], "Телефон") !== false){
						$realFieldTemp[$j-$sdvig]["TITLE"] = "Телефон";
					}
					else{
						$realFieldTemp[$j-$sdvig]["TITLE"] = $arResult["FIELDS"][$j]["TITLE"];
					}
				}
			}
		}
	}

	$countColumns = $arResult["FIELDS"]["COUNT"];
	$arResult["FIELDS"] = $realFieldTemp;
	$arResult["FIELDS"]["COUNT"] = $countColumns;
}
//echo "<pre>"; print_r($arCategory); echo "</pre>";
//echo "<pre>"; print_r(); echo "</pre>";

$this->IncludeComponentTemplate();
?>