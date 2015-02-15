<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/
//ƒÓ·‡‚ËÚ¸ Ô‡‡ÏÂÚ ÍÓÎË˜ÂÒÚ‚Ó ‚ÒÚÂ˜.
//ƒÓ·‡‚ËÚ¸ Ô‡‡ÏÂÚ id ËÌÙÓÒËÒÚÂÏ˚


$arResult["ERROR_MESSAGE"] = "";
$arResult["MESSAGE"] = "";

if(strLen($arParams["PATH_TO_KAB"])<=0){
	$arParams["PATH_TO_KAB"] = "/personal/";
}

if(strLen($arParams["GROUP_ID"])<=0){
	$arParams["GROUP_ID"] = "6";
}

if(strLen($arParams["AUTH_PAGE"])<=0){
	$arParams["AUTH_PAGE"] = "/personal/login.php";
}

if(strLen($arParams["USER"])<=0){
	$arResult["ERROR_MESSAGE"] = "ÕÂ ‚‚Â‰ÂÌ˚ ‰‡ÌÌ˚Â ÔÓ œÓÎ¸ÁÓ‚‡ÚÂÎˇÏ!<br />";
}

if(!isset($arParams["APPOINTMENTS"]) || strLen($arParams["APPOINMENTS"])<=0){
	$arParams["APPOINTMENTS"] = 17;
}

if(!isset($arParams["APP_ID"]) || strLen($arParams["APP_ID"])<=0){
	$arParams["APP_ID"] = 3;
}

if(strLen($arParams["ADMIN_ID"])<=0){
	$arResult["ERROR_MESSAGE"] = "ÕÂ ‚‚Â‰ÂÌ˚ ‰‡ÌÌ˚Â ÔÓ ¿‰ÏËÌËÒÚ‡ÚÓ‡Ï!<br />";
}

if(strLen($arParams["FORM_ID"])<=0){
	$arResult["ERROR_MESSAGE"] = "ÕÂ ‚‚Â‰ÂÌ˚ ‰‡ÌÌ˚Â ÔÓ –ÂÁÛÎ¸Ú‡Ú‡Ï ÔÓÎ¸ÁÓ‚‡ÚÂÎÂÈ!<br />";
}

