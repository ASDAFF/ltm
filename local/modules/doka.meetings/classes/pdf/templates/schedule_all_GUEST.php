<?

/** Extend the TCPDF class to create custom Footer*/
class MYPDF extends TCPDF
{
    /** Custom page header and footer are defined by extending the TCPDF class
     * and overriding the Header() and Footer() methods*/
    public function Footer()// Page footer
    {
        $this->SetY(-15);// Position at 15 mm from bottom
        $this->SetFont('helvetica', 'I', 9);// Set font
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

function DokaGeneratePdf($arResult)
{
    // create new PDF document
    $pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(true);
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->AddFont('freeserif', 'I', 'freeserifi.php');
    $pdf->AddPage();
    $pdf->ImageSVG($file = DOKA_MEETINGS_MODULE_DIR . '/images/logo.svg', $x = 30, $y = 5, $w = '150', $h = '', $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);
    $style = array(
        'border' => 2,
        'vpadding' => 'auto',
        'hpadding' => 'auto',
        'fgcolor' => array(0,0,0),
        'bgcolor' => false, //array(255,255,255)
        'module_width' => 1, // width of a single module in points
        'module_height' => 1 // height of a single module in points
    );
    $pdf->write2DBarcode($arResult["id"], 'QRCODE,H', 160, 5, 35, 35, $style, 'N');
    $pdf->setXY(0, 25);
    $pdf->SetFont('freeserif', 'B', 17);
    $arResult["exhib"]["TITLE_RU"] .= "\n";
    // Если в свойствах выставки отмечено "Есть сессия НВ"
    if($arResult["exhib"]["HB_EXIST"]){
        // Если в настройках встреч отмечено "Сессия с НВ"
        if ($arResult["exhib"]["IS_HB"]) {
            $dayline = "День 1 - 1 марта, 2018";
        } else {
            $dayline = "День 2 - 2 марта, 2018";
        }
    }
    $pdf->multiCell(210, 5, "Расписание встреч на выставке\n" . $arResult["exhib"]["TITLE_RU"] . $dayline, 0, C);
    $pdf->SetFont('freeserif', '', 14);
    $pdf->setXY(30, $pdf->getY() + 2);
    if(in_array($arResult["APP_ID"], [1,6]) && $arResult['is_hb']) {
        $pdf->multiCell(210, 5, $arResult["name"], 0, L);
    } else {
        $pdf->multiCell(210, 5, $arResult["name"] . ", " . $arResult['city'], 0, L);
    }

    $pdf->setXY(30, $pdf->getY() + 1);

    //если есть коллега, выводим его через запятую
    if (!empty($arResult["rep"]) && !empty(trim($arResult["col_rep"]))){
        $pdf->multiCell(300, 5, trim($arResult["rep"]). ", " . trim($arResult["col_rep"]), 0, L);
    }else{
        $pdf->multiCell(300, 5, trim($arResult["rep"]), 0, L);
    }
    $pdf->setXY(30, $pdf->getY() + 1);
    $pdf->multiCell(210, 5, "Мобильный телефон: " . $arResult["mob"], 0, L);
    $pdf->setXY(30, $pdf->getY() + 1);
    $pdf->multiCell(210, 5, "Телефон: " . $arResult["phone"], 0, L);
    $pdf->setXY(30, $pdf->getY() + 4);

    if ($arResult["exhib"]["IS_HB"] && $arResult["hall"] != "None") {
        $pdf->multiCell(210, 5, "Hall, Table: " . $arResult["hall"] . ", " . $arResult["table"], 0, L);
        $pdf->setXY(0, 90);
        $pdf->SetFont('freeserif', '', 15);
        $pdf->multiCell(210, 5, "Ваше расписание", 0, C);
        $pdf->setXY(20, 100);
    } elseif ($arResult["exhib"]["IS_HB"]) {
        $pdf->multiCell(210, 5, "Hall, Table: ", 0, L);

        $pdf->setXY(0, 90);
        $pdf->SetFont('freeserif', '', 15);
        $pdf->multiCell(210, 5, "Ваше расписание", 0, C);
        $pdf->setXY(20, 100);
    } else {
        $pdf->setX(0);
        $pdf->SetFont('freeserif', '', 15);
        $pdf->multiCell(210, 5, "Ваше расписание", 0, C);
        $pdf->setXY(20, 90);
    }

    $pdf->SetFont('freeserif', '', 10);
    $pdf->SetY($pdf->getY() + 2);
    $pdf->SetX(10);

    /* Формируем таблицу */
    if ($arResult["exhib"]["IS_HB"]) {
        $tbl = '<table cellspacing="0" cellpadding="5" border="1">
                <thead>
                    <tr nobr="true">
                        <td align="center" width="85">Время</td>
                        <td align="center" width="340">Участники</td>
                        <td align="center" width="110">Статус</td>
                    </tr>
                </thead>
                <tbody>
                ';
    } else {
        $tbl = '<table cellspacing="0" cellpadding="5" border="1">
                    <thead>
                        <tr nobr="true">
                            <td align="center" width="85">Время</td>
                            <td align="center" width="240">Участники</td>
                            <td align="center" width="100">Статус</td>
                            <td align="center" width="110">Зал, Стол</td>
                        </tr>
                    </thead>
			        <tbody>';
    }

    $count = 0;
    foreach ($arResult['schedule'] as $freeseriflot) {
        $count++;
        if ($freeseriflot['status'] == 'free') {
            $tbl .= '<tr nobr="true">
                        <td width="85" align="center">' . $freeseriflot['timeslot_name'] . '</td>
                        <td colspan="3" width="450" align="center">Свободно</td>
                    </tr>';
        } else if ($freeseriflot['status'] == 'coffee') {
            $tbl .= '<tr nobr="true">
						<td width="85" align="center">' . $freeseriflot['timeslot_name'] . '</td>
						<td colspan="3"  width="450" align="center">Перерыв на кофе</td>
					  </tr>';
        } else if ($freeseriflot['status'] == 'lunch') {
            $lunchText = ($arResult['APP_ID'] == 1) ? 'Легкий обед' : 'Обед';
            $tbl .= '<tr>
                        <td width="85" align="center">' . $freeseriflot['timeslot_name'] . '</td>
                        <td colspan="3" width="450" align="center">' . $lunchText . '</td>
                    </tr>';
        } else {
            if ($arResult["exhib"]["IS_HB"]) {
                $tbl .= '<tr nobr="true">
					  <td width="85" align="center">' . $freeseriflot['timeslot_name'] . '</td>
					  <td width="340">Company: ' . $freeseriflot['company_name'] . '<br />Representative: ' . $freeseriflot['company_rep'] . '</td>
					  <td width="110" align="center" >' . $freeseriflot['notes'] . '</td>
				  </tr>';
            } else {
                $tbl .= '<tr nobr="true">
					  <td width="85" align="center">' . $freeseriflot['timeslot_name'] . '</td>
					  <td width="240">Company: ' . $freeseriflot['company_name'] . '<br />Representative: ' . $freeseriflot['company_rep'] . '</td>
					  <td width="100" align="center">' . $freeseriflot['notes'] . '</td>';
                if ($freeseriflot['hall']) {
                    $tbl .= '<td width="110">' . $freeseriflot['hall'] . ', ' . $freeseriflot['table'] . '</td>
							</tr>';
                } else {
                    $tbl .= '<td width="110"> </td>
                                </tr>';
                }
            }
        }
    }
    $tbl .= '</tbody></table>';
    $pdf->writeHTML($tbl, true, false, false, false, '');

    $pdf->setXY(20, $pdf->getY() + 10);
    $y = $pdf->getY();
    $html = '<p><b>Регистрация гостей и выдача бейджей</b> будет проходить в день мероприятия на стойке регистрации Luxury Travel Mart <b>с 09:30 до 11:30.</b></p>
	<p>Пожалуйста, имейте при себе <b>достаточное количество визитных карточек на английском языке.</b></p>';
    $pdf->writeHTMLCell('', '', 20, $y, $html, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = true);

//    $pdf->setY($pdf->getY() + 15);
//    $y = $pdf->getY();
//    $html = '<p>Пожалуйста, имейте при себе <b>достаточное количество визитных карточек на английском языке.</b><p>';
//    $pdf->writeHTMLCell('', '', 20, $y, $html, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = true);

    //транслитерация наименования pdf, чтобы убрать лишние символы расширенной латиницы
    $arResult['name'] = str_replace(array(' ', '/'), array('_', ''), $arResult['name']);
    $Params = array("replace_space" => "_", "replace_other" => "_");
    $newName = Cutil::translit($arResult['name'], "en", $Params);
    $arResult['path'] = str_replace($arResult['name'], $newName, $arResult['path']);

    $pdf->Output($arResult['path'], F);
    unset($pdf);
}

?>