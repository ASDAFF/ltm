<?
function DokaGeneratePdf($arResult) {
	$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->AddPage();
	$pdf->ImageSVG($file=DOKA_MEETINGS_MODULE_DIR . '/images/logo.svg', $x=30, $y=5, $w='150', $h='', $link='', $align='', $palign='', $border=0, $fitonpage=false);
	$pdf->setXY(15,30);
	$pdf->SetFont('freeserif','B',17);
	$pdf->multiCell(180, 5, "Wish-list " . $arResult["exhib"]["TITLE"] . " for ", 0, C);
	$pdf->SetFont('freeserif','',15);
	$pdf->setXY(30,50);
	$pdf->multiCell(210, 5, $arResult['name'], 0, L);
	$pdf->setXY(30,60);
	$pdf->multiCell(210, 5, $arResult['rep'], 0, L);

	$pdf->SetFont('freeserif','B',13);
	$pdf->SetX(50);

	/* Формируем таблицу */
	$pdf->setXY(0,80);
	$pdf->multiCell(210, 5, "You requested an appointment with these companies,\n but they declined your requests or their schedules were full:", 0, C);

	if (!$arResult['wish_in']) {
		$pdf->setXY(0, $pdf->getY() + 5);
		$pdf->SetFont('freeserif','',13);
		$pdf->multiCell(210, 5, "You don't have any companies in this section", 0, C);
	} else {
		$pdf->setXY(20,$pdf->getY() + 5);
		$pdf->SetFont('freeserif','',13);

		$tbl = '<table cellspacing="0" cellpadding="5" border="1">
			<tr>
				<td align="center" width="60">Number</td>
				<td align="center" width="250">Companies</td>
				<td align="center" width="200">Representative</td>
			</tr>';
		$i = 1;
		foreach ($arResult['wish_in'] as $item) {
		  	$tbl .= '<tr>
				<td align="center">' . $i . '</td>
				<td>' . $item["company_name"] . '</td>
				<td>' . $item["company_rep"] . '</td>
			</tr>';
			$i++;
		}
	    $tbl .= '</table>';
	    $pdf->writeHTML($tbl, true, false, false, false, '');
	}


	$pdf->SetFont('freeserif','B',13);
	$pdf->setXY(0,$pdf->getY() + 20);
	$pdf->multiCell(210, 5, "These companies requested an appointment with you,\n but you declined their requests or your schedule was full:", 0, C);

	if (!$arResult["wish_out"]) {
		$pdf->setXY(0,$pdf->getY() + 5);
		$pdf->SetFont('freeserif','',13);
		$pdf->multiCell(210, 5, "You don't have any companies in this section", 0, C);
	} else {
		$pdf->setXY(20,$pdf->getY() + 5);
		$pdf->SetFont('freeserif','',13);

		$tbl = '<table cellspacing="0" cellpadding="5" border="1">
			<tr>
				<td align="center" width="60">Number</td>
				<td align="center" width="250">Companies</td>
				<td align="center" width="200">Representative</td>
			</tr>';
		$i = 1;
		foreach ($arResult['wish_out'] as $item) {
		  	$tbl .= '<tr>
				<td align="center">' . $i . '</td>
				<td>' . $item["company_name"] . '</td>
				<td>' . $item["company_rep"] . '</td>
			</tr>';
			$i++;
		}
	  $tbl .= '</table>';
	  $pdf->writeHTML($tbl, true, false, false, false, '');

	}

	$pdf->setXY(20,$pdf->getY() + 10);
	$html = '<p>These companies were not included in your schedule because either your or their schedule was already full.</p>';
	$pdf->writeHTML($html, true, false, false, false, '');
	
	$pdf->setXY(20,$pdf->getY() + 20);
	$html = 'You can meet these companies at any time except for the morning session. Please message each guest individually, and make an appointment for any time that suits you – for example, at the evening session, or during lunch or coffee break, or you may even schedule an appointment at the guest’s office for any day after the Luxury Travel Mart.';
	$pdf->writeHTML($html, true, false, false, false, '');

	$pdf->Output($arResult['path'], F);
	unset($pdf);
}


?>