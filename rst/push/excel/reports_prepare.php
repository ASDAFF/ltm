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
$objPHPExcel->getProperties()->setCreator("luxurytravelmart.ru")
							 ->setLastModifiedBy("luxurytravelmart.ru")
							 ->setTitle("Reports")
							 ->setSubject("Reports")
							 ->setDescription("Reports")
							 ->setKeywords("Reports")
							 ->setCategory("Reports");
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
$objPHPExcel->getActiveSheet()->setCellValue('B2', 'MAIN REPORT');

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


$objPHPExcel->getActiveSheet()->mergeCells('B6:C6');
$objPHPExcel->getActiveSheet()->getStyle('B6:C6')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'BUYER');

$objPHPExcel->getActiveSheet()->mergeCells('D6:F6');
$objPHPExcel->getActiveSheet()->setCellValue('D6', $_SESSION["reports"]["COMPANY"]);

$objPHPExcel->getActiveSheet()->mergeCells('B7:C7');
$objPHPExcel->getActiveSheet()->getStyle('B7:C7')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
$objPHPExcel->getActiveSheet()->setCellValue('B7', 'Exhibition');

$objPHPExcel->getActiveSheet()->mergeCells('D7:F7');
$objPHPExcel->getActiveSheet()->setCellValue('D7', $_SESSION["reports"]["exhibition_name"]);

$objPHPExcel->getActiveSheet()->mergeCells('D9:F9');
$objPHPExcel->getActiveSheet()->getStyle('D9:F9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D9:F9')->applyFromArray(
		array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
$objPHPExcel->getActiveSheet()->setCellValue('D9', 'REPORT FOR EVERYONE MEETINGS');




$i=10;

if (!empty($_SESSION["reports"]["list"])) {
	foreach ($_SESSION["reports"]["list"] as $k1=>$v1) {

		$objPHPExcel->getActiveSheet()->getStyle('A' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray(
				array(
						'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
						'borders' => array(
								'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						),
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->applyFromArray(
				array(
						'borders' => array(
								'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						),
				)
		);
		$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, 'EXHIBITOR:');

		$objPHPExcel->getActiveSheet()->mergeCells('C' . $i . ':E' . $i);
		$objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':E' . $i)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':E' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('C' . $i . ':E' . $i)->applyFromArray(
				array(
						'borders' => array(
								'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						),
				)
		);
		$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $v1["USER"]);

		$objPHPExcel->getActiveSheet()->getStyle('F' . $i)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F' . $i)->applyFromArray(
				array(
						'borders' => array(
								'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						),
				)
		);
		$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, 'Question');

		$objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('G' . $i)->applyFromArray(
				array(
						'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
						'borders' => array(
								'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						),
				)
		);
		$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, 'Answer');


		$i++;


$z=1;
		foreach ($v1 as $k2=>$v2) {
			if ($k2!=13 AND $k2!=14 AND $k2!="USER") {
				$question = '';
				if ($v2["QUESTION"]) $question = $v2["QUESTION"];

				$answer = '';
				if ($v2["ANSWER"]) $answer = $v2["ANSWER"];

				$objPHPExcel->getActiveSheet()->getStyle('A' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray(
						array(
								'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT),
						)
				);
				$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $z);

				$objPHPExcel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $v2["QUESTION"]);

				$objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('G' . $i)->applyFromArray(
						array(
								'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
								'borders' => array(
										'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
								),
						)
				);
				$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $answer);

				$i++;
				$z++;

			}
		}


		$objPHPExcel->getActiveSheet()->getStyle('A' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray(
				array(
						'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT),
				)
		);
		$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, '');
		$objPHPExcel->getActiveSheet()->getStyle('A' . $i)->applyFromArray(
				array(
						'borders' => array(
								'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						),
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->applyFromArray(
				array(
						'borders' => array(
								'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						),
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('C' . $i)->applyFromArray(
				array(
						'borders' => array(
								'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						),
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('D' . $i)->applyFromArray(
				array(
						'borders' => array(
								'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						),
				)
		);
		$objPHPExcel->getActiveSheet()->getStyle('E' . $i)->applyFromArray(
				array(
						'borders' => array(
								'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						),
				)
		);

		$objPHPExcel->getActiveSheet()->getStyle('F' . $i)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F' . $i)->applyFromArray(
				array(
						'borders' => array(
								'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						),
				)
		);
		$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $v1[13]["QUESTION"]);

		$objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('G' . $i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('G' . $i)->applyFromArray(
				array(
						'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
						'borders' => array(
								'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
								'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						),
				)
		);
		$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $v1[13]["ANSWER"]);

		$i++;
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
header('Content-Disposition: attachment;filename="'.iconv("cp1251","UTF-8","reports.xlsx").'"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');

$_SESSION["reports"] = '';

//LocalRedirect("/administrator/more/bill/bill_prepare.php?sid=".$_SESSION["link_params"]["sid"]."&ord_id=".$_SESSION["link_params"]["ord_id"]."&uid=".$_SESSION["link_params"]["uid"]."&id=".$_SESSION["link_params"]["id"]);

exit;

?>


