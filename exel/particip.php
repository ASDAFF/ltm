<?header( 'Content-Type: text/html; charset=utf-8' );/* TODO
	в ИБ оставить только нужные свойства
	1 большой массив или несколько мелких?
*/
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");

if (!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form")) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

$arParams["TYPE"] = strip_tags($_REQUEST['type']);
$arParams["APP_CODE"] = strip_tags($_REQUEST['app']);

$arResult = array();

/* Получаем данные по ИБ Выставки*/
if(isset($arParams["APP_CODE"]) && $arParams["APP_CODE"]!=''){
	$rsExhib = CIBlockElement::GetList(
			array(),
			array(
					"IBLOCK_ID" => "15",
					"CODE" => $arParams["APP_CODE"]
				),
			false,
			false,
			array("ID", "CODE","IBLOCK_ID", "NAME","PROPERTY_*")//USER_GROUP_ID    UC_PARTICIPANTS_GROUP
			);
	while($oExhib = $rsExhib->GetNextElement(true, false))
	{
		$arResult["PARAM_EXHIBITION"] = $oExhib->GetFields();
		$arResult["PARAM_EXHIBITION"]["PROPERTIES"] = $oExhib->GetProperties();
		unset($arResult["PARAM_EXHIBITION"]["PROPERTIES"]["MORE_PHOTO"]);
		if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
			$appId = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["APP_HB_ID"]["VALUE"];
		}
		else{
			$appId = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["APP_ID"]["VALUE"];
		}
		$arParams["APP_ID"] = $appId;
	}
}

$fileName = $arResult["PARAM_EXHIBITION"]["NAME"]; // Название файла == Название выставки + указание кто это

$filter = array(); //Массив с полями для фильтрации списка пользователей
$isAll = false; //Коллеги отдельной строкой

$formCompId = 3; //ID формы О КОМПАНИИ для участников 
$formId = 0; //Id формы

//Поля с id результатов
$resultCode = CFormMatrix::getPropertyIDByExh($arResult["PARAM_EXHIBITION"]["ID"], 0);//свойство представителя
$resultCode2 = CFormMatrix::getPropertyIDByExh($arResult["PARAM_EXHIBITION"]["ID"], 1);//свойство представителя 2
$resultAllCode = "UF_ID_COMP";//Поле с ID формы О КОМПАНИИ

$resUser = array(); //Массив результатов Представитель 1
$resUser2 = array(); //Массив результатов Представитель 2
$resAllComp = array(); //Массив результатов О компании

