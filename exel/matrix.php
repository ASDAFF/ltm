<?header( 'Content-Type: text/html; charset=utf-8' );
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");

if (!CModule::IncludeModule("doka.meetings") || !CModule::IncludeModule("iblock") || !CModule::IncludeModule("form")) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

$arParams["USER_TYPE"] = strtoupper(strip_tags($_REQUEST['type']));
$arParams["EXIB_CODE"] = strip_tags($_REQUEST['app']);
$arParams["IS_HB"] = strtoupper(strip_tags($_REQUEST['hb']));

$arResult = array();
if(isset($arParams["EXIB_CODE"]) && $arParams["EXIB_CODE"]!=''){
	$rsExhib = CIBlockElement::GetList(
		array(),
		array(
			"IBLOCK_ID" => "15",
			"CODE" => $arParams["EXIB_CODE"]
		),
		false,
		false,
		array("ID", "CODE", "NAME", "IBLOCK_ID", "PROPERTY_APP_ID", "PROPERTY_APP_HB_ID", "PROPERTY_V_EN", "PROPERTY_V_RU")
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

if (empty($arParams["USER_TYPE"])) {
	ShowError(GetMessage("ERROR_EMPTY_USER_TYPE"));
	return;
}

$fileName = $arResult["EXIB"]["NAME"]; // Название файла == Название выставки + указание кто это
if($arParams["USER_TYPE"] == 'PARTICIP'){
	$fileName = "Участники ".$fileName;
}
elseif($arParams["USER_TYPE"] == 'GUEST'){
	$fileName = "Гости ".$fileName;
}
else{
	echo 'Oops, we are not found this type.';
	die();
}
if($arParams["IS_HB"] == 'Y'){
	$fileName = $fileName." HB.xls";
}
else{
	$fileName = $fileName.".xls";
}

$fioParticip = "";
$formId = CFormMatrix::getPFormIDByExh($arResult["EXIB"]["ID"]);
$propertyNameParticipant = CFormMatrix::getPropertyIDByExh($arResult["EXIB"]["ID"], 0);//свойство участника
$fio_dates = array();
$fio_dates[0][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_446', $formId);
$fio_dates[0][1] = CFormMatrix::getAnswerRelBase(84 ,$formId);//Имя участника
$fio_dates[1][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_551', $formId);
$fio_dates[1][1] = CFormMatrix::getAnswerRelBase(85 ,$formId);//Фамилия участника
$fio_dates[2][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_148', $formId);
$fio_dates[2][1] = CFormMatrix::getAnswerRelBase(1319 ,$formId);//Стол участника
$fio_dates[3][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_732', $formId);
$fio_dates[3][1] = CFormMatrix::getAnswerRelBase('SIMPLE_QUESTION_732' ,$formId);//Зал участника

use Doka\Meetings\Requests as DokaRequest;
use Doka\Meetings\Timeslots as DokaTimeslot;

$req_obj = new DokaRequest($arParams['APP_ID']);

$timeslots = $req_obj->getTimeslots();
$meet_timeslots = $req_obj->getMeetTimeslotsIds();
$statuses_free = $req_obj->getStatusesFree();

// Определяем для какой группы выводить матрицу
if ($arParams['USER_TYPE'] != 'PARTICIP'){
	$group_search_id = $req_obj->getOption('GUESTS_GROUP');
	$group_opposite_id = $req_obj->getOption('MEMBERS_GROUP');}
else{
	$group_search_id = $req_obj->getOption('MEMBERS_GROUP');
	$group_opposite_id = $req_obj->getOption('GUESTS_GROUP');
}

// Список таймслотов со списком компаний
$arResult['TIME'] = array();
foreach ($meet_timeslots as $timeslot_id) {
	$arResult['TIME'][$timeslot_id] = array(
		'id' => $timeslot_id,
		'name' => $timeslots[$timeslot_id]['name'],
	);
}

// Полный список компаний из групп участников и гостей
$selectPart = array(
	'SELECT' => array($propertyNameParticipant),
	'FIELDS' => array('WORK_COMPANY', 'ID')
);

$users_list = array();
$filter = array(
	"GROUPS_ID"  => array($req_obj->getOption('MEMBERS_GROUP'))
);

$rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="desc"), $filter, $selectPart);
while ($arUser = $rsUsers->Fetch()) {
	$arAnswer = CFormResult::GetDataByID(
		$arUser[$propertyNameParticipant],
		array(),  // вопрос "Какие области знаний вас интересуют?"
		$arResultTmp,
		$arAnswer2);
	$users_list[$arUser['ID']] =  array(
		'id' => $arUser['ID'],
		'name' => $arUser['WORK_COMPANY'],
		'repr_name' => trim($arAnswer2[$fio_dates[0][0]][$fio_dates[0][1]]["USER_TEXT"])." ".trim($arAnswer2[$fio_dates[1][0]][$fio_dates[1][1]]["USER_TEXT"]),
	);
}

if(isset($arParams["IS_HB"]) && $arParams["IS_HB"] == 'Y'){
	$filter = array(
		"GROUPS_ID"  => array($req_obj->getOption('GUESTS_GROUP')),
		"UF_HB" => "1"
	);
}
else{
	$filter = array(
		"GROUPS_ID"  => array($req_obj->getOption('GUESTS_GROUP')),
		"UF_MR" => "1"
	);
}

$selectGuest = array('FIELDS' => array('WORK_COMPANY', 'ID', 'NAME', 'LAST_NAME'));
$rsGUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="desc"), $filter, $selectGuest);
while ($arUser = $rsGUsers->Fetch()) {
	$users_list[$arUser['ID']] =  array(
		'id' => $arUser['ID'],
		'name' => $arUser['WORK_COMPANY'],
		'repr_name' => $arUser["NAME"]." ".$arUser["LAST_NAME"],
	);
}

// Список компаний, для которых выведем занятость
$rsCompanies = $req_obj->getAllMeetTimesByGroup($group_search_id);
while ($data = $rsCompanies->Fetch()) {
	$company = array(
		'id' => $data['ID'],
		'name' => $data['WORK_COMPANY'],
		'rep' => $users_list[$data['ID']]['repr_name'],
		'schedule' => array()
	);

	$statuses = $req_obj->getTimslotsStatuses($data);
	//echo "<pre>"; var_dump($data); echo "</pre>";
	// Если пользователя нет в таблице занятости, значит у него все слоты свободны
	if ($data['USER_ID'] === null) {
		foreach ($meet_timeslots as $timeslot_id) {
			$company['schedule'][$timeslot_id][] = array(
				'timeslot_id' => $timeslot_id,
				'timeslot_name' => $timeslots[$timeslot_id]['name'],
				'status' => DokaRequest::getStatusCode($statuses[$timeslot_id]),
			);
		}
	} else {
		foreach ($statuses as $timeslot_id => $status_id) {
			if ( in_array($timeslot_id, $meet_timeslots)) {
				$schedule = array(
					'timeslot_id' => $timeslot_id,
					'timeslot_name' => $timeslots[$timeslot_id]['name'],
					'status' => DokaRequest::getStatusCode($statuses[$timeslot_id]),
					'is_busy' => false
				);
				// если слот занят
				if ( !in_array($statuses[$timeslot_id], $statuses_free) ) {
					$user_is_sender = $data['MEET_'.$timeslot_id] % 10;
					$user_id = substr($data['MEET_'.$timeslot_id], 0, -1);
					$schedule['company_id'] = $users_list[$user_id]['id'];
					$schedule['company_name'] = $users_list[$user_id]['name'];
					$schedule['rep'] = $users_list[$user_id]['repr_name'];
					$schedule['user_is_sender'] = $user_is_sender;
					$schedule['is_busy'] = true;
				}
				$company['schedule'][$timeslot_id] = $schedule;
			}
		}
	}

	$arResult['USERS'][] = $company;
}
unset($meet_timeslots);
/*
echo "<pre>";
print_r($arResult);
echo "</pre>";
die();*/

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

$objPHPExcel->getProperties()->setCreator("LTM Site")->setLastModifiedBy("LTM Site")->setTitle("Office 2007 XLSX Test Document")->setSubject("Office 2007 XLSX Test Document") ->setDescription("Document generated list of meetings.")->setKeywords("office 2007 openxml php");

$objPHPExcel->setActiveSheetIndex(0);
$aSheet = $objPHPExcel->getActiveSheet();

$baseFont = array(
	'font'=>array(
		'name'=>'Arial',
		'size'=>'12',
		'bold'=>false
	),
    'alignment' => array (
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
		'vertical'   	=> PHPExcel_Style_Alignment::VERTICAL_TOP,
		'wrap'       	=> true,
    )
);
$styleConfirmed = array(
	'font'=>array(
		'name'=>'Arial',
		'size'=>'12',
		'bold'=>false
	),
	'fill' => array(
		'type' => PHPExcel_Style_Fill::FILL_SOLID,
		'color' => array (
			'rgb' => 'CCCCCC'
		)
	),
	'alignment' => array (
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical'   	=> PHPExcel_Style_Alignment::VERTICAL_TOP,
		'wrap'       	=> true,
	)
);
if($arParams["USER_TYPE"] == 'PARTICIP'){
	$styleToUser = array(
		'font'=>array(
			'name'=>'Arial',
			'size'=>'12',
			'bold'=>false
		),
		'alignment' => array (
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			'vertical'   	=> PHPExcel_Style_Alignment::VERTICAL_TOP,
			'wrap'       	=> true,
		),
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array (
				'rgb' => 'FFFA7F'
			)
		),
		'alignment' => array (
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical'   	=> PHPExcel_Style_Alignment::VERTICAL_TOP,
			'wrap'       	=> true,
		)
	);
	$styleFromUser = array(
		'font'=>array(
			'name'=>'Arial',
			'size'=>'12',
			'bold'=>false
		),
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array (
				'rgb' => 'FF7F7F'
			)
		),
		'alignment' => array (
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical'   	=> PHPExcel_Style_Alignment::VERTICAL_TOP,
			'wrap'       	=> true,
		)
	);
}
else{
	$styleFromUser = array(
		'font'=>array(
			'name'=>'Arial',
			'size'=>'12',
			'bold'=>false
		),
		'alignment' => array (
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			'vertical'   	=> PHPExcel_Style_Alignment::VERTICAL_TOP,
			'wrap'       	=> true,
		),
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array (
				'rgb' => 'FFFA7F'
			)
		),
		'alignment' => array (
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical'   	=> PHPExcel_Style_Alignment::VERTICAL_TOP,
			'wrap'       	=> true,
		)
	);
	$styleToUser = array(
		'font'=>array(
			'name'=>'Arial',
			'size'=>'12',
			'bold'=>false
		),
		'fill' => array(
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array (
				'rgb' => 'FF7F7F'
			)
		),
		'alignment' => array (
			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			'vertical'   	=> PHPExcel_Style_Alignment::VERTICAL_TOP,
			'wrap'       	=> true,
		)
	);
}

