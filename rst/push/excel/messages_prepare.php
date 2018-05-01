<?
/** Error reporting */
error_reporting(E_ALL);
session_start();

date_default_timezone_set('Europe/Moscow');


/** Include PHPExcel */
require_once $_SERVER["DOCUMENT_ROOT"].'/excel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("luxurytravelmart.ru")
							 ->setLastModifiedBy("luxurytravelmart.ru")
							 ->setTitle("Messages")
							 ->setSubject("Messages")
							 ->setDescription("Messages")
							 ->setKeywords("Messages")
							 ->setCategory("Messages");
$objPHPExcel->setActiveSheetIndex(0);




$objPHPExcel->setActiveSheetIndex(0);

//$objPHPExcel->getActiveSheet()->setShowGridLines(false);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12.11);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(14.12);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12.11);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12.11);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(36.42);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(28.84);


//$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(68);
$objPHPExcel->getActiveSheet()->getRowDimension('9')->setRowHeight(25);
/*
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
*/

$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(12); 
	

$objPHPExcel->getActiveSheet()->mergeCells('B2:G2');
$objPHPExcel->getActiveSheet()->getStyle('B2:F2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2:G2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B2:G2')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'MESSAGES AND DIALOGS REPORT');

$objPHPExcel->getActiveSheet()->mergeCells('B3:C3');
$objPHPExcel->getActiveSheet()->getStyle('B3:C3')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
$objPHPExcel->getActiveSheet()->setCellValue('B3', 'Date of report generation');

$objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
$objPHPExcel->getActiveSheet()->setCellValue('D3', date("d/m/Y"));

$objPHPExcel->getActiveSheet()->mergeCells('B4:C4');
$objPHPExcel->getActiveSheet()->getStyle('B4:C4')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Time of report generation');

$objPHPExcel->getActiveSheet()->getStyle('D4')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
$objPHPExcel->getActiveSheet()->setCellValue('D4', date("H:i"));

$objPHPExcel->getActiveSheet()->mergeCells('B5:C5');
$objPHPExcel->getActiveSheet()->getStyle('B5:C5')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
$objPHPExcel->getActiveSheet()->setCellValue('B5', 'Messages');

$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
$objPHPExcel->getActiveSheet()->setCellValue('D5', '');

$objPHPExcel->getActiveSheet()->mergeCells('B6:C6');
$objPHPExcel->getActiveSheet()->getStyle('B6:C6')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'User 1');

$objPHPExcel->getActiveSheet()->mergeCells('D6:F6');
$objPHPExcel->getActiveSheet()->setCellValue('D6', $_SESSION["messages_report"]["company_from"]);

$objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('G6', $_SESSION["messages_report"]["user_from"]);




$objPHPExcel->getActiveSheet()->mergeCells('B7:C7');
$objPHPExcel->getActiveSheet()->getStyle('B7:C7')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
$objPHPExcel->getActiveSheet()->setCellValue('B7', 'User 2');

$objPHPExcel->getActiveSheet()->mergeCells('D7:F7');
$objPHPExcel->getActiveSheet()->setCellValue('D7', $_SESSION["messages_report"]["company_to"]);

$objPHPExcel->getActiveSheet()->getStyle('G7')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('G7', $_SESSION["messages_report"]["user_to"]);

$objPHPExcel->getActiveSheet()->mergeCells('B8:C8');
$objPHPExcel->getActiveSheet()->getStyle('B8:C8')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
$objPHPExcel->getActiveSheet()->setCellValue('B8', 'Exhibition');

$objPHPExcel->getActiveSheet()->mergeCells('D8:G8');
$objPHPExcel->getActiveSheet()->setCellValue('D8', $_SESSION["reports"]["exhibition"]["name"]);

$objPHPExcel->getActiveSheet()->mergeCells('D10:F10');
$objPHPExcel->getActiveSheet()->getStyle('D10:F10')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D10:F10')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
$objPHPExcel->getActiveSheet()->setCellValue('D10', 'TEXT OF MESSAGES');

$i=12;

if (!empty($_SESSION["messages_report"]["mess"])) {
	foreach ($_SESSION["messages_report"]["mess"] as $k=>$mes) {

		if ($mes["AUTHOR_ID"]==$_SESSION["messages_report"]["company_from_id"]) {
			$user = $_SESSION["messages_report"]["user_from"];
			$place = "F";
		} else {
			$user = $_SESSION["messages_report"]["user_to"];
			$place = "G";
		}

		$objPHPExcel->getActiveSheet()->getStyle('A' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray(
				array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
		$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $k+1);

		$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->applyFromArray(
				array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
		$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, date("d/m/Y", strtotime($mes["DATE_CREATE"])));

		$objPHPExcel->getActiveSheet()->getStyle('C' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('C' . $i)->applyFromArray(
				array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
		$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, date("H:i", strtotime($mes["DATE_CREATE"])));

		$objPHPExcel->getActiveSheet()->mergeCells('D' . $i . ':E' . $i);
		$objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':E' . $i)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':E' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('D' . $i . ':E' . $i)->applyFromArray(
				array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
		$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $user);

		$objPHPExcel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F' . $i)->applyFromArray(
				array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT)));


		$objPHPExcel->getActiveSheet()->setCellValue($place . $i, $mes["MESSAGE"]);

		$objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('G' . $i)->applyFromArray(
				array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT)));
		//$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, '');

		$i++;
	}
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Messages');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

// Redirect output to a client's web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.iconv("cp1251","UTF-8","messages.xlsx").'"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');

$_SESSION["table_order"] = '';

//LocalRedirect("/administrator/more/bill/bill_prepare.php?sid=".$_SESSION["link_params"]["sid"]."&ord_id=".$_SESSION["link_params"]["ord_id"]."&uid=".$_SESSION["link_params"]["uid"]."&id=".$_SESSION["link_params"]["id"]);

exit;

?>