if($arParams["TYPE"] == 'particip'){
	$filter["GROUPS_ID"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["USER_GROUP_ID"]["VALUE"];
	$formId = CFormMatrix::getPFormIDByExh($arResult["PARAM_EXHIBITION"]["ID"]);
	$fileName = "Участники ".$fileName." подтвержденные.xls";
}
elseif($arParams["TYPE"] == 'particip_all'){
	$filter["GROUPS_ID"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["USER_GROUP_ID"]["VALUE"];
	$formId = CFormMatrix::getPFormIDByExh($arResult["PARAM_EXHIBITION"]["ID"]);
	$isAll = true;
	$fileName = "Участники ".$fileName." подтвержденные (коллеги отдельно).xls";
}
elseif($arParams["TYPE"] == 'particip_no'){
	$filter["GROUPS_ID"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["UC_PARTICIPANTS_GROUP"]["VALUE"];
	$formId = 4;
	$resultCode = "UF_ID";//свойство представителя
	$resultCode2 = "UF_ID6";
	$fileName = "Участники ".$fileName." неподтвержденные.xls";
}
elseif($arParams["TYPE"] == 'particip_no_all'){
	$filter["GROUPS_ID"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["UC_PARTICIPANTS_GROUP"]["VALUE"];
	$formId = 4;
	$resultCode = "UF_ID";//свойство представителя
	$resultCode2 = "UF_ID6";
	$isAll = true;
	$fileName = "Участники ".$fileName." неподтвержденные (коллеги отдельно).xls";
}
elseif($arParams["TYPE"] == 'particip_spam'){
	$filter["GROUPS_ID"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["PARTICIPANT_SPAM_GROUP"]["VALUE"];
	$formId = 4;
	$resultCode = "UF_ID";//свойство представителя
	$resultCode2 = "UF_ID6";
	$fileName = "Участники ".$fileName." спам.xls";
}
else{
	echo 'Oops, we are not found this type.';
} 

$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"), "FIELDS"=>array("ID","LOGIN")));
$i = 0;
$arTmpUsers = array();
$rsRequisite = CUserFieldEnum::GetList(array(), array("USER_FIELD_ID" => 39)); //39 код свойства UF_REQUISITE
while($arRequisite = $rsRequisite->GetNext(true,false)){
	$arResult["PAY_REQUISITE"][$arRequisite["ID"]] = $arRequisite["VALUE"];
}

while ($arUser = $rsUsers->Fetch()){
	$arTmpUsers[$i]['ID']       = $arUser['ID'];
	$arTmpUsers[$i]['LOGIN']    = $arUser['LOGIN'];
	$arTmpUsers[$i]['PASSWORD'] = makePassExcelDeCode($arUser["UF_PAS"]);;
	$arTmpUsers[$i]['FORM_COMP'] = $arUser[$resultAllCode];
	$arTmpUsers[$i]['FORM_REP'] = '';
	$arTmpUsers[$i]['FORM_REP2'] = '';

	//Данные по всей компании
	$resAllComp[] = $arUser[$resultAllCode];

	//Представитель 1 для участников
	if($arUser[$resultCode] != ''){
		$resUser[] = $arUser[$resultCode];
		$arTmpUsers[$i]['FORM_REP'] = $arUser[$resultCode];
	}

	//Представитель 2 для участников
	if($arUser[$resultCode2] != ''){
		$resUser[] = $arUser[$resultCode2];
		$arTmpUsers[$i]['FORM_REP2'] = $arUser[$resultCode2];
	}
	$i++;
}


//получение результатов заполнения формы компании
//Получение ответов формы Участники данные компании ВСЕ ВЫСТАВКИ
$arTmpResult["FORM_RESULT_COMMON"] = array("QUESTIONS"=>array(), "ANSWERS"=>array(), "ANSWERS2"=>array());

CForm::GetResultAnswerArray(
    $formCompId,
    $arTmpResult["FORM_RESULT_COMMON"]["QUESTIONS"],
    $arTmpResult["FORM_RESULT_COMMON"]["ANSWERS"],
    $arTmpResult["FORM_RESULT_COMMON"]["ANSWERS2"],
    array("RESULT_ID" => implode("|", $resAllComp))
);

//получение ответов формы Представители (1 и 2)
$arTmpResult["FORM_RESULT_USERS"] = array("QUESTIONS"=>array(), "ANSWERS"=>array(), "ANSWERS2"=>array());

CForm::GetResultAnswerArray(
    $formId,
    $arTmpResult["FORM_RESULT_USERS"]["QUESTIONS"],
    $arTmpResult["FORM_RESULT_USERS"]["ANSWERS"],
    $arTmpResult["FORM_RESULT_USERS"]["ANSWERS2"],
    array("RESULT_ID" => implode("|", $resUser))
);


$arResult["ANSWERS"] = array();
$j=0;
$arCompField = CFormMatrix::$arExelCompParticipantField;//Получаем данные по полям О компании для Exel из справочника
$arRepField = CFormMatrix::$arExelRepParticipantField;//Получаем данные по полям О представителе для Exel из справочника
/* Переделка кодов полей для текущей формы */
foreach ($arRepField["QUEST_CODE"] as $fieldName => $fieldValue) {
	$arRepField["QUEST_CODE"][$fieldName] = CFormMatrix::getSIDRelBase($fieldValue, $formId);
}


foreach ($arTmpUsers as $arUser) {
	/*Данные из рользователя*/
	$arResult["ANSWERS"][$j]["ID"] = $arUser['ID'];
	$arResult["ANSWERS"][$j]["LOGIN"] = $arUser['LOGIN'];
	$arResult["ANSWERS"][$j]["PASSWORD"] = $arUser['PASSWORD'];
	/* Данные обо всей компании */
	foreach ($arCompField["QUEST_CODE"] as $idQuest => $codeRes){
		if($arCompField["NAMES_AR"][$idQuest] != "DESTINITIONS"){
			$arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]] = $arTmpResult["FORM_RESULT_COMMON"]["ANSWERS2"][$arUser['FORM_COMP']][$codeRes]["0"][$arCompField["ANS_TYPE"][$idQuest]];
		}
		else{
			if(!isset($arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]])){
				$arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]] = array();
			}
			foreach ($arTmpResult["FORM_RESULT_COMMON"]["ANSWERS2"][$arUser['FORM_COMP']][$codeRes] as $dest) {
				$arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]][] = $dest[$arCompField["ANS_TYPE"][$idQuest]];
			}
		}
	}
	/* Данные о представителях */
	if(!$isAll){
		foreach ($arRepField["QUEST_CODE"] as $idQuest => $codeRes){
			//Вот это не очень хорошо, но либо SQL либо еще что-то придумать
			if($arRepField["NAMES_AR"][$idQuest] == "HALL" && $arTmpResult["FORM_RESULT_USERS"]["ANSWERS2"][$arUser['FORM_REP']][$codeRes]["0"][$arRepField["ANS_TYPE"][$idQuest]] == 'Mr.'){
				$arResult["ANSWERS"][$j][$arRepField["NAMES_AR"][$idQuest]] = '';
			}
			elseif(strpos($arRepField["NAMES"][$idQuest], 'College') === false){
				$arResult["ANSWERS"][$j][$arRepField["NAMES_AR"][$idQuest]] = $arTmpResult["FORM_RESULT_USERS"]["ANSWERS2"][$arUser['FORM_REP']][$codeRes]["0"][$arRepField["ANS_TYPE"][$idQuest]];
			}
			else{
				$arResult["ANSWERS"][$j][$arRepField["NAMES_AR"][$idQuest]] = $arTmpResult["FORM_RESULT_USERS"]["ANSWERS2"][$arUser['FORM_REP2']][$codeRes]["0"][$arRepField["ANS_TYPE"][$idQuest]];
			}		
		}
	}
	else{//Если все представители, то + коллега отдельной строкой
		$isCollege = false;
		foreach ($arRepField["QUEST_CODE"] as $idQuest => $codeRes){
			if(strpos($arRepField["NAMES"][$idQuest], 'College') === false){
				$arResult["ANSWERS"][$j][$arRepField["NAMES_AR"][$idQuest]] = $arTmpResult["FORM_RESULT_USERS"]["ANSWERS2"][$arUser['FORM_REP']][$codeRes]["0"][$arRepField["ANS_TYPE"][$idQuest]];
			}
			else{
				if($arTmpResult["FORM_RESULT_USERS"]["ANSWERS2"][$arUser['FORM_REP2']][$codeRes]["0"][$arRepField["ANS_TYPE"][$idQuest]] != ''){
					$isCollege = $isCollege || true;
				}
			}
		}
		if($isCollege){
			$arResult["ANSWERS"][$j+1]=$arResult["ANSWERS"][$j];//скопировать всю общую информацию
			$j++;
			foreach ($arRepField["QUEST_CODE"] as $idQuest => $codeRes){
				if(strpos($arRepField["NAMES"][$idQuest], 'College') !== false){//заменяем то, что отличается
					$arResult["ANSWERS"][$j][str_replace ("_COL", "", $arRepField["NAMES_AR"][$idQuest])] = $arTmpResult["FORM_RESULT_USERS"]["ANSWERS2"][$arUser['FORM_REP2']][$codeRes]["0"][$arRepField["ANS_TYPE"][$idQuest]];
				}		
			}
		}
	}
	$j++;
}
/*echo "<pre>";print_r($arTmpResult["FORM_RESULT_COMMON"]["ANSWERS2"]["3749"]);echo "</pre>";
die();*/


