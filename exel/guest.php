<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
//all-luxury.ru/exel/indexNewGuest.php?type=guests&app=moscow-russia-march-12-2015
if (!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form")) {
	ShowError("404 Not Found");
	@define("ERROR_404", "Y");
	CHTTP::SetStatus("404 Not Found");
}

$arParams["TYPE"] = strip_tags($_REQUEST['type']);
$arParams["APP_CODE"] = strip_tags($_REQUEST['app']);

$arResult = array();

/* �������� ������ �� �� ��������*/
if(isset($arParams["APP_CODE"]) && $arParams["APP_CODE"]!=''){
	$rsExhib = CIBlockElement::GetList(
			array(),
			array(
					"IBLOCK_ID" => "15",
					"CODE" => $arParams["APP_CODE"]
				),
			false,
			false,
			array("ID", "CODE","IBLOCK_ID", "NAME","PROPERTY_APP_ID", "PROPERTY_APP_HB_ID",
				"PROPERTY_C_GUESTS_GROUP", "PROPERTY_UC_GUESTS_GROUP")
			);
	while($oExhib = $rsExhib->GetNextElement(true, false))
	{
		$arResult["PARAM_EXHIBITION"] = $oExhib->GetFields();
		$arResult["PARAM_EXHIBITION"]["PROPERTIES"] = $oExhib->GetProperties();
	}
}

$fileName = $arResult["PARAM_EXHIBITION"]["NAME"]; // �������� ����� == �������� �������� + �������� ��� ���

$filter = array(); //������ � ������ ��� ���������� ������ �������������
$isAll = false; //������� ��������� �������
$isEvening = false; //����� �� �����
$isHB = false; //����� HB

$formId = CFormMatrix::getGResultIDByExh($arResult["PARAM_EXHIBITION"]["ID"]); //Id �����

//���� � id �����������
$resultAllCode = "UF_ID_COMP";//���� � ID ����� � ��������
$resAllComp = array(); //������ ����������� � ��������

if($arParams["TYPE"] == 'guests'){
	$filter["GROUPS_ID"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["C_GUESTS_GROUP"]["VALUE"];
	$filter["UF_MR"] = 1;
	$fileName = "����� ".$fileName." ��������������.xls";
}
elseif($arParams["TYPE"] == 'guests_all'){
	$filter["GROUPS_ID"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["C_GUESTS_GROUP"]["VALUE"];
	$filter["UF_MR"] = 1;
	$isAll = true;	
	$fileName = "����� ".$fileName." �������������� (������� ��������).xls";
}
elseif($arParams["TYPE"] == 'guests_hb'){
	$filter["GROUPS_ID"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["C_GUESTS_GROUP"]["VALUE"];
	$filter["UF_HB"] = 1;
	$fileName = "����� ".$fileName." HB.xls";
	$isHB = true;

}
elseif($arParams["TYPE"] == 'guests_hb_all'){
	$filter["GROUPS_ID"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["C_GUESTS_GROUP"]["VALUE"];
	$filter["UF_HB"] = 1;
	$isAll = true;
	$fileName = "����� ".$fileName." HB (������� ��������).xls";
	$isHB = true;

}
elseif($arParams["TYPE"] == 'guests_ev'){
	$filter["GROUPS_ID"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["C_GUESTS_GROUP"]["VALUE"];
	$filter["UF_EV"] = 1;
	$fileName = "����� ".$fileName." �����";
	$isEvening = true;

}
elseif($arParams["TYPE"] == 'guests_ev_all'){
	$filter["GROUPS_ID"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["C_GUESTS_GROUP"]["VALUE"];
	$filter["UF_EV"] = 1;
	$fileName = "����� ".$fileName." ����� (������� ��������)";
	$isAll = true;
	$isEvening = true;
}
elseif($arParams["TYPE"] == 'guests_no'){
	$filter["GROUPS_ID"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["UC_GUESTS_GROUP"]["VALUE"];
	$fileName = "����� ".$fileName." ����������������.xls";
	$isEvening = true;
}
elseif($arParams["TYPE"] == 'guests_no_all'){
	$filter["GROUPS_ID"] = $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["UC_GUESTS_GROUP"]["VALUE"];
	$fileName = "����� ".$fileName." ���������������� (������� ��������).xls";
	$isAll = true;
	$isEvening = true;
}
else{
	echo 'Oops, we are not found this type.';
} 

$rsUsers = CUser::GetList(($by="id"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"), "FIELDS"=>array("ID","LOGIN")));
$i = 0;
$arTmpUsers = array();
while ($arUser = $rsUsers->Fetch()){
	$arTmpUsers[$i]['ID']       = $arUser['ID'];
	$arTmpUsers[$i]['LOGIN']    = $arUser['LOGIN'];
	$arTmpUsers[$i]['PASSWORD'] = LuxorConfig::returnPas($arUser['UF_PAS']);
	$arTmpUsers[$i]['FORM_COMP'] = $arUser[$resultAllCode];

	//������ �� ���� ��������
	$resAllComp[] = $arUser[$resultAllCode];
	$i++;
}

//��������� ������� ����� �����
$arTmpResult = array("QUESTIONS"=>array(), "ANSWERS"=>array(), "ANSWERS2"=>array());

CForm::GetResultAnswerArray(
    $formId,
    $arTmpResult["QUESTIONS"],
    $arTmpResult["ANSWERS"],
    $arTmpResult["ANSWERS2"],
    array("RESULT_ID" => implode("|", $resAllComp))
);

$arResult["ANSWERS"] = array();
$j=0;
if($isEvening)//�������� ������ �� ����� ��� Exel �� �����������
	$arCompField = CFormMatrix::$arExelEvGuestField;
else
	$arCompField = CFormMatrix::$arExelGuestField;

foreach ($arTmpUsers as $arUser) {
	/*������ �� ������������*/
	$arResult["ANSWERS"][$j]["ID"] = $arUser['ID'];
	if($isEvening){
		$arResult["ANSWERS"][$j]["LOGIN"] = $arUser['LOGIN'];
		$arResult["ANSWERS"][$j]["PASSWORD"] = $arUser['PASSWORD'];
	}
	/* ������ � �������� */
	if(!$isAll){
		foreach ($arCompField["QUEST_CODE"] as $idQuest => $codeRes){
			//��� ��� �� ����� ������, �� ���� SQL ���� ��� ���-�� ���������
			/*if($arCompField["NAMES_AR"][$idQuest] == "HALL" && $arTmpResult["ANSWERS2"][$arUser['FORM_COMP']][$codeRes]["0"][$arCompField["ANS_TYPE"][$idQuest]] == 'Mr.'){
				$arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]] = '';
			}*/
			
			if(strpos($arCompField["NAMES"][$idQuest], '(other)') !== false){
				if($arTmpResult["ANSWERS2"][$arUser['FORM_COMP']][$codeRes]["0"][$arCompField["ANS_TYPE"][$idQuest]] != ''){
					$arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]] = $arTmpResult["ANSWERS2"][$arUser['FORM_COMP']][$codeRes]["0"][$arCompField["ANS_TYPE"][$idQuest]];
				}
			}
			elseif(!$isHB && ($arCompField["NAMES_AR"][$idQuest] == 'HALL' || $arCompField["NAMES_AR"][$idQuest] == 'TABLE')){
				continue;
			}
			elseif($arCompField["NAMES_AR"][$idQuest] != "DESTINITIONS" && $arCompField["NAMES_AR"][$idQuest] != "AREA"){
				$arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]] = $arTmpResult["ANSWERS2"][$arUser['FORM_COMP']][$codeRes]["0"][$arCompField["ANS_TYPE"][$idQuest]];
			}
			else{
				if(!isset($arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]])){
					$arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]] == array();
				}
				foreach ($arTmpResult["ANSWERS2"][$arUser['FORM_COMP']][$codeRes] as $dest) {
					$arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]][] = $dest[$arCompField["ANS_TYPE"][$idQuest]];
				}
			}		
		}
		if(!$isEvening){
			$arResult["ANSWERS"][$j]["LOGIN"] = $arUser['LOGIN'];
			$arResult["ANSWERS"][$j]["PASSWORD"] = $arUser['PASSWORD'];
		}
	}
	else{//���� ��� �������������, �� + ������� ��������� �������
		$isCollege = false;
		foreach ($arCompField["QUEST_CODE"] as $idQuest => $codeRes){
			if(strpos($arCompField["NAMES"][$idQuest], '(�� ����)') !== false || strpos($arCompField["NAMES"][$idQuest], ' ������� ') !== false){
				if($arTmpResult["ANSWERS2"][$arUser['FORM_COMP']][$codeRes]["0"][$arCompField["ANS_TYPE"][$idQuest]] != ''){
					$isCollege = $isCollege || true;
				}
			}
			elseif(strpos($arCompField["NAMES"][$idQuest], '(other)') !== false){
				if($arTmpResult["ANSWERS2"][$arUser['FORM_COMP']][$codeRes]["0"][$arCompField["ANS_TYPE"][$idQuest]] != ''){
					$arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]] = $arTmpResult["ANSWERS2"][$arUser['FORM_COMP']][$codeRes]["0"][$arCompField["ANS_TYPE"][$idQuest]];
				}
			}
			elseif(!$isHB && ($arCompField["NAMES_AR"][$idQuest] == 'HALL' || $arCompField["NAMES_AR"][$idQuest] == 'TABLE')){
				continue;
			}
			elseif($arCompField["NAMES_AR"][$idQuest] != "DESTINITIONS" && $arCompField["NAMES_AR"][$idQuest] != "AREA"){
				$arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]] = $arTmpResult["ANSWERS2"][$arUser['FORM_COMP']][$codeRes]["0"][$arCompField["ANS_TYPE"][$idQuest]];
			}

			else{
				if(!isset($arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]])){
					$arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]] == array();
				}
				foreach ($arTmpResult["ANSWERS2"][$arUser['FORM_COMP']][$codeRes] as $dest) {
					$arResult["ANSWERS"][$j][$arCompField["NAMES_AR"][$idQuest]][] = $dest[$arCompField["ANS_TYPE"][$idQuest]];
				}				
			}
		}
		if(!$isEvening){
			$arResult["ANSWERS"][$j]["LOGIN"] = $arUser['LOGIN'];
			$arResult["ANSWERS"][$j]["PASSWORD"] = $arUser['PASSWORD'];
		}
		if($isCollege){
			if($isEvening){
				$numberCol = 0;
				$firstFieldRes = 0;
				$firstFieldId = 0;
				foreach ($arCompField["QUEST_CODE"] as $idQuest => $codeRes){
					if(strpos($arCompField["NAMES"][$idQuest], ' ������� ') !== false){//�������� ��, ��� ����������
						$numderColTmp = substr($arCompField["NAMES_AR"][$idQuest], -1);
						if($numberCol != $numderColTmp && $arTmpResult["ANSWERS2"][$arUser['FORM_COMP']][$codeRes]["0"][$arCompField["ANS_TYPE"][$idQuest]] != ""){
							$numberCol = $numderColTmp;
							$firstFieldRes = $codeRes;
							$firstFieldId = $idQuest;
							if(!isset($arResult["ANSWERS"][$j+$numberCol]))
								$arResult["ANSWERS"][$j+$numberCol]=$arResult["ANSWERS"][$j];//����������� ��� ����� ����������
						}
						elseif($numberCol != $numderColTmp){
							$firstFieldRes = 0;
						}
						if($firstFieldRes){
							$arResult["ANSWERS"][$j+$numberCol][str_replace ("_COL".$numberCol, "", $arCompField["NAMES_AR"][$idQuest])] = $arTmpResult["ANSWERS2"][$arUser['FORM_COMP']][$codeRes]["0"][$arCompField["ANS_TYPE"][$idQuest]];					
						}
					}		
				}			
				$j += $numberCol;	
			}
			else{
				$arResult["ANSWERS"][$j+1]=$arResult["ANSWERS"][$j];//����������� ��� ����� ����������
				$j++;
				foreach ($arCompField["QUEST_CODE"] as $idQuest => $codeRes){
					if(strpos($arCompField["NAMES"][$idQuest], '(�� ����)') !== false){//�������� ��, ��� ����������
						$arResult["ANSWERS"][$j][str_replace ("_COL", "", $arCompField["NAMES_AR"][$idQuest])] = $arTmpResult["ANSWERS2"][$arUser['FORM_COMP']][$codeRes]["0"][$arCompField["ANS_TYPE"][$idQuest]];
					}		
				}				
			}

		}
	}	
	$j++;	
}

