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
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(48.3);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(16);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(7);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(11.5);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);


$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(15.5);
$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(15.5);

for ($i=4; $i<=9; $i++){
	$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(14);
}

$objPHPExcel->getActiveSheet()->getRowDimension('10')->setRowHeight(15.5);
$objPHPExcel->getActiveSheet()->getRowDimension('11')->setRowHeight(20.5);
$objPHPExcel->getActiveSheet()->getRowDimension('12')->setRowHeight(15.5);
$objPHPExcel->getActiveSheet()->getRowDimension('13')->setRowHeight(15.5);
$objPHPExcel->getActiveSheet()->getRowDimension('14')->setRowHeight(14);
$objPHPExcel->getActiveSheet()->getRowDimension('15')->setRowHeight(14);
$objPHPExcel->getActiveSheet()->getRowDimension('16')->setRowHeight(15.5);
$objPHPExcel->getActiveSheet()->getRowDimension('17')->setRowHeight(15.5);
$objPHPExcel->getActiveSheet()->getRowDimension('18')->setRowHeight(15.5);
$objPHPExcel->getActiveSheet()->getRowDimension('19')->setRowHeight(26);

for ($i=20; $i<=27; $i++){
	$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(13.3);
}

for ($i=28; $i<=150; $i++){
	$objPHPExcel->getActiveSheet()->getRowDimension($i)->setRowHeight(15.5);
}


$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(11); 
	

$objPHPExcel->getActiveSheet()->mergeCells('B1:I1');
$objPHPExcel->getActiveSheet()->getStyle('B1:I1')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B1:I1')->getFont()->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('B1:I1')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('B1:I1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Внимание! Оплата данного счета означает согласие с условиями поставки товара. Уведомление об оплате обязательно, в противном случае не гарантируется наличие товара на складе. Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при нал:');