$arABC = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V",
				"W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE");
/*Устанавливаем ширину колонок (разная инфа, лучше индивидуально)*/
$aSheet->getColumnDimension($arABC[0])->setWidth(25);
for($i=1; $i < count($arResult["TIME"])+1; $i++){
	$aSheet->getColumnDimension($arABC[$i])->setWidth(25);
}
/* Шапка таблицы */
$row_count = 1;
$col_count = 1;
$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row_count, "Компания и представитель");
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row_count)->applyFromArray($baseFont);
$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $row_count)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
foreach($arResult["TIME"] as $timeslot){
 	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, $timeslot["name"]);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col_count, $row_count)->applyFromArray($baseFont);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col_count, $row_count)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $col_count++;
}
$row_count++;

/* Вывод данных */
foreach ($arResult["USERS"] as $user) {
	$col_count = 0;
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, $user["name"].", ".$user["rep"]);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col_count, $row_count)->applyFromArray($baseFont);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col_count, $row_count)->getAlignment()->setWrapText(true);
	$col_count++;
	foreach($arResult['TIME'] as $timeslot){
		if($user["schedule"][ $timeslot["id"] ]['status'] == 'confirmed'){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, $user["schedule"][ $timeslot["id"] ]["company_name"].", ".$user["schedule"][ $timeslot["id"] ]["rep"]);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col_count, $row_count)->applyFromArray($styleConfirmed);
		}
		elseif($user["schedule"][ $timeslot["id"] ]["is_busy"]){
			if($user["schedule"][ $timeslot["id"] ]["user_is_sender"]){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, $user["schedule"][ $timeslot["id"] ]["company_name"].", ".$user["schedule"][ $timeslot["id"] ]["rep"]);
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col_count, $row_count)->applyFromArray($styleFromUser);
			}
			else{
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, $user["schedule"][ $timeslot["id"] ]["company_name"].", ".$user["schedule"][ $timeslot["id"] ]["rep"]);
				$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col_count, $row_count)->applyFromArray($styleToUser);
			}
		}
		else{
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, "  ");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col_count, $row_count)->applyFromArray($baseFont);
		}
	    $col_count++;
	}
	$row_count++;
}

$objPHPExcel->getActiveSheet()->setTitle('Matrix');
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