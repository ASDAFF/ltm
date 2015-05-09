<?
$of_name = strip_tags($_REQUEST['of_name']);
$c_name = strip_tags($_REQUEST['c_name']);
$login = strip_tags($_REQUEST['loginMod']);
$area_b = strip_tags($_REQUEST['area_b']);
$adress = strip_tags($_REQUEST['adress']);
$city = strip_tags($_REQUEST['city']);
$country = strip_tags($_REQUEST['country']);
$site = strip_tags($_REQUEST['site']);
$textComp = $_REQUEST['textComp'];
$areas = strip_tags($_REQUEST['areas']);
$first_name = strip_tags($_REQUEST['first_name']);
$last_name = strip_tags($_REQUEST['last_name']);
$salut = strip_tags($_REQUEST['salut']);
$job = strip_tags($_REQUEST['job']);
$phone_n = strip_tags($_REQUEST['phone_n']);
$mail = strip_tags($_REQUEST['mail']);
$con_mail = strip_tags($_REQUEST['con_mail']);
$alt_mail = strip_tags($_REQUEST['alt_mail']);
$group_user = strip_tags($_REQUEST['group_user']);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
if(CModule::IncludeModule("iblock") && CModule::IncludeModule("form")){
	
	//echo $textComp;
	//die('stop');
	
	$userAdd = false;
	$form1 = false;
	$form2 = false;
	
	//логотип 
	if ($handle = opendir(PATH_LOGO)) {
		while (false !== ($file = readdir($handle))){ 
			if($file != '.' && $file != '..'){
				$logotip = $file;
				$logotip_d = $file;
			}
		}
		closedir($handle); 
	}
	$logotip = CFile::MakeFileArray(PATH_LOGO.$logotip);
	
	//будем обрезать логотип
	$vMod = CFile::SaveFile($logotip, "upload");
	$file = CFile::ResizeImageGet($vMod, array('width'=>100, 'height'=> 99999), BX_RESIZE_IMAGE_PROPORTIONAL, true);    
	$logotipMod = CFile::MakeFileArray($file['src']);
	
	//фотографии (6-12) 
	if ($handle = opendir(PATH_PHOTOS)) {
		while (false !== ($file = readdir($handle))){ 
			if($file != '.' && $file != '..'){
				$photos[] = $file;
				$photos_d[] = $file;
			}
		}
		closedir($handle); 
	}
	$bs = new CIBlockSection;
	$arFields = Array(
		"ACTIVE" => 'Y',
		"IBLOCK_ID" => IBLOCK_PHOTO,
		"NAME" => $c_name,
		"SORT" => $SORT
	);
	$ID_SECTION = $bs->Add($arFields);
	
	$tt = 0;
	foreach($photos as $v){
		$tt++;
		$el = new CIBlockElement;
		$arLoad = Array(
			"IBLOCK_SECTION_ID" => $ID_SECTION,
			"IBLOCK_ID"      => IBLOCK_PHOTO,
			"NAME"           => "Фото ".$tt,
			"ACTIVE"         => "Y",
			"PREVIEW_PICTURE" => CFile::MakeFileArray(PATH_PHOTOS.$v)
		);
		$el->Add($arLoad);
	}

	$areas = str_replace('|', '', $areas);
	$areas = str_replace('form_checkbox_', '', $areas);
	$areas = explode('SIMPLE_QUESTION_', $areas);
	foreach($areas as $a){
		if($a != ''){
			$aAr = explode('_', $a);
			$areasAr['form_checkbox_SIMPLE_QUESTION_'.$aAr[0]][] = $aAr[1];
		}
	}
	
	$arValues = array (
		"form_text_29"                       => $of_name,
		"form_text_30"                       => $c_name,
		"form_text_31"                       => $login,
		"form_image_193"                     => $logotipMod,
		"form_dropdown_SIMPLE_QUESTION_284"  => $area_b,
		"form_text_35"                       => $adress,
		"form_text_36"                       => $city,
		"form_text_37"                       => $country,
		"form_text_38"                       => $site,
		"form_textarea_39"                   => $textComp
	);
	$arValuesMain = array_merge($arValues, $areasAr);
	// создадим новый результат
	if ($RESULT_ID = CFormResult::Add(ID_FROM_COMP, $arValuesMain)){
		$form1 = true;
	}
	foreach($photos_d as $v){
		unlink(PATH_PHOTOS.$v);
	}
	unlink(PATH_LOGO.$logotip_d);
	
	//форма Участники Представители Москва Весна (по умолчанию)
	//персональное фото 
	if ($handle = opendir(PATH_PERS_PHOTO)) {
		while (false !== ($file = readdir($handle))){ 
			if($file != '.' && $file != '..'){
				$pers = $file;
				$pers_d = $file;
			}
		}
		closedir($handle); 
	}
	$pers = CFile::MakeFileArray(PATH_PERS_PHOTO.$pers);
	$arValues2 = array (
		"form_text_84"                       => $first_name,
		"form_text_85"                       => $last_name,
		"form_dropdown_SIMPLE_QUESTION_889"  => $salut,
		"form_text_87"                       => $job,
		"form_text_88"                       => '+'.$phone_n,
		"form_text_89"                       => $mail,
		"form_text_90"                       => $con_mail,
		"form_text_91"                       => $alt_mail,
		"form_image_195"                     => $pers
	);
	// создадим новый результат
	if ($RESULT_ID2 = CFormResult::Add(ID_FROM_COMP_DEFAULT, $arValues2)){
		$form2 = true;
	}
	unlink(PATH_PERS_PHOTO.$pers_d);
	
	//Создаём пользователя
	$user = new CUser;
	$group_user = explode('|', $group_user);
	$group_user = array_diff($group_user, array(''));
	foreach($group_user as $g){
		if($g == ID_TYPE_6){
			$gr[] = GROUP_6;
		}
		if($g == ID_TYPE_5){
			$gr[] = GROUP_5;
		}
		if($g == ID_TYPE_4){
			$gr[] = GROUP_4;
		}
		if($g == ID_TYPE_3){
			$gr[] = GROUP_3;
		}
		if($g == ID_TYPE_2){
			$gr[] = GROUP_2;
		}
		if($g == ID_TYPE_1){
			$gr[] = GROUP_1;
		}
		if($g == ID_ALM15){
			$gr[] = GROUP_ALM15;
		}
		if($g == ID_MO15){
			$gr[] = GROUP_MO15;
		}
		if($g == ID_KIEV15){
			$gr[] = GROUP_KIEV15;
		}
	}
	//Прибавляем так же нового пользователя к группам с перечисленными id
	$gr[] = 3;
	$gr[] = 4;
	$gr[] = 5;
	$gr[] = 6;
	
	function strcode($str, $passw=""){
		$salt = "Dn8*#2n!9j";
		$len = strlen($str);
		$gamma = '';
		$n = $len>100 ? 16 : 4;
		while( strlen($gamma)<$len ){
			$gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
		}
		return $str^$gamma;
	}
	/*
	$txt = "Hello XOR encode!";
	$txt = base64_encode(strcode($txt, 'mypassword'));
	echo $txt;
	// result - ZOHdWKf+cf7vAwpJNfSJ8s8= 

	$txt = "ZOHdWKf+cf7vAwpJNfSJ8s8=";
	$txt = strcode(base64_decode($txt), 'mypassword');
	echo $txt;
	// result - Hello XOR encode! 
	*/
	//Пароль рассчитан на 10! комбинаций / разных участников
	$pasAr = array('S', '*', 't', 'f', '[', '$', '3', 'A', 'b', '7');
	shuffle($pasAr);
	$pasStr = implode('', $pasAr);
	$pas = base64_encode(strcode($pasStr, 'luxoran'));
	
	$login = trim($login);
	
	$arFields = Array(
	  "EMAIL"                 => $mail,
	  "LOGIN"                 => $login,
	  "LID"                   => "ru",
	  "ACTIVE"                => "Y",
	  "GROUP_ID"              => $gr,
	  "PASSWORD"              => $pasStr,
	  "CONFIRM_PASSWORD"      => $pasStr,
	  "UF_ID"                 => $RESULT_ID2,
	  "UF_ID_GROUP"           => $ID_SECTION,
	  "UF_PAS"                => $pas,
	  "UF_ID_COMP"            => $RESULT_ID,
	  "WORK_COMPANY"          => $c_name
	);

	$ID = $user->Add($arFields);
	if (intval($ID) > 0){
		$userAdd = true;
	}
	/*
	//письмо админу
	$arEventFields1 = array(
		"NAME"             => $first_name,
		"MAIL"             => $mail,
		"PHONE"            => $phone_n
		);
	CEvent::Send("NEW_EXH", 's1', $arEventFields1);
	
	//письмо зарегистрированному участнику
	$arEventFields2 = array(
		"NAME"             => $first_name,
		"MAIL"             => $mail,
		"LOGIN"            => $login,
		"PASSWORD"         => $pasStr
		);
	CEvent::Send("NEW_EXH_FOR_EXH", 's1', $arEventFields2);
	*/
	//На каждую выставку свой шаблон
	
	foreach($group_user as $g){
		if($g == ID_TYPE_6){
			$arEventFields1 = array(
				"LOGIN"            => $login,
				"MAIL"             => $mail,
				"COMP_NAME"        => $c_name,
				"PASSWORD"         => $pasStr
				);
			CEvent::Send("REG_NEW_E_MOSSP15", 's1', $arEventFields1);
		}
		if($g == ID_TYPE_5){
			$arEventFields1 = array(
				"LOGIN"            => $login,
				"MAIL"             => $mail,
				"COMP_NAME"        => $c_name,
				"PASSWORD"         => $pasStr
				);
			CEvent::Send("REG_NEW_E_MOSSP", 's1', $arEventFields1);
		}
		if($g == ID_TYPE_4){
			$arEventFields1 = array(
				"LOGIN"            => $login,
				"MAIL"             => $mail,
				"COMP_NAME"        => $c_name,
				"PASSWORD"         => $pasStr
				);
			CEvent::Send("REG_NEW_E_KIEV", 's1', $arEventFields1);
		}
		if($g == ID_TYPE_3){
			$arEventFields1 = array(
				"LOGIN"            => $login,
				"MAIL"             => $mail,
				"COMP_NAME"        => $c_name,
				"PASSWORD"         => $pasStr
				);
			CEvent::Send("REG_NEW_E_ALM", 's1', $arEventFields1);
		}
		if($g == ID_TYPE_2){
			$arEventFields1 = array(
				"LOGIN"            => $login,
				"MAIL"             => $mail,
				"COMP_NAME"        => $c_name,
				"PASSWORD"         => $pasStr
				);
			CEvent::Send("REG_NEW_E_MOSOT", 's1', $arEventFields1);
		}
		if($g == ID_TYPE_1){
			$arEventFields1 = array(
				"LOGIN"            => $login,
				"MAIL"             => $mail,
				"COMP_NAME"        => $c_name,
				"PASSWORD"         => $pasStr
				);
			CEvent::Send("REG_NEW_E_BAK", 's1', $arEventFields1);
		}
		if($g == ID_KIEV15){
			$arEventFields1 = array(
				"LOGIN"            => $login,
				"MAIL"             => $mail,
				"COMP_NAME"        => $c_name,
				"PASSWORD"         => $pasStr
				);
			CEvent::Send("REG_NEW_E_KIEV15", 's1', $arEventFields1);
		}
		if($g == ID_ALM15){
			$arEventFields1 = array(
				"LOGIN"            => $login,
				"MAIL"             => $mail,
				"COMP_NAME"        => $c_name,
				"PASSWORD"         => $pasStr
				);
			CEvent::Send("REG_NEW_E_ALM15", 's1', $arEventFields1);
		}
		if($g == ID_MO15){
			$arEventFields1 = array(
				"LOGIN"            => $login,
				"MAIL"             => $mail,
				"COMP_NAME"        => $c_name,
				"PASSWORD"         => $pasStr
				);
			CEvent::Send("REG_NEW_E_MOSOT15", 's1', $arEventFields1);
		}
	}
	
	if($form1 && $form2 && $userAdd){
		echo 1;
	}
	
}
?>