$objPHPExcel->getActiveSheet()->mergeCells('B2:F2');
$objPHPExcel->getActiveSheet()->getStyle('B2:F2')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B2:F2')->getFont()->setSize(11);
$objPHPExcel->getActiveSheet()->getStyle('B2:F2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('B2', $_SESSION["bill_prepare"]["NAUKA_SELLER"]);

$objPHPExcel->getActiveSheet()->mergeCells('G2:H2');
$objPHPExcel->getActiveSheet()->getStyle('G2:H2')->applyFromArray(
		array(
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray(
		array(
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);


$objPHPExcel->getActiveSheet()->setCellValue('G2', 'срок оплаты');

$objPHPExcel->getActiveSheet()->getStyle('I2')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('I2')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('I2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('I2', 'до '.iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["PAY_DATE"]));

$objPHPExcel->getActiveSheet()->mergeCells('B3:I3');
$objPHPExcel->getActiveSheet()->getStyle('B3:I3')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->setCellValue('B3', $_SESSION["bill_prepare"]["NAUKA_ADRESS"]);



$objPHPExcel->getActiveSheet()->getStyle('B4:C4')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->getStyle('D4')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
for ($i=5; $i<=9; $i++){
	$objPHPExcel->getActiveSheet()->getStyle('B'.$i.':D'.$i)->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B'.$i.':C'.$i)->applyFromArray(
		array(
			'borders' => array(
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->applyFromArray(
		array(
			'borders' => array(
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
}
	

$objPHPExcel->getActiveSheet()->mergeCells('B4:C4');
$objPHPExcel->getActiveSheet()->setCellValue('B4', $_SESSION["bill_prepare"]["NAUKA_INN"]);
$objPHPExcel->getActiveSheet()->setCellValue('D4', $_SESSION["bill_prepare"]["NAUKA_KPP"]);

$objPHPExcel->getActiveSheet()->mergeCells('B5:C5');
$objPHPExcel->getActiveSheet()->setCellValue('B5', $_SESSION["bill_prepare"]["NAUKA_RSCH_NAME"]);
$objPHPExcel->getActiveSheet()->setCellValue('D5', $_SESSION["bill_prepare"]["NAUKA_RSCH"]);

$objPHPExcel->getActiveSheet()->mergeCells('B6:C6');
$objPHPExcel->getActiveSheet()->setCellValue('B6', $_SESSION["bill_prepare"]["NAUKA_BANK_NAME"]);
$objPHPExcel->getActiveSheet()->setCellValue('D6', $_SESSION["bill_prepare"]["NAUKA_BANK"]);

$objPHPExcel->getActiveSheet()->mergeCells('B7:C7');
$objPHPExcel->getActiveSheet()->setCellValue('B7', $_SESSION["bill_prepare"]["NAUKA_KORSCH_NAME"]);
$objPHPExcel->getActiveSheet()->setCellValue('D7', $_SESSION["bill_prepare"]["NAUKA_KORSCH"]);

$objPHPExcel->getActiveSheet()->mergeCells('B8:C8');
$objPHPExcel->getActiveSheet()->setCellValue('B8', $_SESSION["bill_prepare"]["NAUKA_BIK_NAME"]);
$objPHPExcel->getActiveSheet()->setCellValue('D8', $_SESSION["bill_prepare"]["NAUKA_BIK"]);

$objPHPExcel->getActiveSheet()->mergeCells('B9:C9');
$objPHPExcel->getActiveSheet()->setCellValue('B9', $_SESSION["bill_prepare"]["NAUKA_GRUZ_NAME"]);
$objPHPExcel->getActiveSheet()->setCellValue('D9', $_SESSION["bill_prepare"]["NAUKA_GRUZ"]);


$objPHPExcel->getActiveSheet()->mergeCells('B11:D11');
$objPHPExcel->getActiveSheet()->getStyle('B11:D11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B11:D11')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B11:D11')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('B11:D11')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('B11', "Счет № ".iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["NBER"]));

$objPHPExcel->getActiveSheet()->mergeCells('E11:I11');
$objPHPExcel->getActiveSheet()->getStyle('E11:I11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E11:I11')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('E11:I11')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('E11:I11')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('E11', iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["BILL_DATE_TEXT"])." г.");

$objPHPExcel->getActiveSheet()->mergeCells('B13:H13');
$objPHPExcel->getActiveSheet()->getStyle('B13:H13')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B13:H13')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('B13:H13')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('B13', 'ПОКУПАТЕЛЬ:   '.iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["BUYER"]));

$objPHPExcel->getActiveSheet()->getStyle('B14:I14')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B15:I15')->getFont()->setSize(10);


$objPHPExcel->getActiveSheet()->getStyle('B14')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C14:I14')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('I14')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);	
	
	$objPHPExcel->getActiveSheet()->getStyle('B14')->applyFromArray(
		array(
			'borders' => array(
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


$objPHPExcel->getActiveSheet()->setCellValue('B14', "код:");
$objPHPExcel->getActiveSheet()->getStyle('C14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('C14', iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["BUYER_CODE"]));
$objPHPExcel->getActiveSheet()->getStyle('D14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('D14', "адрес:");
$objPHPExcel->getActiveSheet()->mergeCells('E14:I14');
$objPHPExcel->getActiveSheet()->setCellValue('E14', iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["ADRESS"]));


$objPHPExcel->getActiveSheet()->getStyle('B15')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('C15:I15')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('I15')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);	
	
	$objPHPExcel->getActiveSheet()->getStyle('B15')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('D15')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
	$objPHPExcel->getActiveSheet()->getStyle('E15')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);

$objPHPExcel->getActiveSheet()->setCellValue('B15', "Тел:");
$objPHPExcel->getActiveSheet()->mergeCells('C15:D15');
$objPHPExcel->getActiveSheet()->setCellValue('C15', iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["PHONE"]));
$objPHPExcel->getActiveSheet()->getStyle('E15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('E15', "E-mail");
$objPHPExcel->getActiveSheet()->mergeCells('F15:I15');
$objPHPExcel->getActiveSheet()->setCellValue('F15', iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["EMAIL"]));


$objPHPExcel->getActiveSheet()->mergeCells('B17:C17');
$objPHPExcel->getActiveSheet()->getStyle('B17:C17')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B17:C17')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('B17:C17')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('B17', "Предмет счета:");

$objPHPExcel->getActiveSheet()->mergeCells('D17:I17');
$objPHPExcel->getActiveSheet()->setCellValue('D17', "Оплата за запасные части и расходуемые материалы на основании интернет заказа № ".iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["ZAKAZ"])." от ".iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["ZAKAZ_DATA"]));


$objPHPExcel->getActiveSheet()->getStyle('H18')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED),
			),
		)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('I18')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED),
				'left' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_DOTTED),
			),
		)
	);	

