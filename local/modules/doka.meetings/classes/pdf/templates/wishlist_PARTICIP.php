<?
function DokaGeneratePdf($arResult) {
	$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->AddPage();
	$pdf->ImageSVG($file=DOKA_MEETINGS_MODULE_DIR . '/images/logo.svg', $x=30, $y=5, $w='150', $h='', $link='', $align='', $palign='', $border=0, $fitonpage=false);
	$pdf->setXY(15,25);
	$pdf->SetFont('freeserif','B',17);
    // Если в свойствах выставки отмечено "Есть сессия НВ"
    if($arResult["PARAM_EXHIBITION"]["PROPERTIES"]["HB_EXIST"]['VALUE']){
        // Если в настройках встреч отмечено "Сессия с НВ"
        if($arResult["EXHIBITION"]["IS_HB"]){
            $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_EN"]['VALUE'] .= " Hosted Buyers session";
            $dayline = "Day 1 - February 28, 2019";
        }
        else {
            $dayline = "Day 2 - March 1, 2019";
        }
    }
	$pdf->multiCell(180, 5, "Wish-list " . $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_EN"]['VALUE'] . " for ", 0, C);
	$pdf->SetFont('freeserif','',15);
	$pdf->setXY(30,$pdf->getY() + 2);
	$pdf->multiCell(210, 5, $arResult["USER"]['COMPANY'], 0, L);
	$pdf->setXY(30,$pdf->getY() + 1);
	$pdf->multiCell(210, 5, $arResult["USER"]['REP'], 0, L);
	$pdf->setXY(0,$pdf->getY() + 4);
	$pdf->SetFont('freeserif','B',17);
	$pdf->multiCell(210, 5, $dayline, 0, C);
	$pdf->SetX(50);
	$pdf->SetFont('freeserif','B',13);

	/* Формируем таблицу */
	$pdf->setXY(0,$pdf->getY() + 5);
	$pdf->multiCell(210, 5, "You requested an appointment with these companies,\n but they declined your requests or their schedules were full:", 0, C);

	if (!$arResult['WISH_IN']) {
		$pdf->setXY(0, $pdf->getY() + 5);
		$pdf->SetFont('freeserif','',13);
		$pdf->multiCell(210, 5, "You don't have any companies in this section", 0, C);
	} else {
		$pdf->setXY(20,$pdf->getY() + 5);
		$pdf->SetFont('freeserif','',11);

		$tbl = '<table cellspacing="0" cellpadding="5" border="1">
			<tr>
				<td align="center" width="40">Num.</td>
				<td align="center" width="220">Companies</td>
				<td align="center" width="160">Representative</td>
				<td align="center" width="90">Reason</td>
			</tr>';
		$i = 1;
		foreach ($arResult['WISH_IN'] as $item) {
		  	$tbl .= '<tr>
				<td align="center">' . $i . '</td>
				<td>' . $item["company_name"] . '</td>
				<td>' . $item["company_rep"] . '</td>
				<td>' . $arResult['STATUS_REQUEST'][ $item["company_reason"] ] . '</td>
			</tr>';
			$i++;
		}
	    $tbl .= '</table>';
	    $pdf->writeHTML($tbl, true, false, false, false, '');
	}


	$pdf->SetFont('freeserif','B',13);
	$pdf->setXY(0,$pdf->getY() + 10);
	$pdf->multiCell(210, 5, "These companies requested an appointment with you,\n but you declined their requests or your schedule was full:", 0, C);

	if (!$arResult["WISH_OUT"]) {
		$pdf->setXY(0,$pdf->getY() + 5);
		$pdf->SetFont('freeserif','',13);
		$pdf->multiCell(210, 5, "You don't have any companies in this section", 0, C);
	} else {
		$pdf->setXY(20,$pdf->getY() + 5);
		$pdf->SetFont('freeserif','',11);

		$tbl = '<table cellspacing="0" cellpadding="5" border="1">
			<tr>
				<td align="center" width="40">Num.</td>
				<td align="center" width="220">Companies</td>
				<td align="center" width="160">Representative</td>
				<td align="center" width="90">Reason</td>
			</tr>';
		$i = 1;
		foreach ($arResult['WISH_OUT'] as $item) {
		  	$tbl .= '<tr>
				<td align="center">' . $i . '</td>
				<td>' . $item["company_name"] . '</td>
				<td>' . $item["company_rep"] . '</td>
				<td>' . $arResult['STATUS_REQUEST'][ $item["company_reason"] ] . '</td>
			</tr>';
			$i++;
		}
	  $tbl .= '</table>';
	  $pdf->writeHTML($tbl, true, false, false, false, '');

	}

	$pdf->setXY(20,$pdf->getY() + 10);
	$html = '<p>These companies were not included in your schedule because either your or their schedule was already full.</p>';
	$html .= '<p>You can meet these companies at any time except for the morning session. Please message each guest individually, and make an appointment for any  time that suits you – for example, at the evening session, or during lunch or coffee break, or you may even schedule an appointment at the guest’s   office for any day after the Luxury Travel Mart.</p>';
	$pdf->writeHTML($html, true, false, false, false, '');

	$pdf->Output("print_wish.pdf", I);
	die();
}


?>