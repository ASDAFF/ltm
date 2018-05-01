<?
/** Error reporting */
error_reporting(E_ALL);
session_start();

date_default_timezone_set('Europe/Moscow');

/*echo "<pre>";
print_r($_SESSION["ordnung"]);
echo "</pre>";
$strL = strlen($_SESSION["ordnung"][6]["TeileNr"])."sdf ";
echo '=TEXT('.$_SESSION["ordnung"][6]["TeileNr"].', "'.str_repeat("0",$strL).'")';
*/
/** Include PHPExcel */
require_once $_SERVER["DOCUMENT_ROOT"].'/excel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Nauka-Auto.ru")
							 ->setLastModifiedBy("Nauka-Auto.ru")
							 ->setTitle("Bill")
							 ->setSubject("Bill")
							 ->setDescription("Bill")
							 ->setKeywords("Bill")
							 ->setCategory("Bill");
$objPHPExcel->setActiveSheetIndex(0);




$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setShowGridLines(false);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(2);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(7);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(11);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(14.2);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15.5);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(32);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12.3);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);

$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(15.5);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(18);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(18);
$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(18);

for ($i=5; $i<=12; $i++){
	$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(15.5);
}

$objPHPExcel->getActiveSheet()->getRowDimension('13')->setRowHeight(26);

for ($i=14; $i<=500; $i++){
	$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(15.5);
}


$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(11); 


$objPHPExcel->getActiveSheet()->getStyle('B2:K2')->applyFromArray(
		array(
			'borders' => array(
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE),
			),
		)
	);

$objPHPExcel->getActiveSheet()->getStyle('B2:K2')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B2:K2')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('B2:K2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2:K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
$objPHPExcel->getActiveSheet()->mergeCells('B2:E2');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Заказ покупателя №');

$objPHPExcel->getActiveSheet()->setCellValue('F2', 'ЗПК');
$objPHPExcel->getActiveSheet()->setCellValue('G2', 'от');

$objPHPExcel->getActiveSheet()->mergeCells('H2:K2');
$objPHPExcel->getActiveSheet()->setCellValue('H2', iconv("cp1251","UTF-8",$_SESSION["order_user_prepare"]["ZAKAZ_DATA_TEXT"]).' г.');


