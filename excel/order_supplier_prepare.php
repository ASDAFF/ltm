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
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(14.2);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15.5);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(32);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12.3);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(11.5);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);


$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(17);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(17);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(17);
$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(19);
$objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(19);

for ($i=6; $i<=15; $i++){
	$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(17);
}

$objPHPExcel->getActiveSheet()->getRowDimension('16')->setRowHeight(27);

for ($i=17; $i<=500; $i++){
	$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(15.5);
}


$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(11); 

$objPHPExcel->getActiveSheet()->getStyle('B1:J1')->applyFromArray(
		array(
			'borders' => array(
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE),
			),
		)
	);

$objPHPExcel->getActiveSheet()->getStyle('B2:J2')->applyFromArray(
		array(
			'borders' => array(
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE),
			),
		)
	);	

$objPHPExcel->getActiveSheet()->mergeCells('B1:J1');
$objPHPExcel->getActiveSheet()->getStyle('B1:J1')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B1:J1')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B1:J1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'ЗАКАЗЧИК:  ООО Технический центр "Наука-авто"');

$objPHPExcel->getActiveSheet()->mergeCells('B2:J2');
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'адрес: 125212, г. Москва, Головинское ш., вл 11.');



$objPHPExcel->getActiveSheet()->getStyle('B4:J4')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B4:J4')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('B4:J4')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->getStyle('B4')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C4:I4')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('J4')->applyFromArray(
		array(
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

$objPHPExcel->getActiveSheet()->getStyle('B4:J4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	

$objPHPExcel->getActiveSheet()->mergeCells('B4:E4');
$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Интернет заказ поставщику № ');
$objPHPExcel->getActiveSheet()->setCellValue('F4', '7');
$objPHPExcel->getActiveSheet()->setCellValue('G4', 'от');
$objPHPExcel->getActiveSheet()->mergeCells('H4:J4');
$objPHPExcel->getActiveSheet()->setCellValue('H4', '9 Января 2013 г.');



$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C6:I6')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('J6')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);	
	
	$objPHPExcel->getActiveSheet()->getStyle('E6')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('F6')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('G6')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
$objPHPExcel->getActiveSheet()->getStyle('B6:J6')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B6:J6')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('B6:J6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B6:J6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	

$objPHPExcel->getActiveSheet()->mergeCells('B6:E6');
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'Заказ поставщику №');


$objPHPExcel->getActiveSheet()->setCellValue('F6', 'ЗПС000087');
$objPHPExcel->getActiveSheet()->setCellValue('G6', 'от');

$objPHPExcel->getActiveSheet()->mergeCells('H6:J6');
$objPHPExcel->getActiveSheet()->setCellValue('H6', '10.01.2013');


$objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C8:I8')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('J8')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
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
	$objPHPExcel->getActiveSheet()->getStyle('F8')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('G8')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	

$objPHPExcel->getActiveSheet()->getStyle('B8:J8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	

$objPHPExcel->getActiveSheet()->mergeCells('B8:C8');
$objPHPExcel->getActiveSheet()->setCellValue('B8', 'Первичный документ');

$objPHPExcel->getActiveSheet()->mergeCells('D8:E8');
$objPHPExcel->getActiveSheet()->setCellValue('D8', 'Интернет заказ покупателя №');

$objPHPExcel->getActiveSheet()->setCellValue('F8', "'000018");
$objPHPExcel->getActiveSheet()->setCellValue('G8', 'от');

$objPHPExcel->getActiveSheet()->mergeCells('H8:J8');
$objPHPExcel->getActiveSheet()->setCellValue('H8', '09.01.2013');



$objPHPExcel->getActiveSheet()->getStyle('B10')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C10:I10')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('J10')->applyFromArray(
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
	$objPHPExcel->getActiveSheet()->getStyle('H10')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
$objPHPExcel->getActiveSheet()->getStyle('B10:J10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		

$objPHPExcel->getActiveSheet()->getStyle('B10:C10')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B10:C10')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B10:C10')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->mergeCells('B10:C10');
$objPHPExcel->getActiveSheet()->setCellValue('B10', 'ПОСТАВЩИК:');

$objPHPExcel->getActiveSheet()->mergeCells('D10:E10');
$objPHPExcel->getActiveSheet()->setCellValue('D10', 'АТП');

$objPHPExcel->getActiveSheet()->getStyle('F10')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('F10')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('F10')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('F10', 'код поставщика');

$objPHPExcel->getActiveSheet()->mergeCells('G10:H10');
$objPHPExcel->getActiveSheet()->getStyle('G10:H10')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('G10:H10')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('G10:H10')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('G10', '12582502');


$objPHPExcel->getActiveSheet()->mergeCells('B12:C12');
$objPHPExcel->getActiveSheet()->setCellValue('B12', 'Договор:');

$objPHPExcel->getActiveSheet()->mergeCells('D12:J12');
$objPHPExcel->getActiveSheet()->setCellValue('D12', 'Автодоговор с фирмой Технический центр "Наука-авто"');


$objPHPExcel->getActiveSheet()->getStyle('B13:C13')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B13:C13')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B13:C13')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->mergeCells('B13:C13');
$objPHPExcel->getActiveSheet()->setCellValue('B13', 'Срок поставки:');

$objPHPExcel->getActiveSheet()->mergeCells('D13:J13');
$objPHPExcel->getActiveSheet()->setCellValue('D13', '14.01.2013');


$objPHPExcel->getActiveSheet()->mergeCells('B14:C14');
$objPHPExcel->getActiveSheet()->setCellValue('B14', 'Комментарий:');

$objPHPExcel->getActiveSheet()->mergeCells('D14:G14');
$objPHPExcel->getActiveSheet()->setCellValue('D14', 'на основании заказа покупателя  № 000005;  интернет заказа покупателя  0000018');


$objPHPExcel->getActiveSheet()->getStyle('I14')->applyFromArray(
		array(
			'borders' => array(
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->getStyle('J14')->applyFromArray(
		array(
			'borders' => array(
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);

$objPHPExcel->getActiveSheet()->getStyle('I14:J14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		
$objPHPExcel->getActiveSheet()->getStyle('J14')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('J14')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('J14')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setCellValue('I14', 'в валюте');
$objPHPExcel->getActiveSheet()->setCellValue('J14', 'Евро');



$objPHPExcel->getActiveSheet()->getStyle('B16')->applyFromArray(
		array(
			'borders' => array(
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->getStyle('B16:J16')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK),
				
			),
		)
	);
	
$arrLet = array('B','C','D','E','F','G','H','I','J');

foreach($arrLet as $k=>$v) {	
$objPHPExcel->getActiveSheet()->getStyle($v.'16')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				
			),
		)
	);
}

$objPHPExcel->getActiveSheet()->getStyle('B16:J16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B16:J16')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B16:J16')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B16:J16')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B16:J16')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setCellValue('B16', '№');
$objPHPExcel->getActiveSheet()->setCellValue('C16', 'Код');
$objPHPExcel->getActiveSheet()->setCellValue('D16', '№ по каталогу');
$objPHPExcel->getActiveSheet()->setCellValue('E16', 'Производитель');
$objPHPExcel->getActiveSheet()->setCellValue('F16', 'Товар');
$objPHPExcel->getActiveSheet()->getStyle('G16')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->setCellValue('G16', 'Цена закупочная');
$objPHPExcel->getActiveSheet()->setCellValue('H16', 'Кол-во');
$objPHPExcel->getActiveSheet()->setCellValue('I16', 'Сумма');
$objPHPExcel->getActiveSheet()->getStyle('J16')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->setCellValue('J16', 'в т.ч. НДС');



for ($i=17; $i<=21; $i++) {
$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray(
		array(
			'borders' => array(
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->getStyle('B'.$i.':J'.$i)->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				
			),
		)
	);
}


$arrLet = array('B','C','D','E','F','G','H','I','J');

foreach($arrLet as $k=>$v) {	
$objPHPExcel->getActiveSheet()->getStyle($v.'17:'.$v.'21')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				
			),
		)
	);
}

for ($i=17; $i<=21; $i++) {
	$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}
for ($i=17; $i<=21; $i++) {
	$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}
for ($i=17; $i<=21; $i++) {
	$objPHPExcel->getActiveSheet()->getStyle('G'.$i.':J'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
}


for ($i=17; $i<=21; $i++) {
$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, ($i-16));
$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, '6794915');
$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, '34 11 6 794 915');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'BMW');
$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Колодки тормозные передние');
$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, '83,23');
$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, '1');
$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, '83,23');
$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, '20,3');
}


$objPHPExcel->getActiveSheet()->getStyle('I22')->applyFromArray(
		array(
			'borders' => array(
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->getStyle('J22')->applyFromArray(
		array(
			'borders' => array(
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);


$objPHPExcel->getActiveSheet()->getStyle('H22')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('H22')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('H22')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('H22', 'Итого Евро');

$objPHPExcel->getActiveSheet()->getStyle('I22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('I22')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('I22')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('I22')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('I22', '434,14');

$objPHPExcel->getActiveSheet()->getStyle('J22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('J22', '434,14');


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Заказ поставщику');
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


