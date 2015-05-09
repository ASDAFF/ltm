<?
$type = strip_tags($_REQUEST['type']);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
if($type == 'guests'){
	if(CModule::IncludeModule("iblock") && CModule::IncludeModule("form")){
		//Все пользователи группы "Гости Алматы подтвержденные" на утро
		$userExelAr = array();
		$exExelAr = array();
		$exelAr = array();
		$filter = Array("GROUPS_ID"   => LuxorConfig::GROUP_GUEST_A, 'UF_MR'=>'1'); 
		$i = 0;
		$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_ID_COMP", "UF_PAS", 'UF_ID', 'UF_ID4', 'UF_MR')));
		while ($arUser = $rsUsers->Fetch()){
			$exelAr[$i]['ID']       = $arUser['ID'];
			$exelAr[$i]['LOGIN']    = $arUser['LOGIN'];
			$exelAr[$i]['PASSWORD'] = LuxorConfig::returnPas($arUser['UF_PAS']);
			$exelAr[$i]['RESULT'] = $arUser['UF_ID_COMP'];
			
			//результат формы "Участники данные компании ВСЕ ВЫСТАВКИ"
			if($arUser['UF_ID_COMP'] != ''){
				LuxorConfig::getAnswerFormSimple(
					10, //!!!!!!!!!!!!! По идее тут id формы на город, но сейчас все в форме для Москвы
					$arrAnswersVarname, 
					array('RESULT_ID'=>$arUser['UF_ID_COMP'])
				);
				$keys[] = $i;
			}
			$i++;
			
		}
		$i = 0;
		foreach($arrAnswersVarname as $v){
			$exelAr[$i]['F_NAME'] = $v['SIMPLE_QUESTION_750'][0]['USER_TEXT'].' '.$v['SIMPLE_QUESTION_823'][0]['USER_TEXT'];
			$exelAr[$i]['JOB'] = $v['SIMPLE_QUESTION_391'][0]['USER_TEXT'];
			$exelAr[$i]['PHONE'] = $v['SIMPLE_QUESTION_636'][0]['USER_TEXT'];
			$exelAr[$i]['MAIL'] = $v['SIMPLE_QUESTION_373'][0]['USER_TEXT'];
			$exelAr[$i]['SITE'] = $v['SIMPLE_QUESTION_552'][0]['USER_TEXT'];
			$exelAr[$i]['NAME_COMP']      = $v['SIMPLE_QUESTION_115'][0]['USER_TEXT'];
			$exelAr[$i]['AREA_OF_B']      = $v['SIMPLE_QUESTION_677'][0]['ANSWER_TEXT'];
			$exelAr[$i]['ADRESS_COMP']    = $v['SIMPLE_QUESTION_773'][0]['USER_TEXT'];
			$exelAr[$i]['INDEX']          = $v['SIMPLE_QUESTION_756'][0]['USER_TEXT'];
			$exelAr[$i]['CITY_COMP']      = $v['SIMPLE_QUESTION_672'][0]['USER_TEXT'];
			$exelAr[$i]['DESC_COMP']      = $v['SIMPLE_QUESTION_166'][0]['USER_TEXT'];
			if($v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'] == 'other'){
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_243'][0]['USER_TEXT'];	
			}else{
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'];	
			}
			

			//Вид деятельности
			foreach($v['SIMPLE_QUESTION_677'] as $ar){
				$exelAr[$i]['VID_D'][] = $ar['ANSWER_TEXT'];
			}
			
			//коллега
			$exelAr[$i]['COLLEGA_NAME']     = $v['SIMPLE_QUESTION_816'][0]['USER_TEXT'];
			$exelAr[$i]['COLLEGA_L_NAME']   = $v['SIMPLE_QUESTION_596'][0]['USER_TEXT'];
			$exelAr[$i]['COLLEGA_JOB']      = $v['SIMPLE_QUESTION_304'][0]['USER_TEXT'];
			$exelAr[$i]['COLLEGA_MAIL']     = $v['SIMPLE_QUESTION_278'][0]['USER_TEXT'];
			
			//Приоритетные направления
			//Europe
			foreach($v['SIMPLE_QUESTION_244'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//North America
			foreach($v['SIMPLE_QUESTION_383'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//South America
			foreach($v['SIMPLE_QUESTION_212'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Asia
			foreach($v['SIMPLE_QUESTION_526'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Africa
			foreach($v['SIMPLE_QUESTION_497'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Oceania
			foreach($v['SIMPLE_QUESTION_878'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			
			$exelAr[$i]['PR_NAPR_ALL'] = implode(',', $exelAr[$i]['PR_NAPR']);
			
			$i++;
		}
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
	$aSheet->getColumnDimension('A')->setWidth(35);	
	$aSheet->getColumnDimension('B')->setWidth(35);	
	$aSheet->getColumnDimension('C')->setWidth(35);	
	$aSheet->getColumnDimension('D')->setWidth(35);	
	$aSheet->getColumnDimension('E')->setWidth(50);	
	$aSheet->getColumnDimension('F')->setWidth(50);
	$aSheet->getColumnDimension('G')->setWidth(35);	
	$aSheet->getColumnDimension('H')->setWidth(35);	
	$aSheet->getColumnDimension('I')->setWidth(35);
	$aSheet->getColumnDimension('J')->setWidth(35);
	$aSheet->getColumnDimension('K')->setWidth(50);
	$aSheet->getColumnDimension('L')->setWidth(30);
	$aSheet->getColumnDimension('M')->setWidth(50);
	$aSheet->getColumnDimension('N')->setWidth(30);
	$aSheet->getColumnDimension('O')->setWidth(60);
	$aSheet->getColumnDimension('P')->setWidth(60);
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

	$aSheet->setCellValue('A1', 'uID');
	$aSheet->getStyle('A1')->applyFromArray($baseFont);
	$aSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('B1', iconv('WINDOWS-1251', 'UTF-8', 'Логин'));
	$aSheet->getStyle('B1')->applyFromArray($baseFont);
	$aSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C1', iconv('WINDOWS-1251', 'UTF-8', 'Пароль'));
	$aSheet->getStyle('C1')->applyFromArray($baseFont);
	$aSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D1', iconv('WINDOWS-1251', 'UTF-8', 'Компания'));
	$aSheet->getStyle('D1')->applyFromArray($baseFont);
	$aSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E1', iconv('WINDOWS-1251', 'UTF-8', 'Вид деятельности'));
	$aSheet->getStyle('E1')->applyFromArray($baseFont);
	$aSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F1', iconv('WINDOWS-1251', 'UTF-8', 'Адрес'));
	$aSheet->getStyle('F1')->applyFromArray($baseFont);
	$aSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G1', iconv('WINDOWS-1251', 'UTF-8', 'Индекс'));
	$aSheet->getStyle('G1')->applyFromArray($baseFont);
	$aSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H1', iconv('WINDOWS-1251', 'UTF-8', 'Город'));
	$aSheet->getStyle('H1')->applyFromArray($baseFont);
	$aSheet->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I1', iconv('WINDOWS-1251', 'UTF-8', "Страна"));
	$aSheet->getStyle('I1')->applyFromArray($baseFont);
	$aSheet->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J1', iconv('WINDOWS-1251', 'UTF-8', 'Имя Фамилия'));
	$aSheet->getStyle('J1')->applyFromArray($baseFont);
	$aSheet->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K1', iconv('WINDOWS-1251', 'UTF-8', "Должность"));
	$aSheet->getStyle('K1')->applyFromArray($baseFont);
	$aSheet->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L1', iconv('WINDOWS-1251', 'UTF-8', "Телефон"));
	$aSheet->getStyle('L1')->applyFromArray($baseFont);
	$aSheet->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('M1', iconv('WINDOWS-1251', 'UTF-8', "E-mail"));
	$aSheet->getStyle('M1')->applyFromArray($baseFont);
	$aSheet->getStyle('M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('N1', iconv('WINDOWS-1251', 'UTF-8', "Web-site компании"));
	$aSheet->getStyle('N1')->applyFromArray($baseFont);
	$aSheet->getStyle('N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('O1', iconv('WINDOWS-1251', 'UTF-8', "Приоритетные направления"));
	$aSheet->getStyle('O1')->applyFromArray($baseFont);
	$aSheet->getStyle('O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('P1', iconv('WINDOWS-1251', 'UTF-8', "Описание компании"));
	$aSheet->getStyle('P1')->applyFromArray($baseFont);
	$aSheet->getStyle('P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('Q1', iconv('WINDOWS-1251', 'UTF-8', "Имя коллеги (на утро)"));
	$aSheet->getStyle('Q1')->applyFromArray($baseFont);
	$aSheet->getStyle('Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('R1', iconv('WINDOWS-1251', 'UTF-8', "Фамилия коллеги (на утро)"));
	$aSheet->getStyle('R1')->applyFromArray($baseFont);
	$aSheet->getStyle('R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('S1', iconv('WINDOWS-1251', 'UTF-8', "Должность коллеги (на утро)"));
	$aSheet->getStyle('S1')->applyFromArray($baseFont);
	$aSheet->getStyle('S1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('T1', iconv('WINDOWS-1251', 'UTF-8', "E-mail коллеги (на утро)"));
	$aSheet->getStyle('T1')->applyFromArray($baseFont);
	$aSheet->getStyle('T1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$str = 1;
	foreach($exelAr as $key=>$mas){
		$str++;
		$aSheet->setCellValue('A'.$str, $mas['ID']);
		$aSheet->setCellValue('B'.$str, $mas['LOGIN']);
		$aSheet->setCellValue('C'.$str, $mas['PASSWORD']);
		$aSheet->setCellValue('D'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['NAME_COMP']));
		if(count($mas['VID_D']) > 0){
			$aSheet->setCellValue('E'.$str, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
		}else{
			$aSheet->setCellValue('E'.$str, '');
		}
		$aSheet->setCellValue('F'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['ADRESS_COMP']));
		$aSheet->setCellValue('G'.$str, $mas['INDEX']);
		$aSheet->setCellValue('H'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['CITY_COMP']));
		$aSheet->setCellValue('I'.$str, $mas['COUNTRY_COMP']);
		$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['F_NAME']));
		$aSheet->setCellValue('K'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['JOB']));
		$aSheet->setCellValue('L'.$str, $mas['PHONE']);
		$aSheet->setCellValue('M'.$str, $mas['MAIL']);
		$aSheet->setCellValue('N'.$str, $mas['SITE']);
		$aSheet->setCellValue('O'.$str, $mas['PR_NAPR_ALL']);
		$aSheet->getStyle('O'.$str)->getAlignment()->setWrapText(1);
		$mas['DESC_COMP'] = str_replace('', ' ', $mas['DESC_COMP']);// крокозябра
		$aSheet->setCellValue('P'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESC_COMP']));
		$aSheet->getStyle('P'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('Q'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_NAME']));
		$aSheet->setCellValue('R'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_L_NAME']));
		$aSheet->setCellValue('S'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_JOB']));
		$aSheet->setCellValue('T'.$str, $mas['COLLEGA_MAIL']);
	}

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Guest_excel');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Гости Алматы на утро.xls"');
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
}elseif($type == 'guests_all'){
	if(CModule::IncludeModule("iblock") && CModule::IncludeModule("form")){
		//Все пользователи группы "Гости Алматы подтвержденные" на утро
		$userExelAr = array();
		$exExelAr = array();
		$exelAr = array();
		$filter = Array("GROUPS_ID"   => LuxorConfig::GROUP_GUEST_A, 'UF_MR'=>'1'); 
		$i = 0;
		$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_ID_COMP", "UF_PAS", 'UF_ID', 'UF_ID4', 'UF_MR')));
		while ($arUser = $rsUsers->Fetch()){
			$exelAr[$i]['ID']       = $arUser['ID'];
			$exelAr[$i]['LOGIN']    = $arUser['LOGIN'];
			$exelAr[$i]['PASSWORD'] = LuxorConfig::returnPas($arUser['UF_PAS']);
			
			//результат формы "Участники данные компании ВСЕ ВЫСТАВКИ"
			if($arUser['UF_ID_COMP'] != ''){
				LuxorConfig::getAnswerFormSimple(
					10,
					$arrAnswersVarname, 
					array('RESULT_ID'=>$arUser['UF_ID_COMP'])
				);
				$keys[] = $i;
			}
			$i++;
			
		}
		$i = 0;
		foreach($arrAnswersVarname as $v){
			$exelAr[$i]['F_NAME'] = $v['SIMPLE_QUESTION_750'][0]['USER_TEXT'].' '.$v['SIMPLE_QUESTION_823'][0]['USER_TEXT'];
			$exelAr[$i]['JOB'] = $v['SIMPLE_QUESTION_391'][0]['USER_TEXT'];
			$exelAr[$i]['PHONE'] = $v['SIMPLE_QUESTION_636'][0]['USER_TEXT'];
			$exelAr[$i]['MAIL'] = $v['SIMPLE_QUESTION_373'][0]['USER_TEXT'];
			$exelAr[$i]['SITE'] = $v['SIMPLE_QUESTION_552'][0]['USER_TEXT'];
			$exelAr[$i]['NAME_COMP']      = $v['SIMPLE_QUESTION_115'][0]['USER_TEXT'];
			$exelAr[$i]['AREA_OF_B']      = $v['SIMPLE_QUESTION_677'][0]['ANSWER_TEXT'];
			$exelAr[$i]['ADRESS_COMP']    = $v['SIMPLE_QUESTION_773'][0]['USER_TEXT'];
			$exelAr[$i]['INDEX']          = $v['SIMPLE_QUESTION_756'][0]['USER_TEXT'];
			$exelAr[$i]['CITY_COMP']      = $v['SIMPLE_QUESTION_672'][0]['USER_TEXT'];
			$exelAr[$i]['DESC_COMP']      = $v['SIMPLE_QUESTION_166'][0]['USER_TEXT'];
			if($v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'] == 'other'){
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_243'][0]['USER_TEXT'];	
			}else{
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'];	
			}
			

			//Вид деятельности
			foreach($v['SIMPLE_QUESTION_677'] as $ar){
				$exelAr[$i]['VID_D'][] = $ar['ANSWER_TEXT'];
			}
			
			//коллега
			$exelAr[$i]['COLLEGA_NAME']     = $v['SIMPLE_QUESTION_816'][0]['USER_TEXT'];
			//$exelAr[$i]['COLLEGA_L_NAME']   = $v['SIMPLE_QUESTION_596'][0]['USER_TEXT'];
			$exelAr[$i]['COLLEGA_F_NAME']   = $v['SIMPLE_QUESTION_816'][0]['USER_TEXT'].' '.$v['SIMPLE_QUESTION_596'][0]['USER_TEXT'];
			$exelAr[$i]['COLLEGA_JOB']      = $v['SIMPLE_QUESTION_304'][0]['USER_TEXT'];
			$exelAr[$i]['COLLEGA_MAIL']     = $v['SIMPLE_QUESTION_278'][0]['USER_TEXT'];
			
			//Приоритетные направления
			//Europe
			foreach($v['SIMPLE_QUESTION_244'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//North America
			foreach($v['SIMPLE_QUESTION_383'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//South America
			foreach($v['SIMPLE_QUESTION_212'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Asia
			foreach($v['SIMPLE_QUESTION_526'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Africa
			foreach($v['SIMPLE_QUESTION_497'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Oceania
			foreach($v['SIMPLE_QUESTION_878'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			
			$exelAr[$i]['PR_NAPR_ALL'] = implode(',', $exelAr[$i]['PR_NAPR']);
			
			$i++;
		}
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
	$aSheet->getColumnDimension('A')->setWidth(35);	
	$aSheet->getColumnDimension('B')->setWidth(35);	
	$aSheet->getColumnDimension('C')->setWidth(35);	
	$aSheet->getColumnDimension('D')->setWidth(35);	
	$aSheet->getColumnDimension('E')->setWidth(50);	
	$aSheet->getColumnDimension('F')->setWidth(50);
	$aSheet->getColumnDimension('G')->setWidth(35);	
	$aSheet->getColumnDimension('H')->setWidth(35);	
	$aSheet->getColumnDimension('I')->setWidth(60);
	$aSheet->getColumnDimension('J')->setWidth(60);
	$aSheet->getColumnDimension('K')->setWidth(50);
	$aSheet->getColumnDimension('L')->setWidth(30);
	$aSheet->getColumnDimension('M')->setWidth(50);
	$aSheet->getColumnDimension('N')->setWidth(30);
	$aSheet->getColumnDimension('O')->setWidth(60);
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

	$aSheet->setCellValue('A1', 'uID');
	$aSheet->getStyle('A1')->applyFromArray($baseFont);
	$aSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('B1', iconv('WINDOWS-1251', 'UTF-8', 'Логин'));
	$aSheet->getStyle('B1')->applyFromArray($baseFont);
	$aSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C1', iconv('WINDOWS-1251', 'UTF-8', 'Пароль'));
	$aSheet->getStyle('C1')->applyFromArray($baseFont);
	$aSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D1', iconv('WINDOWS-1251', 'UTF-8', 'Компания'));
	$aSheet->getStyle('D1')->applyFromArray($baseFont);
	$aSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E1', iconv('WINDOWS-1251', 'UTF-8', 'Вид деятельности'));
	$aSheet->getStyle('E1')->applyFromArray($baseFont);
	$aSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F1', iconv('WINDOWS-1251', 'UTF-8', 'Адрес'));
	$aSheet->getStyle('F1')->applyFromArray($baseFont);
	$aSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G1', iconv('WINDOWS-1251', 'UTF-8', 'Индекс'));
	$aSheet->getStyle('G1')->applyFromArray($baseFont);
	$aSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H1', iconv('WINDOWS-1251', 'UTF-8', 'Город'));
	$aSheet->getStyle('H1')->applyFromArray($baseFont);
	$aSheet->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I1', iconv('WINDOWS-1251', 'UTF-8', "Страна"));
	$aSheet->getStyle('I1')->applyFromArray($baseFont);
	$aSheet->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J1', iconv('WINDOWS-1251', 'UTF-8', 'Описание компании'));
	$aSheet->getStyle('J1')->applyFromArray($baseFont);
	$aSheet->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K1', iconv('WINDOWS-1251', 'UTF-8', 'Приоритетные направление'));
	$aSheet->getStyle('K1')->applyFromArray($baseFont);
	$aSheet->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L1', iconv('WINDOWS-1251', 'UTF-8', 'Web-site компании'));
	$aSheet->getStyle('L1')->applyFromArray($baseFont);
	$aSheet->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('M1', iconv('WINDOWS-1251', 'UTF-8', 'Имя Фамилия'));
	$aSheet->getStyle('M1')->applyFromArray($baseFont);
	$aSheet->getStyle('M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('N1', iconv('WINDOWS-1251', 'UTF-8', "Должность"));
	$aSheet->getStyle('N1')->applyFromArray($baseFont);
	$aSheet->getStyle('N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('O1', iconv('WINDOWS-1251', 'UTF-8', "Телефон"));
	$aSheet->getStyle('O1')->applyFromArray($baseFont);
	$aSheet->getStyle('O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('P1', iconv('WINDOWS-1251', 'UTF-8', "E-mail"));
	$aSheet->getStyle('P1')->applyFromArray($baseFont);
	$aSheet->getStyle('P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$aSheet->setCellValue('A2', 'uID');
	$aSheet->getStyle('A2')->applyFromArray($baseFont);
	$aSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('B2', iconv('WINDOWS-1251', 'UTF-8', 'Логин'));
	$aSheet->getStyle('B2')->applyFromArray($baseFont);
	$aSheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C2', iconv('WINDOWS-1251', 'UTF-8', 'Пароль'));
	$aSheet->getStyle('C2')->applyFromArray($baseFont);
	$aSheet->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D2', iconv('WINDOWS-1251', 'UTF-8', 'Компания'));
	$aSheet->getStyle('D2')->applyFromArray($baseFont);
	$aSheet->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E2', iconv('WINDOWS-1251', 'UTF-8', 'Вид деятельности'));
	$aSheet->getStyle('E2')->applyFromArray($baseFont);
	$aSheet->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F2', iconv('WINDOWS-1251', 'UTF-8', 'Адрес'));
	$aSheet->getStyle('F2')->applyFromArray($baseFont);
	$aSheet->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G2', iconv('WINDOWS-1251', 'UTF-8', 'Индекс'));
	$aSheet->getStyle('G2')->applyFromArray($baseFont);
	$aSheet->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H2', iconv('WINDOWS-1251', 'UTF-8', 'Город'));
	$aSheet->getStyle('H2')->applyFromArray($baseFont);
	$aSheet->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I2', iconv('WINDOWS-1251', 'UTF-8', "Страна"));
	$aSheet->getStyle('I2')->applyFromArray($baseFont);
	$aSheet->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J2', iconv('WINDOWS-1251', 'UTF-8', 'Описание компании'));
	$aSheet->getStyle('J2')->applyFromArray($baseFont);
	$aSheet->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K2', iconv('WINDOWS-1251', 'UTF-8', 'Приоритетные направление'));
	$aSheet->getStyle('K2')->applyFromArray($baseFont);
	$aSheet->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L2', iconv('WINDOWS-1251', 'UTF-8', 'Web-site компании'));
	$aSheet->getStyle('L2')->applyFromArray($baseFont);
	$aSheet->getStyle('L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('M2', iconv('WINDOWS-1251', 'UTF-8', 'Имя Фамилия (коллега)'));
	$aSheet->getStyle('M2')->applyFromArray($baseFont);
	$aSheet->getStyle('M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('N2', iconv('WINDOWS-1251', 'UTF-8', "Должность коллеги (на утро)"));
	$aSheet->getStyle('N2')->applyFromArray($baseFont);
	$aSheet->getStyle('N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('O2', iconv('WINDOWS-1251', 'UTF-8', "Телефон (дублируем)"));
	$aSheet->getStyle('O2')->applyFromArray($baseFont);
	$aSheet->getStyle('O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('P2', iconv('WINDOWS-1251', 'UTF-8', "E-mail коллеги (на утро)"));
	$aSheet->getStyle('P2')->applyFromArray($baseFont);
	$aSheet->getStyle('P2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$str = 1;
	$str2 = 2;
	foreach($exelAr as $key=>$mas){
		$str +=3;
		$str2 +=3;
		$aSheet->setCellValue('A'.$str, $mas['ID']);
		$aSheet->setCellValue('B'.$str, $mas['LOGIN']);
		$aSheet->setCellValue('C'.$str, $mas['PASSWORD']);
		$aSheet->setCellValue('D'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['NAME_COMP']));
		if(count($mas['VID_D']) > 0){
			$aSheet->setCellValue('E'.$str, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
		}else{
			$aSheet->setCellValue('E'.$str, '');
		}
		$aSheet->setCellValue('F'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['ADRESS_COMP']));
		$aSheet->setCellValue('G'.$str, $mas['INDEX']);
		$aSheet->setCellValue('H'.$str, $mas['CITY_COMP']);
		$aSheet->setCellValue('I'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COUNTRY_COMP']));
		$mas['DESC_COMP'] = str_replace(' ', ' ', $mas['DESC_COMP']);//крокозябра
		$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESC_COMP']));
		$aSheet->getStyle('J'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('K'.$str, $mas['PR_NAPR_ALL']);
		$aSheet->getStyle('K'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('L'.$str, $mas['SITE']);
		$aSheet->setCellValue('M'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['F_NAME']));
		$aSheet->setCellValue('N'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['JOB']));
		$aSheet->setCellValue('O'.$str, $mas['PHONE']);
		$aSheet->setCellValue('P'.$str, $mas['MAIL']);
		if($mas['COLLEGA_NAME'] != '' && iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_NAME']) != ''){
			$aSheet->setCellValue('A'.$str2, $mas['ID']);
			$aSheet->setCellValue('B'.$str2, $mas['LOGIN']);
			$aSheet->setCellValue('C'.$str2, $mas['PASSWORD']);
			$aSheet->setCellValue('D'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['NAME_COMP']));
			if(count($mas['VID_D']) > 0){
				$aSheet->setCellValue('E'.$str2, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
			}else{
				$aSheet->setCellValue('E'.$str2, '');
			}
			$aSheet->setCellValue('F'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['ADRESS_COMP']));
			$aSheet->setCellValue('G'.$str2, $mas['INDEX']);
			$aSheet->setCellValue('H'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['CITY_COMP']));
			$aSheet->setCellValue('I'.$str2, $mas['COUNTRY_COMP']);
			$mas['DESC_COMP'] = str_replace(' ', ' ', $mas['DESC_COMP']);//крокозябра
			$aSheet->setCellValue('J'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['DESC_COMP']));
			$aSheet->getStyle('J'.$str2)->getAlignment()->setWrapText(1);
			$aSheet->setCellValue('K'.$str2, $mas['PR_NAPR_ALL']);
			$aSheet->getStyle('K'.$str2)->getAlignment()->setWrapText(1);
			$aSheet->setCellValue('L'.$str2, $mas['SITE']);
			$aSheet->setCellValue('M'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_F_NAME']));
			$aSheet->setCellValue('N'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_JOB']));
			$aSheet->setCellValue('O'.$str2, $mas['PHONE']);
			$aSheet->setCellValue('P'.$str2, $mas['COLLEGA_MAIL']);
		}
	}

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Participants_excel');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Гости Алматы на утро (все люди).xls"');
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
}elseif($type == 'guests_hb'){
	if(CModule::IncludeModule("iblock") && CModule::IncludeModule("form")){
		//Все пользователи группы "Гости Алматы подтвержденные" на утро
		$userExelAr = array();
		$exExelAr = array();
		$exelAr = array();
		$filter = Array("GROUPS_ID"   => LuxorConfig::GROUP_GUEST_A, 'UF_HB'=>'1'); 
		$i = 0;
		$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_ID_COMP", "UF_PAS", 'UF_ID', 'UF_ID4', 'UF_HB')));
		while ($arUser = $rsUsers->Fetch()){
			$exelAr[$i]['ID']       = $arUser['ID'];
			$exelAr[$i]['LOGIN']    = $arUser['LOGIN'];
			$exelAr[$i]['PASSWORD'] = LuxorConfig::returnPas($arUser['UF_PAS']);
			
			//результат формы "Участники данные компании ВСЕ ВЫСТАВКИ"
			if($arUser['UF_ID_COMP'] != ''){
				LuxorConfig::getAnswerFormSimple(
					10, 
					$arrAnswersVarname, 
					array('RESULT_ID'=>$arUser['UF_ID_COMP'])
				);
				$keys[] = $i;
			}
			$i++;
			
		}
		
		$i = 0;
		foreach($arrAnswersVarname as $v){
			$exelAr[$i]['F_NAME'] = $v['SIMPLE_QUESTION_750'][0]['USER_TEXT'].' '.$v['SIMPLE_QUESTION_823'][0]['USER_TEXT'];
			$exelAr[$i]['JOB'] = $v['SIMPLE_QUESTION_391'][0]['USER_TEXT'];
			$exelAr[$i]['PHONE'] = $v['SIMPLE_QUESTION_636'][0]['USER_TEXT'];
			$exelAr[$i]['MAIL'] = $v['SIMPLE_QUESTION_373'][0]['USER_TEXT'];
			$exelAr[$i]['SITE'] = $v['SIMPLE_QUESTION_552'][0]['USER_TEXT'];
			$exelAr[$i]['NAME_COMP']      = $v['SIMPLE_QUESTION_115'][0]['USER_TEXT'];
			$exelAr[$i]['AREA_OF_B']      = $v['SIMPLE_QUESTION_677'][0]['ANSWER_TEXT'];
			$exelAr[$i]['ADRESS_COMP']    = $v['SIMPLE_QUESTION_773'][0]['USER_TEXT'];
			$exelAr[$i]['INDEX']          = $v['SIMPLE_QUESTION_756'][0]['USER_TEXT'];
			$exelAr[$i]['CITY_COMP']      = $v['SIMPLE_QUESTION_672'][0]['USER_TEXT'];
			$exelAr[$i]['DESC_COMP']      = $v['SIMPLE_QUESTION_166'][0]['USER_TEXT'];
			if($v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'] == 'other'){
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_243'][0]['USER_TEXT'];	
			}else{
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'];	
			}
			

			//Вид деятельности
			foreach($v['SIMPLE_QUESTION_677'] as $ar){
				$exelAr[$i]['VID_D'][] = $ar['ANSWER_TEXT'];
			}
			
			//коллега
			$exelAr[$i]['COLLEGA_NAME']     = $v['SIMPLE_QUESTION_816'][0]['USER_TEXT'];
			$exelAr[$i]['COLLEGA_L_NAME']   = $v['SIMPLE_QUESTION_596'][0]['USER_TEXT'];
			$exelAr[$i]['COLLEGA_JOB']      = $v['SIMPLE_QUESTION_304'][0]['USER_TEXT'];
			$exelAr[$i]['COLLEGA_MAIL']     = $v['SIMPLE_QUESTION_278'][0]['USER_TEXT'];
			
			//Приоритетные направления
			//Europe
			foreach($v['SIMPLE_QUESTION_244'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//North America
			foreach($v['SIMPLE_QUESTION_383'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//South America
			foreach($v['SIMPLE_QUESTION_212'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Asia
			foreach($v['SIMPLE_QUESTION_526'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Africa
			foreach($v['SIMPLE_QUESTION_497'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Oceania
			foreach($v['SIMPLE_QUESTION_878'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			
			$exelAr[$i]['PR_NAPR_ALL'] = implode(',', $exelAr[$i]['PR_NAPR']);
			
			$i++;
		}
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
	$aSheet->getColumnDimension('A')->setWidth(35);	
	$aSheet->getColumnDimension('B')->setWidth(35);	
	$aSheet->getColumnDimension('C')->setWidth(35);	
	$aSheet->getColumnDimension('D')->setWidth(35);	
	$aSheet->getColumnDimension('E')->setWidth(50);	
	$aSheet->getColumnDimension('F')->setWidth(50);
	$aSheet->getColumnDimension('G')->setWidth(35);	
	$aSheet->getColumnDimension('H')->setWidth(35);	
	$aSheet->getColumnDimension('I')->setWidth(35);
	$aSheet->getColumnDimension('J')->setWidth(35);
	$aSheet->getColumnDimension('K')->setWidth(50);
	$aSheet->getColumnDimension('L')->setWidth(30);
	$aSheet->getColumnDimension('M')->setWidth(50);
	$aSheet->getColumnDimension('N')->setWidth(30);
	$aSheet->getColumnDimension('O')->setWidth(60);
	$aSheet->getColumnDimension('P')->setWidth(60);
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

	$aSheet->setCellValue('A1', 'uID');
	$aSheet->getStyle('A1')->applyFromArray($baseFont);
	$aSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('B1', iconv('WINDOWS-1251', 'UTF-8', 'Логин'));
	$aSheet->getStyle('B1')->applyFromArray($baseFont);
	$aSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C1', iconv('WINDOWS-1251', 'UTF-8', 'Пароль'));
	$aSheet->getStyle('C1')->applyFromArray($baseFont);
	$aSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D1', iconv('WINDOWS-1251', 'UTF-8', 'Компания'));
	$aSheet->getStyle('D1')->applyFromArray($baseFont);
	$aSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E1', iconv('WINDOWS-1251', 'UTF-8', 'Вид деятельности'));
	$aSheet->getStyle('E1')->applyFromArray($baseFont);
	$aSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F1', iconv('WINDOWS-1251', 'UTF-8', 'Адрес'));
	$aSheet->getStyle('F1')->applyFromArray($baseFont);
	$aSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G1', iconv('WINDOWS-1251', 'UTF-8', 'Индекс'));
	$aSheet->getStyle('G1')->applyFromArray($baseFont);
	$aSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H1', iconv('WINDOWS-1251', 'UTF-8', 'Город'));
	$aSheet->getStyle('H1')->applyFromArray($baseFont);
	$aSheet->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I1', iconv('WINDOWS-1251', 'UTF-8', "Страна"));
	$aSheet->getStyle('I1')->applyFromArray($baseFont);
	$aSheet->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J1', iconv('WINDOWS-1251', 'UTF-8', 'Имя Фамилия'));
	$aSheet->getStyle('J1')->applyFromArray($baseFont);
	$aSheet->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K1', iconv('WINDOWS-1251', 'UTF-8', "Должность"));
	$aSheet->getStyle('K1')->applyFromArray($baseFont);
	$aSheet->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L1', iconv('WINDOWS-1251', 'UTF-8', "Телефон"));
	$aSheet->getStyle('L1')->applyFromArray($baseFont);
	$aSheet->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('M1', iconv('WINDOWS-1251', 'UTF-8', "E-mail"));
	$aSheet->getStyle('M1')->applyFromArray($baseFont);
	$aSheet->getStyle('M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('N1', iconv('WINDOWS-1251', 'UTF-8', "Web-site компании"));
	$aSheet->getStyle('N1')->applyFromArray($baseFont);
	$aSheet->getStyle('N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('O1', iconv('WINDOWS-1251', 'UTF-8', "Приоритетные направления"));
	$aSheet->getStyle('O1')->applyFromArray($baseFont);
	$aSheet->getStyle('O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('P1', iconv('WINDOWS-1251', 'UTF-8', "Описание компании"));
	$aSheet->getStyle('P1')->applyFromArray($baseFont);
	$aSheet->getStyle('P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('Q1', iconv('WINDOWS-1251', 'UTF-8', "Имя коллеги (на утро)"));
	$aSheet->getStyle('Q1')->applyFromArray($baseFont);
	$aSheet->getStyle('Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('R1', iconv('WINDOWS-1251', 'UTF-8', "Фамилия коллеги (на утро)"));
	$aSheet->getStyle('R1')->applyFromArray($baseFont);
	$aSheet->getStyle('R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('S1', iconv('WINDOWS-1251', 'UTF-8', "Должность коллеги (на утро)"));
	$aSheet->getStyle('S1')->applyFromArray($baseFont);
	$aSheet->getStyle('S1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('T1', iconv('WINDOWS-1251', 'UTF-8', "E-mail коллеги (на утро)"));
	$aSheet->getStyle('T1')->applyFromArray($baseFont);
	$aSheet->getStyle('T1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$str = 1;
	foreach($exelAr as $key=>$mas){
		$str++;
		$aSheet->setCellValue('A'.$str, $mas['ID']);
		$aSheet->setCellValue('B'.$str, $mas['LOGIN']);
		$aSheet->setCellValue('C'.$str, $mas['PASSWORD']);
		$aSheet->setCellValue('D'.$str, $mas['NAME_COMP']);
		if(count($mas['VID_D']) > 0){
			$aSheet->setCellValue('E'.$str, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
		}else{
			$aSheet->setCellValue('E'.$str, '');
		}
		$aSheet->setCellValue('F'.$str, $mas['ADRESS_COMP']);
		$aSheet->setCellValue('G'.$str, $mas['INDEX']);
		$aSheet->setCellValue('H'.$str, $mas['CITY_COMP']);
		$aSheet->setCellValue('I'.$str, $mas['COUNTRY_COMP']);
		$aSheet->setCellValue('J'.$str, $mas['F_NAME']);
		$aSheet->setCellValue('K'.$str, $mas['JOB']);
		$aSheet->setCellValue('L'.$str, $mas['PHONE']);
		$aSheet->setCellValue('M'.$str, $mas['MAIL']);
		$aSheet->setCellValue('N'.$str, $mas['SITE']);
		$aSheet->setCellValue('O'.$str, $mas['PR_NAPR_ALL']);
		$aSheet->getStyle('O'.$str)->getAlignment()->setWrapText(1);
		$mas['DESC_COMP'] = str_replace(' ', ' ', $mas['DESC_COMP']);//крокозябра
		$aSheet->setCellValue('P'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESC_COMP']));
		$aSheet->getStyle('P'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('Q'.$str, $mas['COLLEGA_NAME']);
		$aSheet->setCellValue('R'.$str, $mas['COLLEGA_L_NAME']);
		$aSheet->setCellValue('S'.$str, $mas['COLLEGA_JOB']);
		$aSheet->setCellValue('T'.$str, $mas['COLLEGA_MAIL']);
	}

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Participants_excel');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Гости Алматы (Hosted Buyers).xls"');
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
}elseif($type == 'guests_hb_all'){
	if(CModule::IncludeModule("iblock") && CModule::IncludeModule("form")){
		//Все пользователи группы "Гости Москва Весна подтвержденные" на утро
		$userExelAr = array();
		$exExelAr = array();
		$exelAr = array();
		$filter = Array("GROUPS_ID"   => LuxorConfig::GROUP_GUEST_A, 'UF_HB'=>'1'); 
		$i = 0;
		$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_ID_COMP", "UF_PAS", 'UF_ID', 'UF_ID4', 'UF_HB')));
		while ($arUser = $rsUsers->Fetch()){
			$exelAr[$i]['ID']       = $arUser['ID'];
			$exelAr[$i]['LOGIN']    = $arUser['LOGIN'];
			$exelAr[$i]['PASSWORD'] = LuxorConfig::returnPas($arUser['UF_PAS']);
			
			//результат формы "Участники данные компании ВСЕ ВЫСТАВКИ"
			if($arUser['UF_ID_COMP'] != ''){
				LuxorConfig::getAnswerFormSimple(
					10,  
					$arrAnswersVarname, 
					array('RESULT_ID'=>$arUser['UF_ID_COMP'])
				);
				$keys[] = $i;
			}
			$i++;
			
		}
		
		$i = 0;
		foreach($arrAnswersVarname as $v){
			$exelAr[$i]['F_NAME'] = $v['SIMPLE_QUESTION_750'][0]['USER_TEXT'].' '.$v['SIMPLE_QUESTION_823'][0]['USER_TEXT'];
			$exelAr[$i]['JOB'] = $v['SIMPLE_QUESTION_391'][0]['USER_TEXT'];
			$exelAr[$i]['PHONE'] = $v['SIMPLE_QUESTION_636'][0]['USER_TEXT'];
			$exelAr[$i]['MAIL'] = $v['SIMPLE_QUESTION_373'][0]['USER_TEXT'];
			$exelAr[$i]['SITE'] = $v['SIMPLE_QUESTION_552'][0]['USER_TEXT'];
			$exelAr[$i]['NAME_COMP']      = $v['SIMPLE_QUESTION_115'][0]['USER_TEXT'];
			$exelAr[$i]['AREA_OF_B']      = $v['SIMPLE_QUESTION_677'][0]['ANSWER_TEXT'];
			$exelAr[$i]['ADRESS_COMP']    = $v['SIMPLE_QUESTION_773'][0]['USER_TEXT'];
			$exelAr[$i]['INDEX']          = $v['SIMPLE_QUESTION_756'][0]['USER_TEXT'];
			$exelAr[$i]['CITY_COMP']      = $v['SIMPLE_QUESTION_672'][0]['USER_TEXT'];
			$exelAr[$i]['DESC_COMP']      = $v['SIMPLE_QUESTION_166'][0]['USER_TEXT'];
			if($v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'] == 'other'){
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_243'][0]['USER_TEXT'];	
			}else{
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'];	
			}
			

			//Вид деятельности
			foreach($v['SIMPLE_QUESTION_677'] as $ar){
				$exelAr[$i]['VID_D'][] = $ar['ANSWER_TEXT'];
			}
			
			//коллега
			$exelAr[$i]['COLLEGA_NAME']     = $v['SIMPLE_QUESTION_816'][0]['USER_TEXT'];
			//$exelAr[$i]['COLLEGA_L_NAME']   = $v['SIMPLE_QUESTION_596'][0]['USER_TEXT'];
			$exelAr[$i]['COLLEGA_F_NAME']     = $v['SIMPLE_QUESTION_816'][0]['USER_TEXT'].' '.$v['SIMPLE_QUESTION_596'][0]['USER_TEXT'];
			$exelAr[$i]['COLLEGA_JOB']      = $v['SIMPLE_QUESTION_304'][0]['USER_TEXT'];
			$exelAr[$i]['COLLEGA_MAIL']     = $v['SIMPLE_QUESTION_278'][0]['USER_TEXT'];
			
			//Приоритетные направления
			//Europe
			foreach($v['SIMPLE_QUESTION_244'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//North America
			foreach($v['SIMPLE_QUESTION_383'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//South America
			foreach($v['SIMPLE_QUESTION_212'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Asia
			foreach($v['SIMPLE_QUESTION_526'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Africa
			foreach($v['SIMPLE_QUESTION_497'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Oceania
			foreach($v['SIMPLE_QUESTION_878'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			
			$exelAr[$i]['PR_NAPR_ALL'] = implode(',', $exelAr[$i]['PR_NAPR']);
			
			$i++;
		}
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
	$aSheet->getColumnDimension('A')->setWidth(35);	
	$aSheet->getColumnDimension('B')->setWidth(35);	
	$aSheet->getColumnDimension('C')->setWidth(35);	
	$aSheet->getColumnDimension('D')->setWidth(35);	
	$aSheet->getColumnDimension('E')->setWidth(50);	
	$aSheet->getColumnDimension('F')->setWidth(50);
	$aSheet->getColumnDimension('G')->setWidth(35);	
	$aSheet->getColumnDimension('H')->setWidth(35);	
	$aSheet->getColumnDimension('I')->setWidth(60);
	$aSheet->getColumnDimension('J')->setWidth(60);
	$aSheet->getColumnDimension('K')->setWidth(50);
	$aSheet->getColumnDimension('L')->setWidth(30);
	$aSheet->getColumnDimension('M')->setWidth(50);
	$aSheet->getColumnDimension('N')->setWidth(30);
	$aSheet->getColumnDimension('O')->setWidth(60);
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

	$aSheet->setCellValue('A1', 'uID');
	$aSheet->getStyle('A1')->applyFromArray($baseFont);
	$aSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('B1', iconv('WINDOWS-1251', 'UTF-8', 'Логин'));
	$aSheet->getStyle('B1')->applyFromArray($baseFont);
	$aSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C1', iconv('WINDOWS-1251', 'UTF-8', 'Пароль'));
	$aSheet->getStyle('C1')->applyFromArray($baseFont);
	$aSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D1', iconv('WINDOWS-1251', 'UTF-8', 'Компания'));
	$aSheet->getStyle('D1')->applyFromArray($baseFont);
	$aSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E1', iconv('WINDOWS-1251', 'UTF-8', 'Вид деятельности'));
	$aSheet->getStyle('E1')->applyFromArray($baseFont);
	$aSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F1', iconv('WINDOWS-1251', 'UTF-8', 'Адрес'));
	$aSheet->getStyle('F1')->applyFromArray($baseFont);
	$aSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G1', iconv('WINDOWS-1251', 'UTF-8', 'Индекс'));
	$aSheet->getStyle('G1')->applyFromArray($baseFont);
	$aSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H1', iconv('WINDOWS-1251', 'UTF-8', 'Город'));
	$aSheet->getStyle('H1')->applyFromArray($baseFont);
	$aSheet->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I1', iconv('WINDOWS-1251', 'UTF-8', "Страна"));
	$aSheet->getStyle('I1')->applyFromArray($baseFont);
	$aSheet->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J1', iconv('WINDOWS-1251', 'UTF-8', 'Описание компании'));
	$aSheet->getStyle('J1')->applyFromArray($baseFont);
	$aSheet->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K1', iconv('WINDOWS-1251', 'UTF-8', 'Web-site компании'));
	$aSheet->getStyle('K1')->applyFromArray($baseFont);
	$aSheet->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L1', iconv('WINDOWS-1251', 'UTF-8', 'Имя Фамилия'));
	$aSheet->getStyle('L1')->applyFromArray($baseFont);
	$aSheet->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('M1', iconv('WINDOWS-1251', 'UTF-8', "Должность"));
	$aSheet->getStyle('M1')->applyFromArray($baseFont);
	$aSheet->getStyle('M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('N1', iconv('WINDOWS-1251', 'UTF-8', "Телефон"));
	$aSheet->getStyle('N1')->applyFromArray($baseFont);
	$aSheet->getStyle('N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('O1', iconv('WINDOWS-1251', 'UTF-8', "E-mail"));
	$aSheet->getStyle('O1')->applyFromArray($baseFont);
	$aSheet->getStyle('O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$aSheet->setCellValue('A2', 'uID');
	$aSheet->getStyle('A2')->applyFromArray($baseFont);
	$aSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('B2', iconv('WINDOWS-1251', 'UTF-8', 'Логин'));
	$aSheet->getStyle('B2')->applyFromArray($baseFont);
	$aSheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C2', iconv('WINDOWS-1251', 'UTF-8', 'Пароль'));
	$aSheet->getStyle('C2')->applyFromArray($baseFont);
	$aSheet->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D2', iconv('WINDOWS-1251', 'UTF-8', 'Компания'));
	$aSheet->getStyle('D2')->applyFromArray($baseFont);
	$aSheet->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E2', iconv('WINDOWS-1251', 'UTF-8', 'Вид деятельности'));
	$aSheet->getStyle('E2')->applyFromArray($baseFont);
	$aSheet->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F2', iconv('WINDOWS-1251', 'UTF-8', 'Адрес'));
	$aSheet->getStyle('F2')->applyFromArray($baseFont);
	$aSheet->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G2', iconv('WINDOWS-1251', 'UTF-8', 'Индекс'));
	$aSheet->getStyle('G2')->applyFromArray($baseFont);
	$aSheet->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H2', iconv('WINDOWS-1251', 'UTF-8', 'Город'));
	$aSheet->getStyle('H2')->applyFromArray($baseFont);
	$aSheet->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I2', iconv('WINDOWS-1251', 'UTF-8', "Страна"));
	$aSheet->getStyle('I2')->applyFromArray($baseFont);
	$aSheet->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J2', iconv('WINDOWS-1251', 'UTF-8', 'Описание компании'));
	$aSheet->getStyle('J2')->applyFromArray($baseFont);
	$aSheet->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K2', iconv('WINDOWS-1251', 'UTF-8', 'Web-site компании'));
	$aSheet->getStyle('K2')->applyFromArray($baseFont);
	$aSheet->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L2', iconv('WINDOWS-1251', 'UTF-8', 'Имя Фамилия (коллега)'));
	$aSheet->getStyle('L2')->applyFromArray($baseFont);
	$aSheet->getStyle('L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('M2', iconv('WINDOWS-1251', 'UTF-8', "Должность коллеги (на утро)"));
	$aSheet->getStyle('M2')->applyFromArray($baseFont);
	$aSheet->getStyle('M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('N2', iconv('WINDOWS-1251', 'UTF-8', "Телефон (дублируем)"));
	$aSheet->getStyle('N2')->applyFromArray($baseFont);
	$aSheet->getStyle('N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('O2', iconv('WINDOWS-1251', 'UTF-8', "E-mail коллеги (на утро)"));
	$aSheet->getStyle('O2')->applyFromArray($baseFont);
	$aSheet->getStyle('O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$str = 1;
	$str2 = 2;
	foreach($exelAr as $key=>$mas){
		$str +=3;
		$str2 +=3;
		$aSheet->setCellValue('A'.$str, $mas['ID']);
		$aSheet->setCellValue('B'.$str, $mas['LOGIN']);
		$aSheet->setCellValue('C'.$str, $mas['PASSWORD']);
		$aSheet->setCellValue('D'.$str, $mas['NAME_COMP']);
		if(count($mas['VID_D']) > 0){
			$aSheet->setCellValue('E'.$str, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
		}else{
			$aSheet->setCellValue('E'.$str, '');
		}
		$aSheet->setCellValue('F'.$str, $mas['ADRESS_COMP']);
		$aSheet->setCellValue('G'.$str, $mas['INDEX']);
		$aSheet->setCellValue('H'.$str, $mas['CITY_COMP']);
		$aSheet->setCellValue('I'.$str, $mas['COUNTRY_COMP']);
		$mas['DESC_COMP'] = str_replace(' ', ' ', $mas['DESC_COMP']);//крокозябра
		$aSheet->setCellValue('J'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['DESC_COMP']));
		$aSheet->getStyle('J'.$str)->getAlignment()->setWrapText(1);
		$aSheet->setCellValue('K'.$str, $mas['SITE']);
		$aSheet->setCellValue('L'.$str, $mas['F_NAME']);
		$aSheet->setCellValue('M'.$str, $mas['JOB']);
		$aSheet->setCellValue('N'.$str, $mas['PHONE']);
		$aSheet->setCellValue('O'.$str, $mas['MAIL']);
		if($mas['COLLEGA_NAME'] != ''){
			$aSheet->setCellValue('A'.$str2, $mas['ID']);
			$aSheet->setCellValue('B'.$str2, $mas['LOGIN']);
			$aSheet->setCellValue('C'.$str2, $mas['PASSWORD']);
			$aSheet->setCellValue('D'.$str2, $mas['NAME_COMP']);
			if(count($mas['VID_D']) > 0){
				$aSheet->setCellValue('E'.$str2, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
			}else{
				$aSheet->setCellValue('E'.$str2, '');
			}
			$aSheet->setCellValue('F'.$str2, $mas['ADRESS_COMP']);
			$aSheet->setCellValue('G'.$str2, $mas['INDEX']);
			$aSheet->setCellValue('H'.$str2, $mas['CITY_COMP']);
			$aSheet->setCellValue('I'.$str2, $mas['COUNTRY_COMP']);
			$mas['DESC_COMP'] = str_replace(' ', ' ', $mas['DESC_COMP']);//крокозябра
			$aSheet->setCellValue('J'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['DESC_COMP']));
			$aSheet->getStyle('J'.$str2)->getAlignment()->setWrapText(1);
			$aSheet->setCellValue('K'.$str2, $mas['SITE']);
			$aSheet->setCellValue('L'.$str2, $mas['COLLEGA_F_NAME']);
			$aSheet->setCellValue('M'.$str2, $mas['COLLEGA_JOB']);
			$aSheet->setCellValue('N'.$str2, $mas['PHONE']);
			$aSheet->setCellValue('O'.$str2, $mas['COLLEGA_MAIL']);
		}
	}

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Participants_excel');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Гости Алматы Hosted Buyers (все люди).xls"');
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
}elseif($type == 'guests_ev'){
	if(CModule::IncludeModule("iblock") && CModule::IncludeModule("form")){
		//Все пользователи группы "Гости Алматы подтвержденные" на вечер
		$userExelAr = array();
		$exExelAr = array();
		$exelAr = array();
		$filter = Array("GROUPS_ID"   => LuxorConfig::GROUP_GUEST_A, 'UF_EV'=>'1'); 
		$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_ID_COMP", "UF_PAS", 'UF_ID2', 'UF_ID4', 'UF_EV')));
		$i = 0;
		while ($arUser = $rsUsers->Fetch()){
			$exelAr[$i]['ID']       = $arUser['ID'];
			$exelAr[$i]['LOGIN']    = $arUser['LOGIN'];
			$exelAr[$i]['PASSWORD'] = LuxorConfig::returnPas($arUser['UF_PAS']);

			//результат формы "Участники данные компании ВСЕ ВЫСТАВКИ"
			if($arUser['UF_ID_COMP'] != ''){
				LuxorConfig::getAnswerFormSimple(
					10, 
					$arrAnswersVarname, 
					array('RESULT_ID'=>$arUser['UF_ID_COMP'])
				);
				$keys[] = $i;
			}
			$i++;
		}
		
		
		$i = 0;
		foreach($arrAnswersVarname as $v){
			$exelAr[$i]['F_NAME'] = $v['SIMPLE_QUESTION_750'][0]['USER_TEXT'].' '.$v['SIMPLE_QUESTION_823'][0]['USER_TEXT'];
			$exelAr[$i]['JOB'] = $v['SIMPLE_QUESTION_391'][0]['USER_TEXT'];
			$exelAr[$i]['PHONE'] = $v['SIMPLE_QUESTION_636'][0]['USER_TEXT'];
			$exelAr[$i]['MAIL'] = $v['SIMPLE_QUESTION_373'][0]['USER_TEXT'];
			$exelAr[$i]['SITE'] = $v['SIMPLE_QUESTION_552'][0]['USER_TEXT'];
			$exelAr[$i]['NAME_COMP']      = $v['SIMPLE_QUESTION_115'][0]['USER_TEXT'];
			$exelAr[$i]['AREA_OF_B']      = $v['SIMPLE_QUESTION_677'][0]['ANSWER_TEXT'];
			$exelAr[$i]['ADRESS_COMP']    = $v['SIMPLE_QUESTION_773'][0]['USER_TEXT'];
			$exelAr[$i]['INDEX']          = $v['SIMPLE_QUESTION_756'][0]['USER_TEXT'];
			$exelAr[$i]['CITY_COMP']      = $v['SIMPLE_QUESTION_672'][0]['USER_TEXT'];
			$exelAr[$i]['DESC_COMP']      = $v['SIMPLE_QUESTION_166'][0]['USER_TEXT'];
			if($v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'] == 'other'){
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_243'][0]['USER_TEXT'];	
			}else{
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'];	
			}
			

			//Вид деятельности
			foreach($v['SIMPLE_QUESTION_677'] as $ar){
				$exelAr[$i]['VID_D'][] = $ar['ANSWER_TEXT'];
			}
			
			//коллега
			if($v['SIMPLE_QUESTION_367'][0]['USER_TEXT'] == 'Имя коллеги'){
				$exelAr[$i]['COLLEGA_NAME']     = '';
			}else{
				$exelAr[$i]['COLLEGA_NAME']     = $v['SIMPLE_QUESTION_367'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_482'][0]['USER_TEXT'] == 'Фамилия коллеги'){
				$exelAr[$i]['COLLEGA_L_NAME']     = '';
			}else{
				$exelAr[$i]['COLLEGA_L_NAME']     = $v['SIMPLE_QUESTION_482'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_187'][0]['USER_TEXT'] == 'Должность коллеги'){
				$exelAr[$i]['COLLEGA_JOB']     = '';
			}else{
				$exelAr[$i]['COLLEGA_JOB']     = $v['SIMPLE_QUESTION_187'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_421'][0]['USER_TEXT'] == 'E-mail коллеги'){
				$exelAr[$i]['COLLEGA_MAIL']     = '';
			}else{
				$exelAr[$i]['COLLEGA_MAIL']     = $v['SIMPLE_QUESTION_421'][0]['USER_TEXT'];
			}
			//коллега 2
			if($v['SIMPLE_QUESTION_225'][0]['USER_TEXT'] == 'Имя коллеги'){
				$exelAr[$i]['COLLEGA_NAME2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_NAME2']     = $v['SIMPLE_QUESTION_225'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_770'][0]['USER_TEXT'] == 'Фамилия коллеги'){
				$exelAr[$i]['COLLEGA_L_NAME2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_L_NAME2']     = $v['SIMPLE_QUESTION_770'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_280'][0]['USER_TEXT'] == 'Должность коллеги'){
				$exelAr[$i]['COLLEGA_JOB2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_JOB2']     = $v['SIMPLE_QUESTION_280'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_384'][0]['USER_TEXT'] == 'E-mail коллеги'){
				$exelAr[$i]['COLLEGA_MAIL2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_MAIL2']     = $v['SIMPLE_QUESTION_384'][0]['USER_TEXT'];
			}
			//коллега 3
			if($v['SIMPLE_QUESTION_765'][0]['USER_TEXT'] == 'Имя коллеги'){
				$exelAr[$i]['COLLEGA_NAME3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_NAME3']     = $v['SIMPLE_QUESTION_765'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_627'][0]['USER_TEXT'] == 'Фамилия коллеги'){
				$exelAr[$i]['COLLEGA_L_NAME3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_L_NAME3']     = $v['SIMPLE_QUESTION_627'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_788'][0]['USER_TEXT'] == 'Должность коллеги'){
				$exelAr[$i]['COLLEGA_JOB3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_JOB3']     = $v['SIMPLE_QUESTION_788'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_230'][0]['USER_TEXT'] == 'E-mail коллеги'){
				$exelAr[$i]['COLLEGA_MAIL3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_MAIL3']     = $v['SIMPLE_QUESTION_230'][0]['USER_TEXT'];
			}
			
			//Приоритетные направления
			//Europe
			foreach($v['SIMPLE_QUESTION_244'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//North America
			foreach($v['SIMPLE_QUESTION_383'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//South America
			foreach($v['SIMPLE_QUESTION_212'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Asia
			foreach($v['SIMPLE_QUESTION_526'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Africa
			foreach($v['SIMPLE_QUESTION_497'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Oceania
			foreach($v['SIMPLE_QUESTION_878'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			
			$exelAr[$i]['PR_NAPR_ALL'] = implode(',', $exelAr[$i]['PR_NAPR']);
			
			$i++;
		}
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
	$aSheet->getColumnDimension('A')->setWidth(12);	
	$aSheet->getColumnDimension('B')->setWidth(35);	
	$aSheet->getColumnDimension('C')->setWidth(50);	
	$aSheet->getColumnDimension('D')->setWidth(60);	
	$aSheet->getColumnDimension('E')->setWidth(30);	
	$aSheet->getColumnDimension('F')->setWidth(30);
	$aSheet->getColumnDimension('G')->setWidth(30);	
	$aSheet->getColumnDimension('H')->setWidth(35);	
	$aSheet->getColumnDimension('I')->setWidth(35);
	$aSheet->getColumnDimension('J')->setWidth(40);
	$aSheet->getColumnDimension('K')->setWidth(40);
	$aSheet->getColumnDimension('L')->setWidth(40);
	$aSheet->getColumnDimension('M')->setWidth(40);
	$aSheet->getColumnDimension('N')->setWidth(40);
	$aSheet->getColumnDimension('O')->setWidth(40);
	$aSheet->getColumnDimension('P')->setWidth(40);
	$aSheet->getColumnDimension('Q')->setWidth(40);
	$aSheet->getColumnDimension('R')->setWidth(40);
	$aSheet->getColumnDimension('S')->setWidth(40);
	$aSheet->getColumnDimension('T')->setWidth(40);
	$aSheet->getColumnDimension('U')->setWidth(40);
	$aSheet->getColumnDimension('V')->setWidth(40);
	$aSheet->getColumnDimension('W')->setWidth(40);
	$aSheet->getColumnDimension('X')->setWidth(40);

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
	$aSheet->setCellValue('B1', iconv('WINDOWS-1251', 'UTF-8', 'Название компании'));
	$aSheet->getStyle('B1')->applyFromArray($baseFont);
	$aSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C1', iconv('WINDOWS-1251', 'UTF-8', 'Вид деятельности'));
	$aSheet->getStyle('C1')->applyFromArray($baseFont);
	$aSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D1', iconv('WINDOWS-1251', 'UTF-8', 'Адрес'));
	$aSheet->getStyle('D1')->applyFromArray($baseFont);
	$aSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E1', iconv('WINDOWS-1251', 'UTF-8', 'Индекс'));
	$aSheet->getStyle('E1')->applyFromArray($baseFont);
	$aSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F1', iconv('WINDOWS-1251', 'UTF-8', 'Город'));
	$aSheet->getStyle('F1')->applyFromArray($baseFont);
	$aSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G1', iconv('WINDOWS-1251', 'UTF-8', "Страна"));
	$aSheet->getStyle('G1')->applyFromArray($baseFont);
	$aSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H1', iconv('WINDOWS-1251', 'UTF-8', 'Имя Фамилия'));
	$aSheet->getStyle('H1')->applyFromArray($baseFont);
	$aSheet->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I1', iconv('WINDOWS-1251', 'UTF-8', "Должность"));
	$aSheet->getStyle('I1')->applyFromArray($baseFont);
	$aSheet->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J1', iconv('WINDOWS-1251', 'UTF-8', "Телефон"));
	$aSheet->getStyle('J1')->applyFromArray($baseFont);
	$aSheet->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K1', iconv('WINDOWS-1251', 'UTF-8', "E-mail"));
	$aSheet->getStyle('K1')->applyFromArray($baseFont);
	$aSheet->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L1', iconv('WINDOWS-1251', 'UTF-8', "Web-site компании"));
	$aSheet->getStyle('L1')->applyFromArray($baseFont);
	$aSheet->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('M1', iconv('WINDOWS-1251', 'UTF-8', "Имя коллеги 1"));
	$aSheet->getStyle('M1')->applyFromArray($baseFont);
	$aSheet->getStyle('M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('N1', iconv('WINDOWS-1251', 'UTF-8', "Фамилия коллеги 1"));
	$aSheet->getStyle('N1')->applyFromArray($baseFont);
	$aSheet->getStyle('N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('O1', iconv('WINDOWS-1251', 'UTF-8', "Должность коллеги 1"));
	$aSheet->getStyle('O1')->applyFromArray($baseFont);
	$aSheet->getStyle('O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('P1', iconv('WINDOWS-1251', 'UTF-8', "E-mail коллеги 1"));
	$aSheet->getStyle('P1')->applyFromArray($baseFont);
	$aSheet->getStyle('P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('Q1', iconv('WINDOWS-1251', 'UTF-8', "Имя коллеги 2"));
	$aSheet->getStyle('Q1')->applyFromArray($baseFont);
	$aSheet->getStyle('Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('R1', iconv('WINDOWS-1251', 'UTF-8', "Фамилия коллеги 2"));
	$aSheet->getStyle('R1')->applyFromArray($baseFont);
	$aSheet->getStyle('R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('S1', iconv('WINDOWS-1251', 'UTF-8', "Должность коллеги 2"));
	$aSheet->getStyle('S1')->applyFromArray($baseFont);
	$aSheet->getStyle('S1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('T1', iconv('WINDOWS-1251', 'UTF-8', "E-mail коллеги 2"));
	$aSheet->getStyle('T1')->applyFromArray($baseFont);
	$aSheet->getStyle('T1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('U1', iconv('WINDOWS-1251', 'UTF-8', "Имя коллеги 3"));
	$aSheet->getStyle('U1')->applyFromArray($baseFont);
	$aSheet->getStyle('U1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('V1', iconv('WINDOWS-1251', 'UTF-8', "Фамилия коллеги 3"));
	$aSheet->getStyle('V1')->applyFromArray($baseFont);
	$aSheet->getStyle('V1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('W1', iconv('WINDOWS-1251', 'UTF-8', "Должность коллеги 3"));
	$aSheet->getStyle('W1')->applyFromArray($baseFont);
	$aSheet->getStyle('W1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('X1', iconv('WINDOWS-1251', 'UTF-8', "E-mail коллеги 3"));
	$aSheet->getStyle('X1')->applyFromArray($baseFont);
	$aSheet->getStyle('X1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	

	$str = 1;
	foreach($exelAr as $key=>$mas){
		$str++;
		$aSheet->setCellValue('A'.$str, $mas['ID']);
		$aSheet->setCellValue('B'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['NAME_COMP']));
		if(count($mas['VID_D']) > 0){
			$aSheet->setCellValue('C'.$str, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
		}else{
			$aSheet->setCellValue('C'.$str, '');
		}
		$aSheet->setCellValue('D'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['ADRESS_COMP']));
		$aSheet->setCellValue('E'.$str, $mas['INDEX']);
		$aSheet->setCellValue('F'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['CITY_COMP']));
		$aSheet->setCellValue('G'.$str, $mas['COUNTRY_COMP']);
		$aSheet->setCellValue('H'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['F_NAME']));
		$aSheet->setCellValue('I'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['JOB']));
		$aSheet->setCellValue('J'.$str, $mas['PHONE']);
		$aSheet->setCellValue('K'.$str, $mas['MAIL']);
		$aSheet->setCellValue('L'.$str, $mas['SITE']);
		$aSheet->setCellValue('M'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_NAME']));
		$aSheet->setCellValue('N'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_L_NAME']));
		$aSheet->setCellValue('O'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_JOB']));
		$aSheet->setCellValue('P'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_MAIL']));
		$aSheet->setCellValue('Q'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_NAME2']));
		$aSheet->setCellValue('R'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_L_NAME2']));
		$aSheet->setCellValue('S'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_JOB2']));
		$aSheet->setCellValue('T'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_MAIL2']));
		$aSheet->setCellValue('U'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_NAME3']));
		$aSheet->setCellValue('V'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_L_NAME3']));
		$aSheet->setCellValue('W'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_JOB3']));
		$aSheet->setCellValue('X'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_MAIL3']));

	}

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Participants_excel');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Гости Алматы вечер.xls"');
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
}elseif($type == 'guests_ev_all'){
	if(CModule::IncludeModule("iblock") && CModule::IncludeModule("form")){
		//Все пользователи группы "Гости Алматы подтвержденные" на вечер
		$userExelAr = array();
		$exExelAr = array();
		$exelAr = array();
		$filter = Array("GROUPS_ID"   => LuxorConfig::GROUP_GUEST_A, 'UF_EV'=>'1'); 
		$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_ID_COMP", "UF_PAS", 'UF_ID2', 'UF_ID4', 'UF_EV')));
		$i = 0;
		while ($arUser = $rsUsers->Fetch()){
			$exelAr[$i]['ID']       = $arUser['ID'];
			$exelAr[$i]['LOGIN']    = $arUser['LOGIN'];
			$exelAr[$i]['PASSWORD'] = LuxorConfig::returnPas($arUser['UF_PAS']);

			//результат формы "Участники данные компании ВСЕ ВЫСТАВКИ"
			if($arUser['UF_ID_COMP'] != ''){
				LuxorConfig::getAnswerFormSimple(
					10, 
					$arrAnswersVarname, 
					array('RESULT_ID'=>$arUser['UF_ID_COMP'])
				);
				$keys[] = $i;
			}
			$i++;
		}
		
		$i = 0;
		foreach($arrAnswersVarname as $v){
			$exelAr[$i]['F_NAME'] = $v['SIMPLE_QUESTION_750'][0]['USER_TEXT'].' '.$v['SIMPLE_QUESTION_823'][0]['USER_TEXT'];
			$exelAr[$i]['JOB'] = $v['SIMPLE_QUESTION_391'][0]['USER_TEXT'];
			$exelAr[$i]['PHONE'] = $v['SIMPLE_QUESTION_636'][0]['USER_TEXT'];
			$exelAr[$i]['MAIL'] = $v['SIMPLE_QUESTION_373'][0]['USER_TEXT'];
			$exelAr[$i]['SITE'] = $v['SIMPLE_QUESTION_552'][0]['USER_TEXT'];
			$exelAr[$i]['NAME_COMP']      = $v['SIMPLE_QUESTION_115'][0]['USER_TEXT'];
			$exelAr[$i]['AREA_OF_B']      = $v['SIMPLE_QUESTION_677'][0]['ANSWER_TEXT'];
			$exelAr[$i]['ADRESS_COMP']    = $v['SIMPLE_QUESTION_773'][0]['USER_TEXT'];
			$exelAr[$i]['INDEX']          = $v['SIMPLE_QUESTION_756'][0]['USER_TEXT'];
			$exelAr[$i]['CITY_COMP']      = $v['SIMPLE_QUESTION_672'][0]['USER_TEXT'];
			$exelAr[$i]['DESC_COMP']      = $v['SIMPLE_QUESTION_166'][0]['USER_TEXT'];
			if($v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'] == 'other'){
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_243'][0]['USER_TEXT'];	
			}else{
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'];	
			}
			

			//Вид деятельности
			foreach($v['SIMPLE_QUESTION_677'] as $ar){
				$exelAr[$i]['VID_D'][] = $ar['ANSWER_TEXT'];
			}
			
			//коллега
			if($v['SIMPLE_QUESTION_367'][0]['USER_TEXT'] == 'Имя коллеги'){
				$exelAr[$i]['COLLEGA_NAME']     = '';
			}else{
				$exelAr[$i]['COLLEGA_NAME']     = $v['SIMPLE_QUESTION_367'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_482'][0]['USER_TEXT'] == 'Фамилия коллеги'){
				$exelAr[$i]['COLLEGA_L_NAME']     = '';
			}else{
				$exelAr[$i]['COLLEGA_L_NAME']     = $v['SIMPLE_QUESTION_482'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_187'][0]['USER_TEXT'] == 'Должность коллеги'){
				$exelAr[$i]['COLLEGA_JOB']     = '';
			}else{
				$exelAr[$i]['COLLEGA_JOB']     = $v['SIMPLE_QUESTION_187'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_421'][0]['USER_TEXT'] == 'E-mail коллеги'){
				$exelAr[$i]['COLLEGA_MAIL']     = '';
			}else{
				$exelAr[$i]['COLLEGA_MAIL']     = $v['SIMPLE_QUESTION_421'][0]['USER_TEXT'];
			}
			//коллега 2
			if($v['SIMPLE_QUESTION_225'][0]['USER_TEXT'] == 'Имя коллеги'){
				$exelAr[$i]['COLLEGA_NAME2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_NAME2']     = $v['SIMPLE_QUESTION_225'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_770'][0]['USER_TEXT'] == 'Фамилия коллеги'){
				$exelAr[$i]['COLLEGA_L_NAME2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_L_NAME2']     = $v['SIMPLE_QUESTION_770'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_280'][0]['USER_TEXT'] == 'Должность коллеги'){
				$exelAr[$i]['COLLEGA_JOB2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_JOB2']     = $v['SIMPLE_QUESTION_280'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_384'][0]['USER_TEXT'] == 'E-mail коллеги'){
				$exelAr[$i]['COLLEGA_MAIL2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_MAIL2']     = $v['SIMPLE_QUESTION_384'][0]['USER_TEXT'];
			}
			//коллега 3
			if($v['SIMPLE_QUESTION_765'][0]['USER_TEXT'] == 'Имя коллеги'){
				$exelAr[$i]['COLLEGA_NAME3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_NAME3']     = $v['SIMPLE_QUESTION_765'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_627'][0]['USER_TEXT'] == 'Фамилия коллеги'){
				$exelAr[$i]['COLLEGA_L_NAME3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_L_NAME3']     = $v['SIMPLE_QUESTION_627'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_788'][0]['USER_TEXT'] == 'Должность коллеги'){
				$exelAr[$i]['COLLEGA_JOB3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_JOB3']     = $v['SIMPLE_QUESTION_788'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_230'][0]['USER_TEXT'] == 'E-mail коллеги'){
				$exelAr[$i]['COLLEGA_MAIL3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_MAIL3']     = $v['SIMPLE_QUESTION_230'][0]['USER_TEXT'];
			}
			
			//Приоритетные направления
			//Europe
			foreach($v['SIMPLE_QUESTION_244'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//North America
			foreach($v['SIMPLE_QUESTION_383'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//South America
			foreach($v['SIMPLE_QUESTION_212'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Asia
			foreach($v['SIMPLE_QUESTION_526'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Africa
			foreach($v['SIMPLE_QUESTION_497'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Oceania
			foreach($v['SIMPLE_QUESTION_878'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			
			$exelAr[$i]['PR_NAPR_ALL'] = implode(',', $exelAr[$i]['PR_NAPR']);
			
			$i++;
		}
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
	$aSheet->getColumnDimension('A')->setWidth(12);	
	$aSheet->getColumnDimension('B')->setWidth(35);	
	$aSheet->getColumnDimension('C')->setWidth(50);	
	$aSheet->getColumnDimension('D')->setWidth(60);	
	$aSheet->getColumnDimension('E')->setWidth(30);	
	$aSheet->getColumnDimension('F')->setWidth(30);
	$aSheet->getColumnDimension('G')->setWidth(30);	
	$aSheet->getColumnDimension('H')->setWidth(35);	
	$aSheet->getColumnDimension('I')->setWidth(35);
	$aSheet->getColumnDimension('J')->setWidth(40);
	$aSheet->getColumnDimension('K')->setWidth(40);
	$aSheet->getColumnDimension('L')->setWidth(40);

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
	$aSheet->setCellValue('B1', iconv('WINDOWS-1251', 'UTF-8', 'Название компании'));
	$aSheet->getStyle('B1')->applyFromArray($baseFont);
	$aSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C1', iconv('WINDOWS-1251', 'UTF-8', 'Вид деятельности'));
	$aSheet->getStyle('C1')->applyFromArray($baseFont);
	$aSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D1', iconv('WINDOWS-1251', 'UTF-8', 'Адрес'));
	$aSheet->getStyle('D1')->applyFromArray($baseFont);
	$aSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E1', iconv('WINDOWS-1251', 'UTF-8', 'Индекс'));
	$aSheet->getStyle('E1')->applyFromArray($baseFont);
	$aSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F1', iconv('WINDOWS-1251', 'UTF-8', 'Город'));
	$aSheet->getStyle('F1')->applyFromArray($baseFont);
	$aSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G1', iconv('WINDOWS-1251', 'UTF-8', "Страна"));
	$aSheet->getStyle('G1')->applyFromArray($baseFont);
	$aSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H1', iconv('WINDOWS-1251', 'UTF-8', 'Имя Фамилия'));
	$aSheet->getStyle('H1')->applyFromArray($baseFont);
	$aSheet->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I1', iconv('WINDOWS-1251', 'UTF-8', "Должность"));
	$aSheet->getStyle('I1')->applyFromArray($baseFont);
	$aSheet->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J1', iconv('WINDOWS-1251', 'UTF-8', "Телефон"));
	$aSheet->getStyle('J1')->applyFromArray($baseFont);
	$aSheet->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K1', iconv('WINDOWS-1251', 'UTF-8', "E-mail"));
	$aSheet->getStyle('K1')->applyFromArray($baseFont);
	$aSheet->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L1', iconv('WINDOWS-1251', 'UTF-8', "Web-site компании"));
	$aSheet->getStyle('L1')->applyFromArray($baseFont);
	$aSheet->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	

	$str = 0;
	$str1 = 1;
	$str2 = 2;
	$str3 = 3;
	foreach($exelAr as $key=>$mas){
		$str +=3;
		$str1 +=3;
		$str2 +=3;
		$str3 +=3;
		$aSheet->setCellValue('A'.$str, $mas['ID']);
		$aSheet->setCellValue('B'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['NAME_COMP']));
		if(count($mas['VID_D']) > 0){
			$aSheet->setCellValue('C'.$str, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
		}else{
			$aSheet->setCellValue('C'.$str, '');
		}
		$aSheet->setCellValue('D'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['ADRESS_COMP']));
		$aSheet->setCellValue('E'.$str, $mas['INDEX']);
		$aSheet->setCellValue('F'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['CITY_COMP']));
		$aSheet->setCellValue('G'.$str, $mas['COUNTRY_COMP']);
		$aSheet->setCellValue('H'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['F_NAME']));
		$aSheet->setCellValue('I'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['JOB']));
		$aSheet->setCellValue('J'.$str, $mas['PHONE']);
		$aSheet->setCellValue('K'.$str, $mas['MAIL']);
		$aSheet->setCellValue('L'.$str, $mas['SITE']);
		if($mas['COLLEGA_NAME'] != ''){
			$aSheet->setCellValue('A'.$str1, $mas['ID']);
			$aSheet->setCellValue('B'.$str1, iconv('WINDOWS-1251', 'UTF-8', $mas['NAME_COMP']));
			if(count($mas['VID_D']) > 0){
				$aSheet->setCellValue('C'.$str1, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
			}else{
				$aSheet->setCellValue('C'.$str1, '');
			}
			$aSheet->setCellValue('D'.$str1, iconv('WINDOWS-1251', 'UTF-8', $mas['ADRESS_COMP']));
			$aSheet->setCellValue('E'.$str1, $mas['INDEX']);
			$aSheet->setCellValue('F'.$str1, iconv('WINDOWS-1251', 'UTF-8', $mas['CITY_COMP']));
			$aSheet->setCellValue('G'.$str1, $mas['COUNTRY_COMP']);
			$aSheet->setCellValue('H'.$str1, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_NAME'].' '.$mas['COLLEGA_L_NAME']));
			$aSheet->setCellValue('I'.$str1, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_JOB']));
			$aSheet->setCellValue('J'.$str1, $mas['PHONE']);
			$aSheet->setCellValue('K'.$str1, $mas['COLLEGA_MAIL']);
			$aSheet->setCellValue('L'.$str1, $mas['SITE']);
		}
		
		if($mas['COLLEGA_NAME2'] != ''){
			$aSheet->setCellValue('A'.$str2, $mas['ID']);
			$aSheet->setCellValue('B'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['NAME_COMP']));
			if(count($mas['VID_D']) > 0){
				$aSheet->setCellValue('C'.$str2, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
			}else{
				$aSheet->setCellValue('C'.$str2, '');
			}
			$aSheet->setCellValue('D'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['ADRESS_COMP']));
			$aSheet->setCellValue('E'.$str2, $mas['INDEX']);
			$aSheet->setCellValue('F'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['CITY_COMP']));
			$aSheet->setCellValue('G'.$str2, $mas['COUNTRY_COMP']);
			$aSheet->setCellValue('H'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_NAME2'].' '.$mas['COLLEGA_L_NAME2']));
			$aSheet->setCellValue('I'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_JOB2']));
			$aSheet->setCellValue('J'.$str2, $mas['PHONE']);
			$aSheet->setCellValue('K'.$str2, $mas['COLLEGA_MAIL2']);
			$aSheet->setCellValue('L'.$str2, $mas['SITE']);
		}
		if($mas['COLLEGA_NAME3'] != ''){
			$aSheet->setCellValue('A'.$str3, $mas['ID']);
			$aSheet->setCellValue('B'.$str3, iconv('WINDOWS-1251', 'UTF-8', $mas['NAME_COMP']));
			if(count($mas['VID_D']) > 0){
				$aSheet->setCellValue('C'.$str3, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
			}else{
				$aSheet->setCellValue('C'.$str3, '');
			}
			$aSheet->setCellValue('D'.$str3, iconv('WINDOWS-1251', 'UTF-8', $mas['ADRESS_COMP']));
			$aSheet->setCellValue('E'.$str3, $mas['INDEX']);
			$aSheet->setCellValue('F'.$str3, iconv('WINDOWS-1251', 'UTF-8', $mas['CITY_COMP']));
			$aSheet->setCellValue('G'.$str3, $mas['COUNTRY_COMP']);
			$aSheet->setCellValue('H'.$str3, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_NAME3'].' '.$mas['COLLEGA_L_NAME3']));
			$aSheet->setCellValue('I'.$str3, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_JOB3']));
			$aSheet->setCellValue('J'.$str3, $mas['PHONE']);
			$aSheet->setCellValue('K'.$str3, $mas['COLLEGA_MAIL3']);
			$aSheet->setCellValue('L'.$str3, $mas['SITE']);
		}
	}

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Participants_excel');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Гости Алматы вечер (все люди).xls"');
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
}elseif($type == 'guests_inact'){
	if(CModule::IncludeModule("iblock") && CModule::IncludeModule("form")){
		//Все пользователи группы "Гости Алматы неподтвержденные" на утро
		$userExelAr = array();
		$exExelAr = array();
		$exelAr = array();
		$filter = Array("GROUPS_ID"   => LuxorConfig::GROUP_GUEST_INACT, '!UF_ID4'=>'false'); 
		$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_ID_COMP", "UF_PAS", 'UF_ID2', 'UF_ID3', 'UF_EV')));
		$i = 0;
		while ($arUser = $rsUsers->Fetch()){
			$exelAr[$i]['ID']       = $arUser['ID'];
			$exelAr[$i]['LOGIN']    = $arUser['LOGIN'];
			$exelAr[$i]['PASSWORD'] = LuxorConfig::returnPas($arUser['UF_PAS']);

			//результат формы "Участники данные компании ВСЕ ВЫСТАВКИ"
			if($arUser['UF_ID_COMP'] != ''){
				LuxorConfig::getAnswerFormSimple(
					10, 
					$arrAnswersVarname, 
					array('RESULT_ID'=>$arUser['UF_ID_COMP'])
				);
				$keys[] = $i;
			}
			$i++;
		}
		
		
		$i = 0;
		foreach($arrAnswersVarname as $v){
			$exelAr[$i]['F_NAME'] = $v['SIMPLE_QUESTION_750'][0]['USER_TEXT'].' '.$v['SIMPLE_QUESTION_823'][0]['USER_TEXT'];
			$exelAr[$i]['JOB'] = $v['SIMPLE_QUESTION_391'][0]['USER_TEXT'];
			$exelAr[$i]['PHONE'] = $v['SIMPLE_QUESTION_636'][0]['USER_TEXT'];
			$exelAr[$i]['MAIL'] = $v['SIMPLE_QUESTION_373'][0]['USER_TEXT'];
			$exelAr[$i]['SITE'] = $v['SIMPLE_QUESTION_552'][0]['USER_TEXT'];
			$exelAr[$i]['NAME_COMP']      = $v['SIMPLE_QUESTION_115'][0]['USER_TEXT'];
			$exelAr[$i]['AREA_OF_B']      = $v['SIMPLE_QUESTION_677'][0]['ANSWER_TEXT'];
			$exelAr[$i]['ADRESS_COMP']    = $v['SIMPLE_QUESTION_773'][0]['USER_TEXT'];
			$exelAr[$i]['INDEX']          = $v['SIMPLE_QUESTION_756'][0]['USER_TEXT'];
			$exelAr[$i]['CITY_COMP']      = $v['SIMPLE_QUESTION_672'][0]['USER_TEXT'];
			$exelAr[$i]['DESC_COMP']      = $v['SIMPLE_QUESTION_166'][0]['USER_TEXT'];
			if($v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'] == 'other'){
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_243'][0]['USER_TEXT'];	
			}else{
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'];	
			}
			

			//Вид деятельности
			foreach($v['SIMPLE_QUESTION_677'] as $ar){
				$exelAr[$i]['VID_D'][] = $ar['ANSWER_TEXT'];
			}
			
			//коллега
			if($v['SIMPLE_QUESTION_367'][0]['USER_TEXT'] == 'Имя коллеги'){
				$exelAr[$i]['COLLEGA_NAME']     = '';
			}else{
				$exelAr[$i]['COLLEGA_NAME']     = $v['SIMPLE_QUESTION_367'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_482'][0]['USER_TEXT'] == 'Фамилия коллеги'){
				$exelAr[$i]['COLLEGA_L_NAME']     = '';
			}else{
				$exelAr[$i]['COLLEGA_L_NAME']     = $v['SIMPLE_QUESTION_482'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_187'][0]['USER_TEXT'] == 'Должность коллеги'){
				$exelAr[$i]['COLLEGA_JOB']     = '';
			}else{
				$exelAr[$i]['COLLEGA_JOB']     = $v['SIMPLE_QUESTION_187'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_421'][0]['USER_TEXT'] == 'E-mail коллеги'){
				$exelAr[$i]['COLLEGA_MAIL']     = '';
			}else{
				$exelAr[$i]['COLLEGA_MAIL']     = $v['SIMPLE_QUESTION_421'][0]['USER_TEXT'];
			}
			//коллега 2
			if($v['SIMPLE_QUESTION_225'][0]['USER_TEXT'] == 'Имя коллеги'){
				$exelAr[$i]['COLLEGA_NAME2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_NAME2']     = $v['SIMPLE_QUESTION_225'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_770'][0]['USER_TEXT'] == 'Фамилия коллеги'){
				$exelAr[$i]['COLLEGA_L_NAME2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_L_NAME2']     = $v['SIMPLE_QUESTION_770'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_280'][0]['USER_TEXT'] == 'Должность коллеги'){
				$exelAr[$i]['COLLEGA_JOB2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_JOB2']     = $v['SIMPLE_QUESTION_280'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_384'][0]['USER_TEXT'] == 'E-mail коллеги'){
				$exelAr[$i]['COLLEGA_MAIL2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_MAIL2']     = $v['SIMPLE_QUESTION_384'][0]['USER_TEXT'];
			}
			//коллега 3
			if($v['SIMPLE_QUESTION_765'][0]['USER_TEXT'] == 'Имя коллеги'){
				$exelAr[$i]['COLLEGA_NAME3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_NAME3']     = $v['SIMPLE_QUESTION_765'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_627'][0]['USER_TEXT'] == 'Фамилия коллеги'){
				$exelAr[$i]['COLLEGA_L_NAME3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_L_NAME3']     = $v['SIMPLE_QUESTION_627'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_788'][0]['USER_TEXT'] == 'Должность коллеги'){
				$exelAr[$i]['COLLEGA_JOB3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_JOB3']     = $v['SIMPLE_QUESTION_788'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_230'][0]['USER_TEXT'] == 'E-mail коллеги'){
				$exelAr[$i]['COLLEGA_MAIL3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_MAIL3']     = $v['SIMPLE_QUESTION_230'][0]['USER_TEXT'];
			}
			
			//Приоритетные направления
			//Europe
			foreach($v['SIMPLE_QUESTION_244'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//North America
			foreach($v['SIMPLE_QUESTION_383'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//South America
			foreach($v['SIMPLE_QUESTION_212'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Asia
			foreach($v['SIMPLE_QUESTION_526'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Africa
			foreach($v['SIMPLE_QUESTION_497'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Oceania
			foreach($v['SIMPLE_QUESTION_878'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			
			$exelAr[$i]['PR_NAPR_ALL'] = implode(',', $exelAr[$i]['PR_NAPR']);
			
			$i++;
		}
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
	$aSheet->getColumnDimension('A')->setWidth(12);	
	$aSheet->getColumnDimension('B')->setWidth(35);	
	$aSheet->getColumnDimension('C')->setWidth(50);	
	$aSheet->getColumnDimension('D')->setWidth(60);	
	$aSheet->getColumnDimension('E')->setWidth(30);	
	$aSheet->getColumnDimension('F')->setWidth(30);
	$aSheet->getColumnDimension('G')->setWidth(30);	
	$aSheet->getColumnDimension('H')->setWidth(35);	
	$aSheet->getColumnDimension('I')->setWidth(35);
	$aSheet->getColumnDimension('J')->setWidth(40);
	$aSheet->getColumnDimension('K')->setWidth(40);
	$aSheet->getColumnDimension('L')->setWidth(40);
	$aSheet->getColumnDimension('M')->setWidth(40);
	$aSheet->getColumnDimension('N')->setWidth(40);
	$aSheet->getColumnDimension('O')->setWidth(40);
	$aSheet->getColumnDimension('P')->setWidth(40);
	$aSheet->getColumnDimension('Q')->setWidth(40);
	$aSheet->getColumnDimension('R')->setWidth(40);
	$aSheet->getColumnDimension('S')->setWidth(40);
	$aSheet->getColumnDimension('T')->setWidth(40);
	$aSheet->getColumnDimension('U')->setWidth(40);
	$aSheet->getColumnDimension('V')->setWidth(40);
	$aSheet->getColumnDimension('W')->setWidth(40);
	$aSheet->getColumnDimension('X')->setWidth(40);

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
	$aSheet->setCellValue('B1', iconv('WINDOWS-1251', 'UTF-8', 'Название компании'));
	$aSheet->getStyle('B1')->applyFromArray($baseFont);
	$aSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C1', iconv('WINDOWS-1251', 'UTF-8', 'Вид деятельности'));
	$aSheet->getStyle('C1')->applyFromArray($baseFont);
	$aSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D1', iconv('WINDOWS-1251', 'UTF-8', 'Адрес'));
	$aSheet->getStyle('D1')->applyFromArray($baseFont);
	$aSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E1', iconv('WINDOWS-1251', 'UTF-8', 'Индекс'));
	$aSheet->getStyle('E1')->applyFromArray($baseFont);
	$aSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F1', iconv('WINDOWS-1251', 'UTF-8', 'Город'));
	$aSheet->getStyle('F1')->applyFromArray($baseFont);
	$aSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G1', iconv('WINDOWS-1251', 'UTF-8', "Страна"));
	$aSheet->getStyle('G1')->applyFromArray($baseFont);
	$aSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H1', iconv('WINDOWS-1251', 'UTF-8', 'Имя Фамилия'));
	$aSheet->getStyle('H1')->applyFromArray($baseFont);
	$aSheet->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I1', iconv('WINDOWS-1251', 'UTF-8', "Должность"));
	$aSheet->getStyle('I1')->applyFromArray($baseFont);
	$aSheet->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J1', iconv('WINDOWS-1251', 'UTF-8', "Телефон"));
	$aSheet->getStyle('J1')->applyFromArray($baseFont);
	$aSheet->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K1', iconv('WINDOWS-1251', 'UTF-8', "E-mail"));
	$aSheet->getStyle('K1')->applyFromArray($baseFont);
	$aSheet->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L1', iconv('WINDOWS-1251', 'UTF-8', "Web-site компании"));
	$aSheet->getStyle('L1')->applyFromArray($baseFont);
	$aSheet->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('M1', iconv('WINDOWS-1251', 'UTF-8', "Имя коллеги 1"));
	$aSheet->getStyle('M1')->applyFromArray($baseFont);
	$aSheet->getStyle('M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('N1', iconv('WINDOWS-1251', 'UTF-8', "Фамилия коллеги 1"));
	$aSheet->getStyle('N1')->applyFromArray($baseFont);
	$aSheet->getStyle('N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('O1', iconv('WINDOWS-1251', 'UTF-8', "Должность коллеги 1"));
	$aSheet->getStyle('O1')->applyFromArray($baseFont);
	$aSheet->getStyle('O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('P1', iconv('WINDOWS-1251', 'UTF-8', "E-mail коллеги 1"));
	$aSheet->getStyle('P1')->applyFromArray($baseFont);
	$aSheet->getStyle('P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('Q1', iconv('WINDOWS-1251', 'UTF-8', "Имя коллеги 2"));
	$aSheet->getStyle('Q1')->applyFromArray($baseFont);
	$aSheet->getStyle('Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('R1', iconv('WINDOWS-1251', 'UTF-8', "Фамилия коллеги 2"));
	$aSheet->getStyle('R1')->applyFromArray($baseFont);
	$aSheet->getStyle('R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('S1', iconv('WINDOWS-1251', 'UTF-8', "Должность коллеги 2"));
	$aSheet->getStyle('S1')->applyFromArray($baseFont);
	$aSheet->getStyle('S1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('T1', iconv('WINDOWS-1251', 'UTF-8', "E-mail коллеги 2"));
	$aSheet->getStyle('T1')->applyFromArray($baseFont);
	$aSheet->getStyle('T1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('U1', iconv('WINDOWS-1251', 'UTF-8', "Имя коллеги 3"));
	$aSheet->getStyle('U1')->applyFromArray($baseFont);
	$aSheet->getStyle('U1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('V1', iconv('WINDOWS-1251', 'UTF-8', "Фамилия коллеги 3"));
	$aSheet->getStyle('V1')->applyFromArray($baseFont);
	$aSheet->getStyle('V1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('W1', iconv('WINDOWS-1251', 'UTF-8', "Должность коллеги 3"));
	$aSheet->getStyle('W1')->applyFromArray($baseFont);
	$aSheet->getStyle('W1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('X1', iconv('WINDOWS-1251', 'UTF-8', "E-mail коллеги 3"));
	$aSheet->getStyle('X1')->applyFromArray($baseFont);
	$aSheet->getStyle('X1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	

	$str = 1;
	foreach($exelAr as $key=>$mas){
		$str++;
		$aSheet->setCellValue('A'.$str, $mas['ID']);
		$aSheet->setCellValue('B'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['NAME_COMP']));
		if(count($mas['VID_D']) > 0){
			$aSheet->setCellValue('C'.$str, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
		}else{
			$aSheet->setCellValue('C'.$str, '');
		}
		$aSheet->setCellValue('D'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['ADRESS_COMP']));
		$aSheet->setCellValue('E'.$str, $mas['INDEX']);
		$aSheet->setCellValue('F'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['CITY_COMP']));
		$aSheet->setCellValue('G'.$str, $mas['COUNTRY_COMP']);
		$aSheet->setCellValue('H'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['F_NAME']));
		$aSheet->setCellValue('I'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['JOB']));
		$aSheet->setCellValue('J'.$str, $mas['PHONE']);
		$aSheet->setCellValue('K'.$str, $mas['MAIL']);
		$aSheet->setCellValue('L'.$str, $mas['SITE']);
		$aSheet->setCellValue('M'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_NAME']));
		$aSheet->setCellValue('N'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_L_NAME']));
		$aSheet->setCellValue('O'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_JOB']));
		$aSheet->setCellValue('P'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_MAIL']));
		$aSheet->setCellValue('Q'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_NAME2']));
		$aSheet->setCellValue('R'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_L_NAME2']));
		$aSheet->setCellValue('S'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_JOB2']));
		$aSheet->setCellValue('T'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_MAIL2']));
		$aSheet->setCellValue('U'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_NAME3']));
		$aSheet->setCellValue('V'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_L_NAME3']));
		$aSheet->setCellValue('W'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_JOB3']));
		$aSheet->setCellValue('X'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_MAIL3']));

	}

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Participants_excel');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Гости Алматы неподтвержденные.xls"');
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
}elseif($type == 'guests_inact_all'){
	if(CModule::IncludeModule("iblock") && CModule::IncludeModule("form")){
		//Все пользователи группы "Гости Алматы неподтвержденные" на утро
		$userExelAr = array();
		$exExelAr = array();
		$exelAr = array();
		$filter = Array("GROUPS_ID"   => LuxorConfig::GROUP_GUEST_K, '!UF_ID4'=>'false'); 
		$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_ID_COMP", "UF_PAS", 'UF_ID2', 'UF_ID3', 'UF_EV')));
		$i = 0;
		while ($arUser = $rsUsers->Fetch()){
			$exelAr[$i]['ID']       = $arUser['ID'];
			$exelAr[$i]['LOGIN']    = $arUser['LOGIN'];
			$exelAr[$i]['PASSWORD'] = LuxorConfig::returnPas($arUser['UF_PAS']);

			//результат формы "Участники данные компании ВСЕ ВЫСТАВКИ"
			if($arUser['UF_ID_COMP'] != ''){
				LuxorConfig::getAnswerFormSimple(
					10, 
					$arrAnswersVarname, 
					array('RESULT_ID'=>$arUser['UF_ID_COMP'])
				);
				$keys[] = $i;
			}
			$i++;
		}
		
		$i = 0;
		foreach($arrAnswersVarname as $v){
			$exelAr[$i]['F_NAME'] = $v['SIMPLE_QUESTION_750'][0]['USER_TEXT'].' '.$v['SIMPLE_QUESTION_823'][0]['USER_TEXT'];
			$exelAr[$i]['JOB'] = $v['SIMPLE_QUESTION_391'][0]['USER_TEXT'];
			$exelAr[$i]['PHONE'] = $v['SIMPLE_QUESTION_636'][0]['USER_TEXT'];
			$exelAr[$i]['MAIL'] = $v['SIMPLE_QUESTION_373'][0]['USER_TEXT'];
			$exelAr[$i]['SITE'] = $v['SIMPLE_QUESTION_552'][0]['USER_TEXT'];
			$exelAr[$i]['NAME_COMP']      = $v['SIMPLE_QUESTION_115'][0]['USER_TEXT'];
			$exelAr[$i]['AREA_OF_B']      = $v['SIMPLE_QUESTION_677'][0]['ANSWER_TEXT'];
			$exelAr[$i]['ADRESS_COMP']    = $v['SIMPLE_QUESTION_773'][0]['USER_TEXT'];
			$exelAr[$i]['INDEX']          = $v['SIMPLE_QUESTION_756'][0]['USER_TEXT'];
			$exelAr[$i]['CITY_COMP']      = $v['SIMPLE_QUESTION_672'][0]['USER_TEXT'];
			$exelAr[$i]['DESC_COMP']      = $v['SIMPLE_QUESTION_166'][0]['USER_TEXT'];
			if($v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'] == 'other'){
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_243'][0]['USER_TEXT'];	
			}else{
				$exelAr[$i]['COUNTRY_COMP']   = $v['SIMPLE_QUESTION_678'][0]['ANSWER_TEXT'];	
			}
			

			//Вид деятельности
			foreach($v['SIMPLE_QUESTION_677'] as $ar){
				$exelAr[$i]['VID_D'][] = $ar['ANSWER_TEXT'];
			}
			
			//коллега
			if($v['SIMPLE_QUESTION_367'][0]['USER_TEXT'] == 'Имя коллеги'){
				$exelAr[$i]['COLLEGA_NAME']     = '';
			}else{
				$exelAr[$i]['COLLEGA_NAME']     = $v['SIMPLE_QUESTION_367'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_482'][0]['USER_TEXT'] == 'Фамилия коллеги'){
				$exelAr[$i]['COLLEGA_L_NAME']     = '';
			}else{
				$exelAr[$i]['COLLEGA_L_NAME']     = $v['SIMPLE_QUESTION_482'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_187'][0]['USER_TEXT'] == 'Должность коллеги'){
				$exelAr[$i]['COLLEGA_JOB']     = '';
			}else{
				$exelAr[$i]['COLLEGA_JOB']     = $v['SIMPLE_QUESTION_187'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_421'][0]['USER_TEXT'] == 'E-mail коллеги'){
				$exelAr[$i]['COLLEGA_MAIL']     = '';
			}else{
				$exelAr[$i]['COLLEGA_MAIL']     = $v['SIMPLE_QUESTION_421'][0]['USER_TEXT'];
			}
			//коллега 2
			if($v['SIMPLE_QUESTION_225'][0]['USER_TEXT'] == 'Имя коллеги'){
				$exelAr[$i]['COLLEGA_NAME2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_NAME2']     = $v['SIMPLE_QUESTION_225'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_770'][0]['USER_TEXT'] == 'Фамилия коллеги'){
				$exelAr[$i]['COLLEGA_L_NAME2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_L_NAME2']     = $v['SIMPLE_QUESTION_770'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_280'][0]['USER_TEXT'] == 'Должность коллеги'){
				$exelAr[$i]['COLLEGA_JOB2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_JOB2']     = $v['SIMPLE_QUESTION_280'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_384'][0]['USER_TEXT'] == 'E-mail коллеги'){
				$exelAr[$i]['COLLEGA_MAIL2']     = '';
			}else{
				$exelAr[$i]['COLLEGA_MAIL2']     = $v['SIMPLE_QUESTION_384'][0]['USER_TEXT'];
			}
			//коллега 3
			if($v['SIMPLE_QUESTION_765'][0]['USER_TEXT'] == 'Имя коллеги'){
				$exelAr[$i]['COLLEGA_NAME3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_NAME3']     = $v['SIMPLE_QUESTION_765'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_627'][0]['USER_TEXT'] == 'Фамилия коллеги'){
				$exelAr[$i]['COLLEGA_L_NAME3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_L_NAME3']     = $v['SIMPLE_QUESTION_627'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_788'][0]['USER_TEXT'] == 'Должность коллеги'){
				$exelAr[$i]['COLLEGA_JOB3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_JOB3']     = $v['SIMPLE_QUESTION_788'][0]['USER_TEXT'];
			}
			if($v['SIMPLE_QUESTION_230'][0]['USER_TEXT'] == 'E-mail коллеги'){
				$exelAr[$i]['COLLEGA_MAIL3']     = '';
			}else{
				$exelAr[$i]['COLLEGA_MAIL3']     = $v['SIMPLE_QUESTION_230'][0]['USER_TEXT'];
			}
			
			//Приоритетные направления
			//Europe
			foreach($v['SIMPLE_QUESTION_244'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//North America
			foreach($v['SIMPLE_QUESTION_383'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//South America
			foreach($v['SIMPLE_QUESTION_212'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Asia
			foreach($v['SIMPLE_QUESTION_526'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Africa
			foreach($v['SIMPLE_QUESTION_497'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			//Oceania
			foreach($v['SIMPLE_QUESTION_878'] as $ar){
				$exelAr[$i]['PR_NAPR'][] = $ar['ANSWER_TEXT'];
			}
			
			$exelAr[$i]['PR_NAPR_ALL'] = implode(',', $exelAr[$i]['PR_NAPR']);
			
			$i++;
		}
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
	$aSheet->getColumnDimension('A')->setWidth(12);	
	$aSheet->getColumnDimension('B')->setWidth(35);	
	$aSheet->getColumnDimension('C')->setWidth(50);	
	$aSheet->getColumnDimension('D')->setWidth(60);	
	$aSheet->getColumnDimension('E')->setWidth(30);	
	$aSheet->getColumnDimension('F')->setWidth(30);
	$aSheet->getColumnDimension('G')->setWidth(30);	
	$aSheet->getColumnDimension('H')->setWidth(35);	
	$aSheet->getColumnDimension('I')->setWidth(35);
	$aSheet->getColumnDimension('J')->setWidth(40);
	$aSheet->getColumnDimension('K')->setWidth(40);
	$aSheet->getColumnDimension('L')->setWidth(40);

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
	$aSheet->setCellValue('B1', iconv('WINDOWS-1251', 'UTF-8', 'Название компании'));
	$aSheet->getStyle('B1')->applyFromArray($baseFont);
	$aSheet->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('C1', iconv('WINDOWS-1251', 'UTF-8', 'Вид деятельности'));
	$aSheet->getStyle('C1')->applyFromArray($baseFont);
	$aSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('D1', iconv('WINDOWS-1251', 'UTF-8', 'Адрес'));
	$aSheet->getStyle('D1')->applyFromArray($baseFont);
	$aSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('E1', iconv('WINDOWS-1251', 'UTF-8', 'Индекс'));
	$aSheet->getStyle('E1')->applyFromArray($baseFont);
	$aSheet->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('F1', iconv('WINDOWS-1251', 'UTF-8', 'Город'));
	$aSheet->getStyle('F1')->applyFromArray($baseFont);
	$aSheet->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('G1', iconv('WINDOWS-1251', 'UTF-8', "Страна"));
	$aSheet->getStyle('G1')->applyFromArray($baseFont);
	$aSheet->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('H1', iconv('WINDOWS-1251', 'UTF-8', 'Имя Фамилия'));
	$aSheet->getStyle('H1')->applyFromArray($baseFont);
	$aSheet->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('I1', iconv('WINDOWS-1251', 'UTF-8', "Должность"));
	$aSheet->getStyle('I1')->applyFromArray($baseFont);
	$aSheet->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('J1', iconv('WINDOWS-1251', 'UTF-8', "Телефон"));
	$aSheet->getStyle('J1')->applyFromArray($baseFont);
	$aSheet->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('K1', iconv('WINDOWS-1251', 'UTF-8', "E-mail"));
	$aSheet->getStyle('K1')->applyFromArray($baseFont);
	$aSheet->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$aSheet->setCellValue('L1', iconv('WINDOWS-1251', 'UTF-8', "Web-site компании"));
	$aSheet->getStyle('L1')->applyFromArray($baseFont);
	$aSheet->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	

	$str = 0;
	$str1 = 1;
	$str2 = 2;
	$str3 = 3;
	foreach($exelAr as $key=>$mas){
		$str +=3;
		$str1 +=3;
		$str2 +=3;
		$str3 +=3;
		$aSheet->setCellValue('A'.$str, $mas['ID']);
		$aSheet->setCellValue('B'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['NAME_COMP']));
		if(count($mas['VID_D']) > 0){
			$aSheet->setCellValue('C'.$str, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
		}else{
			$aSheet->setCellValue('C'.$str, '');
		}
		$aSheet->setCellValue('D'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['ADRESS_COMP']));
		$aSheet->setCellValue('E'.$str, $mas['INDEX']);
		$aSheet->setCellValue('F'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['CITY_COMP']));
		$aSheet->setCellValue('G'.$str, $mas['COUNTRY_COMP']);
		$aSheet->setCellValue('H'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['F_NAME']));
		$aSheet->setCellValue('I'.$str, iconv('WINDOWS-1251', 'UTF-8', $mas['JOB']));
		$aSheet->setCellValue('J'.$str, $mas['PHONE']);
		$aSheet->setCellValue('K'.$str, $mas['MAIL']);
		$aSheet->setCellValue('L'.$str, $mas['SITE']);
		if($mas['COLLEGA_NAME'] != ''){
			$aSheet->setCellValue('A'.$str1, $mas['ID']);
			$aSheet->setCellValue('B'.$str1, iconv('WINDOWS-1251', 'UTF-8', $mas['NAME_COMP']));
			if(count($mas['VID_D']) > 0){
				$aSheet->setCellValue('C'.$str1, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
			}else{
				$aSheet->setCellValue('C'.$str1, '');
			}
			$aSheet->setCellValue('D'.$str1, iconv('WINDOWS-1251', 'UTF-8', $mas['ADRESS_COMP']));
			$aSheet->setCellValue('E'.$str1, $mas['INDEX']);
			$aSheet->setCellValue('F'.$str1, iconv('WINDOWS-1251', 'UTF-8', $mas['CITY_COMP']));
			$aSheet->setCellValue('G'.$str1, $mas['COUNTRY_COMP']);
			$aSheet->setCellValue('H'.$str1, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_NAME'].' '.$mas['COLLEGA_L_NAME']));
			$aSheet->setCellValue('I'.$str1, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_JOB']));
			$aSheet->setCellValue('J'.$str1, $mas['PHONE']);
			$aSheet->setCellValue('K'.$str1, $mas['COLLEGA_MAIL']);
			$aSheet->setCellValue('L'.$str1, $mas['SITE']);
		}
		
		if($mas['COLLEGA_NAME2'] != ''){
			$aSheet->setCellValue('A'.$str2, $mas['ID']);
			$aSheet->setCellValue('B'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['NAME_COMP']));
			if(count($mas['VID_D']) > 0){
				$aSheet->setCellValue('C'.$str2, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
			}else{
				$aSheet->setCellValue('C'.$str2, '');
			}
			$aSheet->setCellValue('D'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['ADRESS_COMP']));
			$aSheet->setCellValue('E'.$str2, $mas['INDEX']);
			$aSheet->setCellValue('F'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['CITY_COMP']));
			$aSheet->setCellValue('G'.$str2, $mas['COUNTRY_COMP']);
			$aSheet->setCellValue('H'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_NAME2'].' '.$mas['COLLEGA_L_NAME2']));
			$aSheet->setCellValue('I'.$str2, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_JOB2']));
			$aSheet->setCellValue('J'.$str2, $mas['PHONE']);
			$aSheet->setCellValue('K'.$str2, $mas['COLLEGA_MAIL2']);
			$aSheet->setCellValue('L'.$str2, $mas['SITE']);
		}
		if($mas['COLLEGA_NAME3'] != ''){
			$aSheet->setCellValue('A'.$str3, $mas['ID']);
			$aSheet->setCellValue('B'.$str3, iconv('WINDOWS-1251', 'UTF-8', $mas['NAME_COMP']));
			if(count($mas['VID_D']) > 0){
				$aSheet->setCellValue('C'.$str3, iconv('WINDOWS-1251', 'UTF-8', implode(',', $mas['VID_D'])));
			}else{
				$aSheet->setCellValue('C'.$str3, '');
			}
			$aSheet->setCellValue('D'.$str3, iconv('WINDOWS-1251', 'UTF-8', $mas['ADRESS_COMP']));
			$aSheet->setCellValue('E'.$str3, $mas['INDEX']);
			$aSheet->setCellValue('F'.$str3, iconv('WINDOWS-1251', 'UTF-8', $mas['CITY_COMP']));
			$aSheet->setCellValue('G'.$str3, $mas['COUNTRY_COMP']);
			$aSheet->setCellValue('H'.$str3, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_NAME3'].' '.$mas['COLLEGA_L_NAME3']));
			$aSheet->setCellValue('I'.$str3, iconv('WINDOWS-1251', 'UTF-8', $mas['COLLEGA_JOB3']));
			$aSheet->setCellValue('J'.$str3, $mas['PHONE']);
			$aSheet->setCellValue('K'.$str3, $mas['COLLEGA_MAIL3']);
			$aSheet->setCellValue('L'.$str3, $mas['SITE']);
		}
	}

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Participants_excel');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="Гости Алматы неподтвержденные (все люди).xls"');
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