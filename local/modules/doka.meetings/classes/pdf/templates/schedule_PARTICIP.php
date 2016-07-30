<?
function DokaGeneratePdf($arResult) {
	$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	// $pdf->AddFont('freeserif','I','freeserifi.php');
	$pdf->AddPage();
	$pdf->ImageSVG($file=DOKA_MEETINGS_MODULE_DIR . '/images/logo.svg', $x=30, $y=5, $w='150', $h='', $link='', $align='', $palign='', $border=0, $fitonpage=false);

	$pdf->setXY(0,25);
	$pdf->SetFont('freeserif','B',18);
	$pdf->multiCell(220, 6, 'Personal diary during the morning session', 0, C);
	$pdf->SetFont('freeserif','B',18);
	if($arResult["EXHIBITION"]["IS_HB"]){
		$arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_EN"]['VALUE'] .= " Hosted Buyers session\n";
		$dayline = "Day 1 - March 10, 2016";
	}
	else {
		$dayline = "Day 2 - March 11, 2016";
		$arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_EN"]['VALUE'] .= "\n";
	}
	$pdf->multiCell(200, 6, "at " . $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_EN"]['VALUE'] . $dayline, 0, C);
	$pdf->SetFont('freeserif','',15);
	$pdf->setXY(30,$pdf->getY() + 2);
	$pdf->multiCell(210, 5, $arResult["USER"]['COMPANY'], 0, L);
	$pdf->setXY(30,$pdf->getY() + 1);
	$pdf->multiCell(210, 5, $arResult["USER"]['REP'], 0, L);
	$pdf->SetFont('freeserif','',13);
	$pdf->setXY(30,$pdf->getY() + 1);
	if($arResult["EXHIBITION"]["IS_HB"] != '1'){
		if($arResult["HALL"] != "None"){
			$pdf->multiCell(210, 5, "Hall, Table: ".$arResult["HALL"].", ".$arResult["TABLE"], 0, L);
		}
		else{
			$pdf->multiCell(210, 5, "Hall, Table: ", 0, L);
		}
		$pdf->setXY(30,$pdf->getY() + 2);
	}
	$pdf->SetFont('freeserif','',11);
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
	foreach ($arResult['SCHEDULE'] as $freeseriflot) {
		$count++;
		if ($freeseriflot['status'] == 'free') {
			$tbl .= '<tr>
				  <td>' . $freeseriflot['name'] . '</td>
				  <td colspan="'.$colspanGuest.'" align="center">Free time</td>
			  </tr>';
		}
		else if($freeseriflot['status'] == 'coffee'){
			$tbl .= '<tr>
				  <td>' . $freeseriflot['name'] . '</td>
				  <td colspan="'.$colspanGuest.'" align="center">Coffee-break</td>
			  </tr>';
			}
		else if($freeseriflot['status'] == 'lunch'){
			$tbl .= '<tr>
				  <td>' . $freeseriflot['name'] . '</td>
				  <td colspan="'.$colspanGuest.'" align="center">Lunch</td>
			  </tr>';
			}
		else {
			if(!$arResult["EXHIBITION"]["IS_HB"]){
				$tbl .= '<tr>
					  <td>' . $freeseriflot['name'] . '</td>
					  <td>Company: ' . $freeseriflot['company_name'] . '<br />Representative: ' . $freeseriflot['company_rep'] . '</td>
					  <td align="center">' . $freeseriflot['notes'] . '</td>
				  </tr>';
			}
			else{
				$tbl .= '<tr>
					  <td>' . $freeseriflot['name'] . '</td>
					  <td>Company: ' . $freeseriflot['company_name'] . '<br />Representative: ' . $freeseriflot['company_rep'] . '</td>
					  <td align="center">' . $freeseriflot['notes'] . '</td>';
				if($freeseriflot['hall']){
				 	$tbl .= '<td>'.$freeseriflot['hall'].', '.$freeseriflot['table'].'</td>
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