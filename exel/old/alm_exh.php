<?
$type = strip_tags($_REQUEST['type']);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
if($type == 'yes'){
	if(CModule::IncludeModule("iblock") && CModule::IncludeModule("form")){
		//Все пользователи группы "Участники Алма-ата подтвержденные (не все)"
		$userExelAr = array();
		$exExelAr = array();
		$exelAr = array();
		$filter = Array("GROUPS_ID"   => LuxorConfig::GROUP_USER_ALM_P); 
		$i = 0;
		$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_ID_COMP", "UF_PAS", 'UF_ID4', 'UF_ID9')));
		while ($arUser = $rsUsers->Fetch()){
			$userExelAr[$i]['ID']       = $arUser['ID'];
			$userExelAr[$i]['LOGIN']    = $arUser['LOGIN'];
			$userExelAr[$i]['PASSWORD'] = LuxorConfig::returnPas($arUser['UF_PAS']);
			
			//Представитель 1
			if($arUser['UF_ID4'] != ''){
				LuxorConfig::getAnswerFormSimple(
					LuxorConfig::ID_E_ALM, 
					$arrAnswersVarnameE, 
					array('RESULT_ID'=>$arUser['UF_ID4'])
				);
				$keys1[] = $i;
			}	
			
			//Коллега
			if($arUser['UF_ID9'] != ''){
				LuxorConfig::getAnswerFormSimple(
					LuxorConfig::ID_E_ALM,  
					$arrAnswersVarnameE6, 
					array('RESULT_ID'=>$arUser['UF_ID9'])
				);
				$keys6[] = $i;
			}
			
			//результат формы "Участники данные компании ВСЕ ВЫСТАВКИ"
			if($arUser['UF_ID_COMP'] != ''){
				LuxorConfig::getAnswerFormSimple(
					LuxorConfig::ID_E_FORM, 
					$arrAnswersVarname, 
					array('RESULT_ID'=>$arUser['UF_ID_COMP'])
				);
				$keys[] = $i;
			}
			$i++;
		}

		$j = 0;
		foreach($arrAnswersVarnameE as $ex){
			if(trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'Mr.' || trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'None'){
				$hall = '';
			}else{
				$hall = $ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT'];
			}
			$exExelAr[0][$keys1[$j]]['F_NAME']        = $ex['SIMPLE_QUESTION_948'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['L_NAME']        = $ex['SIMPLE_QUESTION_159'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['SOLUTION']      = $ex['SIMPLE_QUESTION_270'][0]['ANSWER_TEXT'];
			$exExelAr[0][$keys1[$j]]['JOB']           = $ex['SIMPLE_QUESTION_993'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['PHONE']         = $ex['SIMPLE_QUESTION_434'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['MAIL']          = $ex['SIMPLE_QUESTION_742'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['ALT_MAIL']      = $ex['SIMPLE_QUESTION_528'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['HALL']          = $hall;
			$exExelAr[0][$keys1[$j]]['TABLE']         = $ex['SIMPLE_QUESTION_778'][0]['USER_TEXT'];
			$j++;
		}
		$j = 0;
		foreach($arrAnswersVarnameE6 as $ex){
			if(trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'Mr.' || trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'None'){
				$hall = '';
			}else{
				$hall = $ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT'];
			}
			$exExelAr[5][$keys6[$j]]['F_NAME2']        = $ex['SIMPLE_QUESTION_948'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['L_NAME2']        = $ex['SIMPLE_QUESTION_159'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['SOLUTION2']      = $ex['SIMPLE_QUESTION_270'][0]['ANSWER_TEXT'];
			$exExelAr[5][$keys6[$j]]['JOB2']           = $ex['SIMPLE_QUESTION_993'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['PHONE2']         = $ex['SIMPLE_QUESTION_434'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['MAIL2']          = $ex['SIMPLE_QUESTION_742'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['ALT_MAIL2']      = $ex['SIMPLE_QUESTION_528'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['HALL2']          = $hall;
			$exExelAr[5][$keys6[$j]]['TABLE2']         = $ex['SIMPLE_QUESTION_778'][0]['USER_TEXT'];
			$j++;
		}
		$i = 0;
		foreach($arrAnswersVarname as $v){
			$exelAr[$i]['NAME_COMP']      = ucfirst($v['SIMPLE_QUESTION_988'][0]['USER_TEXT']);;
			$exelAr[$i]['AREA_OF_B']      = $v['SIMPLE_QUESTION_284'][0]['ANSWER_TEXT'];
			$exelAr[$i]['ADRESS_COMP']    = $v['SIMPLE_QUESTION_295'][0]['USER_TEXT'];
			$exelAr[$i]['CITY_COMP']      = $v['SIMPLE_QUESTION_320'][0]['USER_TEXT'];
			$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_778'][0]['USER_TEXT'];
			$exelAr[$i]['SITE_COMP']      = $v['SIMPLE_QUESTION_501'][0]['USER_TEXT'];
			$exelAr[$i]['DESCR_COMP']     = $v['SIMPLE_QUESTION_163'][0]['USER_TEXT'];
			
			//Europe
			foreach($v['SIMPLE_QUESTION_367'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//North America
			foreach($v['SIMPLE_QUESTION_876'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//South America
			foreach($v['SIMPLE_QUESTION_328'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//Asia
			foreach($v['SIMPLE_QUESTION_931'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//Africa
			foreach($v['SIMPLE_QUESTION_459'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//Oceania
			foreach($v['SIMPLE_QUESTION_445'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			
			$i++;
		}
		//c($userExelAr);
		//c($exExelAr);
		//c($exelAr);
	}
	
	//c($arrAnswersVarnameE);
	//die();
	
	//сортировка по названию компании
	foreach($exelAr as $k=>$v){
		$exelArMod[$k] = array_merge($v, $userExelAr[$k], (array)$exExelAr[0][$k], (array)$exExelAr[5][$k]);
	}
	
	$data_year=array();
	//Генерируем "определяющий" массив
	foreach($exelArMod as $key=>$arr){
		$data_year[$key]=$arr['NAME_COMP'];
	}
	
	$countAar = count($exelArMod);
	
	for($i=0; $i<$countAar; $i++){
		array_multisort($data_year, SORT_STRING, $exelArMod);
	}
	
	//error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');


	require_once 'PHPExcel.php';


	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Vladimir Sinica")->setLastModifiedBy("Vladimir Sinica")->setTitle("Office 2007 XLSX Test Document")->setSubject("Office 2007 XLSX Test Document") ->setDescription("Test document generated list of exhibitors.")->setKeywords("office 2007 openxml php");

	$objPHPExcel->setActiveSheetIndex(0);
	$aSheet = $objPHPExcel->getActiveSheet();
	$aSheet->getColumnDimension('A')->setWidth(50);	
	$aSheet->getColumnDimension('B')->setWidth(50);	
	$aSheet->getColumnDimension('C')->setWidth(50);	
	$aSheet->getColumnDimension('D')->setWidth(35);	
	$aSheet->getColumnDimension('E')->setWidth(35);	
	$aSheet->getColumnDimension('F')->setWidth(35);
	$aSheet->getColumnDimension('G')->setWidth(50);	
	$aSheet->getColumnDimension('H')->setWidth(35);	
	$aSheet->getColumnDimension('I')->setWidth(35);
	$aSheet->getColumnDimension('J')->setWidth(75);
	$aSheet->getColumnDimension('K')->setWidth(50);
	$aSheet->getColumnDimension('L')->setWidth(30);
	$aSheet->getColumnDimension('M')->setWidth(30);
	$aSheet->getColumnDimension('N')->setWidth(20);
	$aSheet->getColumnDimension('O')->setWidth(35);
	$aSheet->getColumnDimension('P')->setWidth(35);
	$aSheet->getColumnDimension('Q')->setWidth(35);
	$aSheet->getColumnDimension('R')->setWidth(35);
	$aSheet->getColumnDimension('S')->setWidth(35);
	$aSheet->getColumnDimension('T')->setWidth(35);
	$aSheet->getColumnDimension('U')->setWidth(35);
	$aSheet->getColumnDimension('V')->setWidth(35);
	$aSheet->getColumnDimension('W')->setWidth(35);
	$aSheet->getColumnDimension('X')->setWidth(35);
	$aSheet->getColumnDimension('Y')->setWidth(35);

	$baseFont = array(
		'font'=>array(
			'name'=>'Arial',
			'size'=>'12',
			'bold'=>false
		)
	);

	$aSheet->setCellValue('A1', 'uID');
	$aSheet->getStyle('A1')->applyFromArray($baseFont);
	$aSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('B1', 'Login');
	$aSheet->getStyle('B1')->applyFromArray($baseFont);
	$aSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C1', 'Password (decoded)');
	$aSheet->getStyle('C1')->applyFromArray($baseFont);
	$aSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D1', 'Company or Hotel name');
	$aSheet->getStyle('D1')->applyFromArray($baseFont);
	$aSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E1', 'Area of business');
	$aSheet->getStyle('E1')->applyFromArray($baseFont);
	$aSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F1', 'Address');
	$aSheet->getStyle('F1')->applyFromArray($baseFont);
	$aSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G1', 'City');
	$aSheet->getStyle('G1')->applyFromArray($baseFont);
	$aSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H1', 'Country');
	$aSheet->getStyle('H1')->applyFromArray($baseFont);
	$aSheet->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I1', "Company's web-site");
	$aSheet->getStyle('I1')->applyFromArray($baseFont);
	$aSheet->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J1', 'Company description');
	$aSheet->getStyle('J1')->applyFromArray($baseFont);
	$aSheet->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K1', "Priority destinations");
	$aSheet->getStyle('K1')->applyFromArray($baseFont);
	$aSheet->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L1', "Participant first name");
	$aSheet->getStyle('L1')->applyFromArray($baseFont);
	$aSheet->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('M1', "Participant last name");
	$aSheet->getStyle('M1')->applyFromArray($baseFont);
	$aSheet->getStyle('M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('N1', "Title (salutation)");
	$aSheet->getStyle('N1')->applyFromArray($baseFont);
	$aSheet->getStyle('N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('O1', "Job Title");
	$aSheet->getStyle('O1')->applyFromArray($baseFont);
	$aSheet->getStyle('O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('P1', "Telephone number");
	$aSheet->getStyle('P1')->applyFromArray($baseFont);
	$aSheet->getStyle('P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('Q1', "Email");
	$aSheet->getStyle('Q1')->applyFromArray($baseFont);
	$aSheet->getStyle('Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('R1', "Alternative email");
	$aSheet->getStyle('R1')->applyFromArray($baseFont);
	$aSheet->getStyle('R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('S1', "Title College (Salutation)");
	$aSheet->getStyle('S1')->applyFromArray($baseFont);
	$aSheet->getStyle('S1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('T1', "First Name College");
	$aSheet->getStyle('T1')->applyFromArray($baseFont);
	$aSheet->getStyle('T1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('U1', "Last Name College");
	$aSheet->getStyle('U1')->applyFromArray($baseFont);
	$aSheet->getStyle('U1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('V1', "Job Title College");
	$aSheet->getStyle('V1')->applyFromArray($baseFont);
	$aSheet->getStyle('V1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('W1', "Email College");
	$aSheet->getStyle('W1')->applyFromArray($baseFont);
	$aSheet->getStyle('W1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('X1', "Table");
	$aSheet->getStyle('X1')->applyFromArray($baseFont);
	$aSheet->getStyle('X1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('Y1', "Hall");
	$aSheet->getStyle('Y1')->applyFromArray($baseFont);
	$aSheet->getStyle('Y1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$str = 1;
	/*foreach($exelArMod as $key=>$mas){
		$str++;
		if (array_key_exists($key, $userExelAr)) {
			$aSheet->setCellValue('A'.$str, $userExelAr[$key]['ID']);
			$aSheet->setCellValue('B'.$str, $userExelAr[$key]['LOGIN']);
			$aSheet->setCellValue('C'.$str, $userExelAr[$key]['PASSWORD']);
		}
		$aSheet->setCellValue('D'.$str, $mas['NAME_COMP']);
		$aSheet->setCellValue('E'.$str, $mas['AREA_OF_B']);
		$aSheet->setCellValue('F'.$str, $mas['ADRESS_COMP']);
		$aSheet->getStyle('F'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('G'.$str, $mas['CITY_COMP']);
		$aSheet->setCellValue('H'.$str, $mas['COUNTRY_COMP']);
		$aSheet->setCellValue('I'.$str, $mas['SITE_COMP']);
		//$mas['DESCR_COMP'] = str_replace('вЂ', ' ', $mas['DESCR_COMP']);
		$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESCR_COMP']));
		$aSheet->getStyle('J'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('K'.$str, implode(',', $mas['AREAS_COMP']));
		$aSheet->getStyle('K'.$str)->getAlignment()->setWrapText(1);
		if (array_key_exists($key, $exExelAr[0])) {
			$aSheet->setCellValue('L'.$str, iconv('WINDOWS-1251', 'UTF-8', $exExelAr[0][$key]['F_NAME']));
			//$aSheet->setCellValue('L'.$str, $exExelAr[0][$key]['F_NAME']);
			//$aSheet->setCellValue('M'.$str, $exExelAr[0][$key]['L_NAME']);
			$aSheet->setCellValue('M'.$str, iconv('WINDOWS-1251', 'UTF-8', $exExelAr[0][$key]['L_NAME']));
			//$aSheet->setCellValue('N'.$str, $exExelAr[0][$key]['SOLUTION']);
			$aSheet->setCellValue('N'.$str, iconv('WINDOWS-1251', 'UTF-8', $exExelAr[0][$key]['SOLUTION']));
			//$aSheet->setCellValue('O'.$str, $exExelAr[0][$key]['JOB']);
			$aSheet->setCellValue('O'.$str, iconv('WINDOWS-1251', 'UTF-8', $exExelAr[0][$key]['JOB']));
			$aSheet->setCellValue('P'.$str, $exExelAr[0][$key]['PHONE']);
			$aSheet->setCellValue('Q'.$str, $exExelAr[0][$key]['MAIL']);
			$aSheet->setCellValue('R'.$str, $exExelAr[0][$key]['ALT_MAIL']);
			$aSheet->setCellValue('X'.$str, $exExelAr[0][$key]['TABLE']);
			$aSheet->setCellValue('Y'.$str, $exExelAr[0][$key]['HALL']);
		}
		if (array_key_exists($key, $exExelAr[5])) {
			//$aSheet->setCellValue('S'.$str, $exExelAr[5][$key]['SOLUTION']);
			$aSheet->setCellValue('S'.$str, iconv('WINDOWS-1251', 'UTF-8', $exExelAr[5][$key]['SOLUTION']));
			//$aSheet->setCellValue('T'.$str, $exExelAr[5][$key]['F_NAME']);
			$aSheet->setCellValue('T'.$str, iconv('WINDOWS-1251', 'UTF-8', $str, $exExelAr[5][$key]['F_NAME']));
			//$aSheet->setCellValue('U'.$str, $exExelAr[5][$key]['L_NAME']);
			$aSheet->setCellValue('U'.$str, iconv('WINDOWS-1251', 'UTF-8', $str, $exExelAr[5][$key]['L_NAME']));
			//$aSheet->setCellValue('V'.$str, $exExelAr[5][$key]['JOB']);
			$aSheet->setCellValue('V'.$str, iconv('WINDOWS-1251', 'UTF-8', $str, $exExelAr[5][$key]['JOB']));
			$aSheet->setCellValue('W'.$str, $exExelAr[5][$key]['MAIL']);
		}
	}*/
	
	foreach($exelArMod as $key=>$mas){
		$str++;
		//if (array_key_exists($key, $userExelAr)) {
			$aSheet->setCellValue('A'.$str, $mas['ID']);
			$aSheet->setCellValue('B'.$str, $mas['LOGIN']);
			$aSheet->setCellValue('C'.$str, $mas['PASSWORD']);
		//}
		$aSheet->setCellValue('D'.$str, $mas['NAME_COMP']);
		$aSheet->setCellValue('E'.$str, $mas['AREA_OF_B']);
		$aSheet->setCellValue('F'.$str, $mas['ADRESS_COMP']);
		$aSheet->getStyle('F'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('G'.$str, $mas['CITY_COMP']);
		$aSheet->setCellValue('H'.$str, $mas['COUNTRY_COMP']);
		$aSheet->setCellValue('I'.$str, $mas['SITE_COMP']);
		//$mas['DESCR_COMP'] = str_replace('вЂ', ' ', $mas['DESCR_COMP']);
		$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESCR_COMP']));
		$aSheet->getStyle('J'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('K'.$str, implode(',', $mas['AREAS_COMP']));
		$aSheet->getStyle('K'.$str)->getAlignment()->setWrapText(1);
		//if (array_key_exists($key, $exExelAr[0])) {
			$aSheet->setCellValue('L'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['F_NAME']));
			//$aSheet->setCellValue('L'.$str, $exExelAr[0][$key]['F_NAME']);
			//$aSheet->setCellValue('M'.$str, $exExelAr[0][$key]['L_NAME']);
			$aSheet->setCellValue('M'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['L_NAME']));
			//$aSheet->setCellValue('N'.$str, $exExelAr[0][$key]['SOLUTION']);
			$aSheet->setCellValue('N'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['SOLUTION']));
			//$aSheet->setCellValue('O'.$str, $exExelAr[0][$key]['JOB']);
			$aSheet->setCellValue('O'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['JOB']));
			$aSheet->setCellValue('P'.$str, $mas['PHONE']);
			$aSheet->setCellValue('Q'.$str, $mas['MAIL']);
			$aSheet->setCellValue('R'.$str, $mas['ALT_MAIL']);
			$aSheet->setCellValue('X'.$str, $mas['TABLE']);
			$aSheet->setCellValue('Y'.$str, $mas['HALL']);
		//}
		//if (array_key_exists($key, $exExelAr[5])) {
			//$aSheet->setCellValue('S'.$str, $exExelAr[5][$key]['SOLUTION']);
			$aSheet->setCellValue('S'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['SOLUTION2']));
			//$aSheet->setCellValue('T'.$str, $exExelAr[5][$key]['F_NAME']);
			$aSheet->setCellValue('T'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['F_NAME2']));
			//$aSheet->setCellValue('U'.$str, $exExelAr[5][$key]['L_NAME']);
			$aSheet->setCellValue('U'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['L_NAME2']));
			//$aSheet->setCellValue('V'.$str, $exExelAr[5][$key]['JOB']);
			$aSheet->setCellValue('V'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['JOB2']));
			$aSheet->setCellValue('W'.$str, $mas['MAIL2']);
		//}
	}

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Participants_excel');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Участники Алматы подтвержденные.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}elseif($type == 'yes_all'){
	if(CModule::IncludeModule("iblock") && CModule::IncludeModule("form")){
		//Все пользователи группы "Участники Алма-ата подтвержденные (все)"
		$userExelAr = array();
		$exExelAr = array();
		$exelAr = array();
		$filter = Array("GROUPS_ID"   => LuxorConfig::GROUP_USER_ALM_P); 
		$i = 0;
		$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_ID_COMP", "UF_PAS", 'UF_ID4', 'UF_ID9')));
		while ($arUser = $rsUsers->Fetch()){
			$userExelAr[$i]['ID']       = $arUser['ID'];
			$userExelAr[$i]['LOGIN']    = $arUser['LOGIN'];
			$userExelAr[$i]['PASSWORD'] = LuxorConfig::returnPas($arUser['UF_PAS']);
			
			//Представитель 1
			if($arUser['UF_ID4'] != ''){
				LuxorConfig::getAnswerFormSimple(
					LuxorConfig::ID_E_ALM,  
					$arrAnswersVarnameE, 
					array('RESULT_ID'=>$arUser['UF_ID4'])
				);
				$keys1[] = $i;
			}	
			//Коллега
			if($arUser['UF_ID9'] != ''){
				LuxorConfig::getAnswerFormSimple(
					LuxorConfig::ID_E_ALM, 
					$arrAnswersVarnameE6, 
					array('RESULT_ID'=>$arUser['UF_ID9'])
				);
				$keys6[] = $i;
			}
			//результат формы "Участники данные компании ВСЕ ВЫСТАВКИ"
			if($arUser['UF_ID_COMP'] != ''){
				LuxorConfig::getAnswerFormSimple(
					LuxorConfig::ID_E_FORM,  
					$arrAnswersVarname, 
					array('RESULT_ID'=>$arUser['UF_ID_COMP'])
				);
				$keys[] = $i;
			}
			$i++;
		}
		$j = 0;
		foreach($arrAnswersVarnameE as $ex){
			if(trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'Mr.' || trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'None'){
				$hall = '';
			}else{
				$hall = $ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT'];
			}
			$exExelAr[0][$keys1[$j]]['F_NAME']        = $ex['SIMPLE_QUESTION_948'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['L_NAME']        = $ex['SIMPLE_QUESTION_159'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['SOLUTION']      = $ex['SIMPLE_QUESTION_270'][0]['ANSWER_TEXT'];
			$exExelAr[0][$keys1[$j]]['JOB']           = $ex['SIMPLE_QUESTION_993'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['PHONE']         = $ex['SIMPLE_QUESTION_434'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['MAIL']          = $ex['SIMPLE_QUESTION_742'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['ALT_MAIL']      = $ex['SIMPLE_QUESTION_528'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['HALL']          = $hall;
			$exExelAr[0][$keys1[$j]]['TABLE']         = $ex['SIMPLE_QUESTION_778'][0]['USER_TEXT'];
			$j++;
		}
		$j = 0;
		foreach($arrAnswersVarnameE6 as $ex){
			if(trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'Mr.' || trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'None'){
				$hall = '';
			}else{
				$hall = $ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT'];
			}
			$exExelAr[5][$keys6[$j]]['F_NAME2']        = $ex['SIMPLE_QUESTION_948'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['L_NAME2']        = $ex['SIMPLE_QUESTION_159'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['SOLUTION2']      = $ex['SIMPLE_QUESTION_270'][0]['ANSWER_TEXT'];
			$exExelAr[5][$keys6[$j]]['JOB2']           = $ex['SIMPLE_QUESTION_993'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['PHONE2']         = $ex['SIMPLE_QUESTION_434'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['MAIL2']          = $ex['SIMPLE_QUESTION_742'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['ALT_MAIL2']      = $ex['SIMPLE_QUESTION_528'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['HALL2']          = $hall;
			$exExelAr[5][$keys6[$j]]['TABLE2']         = $ex['SIMPLE_QUESTION_778'][0]['USER_TEXT'];
			$j++;
		}
		$i = 0;
		foreach($arrAnswersVarname as $v){
			$exelAr[$i]['NAME_COMP']      = ucfirst($v['SIMPLE_QUESTION_988'][0]['USER_TEXT']);;
			$exelAr[$i]['AREA_OF_B']      = $v['SIMPLE_QUESTION_284'][0]['ANSWER_TEXT'];
			$exelAr[$i]['ADRESS_COMP']    = $v['SIMPLE_QUESTION_295'][0]['USER_TEXT'];
			$exelAr[$i]['CITY_COMP']      = $v['SIMPLE_QUESTION_320'][0]['USER_TEXT'];
			$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_778'][0]['USER_TEXT'];
			$exelAr[$i]['SITE_COMP']      = $v['SIMPLE_QUESTION_501'][0]['USER_TEXT'];
			$exelAr[$i]['DESCR_COMP']     = $v['SIMPLE_QUESTION_163'][0]['USER_TEXT'];
			
			//Europe
			foreach($v['SIMPLE_QUESTION_367'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//North America
			foreach($v['SIMPLE_QUESTION_876'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//South America
			foreach($v['SIMPLE_QUESTION_328'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//Asia
			foreach($v['SIMPLE_QUESTION_931'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//Africa
			foreach($v['SIMPLE_QUESTION_459'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//Oceania
			foreach($v['SIMPLE_QUESTION_445'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			
			$i++;
		}
		//c($userExelAr);
		//c($exExelAr);
		//c($exelAr);
	}

	
	
	//сортировка по названию компании
	foreach($exelAr as $k=>$v){
		$exelArMod[$k] = array_merge($v, $userExelAr[$k], (array)$exExelAr[0][$k], (array)$exExelAr[5][$k]);
	}
	
	$data_year=array();
	//Генерируем "определяющий" массив
	foreach($exelArMod as $key=>$arr){
		$data_year[$key]=$arr['NAME_COMP'];
	}
	
	$countAar = count($exelArMod);
	
	for($i=0; $i<$countAar; $i++){
		array_multisort($data_year, SORT_STRING, $exelArMod);
	}
	
	
	
	//массивы готовы для записи
	/** Error reporting */
	//error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');

	/** Include PHPExcel */
	require_once 'PHPExcel.php';


	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Vladimir Sinica")->setLastModifiedBy("Vladimir Sinica")->setTitle("Office 2007 XLSX Test Document")->setSubject("Office 2007 XLSX Test Document") ->setDescription("Test document generated list of exhibitors.")->setKeywords("office 2007 openxml php");

	$objPHPExcel->setActiveSheetIndex(0);
	$aSheet = $objPHPExcel->getActiveSheet();
	$aSheet->getColumnDimension('A')->setWidth(50);	
	$aSheet->getColumnDimension('B')->setWidth(50);	
	$aSheet->getColumnDimension('C')->setWidth(50);	
	$aSheet->getColumnDimension('D')->setWidth(35);	
	$aSheet->getColumnDimension('E')->setWidth(35);	
	$aSheet->getColumnDimension('F')->setWidth(35);
	$aSheet->getColumnDimension('G')->setWidth(50);	
	$aSheet->getColumnDimension('H')->setWidth(35);	
	$aSheet->getColumnDimension('I')->setWidth(35);
	$aSheet->getColumnDimension('J')->setWidth(75);
	$aSheet->getColumnDimension('K')->setWidth(50);
	$aSheet->getColumnDimension('L')->setWidth(30);
	$aSheet->getColumnDimension('M')->setWidth(30);
	$aSheet->getColumnDimension('N')->setWidth(20);
	$aSheet->getColumnDimension('O')->setWidth(35);
	$aSheet->getColumnDimension('P')->setWidth(35);
	$aSheet->getColumnDimension('Q')->setWidth(35);
	$aSheet->getColumnDimension('R')->setWidth(35);
	$aSheet->getColumnDimension('S')->setWidth(35);
	$aSheet->getColumnDimension('T')->setWidth(35);

	$baseFont = array(
		'font'=>array(
			'name'=>'Arial',
			'size'=>'12',
			'bold'=>false
		)
	);
	
	function cellColor($cells,$color){
        global $objPHPExcel;
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()
        ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => $color)
        ));
    }


	$aSheet->setCellValue('A1', 'uID');
	$aSheet->getStyle('A1')->applyFromArray($baseFont);
	$aSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('B1', 'Login');
	$aSheet->getStyle('B1')->applyFromArray($baseFont);
	$aSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C1', 'Password (decoded)');
	$aSheet->getStyle('C1')->applyFromArray($baseFont);
	$aSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D1', 'Company or Hotel name');
	$aSheet->getStyle('D1')->applyFromArray($baseFont);
	$aSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E1', 'Area of business');
	$aSheet->getStyle('E1')->applyFromArray($baseFont);
	$aSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F1', 'Address');
	$aSheet->getStyle('F1')->applyFromArray($baseFont);
	$aSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G1', 'City');
	$aSheet->getStyle('G1')->applyFromArray($baseFont);
	$aSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H1', 'Country');
	$aSheet->getStyle('H1')->applyFromArray($baseFont);
	$aSheet->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I1', "Company's web-site");
	$aSheet->getStyle('I1')->applyFromArray($baseFont);
	$aSheet->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J1', 'Company description');
	$aSheet->getStyle('J1')->applyFromArray($baseFont);
	$aSheet->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K1', "Priority destinations");
	$aSheet->getStyle('K1')->applyFromArray($baseFont);
	$aSheet->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L1', "Participant first name");
	$aSheet->getStyle('L1')->applyFromArray($baseFont);
	$aSheet->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('M1', "Participant last name");
	$aSheet->getStyle('M1')->applyFromArray($baseFont);
	$aSheet->getStyle('M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('N1', "Title (salutation)");
	$aSheet->getStyle('N1')->applyFromArray($baseFont);
	$aSheet->getStyle('N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('O1', "Job Title");
	$aSheet->getStyle('O1')->applyFromArray($baseFont);
	$aSheet->getStyle('O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('P1', "Telephone number");
	$aSheet->getStyle('P1')->applyFromArray($baseFont);
	$aSheet->getStyle('P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('Q1', "Email");
	$aSheet->getStyle('Q1')->applyFromArray($baseFont);
	$aSheet->getStyle('Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('R1', "Alternative email");
	$aSheet->getStyle('R1')->applyFromArray($baseFont);
	$aSheet->getStyle('R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('S1', "Table");
	$aSheet->getStyle('S1')->applyFromArray($baseFont);
	$aSheet->getStyle('S1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('T1', "Hall");
	$aSheet->getStyle('T1')->applyFromArray($baseFont);
	$aSheet->getStyle('T1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$aSheet->setCellValue('A2', 'uID');
	$aSheet->getStyle('A2')->applyFromArray($baseFont);
	$aSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('B2', 'Login');
	$aSheet->getStyle('B2')->applyFromArray($baseFont);
	$aSheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C2', 'Password (decoded)');
	$aSheet->getStyle('C2')->applyFromArray($baseFont);
	$aSheet->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D2', 'Company or Hotel name');
	$aSheet->getStyle('D2')->applyFromArray($baseFont);
	$aSheet->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E2', 'Area of business');
	$aSheet->getStyle('E2')->applyFromArray($baseFont);
	$aSheet->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F2', 'Address');
	$aSheet->getStyle('F2')->applyFromArray($baseFont);
	$aSheet->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G2', 'City');
	$aSheet->getStyle('G2')->applyFromArray($baseFont);
	$aSheet->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H2', 'Country');
	$aSheet->getStyle('H2')->applyFromArray($baseFont);
	$aSheet->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I2', "Company's web-site");
	$aSheet->getStyle('I2')->applyFromArray($baseFont);
	$aSheet->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J2', 'Company description');
	$aSheet->getStyle('J2')->applyFromArray($baseFont);
	$aSheet->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K2', "Priority destinations");
	$aSheet->getStyle('K2')->applyFromArray($baseFont);
	$aSheet->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L2', "First Name College");
	$aSheet->getStyle('L2')->applyFromArray($baseFont);
	$aSheet->getStyle('L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('M2', "Last Name College");
	$aSheet->getStyle('M2')->applyFromArray($baseFont);
	$aSheet->getStyle('M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('N2', "Title College (Salutation)");
	$aSheet->getStyle('N2')->applyFromArray($baseFont);
	$aSheet->getStyle('N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('O2', "Job Title College");
	$aSheet->getStyle('O2')->applyFromArray($baseFont);
	$aSheet->getStyle('O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('P2', "Telephone number");
	$aSheet->getStyle('P2')->applyFromArray($baseFont);
	$aSheet->getStyle('P2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('Q2', "Email College");
	$aSheet->getStyle('Q2')->applyFromArray($baseFont);
	$aSheet->getStyle('Q2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('R2', "Alternative email");
	$aSheet->getStyle('R2')->applyFromArray($baseFont);
	$aSheet->getStyle('R2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('S2', "Table");
	$aSheet->getStyle('S2')->applyFromArray($baseFont);
	$aSheet->getStyle('S2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('T2', "Hall");
	$aSheet->getStyle('T2')->applyFromArray($baseFont);
	$aSheet->getStyle('T2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$str = 1;
	$strNext = 2;
	foreach($exelArMod as $key=>$mas){
		$str += 3;
		$strNext += 3;
		//if (array_key_exists($key, $userExelAr)) {
			$aSheet->setCellValue('A'.$str, $mas['ID']);
			$aSheet->setCellValue('B'.$str, $mas['LOGIN']);
			$aSheet->setCellValue('C'.$str, $mas['PASSWORD']);
			cellColor('A'.$str.':C'.$str, 'f3e0bb');
			if($mas['F_NAME2'] != '' && $mas['F_NAME2'] != 'Participant first name'){
				$aSheet->setCellValue('A'.$strNext, $mas['ID']);
				$aSheet->setCellValue('B'.$strNext, $mas['LOGIN']);
				$aSheet->setCellValue('C'.$strNext, $mas['PASSWORD']);
				cellColor('A'.$strNext.':C'.$strNext, 'c7f5a9');
			}
		//}
		$aSheet->setCellValue('D'.$str, $mas['NAME_COMP']);
		$aSheet->setCellValue('E'.$str, $mas['AREA_OF_B']);
		$aSheet->setCellValue('F'.$str, $mas['ADRESS_COMP']);
		$aSheet->getStyle('F'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('G'.$str, $mas['CITY_COMP']);
		$aSheet->setCellValue('H'.$str, $mas['COUNTRY_COMP']);
		$aSheet->setCellValue('I'.$str, $mas['SITE_COMP']);
		//$mas['DESCR_COMP'] = str_replace('вЂ', ' ', $mas['DESCR_COMP']);
		$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESCR_COMP']));
		$aSheet->getStyle('J'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('K'.$str, implode(',', $mas['AREAS_COMP']));
		$aSheet->getStyle('K'.$str)->getAlignment()->setWrapText(1);
		cellColor('D'.$str.':K'.$str, 'f3e0bb');
		if($mas['F_NAME2'] != '' && $mas['F_NAME2'] != 'Participant first name'){
			$aSheet->setCellValue('D'.$strNext, $mas['NAME_COMP']);
			$aSheet->setCellValue('E'.$strNext, $mas['AREA_OF_B']);
			$aSheet->setCellValue('F'.$strNext, $mas['ADRESS_COMP']);
			$aSheet->getStyle('F'.$str)->getAlignment()->setWrapText(1);
			$aSheet->setCellValue('G'.$strNext, $mas['CITY_COMP']);
			$aSheet->setCellValue('H'.$strNext, $mas['COUNTRY_COMP']);
			$aSheet->setCellValue('I'.$strNext, $mas['SITE_COMP']);
			$mas['DESCR_COMP'] = str_replace('вЂ', ' ', $mas['DESCR_COMP']);
			//$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESCR_COMP']));
			if(preg_match("#[а-яё]+#iu", $mas['DESCR_COMP'])){
				$aSheet->setCellValue('J'.$strNext, iconv('WINDOWS-1251', 'UTF-8', $mas['DESCR_COMP']));
			}else{
				$aSheet->setCellValue('J'.$strNext, $mas['DESCR_COMP']);
			}
			$aSheet->getStyle('J'.$strNext)->getAlignment()->setWrapText(1);
			$aSheet->setCellValue('K'.$strNext, implode(',', $mas['AREAS_COMP']));
			$aSheet->getStyle('K'.$strNext)->getAlignment()->setWrapText(1);
			cellColor('D'.$strNext.':K'.$strNext, 'c7f5a9');
		}
		//if (array_key_exists($key, $exExelAr[0])) {
			//$aSheet->setCellValue('L'.$str, $exExelAr[0][$key]['F_NAME']);
			$aSheet->setCellValue('L'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['F_NAME']));
			//$aSheet->setCellValue('M'.$str, $exExelAr[0][$key]['L_NAME']);
			$aSheet->setCellValue('M'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['L_NAME']));
			//$aSheet->setCellValue('N'.$str, $exExelAr[0][$key]['SOLUTION']);
			$aSheet->setCellValue('N'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['SOLUTION']));
			//$aSheet->setCellValue('O'.$str, $exExelAr[0][$key]['JOB']);
			$aSheet->setCellValue('O'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['JOB']));
			$aSheet->setCellValue('P'.$str, $mas['PHONE']);
			$aSheet->setCellValue('Q'.$str, $mas['MAIL']);
			$aSheet->setCellValue('R'.$str, $mas['ALT_MAIL']);
			$aSheet->setCellValue('S'.$str, $mas['TABLE']);
			$aSheet->setCellValue('T'.$str, $mas['HALL']);
			cellColor('L'.$str.':T'.$str, 'f3e0bb');
		//}
		//if (array_key_exists($key, $exExelAr[5])) {
			if($mas['F_NAME2'] != '' && $mas['F_NAME2'] != 'Participant first name'){
				//$aSheet->setCellValue('L'.$strNext, $exExelAr[5][$key]['F_NAME']);
				$aSheet->setCellValue('L'.$strNext, iconv('WINDOWS-1251', 'UTF-8', $mas['F_NAME2']));
				//$aSheet->setCellValue('M'.$strNext, $exExelAr[5][$key]['L_NAME']);
				$aSheet->setCellValue('M'.$strNext, iconv('WINDOWS-1251', 'UTF-8', $mas['L_NAME2']));
				//$aSheet->setCellValue('N'.$strNext, $exExelAr[5][$key]['SOLUTION']);
				$aSheet->setCellValue('N'.$strNext, iconv('WINDOWS-1251', 'UTF-8', $mas['SOLUTION2']));
				//$aSheet->setCellValue('O'.$strNext, $exExelAr[5][$key]['JOB']);
				$aSheet->setCellValue('O'.$strNext, iconv('WINDOWS-1251', 'UTF-8', $mas['JOB2']));
				$aSheet->setCellValue('P'.$strNext, $mas['PHONE2']);
				$aSheet->setCellValue('Q'.$strNext, $mas['MAIL2']);
				$aSheet->setCellValue('R'.$strNext, $mas['ALT_MAIL2']);
				$aSheet->setCellValue('S'.$strNext, $mas['TABLE2']);
				$aSheet->setCellValue('T'.$strNext, $mas['HALL2']);
				cellColor('L'.$strNext.':T'.$strNext, 'c7f5a9');
			}
		//}
	}
	

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Participants_excel_all');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Участники Алматы подтвержденные (коллеги отдельно).xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}elseif($type == 'no'){
	if(CModule::IncludeModule("iblock") && CModule::IncludeModule("form")){
		//Все пользователи группы "Участники Алма-ата неподтвержденные (не все)"
		$userExelAr = array();
		$exExelAr = array();
		$exelAr = array();
		$filter = Array("GROUPS_ID"   => LuxorConfig::GROUP_USER_ALM_NP); 
		$i = 0;
		$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_ID_COMP", "UF_PAS", 'UF_ID4', 'UF_ID9')));
		while ($arUser = $rsUsers->Fetch()){
			$userExelAr[$i]['ID']       = $arUser['ID'];
			$userExelAr[$i]['LOGIN']    = $arUser['LOGIN'];
			$userExelAr[$i]['PASSWORD'] = LuxorConfig::returnPas($arUser['UF_PAS']);
			
			//Представитель 1
			if($arUser['UF_ID4'] != ''){
				LuxorConfig::getAnswerFormSimple(
					LuxorConfig::ID_E_ALM, 
					$arrAnswersVarnameE, 
					array('RESULT_ID'=>$arUser['UF_ID4'])
				);
				$keys1[] = $i;
			}	
			
			//Коллега
			if($arUser['UF_ID9'] != ''){
				LuxorConfig::getAnswerFormSimple(
					LuxorConfig::ID_E_ALM,  
					$arrAnswersVarnameE6, 
					array('RESULT_ID'=>$arUser['UF_ID9'])
				);
				$keys6[] = $i;
			}
			
			//результат формы "Участники данные компании ВСЕ ВЫСТАВКИ"
			if($arUser['UF_ID_COMP'] != ''){
				LuxorConfig::getAnswerFormSimple(
					LuxorConfig::ID_E_FORM, 
					$arrAnswersVarname, 
					array('RESULT_ID'=>$arUser['UF_ID_COMP'])
				);
				$keys[] = $i;
			}
			$i++;
		}
		$j = 0;
		foreach($arrAnswersVarnameE as $ex){
			if(trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'Mr.' || trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'None'){
				$hall = '';
			}else{
				$hall = $ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT'];
			}
			$exExelAr[0][$keys1[$j]]['F_NAME']        = $ex['SIMPLE_QUESTION_948'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['L_NAME']        = $ex['SIMPLE_QUESTION_159'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['SOLUTION']      = $ex['SIMPLE_QUESTION_270'][0]['ANSWER_TEXT'];
			$exExelAr[0][$keys1[$j]]['JOB']           = $ex['SIMPLE_QUESTION_993'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['PHONE']         = $ex['SIMPLE_QUESTION_434'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['MAIL']          = $ex['SIMPLE_QUESTION_742'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['ALT_MAIL']      = $ex['SIMPLE_QUESTION_528'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['HALL']          = $hall;
			$exExelAr[0][$keys1[$j]]['TABLE']         = $ex['SIMPLE_QUESTION_778'][0]['USER_TEXT'];
			$j++;
		}
		$j = 0;
		foreach($arrAnswersVarnameE6 as $ex){
			if(trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'Mr.' || trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'None'){
				$hall = '';
			}else{
				$hall = $ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT'];
			}
			$exExelAr[5][$keys6[$j]]['F_NAME2']        = $ex['SIMPLE_QUESTION_948'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['L_NAME2']        = $ex['SIMPLE_QUESTION_159'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['SOLUTION2']      = $ex['SIMPLE_QUESTION_270'][0]['ANSWER_TEXT'];
			$exExelAr[5][$keys6[$j]]['JOB2']           = $ex['SIMPLE_QUESTION_993'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['PHONE2']         = $ex['SIMPLE_QUESTION_434'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['MAIL2']          = $ex['SIMPLE_QUESTION_742'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['ALT_MAIL2']      = $ex['SIMPLE_QUESTION_528'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['HALL2']          = $hall;
			$exExelAr[5][$keys6[$j]]['TABLE2']         = $ex['SIMPLE_QUESTION_778'][0]['USER_TEXT'];
			$j++;
		}
		$i = 0;
		foreach($arrAnswersVarname as $v){
			$exelAr[$i]['NAME_COMP']      = ucfirst($v['SIMPLE_QUESTION_988'][0]['USER_TEXT']);;
			$exelAr[$i]['AREA_OF_B']      = $v['SIMPLE_QUESTION_284'][0]['ANSWER_TEXT'];
			$exelAr[$i]['ADRESS_COMP']    = $v['SIMPLE_QUESTION_295'][0]['USER_TEXT'];
			$exelAr[$i]['CITY_COMP']      = $v['SIMPLE_QUESTION_320'][0]['USER_TEXT'];
			$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_778'][0]['USER_TEXT'];
			$exelAr[$i]['SITE_COMP']      = $v['SIMPLE_QUESTION_501'][0]['USER_TEXT'];
			$exelAr[$i]['DESCR_COMP']     = $v['SIMPLE_QUESTION_163'][0]['USER_TEXT'];
			
			//Europe
			foreach($v['SIMPLE_QUESTION_367'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//North America
			foreach($v['SIMPLE_QUESTION_876'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//South America
			foreach($v['SIMPLE_QUESTION_328'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//Asia
			foreach($v['SIMPLE_QUESTION_931'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//Africa
			foreach($v['SIMPLE_QUESTION_459'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//Oceania
			foreach($v['SIMPLE_QUESTION_445'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			
			$i++;
		}
		//c($userExelAr);
		//c($exExelAr);
		//c($exelAr);
	}
	
	//сортировка по названию компании
	foreach($exelAr as $k=>$v){
		$exelArMod[$k] = array_merge($v, $userExelAr[$k], (array)$exExelAr[0][$k], (array)$exExelAr[5][$k]);
	}

	$data_year=array();
	//Генерируем "определяющий" массив
	foreach($exelArMod as $key=>$arr){
		$data_year[$key]=trim($arr['NAME_COMP']);
	}
	
	$countAar = count($exelArMod);
	
	for($i=0; $i<$countAar; $i++){
		array_multisort($data_year, SORT_STRING, $exelArMod);
	}
	
	//error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');


	require_once 'PHPExcel.php';


	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Vladimir Sinica")->setLastModifiedBy("Vladimir Sinica")->setTitle("Office 2007 XLSX Test Document")->setSubject("Office 2007 XLSX Test Document") ->setDescription("Test document generated list of exhibitors.")->setKeywords("office 2007 openxml php");

	$objPHPExcel->setActiveSheetIndex(0);
	$aSheet = $objPHPExcel->getActiveSheet();
	$aSheet->getColumnDimension('A')->setWidth(50);	
	$aSheet->getColumnDimension('B')->setWidth(50);	
	$aSheet->getColumnDimension('C')->setWidth(50);	
	$aSheet->getColumnDimension('D')->setWidth(35);	
	$aSheet->getColumnDimension('E')->setWidth(35);	
	$aSheet->getColumnDimension('F')->setWidth(35);
	$aSheet->getColumnDimension('G')->setWidth(50);	
	$aSheet->getColumnDimension('H')->setWidth(35);	
	$aSheet->getColumnDimension('I')->setWidth(35);
	$aSheet->getColumnDimension('J')->setWidth(75);
	$aSheet->getColumnDimension('K')->setWidth(50);
	$aSheet->getColumnDimension('L')->setWidth(30);
	$aSheet->getColumnDimension('M')->setWidth(30);
	$aSheet->getColumnDimension('N')->setWidth(20);
	$aSheet->getColumnDimension('O')->setWidth(35);
	$aSheet->getColumnDimension('P')->setWidth(35);
	$aSheet->getColumnDimension('Q')->setWidth(35);
	$aSheet->getColumnDimension('R')->setWidth(35);
	$aSheet->getColumnDimension('S')->setWidth(35);
	$aSheet->getColumnDimension('T')->setWidth(35);
	$aSheet->getColumnDimension('U')->setWidth(35);
	$aSheet->getColumnDimension('V')->setWidth(35);
	$aSheet->getColumnDimension('W')->setWidth(35);
	$aSheet->getColumnDimension('X')->setWidth(35);
	$aSheet->getColumnDimension('Y')->setWidth(35);

	$baseFont = array(
		'font'=>array(
			'name'=>'Arial',
			'size'=>'12',
			'bold'=>false
		)
	);

	$aSheet->setCellValue('A1', 'uID');
	$aSheet->getStyle('A1')->applyFromArray($baseFont);
	$aSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('B1', 'Login');
	$aSheet->getStyle('B1')->applyFromArray($baseFont);
	$aSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C1', 'Password (decoded)');
	$aSheet->getStyle('C1')->applyFromArray($baseFont);
	$aSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D1', 'Company or Hotel name');
	$aSheet->getStyle('D1')->applyFromArray($baseFont);
	$aSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E1', 'Area of business');
	$aSheet->getStyle('E1')->applyFromArray($baseFont);
	$aSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F1', 'Address');
	$aSheet->getStyle('F1')->applyFromArray($baseFont);
	$aSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G1', 'City');
	$aSheet->getStyle('G1')->applyFromArray($baseFont);
	$aSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H1', 'Country');
	$aSheet->getStyle('H1')->applyFromArray($baseFont);
	$aSheet->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I1', "Company's web-site");
	$aSheet->getStyle('I1')->applyFromArray($baseFont);
	$aSheet->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J1', 'Company description');
	$aSheet->getStyle('J1')->applyFromArray($baseFont);
	$aSheet->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K1', "Priority destinations");
	$aSheet->getStyle('K1')->applyFromArray($baseFont);
	$aSheet->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L1', "Participant first name");
	$aSheet->getStyle('L1')->applyFromArray($baseFont);
	$aSheet->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('M1', "Participant last name");
	$aSheet->getStyle('M1')->applyFromArray($baseFont);
	$aSheet->getStyle('M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('N1', "Title (salutation)");
	$aSheet->getStyle('N1')->applyFromArray($baseFont);
	$aSheet->getStyle('N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('O1', "Job Title");
	$aSheet->getStyle('O1')->applyFromArray($baseFont);
	$aSheet->getStyle('O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('P1', "Telephone number");
	$aSheet->getStyle('P1')->applyFromArray($baseFont);
	$aSheet->getStyle('P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('Q1', "Email");
	$aSheet->getStyle('Q1')->applyFromArray($baseFont);
	$aSheet->getStyle('Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('R1', "Alternative email");
	$aSheet->getStyle('R1')->applyFromArray($baseFont);
	$aSheet->getStyle('R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('S1', "Title College (Salutation)");
	$aSheet->getStyle('S1')->applyFromArray($baseFont);
	$aSheet->getStyle('S1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('T1', "First Name College");
	$aSheet->getStyle('T1')->applyFromArray($baseFont);
	$aSheet->getStyle('T1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('U1', "Last Name College");
	$aSheet->getStyle('U1')->applyFromArray($baseFont);
	$aSheet->getStyle('U1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('V1', "Job Title College");
	$aSheet->getStyle('V1')->applyFromArray($baseFont);
	$aSheet->getStyle('V1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('W1', "Email College");
	$aSheet->getStyle('W1')->applyFromArray($baseFont);
	$aSheet->getStyle('W1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('X1', "Table");
	$aSheet->getStyle('X1')->applyFromArray($baseFont);
	$aSheet->getStyle('X1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('Y1', "Hall");
	$aSheet->getStyle('Y1')->applyFromArray($baseFont);
	$aSheet->getStyle('Y1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$str = 1;
	/*foreach($exelAr as $key=>$mas){
		$str++;
		if (array_key_exists($key, $userExelAr)) {
			$aSheet->setCellValue('A'.$str, $userExelAr[$key]['ID']);
			$aSheet->setCellValue('B'.$str, $userExelAr[$key]['LOGIN']);
			$aSheet->setCellValue('C'.$str, $userExelAr[$key]['PASSWORD']);
		}
		$aSheet->setCellValue('D'.$str, $mas['NAME_COMP']);
		$aSheet->setCellValue('E'.$str, $mas['AREA_OF_B']);
		$aSheet->setCellValue('F'.$str, $mas['ADRESS_COMP']);
		$aSheet->getStyle('F'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('G'.$str, $mas['CITY_COMP']);
		$aSheet->setCellValue('H'.$str, $mas['COUNTRY_COMP']);
		$aSheet->setCellValue('I'.$str, $mas['SITE_COMP']);
		//$mas['DESCR_COMP'] = str_replace('вЂ', ' ', $mas['DESCR_COMP']);
		$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESCR_COMP']));
		$aSheet->getStyle('J'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('K'.$str, implode(',', $mas['AREAS_COMP']));
		$aSheet->getStyle('K'.$str)->getAlignment()->setWrapText(1);
		if (array_key_exists($key, $exExelAr[0])) {
			$aSheet->setCellValue('L'.$str, $exExelAr[0][$key]['F_NAME']);
			$aSheet->setCellValue('M'.$str, $exExelAr[0][$key]['L_NAME']);
			$aSheet->setCellValue('N'.$str, $exExelAr[0][$key]['SOLUTION']);
			$aSheet->setCellValue('O'.$str, $exExelAr[0][$key]['JOB']);
			$aSheet->setCellValue('P'.$str, $exExelAr[0][$key]['PHONE']);
			$aSheet->setCellValue('Q'.$str, $exExelAr[0][$key]['MAIL']);
			$aSheet->setCellValue('R'.$str, $exExelAr[0][$key]['ALT_MAIL']);
			$aSheet->setCellValue('X'.$str, $exExelAr[0][$key]['TABLE']);
			$aSheet->setCellValue('Y'.$str, $exExelAr[0][$key]['HALL']);
		}
		if (array_key_exists($key, $exExelAr[5])) {
			$aSheet->setCellValue('S'.$str, $exExelAr[5][$key]['SOLUTION']);
			$aSheet->setCellValue('T'.$str, $exExelAr[5][$key]['F_NAME']);
			$aSheet->setCellValue('U'.$str, $exExelAr[5][$key]['L_NAME']);
			$aSheet->setCellValue('V'.$str, $exExelAr[5][$key]['JOB']);
			$aSheet->setCellValue('W'.$str, $exExelAr[5][$key]['MAIL']);
		}
	}*/
	foreach($exelArMod as $key=>$mas){
		$str++;
		//if (array_key_exists($key, $userExelAr)) {
			$aSheet->setCellValue('A'.$str, $mas['ID']);
			$aSheet->setCellValue('B'.$str, $mas['LOGIN']);
			$aSheet->setCellValue('C'.$str, $mas['PASSWORD']);
		//}
		$aSheet->setCellValue('D'.$str, $mas['NAME_COMP']);
		$aSheet->setCellValue('E'.$str, $mas['AREA_OF_B']);
		$aSheet->setCellValue('F'.$str, $mas['ADRESS_COMP']);
		$aSheet->getStyle('F'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('G'.$str, $mas['CITY_COMP']);
		$aSheet->setCellValue('H'.$str, $mas['COUNTRY_COMP']);
		$aSheet->setCellValue('I'.$str, $mas['SITE_COMP']);
		//$mas['DESCR_COMP'] = str_replace('вЂ', ' ', $mas['DESCR_COMP']);
		$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESCR_COMP']));
		$aSheet->getStyle('J'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('K'.$str, implode(',', $mas['AREAS_COMP']));
		$aSheet->getStyle('K'.$str)->getAlignment()->setWrapText(1);
		//if (array_key_exists($key, $exExelAr[0])) {
			$aSheet->setCellValue('L'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['F_NAME']));
			//$aSheet->setCellValue('L'.$str, $mas['F_NAME']);
			$aSheet->setCellValue('M'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['L_NAME']));
			//$aSheet->setCellValue('M'.$str, $mas['L_NAME']);
			$aSheet->setCellValue('N'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['SOLUTION']));
			//$aSheet->setCellValue('N'.$str, $mas['SOLUTION']);
			$aSheet->setCellValue('O'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['JOB']));
			//$aSheet->setCellValue('O'.$str, $mas['JOB']);
			$aSheet->setCellValue('P'.$str, $mas['PHONE']);
			$aSheet->setCellValue('Q'.$str, $mas['MAIL']);
			$aSheet->setCellValue('R'.$str, $mas['ALT_MAIL']);
			$aSheet->setCellValue('X'.$str, $mas['TABLE']);
			$aSheet->setCellValue('Y'.$str, $mas['HALL']);
		//}
		//if (array_key_exists($key, $exExelAr[5])) {
			$aSheet->setCellValue('S'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['SOLUTION2']));
			//$aSheet->setCellValue('S'.$str, $mas['SOLUTION2']);
			$aSheet->setCellValue('T'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['F_NAME2']));
			//$aSheet->setCellValue('T'.$str, $mas['F_NAME2']);
			$aSheet->setCellValue('U'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['L_NAME2']));
			//$aSheet->setCellValue('U'.$str, $mas['L_NAME2']);
			$aSheet->setCellValue('V'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['JOB2']));
			//$aSheet->setCellValue('V'.$str, $mas['JOB2']);
			$aSheet->setCellValue('W'.$str, $mas['MAIL2']);
		//}
	}

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Participants_excel');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Участники Алма-ата неподтвержденные (не все).xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}elseif($type == 'no_all'){
	if(CModule::IncludeModule("iblock") && CModule::IncludeModule("form")){
		//Все пользователи группы "Участники Алма-ата неподтвержденные (все)"
		$userExelAr = array();
		$exExelAr = array();
		$exelAr = array();
		$filter = Array("GROUPS_ID"   => LuxorConfig::GROUP_USER_ALM_NP); 
		$i = 0;
		$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_ID_COMP", "UF_PAS", 'UF_ID4', 'UF_ID9')));
		while ($arUser = $rsUsers->Fetch()){
			$userExelAr[$i]['ID']       = $arUser['ID'];
			$userExelAr[$i]['LOGIN']    = $arUser['LOGIN'];
			$userExelAr[$i]['PASSWORD'] = LuxorConfig::returnPas($arUser['UF_PAS']);
			
			//Представитель 1
			if($arUser['UF_ID4'] != ''){
				LuxorConfig::getAnswerFormSimple(
					LuxorConfig::ID_E_ALM,  
					$arrAnswersVarnameE, 
					array('RESULT_ID'=>$arUser['UF_ID4'])
				);
				$keys1[] = $i;
			}	
			//Коллега
			if($arUser['UF_ID9'] != ''){
				LuxorConfig::getAnswerFormSimple(
					LuxorConfig::ID_E_ALM, 
					$arrAnswersVarnameE6, 
					array('RESULT_ID'=>$arUser['UF_ID9'])
				);
				$keys6[] = $i;
			}
			//результат формы "Участники данные компании ВСЕ ВЫСТАВКИ"
			if($arUser['UF_ID_COMP'] != ''){
				LuxorConfig::getAnswerFormSimple(
					LuxorConfig::ID_E_FORM,  
					$arrAnswersVarname, 
					array('RESULT_ID'=>$arUser['UF_ID_COMP'])
				);
				$keys[] = $i;
			}
			$i++;
		}
		$j = 0;
		foreach($arrAnswersVarnameE as $ex){
			if(trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'Mr.' || trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'None'){
				$hall = '';
			}else{
				$hall = $ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT'];
			}
			$exExelAr[0][$keys1[$j]]['F_NAME']        = $ex['SIMPLE_QUESTION_948'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['L_NAME']        = $ex['SIMPLE_QUESTION_159'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['SOLUTION']      = $ex['SIMPLE_QUESTION_270'][0]['ANSWER_TEXT'];
			$exExelAr[0][$keys1[$j]]['JOB']           = $ex['SIMPLE_QUESTION_993'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['PHONE']         = $ex['SIMPLE_QUESTION_434'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['MAIL']          = $ex['SIMPLE_QUESTION_742'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['ALT_MAIL']      = $ex['SIMPLE_QUESTION_528'][0]['USER_TEXT'];
			$exExelAr[0][$keys1[$j]]['HALL']          = $hall;
			$exExelAr[0][$keys1[$j]]['TABLE']         = $ex['SIMPLE_QUESTION_778'][0]['USER_TEXT'];
			$j++;
		}
		$j = 0;
		foreach($arrAnswersVarnameE6 as $ex){
			if(trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'Mr.' || trim($ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT']) == 'None'){
				$hall = '';
			}else{
				$hall = $ex['SIMPLE_QUESTION_428'][0]['ANSWER_TEXT'];
			}
			$exExelAr[5][$keys6[$j]]['F_NAME2']        = $ex['SIMPLE_QUESTION_948'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['L_NAME2']        = $ex['SIMPLE_QUESTION_159'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['SOLUTION2']      = $ex['SIMPLE_QUESTION_270'][0]['ANSWER_TEXT'];
			$exExelAr[5][$keys6[$j]]['JOB2']           = $ex['SIMPLE_QUESTION_993'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['PHONE2']         = $ex['SIMPLE_QUESTION_434'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['MAIL2']          = $ex['SIMPLE_QUESTION_742'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['ALT_MAIL2']      = $ex['SIMPLE_QUESTION_528'][0]['USER_TEXT'];
			$exExelAr[5][$keys6[$j]]['HALL2']          = $hall;
			$exExelAr[5][$keys6[$j]]['TABLE2']         = $ex['SIMPLE_QUESTION_778'][0]['USER_TEXT'];
			$j++;
		}
		$i = 0;
		foreach($arrAnswersVarname as $v){
			$exelAr[$i]['NAME_COMP']      = ucfirst($v['SIMPLE_QUESTION_988'][0]['USER_TEXT']);;
			$exelAr[$i]['AREA_OF_B']      = $v['SIMPLE_QUESTION_284'][0]['ANSWER_TEXT'];
			$exelAr[$i]['ADRESS_COMP']    = $v['SIMPLE_QUESTION_295'][0]['USER_TEXT'];
			$exelAr[$i]['CITY_COMP']      = $v['SIMPLE_QUESTION_320'][0]['USER_TEXT'];
			$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_778'][0]['USER_TEXT'];
			$exelAr[$i]['SITE_COMP']      = $v['SIMPLE_QUESTION_501'][0]['USER_TEXT'];
			$exelAr[$i]['DESCR_COMP']     = $v['SIMPLE_QUESTION_163'][0]['USER_TEXT'];
			
			//Europe
			foreach($v['SIMPLE_QUESTION_367'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//North America
			foreach($v['SIMPLE_QUESTION_876'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//South America
			foreach($v['SIMPLE_QUESTION_328'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//Asia
			foreach($v['SIMPLE_QUESTION_931'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//Africa
			foreach($v['SIMPLE_QUESTION_459'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			//Oceania
			foreach($v['SIMPLE_QUESTION_445'] as $ar){
				$exelAr[$i]['AREAS_COMP'][] = $ar['ANSWER_TEXT'];
			}
			
			$i++;
		}
		//c($userExelAr);
		//c($exExelAr);
		//c($exelAr);
	}
	
	//сортировка по названию компании
	foreach($exelAr as $k=>$v){
		$exelArMod[$k] = array_merge($v, $userExelAr[$k], (array)$exExelAr[0][$k], (array)$exExelAr[5][$k]);
	}
	
	$data_year=array();
	//Генерируем "определяющий" массив
	foreach($exelArMod as $key=>$arr){
		$data_year[$key]=$arr['NAME_COMP'];
	}
	
	$countAar = count($exelArMod);
	
	for($i=0; $i<$countAar; $i++){
		array_multisort($data_year, SORT_STRING, $exelArMod);
	}
	
	//массивы готовы для записи
	/** Error reporting */
	//error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');

	/** Include PHPExcel */
	require_once 'PHPExcel.php';


	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Vladimir Sinica")->setLastModifiedBy("Vladimir Sinica")->setTitle("Office 2007 XLSX Test Document")->setSubject("Office 2007 XLSX Test Document") ->setDescription("Test document generated list of exhibitors.")->setKeywords("office 2007 openxml php");

	$objPHPExcel->setActiveSheetIndex(0);
	$aSheet = $objPHPExcel->getActiveSheet();
	$aSheet->getColumnDimension('A')->setWidth(50);	
	$aSheet->getColumnDimension('B')->setWidth(50);	
	$aSheet->getColumnDimension('C')->setWidth(50);	
	$aSheet->getColumnDimension('D')->setWidth(35);	
	$aSheet->getColumnDimension('E')->setWidth(35);	
	$aSheet->getColumnDimension('F')->setWidth(35);
	$aSheet->getColumnDimension('G')->setWidth(50);	
	$aSheet->getColumnDimension('H')->setWidth(35);	
	$aSheet->getColumnDimension('I')->setWidth(35);
	$aSheet->getColumnDimension('J')->setWidth(75);
	$aSheet->getColumnDimension('K')->setWidth(50);
	$aSheet->getColumnDimension('L')->setWidth(30);
	$aSheet->getColumnDimension('M')->setWidth(30);
	$aSheet->getColumnDimension('N')->setWidth(20);
	$aSheet->getColumnDimension('O')->setWidth(35);
	$aSheet->getColumnDimension('P')->setWidth(35);
	$aSheet->getColumnDimension('Q')->setWidth(35);
	$aSheet->getColumnDimension('R')->setWidth(35);
	$aSheet->getColumnDimension('S')->setWidth(35);
	$aSheet->getColumnDimension('T')->setWidth(35);

	$baseFont = array(
		'font'=>array(
			'name'=>'Arial',
			'size'=>'12',
			'bold'=>false
		)
	);
	
	function cellColor($cells,$color){
        global $objPHPExcel;
        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()
        ->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => $color)
        ));
    }


	$aSheet->setCellValue('A1', 'uID');
	$aSheet->getStyle('A1')->applyFromArray($baseFont);
	$aSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('B1', 'Login');
	$aSheet->getStyle('B1')->applyFromArray($baseFont);
	$aSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C1', 'Password (decoded)');
	$aSheet->getStyle('C1')->applyFromArray($baseFont);
	$aSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D1', 'Company or Hotel name');
	$aSheet->getStyle('D1')->applyFromArray($baseFont);
	$aSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E1', 'Area of business');
	$aSheet->getStyle('E1')->applyFromArray($baseFont);
	$aSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F1', 'Address');
	$aSheet->getStyle('F1')->applyFromArray($baseFont);
	$aSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G1', 'City');
	$aSheet->getStyle('G1')->applyFromArray($baseFont);
	$aSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H1', 'Country');
	$aSheet->getStyle('H1')->applyFromArray($baseFont);
	$aSheet->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I1', "Company's web-site");
	$aSheet->getStyle('I1')->applyFromArray($baseFont);
	$aSheet->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J1', 'Company description');
	$aSheet->getStyle('J1')->applyFromArray($baseFont);
	$aSheet->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K1', "Priority destinations");
	$aSheet->getStyle('K1')->applyFromArray($baseFont);
	$aSheet->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L1', "Participant first name");
	$aSheet->getStyle('L1')->applyFromArray($baseFont);
	$aSheet->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('M1', "Participant last name");
	$aSheet->getStyle('M1')->applyFromArray($baseFont);
	$aSheet->getStyle('M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('N1', "Title (salutation)");
	$aSheet->getStyle('N1')->applyFromArray($baseFont);
	$aSheet->getStyle('N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('O1', "Job Title");
	$aSheet->getStyle('O1')->applyFromArray($baseFont);
	$aSheet->getStyle('O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('P1', "Telephone number");
	$aSheet->getStyle('P1')->applyFromArray($baseFont);
	$aSheet->getStyle('P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('Q1', "Email");
	$aSheet->getStyle('Q1')->applyFromArray($baseFont);
	$aSheet->getStyle('Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('R1', "Alternative email");
	$aSheet->getStyle('R1')->applyFromArray($baseFont);
	$aSheet->getStyle('R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('S1', "Table");
	$aSheet->getStyle('S1')->applyFromArray($baseFont);
	$aSheet->getStyle('S1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('T1', "Hall");
	$aSheet->getStyle('T1')->applyFromArray($baseFont);
	$aSheet->getStyle('T1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$aSheet->setCellValue('A2', 'uID');
	$aSheet->getStyle('A2')->applyFromArray($baseFont);
	$aSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('B2', 'Login');
	$aSheet->getStyle('B2')->applyFromArray($baseFont);
	$aSheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C2', 'Password (decoded)');
	$aSheet->getStyle('C2')->applyFromArray($baseFont);
	$aSheet->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D2', 'Company or Hotel name');
	$aSheet->getStyle('D2')->applyFromArray($baseFont);
	$aSheet->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E2', 'Area of business');
	$aSheet->getStyle('E2')->applyFromArray($baseFont);
	$aSheet->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F2', 'Address');
	$aSheet->getStyle('F2')->applyFromArray($baseFont);
	$aSheet->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G2', 'City');
	$aSheet->getStyle('G2')->applyFromArray($baseFont);
	$aSheet->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H2', 'Country');
	$aSheet->getStyle('H2')->applyFromArray($baseFont);
	$aSheet->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I2', "Company's web-site");
	$aSheet->getStyle('I2')->applyFromArray($baseFont);
	$aSheet->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J2', 'Company description');
	$aSheet->getStyle('J2')->applyFromArray($baseFont);
	$aSheet->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K2', "Priority destinations");
	$aSheet->getStyle('K2')->applyFromArray($baseFont);
	$aSheet->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L2', "First Name College");
	$aSheet->getStyle('L2')->applyFromArray($baseFont);
	$aSheet->getStyle('L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('M2', "Last Name College");
	$aSheet->getStyle('M2')->applyFromArray($baseFont);
	$aSheet->getStyle('M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('N2', "Title College (Salutation)");
	$aSheet->getStyle('N2')->applyFromArray($baseFont);
	$aSheet->getStyle('N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('O2', "Job Title College");
	$aSheet->getStyle('O2')->applyFromArray($baseFont);
	$aSheet->getStyle('O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('P2', "Telephone number");
	$aSheet->getStyle('P2')->applyFromArray($baseFont);
	$aSheet->getStyle('P2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('Q2', "Email College");
	$aSheet->getStyle('Q2')->applyFromArray($baseFont);
	$aSheet->getStyle('Q2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('R2', "Alternative email");
	$aSheet->getStyle('R2')->applyFromArray($baseFont);
	$aSheet->getStyle('R2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('S2', "Table");
	$aSheet->getStyle('S2')->applyFromArray($baseFont);
	$aSheet->getStyle('S2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('T2', "Hall");
	$aSheet->getStyle('T2')->applyFromArray($baseFont);
	$aSheet->getStyle('T2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$str = 1;
	$strNext = 2;
	/*foreach($exelAr as $key=>$mas){
		$str += 3;
		$strNext += 3;
		if (array_key_exists($key, $userExelAr)) {
			$aSheet->setCellValue('A'.$str, $userExelAr[$key]['ID']);
			$aSheet->setCellValue('B'.$str, $userExelAr[$key]['LOGIN']);
			$aSheet->setCellValue('C'.$str, $userExelAr[$key]['PASSWORD']);
			cellColor('A'.$str.':C'.$str, 'f3e0bb');
			if($exExelAr[5][$key]['F_NAME'] != '' && $exExelAr[5][$key]['F_NAME'] != 'Participant first name'){
				$aSheet->setCellValue('A'.$strNext, $userExelAr[$key]['ID']);
				$aSheet->setCellValue('B'.$strNext, $userExelAr[$key]['LOGIN']);
				$aSheet->setCellValue('C'.$strNext, $userExelAr[$key]['PASSWORD']);
				cellColor('A'.$strNext.':C'.$strNext, 'c7f5a9');
			}
		}
		$aSheet->setCellValue('D'.$str, $mas['NAME_COMP']);
		$aSheet->setCellValue('E'.$str, $mas['AREA_OF_B']);
		$aSheet->setCellValue('F'.$str, $mas['ADRESS_COMP']);
		$aSheet->getStyle('F'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('G'.$str, $mas['CITY_COMP']);
		$aSheet->setCellValue('H'.$str, $mas['COUNTRY_COMP']);
		$aSheet->setCellValue('I'.$str, $mas['SITE_COMP']);
		//$mas['DESCR_COMP'] = str_replace('вЂ', ' ', $mas['DESCR_COMP']);
		$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESCR_COMP']));
		$aSheet->getStyle('J'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('K'.$str, implode(',', $mas['AREAS_COMP']));
		$aSheet->getStyle('K'.$str)->getAlignment()->setWrapText(1);
		cellColor('D'.$str.':K'.$str, 'f3e0bb');
		if($exExelAr[5][$key]['F_NAME'] != '' && $exExelAr[5][$key]['F_NAME'] != 'Participant first name'){
			$aSheet->setCellValue('D'.$strNext, $mas['NAME_COMP']);
			$aSheet->setCellValue('E'.$strNext, $mas['AREA_OF_B']);
			$aSheet->setCellValue('F'.$strNext, $mas['ADRESS_COMP']);
			$aSheet->getStyle('F'.$str)->getAlignment()->setWrapText(1);
			$aSheet->setCellValue('G'.$strNext, $mas['CITY_COMP']);
			$aSheet->setCellValue('H'.$strNext, $mas['COUNTRY_COMP']);
			$aSheet->setCellValue('I'.$strNext, $mas['SITE_COMP']);
			$mas['DESCR_COMP'] = str_replace('вЂ', ' ', $mas['DESCR_COMP']);
			//$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESCR_COMP']));
			if(preg_match("#[а-яё]+#iu", $mas['DESCR_COMP'])){
				$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESCR_COMP']));
			}else{
				$aSheet->setCellValue('J'.$str, $mas['DESCR_COMP']);
			}
			$aSheet->getStyle('J'.$strNext)->getAlignment()->setWrapText(1);
			$aSheet->setCellValue('K'.$strNext, implode(',', $mas['AREAS_COMP']));
			$aSheet->getStyle('K'.$strNext)->getAlignment()->setWrapText(1);
			cellColor('D'.$strNext.':K'.$strNext, 'c7f5a9');
		}
		if (array_key_exists($key, $exExelAr[0])) {
			$aSheet->setCellValue('L'.$str, $exExelAr[0][$key]['F_NAME']);
			$aSheet->setCellValue('M'.$str, $exExelAr[0][$key]['L_NAME']);
			$aSheet->setCellValue('N'.$str, $exExelAr[0][$key]['SOLUTION']);
			$aSheet->setCellValue('O'.$str, $exExelAr[0][$key]['JOB']);
			$aSheet->setCellValue('P'.$str, $exExelAr[0][$key]['PHONE']);
			$aSheet->setCellValue('Q'.$str, $exExelAr[0][$key]['MAIL']);
			$aSheet->setCellValue('R'.$str, $exExelAr[0][$key]['ALT_MAIL']);
			$aSheet->setCellValue('S'.$str, $exExelAr[0][$key]['TABLE']);
			$aSheet->setCellValue('T'.$str, $exExelAr[0][$key]['HALL']);
			cellColor('L'.$str.':T'.$str, 'f3e0bb');
		}
		if (array_key_exists($key, $exExelAr[5])) {
			if($exExelAr[5][$key]['F_NAME'] != '' && $exExelAr[5][$key]['F_NAME'] != 'Participant first name'){
				$aSheet->setCellValue('L'.$strNext, $exExelAr[5][$key]['F_NAME']);
				$aSheet->setCellValue('M'.$strNext, $exExelAr[5][$key]['L_NAME']);
				$aSheet->setCellValue('N'.$strNext, $exExelAr[5][$key]['SOLUTION']);
				$aSheet->setCellValue('O'.$strNext, $exExelAr[5][$key]['JOB']);
				$aSheet->setCellValue('P'.$strNext, $exExelAr[5][$key]['PHONE']);
				$aSheet->setCellValue('Q'.$strNext, $exExelAr[5][$key]['MAIL']);
				$aSheet->setCellValue('R'.$strNext, $exExelAr[5][$key]['ALT_MAIL']);
				$aSheet->setCellValue('S'.$strNext, $exExelAr[5][$key]['TABLE']);
				$aSheet->setCellValue('T'.$strNext, $exExelAr[5][$key]['HALL']);
				cellColor('L'.$strNext.':T'.$strNext, 'c7f5a9');
			}
		}
	}*/
	foreach($exelArMod as $key=>$mas){
		$str += 3;
		$strNext += 3;
		//if (array_key_exists($key, $userExelAr)) {
			$aSheet->setCellValue('A'.$str, $mas['ID']);
			$aSheet->setCellValue('B'.$str, $mas['LOGIN']);
			$aSheet->setCellValue('C'.$str, $mas['PASSWORD']);
			cellColor('A'.$str.':C'.$str, 'f3e0bb');
			if($mas['F_NAME2'] != '' && $mas['F_NAME2'] != 'Participant first name'){
				$aSheet->setCellValue('A'.$strNext, $mas['ID']);
				$aSheet->setCellValue('B'.$strNext, $mas['LOGIN']);
				$aSheet->setCellValue('C'.$strNext, $mas['PASSWORD']);
				cellColor('A'.$strNext.':C'.$strNext, 'c7f5a9');
			}
		//}
		$aSheet->setCellValue('D'.$str, $mas['NAME_COMP']);
		$aSheet->setCellValue('E'.$str, $mas['AREA_OF_B']);
		$aSheet->setCellValue('F'.$str, $mas['ADRESS_COMP']);
		$aSheet->getStyle('F'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('G'.$str, $mas['CITY_COMP']);
		$aSheet->setCellValue('H'.$str, $mas['COUNTRY_COMP']);
		$aSheet->setCellValue('I'.$str, $mas['SITE_COMP']);
		//$mas['DESCR_COMP'] = str_replace('вЂ', ' ', $mas['DESCR_COMP']);
		$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESCR_COMP']));
		$aSheet->getStyle('J'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('K'.$str, implode(',', $mas['AREAS_COMP']));
		$aSheet->getStyle('K'.$str)->getAlignment()->setWrapText(1);
		cellColor('D'.$str.':K'.$str, 'f3e0bb');
		if($mas['F_NAME2'] != '' && $mas['F_NAME2'] != 'Participant first name'){
			$aSheet->setCellValue('D'.$strNext, $mas['NAME_COMP']);
			$aSheet->setCellValue('E'.$strNext, $mas['AREA_OF_B']);
			$aSheet->setCellValue('F'.$strNext, $mas['ADRESS_COMP']);
			$aSheet->getStyle('F'.$str)->getAlignment()->setWrapText(1);
			$aSheet->setCellValue('G'.$strNext, $mas['CITY_COMP']);
			$aSheet->setCellValue('H'.$strNext, $mas['COUNTRY_COMP']);
			$aSheet->setCellValue('I'.$strNext, $mas['SITE_COMP']);
			$mas['DESCR_COMP'] = str_replace('вЂ', ' ', $mas['DESCR_COMP']);
			//$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESCR_COMP']));
			if(preg_match("#[а-яё]+#iu", $mas['DESCR_COMP'])){
				$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESCR_COMP']));
			}else{
				$aSheet->setCellValue('J'.$str, $mas['DESCR_COMP']);
			}
			$aSheet->getStyle('J'.$strNext)->getAlignment()->setWrapText(1);
			$aSheet->setCellValue('K'.$strNext, implode(',', $mas['AREAS_COMP']));
			$aSheet->getStyle('K'.$strNext)->getAlignment()->setWrapText(1);
			cellColor('D'.$strNext.':K'.$strNext, 'c7f5a9');
		}
		//if (array_key_exists($key, $exExelAr[0])) {
			$aSheet->setCellValue('L'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['F_NAME']));
			//$aSheet->setCellValue('L'.$str, $mas['F_NAME']);
			$aSheet->setCellValue('M'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['L_NAME']));
			//$aSheet->setCellValue('M'.$str, $mas['L_NAME']);
			$aSheet->setCellValue('N'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['SOLUTION']));
			//$aSheet->setCellValue('N'.$str, $mas['SOLUTION']);
			$aSheet->setCellValue('O'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['JOB']));
			//$aSheet->setCellValue('O'.$str, $mas['JOB']);
			$aSheet->setCellValue('P'.$str, $mas['PHONE']);
			$aSheet->setCellValue('Q'.$str, $mas['MAIL']);
			$aSheet->setCellValue('R'.$str, $mas['ALT_MAIL']);
			$aSheet->setCellValue('S'.$str, $mas['TABLE']);
			$aSheet->setCellValue('T'.$str, $mas['HALL']);
			cellColor('L'.$str.':T'.$str, 'f3e0bb');
		//}
		//if (array_key_exists($key, $exExelAr[5])) {
			if($mas['F_NAME2'] != '' && $mas['F_NAME2'] != 'Participant first name'){
				$aSheet->setCellValue('L'.$strNext, iconv('WINDOWS-1251', 'UTF-8', $mas['F_NAME2']));
				//$aSheet->setCellValue('L'.$strNext, $mas['F_NAME2']);
				$aSheet->setCellValue('M'.$strNext, iconv('WINDOWS-1251', 'UTF-8', $mas['L_NAME2']));
				//$aSheet->setCellValue('M'.$strNext, $mas['L_NAME2']);
				$aSheet->setCellValue('N'.$strNext, iconv('WINDOWS-1251', 'UTF-8', $mas['SOLUTION2']));
				//$aSheet->setCellValue('N'.$strNext, $mas['SOLUTION2']);
				$aSheet->setCellValue('O'.$strNext, iconv('WINDOWS-1251', 'UTF-8', $mas['JOB2']));
				//$aSheet->setCellValue('O'.$strNext, $mas['JOB2']);
				$aSheet->setCellValue('P'.$strNext, $mas['PHONE2']);
				$aSheet->setCellValue('Q'.$strNext, $mas['MAIL2']);
				$aSheet->setCellValue('R'.$strNext, $mas['ALT_MAIL2']);
				$aSheet->setCellValue('S'.$strNext, $mas['TABLE2']);
				$aSheet->setCellValue('T'.$strNext, $mas['HALL2']);
				cellColor('L'.$strNext.':T'.$strNext, 'c7f5a9');
			}
		//}
	}

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Participants_excel_all');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Участники Алма-ата неподтвержденные (все).xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}else{
	echo 'Oops, we are not found this type.';
}
?>