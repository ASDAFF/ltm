<?
$name_c = strip_tags($_REQUEST['name_c']);
$det = strip_tags($_REQUEST['det']);
$address = strip_tags($_REQUEST['address']);
$index = strip_tags($_REQUEST['index']);
$town = strip_tags($_REQUEST['town']);
$country = strip_tags($_REQUEST['country']);
$name = strip_tags($_REQUEST['name']);
$f_name = strip_tags($_REQUEST['f_name']);
$textComp = $_REQUEST['textComp'];
$dolj = strip_tags($_REQUEST['dolj']);
$phone = strip_tags($_REQUEST['phone']);
$mail = strip_tags($_REQUEST['mail']);
$site = $_REQUEST['site'];
$other_country = $_REQUEST['other_country'];
$name_c1 = strip_tags($_REQUEST['name_c1']);
$f_name_c1 = strip_tags($_REQUEST['f_name_c1']);
$dolj_c1 = strip_tags($_REQUEST['dolj_c1']);
$mail_c1 = strip_tags($_REQUEST['mail_c1']);
$name_c2 = strip_tags($_REQUEST['name_c2']);
$f_name_c2 = strip_tags($_REQUEST['f_name_c2']);
$dolj_c2 = strip_tags($_REQUEST['dolj_c2']);
$mail_c2 = strip_tags($_REQUEST['mail_c2']);
$name_c3 = strip_tags($_REQUEST['name_c3']);
$f_name_c3 = strip_tags($_REQUEST['f_name_c3']);
$dolj_c3 = strip_tags($_REQUEST['dolj_c3']);
$mail_c3 = strip_tags($_REQUEST['mail_c3']);
$login = strip_tags($_REQUEST['loginMod']);
$pas = strip_tags($_REQUEST['pas']);
$areas = strip_tags($_REQUEST['areas']);
$eSob = strip_tags($_REQUEST['eSob']);
$morn = $_REQUEST['morn'];
$ever = $_REQUEST['ever'];
$formId = $_REQUEST['formId']*1;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
if(CModule::IncludeModule("iblock") && CModule::IncludeModule("form")){
	
	$userAdd = false;
	$form1 = false;
	$areas = str_replace('|', '', $areas);
	$areas = str_replace('form_checkbox_', '', $areas);
	$areas = explode('SIMPLE_QUESTION_', $areas);
	//c($areas);
	foreach($areas as $a){
		if($a != ''){
			$aAr = explode('_', $a);
			$areasAr['form_checkbox_SIMPLE_QUESTION_'.$aAr[0]][] = $aAr[1];
		}
	}
	
	//c($areasAr);
	
	$det = str_replace('|', '', $det);
	$det = str_replace('form_checkbox_', '', $det);
	$det = explode('SIMPLE_QUESTION_', $det);
	foreach($det as $a){
		if($a != ''){
			$dAr = explode('_', $a);
			$detAr['form_checkbox_SIMPLE_QUESTION_'.$dAr[0]][] = $dAr[1];
		}
	}
	
	//die('stop');
	//утро?
	if($morn > 0){
		$mornAr = array('form_checkbox_SIMPLE_QUESTION_836'=> array(843));
	}else{
		$mornAr = '';
	}
	//вечер?
	if($ever > 0){
		$everAr = array('form_checkbox_SIMPLE_QUESTION_156'=>array(844));
	}else{
		$everAr = '';
	}

	if($pas != ''){
		function strcode($str, $passw=""){
			$salt = "Dn8*#2n!9j";
			$len = strlen($str);
			$gamma = '';
			$n = $len>100 ? 8 : 2;
			while( strlen($gamma)<$len ){
				$gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
			}
			return $str^$gamma;
		}
		$pass = base64_encode(strcode($pas, 'luxoran'));
	}else{
		//Пароль рассчитан на 10! комбинаций / разных гостей
		$pasAr = array('d', 'p', '!', 'l', '9', '#', 'm', 'A', 'r', '2');
		shuffle($pasAr);
		$pas = implode("", $pasAr);
		function strcode($str, $passw=""){
			$salt = "Dn8*#2n!9j";
			$len = strlen($str);
			$gamma = '';
			$n = $len>100 ? 8 : 2;
			while( strlen($gamma)<$len ){
				$gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
			}
			return $str^$gamma;
		}
		$pass = base64_encode(strcode($pas, 'luxoran'));
	}

	$arValues = array (
		"form_text_204"                         => $name_c,
		"form_text_208"                         => $address,
		"form_text_209"                         => $index,
		"form_text_210"                         => $town,
		"form_dropdown_SIMPLE_QUESTION_678"     => $country,
		"form_text_216"                         => $name,
		"form_text_217"                         => $f_name,
		"form_text_218"                         => $dolj,
		"form_textarea_238"                     => $textComp, 
		"form_text_219"                         => '+'.$phone,
		"form_text_220"                         => $mail,
		"form_text_222"                         => $site,
		"form_text_223"                         => $name_c1,
		"form_text_224"                         => $f_name_c1,
		"form_text_225"                         => $dolj_c1,
		"form_text_226"                         => $mail_c1,
		"form_text_227"                         => $name_c2,
		"form_text_228"                         => $f_name_c2,
		"form_text_229"                         => $dolj_c2,
		"form_text_230"                         => $mail_c2,
		"form_text_231"                         => $name_c3,
		"form_text_232"                         => $f_name_c3,
		"form_text_233"                         => $dolj_c3,
		"form_text_234"                         => $mail_c3,
		"form_text_235"                         => $login,
		"form_password_236"                     => $pass,
		"form_text_510"                         => $other_country
	);

	//ФОРМИРУЕМ МАССИВ РЕЗУЛЬТАТА ФОРМЫ
	if(is_array($areasAr)){
		$arValuesMain = array_merge($arValues, $areasAr, $detAr);
		if(is_array($mornAr)){
			$arValuesMain = array_merge($arValues, $areasAr, $detAr, $mornAr);
		}
		if(is_array($everAr)){
			$arValuesMain = array_merge($arValues, $areasAr, $detAr, $everAr);
		}
		if(is_array($everAr) && is_array($mornAr)){
			$arValuesMain = array_merge($arValues, $areasAr, $detAr, $everAr, $mornAr);
		}
	}else{
		$arValuesMain = array_merge($arValues, $detAr);
		if(is_array($mornAr)){
			$arValuesMain = array_merge($arValues, $detAr, $mornAr);
		}
		if(is_array($everAr)){
			$arValuesMain = array_merge($arValues, $detAr, $everAr);
		}
		if(is_array($everAr) && is_array($mornAr)){
			$arValuesMain = array_merge($arValues, $detAr, $everAr, $mornAr);
		}
	}

	// создадим новый результат
	if ($RESULT_ID = CFormResult::Add(ID_FORM_GUEST, $arValuesMain)){
		$form1 = true;
		//echo $RESULT_ID;
	}
	

	//Создаём пользователя
	$user = new CUser;
	//в каких группах будет пользователь
	//3,4,5,6 - дефолтные битрикса группы. 19 - неподтверждённые пользователи - создана
	$gr = array(3, 4, 5, 6, 19);
	if($login != '' && $mornAr != ''){
		$loginA = $login;
	}else{
		//$loginA = $user = "user".rand(0,10000000);
		$pasAr = array('1', '2', '3', '4', '5', '6', '7', '8', '9');
		shuffle($pasAr);
		$pas = implode("", $pasAr);
		$loginA = $eSob.$pas.$mail;
	}
	$loginA = trim($loginA);
	//UF_IDx - id пользовательских свойств
	switch($formId){
		case ID_TYPE_1; $UF = 'UF_ID2'; break;
		case ID_TYPE_2; $UF = 'UF_ID5'; break;
		case ID_TYPE_3; $UF = 'UF_ID4'; break;
		case ID_TYPE_4; $UF = 'UF_ID3'; break;
		case ID_TYPE_5; $UF = 'UF_ID'; break;
		case ID_TYPE_6; $UF = 'UF_ID11'; break;
	}
	
	$arFields = Array(
		"EMAIL"             => $mail,
		"LOGIN"             => $loginA,
		"LID"               => "ru",
		"ACTIVE"            => "Y",
		"GROUP_ID"          => $gr,
		"PASSWORD"          => $pas,
		"CONFIRM_PASSWORD"  => $pas,
		"UF_ID_COMP"        => $RESULT_ID,
		"UF_PAS"            => $pass,
		$UF                 =>$RESULT_ID
	);


	$ID = $user->Add($arFields);
	if (intval($ID) > 0){
		$userAdd = true;
		//echo $ID;
	}else{
		//echo $user->LAST_ERROR;
		//die('stop');
	}
	/*
	if(is_array($everAr) && is_array($mornAr)){
		//письмо УТРО
		$arEventFields1 = array(
			"LOGIN"             => $loginA,
			"PASSWORD"          => $pas
			);
		CEvent::Send("REG_GUEST_MR", 's1', $arEventFields1);
		
		//письмо ВЕЧЕР
		$arEventFields2 = array(
			"LOGIN"             => $loginA,
			"PASSWORD"          => $pas
			);
		CEvent::Send("REG_GUEST_EV", 's1', $arEventFields2);
	}elseif(is_array($everAr)){
		//письмо ВЕЧЕР
		$arEventFields2 = array(
			"LOGIN"             => $loginA,
			"PASSWORD"          => $pas
			);
		CEvent::Send("REG_GUEST_EV", 's1', $arEventFields2);
	}else{
		//письмо УТРО
		$arEventFields1 = array(
			"LOGIN"             => $loginA,
			"PASSWORD"          => $pas
			);
		CEvent::Send("REG_GUEST_MR", 's1', $arEventFields1);
	}
	*/
	//На каждую выставку свой тип события
	switch($formId){
		case ID_TYPE_1; 
						if(is_array($everAr) && is_array($mornAr)){
							//письмо УТРО
							$arEventFields1 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_BAK_M", 's1', $arEventFields1);
							//письмо ВЕЧЕР
							$arEventFields2 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_BAK_E", 's1', $arEventFields2);
						}elseif(is_array($everAr)){
							//письмо ВЕЧЕР
							$arEventFields2 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_BAK_E", 's1', $arEventFields2);
						}else{
							//письмо УТРО
							$arEventFields1 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_BAK_M", 's1', $arEventFields1);
						}
		break;
		case ID_TYPE_2; 
						if(is_array($everAr) && is_array($mornAr)){
							//письмо УТРО
							$arEventFields1 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_MOSOT_M", 's1', $arEventFields1);
							//письмо ВЕЧЕР
							$arEventFields2 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_MOSOT_E", 's1', $arEventFields2);
						}elseif(is_array($everAr)){
							//письмо ВЕЧЕР
							$arEventFields2 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_MOSOT_E", 's1', $arEventFields2);
						}else{
							//письмо УТРО
							$arEventFields1 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_MOSOT_M", 's1', $arEventFields1);
						}
		break;
		case ID_TYPE_3; 
						if(is_array($everAr) && is_array($mornAr)){
							//письмо УТРО
							$arEventFields1 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_ALM_M", 's1', $arEventFields1);
							//письмо ВЕЧЕР
							$arEventFields2 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_ALM_E", 's1', $arEventFields2);
						}elseif(is_array($everAr)){
							//письмо ВЕЧЕР
							$arEventFields2 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_ALM_E", 's1', $arEventFields2);
						}else{
							//письмо УТРО
							$arEventFields1 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_ALM_M", 's1', $arEventFields1);
						}
		break;
		case ID_TYPE_4; 
						if(is_array($everAr) && is_array($mornAr)){
							//письмо УТРО
							$arEventFields1 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_KIEV_M", 's1', $arEventFields1);
							//письмо ВЕЧЕР
							$arEventFields2 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_KIEV_E", 's1', $arEventFields2);
						}elseif(is_array($everAr)){
							//письмо ВЕЧЕР
							$arEventFields2 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_KIEV_E", 's1', $arEventFields2);
						}else{
							//письмо УТРО
							$arEventFields1 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_KIEV_M", 's1', $arEventFields1);
						} 
		break;
		case ID_TYPE_5; 
						if(is_array($everAr) && is_array($mornAr)){
							//письмо УТРО
							$arEventFields1 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_MOSSP_M", 's1', $arEventFields1);
							//письмо ВЕЧЕР
							$arEventFields2 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_MOSSP_E", 's1', $arEventFields2);
						}elseif(is_array($everAr)){
							//письмо ВЕЧЕР
							$arEventFields2 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_MOSSP_E", 's1', $arEventFields2);
						}else{
							//письмо УТРО
							$arEventFields1 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_MOSSP_M", 's1', $arEventFields1);
						}
		break;
		case ID_TYPE_6; 
						if(is_array($everAr) && is_array($mornAr)){
							//письмо УТРО
							$arEventFields1 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_MOSSP15_M", 's1', $arEventFields1);
							//письмо ВЕЧЕР
							$arEventFields2 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_MOSSP15_E", 's1', $arEventFields2);
						}elseif(is_array($everAr)){
							//письмо ВЕЧЕР
							$arEventFields2 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_MOSSP15_E", 's1', $arEventFields2);
						}else{
							//письмо УТРО
							$arEventFields1 = array(
								"LOGIN"             => $loginA,
								"PASSWORD"          => $pas,
								"MAIL"              => $mail
								);
							CEvent::Send("REG_NEW_B_MOSSP15_M", 's1', $arEventFields1);
						}
		break;
	}
	echo 1;
}
?>