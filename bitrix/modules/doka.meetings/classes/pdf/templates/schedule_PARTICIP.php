<?
function DokaGeneratePdf($arResult) {
	$pdf = new TCPDF('P', 'mm', 'A4', false, 'UTF-8', false);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	// $pdf->AddFont('times','I','timesi.php');
	$pdf->AddPage();
	$pdf->ImageSVG($file=DOKA_MEETINGS_MODULE_DIR . '/images/logo.svg', $x=30, $y=5, $w='150', $h='', $link='', $align='', $palign='', $border=0, $fitonpage=false);

	$pdf->setXY(0,25);
	$pdf->SetFont('times','B',18);
	$pdf->multiCell(220, 6, iconv('utf-8', 'windows-1251', 'Personal diary during the morning session'), 0, C);
	$pdf->SetFont('times','B',18);
	if($arResult["EXHIBITION"]["IS_HB"]){
		$arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_EN"]['VALUE'] .= " Hosted Buyers session";
	}
	$pdf->multiCell(200, 6, "at " . $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_EN"]['VALUE'], 0, C);
	$pdf->SetFont('times','',15);
	$pdf->setXY(30,45);
	$pdf->multiCell(210, 5, $arResult["USER"]['COMPANY'], 0, L);
	$pdf->setXY(30,50);
	$pdf->multiCell(210, 5, iconv('utf-8', 'windows-1251', $arResult["USER"]['REP']), 0, L);
	$pdf->SetFont('times','',13);
	$pdf->setXY(30,60);
	if($arResult["EXHIBITION"]["IS_HB"] != '1'){
		if($arResult["HALL"] != "None"){
			$pdf->multiCell(210, 5, "Hall, Table: ".$arResult["HALL"].", ".$arResult["TABLE"], 0, L);
		}
		else{
			$pdf->multiCell(210, 5, "Hall, Table: ", 0, L);
		}
		$pdf->setXY(0,70);
	}
	$pdf->SetFont('Times','',11);
	$pdf->SetX(20);

	/* Формируем таблицу */
	if($arResult["EXHIBITION"]["IS_HB"] == '1'){
		$header = '
		<table cellspacing="0" cellpadding="5" border="1">
				<tr>
					<th align="center" width="70">Time</th>
					<th align="center" width="240">Companies</th>
					<th align="center" width="90"> </th>
					<td align="center" width="95">Hall, Table</td>
				</tr>
		';
		$colspanGuest = 3;
	}
	else{
		$header = '
		<table cellspacing="0" cellpadding="5" border="1">
				<tr>
					<th align="center" width="75">Time</th>
					<th align="center" width="340">Companies</th>
					<th align="center" width="90"> </th>
				</tr>
		';
		$colspanGuest = 2;	
	}

	$tbl = $header;
	$count = 0;
	$countBreaks = 0;
	foreach ($arResult['SCHEDULE'] as $timeslot) {
		$count++;
		if ($timeslot['status'] == 'free') {
			$tbl .= '<tr>
				  <td>' . $timeslot['name'] . '</td>
				  <td colspan="'.$colspanGuest.'" align="center">Free time</td>
			  </tr>';
		}
		else if($timeslot['status'] == 'coffe' && !$countBreaks){
			$tbl .= '<tr>
				  <td>' . $timeslot['name'] . '</td>
				  <td colspan="'.$colspanGuest.'" align="center">Coffee-break</td>
			  </tr>';
			  $countBreaks++;
			}
		else if($timeslot['status'] == 'coffe' && $countBreaks){
			$tbl .= '<tr>
				  <td>' . $timeslot['name'] . '</td>
				  <td colspan="'.$colspanGuest.'" align="center">Lunch</td>
			  </tr>';
			}
		else {
			if(!$arResult["EXHIBITION"]["IS_HB"]){
				$tbl .= '<tr>
					  <td>' . $timeslot['name'] . '</td>
					  <td>Company: ' . iconv('utf-8', 'windows-1251', $timeslot['company_name']) . '<br />Representative: ' . iconv('utf-8', 'windows-1251', $timeslot['company_rep']) . '</td>
					  <td align="center">' . $timeslot['notes'] . '</td>
				  </tr>';
			}
			else{
				$tbl .= '<tr>
					  <td>' . $timeslot['name'] . '</td>
					  <td>Company: ' . iconv('utf-8', 'windows-1251', $timeslot['company_name']) . '<br />Representative: ' . iconv('utf-8', 'windows-1251', $timeslot['company_rep']) . '</td>
					  <td align="center">' . $timeslot['notes'] . '</td>';
				if($timeslot['hall']){
				 	$tbl .= '<td>'.$timeslot['hall'].', '.iconv('utf-8', 'windows-1251', $timeslot['table']).'</td>
							</tr>';
				}
				else{
					$tbl .= '<td> </td>
                                </tr>';
				}		
			}
		}
		if ($count % $arResult['CUT'] == 0 && $count != count($arResult['SCHEDULE'])) {
			$tbl .= '</table>';
			$pdf->writeHTML($tbl, true, false, false, false, '');
			$pdf->setXY(0,$pdf->getY() + 1);
			$pdf->multiCell(210, 5, "continued on the next page", 0, C);
			$pdf->AddPage();
			$tbl = $header;
		}
	}
	$tbl .= '</table>';
	$pdf->writeHTML($tbl, true, false, false, false, '');

	$pdf->setXY(0,$pdf->getY() + 10);
	$pdf->multiCell(210, 5, "Please make your appointments in time; any delay in timing will effect the next exhibitor after you.", 0, C);
	$pdf->setXY(0,$pdf->getY() + 5);
	$pdf->multiCell(210, 5, "Please report all no-shows to your Hall Manager or to the registration desk of the Luxury Travel Mart.", 0, C);
	$pdf->Output("print.pdf", I);
	die();
	// var_dump($test);die();
	
}


?>