<?
function DokaGeneratePdf($arResult) {
	$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->AddFont('freeserif','I','freeserifi.php');
	$pdf->AddPage();
	$pdf->ImageSVG($file=DOKA_MEETINGS_MODULE_DIR . '/images/logo.svg', $x=30, $y=5, $w='150', $h='', $link='', $align='', $palign='', $border=0, $fitonpage=false);

	$pdf->setXY(0,25);
	$pdf->SetFont('freeserif', 'B',17);
	if($arResult["EXHIBITION"]["IS_HB"]){
		$arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_RU"]['VALUE'] .= " Hosted Buyers сессия\n";
		$dayline = "День 1, 10 марта 2016";
	}
	else {
		$dayline = "День 2, 11 марта 2016";
		$arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_RU"]['VALUE'] .= "\n";
	}
	$pdf->multiCell(210, 5, "Расписание встреч на утренней сессии\n" . $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_RU"]['VALUE'] . $dayline, 0, C);
/*$pdf->multiCell(210, 5, "Список неподтвержденных запросов на\nLuxury Travel Mart Баку", 0, C);*/
	$pdf->SetFont('freeserif','',15);
	$pdf->setXY(30,44);
	$pdf->multiCell(210, 5, $arResult["USER"]['COMPANY'].", ". $arResult["USER"]['CITY'], 0, L);
	$pdf->setXY(30,52);
	if($arResult["USER"]['COL_REP'] == ""){
		$pdf->multiCell(210, 5, $arResult["USER"]['REP'], 0, L);
	}
	else{
		$pdf->multiCell(210, 5, $arResult["USER"]['REP'].", ".$arResult["USER"]['COL_REP'], 0, L);
	}
	$pdf->setXY(30,60);
	$pdf->multiCell(210, 5, "Мобильный телефон: ".$arResult["USER"]['MOB'], 0, L);
	$pdf->setXY(30,68);
	$pdf->multiCell(210, 5, "Телефон: ".$arResult["USER"]['PHONE'], 0, L);
	$pdf->setXY(30,80);

	if($arResult["EXHIBITION"]["IS_HB"] && $arResult["HALL"] != "None"){
		$pdf->multiCell(210, 5, "Hall, Table: ".$arResult["HALL"].", ".$arResult["TABLE"], 0, L);

		$pdf->setXY(0,90);
		$pdf->SetFont('freeserif','',13);
		$pdf->multiCell(210, 5, "Ваше расписание", 0, C);
		$pdf->setXY(20,100);
	}
	elseif($arResult["EXHIBITION"]["IS_HB"]){
		$pdf->multiCell(210, 5, "Hall, Table: ", 0, L);
		
		$pdf->setXY(0,90);
		$pdf->SetFont('freeserif','',13);
		$pdf->multiCell(210, 5, "Ваше расписание", 0, C);
		$pdf->setXY(20,100);
	}
	else{
		$pdf->setX(0);
		$pdf->SetFont('freeserif','',13);
		$pdf->multiCell(210, 5, "Ваше расписание", 0, C);
		$pdf->setXY(20,90);
	}

	$pdf->SetFont('freeserif','',10);

	/* Формируем таблицу */
	if($arResult["EXHIBITION"]["IS_HB"]){
        $header = '<table cellspacing="0" cellpadding="5" border="1">
    <tr>
        <td align="center" width="70">Время</td>
        <td align="center" width="240">Участники</td>
        <td align="center" width="90">Статус</td>
    </tr>';
	}
	else{
        $header = '<table cellspacing="0" cellpadding="5" border="1">
    <tr>
        <td align="center" width="70">Время</td>
        <td align="center" width="240">Участники</td>
        <td align="center" width="90">Статус</td>
        <td align="center" width="95">Зал, Стол</td>
    </tr>';		
	}

	$tbl = $header;
	$count = 0;
	$countBreaks = 0;
	foreach ($arResult['SCHEDULE'] as $freeseriflot) {
		$count++;
		if ($freeseriflot['status'] == 'free') {
			$tbl .= '<tr>
                        <td>'.$freeseriflot['name'].'</td>
                        <td colspan="3" align="center">Свободно</td>
                    </tr>';
		}
		else if($freeseriflot['status'] == 'coffe' && !$countBreaks){
			$tbl .= '<tr>
                        <td>'.$freeseriflot['name'].'</td>
                        <td colspan="3" align="center">Перерыв на кофе</td>
                    </tr>';
            $countBreaks++;
			}
		else if($freeseriflot['status'] == 'coffe' && $countBreaks){
			$tbl .= '<tr>
                        <td>'.$freeseriflot['name'].'</td>
                        <td colspan="3" align="center">Перерыв на обед</td>
                    </tr>';
			}
		else {
			$tbl .= '<tr>
							<td>'.$freeseriflot['name'].'</td>
							<td>Компания: '.$freeseriflot['company_name'].'<br />Представитель: '.$freeseriflot['company_rep'].'</td>
							<td align="center">' . $freeseriflot['notes']. '</td>';
			if(!$arResult["EXHIBITION"]["IS_HB"]){
				if($freeseriflot['hall']){
					 $tbl .= '<td>'.$freeseriflot['hall'].', '.$freeseriflot['table'].'</td>';
				}
				else{
					$tbl .= '<td> </td>';
	            }				
			}
			$tbl .= '</tr>';
		}
		if ($count % $arResult['CUT'] == 0 && $count != count($arResult['SCHEDULE'])) {
			$tbl .= '</table>';
			$pdf->writeHTML($tbl, true, false, false, false, '');
			$pdf->setXY(0,$pdf->getY() + 1);
			$pdf->multiCell(210, 5, "продолжение на следующей странице", 0, C);
			$pdf->AddPage();
			$pdf->setX(20);
			$tbl = $header;
		}
	}
	$tbl .= '</table>';
	$pdf->writeHTML($tbl, true, false, false, false, '');

	$pdf->setXY(20,$pdf->getY() + 10);
	$y = $pdf->getY();
	$html = '<b>Регистрация гостей и выдача бейджей</b> будет проходить в день мероприятия на стойке регистрации Luxury Travel Mart <b>с 09:30 до 11:30.</b>';
	$pdf->writeHTMLCell('', '', 20, $y, $html, $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true);

	$pdf->setY($pdf->getY() + 10);
	$y = $pdf->getY();
	$html = 'Пожалуйста, имейте при себе <b>достаточное количество визитных карточек на английском языке.</b>';
	$pdf->writeHTMLCell('', '', 20, $y, $html, $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true);

	$pdf->Output("print.pdf", I);
	die();
	// var_dump($test);die();
	
}


?>