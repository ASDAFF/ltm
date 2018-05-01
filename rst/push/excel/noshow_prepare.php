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
							 ->setTitle("Reports no-show")
							 ->setSubject("Reports no-show")
							 ->setDescription("Reports no-show")
							 ->setKeywords("Reports no-show")
							 ->setCategory("Reports no-show");
$objPHPExcel->setActiveSheetIndex(0);


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12.11);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(14.12);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12.11);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12.11);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(36.42);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(28.84);

$objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(68);


$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(12); 
	

$objPHPExcel->getActiveSheet()->mergeCells('B2:G2');
$objPHPExcel->getActiveSheet()->getStyle('B2:F2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2:G2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B2:G2')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'REPORT NO-SHOW');

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
$objPHPExcel->getActiveSheet()->setCellValue('B5', 'Qnt. Of reports');

$objPHPExcel->getActiveSheet()->mergeCells('D6:F6');
$objPHPExcel->getActiveSheet()->setCellValue('D6', $_SESSION["no_show_report"]["reports"]);

$objPHPExcel->getActiveSheet()->mergeCells('B6:C6');
$objPHPExcel->getActiveSheet()->getStyle('B6:C6')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'Participant');

$objPHPExcel->getActiveSheet()->mergeCells('D6:F6');
$objPHPExcel->getActiveSheet()->setCellValue('D6', $_SESSION["no_show_report"]["COMPANY_WHO"]);

$objPHPExcel->getActiveSheet()->getStyle('G6')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->setCellValue('G6', $_SESSION["no_show_report"]["FIO_WHO"]);

$objPHPExcel->getActiveSheet()->mergeCells('B8:C8');
$objPHPExcel->getActiveSheet()->getStyle('B8:C8')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
$objPHPExcel->getActiveSheet()->setCellValue('B8', 'Exhibition');

$objPHPExcel->getActiveSheet()->mergeCells('D8:F8');
$objPHPExcel->getActiveSheet()->setCellValue('D8', $_SESSION["no_show_report"]["exhibition_name"]);

$objPHPExcel->getActiveSheet()->mergeCells('D10:F10');
$objPHPExcel->getActiveSheet()->getStyle('D10:F10')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D10:F10')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
$objPHPExcel->getActiveSheet()->setCellValue('D10', 'REPORTS');



$i=11;

$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray(
		array(
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, '#');

$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'Time');

$objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':E'.$i)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':E'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':E'.$i)->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Participant');

$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray(
		array(
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, 'Person');

$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray(
		array(
			'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
			'borders' => array(
				'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
				'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			),
		)
	);
$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, 'Date of Report no-show');


$i=12;
foreach ($_SESSION["no_show_report"]["list"] as $k=>$item) {

	$objPHPExcel->getActiveSheet()->getStyle('A' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray(
			array(
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
			)
	);
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, ($k+1));

	$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->applyFromArray(
			array(
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
			)
	);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $item["REP_TIME"]);

	$objPHPExcel->getActiveSheet()->mergeCells('C' . $i . ':E' . $i);
	$objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':E' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':E' . $i)->applyFromArray(
			array(
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
			)
	);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $item["COMPANY"]);

	$objPHPExcel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('F' . $i)->applyFromArray(
			array(
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
			)
	);
	$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $item["FIO"]);

	$objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('G' . $i)->applyFromArray(
			array(
					'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
			)
	);
	$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $item["REP_DATE"]);
	$i++;
}




// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Report noshow');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

// Redirect output to a client's web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.iconv("cp1251","UTF-8","reports-noshow.xlsx").'"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');

$_SESSION["no_show_report"] = '';
unset($_SESSION["no_show_report"]);
//LocalRedirect("/administrator/more/bill/bill_prepare.php?sid=".$_SESSION["link_params"]["sid"]."&ord_id=".$_SESSION["link_params"]["ord_id"]."&uid=".$_SESSION["link_params"]["uid"]."&id=".$_SESSION["link_params"]["id"]);

exit;

?>


