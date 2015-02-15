<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
//Добавить в параметры Номера вопросов для проверки
//Добавить в параметры Создавать ли пользователя


if (CModule::IncludeModule("form"))
{
	$arDefaultComponentParameters = array(
		"WEB_FORM_ID" => $_REQUEST["WEB_FORM_ID"],
		"SEF_MODE" => "N",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"USE_EXTENDED_ERRORS" => "N",
		"CACHE_TIME" => "3600",
	);

	foreach ($arDefaultComponentParameters as $key => $value) if (!is_set($arParams, $key)) $arParams[$key] = $value;
	
	$arDefaultUrl = array(
		'LIST' => $arParams["SEF_MODE"] == "Y" ? "list/" : "result_list.php", 
		'EDIT' => $arParams["SEF_MODE"] == "Y" ? "edit/#RESULT_ID#/" : "result_edit.php"
	);
	
	foreach ($arDefaultUrl as $action => $url)
	{
		if (!is_set($arParams, $action.'_URL'))
		{
			if (!is_set($arParams, 'SHOW_'.$action.'_PAGE') || $arParams['SHOW_'.$action.'_PAGE'] == 'Y')
				$arParams[$action.'_URL'] = $url;
		}
	}
	
	if (isset($arParams['RESULT_ID']))
		unset($arParams['RESULT_ID']);
	
	//  insert chain item
	if (strlen($arParams["CHAIN_ITEM_TEXT"]) > 0)
	{
		$APPLICATION->AddChainItem($arParams["CHAIN_ITEM_TEXT"], $arParams["CHAIN_ITEM_LINK"]);
	}
	
	// check whether cache using needed
	$bCache = !(
		$_SERVER["REQUEST_METHOD"] == "POST" 
		&& 
		(
			!empty($_REQUEST["web_form_submit"]) 
			|| 
			!empty($_REQUEST["web_form_apply"])
		)
		||
		$_REQUEST['formresult'] == 'ADDOK'
	) 
	&& 
	!(
		$arParams["CACHE_TYPE"] == "N" 
		|| 
		(
			$arParams["CACHE_TYPE"] == "A" 
			&& 
			COption::GetOptionString("main", "component_cache_on", "Y") == "N" 
		)
		||
		(
			$arParams["CACHE_TYPE"] == "Y"
			&&
			intval($arParams["CACHE_TIME"]) <= 0
		)
	);
	
	// start caching
	if ($bCache)
	{
		// append arParams to cache ID;
		$arCacheParams = array();
		foreach ($arParams as $key => $value) if (substr($key, 0, 1) != "~") $arCacheParams[$key] = $value;
		// create CPHPCache class instance
		$obFormCache = new CPHPCache;
		// create cache ID and path
		$CACHE_ID = SITE_ID."|".$componentName."|".md5(serialize($arCacheParams))."|".$USER->GetGroups();
		$CACHE_PATH = "/".SITE_ID.CComponentEngine::MakeComponentPath($componentName);
	}

	// initialize cache
	if ($bCache && $obFormCache->InitCache($arParams["CACHE_TIME"], $CACHE_ID, $CACHE_PATH))
	{
		// if cache already exists - get vars
		$arCacheVars = $obFormCache->GetVars();
		$bVarsFromCache = true;
		
		$arResult = $arCacheVars["arResult"];
		
		if ($arParams["IGNORE_CUSTOM_TEMPLATE"] == "N" && $arResult["arForm"]["USE_DEFAULT_TEMPLATE"] == "N" && strlen($arResult["arForm"]["FORM_TEMPLATE"]) > 0)
		{
			$FORM = $arCacheVars["FORM"];
			if (!$FORM) $bVarsFromCache = false;
		}
		$arResult['FORM_NOTE'] = '';
		$arResult['isFormNote'] = 'N';
	}
	else
	{
/*************************************************************************************************/
		$bVarsFromCache = false;
		
		$arResult["bSimple"] = COption::GetOptionString("form", "SIMPLE", "Y") == "N" ? "N" : "Y";
		$arResult["bAdmin"] = defined("ADMIN_SECTION") && ADMIN_SECTION===true ? "Y" : "N";

		// if form taken from admin interface - check rights to form module
		if ($arResult["bAdmin"] == "Y")
		{
			$FORM_RIGHT = $APPLICATION->GetGroupRight("form");
			if($FORM_RIGHT<="D") $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
		}
		
		if (intval($arParams['WEB_FORM_ID']) <= 0 && strlen($arParams['WEB_FORM_ID']) > 0)
		{
			$obForm = CForm::GetBySID($arParams['WEB_FORM_ID']);
			if ($arForm = $obForm->Fetch())
			{
				$arParams['WEB_FORM_ID'] = $arForm['ID'];
			}
		}
		
		
		// check WEB_FORM_ID and get web form data
		$arParams["WEB_FORM_ID"] = CForm::GetDataByID($arParams["WEB_FORM_ID"], $arResult["arForm"], $arResult["arQuestions"], $arResult["arAnswers"], $arResult["arDropDown"], $arResult["arMultiSelect"], $arResult["bAdmin"] == "Y" || $arParams["SHOW_ADDITIONAL"] == "Y" || $arParams["EDIT_ADDITIONAL"] == "Y" ? "ALL" : "N");
		
		$arResult["WEB_FORM_NAME"] = $arResult["arForm"]["SID"];
	
		// if wrong WEB_FORM_ID return error;
		if ($arParams["WEB_FORM_ID"] > 0) 
		{
			// check web form rights;
			$arResult["F_RIGHT"] = intval(CForm::GetPermission($arParams["WEB_FORM_ID"]));
			
			// in no form access - return error
			if ($arResult["F_RIGHT"] < 10)
			{
				$arResult["ERROR"] = "FORM_ACCESS_DENIED";
			}
		}
		else
		{
			$arResult["ERROR"] = "FORM_NOT_FOUND";
		}
	}

	if (strlen($arResult["ERROR"]) <= 0)
	{
		// ************************************************************* //
		//                                             get/post processing                                             //
		// ************************************************************* //
		
		$arResult["arrVALUES"] = array();
		
		//CForm::GetDataByID($arParams["SECOND_FORM_ID"], $form, $questions, $answers, $dropdown, $multiselect);
		//'<pre>'; print_r($_REQUEST); echo '</pre>';


		if (($_POST['WEB_FORM_ID'] == $arParams['WEB_FORM_ID'] || $_POST['WEB_FORM_ID'] == $arResult['arForm']['SID']) && (strlen($_REQUEST["web_form_submit"])>0 || strlen($_REQUEST["web_form_apply"])>0))
		{
			$arResult["arrVALUES"] = $_REQUEST;
	
			// check errors
			$arResult["FORM_ERRORS"] = CForm::Check($arParams["WEB_FORM_ID"], $arResult["arrVALUES"], false, "Y", $arParams['USE_EXTENDED_ERRORS']);
			
/*/////////// ПРОВЕРКА СОВПАДЕНИЯ ПОЛЯ EMAIL ///////////*/
			if(isset($_REQUEST["form_email_63"]) && $_REQUEST["form_email_63"] != $_REQUEST["form_email_conf"]){
				$arResult["FORM_ERRORS"]["SIMPLE_QUESTION_634_CONF"] = "Введенные Вами email не совпадают!";
			}
			elseif(isset($_REQUEST["form_email_378"]) && $_REQUEST["form_email_378"] != $_REQUEST["form_email_conf"]){
				$arResult["FORM_ERRORS"]["SIMPLE_QUESTION_579_CONF"] = "Email entered in field \"Please confirm your email\" isn't correct.";
			}
			if(isset($_REQUEST["form_text_310"]) && !isset($_POST[countries])){
				$arResult["FORM_ERRORS"]["SIMPLE_QUESTION_310"] = "Не заполнены следующие обязательные поля: Приоритетные направления";
			}
			if($arParams["CHECK_LOGIN"] != ''){
				$tempUser = CUser::GetByLogin($_REQUEST[$arParams["CHECK_LOGIN"]]);
				$arUser = $tempUser->Fetch();
				if($arUser){
					$arResult["FORM_ERRORS"]["LOGIN"] = GetMessage("FORM_ERROR_LOGIN");
				}
			}				

			if (
				$arParams['USE_EXTENDED_ERRORS'] == 'Y' && (!is_array($arResult["FORM_ERRORS"]) || count($arResult["FORM_ERRORS"]) <= 0)
				||
				$arParams['USE_EXTENDED_ERRORS'] != 'Y' && strlen($arResult["FORM_ERRORS"]) <= 0
			)
			{
				// check user session
				if (check_bitrix_sessid())
				{
					$return = false;

// ************************************************************* //
//                    ПРОВЕРКА ВСТРЕЧ                            //
// ************************************************************* //
					if($arParams["STEP"] == "CHECK" && $arParams["SECOND_FORM_ID"] != ''){
						if(array_search("66", $arResult["arrVALUES"][form_checkbox_SIMPLE_QUESTION_805]) !== false){
							$arParams["REGIST_USER"] = "N";
							$arParams["WEB_FORM_ID"] = $arParams["SECOND_FORM_ID"];
							//Перепишем массив значений $arResult["arrVALUES"], чтобы можно было сохранить нужные данные
							$comparison = array();
							$counter = 0;
							foreach($arResult["arQuestions"] as $quest){
								$comparison[$counter]["OLD_SID"] = $quest["SID"];
								$comparison[$counter]["OLD_ID"] = $quest["ID"];
								$comparison[$counter]["OLD_VARNAME"] = $quest["VARNAME"];
								$comparison[$counter]["OLD_TITLE"] = $quest["TITLE"];
								$comparison[$counter]["OLD_ANS_ID"] = $arResult["arAnswers"][$quest["SID"]][0]["ID"];
								$comparison[$counter]["OLD_ANS_TYPE"] = $arResult["arAnswers"][$quest["SID"]][0]["FIELD_TYPE"];
								$counter++;
							}
							$questions = array();
							$answers = array();
							CForm::GetDataByID($arParams["SECOND_FORM_ID"], $form, $questions, $answers, $dropdown, $multiselect);
							$counter = 0;
							foreach($questions as $quest){
								$comparison[$counter]["NEW_SID"] = $quest["SID"];
								$comparison[$counter]["NEW_ID"] = $quest["ID"];
								$comparison[$counter]["NEW_VARNAME"] = $quest["VARNAME"];
								$comparison[$counter]["NEW_TITLE"] = $quest["TITLE"];
								$comparison[$counter]["NEW_ANS_ID"] = $answers[$quest["SID"]][0]["ID"];
								$comparison[$counter]["NEW_ANS_TYPE"] = $answers[$quest["SID"]][0]["FIELD_TYPE"];
								$counter++;
							}
							$newVal = array();
							$flag = true;
							foreach($arResult["arrVALUES"] as $key => $value){
								for($i=0; $i< $counter; $i++){
									if($key == "form_text_".$comparison[$i]["OLD_ANS_ID"]){
										$newVal["form_text_".$comparison[$i]["NEW_ANS_ID"]] = $value;
										$flag = false;
									}
									elseif($key == "form_email_".$comparison[$i]["OLD_ANS_ID"]){
										$newVal["form_email_".$comparison[$i]["NEW_ANS_ID"]] = $value;
										$flag = false;
									}
									elseif($key == "form_url_".$comparison[$i]["OLD_ANS_ID"]){
										$newVal["form_url_".$comparison[$i]["NEW_ANS_ID"]] = $value;
										$flag = false;
									}
									elseif($key == $comparison[$i]["OLD_SID"]){
										$newVal[$comparison[$i]["NEW_SID"]] = $value;
										$flag = false;
									}
								}
								if($flag){
									$newVal[$key] = $value;
								}
								$flag = true;
							}
							$arResult["arrVALUES"] = $newVal;
						}
					}
// ************************************************************* //
//                      ПРОВЕРКА СТРАН                           //
// ************************************************************* //
					if(isset($_POST["countries"])){
						$arResult["arrVALUES"]["form_text_310"] = implode(", ", $_POST["countries"]);
					}

					// add result
					if($RESULT_ID = CFormResult::Add($arParams["WEB_FORM_ID"], $arResult["arrVALUES"]))
					{
// ************************************************************* //
//                 ПРОВЕРКА ВСТРЕЧ ШАГ2                          //
// ************************************************************* //
						if($arParams["STEP"] == "CHECK"){
							if(array_search("66", $arResult["arrVALUES"][form_checkbox_SIMPLE_QUESTION_805]) !== false){
								$arParams["SUCCESS_URL"] = "/ru/regist/step1/"; // Перенаправляем на другой шаг регистрации
							}
						}
// ************ ДОБАВЛЕНИЕ РЕЗУЛЬТАТА В ОСНОВНУЮ ФОРМУ ************ //
						if(isset($_POST["res_num"]) && $_POST["res_num"] != '' && $arParams["SECOND_FORM_ID"] != ''){
							$questions = array();
							$answers = array();
							$comparison = array();
							$counter = 0;
							CForm::GetDataByID($arParams["SECOND_FORM_ID"], $form, $questions, $answers, $dropdown, $multiselect);
							foreach($questions as $quest){
								foreach($arResult["arQuestions"] as $questOld){
									if($quest["TITLE"] == $questOld["TITLE"]){
										$comparison[$counter]["NEW_SID"] = $quest["SID"];
										$comparison[$counter]["NEW_ID"] = $quest["ID"];
										$comparison[$counter]["NEW_VARNAME"] = $quest["VARNAME"];
										$comparison[$counter]["NEW_TITLE"] = $quest["TITLE"];
										$comparison[$counter]["NEW_ANS_TYPE"] = $answers[$quest["SID"]][0]["FIELD_TYPE"];
										$comparison[$counter]["OLD_SID"] = $questOld["SID"];
										$comparison[$counter]["OLD_ID"] = $questOld["ID"];
										$comparison[$counter]["OLD_VARNAME"] = $questOld["VARNAME"];
										$comparison[$counter]["OLD_TITLE"] = $questOld["TITLE"];
										$comparison[$counter]["OLD_ANS_TYPE"] = $arResult["arAnswers"][$questOld["SID"]][0]["FIELD_TYPE"];
										if($answers[$quest["SID"]][0]["FIELD_TYPE"] == 'checkbox'){
											$comparison[$counter]["NEW_ANS_ID"] = $answers[$quest["SID"]];
											$comparison[$counter]["OLD_ANS_ID"] = $arResult["arAnswers"][$questOld["SID"]];
										}
										else{
											$comparison[$counter]["NEW_ANS_ID"] = $answers[$quest["SID"]][0]["ID"];
											$comparison[$counter]["OLD_ANS_ID"] = $arResult["arAnswers"][$questOld["SID"]][0]["ID"];
										}
										$counter++;
									}
								}
							}
							$newVal = array();
							$newAns = array();
							$flag = true;
							foreach($arResult["arrVALUES"] as $key => $value){
								for($i=0; $i< $counter; $i++){
									if($key == "form_text_".$comparison[$i]["OLD_ANS_ID"]){
										$newVal[$comparison[$i]["NEW_SID"]] = $value;
										$newAns[$comparison[$i]["NEW_SID"]] = $comparison[$i]["NEW_ANS_ID"];
									}
									elseif($key == "form_textarea_".$comparison[$i]["OLD_ANS_ID"]){
										$newVal[$comparison[$i]["NEW_SID"]] = $value;
										$newAns[$comparison[$i]["NEW_SID"]] = $comparison[$i]["NEW_ANS_ID"];
									}
									elseif($key == "form_email_".$comparison[$i]["OLD_ANS_ID"]){
										$newVal[$comparison[$i]["NEW_SID"]] = $value;
										$newAns[$comparison[$i]["NEW_SID"]] = $comparison[$i]["NEW_ANS_ID"];
									}
									elseif($key == "form_password_".$comparison[$i]["OLD_ANS_ID"]){
										$newVal[$comparison[$i]["NEW_SID"]] = $value;
										$newAns[$comparison[$i]["NEW_SID"]] = $comparison[$i]["NEW_ANS_ID"];
									}
									elseif($key == "form_checkbox_".$comparison[$i]["OLD_SID"]){
										foreach($value as $checkBox){
											$countBox = 0;
											foreach($comparison[$i]["OLD_ANS_ID"] as $checkBoxOld){
												if($checkBox == $checkBoxOld["ID"]){
													$newVal[$comparison[$i]["NEW_SID"]][$comparison[$i]["NEW_ANS_ID"][$countBox]["ID"]] = "";
												}
												$countBox++;
											}
										}
									}
									elseif($key == "form_url_".$comparison[$i]["OLD_ANS_ID"]){
										$newVal[$comparison[$i]["NEW_SID"]] = $value;
										$newAns[$comparison[$i]["NEW_SID"]] = $comparison[$i]["NEW_ANS_ID"];
									}
									elseif($key == "form_dropdown_".$comparison[$i]["OLD_SID"]){
										$newVal[$comparison[$i]["NEW_SID"]] = $value;
										$newAns[$comparison[$i]["NEW_SID"]] = $comparison[$i]["NEW_ANS_ID"];
									}
									elseif($key == $comparison[$i]["OLD_SID"]){
										$newVal[$comparison[$i]["NEW_SID"]] = $value;
										$newAns[$comparison[$i]["NEW_SID"]] = $comparison[$i]["NEW_ANS_ID"];
									}
								}
							}

							foreach($newVal as $key => $value){
								$arVALUE = array();
								$FIELD_SID = $key; // символьный идентификатор вопроса
								$ANSWER_ID = $newAns[$key];
								if(is_array($value)){
									$arVALUE = $value;
								}
								else{
									$arVALUE[$ANSWER_ID] = $value;
								}
								if(!CFormResult::SetField($_POST["res_num"], $FIELD_SID, $arVALUE)){
									echo "Ошибка<br />";
								}
							}
							$RESULT_ID = $_POST["res_num"];
						}					
						elseif($arParams["SECOND_FORM_ID"] != '' && $arParams["STEP"] != "CHECK"){
							$arParams["WEB_FORM_ID"] = $arParams["SECOND_FORM_ID"];
							//Перепишем массив значений $arResult["arrVALUES"], чтобы можно было сохранить нужные данные
							$comparison = array();
							$counter = 0;
							foreach($arResult["arQuestions"] as $quest){
								$comparison[$counter]["OLD_SID"] = $quest["SID"];
								$comparison[$counter]["OLD_ID"] = $quest["ID"];
								$comparison[$counter]["OLD_VARNAME"] = $quest["VARNAME"];
								$comparison[$counter]["OLD_TITLE"] = $quest["TITLE"];
								$comparison[$counter]["OLD_ANS_ID"] = $arResult["arAnswers"][$quest["SID"]][0]["ID"];
								$comparison[$counter]["OLD_ANS_TYPE"] = $arResult["arAnswers"][$quest["SID"]][0]["FIELD_TYPE"];
								$counter++;
							}
							$questions = array();
							$answers = array();
							CForm::GetDataByID($arParams["SECOND_FORM_ID"], $form, $questions, $answers, $dropdown, $multiselect);
							$counter = 0;
							foreach($questions as $quest){
								$comparison[$counter]["NEW_SID"] = $quest["SID"];
								$comparison[$counter]["NEW_ID"] = $quest["ID"];
								$comparison[$counter]["NEW_VARNAME"] = $quest["VARNAME"];
								$comparison[$counter]["NEW_TITLE"] = $quest["TITLE"];
								$comparison[$counter]["NEW_ANS_ID"] = $answers[$quest["SID"]][0]["ID"];
								$comparison[$counter]["NEW_ANS_TYPE"] = $answers[$quest["SID"]][0]["FIELD_TYPE"];
								$counter++;
							}
							echo "<pre>"; print_r($arResult["arrVALUES"]); echo "</pre>";
							$newVal = array();
							$flag = true;
							foreach($arResult["arrVALUES"] as $key => $value){
								for($i=0; $i< $counter; $i++){
									if($key == "form_text_".$comparison[$i]["OLD_ANS_ID"]){
										$newVal["form_text_".$comparison[$i]["NEW_ANS_ID"]] = $value;
										$flag = false;
									}
									elseif($key == "form_email_".$comparison[$i]["OLD_ANS_ID"]){
										$newVal["form_email_".$comparison[$i]["NEW_ANS_ID"]] = $value;
										$flag = false;
									}
									elseif($key == "form_url_".$comparison[$i]["OLD_ANS_ID"]){
										$newVal["form_url_".$comparison[$i]["NEW_ANS_ID"]] = $value;
										$flag = false;
									}
									elseif($key == $comparison[$i]["OLD_SID"]){
										$newVal[$comparison[$i]["NEW_SID"]] = $value;
										$flag = false;
									}
								}
								if($flag){
									$newVal[$key] = $value;
								}
								$flag = true;
							}
							$RESULT_ID = CFormResult::Add($arParams["WEB_FORM_ID"], $newVal);
						}
// ************************************************************* //
//               РЕГИСТРИРУЕМ НОВОГО ПОЛЬЗОВАТЕЛЯ                //
// ************************************************************* //
						if($arParams["REGIST_USER"] == "Y" && $arParams["REGIST_GROUP"] != '' && isset($_POST["res_num"]) && $_POST["res_num"] != '' && $arParams["SECOND_FORM_ID"] != ''){
							  function generatePassword($length = 8)
							  {
								$chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
								$numChars = strlen($chars);
							
								$string = '';
								for ($i = 0; $i < $length; $i++)
								  $string .= substr($chars, rand(1, $numChars) - 1, 1);
								return $string;
							  }

							$newEmail = '';
							$newLogin = '';
							$newPassword = '';
							$filterStr = '';

							$FORM_ID = $arParams["SECOND_FORM_ID"];

							if($arParams["LOGIN_FIELD"] != ''){
								$filterStr .= $arParams["LOGIN_FIELD"];
							}
							if($arParams["PASS_FIELD"] != ''){
								$filterStr .= " | ".$arParams["PASS_FIELD"];
							}
							if($arParams["EMAIL_FIELD"] != ''){
								$filterStr .= " | ".$arParams["EMAIL_FIELD"];
							}
							
							CForm::GetResultAnswerArray($FORM_ID, 
								$arrColumns, 
								$arrAnswers, 
								$arrAnswersVarname, 
								array("FIELD_ID" => $filterStr, "RESULT_ID" => $_POST["res_num"]));

							foreach($arrAnswersVarname[$_POST["res_num"]] as $field){
								switch ($field[0]["FIELD_ID"]) {
									case $arParams["EMAIL_FIELD"]:
										$newEmail = trim($field[0]["USER_TEXT"]);
										break;
									case $arParams["LOGIN_FIELD"]:
										$newLogin = trim($field[0]["USER_TEXT"]);
										break;
									case $arParams["PASS_FIELD"]:
										$newPassword = $field[0]["USER_TEXT"];
										break;
								}
							}
							$user = new CUser;
							if($arParams["LOGIN_FIELD"] == ''){
								$password = generatePassword();
								$arFields = Array(
								  "EMAIL"             => $newEmail,
								  "LOGIN"             => $newEmail,
								  "GROUP_ID"          => array($arParams["REGIST_GROUP"]),
								  "PASSWORD"          => $password,
								  "CONFIRM_PASSWORD"  => $password,
								  "ADMIN_NOTES"       => $password,
								  "UF_ANKETA"         => $_POST["res_num"],
								);
							}
							elseif($arParams["PASS_FIELD"] == ''){
								$password = generatePassword();
								$arFields = Array(
								  "EMAIL"             => $newEmail,
								  "LOGIN"             => $newLogin,
								  "GROUP_ID"          => array($arParams["REGIST_GROUP"]),
								  "PASSWORD"          => $password,
								  "CONFIRM_PASSWORD"  => $password,
								  "ADMIN_NOTES"       => $password,
								  "UF_ANKETA"         => $_POST["res_num"],
								);
							}
							else{
								$arFields = Array(
								  "EMAIL"             => $newEmail,
								  "LOGIN"             => $newLogin,
								  "GROUP_ID"          => array($arParams["REGIST_GROUP"]),
								  "PASSWORD"          => $newPassword,
								  "CONFIRM_PASSWORD"  => $newPassword,
								  "ADMIN_NOTES"       => $newPassword,
								  "UF_ANKETA"         => $_POST["res_num"],
								);
							}
							$ID = $user->Add($arFields);
						}				
						
						//$arResult["FORM_NOTE"] = GetMessage("FORM_DATA_SAVED1").$RESULT_ID.GetMessage("FORM_DATA_SAVED2");
						$arResult["FORM_RESULT"] = 'addok';
						
						// send email notifications
						CFormResult::SetEvent($RESULT_ID);
						CFormResult::Mail($RESULT_ID);
						
						// choose type of user redirect and do it
						
						if ($arResult["F_RIGHT"] >= 15)
						{
							if (strlen($_REQUEST["web_form_submit"])>0 && strlen($arParams["LIST_URL"]) > 0)
							{
								if ($arParams["SEF_MODE"] == "Y")
								{
									//LocalRedirect($arParams["LIST_URL"]."?strFormNote=".urlencode($arResult["FORM_NOTE"]));
									LocalRedirect(
										str_replace(
											array('#WEB_FORM_ID#', '#RESULT_ID#'),
											array($arParams['WEB_FORM_ID'], $RESULT_ID),
											$arParams["LIST_URL"]
										)."?formresult=".urlencode($arResult["FORM_RESULT"]).'&RESULT_ID='.$RESULT_ID
									);
								}
								else
								{
									//LocalRedirect($arParams["LIST_URL"].(strpos($arParams["LIST_URL"], "?") === false ? "?" : "&")."WEB_FORM_ID=".$arParams["WEB_FORM_ID"]."&RESULT_ID=".$RESULT_ID."&strFormNote=".urlencode($arResult["FORM_NOTE"]));
									LocalRedirect(
										$arParams["LIST_URL"]
										.(strpos($arParams["LIST_URL"], "?") === false ? "?" : "&")
										."WEB_FORM_ID=".$arParams["WEB_FORM_ID"]
										."&RESULT_ID=".$RESULT_ID
										."&formresult=".urlencode($arResult["FORM_RESULT"])
									);
								}
							}
							elseif (strlen($_REQUEST["web_form_apply"])>0 && strlen($arParams["EDIT_URL"])>0)
							{
								if ($arParams["SEF_MODE"] == "Y")
								{
									//LocalRedirect(str_replace("#RESULT_ID#", $RESULT_ID, $arParams["EDIT_URL"])."?strFormNote=".urlencode($arResult["FORM_NOTE"]));
									LocalRedirect(
										str_replace(
											array('#WEB_FORM_ID#', '#RESULT_ID#'),
											array($arParams['WEB_FORM_ID'], $RESULT_ID),
											$arParams["EDIT_URL"]
										)
										.(strpos($arParams["EDIT_URL"], "?") === false ? "?" : "&")
										."formresult=".urlencode($arResult["FORM_RESULT"])
									);
								}
								else
								{
									LocalRedirect(
										$arParams["EDIT_URL"]
										.(strpos($arParams["EDIT_URL"], "?") === false ? "?" : "&")
										."WEB_FORM_ID=".$arParams["WEB_FORM_ID"]
										."&RESULT_ID=".$RESULT_ID
										."&formresult=".urlencode($arResult["FORM_RESULT"])
									);
								}
								die();
							}
							
							$arResult["return"] = true;
						}

						if (strlen($arParams["SUCCESS_URL"]) > 0)
						{
							if ($arParams['SEF_MODE'] == 'Y')
							{
								LocalRedirect(
									str_replace(
										array('#WEB_FORM_ID#', '#RESULT_ID#'),
										array($arParams['WEB_FORM_ID'], $RESULT_ID),
										$arParams["SUCCESS_URL"]
									)
									.(strpos($arParams["SUCCESS_URL"], "?") === false ? "?" : "&")
									."formresult=".urlencode($arResult["FORM_RESULT"])."&num=".$RESULT_ID
								);
							}
							else
							{
								LocalRedirect(
									$arParams["SUCCESS_URL"]
									.(strpos($arParams["SUCCESS_URL"], "?") === false ? "?" : "&")
									."WEB_FORM_ID=".$arParams["WEB_FORM_ID"]
									."&RESULT_ID=".$RESULT_ID
									."&formresult=".urlencode($arResult["FORM_RESULT"])
								);
							}
							
							die();
						}
						elseif ($arParams["SEF_MODE"] == "Y")
						{
							LocalRedirect(
								$APPLICATION->GetCurPageParam(
									"formresult=".urlencode($arResult["FORM_RESULT"]), 
									array('formresult', 'strFormNote', 'SEF_APPLICATION_CUR_PAGE_URL')
								)
							);
							
							die();
						}
						else
						{
							LocalRedirect(
								$APPLICATION->GetCurPageParam(
									"WEB_FORM_ID=".$arParams["WEB_FORM_ID"]
									."&RESULT_ID=".$RESULT_ID
									."&formresult=".urlencode($arResult["FORM_RESULT"]), 
									array('formresult', 'strFormNote', 'WEB_FORM_ID', 'RESULT_ID')
								)
							);
							
							die();
							//LocalRedirect($APPLICATION->GetCurPage()."?WEB_FORM_ID=".$arParams["WEB_FORM_ID"]."&strFormNote=".urlencode($arResult["FORM_NOTE"]));
						}
					}
					else
					{
						if ($arParams['USE_EXTENDED_ERRORS'] == 'Y')
							$arResult["FORM_ERRORS"] = array($GLOBALS["strError"]);
						else
							$arResult["FORM_ERRORS"] = $GLOBALS["strError"];
					}
				}
			}
		}
		
		/*
		if (is_array($arResult["FORM_ERRORS"])) 
		{
			$arResult["FORM_ERRORS"] = implode("<br />", $arResult["FORM_ERRORS"]);
		}
		*/

		//if (!empty($_REQUEST["strFormNote"])) $arResult["FORM_NOTE"] = $_REQUEST["strFormNote"];
		if (!empty($_REQUEST["formresult"]) && ($_REQUEST['WEB_FORM_ID'] == $arParams['WEB_FORM_ID'] || $arParams['SEF_MODE'] == 'Y')) 
		{
			$formResult = strtoupper($_REQUEST['formresult']);
			switch ($formResult)
			{
				case 'ADDOK':
					$arResult['FORM_NOTE'] = str_replace("#RESULT_ID#", $RESULT_ID, GetMessage('FORM_NOTE_ADDOK'));
			}
		}
		
		$arResult["isFormErrors"] = 
			(
				$arParams['USE_EXTENDED_ERRORS'] == 'Y' && is_array($arResult["FORM_ERRORS"]) && count($arResult["FORM_ERRORS"]) > 0
				||
				$arParams['USE_EXTENDED_ERRORS'] != 'Y' && strlen($arResult["FORM_ERRORS"]) > 0
			)
			? "Y" : "N";
				
		// ************************************************************* //
		//                                             output                                                                    //
		// ************************************************************* //

		//echo '<pre>'; print_r($arResult['arForm']); echo '</pre>';
		
		if ($arParams["IGNORE_CUSTOM_TEMPLATE"] == "N" && $arResult["arForm"]["USE_DEFAULT_TEMPLATE"] == "N" && strlen($arResult["arForm"]["FORM_TEMPLATE"]) > 0)
		{
			// use visual template
			if (!$bCache || $bCache && !$bVarsFromCache)
			{
				if ($bCache)
				{
					$obFormCache->StartDataCache();
				}
				
				//if (is_array($arResult['FORM_ERRORS']))
					//$arResult['FORM_ERRORS'] = implode('<br />', $arResult['FORM_ERRORS']);
				
				$FORM = new CFormOutput();
				// initialize template
				
				
				$FORM->InitializeTemplate($arParams, $arResult);
				//echo '<pre>',htmlspecialchars(print_r($arParams, true)),htmlspecialchars(print_r($arResult, true)),htmlspecialchars(print_r($FORM, true)),'</pre>';
				
				// cache image files paths
				$FORM->ShowFormImage();
				$FORM->getFormImagePath();

				if ($bCache)
				{
					$obFormCache->EndDataCache(
						array(
							"arResult" => $arResult,
							"FORM" => $FORM,
						)
					);
				}
			}
			else
			{
				$FORM->strFormNote = $arResult['FORM_NOTE'];
				$FORM->isFormNote = (bool)$arResult['FORM_NOTE'];
			}
		
			// if form uses CAPCHA initialize it
			if ($arResult["arForm"]["USE_CAPTCHA"] == "Y") $FORM->CAPTCHACode = $arResult["CAPTCHACode"] = $APPLICATION->CaptchaGetCode();
		
			// get template
			if ($strReturn = $FORM->IncludeFormCustomTemplate())
			{
				// add icons
				$back_url = $_SERVER['REQUEST_URI'];
				
				$editor = "/bitrix/admin/fileman_file_edit.php?full_src=Y&site=".SITE_ID."&";
				$href = "javascript:window.location='".$editor."path=".urlencode($path)."&lang=".LANGUAGE_ID."&back_url=".urlencode($back_url)."'";
				
				if ($arParams['USE_EXTENDED_ERRORS'] == 'Y')
					$APPLICATION->SetAdditionalCSS($this->GetPath()."/error.css");
				
				if ($APPLICATION->GetShowIncludeAreas() && $USER->IsAdmin())
				{
					$APPLICATION->SetAdditionalCSS($this->GetPath()."/icons.css");
					// define additional icons for Site Edit mode
					$arIcons = array(
						// form template edit icon
						array(
							'URL' => "javascript:".$APPLICATION->GetPopupLink(
								array(
									'URL' => "/bitrix/admin/form_edit.php?bxpublic=Y&from_module=form&lang=".LANGUAGE_ID."&ID=".$FORM->WEB_FORM_ID."&tabControl_active_tab=edit5&back_url=".urlencode($_SERVER["REQUEST_URI"]),
									'PARAMS' => array(
										'width' => 700,
										'height' => 500,
										'resize' => false,
									)
								)
							),
							'ICON' => 'form-edit-tpl',
							'TITLE' => GetMessage("FORM_PUBLIC_ICON_EDIT_TPL")
						),
						
						// form params edit icon
						/*array(
							'URL' => "/bitrix/admin/form_edit.php?lang=".LANGUAGE_ID."&ID=".$FORM->WEB_FORM_ID."&back_url=".urlencode($_SERVER["REQUEST_URI"]),
							'ICON' => 'form-edit',
							'TITLE' => GetMessage("FORM_PUBLIC_ICON_EDIT")
						),*/

						array(
							'URL' => "javascript:".$APPLICATION->GetPopupLink(
								array(
									'URL' => "/bitrix/admin/form_edit.php?bxpublic=Y&from_module=form&lang=".LANGUAGE_ID."&ID=".$FORM->WEB_FORM_ID."&back_url=".urlencode($_SERVER["REQUEST_URI"]),
									'PARAMS' => array(
										'width' => 700,
										'height' => 500,
										'resize' => false,
									)
								)
							),
							'ICON' => 'form-edit',
							'TITLE' => GetMessage("FORM_PUBLIC_ICON_EDIT"),
							'DEFAULT' => ($APPLICATION->GetPublicShowMode() != 'configure' ? true : false),
							"MODE" => array("edit", "configure"),
						),
					);
					
					$this->AddIncludeAreaIcons($arIcons);
				}
				
				// output template
				echo $strReturn;
				
				return;
			}
		}
		
		if ($arResult["arForm"]["USE_CAPTCHA"] == "Y") $arResult["CAPTCHACode"] = $APPLICATION->CaptchaGetCode();

		// include CSS with additional icons for Site Edit mode
		if ($APPLICATION->GetShowIncludeAreas() && $USER->IsAdmin())
		{
			$APPLICATION->SetAdditionalCSS($this->GetPath()."/icons.css");
			// define additional icons for Site Edit mode
			$arIcons = array(
				// form params edit icon
				array(
					'URL' => "javascript:".$APPLICATION->GetPopupLink(
								array(
									'URL' => "/bitrix/admin/form_edit.php?bxpublic=Y&from_module=form&lang=".LANGUAGE_ID."&ID=".$arParams["WEB_FORM_ID"]."&back_url=".urlencode($_SERVER["REQUEST_URI"]),
									'PARAMS' => array(
										'width' => 700,
										'height' => 500,
										'resize' => false,
									)
								)
							),
					'ICON' => 'form-edit',
					'TITLE' => GetMessage("FORM_PUBLIC_ICON_EDIT"),
					'DEFAULT' => ($APPLICATION->GetPublicShowMode() != 'configure' ? true : false),
					"MODE" => array("edit", "configure"),
					
				),
			);

			// append icons
			$this->AddIncludeAreaIcons($arIcons);
		}
			
		// define variables to assign
		$arResult = array_merge(
			$arResult,
			array(
				"isFormNote"			=> strlen($arResult["FORM_NOTE"]) ? "Y" : "N", // flag "is there a form note"
				"isAccessFormParams"	=> $arResult["F_RIGHT"] >= 25 ? "Y" : "N", // flag "does current user have access to form params"
				"isStatisticIncluded"	=> CModule::IncludeModule('statistic') ? "Y" : "N", // flag "is statistic module included"
				
				"FORM_HEADER" => sprintf( // form header (<form> tag and hidden inputs)
					"<form name=\"%s\" action=\"%s\" method=\"%s\" enctype=\"multipart/form-data\">", 
					$arResult["arForm"]["SID"], POST_FORM_ACTION_URI, "POST"
				).$res .= bitrix_sessid_post().'<input type="hidden" name="WEB_FORM_ID" value="'.$arParams['WEB_FORM_ID'].'" />',
				
				"FORM_TITLE"			=> trim(htmlspecialchars($arResult["arForm"]["NAME"])), // form title
				
				"FORM_DESCRIPTION" => // form description
					$arResult["arForm"]["DESCRIPTION_TYPE"] == "html" ? 
					trim($arResult["arForm"]["DESCRIPTION"]) : 
					nl2br(htmlspecialchars(trim($arResult["arForm"]["DESCRIPTION"]))),
				
				"isFormTitle"			=> strlen($arResult["arForm"]["NAME"]) > 0 ? "Y" : "N", // flag "does form have title"
				"isFormDescription"		=> strlen($arResult["arForm"]["DESCRIPTION"]) > 0 ? "Y" : "N", // flag "does form have description"
				"isFormImage"			=> intval($arResult["arForm"]["IMAGE_ID"]) > 0 ? "Y" : "N", // flag "does form have image"
				"isUseCaptcha"			=> $arResult["arForm"]["USE_CAPTCHA"] == "Y", // flag "does form use captcha"
				"DATE_FORMAT"			=> CLang::GetDateFormat("SHORT"), // current site date format
				"REQUIRED_SIGN"			=> CForm::ShowRequired("Y"), // "required" sign
				"FORM_FOOTER"			=> "</form>", // form footer (close <form> tag)
			)
		);

		/*
		if ($arResult["isFormNote"] == "Y")
		{
			ob_start();
			ShowMessage($arResult["FORM_NOTE"]);
			$arResult["FORM_NOTE"] = ob_get_contents();
			ob_end_clean();
		}
		*/
			
		// get template vars for form image
		if ($arResult["isFormImage"] == "Y")
		{
			$arResult["FORM_IMAGE"]["ID"] = $arResult["arForm"]["IMAGE_ID"];
			// assign form image url
			$arResult["FORM_IMAGE"]["URL"] = CFile::GetPath($arResult["arForm"]["IMAGE_ID"]);
			
			// check image file existance and assign image data
			if (
				file_exists($_SERVER["DOCUMENT_ROOT"].$arResult["FORM_IMAGE"]["URL"]) 
				&& 
				list(
					$arResult["FORM_IMAGE"]["WIDTH"], 
					$arResult["FORM_IMAGE"]["HEIGHT"], 
					$arResult["FORM_IMAGE"]["TYPE"], 
					$arResult["FORM_IMAGE"]["ATTR"]
				) = @getimagesize($_SERVER["DOCUMENT_ROOT"].$arResult["FORM_IMAGE"]["URL"])
			)
			{
				$arResult["FORM_IMAGE"]["HTML_CODE"] = CFile::ShowImage($arResult["arForm"]["IMAGE_ID"]);
			}
		}
		
		$arResult["QUESTIONS"] = array();
		reset($arResult["arQuestions"]);
		
		// assign questions data
		foreach ($arResult["arQuestions"] as $key => $arQuestion)
		{
			$FIELD_SID = $arQuestion["SID"];
			$arResult["QUESTIONS"][$FIELD_SID] = array(
				"CAPTION" => // field caption
					$arResult["arQuestions"][$FIELD_SID]["TITLE_TYPE"] == "html" ? 
					$arResult["arQuestions"][$FIELD_SID]["TITLE"] : 
					nl2br(htmlspecialchars($arResult["arQuestions"][$FIELD_SID]["TITLE"])), 
					
				"IS_HTML_CAPTION"			=> $arResult["arQuestions"][$FIELD_SID]["TITLE_TYPE"] == "html" ? "Y" : "N",
				"REQUIRED"					=> $arResult["arQuestions"][$FIELD_SID]["REQUIRED"] == "Y" ? "Y" : "N", 
				"IS_INPUT_CAPTION_IMAGE"	=> intval($arResult["arQuestions"][$FIELD_SID]["IMAGE_ID"]) > 0 ? "Y" : "N",
			);
			
			// ******************************** customize answers ***************************** //
			
			$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"] = array();
			
			if (is_array($arResult["arAnswers"][$FIELD_SID]))
			{
				$res = "";
			
				reset($arResult["arAnswers"][$FIELD_SID]);
				if (is_array($arResult["arDropDown"][$FIELD_SID])) reset($arResult["arDropDown"][$FIELD_SID]);
				if (is_array($arResult["arMutiselect"][$FIELD_SID])) reset($arResult["arMutiselect"][$FIELD_SID]);

				$show_dropdown = "N";
				$show_multiselect = "N";

				foreach ($arResult["arAnswers"][$FIELD_SID] as $key => $arAnswer)
				{
					//echo "<pre>".$FIELD_SID." ".$key." "; print_r($arAnswer); echo "</pre>";
					if ($arAnswer["FIELD_TYPE"]=="dropdown" && $show_dropdown=="Y") continue;
					if ($arAnswer["FIELD_TYPE"]=="multiselect" && $show_multiselect=="Y") continue;
					
					$res = "";
					
					switch ($arAnswer["FIELD_TYPE"]) 
					{
						case "radio":
							if (strpos($arAnswer["FIELD_PARAM"], "id=") === false)
							{
								$ans_id = $arAnswer["ID"];
								$arAnswer["FIELD_PARAM"] .= " id=\"".$ans_id."\"";
							}
							else
							{
								$ans_id = "";
							}
						
							$value = CForm::GetRadioValue($FIELD_SID, $arAnswer, $arResult["arrVALUES"]);
							
							if ($arResult["isFormErrors"] == 'Y')
							{
								if (
									strpos(strtolower($arAnswer["FIELD_PARAM"]), "selected")!==false 
									|| 
									strpos(strtolower($arAnswer["FIELD_PARAM"]), "checked")!==false)
									{
										$arAnswer["FIELD_PARAM"] = eregi_replace("checked|selected", "", $arAnswer["FIELD_PARAM"]);
									}
							}							
							
							$input = CForm::GetRadioField(
								$FIELD_SID,
								$arAnswer["ID"],
								$value,
								$arAnswer["FIELD_PARAM"]);
							
							
							if (strlen($ans_id) > 0)
							{
								$res .= $input;
								$res .= "<label for=\"".$ans_id."\">".$arAnswer["MESSAGE"]."</label>";
							}
							else
							{
								$res .= "<label>".$input.$arAnswer["MESSAGE"]."</label>";
							}
							
							$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res;
							
							break;
						case "checkbox":
							if (strpos($arAnswer["FIELD_PARAM"], "id=") === false)
							{
								$ans_id = $arAnswer["ID"];
								$arAnswer["FIELD_PARAM"] .= " id=\"".$ans_id."\"";
							}
							else
							{
								$ans_id = "";
							}					
						
							$value = CForm::GetCheckBoxValue($FIELD_SID, $arAnswer, $arResult["arrVALUES"]);

							if ($arResult['isFormErrors'] == 'Y')
							{
								if (
									strpos(strtolower($arAnswer["FIELD_PARAM"]), "selected")!==false 
									|| 
									strpos(strtolower($arAnswer["FIELD_PARAM"]), "checked")!==false)
									{
										$arAnswer["FIELD_PARAM"] = eregi_replace("checked|selected", "", $arAnswer["FIELD_PARAM"]);
									}
							}
							
							$input = CForm::GetCheckBoxField(
								$FIELD_SID,
								$arAnswer["ID"],
								$value,
								$arAnswer["FIELD_PARAM"]);
								
							
							if (strlen($ans_id) > 0)
							{
								$res .= $input."<label for=\"".$ans_id."\">".$arAnswer["MESSAGE"]."</label>";
							}
							else
							{
								$res .= "<label>".$input.$arAnswer["MESSAGE"]."</label>";
							}
							
							$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res;
							
							break;
						case "dropdown":
							if ($show_dropdown!="Y")
							{
								$value = CForm::GetDropDownValue($FIELD_SID, $arResult["arDropDown"], $arResult["arrVALUES"]);
								
								if (strlen($arResult["FORM_ERROR"]) > 0)
									for ($i=0;$i<=count($arDropDown[$FIELD_SID]["param"])-1;$i++)
										$arDropDown[$FIELD_SID]["param"][$i] = eregi_replace("checked|selected", "", $arDropDown[$FIELD_SID]["param"][$i]);
										
								$res .= CForm::GetDropDownField(
									$FIELD_SID,
									$arResult["arDropDown"][$FIELD_SID],
									$value,
									$arAnswer["FIELD_PARAM"]);
								$show_dropdown = "Y";
							}
							
							$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res;
							
							break;
						case "multiselect":
							if ($show_multiselect!="Y")
							{
								$value = CForm::GetMultiSelectValue($FIELD_SID, $arResult["arMultiSelect"], $arResult["arrVALUES"]);
								
								if (strlen($arResult["FORM_ERROR"]) > 0)
									for ($i=0;$i<=count($arResult["arMultiSelect"][$FIELD_SID]["param"])-1;$i++)
										$arResult["arMultiSelect"][$FIELD_SID]["param"][$i] = eregi_replace("checked|selected", "", $arResult["arMultiSelect"][$FIELD_SID]["param"][$i]);								
								$res .= CForm::GetMultiSelectField(
									$FIELD_SID,
									$arResult["arMultiSelect"][$FIELD_SID],
									$value,
									$arAnswer["FIELD_HEIGHT"],
									$arAnswer["FIELD_PARAM"]
								);
									
								$show_multiselect = "Y";
							}
							
							$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res;
							
							break;
						case "text":
							if (strlen(trim($arAnswer["MESSAGE"]))>0) 
							{
								$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $arAnswer["MESSAGE"];
							}
							
							$value = CForm::GetTextValue($arAnswer["ID"], $arAnswer, $arResult["arrVALUES"]);
							$res .= CForm::GetTextField(
								$arAnswer["ID"],
								$value,
								$arAnswer["FIELD_WIDTH"],
								$arAnswer["FIELD_PARAM"]);
								
							$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res;
							
							break;
							
						case "hidden":

							$value = CForm::GetHiddenValue($arAnswer["ID"], $arAnswer, $arResult["arrVALUES"]);
							$res .= CForm::GetHiddenField(
								$arAnswer["ID"],
								$value,
								$arAnswer["FIELD_PARAM"]);
								
							$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res;							
							
							break;
							
						case "password":
							if (strlen(trim($arAnswer["MESSAGE"]))>0) 
							{
								$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $arAnswer["MESSAGE"];
							}
							
							$value = CForm::GetPasswordValue($arAnswer["ID"], $arAnswer, $arResult["arrVALUES"]);
							$res .= CForm::GetPasswordField(
								$arAnswer["ID"],
								$value,
								$arAnswer["FIELD_WIDTH"],
								$arAnswer["FIELD_PARAM"]);
								
							$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res;
							
							break;
						case "email":
							if (strlen(trim($arAnswer["MESSAGE"]))>0) 
							{
								$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $arAnswer["MESSAGE"];
							}
							$value = CForm::GetEmailValue($arAnswer["ID"], $arAnswer, $arResult["arrVALUES"]);
							$res .= CForm::GetEmailField(
								$arAnswer["ID"],
								$value,
								$arAnswer["FIELD_WIDTH"],
								$arAnswer["FIELD_PARAM"]);
							
							$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res;							
							
							break;
						case "url":
							if (strlen(trim($arAnswer["MESSAGE"]))>0) 
							{
								$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $arAnswer["MESSAGE"];
							}
							$value = CForm::GetUrlValue($arAnswer["ID"], $arAnswer, $arResult["arrVALUES"]);
							$res .= CForm::GetUrlField(
								$arAnswer["ID"],
								$value,
								$arAnswer["FIELD_WIDTH"],
								$arAnswer["FIELD_PARAM"]);
								
							$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res;
							
							break;
						case "textarea":
							if (strlen(trim($arAnswer["MESSAGE"]))>0) 
							{
								$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $arAnswer["MESSAGE"];
							}
							
							if (intval($arAnswer["FIELD_WIDTH"]) <= 0) $arAnswer["FIELD_WIDTH"] = "40";
							if (intval($arAnswer["FIELD_HEIGHT"]) <= 0) $arAnswer["FIELD_HEIGHT"] = "5";
							
							$value = CForm::GetTextAreaValue($arAnswer["ID"], $arAnswer, $arResult["arrVALUES"]);
							$res .= CForm::GetTextAreaField(
								$arAnswer["ID"],
								$arAnswer["FIELD_WIDTH"],
								$arAnswer["FIELD_HEIGHT"],
								$arAnswer["FIELD_PARAM"],
								$value
								);
								
							$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res;
							
							break;
						case "date":
							if (strlen(trim($arAnswer["MESSAGE"]))>0) 
							{
								$res .= $arAnswer["MESSAGE"];
							}
							$value = CForm::GetDateValue($arAnswer["ID"], $arAnswer, $arResult["arrVALUES"]);
							$res .= CForm::GetDateField(
								$arAnswer["ID"],
								$arResult["arForm"]["SID"],
								$value,
								$arAnswer["FIELD_WIDTH"],
								$arAnswer["FIELD_PARAM"]);
								
							$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res." (".CSite::GetDateFormat("SHORT").")";
							
							break;
						case "image":
							$res .= CForm::GetFileField(
								$arAnswer["ID"],
								$arAnswer["FIELD_WIDTH"],
								"IMAGE",
								0,
								"",
								$arAnswer["FIELD_PARAM"]);
								
							$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res;
							
							break;
						case "file":
							
							$res .= CForm::GetFileField(
								$arAnswer["ID"],
								$arAnswer["FIELD_WIDTH"],
								"FILE",
								0,
								"",
								$arAnswer["FIELD_PARAM"]);
								
							$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res;
							
							break;
					} //endswitch;
				} //endwhile;
				
				
			} //endif(is_array($arAnswers[$FIELD_SID]));
			elseif (is_array($arResult["arQuestions"][$FIELD_SID]) && $arResult["arQuestions"][$FIELD_SID]["ADDITIONAL"] == "Y")
			{
			
				$res = "";
				
				switch ($arResult["arQuestions"][$FIELD_SID]["FIELD_TYPE"])
				{
					case "text":
						$value = CForm::GetTextAreaValue("ADDITIONAL_".$arResult["arQuestions"][$FIELD_SID]["ID"], array(), $arResult["arrVALUES"]);
						$res .= CForm::GetTextAreaField(
							"ADDITIONAL_".$arResult["arQuestions"][$FIELD_SID]["ID"],
							"60",
							"5",
							"",
							$value
							);
							
						$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res;
						
						break;
					case "integer":
						$value = CForm::GetTextValue("ADDITIONAL_".$arResult["arQuestions"][$FIELD_SID]["ID"], array(), $arResult["arrVALUES"]);
						$res .= CForm::GetTextField(
							"ADDITIONAL_".$arResult["arQuestions"][$FIELD_SID]["ID"], 
							$value);
							
						$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res;
						
						break;
					case "date":
						$value = CForm::GetDateValue("ADDITIONAL_".$arResult["arQuestions"][$FIELD_SID]["ID"], array(), $arResult["arrVALUES"]);
						$res .= CForm::GetDateField(
							"ADDITIONAL_".$arResult["arQuestions"][$FIELD_SID]["ID"],
							$arResult["arForm"]["SID"],
							$value);
							
						$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"][] = $res." (".CSite::GetDateFormat("SHORT").")";
						
						break;
				} //endswitch;
			}
			
			$arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"] = implode("<br />", $arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"]);
			
			// ******************************************************************************* //
			
			if ($arResult["QUESTIONS"][$FIELD_SID]["IS_INPUT_CAPTION_IMAGE"] == "Y")
			{
				$arResult["QUESTIONS"][$FIELD_SID]["IMAGE"]["ID"] = $arResult["arQuestions"][$FIELD_SID]["IMAGE_ID"];
				
				// assign field image path
				$arResult["QUESTIONS"][$FIELD_SID]["IMAGE"]["URL"] = CFile::GetPath($arResult["arQuestions"][$FIELD_SID]["IMAGE_ID"]);
				
				// check image file existance and assign image data
				if (
					file_exists($_SERVER["DOCUMENT_ROOT"].$arResult["QUESTIONS"][$FIELD_SID]["IMAGE"]["URL"]) 
					&& 
					list(
						$arResult["QUESTIONS"][$FIELD_SID]["IMAGE"]["WIDTH"], 
						$arResult["QUESTIONS"][$FIELD_SID]["IMAGE"]["HEIGHT"], 
						$arResult["QUESTIONS"][$FIELD_SID]["IMAGE"]["TYPE"], 
						$arResult["QUESTIONS"][$FIELD_SID]["IMAGE"]["ATTR"]
					) = @getimagesize($_SERVER["DOCUMENT_ROOT"].$arResult["QUESTIONS"][$FIELD_SID]["IMAGE"]["URL"])
				)
				{
					$arResult["QUESTIONS"][$FIELD_SID]["IMAGE"]["HTML_CODE"] = CFile::ShowImage($arResult["arQuestions"][$FIELD_SID]["IMAGE_ID"]);
				}
			}
			
			// get answers raw structure
			$arResult["QUESTIONS"][$FIELD_SID]["STRUCTURE"] = $arResult["arAnswers"][$FIELD_SID];
			
			// nullify value
			$arResult["QUESTIONS"][$FIELD_SID]["VALUE"] = "";
		}
		
		// compability:
		
		if ($arResult["isFormErrors"] == "Y")
		{
			ob_start();
			if ($arParams['USE_EXTENDED_ERRORS'] == 'N')
				ShowError($arResult["FORM_ERRORS"]);
			else
				ShowError(implode('<br />', $arResult["FORM_ERRORS"]));
			
			$arResult["FORM_ERRORS_TEXT"] = ob_get_contents();
			ob_end_clean();
		}
		
		$arResult["SUBMIT_BUTTON"] = "<input ".(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "")." type=\"submit\" name=\"web_form_submit\" value=\"".(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"])."\" />";
		$arResult["APPLY_BUTTON"] = "<input type=\"hidden\" name=\"web_form_apply\" value=\"Y\" /><input type=\"submit\" name=\"web_form_apply\" value=\"".GetMessage("FORM_APPLY")."\" />";
		$arResult["RESET_BUTTON"] = "<input type=\"reset\" value=\"".GetMessage("FORM_RESET")."\" />";
		$arResult["REQUIRED_STAR"] = $arResult["REQUIRED_SIGN"];
		$arResult["CAPTCHA_IMAGE"] = "<input type=\"hidden\" name=\"captcha_sid\" value=\"".htmlspecialchars($arResult["CAPTCHACode"])."\" /><img src=\"/bitrix/tools/captcha.php?captcha_sid=".htmlspecialchars($arResult["CAPTCHACode"])."\" width=\"180\" height=\"40\" />";
		$arResult["CAPTCHA_FIELD"] = "<input type=\"text\" name=\"captcha_word\" size=\"30\" maxlength=\"50\" value=\"\" class=\"inputtext\" />";
		$arResult["CAPTCHA"] = $arResult["CAPTCHA_IMAGE"]."<br />".$arResult["CAPTCHA_FIELD"];
		
		if ($bCache)
		{
			$obFormCache->StartDataCache();
			$obFormCache->EndDataCache(
				array(
					"arResult" => $arResult,
				)
			);
		}
		
		// include default template
		$this->IncludeComponentTemplate();
	}
	else
	{
		ShowError(GetMessage($arResult["ERROR"]));
	}
}
else
{
	echo ShowError(GetMessage("FORM_MODULE_NOT_INSTALLED"));
}
?>