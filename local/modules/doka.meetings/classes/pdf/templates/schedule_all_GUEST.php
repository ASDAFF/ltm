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
	$arResult["exhib"]["TITLE_RU"] .= "\n";
	if($arResult["exhib"]["IS_HB"]){
		$dayline = "День 1, 2 марта 2017";
	}
	else {
		$dayline = "День 2, 3 марта 2017";
	}
	$pdf->multiCell(210, 5, "Расписание встреч на утренней сессии\n" . $arResult["exhib"]["TITLE_RU"] . $dayline, 0, C);
/*$pdf->multiCell(210, 5, "Список неподтвержденных запросов на\nLuxury Travel Mart Баку", 0, C);*/
	$pdf->SetFont('freeserif','',15);
	$pdf->setXY(30,$pdf->getY() + 2);
	$pdf->multiCell(210, 5, $arResult["name"].", ". $arResult['city'], 0, L);
	$pdf->setXY(30,$pdf->getY() + 1);
	if($arResult["USER"]['COL_REP'] == ""){
		$pdf->multiCell(210, 5, $arResult["rep"], 0, L);
	}
	else{
		$pdf->multiCell(210, 5, $arResult["rep"].", ".$arResult["col_rep"], 0, L);
	}
	$pdf->setXY(30,$pdf->getY() + 1);
	$pdf->multiCell(210, 5, "Мобильный телефон: ".$arResult["mob"], 0, L);
	$pdf->setXY(30,$pdf->getY() + 1);
	$pdf->multiCell(210, 5, "Телефон: ".$arResult["phone"], 0, L);
	$pdf->setXY(30,$pdf->getY() + 1);

	if($arResult["exhib"]["IS_HB"] && $arResult["hall"] != "None"){
		$pdf->multiCell(210, 5, "Hall, Table: ".$arResult["hall"].", ".$arResult["table"], 0, L);

		$pdf->setXY(0,90);
		$pdf->SetFont('freeserif','',13);
		$pdf->multiCell(210, 5, "Ваше расписание", 0, C);
		$pdf->setXY(20,100);
	}
	elseif($arResult["exhib"]["IS_HB"]){
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
	if($arResult["exhib"]["IS_HB"]){
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
	foreach ($arResult['schedule'] as $freeseriflot) {
		$count++;
		if ($freeseriflot['status'] == 'free') {
			$tbl .= '<tr>
                        <td>'.$freeseriflot['timeslot_name'].'</td>
                        <td colspan="3" align="center">Свободно</td>
                    </tr>';
		}
		else if($freeseriflot['status'] == 'coffee'){
			$tbl .= '<tr>
									<td>'.$freeseriflot['timeslot_name'].'</td>
									<td colspan="3" align="center">Перерыв на кофе</td>
							</tr>';
			}
		else if($freeseriflot['status'] == 'lunch'){
			$lunchText = ($arResult['APP_ID'] == 1)? 'Легкий обед': 'Обед';
			$tbl .= '<tr>
									<td>'.$freeseriflot['timeslot_name'].'</td>
									<td colspan="3" align="center">'.$lunchText.'</td>
							</tr>';
			}
		else {
			$tbl .= '<tr>
							<td>'.$freeseriflot['timeslot_name'].'</td>
							<td>Компания: '.$freeseriflot['company_name'].'<br />Представитель: '.$freeseriflot['company_rep'].'</td>
							<td align="center">' . $freeseriflot['notes']. '</td>';
			if(!$arResult["exhib"]["IS_HB"]){
				if($freeseriflot['hall']){
					 $tbl .= '<td>'.$freeseriflot['hall'].', '.$freeseriflot['table'].'</td>';
				}
				else{
					$tbl .= '<td> </td>';
	            }				
			}
			$tbl .= '</tr>';
		}
		if ($count % $arResult["exhib"]['CUT'] == 0 && $count != count($arResult['schedule'])) {
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
	$html = '<p><b>Регистрация гостей и выдача бейджей</b> будет проходить в день мероприятия на стойке регистрации Luxury Travel Mart
 <b>с 09:30 до 11:30.</b></p>';
	$html .= '<p>Пожалуйста, имейте при себе <b>достаточное количество визитных карточек на английском языке.</b><p>';
	$pdf->writeHTML($html, true, false, false, false, '');


	$pdf->Output($arResult['path'], F);
	unset($pdf);
}
?>