$arResult["TITLES"] = array("uId", "Login", "Password");

$isDestinationsWrite = false;
foreach($arCompField["QUEST_CODE"] as $idQuest => $codeRes){
	if($isDestinationsWrite){
		break;
	}
	if($arCompField["NAMES_AR"][$idQuest] == 'DESTINITIONS'){
		$isDestinationsWrite = true;
	}
 	$arResult["TITLES"][] = $arCompField["NAMES"][$idQuest];
}
foreach($arRepField["QUEST_CODE"] as $idQuest => $codeRes){
	if(!$isAll || strpos($arRepField["NAMES"][$idQuest], 'College') === false){//Если у нас все представители, то коллега отдельной строкой
		$arResult["TITLES"][] = $arRepField["NAMES"][$idQuest];
	}	
}

ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

require_once 'PHPExcel.php';

// Настройки
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
PHPExcel_Settings::setLocale('ru_ru');

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("LTM Site")->setLastModifiedBy("LTM Site")->setTitle("Office 2007 XLSX Test Document")->setSubject("Office 2007 XLSX Test Document") ->setDescription("Document generated list of exhibitors.")->setKeywords("office 2007 openxml php");

$objPHPExcel->setActiveSheetIndex(0);
$aSheet = $objPHPExcel->getActiveSheet();