/*---------------------------------------------------*/
//           ‘Œ–Ã»–”≈Ã —œ»—Œ  œŒÀ‹«Œ¬¿“≈À≈…            //
/*---------------------------------------------------*/
if($arResult["ERROR_MESSAGE"] == '')
{
	//—œ»—Œ  œŒÀ‹«Œ¬¿“≈À≈…
	$filter = Array(
		"GROUPS_ID"  => Array($arParams["GROUP_ID"])
	);
	$rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // ‚˚·Ë‡ÂÏ ÔÓÎ¸ÁÓ‚‡ÚÂÎÂÈ
	if(!isset($_REQUEST["ussort"]) || ($_REQUEST["ussort"]=='abc' && !isset($_REQUEST['letter'])) || $_REQUEST["ussort"]=='all'){
		$rsUsers->NavStart(50); // ‡Á·Ë‚‡ÂÏ ÔÓÒÚ‡ÌË˜ÌÓ ÔÓ 50 Á‡ÔËÒÂÈ
		$arResult["NAVIGATE"] = $rsUsers->GetPageNavStringEx($navComponentObject, "Companies", "");
	}
	$countUsers = 0;
	$resultFormId = "";
	while($arUsersTemp=$rsUsers->Fetch()){
		$arUsers[$countUsers]["ID"] = $arUsersTemp["ID"];
		$arUsers[$countUsers]["UF_ANKETA"] = $arUsersTemp["UF_ANKETA"];
		$arUsers[$countUsers]["APPOINTMENTS"] = array();
		$countApp = 0;
		for($i=1; $i<$arParams["APPOINTMENTS"]+1; $i++){
			$arUsers[$countUsers]["APPOINTMENTS"][] = $arUsersTemp["UF_SHEDULE_".$i];
			if($arUsersTemp["UF_SHEDULE_".$i] == ''){
				$countApp++;
			}
		}
		$arUsers[$countUsers]["COUNT_APP"] = $countApp;
		$resultFormId .= " | ".$arUsersTemp["UF_ANKETA"];
		$countUsers++;
	}
	$resultFormId = substr($resultFormId, 3);
	$arResult["COUNT"] = $countUsers;

	//–≈«”À‹“¿“€ œŒÀ‹«Œ¬¿“≈À≈…
	CForm::GetResultAnswerArray($arParams["FORM_ID"], $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $resultFormId));
	
	//—œ»—Œ   ŒÀŒÕŒ  ƒÀﬂ “¿¡À»÷€
	$countries = array();
	$countContr = 0;
	$cities = array();
	$countCity = 0;
	$times = array(
	  '10:00 ñ 10:10', '10:15 ñ 10:25',
	  '10:30 ñ 10:40', '10:45 ñ 10:55',
	  '11:00 ñ 11:10', '11:15 ñ 11:25',
	  '11:30 ñ 11:40', '11:45 ñ 11:55',
	  '12:10 ñ 12:20', '12:25 ñ 12:35',
	  '12:40 ñ 12:50', '12:55 ñ 13:05',
	  '13:10 ñ 13:20', '13:25 ñ 13:35',
	  '13:40 ñ 13:50', '13:55 ñ 14:05',
	  '14:10 ñ 14:20'
  );
	$arResult["TIMES"]["COUNT"] = $arParams["APPOINTMENTS"];
	$arResult["TIMES"]["VALUES"] = $times;

	//—œ»—Œ  œŒÀ‹«Œ¬¿“≈À≈…
	if($arParams["USER"] == 'PARTICIP'){
	  for($i=0; $i<$countUsers; $i++){
		  $arUsers[$i]["FIELDS"]["NAME"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["name"][0]["USER_TEXT"]." ".$arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["surname"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["COMPANY"] = trim($arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["company"][0]["USER_TEXT"]);
		  $arUsers[$i]["FIELDS"]["DESC"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["company_desc"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["ADRESS"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["adress"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["SITE"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["site"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["CITY"] = trim(ucfirst(strtolower($arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["city"][0]["USER_TEXT"])));
		  if(!in_array($arUsers[$i]["FIELDS"]["CITY"], $cities)){
			  $cities[] = $arUsers[$i]["FIELDS"]["CITY"];
			  $countCity++;
		  }
		  $arUsers[$i]["FIELDS"]["COUNTRY"] = array();
		  foreach($arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["directions"] as $countryInt){
			  $arUsers[$i]["FIELDS"]["COUNTRY"][] = $countryInt["MESSAGE"];
			  if(!in_array($countryInt["MESSAGE"], $countries)){
				  $countries[] = $countryInt["MESSAGE"];
				  $countContr++;
			  }
		  }
		  sort($arUsers[$i]["FIELDS"]["COUNTRY"]);
	  }
	}
	elseif($arParams["USER"] == 'PARTICIP_EV'){
	  for($i=0; $i<$countUsers; $i++){
		  $arUsers[$i]["FIELDS"]["NAME"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["name"][0]["USER_TEXT"]." ".$arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["surname"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["COMPANY"] = trim($arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["company"][0]["USER_TEXT"]);
		  $arUsers[$i]["FIELDS"]["DESC"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["company_desc"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["ADRESS"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["adress"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["SITE"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["site"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["CITY"] = trim(ucfirst(strtolower($arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["city"][0]["USER_TEXT"])));
		  if(!in_array($arUsers[$i]["FIELDS"]["CITY"], $cities)){
			  $cities[] = $arUsers[$i]["FIELDS"]["CITY"];
			  $countCity++;
		  }
		  $arUsers[$i]["FIELDS"]["COUNTRY"] = array();
		  foreach($arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["directions"] as $countryInt){
			  $arUsers[$i]["FIELDS"]["COUNTRY"][] = $countryInt["MESSAGE"];
			  if(!in_array($countryInt["MESSAGE"], $countries)){
				  $countries[] = $countryInt["MESSAGE"];
				  $countContr++;
			  }
		  }
		  sort($arUsers[$i]["FIELDS"]["COUNTRY"]);
		  $arUsers[$i]["FIELDS"]["COLLEGE"] = array();
		  $arUsers[$i]["FIELDS"]["COLLEGE"][0] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["evening_college1_name"][0]["USER_TEXT"]." ".$arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["evening_college1_surname"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["COLLEGE"][1] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["evening_college2_name"][0]["USER_TEXT"]." ".$arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["evening_college2_surname"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["COLLEGE"][2] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["evening_college3_name"][0]["USER_TEXT"]." ".$arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["evening_college3_surname"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["COLLEGE"][3] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["evening_college4_name"][0]["USER_TEXT"]." ".$arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["evening_college4_surname"][0]["USER_TEXT"];
	  }
	}
	elseif($arParams["USER"] == 'GUEST'){
	  for($i=0; $i<$countUsers; $i++){
		  $arUsers[$i]["FIELDS"]["NAME"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["SIMPLE_QUESTION_605"][0]["USER_TEXT"]." ".$arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["SIMPLE_QUESTION_151"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["COMPANY"] = trim($arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["SIMPLE_QUESTION_961"][0]["USER_TEXT"]);
		  $arUsers[$i]["FIELDS"]["COUNTRY"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["SIMPLE_QUESTION_876"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["BUSINESS"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["SIMPLE_QUESTION_716"][0]["MESSAGE"];
		  $arUsers[$i]["FIELDS"]["DESC"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["SIMPLE_QUESTION_182"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["ADRESS"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["SIMPLE_QUESTION_700"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["SITE"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["SIMPLE_QUESTION_973"][0]["USER_TEXT"];
		  $arUsers[$i]["FIELDS"]["CITY"] = $arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["SIMPLE_QUESTION_653"][0]["USER_TEXT"];
		  if(!in_array($arUsers[$i]["FIELDS"]["BUSINESS"], $cities)){
			  $cities[] = $arUsers[$i]["FIELDS"]["BUSINESS"];
			  $countCity++;
		  }
		  /*if(!in_array(strtoupper(trim($arUsers[$i]["FIELDS"]["COUNTRY"])), $countries)){
			  $countries[] = strtoupper(trim($arUsers[$i]["FIELDS"]["COUNTRY"]));
			  $countContr++;
		  }*/
		  $arUsers[$i]["FIELDS"]["COUNTRY"] = array();
		  $arUsers[$i]["FIELDS"]["COUNTRY_LIST"] = '';
		  foreach($arrAnswersVarname[$arUsers[$i]["UF_ANKETA"]]["directions"] as $countryInt){
			  $arUsers[$i]["FIELDS"]["COUNTRY"][] = $countryInt["MESSAGE"];
			  if(!in_array($countryInt["MESSAGE"], $countries)){
				  $countries[] = $countryInt["MESSAGE"];
				  $countContr++;
			  }
		  }
		  sort($arUsers[$i]["FIELDS"]["COUNTRY"]);
		  $arUsers[$i]["FIELDS"]["COUNTRY_LIST"] = implode(", ",$arUsers[$i]["FIELDS"]["COUNTRY"]);
	  }
	}
  /*---------------------------------------------------*/
  //                 ‘Œ–Ã»–”≈Ã ‘»À‹“–€                 //
  /*---------------------------------------------------*/
  $arResult["SORT"] = "ALL";
  $thisUrl = $APPLICATION->GetCurPage();
  $arResult["LINK"] = $thisUrl;
  //œÓ ‡ÎÙ‡‚ËÚÛ
  if((isset($_GET['ussort'])) and ($_GET['ussort'] == 'abc')){
    $arResult["SORT"] = "ABC";
	$letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0";
	$letter_filt = '';
	$isLetter = true;
	for ($i = 0; $i < 27; $i++)
	{
	  if (isset($_REQUEST['letter']) && $_REQUEST['letter'] == $letters[$i]){
		if($i == 26){
			$letter_filt .= '<span style="margin:0 3px 0; color:#66ccff; font-weight:bold;">0-9</span>';
			$isLetter = false;
		}
		else{
			$letter_filt .= '<span style="margin:0 3px 0; color:#66ccff; font-weight:bold;">'.$letters[$i].'</span>';
			$isLetter = false;
		}
	  }
	  else
	  {
		  if($i == 26){
			  $letter_filt .= '<a href="'.$thisUrl.'?ussort=abc&letter='.$letters[$i].'" style="margin:0 3px 0;">0-9</a>';
		  }
		  else{
			  $letter_filt .= '<a href="'.$thisUrl.'?ussort=abc&letter='.$letters[$i].'" style="margin:0 3px 0;">'.$letters[$i].'</a>';
		  }
	  }
	}
	if($isLetter){
	 $letter_filt = $letter_filt.'<span style="margin:0 3px 0; color:#66ccff; font-weight:bold;">All</span>';
	}
	else{
	 $letter_filt = $letter_filt.'<a href="'.$thisUrl.'?ussort=abc" style="margin:0 3px 0;">All</a>';
	}
	$arResult["FILTER"]["SUB"] = $letter_filt;
  }
  elseif((isset($_GET['ussort'])) and ($_GET['ussort'] == 'country')){
	  sort($countries);
      $arResult["SORT"] = "COUNTRIES";
	  $countries_filt = '';
	  $isCountry = false;
	  for($i=1; $i <$countContr; $i++){
		if (isset($_REQUEST['type']) && $_REQUEST['type'] == $i){
		  $countries_filt .= '<span style="margin:0 3px 0; color:#66ccff; font-weight:bold;">'.$countries[$i].'</span> ';
		  $isCountry = true;
		}
		else
		{
		  $countries_filt .= '<a href="'.$arResult["LINK"].'?ussort=country&type='.$i.'" style="margin:0 3px 0;">'.$countries[$i].'</a> ';
		}
	  }
	  if($isCountry){
		  $countries_filt = '<a href="'.$arResult["LINK"].'?ussort=country&type=0" style="margin:0 3px 0;">'.$countries[0].'</a> '.$countries_filt;
	  }
	  else{
		  $countries_filt = '<span style="margin:0 3px 0; color:#66ccff; font-weight:bold;">'.$countries[0].'</span> '.$countries_filt;
	  }
	$arResult["FILTER"]["SUB"] = $countries_filt;
  }
  elseif((isset($_GET['ussort'])) and ($_GET['ussort'] == 'city')){
	  sort($cities);
      $arResult["SORT"] = "CITY";
	  $countries_filt = '';
	  $isCountry = false;
	  for($i=1; $i <$countCity; $i++){
		if (isset($_REQUEST['type']) && $_REQUEST['type'] == $i){
		  $countries_filt .= '<span style="margin:0 3px 0; color:#66ccff; font-weight:bold;">'.$cities[$i].'</span> ';
		  $isCountry = true;
		}
		else
		{
		  $countries_filt .= '<a href="'.$arResult["LINK"].'?ussort=city&type='.$i.'" style="margin:0 3px 0;">'.$cities[$i].'</a> ';
		}
	  }
	  if($isCountry){
		  $countries_filt = '<a href="'.$arResult["LINK"].'?ussort=city&type=0" style="margin:0 3px 0;">'.$cities[0].'</a> '.$countries_filt;
	  }
	  else{
		  $countries_filt = '<span style="margin:0 3px 0; color:#66ccff; font-weight:bold;">'.$cities[0].'</span> '.$countries_filt;
	  }
	$arResult["FILTER"]["SUB"] = $countries_filt;
  }
  elseif((isset($_GET['ussort'])) and ($_GET['ussort'] == 'business')){
	  sort($cities);
      $arResult["SORT"] = "BUSINESS";
	  $countries_filt = '';
	  $isCountry = false;
	  for($i=1; $i <$countCity; $i++){
		if (isset($_REQUEST['type']) && $_REQUEST['type'] == $i){
		  $countries_filt .= '<span style="margin:0 3px 0; color:#66ccff; font-weight:bold;">'.$cities[$i].'</span> ';
		  $isCountry = true;
		}
		else
		{
		  $countries_filt .= '<a href="'.$arResult["LINK"].'?ussort=business&type='.$i.'" style="margin:0 3px 0;">'.$cities[$i].'</a> ';
		}
	  }
	  if($isCountry){
		  $countries_filt = '<a href="'.$arResult["LINK"].'?ussort=business&type=0" style="margin:0 3px 0;">'.$cities[0].'</a> '.$countries_filt;
	  }
	  else{
		  $countries_filt = '<span style="margin:0 3px 0; color:#66ccff; font-weight:bold;">'.$cities[0].'</span> '.$countries_filt;
	  }
	$arResult["FILTER"]["SUB"] = $countries_filt;
  }
  elseif((isset($_GET['ussort'])) and ($_GET['ussort'] == 'times')){
      $arResult["SORT"] = "TIMES";
	  $countries_filt = '';
	  $isCountry = false;
	  for($i=1; $i < $arParams["APPOINTMENTS"]; $i++){
		if (isset($_REQUEST['type']) && $_REQUEST['type'] == $i){
		  $countries_filt .= '<span style="margin:0 6px 0 3px; color:#66ccff; font-weight:bold;">'.$times[$i].'</span> ';
		  $isCountry = true;
		}
		else
		{
		  $countries_filt .= '<a href="'.$arResult["LINK"].'?ussort=times&type='.$i.'" style="margin:0 6px 0 3px;">'.$times[$i].'</a> ';
		}
	  }
	  if($isCountry){
		  $countries_filt = '<a href="'.$arResult["LINK"].'?ussort=times&type=0" style="margin:0 6px 0 3px;">'.$times[0].'</a> '.$countries_filt;
	  }
	  else{
		  $countries_filt = '<span style="margin:0 6px 0 3px; color:#66ccff; font-weight:bold;">'.$times[0].'</span> '.$countries_filt;
	  }
	  
	$arResult["FILTER"]["SUB"] = $countries_filt;
  }
  $realCount = 0;
  if($arResult["SORT"] == "COUNTRIES"){
	foreach($arUsers as $userTmp){
		if($isCountry){
			if(in_array($countries[$_REQUEST['type']],$userTmp["FIELDS"]["COUNTRY"]) || $countries[$_REQUEST['type']] == strtoupper(trim($userTmp["FIELDS"]["COUNTRY"]))){
				$arResult["USERS"][] = $userTmp;
				$realCount++;
			}
		}
		else{
			if(in_array($countries[0],$userTmp["FIELDS"]["COUNTRY"]) || $countries[0] == strtoupper(trim($userTmp["FIELDS"]["COUNTRY"]))){
				$arResult["USERS"][] = $userTmp;
				$realCount++;
			}
		}
	}
	$arResult["COUNT"] = $realCount;
  }
  elseif($arResult["SORT"] == "CITY"){
	foreach($arUsers as $userTmp){
		if($isCountry){
			if($cities[$_REQUEST['type']] == $userTmp["FIELDS"]["CITY"]){
				$arResult["USERS"][] = $userTmp;
				$realCount++;
			}
		}
		else{
			if($cities[0] == $userTmp["FIELDS"]["CITY"]){
				$arResult["USERS"][] = $userTmp;
				$realCount++;
			}
		}
	}
	$arResult["COUNT"] = $realCount;
  }
  elseif($arResult["SORT"] == "BUSINESS"){
	foreach($arUsers as $userTmp){
		if($isCountry){
			if($cities[$_REQUEST['type']] == $userTmp["FIELDS"]["BUSINESS"]){
				$arResult["USERS"][] = $userTmp;
				$realCount++;
			}
		}
		else{
			if($cities[0] == $userTmp["FIELDS"]["BUSINESS"]){
				$arResult["USERS"][] = $userTmp;
				$realCount++;
			}
		}
	}
	$arResult["COUNT"] = $realCount;
  }
  elseif($arResult["SORT"] == "TIMES"){
	foreach($arUsers as $userTmp){
		if($isCountry){
			if($userTmp["APPOINTMENTS"][$_REQUEST['type']] == ''){
				$arResult["USERS"][] = $userTmp;
				$realCount++;
			}
		}
		else{
			if($userTmp["APPOINTMENTS"][0] == ''){
				$arResult["USERS"][] = $userTmp;
				$realCount++;
			}
		}
	}
	$arResult["COUNT"] = $realCount;
  }
  elseif($arResult["SORT"] == "ABC"){
	foreach($arUsers as $userTmp){
		if(!$isLetter){
			if(strtolower($_REQUEST['letter']) == strtolower($userTmp["FIELDS"]["COMPANY"][0])){
				$arResult["USERS"][] = $userTmp;
				$realCount++;
			}
			elseif($_REQUEST['letter'] == '0' && ctype_digit($userTmp["FIELDS"]["COMPANY"][0])){
				$arResult["USERS"][] = $userTmp;
				$realCount++;
			}
		}
	}
	if(!$isLetter){
	  $arResult["COUNT"] = $realCount;
	}
	else{
	  $arResult["USERS"] = $arUsers;
	}
  }
  else{
    $arResult["USERS"] = $arUsers;
  }
}
//echo "<pre>"; print_r($arrAnswersVarname); echo "</pre>";

$this->IncludeComponentTemplate();
?>