$objPHPExcel->getActiveSheet()->getStyle('B4:K4')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B4:K4')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('B4:K4')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->getStyle('B4')->applyFromArray(
		array(
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C4:K4')->applyFromArray(
		array(
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('K4')->applyFromArray(
		array(
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);	
	
	$objPHPExcel->getActiveSheet()->getStyle('E4')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('F4')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('G4')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('I4')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('J4')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	

$objPHPExcel->getActiveSheet()->mergeCells('B4:E4');
$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Интернет заказ покупателя №');

$objPHPExcel->getActiveSheet()->setCellValue('F4', $_SESSION["order_user_prepare"]["UF_ORDER"]);
$objPHPExcel->getActiveSheet()->setCellValue('G4', 'от');

$objPHPExcel->getActiveSheet()->mergeCells('H4:I4');
$objPHPExcel->getActiveSheet()->setCellValue('H4', $_SESSION["order_user_prepare"]["ZAKAZ_DATA_DATE"]);

$objPHPExcel->getActiveSheet()->setCellValue('J4', "время");
$objPHPExcel->getActiveSheet()->setCellValue('K4', $_SESSION["order_user_prepare"]["ZAKAZ_DATA_TIME"]);


$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C6:K6')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('K6')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);	
	
	$objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	

$objPHPExcel->getActiveSheet()->getStyle('B6:K6')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B6:K6')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B6:K6')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('B6:C6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->mergeCells('B6:C6');
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'ПОКУПАТЕЛЬ:');

$objPHPExcel->getActiveSheet()->mergeCells('D6:K6');
$objPHPExcel->getActiveSheet()->setCellValue('D6', iconv("cp1251","UTF-8",$_SESSION["order_user_prepare"]["ZAKAZ_FIO"]));

	

$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C7:K7')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('K7')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);	
	
	$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
$objPHPExcel->getActiveSheet()->getStyle('B7:D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


$objPHPExcel->getActiveSheet()->setCellValue('B7', 'код');
$objPHPExcel->getActiveSheet()->setCellValue('C7', 'BX992558');
$objPHPExcel->getActiveSheet()->setCellValue('D7', 'адрес');

$objPHPExcel->getActiveSheet()->mergeCells('E7:K7');
$objPHPExcel->getActiveSheet()->setCellValue('E7', iconv("cp1251","UTF-8",$_SESSION["order_user_prepare"]["ZAKAZ_ZIP"].'. '.$_SESSION["order_user_prepare"]["ZAKAZ_CITY"].', '.$_SESSION["order_user_prepare"]["ZAKAZ_ADRESS"]));


$objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C8:K8')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('K8')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);	
	
	$objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('C8')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('E8')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('D8')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
$objPHPExcel->getActiveSheet()->getStyle('B8:E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('B8', 'Тел');

$objPHPExcel->getActiveSheet()->mergeCells('C8:D8');
$objPHPExcel->getActiveSheet()->setCellValue('C8', iconv("cp1251","UTF-8",$_SESSION["order_user_prepare"]["ZAKAZ_PHONE"]));

$objPHPExcel->getActiveSheet()->setCellValue('D8', 'адрес');
$objPHPExcel->getActiveSheet()->setCellValue('E8', 'E-mail');

$objPHPExcel->getActiveSheet()->mergeCells('F8:K8');
$objPHPExcel->getActiveSheet()->setCellValue('F8', iconv("cp1251","UTF-8",$_SESSION["order_user_prepare"]["ZAKAZ_MAIL"]));



$objPHPExcel->getActiveSheet()->getStyle('B10')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C10:K10')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('K10')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);	
	
	$objPHPExcel->getActiveSheet()->getStyle('C10')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('E10')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('F10')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('G10')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('H10')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('J10')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	

$objPHPExcel->getActiveSheet()->mergeCells('B10:C10');
$objPHPExcel->getActiveSheet()->setCellValue('B10', 'Договор:');

$objPHPExcel->getActiveSheet()->getStyle('D10:E10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->mergeCells('D10:E10');
$objPHPExcel->getActiveSheet()->setCellValue('D10', 'интернет заказ поставщику №');

$objPHPExcel->getActiveSheet()->getStyle('G10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('F10', "");
$objPHPExcel->getActiveSheet()->setCellValue('G10', "от");
$objPHPExcel->getActiveSheet()->setCellValue('H10', "");

$objPHPExcel->getActiveSheet()->mergeCells('I10:J10');
$objPHPExcel->getActiveSheet()->setCellValue('I10', "Срок выполнения:");

$objPHPExcel->getActiveSheet()->setCellValue('K10', "");



$objPHPExcel->getActiveSheet()->getStyle('B11')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C11:F11')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('F11')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);	
	
	$objPHPExcel->getActiveSheet()->getStyle('C11')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	

$objPHPExcel->getActiveSheet()->mergeCells('B11:C11');
$objPHPExcel->getActiveSheet()->setCellValue('B11', 'Комментарий:');

$objPHPExcel->getActiveSheet()->getStyle('D11:F11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->mergeCells('D11:F11');
$objPHPExcel->getActiveSheet()->setCellValue('D11', 'на основании интернет заявки '.$_SESSION["order_user_prepare"]["UF_ORDER"].' от '.$_SESSION["order_user_prepare"]["ZAKAZ_DATA_DATE"].';  '.$_SESSION["order_user_prepare"]["ZAKAZ_DATA_TIME"]);



$objPHPExcel->getActiveSheet()->getStyle('J12')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->getStyle('J12:K12')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);

$objPHPExcel->getActiveSheet()->getStyle('J12:K12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
$objPHPExcel->getActiveSheet()->getStyle('K12')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('K12')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('K12')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('J12', 'в валюте');
$objPHPExcel->getActiveSheet()->setCellValue('K12', 'рубли');

	
$arrLet = array('B','C','D','E','F','G','H','I','J','K');

foreach($arrLet as $k=>$v) {	
$objPHPExcel->getActiveSheet()->getStyle($v.'13')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
}

$objPHPExcel->getActiveSheet()->getStyle('B13:K13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B13:K13')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B13:K13')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B13:K13')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B13:K13')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setCellValue('B13', '№');
$objPHPExcel->getActiveSheet()->setCellValue('C13', 'Код');
$objPHPExcel->getActiveSheet()->setCellValue('D13', '№ по каталогу');
$objPHPExcel->getActiveSheet()->setCellValue('E13', 'Производитель');
$objPHPExcel->getActiveSheet()->setCellValue('F13', 'Товар');
$objPHPExcel->getActiveSheet()->getStyle('G13')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->setCellValue('G13', 'Цена Розничная');
$objPHPExcel->getActiveSheet()->setCellValue('H13', 'Кол-во');
$objPHPExcel->getActiveSheet()->setCellValue('I13', 'Сумма');
$objPHPExcel->getActiveSheet()->setCellValue('J13', 'в т.ч. НДС');
$objPHPExcel->getActiveSheet()->getStyle('K13')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->setCellValue('K13', 'цена закупочная');


$cnt = 14+count($_SESSION["order_user_prepare"]["ITEMS"]);

for ($i=14; $i<$cnt; $i++) {
$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray(
		array(
			'borders' => array(
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->getStyle('B'.$i.':K'.$i)->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				
			),
		)
	);
}


$arrLet = array('B','C','D','E','F','G','H','I','J','K');

foreach($arrLet as $k=>$v) {	
$objPHPExcel->getActiveSheet()->getStyle($v.'14:'.$v.($cnt-1))->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				
			),
		)
	);
}

for ($i=14; $i<=$cnt; $i++) {
	$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}
for ($i=14; $i<=$cnt; $i++) {
	$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}
for ($i=14; $i<=$cnt; $i++) {
	$objPHPExcel->getActiveSheet()->getStyle('G'.$i.':K'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
}

$sum = $vatsum = $zaksum = '';
foreach ($_SESSION["order_user_prepare"]["ITEMS"] as $key=>$value) {

$objPHPExcel->getActiveSheet()->setCellValue('B'.($key+14), ($key+1));
$objPHPExcel->getActiveSheet()->setCellValue('C'.($key+14), substr($value["articul"],4));
$objPHPExcel->getActiveSheet()->setCellValue('D'.($key+14), substr($value["articul"],0,2)." ".substr($value["articul"],2,2)." ".substr($value["articul"],4,1)." ".substr($value["articul"],5,3)." ".substr($value["articul"],8,3) );
$objPHPExcel->getActiveSheet()->setCellValue('E'.($key+14), iconv("cp1251","UTF-8",$value["manuf"]));
$objPHPExcel->getActiveSheet()->setCellValue('F'.($key+14), iconv("cp1251","UTF-8",$value["name"]));
$objPHPExcel->getActiveSheet()->setCellValue('G'.($key+14), str_replace(".",",",$value["price"]));
$objPHPExcel->getActiveSheet()->setCellValue('H'.($key+14), $value["quant"]);
$objPHPExcel->getActiveSheet()->setCellValue('I'.($key+14), str_replace(".",",",$value["quant"]*$value["price"]));
$objPHPExcel->getActiveSheet()->setCellValue('J'.($key+14), str_replace(".",",",$value["quant"]*$value["price"]*18/100));
$objPHPExcel->getActiveSheet()->setCellValue('K'.($key+14), str_replace(".",",",round($value["quant"]*$value["default_price"],0)));

$sum += $value["quant"]*$value["price"];
$vatsum += $value["quant"]*$value["price"]*18/100;
$zaksum += $value["quant"]*$value["default_price"];

}


$objPHPExcel->getActiveSheet()->getStyle('I'.($cnt).':K'.($cnt))->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);

$objPHPExcel->getActiveSheet()->getStyle('I'.($cnt))->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->getStyle('J'.($cnt))->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);

$objPHPExcel->getActiveSheet()->getStyle('H'.($cnt))->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('H'.($cnt))->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('H'.($cnt))->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('H'.($cnt), 'Итого');

$objPHPExcel->getActiveSheet()->getStyle('I'.($cnt))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('I'.($cnt))->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('I'.($cnt))->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('I'.($cnt))->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('I'.($cnt), str_replace(".",",",round($sum,0)));

$objPHPExcel->getActiveSheet()->getStyle('J'.($cnt))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('J'.($cnt), str_replace(".",",",$vatsum));

$objPHPExcel->getActiveSheet()->getStyle('K'.($cnt))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('K'.($cnt), str_replace(".",",",round($zaksum,0)));


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Заказ покупателя');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

// Redirect output to a client's web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="table.xlsx"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');

$_SESSION["table_oreder"] = '';
exit;

?>


