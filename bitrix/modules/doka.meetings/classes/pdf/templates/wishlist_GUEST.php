<?
function DokaGeneratePdf($arResult) {
	$pdf = new TCPDF('P', 'mm', 'A4', false, 'UTF-8', false);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	 $pdf->AddFont('times','I','timesi.php');
	$pdf->AddPage();
	$pdf->ImageSVG($file=DOKA_MEETINGS_MODULE_DIR . '/images/logo.svg', $x=30, $y=5, $w='150', $h='', $link='', $align='', $palign='', $border=0, $fitonpage=false);
	$pdf->setXY(0,23);
	$pdf->SetFont('times','B',17);
	if($arResult["EXHIBITION"]["IS_HB"]){
		$arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_RU"]['VALUE'] .= " Hosted Buyers сессия";
	}
	$pdf->multiCell(210, 5, "Список неподтвержденных запросов на\n" . $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_RU"]['VALUE'], 0, C);
	$pdf->SetFont('times','',15);
	$pdf->setXY(30,40);
	$pdf->multiCell(210, 5, iconv('utf-8', 'windows-1251', $arResult["USER"]['COMPANY']).", ". iconv('utf-8', 'windows-1251', $arResult["USER"]['CITY']), 0, L);
	$pdf->setXY(30,48);
	$pdf->multiCell(210, 5, iconv('utf-8', 'windows-1251', $arResult["USER"]['REP']), 0, L);


	$pdf->SetFont('times','B',13);
	$pdf->setXY(0,60);
	$pdf->multiCell(210, 5, "Вы также хотели бы встретиться со следующими компаниями", 0, C);

	$pdf->SetFont('times','',10);
	$pdf->setXY(0,65);
	$pdf->multiCell(210, 5, "(возможно, данные участники отклонили ваши запросы или их расписание уже полное):", 0, C);


	/* Формируем таблицу */
	if (!$arResult['WISH_IN']) {
		$pdf->setXY(0, $pdf->getY() + 5);
		$pdf->SetFont('Times','',13);
		$pdf->multiCell(210, 5, "Этот список запросов пуст.", 0, C);
	} else {
		$pdf->setXY(20,$pdf->getY() + 5);
		$pdf->SetFont('times','',13);

		$tbl = '<table cellspacing="0" cellpadding="5" border="1">
			<tr>
				<td align="center" width="60">№</td>
				<td align="center" width="250">Компания</td>
				<td align="center" width="200">Представитель</td>
			</tr>';
		$i = 1;
		foreach ($arResult['WISH_IN'] as $item) {
		  $tbl .= '<tr>
				<td align="center">'.$i.'</td>
				<td>'.iconv('utf-8', 'windows-1251', $item["company_name"]).'</td>
				<td>'.iconv('utf-8', 'windows-1251', $item["company_rep"]).'</td>
			</tr>';
			$i++;
		}
	    $tbl .= '</table>';
	    $pdf->writeHTML($tbl, true, false, false, false, '');
	}


	$pdf->SetFont('times','B',13);
	$pdf->setXY(0,$pdf->getY() + 20);
	$pdf->multiCell(210, 5, "С вами также хотели бы встретиться следующие компании", 0, C);

	$pdf->SetFont('times','',10);
	$pdf->setX(0);
	$pdf->multiCell(210, 5, "(возможно, вы отклонили запросы от этих участников или ваше расписание уже полное):", 0, C);

	if (!$arResult["WISH_OUT"]) {
		$pdf->setXY(0,$pdf->getY() + 5);
		$pdf->SetFont('Times','',13);
		$pdf->multiCell(210, 5, "Этот список запросов пуст.", 0, C);
	} else {
		$pdf->setXY(20,$pdf->getY() + 5);
		$pdf->SetFont('times','',13);

		$tbl = '<table cellspacing="0" cellpadding="5" border="1">
			<tr>
				<td align="center" width="60">№</td>
				<td align="center" width="250">Компания</td>
				<td align="center" width="200">Представитель</td>
			</tr>';
		$i = 1;
		foreach ($arResult['WISH_OUT'] as $item) {
		  $tbl .= '<tr>
				<td align="center">'.$i.'</td>
				<td>'.iconv('utf-8', 'windows-1251', $item["company_name"]).'</td>
				<td>'.iconv('utf-8', 'windows-1251', $item["company_rep"]).'</td>
			</tr>';
			$i++;
		}
	  $tbl .= '</table>';
	  $pdf->writeHTML($tbl, true, false, false, false, '');

	}

	$pdf->setXY(20,$pdf->getY() + 10);
	$y = $pdf->getY();
	$html = 'Вы можете встретиться со всеми компаниями, указанными выше, в любое другое время Luxury Travel
Mart, например, во время ланча, перерыва на кофе или на вечерней сессии.';
	$pdf->writeHTMLCell('', '', 20, $y, $html, $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true);

	$pdf->Output("print_wish.pdf", I);
	die();
}


?>