$baseFont = array(
	'font'=>array(
		'name'=>'Arial',
		'size'=>'12',
		'bold'=>false
	),
    'alignment' => array (
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
    )
);

/*Устанавливаем ширину колонок (разная инфа, лучше индивидуально)*/
$aSheet->getColumnDimension('A')->setWidth(7);	
$aSheet->getColumnDimension('B')->setWidth(20);	
$aSheet->getColumnDimension('C')->setWidth(13);	
$aSheet->getColumnDimension('D')->setWidth(50);	
$aSheet->getColumnDimension('E')->setWidth(30);	
$aSheet->getColumnDimension('F')->setWidth(40);
$aSheet->getColumnDimension('G')->setWidth(25);	
$aSheet->getColumnDimension('H')->setWidth(25);	
$aSheet->getColumnDimension('I')->setWidth(30);
$aSheet->getColumnDimension('J')->setWidth(75);
$aSheet->getColumnDimension('K')->setWidth(50);
$aSheet->getColumnDimension('L')->setWidth(15);
$aSheet->getColumnDimension('M')->setWidth(25);
$aSheet->getColumnDimension('N')->setWidth(25);
$aSheet->getColumnDimension('O')->setWidth(35);
$aSheet->getColumnDimension('P')->setWidth(25);
$aSheet->getColumnDimension('Q')->setWidth(35);
$aSheet->getColumnDimension('R')->setWidth(35);
$aSheet->getColumnDimension('S')->setWidth(15);
if(!$isAll || strpos($arRepField["NAMES"][$idQuest], 'College') === false){
	$aSheet->getColumnDimension('T')->setWidth(25);
	$aSheet->getColumnDimension('U')->setWidth(25);
	$aSheet->getColumnDimension('V')->setWidth(35);
	$aSheet->getColumnDimension('W')->setWidth(35);
	$aSheet->getColumnDimension('X')->setWidth(15);
	$aSheet->getColumnDimension('Y')->setWidth(15);
	}
else{
	$aSheet->getColumnDimension('T')->setWidth(15);
	$aSheet->getColumnDimension('U')->setWidth(15);
}

/* Шапка таблицы */
$row_count = 1;
$col_count = 0;
foreach($arResult["TITLES"] as $idQuest => $codeRes){
 	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, $codeRes);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col_count, $row_count)->applyFromArray($baseFont);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col_count, $row_count)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $col_count++;
}
$row_count++;

/* Вывод данных */
foreach ($arResult["ANSWERS"] as $ans) {
	$col_count = 0;	
	foreach($ans as $userAns){
		if(is_array($userAns) && !empty($userAns)){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, implode(', ', $userAns));
		}
		elseif($userAns == '' || empty($userAns)){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, ' ');
		}
		else{
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, $userAns);
		}
	 	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col_count, $row_count)->applyFromArray($baseFont);
	    $col_count++;
	}
	$row_count++;
}

$objPHPExcel->getActiveSheet()->setTitle('Participants_excel');
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment;filename="'.$fileName.'"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
//echo "<pre>"; print_r($arResult["ANSWERS"]); echo "</pre>";
?>