$arResultTitles = array("uId");
if($isEvening){
	$arResultTitles[] ="Login";
	$arResultTitles[] ="Password";
}
$isDestinationsWrite = false;
foreach($arCompField["QUEST_CODE"] as $idQuest => $codeRes){
	if($arCompField["NAMES_AR"][$idQuest] == 'DESTINITIONS'){
		if($isDestinationsWrite){
			continue;
		}
		$isDestinationsWrite = true;
		$arResultTitles[] = $arCompField["NAMES"][$idQuest];
	}
 	elseif($isAll && (strpos($arCompField["NAMES"][$idQuest], '(�� ����)') !== false || strpos($arCompField["NAMES"][$idQuest], ' ������� ') !== false)){//���� � ��� ��� �������������, �� ������� ��������� �������
		continue;
	}
	elseif(strpos($arCompField["NAMES"][$idQuest], '(other)') !== false){
		continue;
	}
	elseif(!$isHB && ($arCompField["NAMES_AR"][$idQuest] == 'HALL' || $arCompField["NAMES_AR"][$idQuest] == 'TABLE')){
		continue;
	}
	else{//���� � ��� ��� �������������, �� ������� ��������� �������
		$arResultTitles[] = $arCompField["NAMES"][$idQuest];
	}
}
if(!$isEvening){
	$arResultTitles[] ="Login";
	$arResultTitles[] ="Password";
}

ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

