<? /* TODO Переписать класс Вишлистов, чтобы брались все сразу, а не по отдельности
  * Переписать определение данных об участниках из форм. Сделать 1 запрос, а не много маленьких
  * Убрать все лишнее
  */

set_time_limit(0);
ignore_user_abort(true);
session_write_close();

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 3600;
}

if (!CModule::IncludeModule("doka.meetings") || !CModule::IncludeModule("iblock") || !CModule::IncludeModule("form")) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}
if(!isset($arParams["EXHIB_IBLOCK_ID"]) || $arParams["EXHIB_IBLOCK_ID"] == ''){
	$arParams["EXHIB_IBLOCK_ID"] = 15;
}
$arResult = array();

if(isset($arParams["EXIB_CODE"]) && $arParams["EXIB_CODE"]!=''){
	$rsExhib = CIBlockElement::GetList(
			array(),
			array(
					"IBLOCK_ID" => $arParams["EXHIB_IBLOCK_ID"],
					"CODE" => $arParams["EXIB_CODE"]
				),
			false,
			false,
			array("ID", "CODE", "PROPERTY_APP_ID", "PROPERTY_APP_HB_ID", "PROPERTY_V_EN", "PROPERTY_V_RU")
			);
	if($oExhib = $rsExhib->Fetch())
	{
		$arResult["EXIB"] = $oExhib;
		if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
			$appId = $oExhib["PROPERTY_APP_HB_ID_VALUE"];
		}
		else{
			$appId = $oExhib["PROPERTY_APP_ID_VALUE"];
		}
		$arParams["APP_ID"] = $appId;
	}
}

if (empty($arParams["APP_ID"])) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}


if (empty($arParams["USER_ID"]))
	$arParams['USER_ID'] = $USER->GetID();

use Doka\Meetings\Requests as DR;
use Doka\Meetings\Wishlists as DWL;

$req_obj = new DR($arParams['APP_ID']);
$wishlist_obj = new DWL($arParams['APP_ID']);

if(empty($arParams["USER_TYPE"])){
	$arParams["USER_TYPE"] = "PARTICIP";
}

if(empty($arParams["EMAIL"])){
	$arParams["EMAIL"] = "info@luxurytravelmart.ru";
}

$arResult['USER_TYPE'] = $arParams['USER_TYPE'];
$arResult['APP_ID'] = $arParams['APP_ID'];

$exhibitionParam = array();
$exhibitionParam["IS_HB"] = $arParams["IS_HB"];
$exhibitionParam["TITLE"] = $arResult["EXIB"]["PROPERTY_V_EN_VALUE"];
$exhibitionParam["TITLE_RU"] = $arResult["EXIB"]["PROPERTY_V_RU_VALUE"];
if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
	$exhibitionParam["TITLE"] .= " Hosted Buyers session";
	$exhibitionParam["TITLE_RU"] .= " Hosted Buyers session";
}

$fioParticip = '';
$formId = CFormMatrix::getPFormIDByExh($arResult["EXIB"]["ID"]);
$propertyNameParticipant = CFormMatrix::getPropertyIDByExh($arResult["EXIB"]["ID"], 0);//свойство участника
$fio_dates = array();
$fio_dates[0][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_446', $formId);
$fio_dates[0][1] = CFormMatrix::getAnswerRelBase(84 ,$formId);//Имя участника
$fio_dates[1][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_551', $formId);
$fio_dates[1][1] = CFormMatrix::getAnswerRelBase(85 ,$formId);//Фамилия участника

// Определяем для какой группы генерировать вишлисты
if ($arResult['USER_TYPE'] != 'PARTICIP'){
	$group_search_id = $req_obj->getOption('GUESTS_GROUP');
	$group_opposite_id = $req_obj->getOption('MEMBERS_GROUP');}
else{
	$group_search_id = $req_obj->getOption('MEMBERS_GROUP');
	$group_opposite_id = $req_obj->getOption('GUESTS_GROUP');}

// Получаем список пользователей для которыз генерируем вишлисты
$selectPart = array( 'SELECT' => array($propertyNameParticipant),
	'FIELDS' => array('WORK_COMPANY', 'ID', "NAME", "LAST_NAME") );
$filter = array( "GROUPS_ID"  => array($group_search_id) );

//Гости могут быть HB
if($arResult['USER_TYPE'] == 'GUEST' && isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
	$filter = array( "GROUPS_ID"  => array($req_obj->getOption('GUESTS_GROUP')),
		"UF_HB" => "1" );
}
elseif($arResult['USER_TYPE'] == 'GUEST'){
	$filter = array( "GROUPS_ID"  => array($req_obj->getOption('GUESTS_GROUP')),
		"UF_MR" => "1" );
}
$rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="desc"), $filter, $selectPart);

$isHB = '';
if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y')
	$isHB = "_hb";
$path = '/upload/pdf/'.strtolower($arParams['USER_TYPE']).'/wish_'.strtolower($arParams["EXIB_CODE"]).$isHB.'/';
$shotPath = '/upload/pdf/'.strtolower($arParams['USER_TYPE']).'/';
CheckDirPath($_SERVER['DOCUMENT_ROOT'].$path);
$pdfFolder = $_SERVER['DOCUMENT_ROOT'].$path;