$objPHPExcel->getActiveSheet()->getStyle('H18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('H18', "в валюте");
$objPHPExcel->getActiveSheet()->getStyle('I18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('I18')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('I18')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('I18')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('I18', "Руб.");



$objPHPExcel->getActiveSheet()->getStyle('B19')->applyFromArray(
		array(
			'borders' => array(
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->getStyle('B19:I19')->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK),
				
			),
		)
	);
	
		
$arrLet = array('B','C','D','E','F','G','H','I');

foreach($arrLet as $k=>$v) {	
$objPHPExcel->getActiveSheet()->getStyle($v.'19')->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				
			),
		)
	);
}

$objPHPExcel->getActiveSheet()->getStyle('B19:I19')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B19:I19')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B19:I19')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('B19:I19')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('B19:I19')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setCellValue('B19', '№');
$objPHPExcel->getActiveSheet()->setCellValue('C19', '№ по каталогу');
$objPHPExcel->getActiveSheet()->setCellValue('D19', 'Товар');
$objPHPExcel->getActiveSheet()->setCellValue('E19', 'производитель');
$objPHPExcel->getActiveSheet()->getStyle('F19')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->setCellValue('F19', 'Цена Розничная');
$objPHPExcel->getActiveSheet()->setCellValue('G19', 'Кол-во');
$objPHPExcel->getActiveSheet()->setCellValue('H19', 'Сумма');
$objPHPExcel->getActiveSheet()->setCellValue('I19', 'в т.ч. НДС');


$cnt = 20+count($_SESSION["bill_prepare"]["ITEMS"]);

for ($i=20; $i<$cnt; $i++) {
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
$objPHPExcel->getActiveSheet()->getStyle($v.'20:'.$v.($cnt-1))->applyFromArray(
		array(
			'borders' => array(
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				
			),
		)
	);
}

for ($i=20; $i<$cnt; $i++) {
	$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}
for ($i=20; $i<$cnt; $i++) {
	$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}
for ($i=20; $i<$cnt; $i++) {
	$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}
for ($i=20; $i<$cnt; $i++) {
	$objPHPExcel->getActiveSheet()->getStyle('F'.$i.':I'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
}

$i=20;
$sum = $vatsum = $zaksum = '';
foreach ($_SESSION["bill_prepare"]["ITEMS"] as $key=>$value) {
$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, ($key+1));
$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, iconv("cp1251","UTF-8",$value["articul"]));
$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, iconv("cp1251","UTF-8",$value["name"]));
$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, iconv("cp1251","UTF-8",$value["manuf"]));
$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, iconv("cp1251","UTF-8",str_replace(".",",",$value["price"])));
$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, iconv("cp1251","UTF-8",$value["quant"]));
$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, iconv("cp1251","UTF-8",str_replace(".",",",$value["quant"]*$value["price"])));
$objPHPExcel->getActiveSheet()->setCellValue('I'.$i,'');
// iconv("cp1251","UTF-8",str_replace(".",",",$value["quant"]*$value["price"]*18/100))
$i++;
}


$objPHPExcel->getActiveSheet()->getStyle('G'.$cnt)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->setCellValue('G'.$cnt, "Итого:");
$objPHPExcel->getActiveSheet()->getStyle('H'.$cnt)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$cnt)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->setCellValue('H'.$cnt, iconv("cp1251","UTF-8",iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]['sum'])));

$objPHPExcel->getActiveSheet()->getStyle('H'.$cnt)->applyFromArray(
		array(
			'borders' => array(
				'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				
			),
		)
	);
$objPHPExcel->getActiveSheet()->getStyle('I'.$cnt)->applyFromArray(
		array(
			'borders' => array(
                                'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				
			),
		)
	);


$cnt++;

$objPHPExcel->getActiveSheet()->mergeCells('B'.$cnt.':I'.$cnt);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$cnt, 'Всего '.iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]['quant']).' позиций на сумму '.iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]['sum_text']).' ('.iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]['sum']).' руб.) НДС не облагаеться.');

$cnt+=2;


