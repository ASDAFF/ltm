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
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(16);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(16);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13);


$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(17);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(17);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(17);
$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(17);
$objPHPExcel->getActiveSheet()->getRowDimension('5')->setRowHeight(17);

for ($i=6; $i<=16; $i++){
	$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(17);
}

$objPHPExcel->getActiveSheet()->getRowDimension('17')->setRowHeight(27);

for ($i=18; $i<=500; $i++){
	$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(15.5);
}


$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(11); 

$objPHPExcel->getActiveSheet()->getStyle('B2:H2')->applyFromArray(
		array(
			'borders' => array(
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_DOUBLE),
			),
		)
	);	

$objPHPExcel->getActiveSheet()->mergeCells('B2:D2');
$objPHPExcel->getActiveSheet()->getStyle('B2:I2')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B2:I2')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('B2:I2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('B2', 'Расходная накладная №');
$objPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('E2', '6');

$objPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('F2', 'от');

$objPHPExcel->getActiveSheet()->mergeCells('G2:H2');
$objPHPExcel->getActiveSheet()->getStyle('G2:H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->setCellValue('G2', '16 Января 2013 г.');


$objPHPExcel->getActiveSheet()->getStyle('B4:H4')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B4:H4')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('B4:H4')->getFont()->setBold(true);

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
	
	$objPHPExcel->getActiveSheet()->getStyle('C4:G4')->applyFromArray(
		array(
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('H4')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);	
	$objPHPExcel->getActiveSheet()->getStyle('D4')->applyFromArray(
		array(
			'borders' => array(
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
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT),
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	

$objPHPExcel->getActiveSheet()->mergeCells('B4:D4');
$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Интернет заказ покупателя № ');

$objPHPExcel->getActiveSheet()->setCellValue('E4', "'000018");
$objPHPExcel->getActiveSheet()->setCellValue('F4', 'от');

$objPHPExcel->getActiveSheet()->getStyle('G4:H4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->mergeCells('G4:H4');
$objPHPExcel->getActiveSheet()->setCellValue('G4', '09.01.2013');



$objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C6:G6')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('H6')->applyFromArray(
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
	
$objPHPExcel->getActiveSheet()->getStyle('B6:H6')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B6:H6')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B6:H6')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->mergeCells('B6:C6');
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'ПОКУПАТЕЛЬ:');

$objPHPExcel->getActiveSheet()->mergeCells('D6:H6');
$objPHPExcel->getActiveSheet()->setCellValue('D6', 'Новоселова Татьяна Евгеньевна');


$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray(
		array(
			'borders' => array(
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C7:H7')->applyFromArray(
		array(
			'borders' => array(
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('H7')->applyFromArray(
		array(
			'borders' => array(
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
	

$objPHPExcel->getActiveSheet()->setCellValue('B7', 'код');
$objPHPExcel->getActiveSheet()->setCellValue('C7', 'BX992558');
$objPHPExcel->getActiveSheet()->getStyle('D7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('D7', 'адрес');

$objPHPExcel->getActiveSheet()->mergeCells('E7:H7');
$objPHPExcel->getActiveSheet()->setCellValue('B8', '125040. Москва, Ул Петровка д.25. кв. 15.');


	

$objPHPExcel->getActiveSheet()->getStyle('B8')->applyFromArray(
		array(
			'borders' => array(
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C8:H8')->applyFromArray(
		array(
			'borders' => array(
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('H8')->applyFromArray(
		array(
			'borders' => array(
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
	$objPHPExcel->getActiveSheet()->getStyle('D8')->applyFromArray(
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
	
$objPHPExcel->getActiveSheet()->setCellValue('B8', 'Тел');

$objPHPExcel->getActiveSheet()->mergeCells('C8:D8');
$objPHPExcel->getActiveSheet()->setCellValue('C8', '8-916-629-22-16');

$objPHPExcel->getActiveSheet()->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('E8', 'E-mail');

$objPHPExcel->getActiveSheet()->mergeCells('F8:H8');
$objPHPExcel->getActiveSheet()->setCellValue('F8', 'novoselova_t@mail.ru');




$objPHPExcel->getActiveSheet()->getStyle('B10')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C10:H10')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('H10')->applyFromArray(
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
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
		)
	);
	


$objPHPExcel->getActiveSheet()->mergeCells('B10:C10');
$objPHPExcel->getActiveSheet()->setCellValue('B10', 'Договор:');

$objPHPExcel->getActiveSheet()->mergeCells('D10:E10');
$objPHPExcel->getActiveSheet()->setCellValue('D10', 'интернет заказ поставщику №');

$objPHPExcel->getActiveSheet()->setCellValue('F10', "'0000018");

$objPHPExcel->getActiveSheet()->getStyle('G10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('G10', 'от');

$objPHPExcel->getActiveSheet()->getStyle('H10')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('H10', '09.01.2013');

$objPHPExcel->getActiveSheet()->getStyle('B11')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C11:H11')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('H11')->applyFromArray(
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
	$objPHPExcel->getActiveSheet()->getStyle('D11')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);

$objPHPExcel->getActiveSheet()->mergeCells('B11:C11');
$objPHPExcel->getActiveSheet()->setCellValue('B11', 'Срок выполнения:');
$objPHPExcel->getActiveSheet()->setCellValue('D11', '14.01.13');


$objPHPExcel->getActiveSheet()->getStyle('B12')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C12:H12')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('H12')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);	
	
	
	$objPHPExcel->getActiveSheet()->getStyle('C12')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('D12')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);

$objPHPExcel->getActiveSheet()->mergeCells('B12:C12');
$objPHPExcel->getActiveSheet()->setCellValue('B12', 'Комментарий:');

$objPHPExcel->getActiveSheet()->mergeCells('D12:H12');
$objPHPExcel->getActiveSheet()->setCellValue('D12', 'на основании интернет заказа поставщику № 000018 от 09.01.13;  18-30');



$objPHPExcel->getActiveSheet()->getStyle('B14')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C14:H14')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('H14')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);	
	
	
	$objPHPExcel->getActiveSheet()->getStyle('C14')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('D14')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	

$objPHPExcel->getActiveSheet()->getStyle('B14:H14')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->mergeCells('B14:C14');
$objPHPExcel->getActiveSheet()->setCellValue('B14', 'Отгружено со склада');

$objPHPExcel->getActiveSheet()->mergeCells('D14:H14');
$objPHPExcel->getActiveSheet()->setCellValue('D14', 'Склад заказных деталей');



$objPHPExcel->getActiveSheet()->getStyle('H16')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->getStyle('I16')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);

$objPHPExcel->getActiveSheet()->getStyle('I16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('I16')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setCellValue('H16', 'в валюте');	
$objPHPExcel->getActiveSheet()->setCellValue('I16', 'рубли');
	
	

$objPHPExcel->getActiveSheet()->getStyle('B17')->applyFromArray(
		array(
			'borders' => array(
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
		
$arrLet = array('B','C','D','E','F','G','H','I');

foreach($arrLet as $k=>$v) {	
$objPHPExcel->getActiveSheet()->getStyle($v.'17')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
}

$objPHPExcel->getActiveSheet()->getStyle('B17:I17')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B17:I17')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B17:I17')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B17:I17')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B17:I17')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setCellValue('B17', '№');
$objPHPExcel->getActiveSheet()->setCellValue('C17', 'Код');
$objPHPExcel->getActiveSheet()->setCellValue('D17', '№ по каталогу');
$objPHPExcel->getActiveSheet()->setCellValue('E17', 'Производитель');
$objPHPExcel->getActiveSheet()->setCellValue('F17', 'Товар');
$objPHPExcel->getActiveSheet()->setCellValue('G17', 'Цена');
$objPHPExcel->getActiveSheet()->setCellValue('H17', 'Кол-во');
$objPHPExcel->getActiveSheet()->setCellValue('I17', 'Сумма');



for ($i=18; $i<=26; $i++) {
$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray(
		array(
			'borders' => array(
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->getStyle('B'.$i.':I'.$i)->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				
			),
		)
	);
}


$arrLet = array('B','C','D','E','F','G','H','I');

foreach($arrLet as $k=>$v) {	
$objPHPExcel->getActiveSheet()->getStyle($v.'18:'.$v.'26')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				
			),
		)
	);
}

for ($i=18; $i<=26; $i++) {
	$objPHPExcel->getActiveSheet()->getStyle('B'.$i.':E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}
for ($i=18; $i<=26; $i++) {
	$objPHPExcel->getActiveSheet()->getStyle('G'.$i.':I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
}


for ($i=18; $i<=26; $i++) {
$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, ($i-12));
$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, '6794915');
$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, '34 11 6 794 915');
$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, 'BMW');
$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Ведро складное');
$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, '605,57');
$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, '1');
$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, '605,57');
}


$objPHPExcel->getActiveSheet()->getStyle('G27:H27')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('G27:H27')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('G27:H27')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->mergeCells('G27:H27');
$objPHPExcel->getActiveSheet()->setCellValue('G27', 'Итого по накладной Руб.  ');

$objPHPExcel->getActiveSheet()->getStyle('I27')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('I27')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('I27')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('I27')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('I27', '434,14');



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Расходная накладная');
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