@unlink($_SERVER['DOCUMENT_ROOT'].$shotPath."wish_".$arParams["EXIB_CODE"].$isHB.'.zip');
require(DOKA_MEETINGS_MODULE_DIR . '/classes/pdf/tcpdf.php');
require_once(DOKA_MEETINGS_MODULE_DIR . '/classes/pdf/templates/wishlist_all_' . $arParams['USER_TYPE'] . '.php');

while ($arUser = $rsUsers->Fetch()) {
	$pdfName = str_replace(" ", "_", $arUser["WORK_COMPANY"])."_".$arUser["ID"].".pdf";
	$pdfName = str_replace("/", "", $pdfName);
	$pdfName = str_replace("*", "", $pdfName);
	$company = array(
		'id' => $arUser['ID'],
		'name' => $arUser['WORK_COMPANY'],
		'rep' => $arUser["NAME"]." ".$arUser["LAST_NAME"],
		'city' => '',
		'path' => $pdfFolder.$pdfName,
		'exhib' => $exhibitionParam,
		'wish_in' => array(),
		'wish_out' => array(),
	);
	// Получим все вишлисты
	if($arResult['USER_TYPE'] == 'GUEST'){
		$wishlists = $wishlist_obj->getWishlistsFull($arUser['ID'], $formId, $propertyNameParticipant, $fio_dates);
	}
	elseif($arResult['USER_TYPE'] == 'PARTICIP'){
		$wishlists = $wishlist_obj->getWishlists($arUser['ID']);
	}
	$company['wish_in'] = $wishlists['WISH_IN']; // с кем хочет встретиться
	$company['wish_out'] = $wishlists['WISH_OUT'];

	$arAnswer = CFormResult::GetDataByID($arUser[$propertyNameParticipant], array(), $arTmpResult, $arAnswer2);
	if($arResult['USER_TYPE'] == "PARTICIP"){
		$company['rep'] = $arAnswer2[$fio_dates[0][0]][$fio_dates[0][1]]["USER_TEXT"]." ".$arAnswer2[$fio_dates[1][0]][$fio_dates[1][1]]["USER_TEXT"];
	}
	else{
		foreach($arAnswer2["SIMPLE_QUESTION_672"] as $value){
			$company['city'] = $value["USER_TEXT"];
		}
	}
	$APPLICATION->RestartBuffer();
	DokaGeneratePdf($company);
}
/* Создание архива и удаление папки */
include_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/lib/pclzip.lib.php"); //Подключаем библиотеку.
$archive = new PclZip($_SERVER['DOCUMENT_ROOT'].$shotPath."wish_".$arParams["EXIB_CODE"].$isHB.'.zip'); //Создаём объект и в качестве аргумента, указываем название архива, с которым работаем.
$result = $archive->create($pdfFolder, PCLZIP_OPT_REMOVE_PATH, $_SERVER['DOCUMENT_ROOT'].$shotPath); // Этим методом класса мы создаём архив с заданным выше названием
if($result == 0) {
	echo $archive->errorInfo(true); //Возращает причину ошибки
}
else{
	$arEventFields = array(
		"EMAIL" => $arParams["EMAIL"],
		"EXIBITION" => $exhibitionParam["TITLE"],
		"TYPE" => "вишлист",
		"USER_TYPE" => strtolower($arParams["USER_TYPE"]),
		"LINK" => "http://".$_SERVER['SERVER_NAME'].$shotPath."wish_".strtolower($arParams["EXIB_CODE"]).$isHB.'.zip'
	);
	CEvent::SendImmediate("ARCHIVE_READY ", "s1", $arEventFields, $Duplicate = "Y");
	$text = "<p>Архив готов.</p>\n
			<p>Ссылка для скачивания: <a href='".$arEventFields["LINK"]."'>".$arEventFields["LINK"]."</a></p>";
	mail($arParams["EMAIL"], 'Готов архив с '.$arEventFields["TYPE"].' для '.$arEventFields["USER_TYPE"].' на выставку '.$arEventFields["EXIBITION"],$text);
}

fullRemove_ff($pdfFolder);

function fullRemove_ff($path,$t="1") {
	$rtrn="1";
	if (file_exists($path) && is_dir($path)) {
		$dirHandle = opendir($path);
		while (false !== ($file = readdir($dirHandle))) {
			if ($file!='.' && $file!='..') {
				$tmpPath=$path.'/'.$file;
				chmod($tmpPath, 0777);
				if (is_dir($tmpPath)) {
					fullRemove_ff($tmpPath);
				} else {
					if (file_exists($tmpPath)) {
						unlink($tmpPath);
					}
				}
			}
		}
		closedir($dirHandle);
		if ($t=="1") {
			if (file_exists($path)) {
				rmdir($path);
			}
		}
	} else {
		$rtrn="0";
	}
	return $rtrn;
}
?>