$objPHPExcel->getActiveSheet()->mergeCells('C'.$cnt.':E'.$cnt);
$objPHPExcel->getActiveSheet()->getStyle('C'.$cnt.':E'.$cnt)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('C'.$cnt.':E'.$cnt)->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('C'.$cnt.':E'.$cnt)->getFont()->setItalic(true);
$objPHPExcel->getActiveSheet()->setCellValue('C'.$cnt, 'Руководитель_________________________________________________________________________________________________________________________________');

$objPHPExcel->getActiveSheet()->mergeCells('F'.$cnt.':H'.$cnt);
$objPHPExcel->getActiveSheet()->setCellValue('F'.$cnt, '/Козлов С. Б./');

$cnt+=2;

$objPHPExcel->getActiveSheet()->mergeCells('C'.$cnt.':E'.$cnt);
$objPHPExcel->getActiveSheet()->getStyle('C'.$cnt.':E'.$cnt)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('C'.$cnt.':E'.$cnt)->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('C'.$cnt.':E'.$cnt)->getFont()->setItalic(true);
$objPHPExcel->getActiveSheet()->setCellValue('C'.$cnt, 'Бухгалтер____________________________________________________________________________________________________________________________________');

$objPHPExcel->getActiveSheet()->mergeCells('F'.$cnt.':H'.$cnt);
$objPHPExcel->getActiveSheet()->setCellValue('F'.$cnt, '/Калиновская Е. В./');


$objPHPExcel->getActiveSheet()->getStyle('I'.$cnt)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('I'.$cnt, 'М.П.');



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Счет');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

$dir = substr(iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["NBER"]),0,3);
@mkdir($_SERVER["DOCUMENT_ROOT"]."/upload/bills/".$dir);

// Redirect output to a client's web browser (Excel2007)
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="'.iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["NBER"]).'.xlsx"');
//header('Cache-Control: max-age=0');
$objWriter->save($_SERVER["DOCUMENT_ROOT"]."/upload/bills/".$dir."/".iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["NBER"]).".xlsx");




//$subject = '=?koi8-r?B?'.base64_encode(convert_cyr_string('Счет N'.$_SESSION["bill_prepare"]["NBER"].' (интернет-магазин Наука-Авто)', "w","k")).'?='; 
$subject = 'Счет N'.$_SESSION["bill_prepare"]["NBER"].' (интернет-магазин Наука-Авто)';
$random_hash = md5(date('r', time()));
//$headers  = "MIME-Version: 1.0\r\n";
//$headers .= "Content-type: text/html; charset=windows-1251\r\n";
//$headers .= "From: sale@nauka-auto.ru\r\nReply-To: sale@nauka-auto.ru";

$headers = "From: sale@nauka-auto.ru\r\nReply-To: sale@nauka-auto.ru";
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";

$message = $_SESSION["bill_local_params"]["message"];

$file_name = iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["NBER"]).".xlsx";
$attachment = chunk_split(base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/upload/bills/".$dir."/".iconv("cp1251","UTF-8",$_SESSION["bill_prepare"]["NBER"]).".xlsx")));


ob_start(); //Turn on output buffering
?>
Content-Type: multipart/mixed; boundary="PHP-mixed-<?php echo $random_hash; ?>"
--PHP-mixed-<?php echo $random_hash; ?>

Content-Type: text/html; charset="windows-1251"
Content-Transfer-Encoding: 7bit
<?=$message?>

--PHP-mixed-<?php echo $random_hash; ?>

Content-Type: application/octet-stream; name="<?=$file_name?>" 
Content-Transfer-Encoding: base64 
Content-Disposition: attachment 

<?=$attachment?>
--PHP-mixed-<?php echo $random_hash; ?>

<?	
$message = ob_get_clean();	

$mail_sent = mail($_SESSION["bill_local_params"]["user_mail"], $subject, $message, $headers);

$_SESSION["bill_local_params"] = array();
$_SESSION["bill_local_params"] = '';
$_SESSION["bill_prepare"] = array();
$_SESSION["bill_prepare"] = '';

//header ("Location: /administrator/more/orders/index.php?sid=".$_SESSION["bill_local_params"]["sid"]."&uid=".$_SESSION["bill_local_params"]["uid"]."&ord_id=".$_SESSION["bill_local_params"]["ord_id"]."&cid=".$_SESSION["bill_local_params"]["cid"]);


?>

<?
exit;
?>


