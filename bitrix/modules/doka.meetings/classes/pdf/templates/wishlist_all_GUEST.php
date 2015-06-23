<?
function DokaGeneratePdf($arResult) {
	$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	 $pdf->AddFont('freeserif','I','freeserifi.php');
	$pdf->AddPage();
	$pdf->ImageSVG($file=DOKA_MEETINGS_MODULE_DIR . '/images/logo.svg', $x=30, $y=5, $w='150', $h='', $link='', $align='', $palign='', $border=0, $fitonpage=false);
	$pdf->setXY(0,23);
	$pdf->SetFont('freeserif','B',17);
	$pdf->multiCell(210, 5, "Список неподтвержденных запросов на\n" . $arResult["exhib"]["TITLE_RU"], 0, C);
	$pdf->SetFont('freeserif','',15);
	$pdf->setXY(30,40);
	$pdf->multiCell(210, 5, $arResult['name'].", ". $arResult['city'], 0, L);
	$pdf->setXY(30,48);
	$pdf->multiCell(210, 5, $arResult['rep'], 0, L);


	$pdf->SetFont('freeserif','B',13);
	$pdf->setXY(0,60);
	$pdf->multiCell(210, 5, "Вы также хотели бы встретиться со следующими компаниями", 0, C);

	$pdf->SetFont('freeserif','',10);
	$pdf->setXY(0,65);
	$pdf->multiCell(210, 5, "(возможно, данные участники отклонили ваши запросы или их расписание уже полное):", 0, C);


	/* Формируем таблицу */
	if (!$arResult['wish_in']) {
		$pdf->setXY(0, $pdf->getY() + 5);
		$pdf->SetFont('freeserif','',13);
		$pdf->multiCell(210, 5, "Этот список запросов пуст.", 0, C);
	} else {
		$pdf->setXY(20,$pdf->getY() + 5);
		$pdf->SetFont('freeserif','',13);

		$tbl = '<table cellspacing="0" cellpadding="5" border="1">
			<tr>
				<td align="center" width="60">№</td>
				<td align="center" width="250">Компания</td>
				<td align="center" width="200">Представитель</td>
			</tr>';
		$i = 1;
		foreach ($arResult['wish_in'] as $item) {
		  $tbl .= '<tr>
				<td align="center">'.$i.'</td>
				<td>'.$item["company_name"].'</td>
				<td>'.$item["company_rep"].'</td>
			</tr>';
			$i++;
		}
	    $tbl .= '</table>';
	    $pdf->writeHTML($tbl, true, false, false, false, '');
	}


	$pdf->SetFont('freeserif','B',13);
	$pdf->setXY(0,$pdf->getY() + 20);
	$pdf->multiCell(210, 5, "С вами также хотели бы встретиться следующие компании", 0, C);

	$pdf->SetFont('freeserif','',10);
	$pdf->setX(0);
	$pdf->multiCell(210, 5, "(возможно, вы отклонили запросы от этих участников или ваше расписание уже полное):", 0, C);

	if (!$arResult["wish_out"]) {
		$pdf->setXY(0,$pdf->getY() + 5);
		$pdf->SetFont('freeserif','',13);
		$pdf->multiCell(210, 5, "Этот список запросов пуст.", 0, C);
	} else {
		$pdf->setXY(20,$pdf->getY() + 5);
		$pdf->SetFont('freeserif','',13);

		$tbl = '<table cellspacing="0" cellpadding="5" border="1">
			<tr>
				<td align="center" width="60">№</td>
				<td align="center" width="250">Компания</td>
				<td align="center" width="200">Представитель</td>
			</tr>';
		$i = 1;
		foreach ($arResult['wish_out'] as $item) {
		  $tbl .= '<tr>
				<td align="center">'.$i.'</td>
				<td>'.$item["company_name"].'</td>
				<td>'.$item["company_rep"].'</td>
			</tr>';
			$i++;
		}
	  $tbl .= '</table>';
	  $pdf->writeHTML($tbl, true, false, false, false, '');

	}

	$pdf->setXY(20,$pdf->getY() + 10);
	$html = '<p>Вы можете встретиться со всеми компаниями, указанными выше, в любое другое время Luxury Travel
Mart, например, во время ланча, перерыва на кофе или на вечерней сессии.<p>';
	$pdf->writeHTML($html, true, false, false, false, '');

	$pdf->Output($arResult['path'], F);
	unset($pdf);
}


?>