require_once 'PHPExcel.php';

unset($arTmpResult["QUESTIONS"]);
unset($arTmpResult["ANSWERS"]);
unset($arTmpResult["ANSWERS2"]);
unset($arCompField);


// ���������
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '1MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

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

/*������������� ������ ������� (������ ����, ����� �������������)*/
$aSheet->getColumnDimension('A')->setWidth(7);	//uId
$aSheet->getColumnDimension('B')->setWidth(18);	//Login
$aSheet->getColumnDimension('C')->setWidth(18);	//Password
$aSheet->getColumnDimension('D')->setWidth(35);	//��������
$aSheet->getColumnDimension('E')->setWidth(20);	//��� ������������
$aSheet->getColumnDimension('F')->setWidth(40); //�����
$aSheet->getColumnDimension('G')->setWidth(10);	//������
$aSheet->getColumnDimension('H')->setWidth(20);	//�����
$aSheet->getColumnDimension('I')->setWidth(15); //������
$aSheet->getColumnDimension('J')->setWidth(20); //���
$aSheet->getColumnDimension('K')->setWidth(25); //�������
$aSheet->getColumnDimension('L')->setWidth(25); //���������
$aSheet->getColumnDimension('M')->setWidth(25); //�������
$aSheet->getColumnDimension('N')->setWidth(25); //��� �������
$aSheet->getColumnDimension('O')->setWidth(35); //E-mail
$aSheet->getColumnDimension('P')->setWidth(35); //����
$aSheet->getColumnDimension('Q')->setWidth(70); //������������ �����������
$aSheet->getColumnDimension('R')->setWidth(70); //�������� ��������
if(!$isAll || strpos($arRepField["NAMES"][$idQuest], '(�� ����)') === false){
	$aSheet->getColumnDimension('S')->setWidth(20); //��� ������� (�� ����)
	$aSheet->getColumnDimension('T')->setWidth(25); //������� ������� (�� ����)
	$aSheet->getColumnDimension('U')->setWidth(25); //��������� ������� (�� ����)
	$aSheet->getColumnDimension('V')->setWidth(35); //E-mail ������� (�� ����)
}

/* ����� ������� */
$row_count = 1;
$col_count = 0;
foreach($arResultTitles as $idQuest => $codeRes){
 	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, iconv('WINDOWS-1251', 'UTF-8', $codeRes));
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col_count, $row_count)->applyFromArray($baseFont);
	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col_count, $row_count)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $col_count++;
}
$row_count++;
unset($arResultTitles);

/* ����� ������ */
foreach ($arResult["ANSWERS"] as $numb => $ans) {
	$col_count = 0;	
	foreach($ans as $userAns){
		if(is_array($userAns)){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, implode(', ', $userAns));		
		}
		elseif($userAns == ''){
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, iconv('WINDOWS-1251', 'UTF-8', '  '));
		}
		else{
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, iconv('WINDOWS-1251', 'UTF-8', $userAns));
		}
	 	$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col_count, $row_count)->applyFromArray($baseFont);
	    $col_count++;
	}
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col_count, $row_count, iconv('WINDOWS-1251', 'UTF-8', '  '));
	$row_count++;
	unset($arResult["ANSWERS"][$numb]);
}

$objPHPExcel->getActiveSheet()->setTitle('Guest_excel');
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client�s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$fileName.'"');
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
//echo "<pre>"; print_r($arResult["ANSWERS"]); echo "</pre>";
?>