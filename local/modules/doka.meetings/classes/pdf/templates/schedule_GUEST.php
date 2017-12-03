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
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->AddFont('freeserif', 'I', 'freeserifi.php');
    $pdf->AddPage();
    $pdf->ImageSVG($file = DOKA_MEETINGS_MODULE_DIR . '/images/logo.svg', $x = 30, $y = 5, $w = '150', $h = '', $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);

    $pdf->setXY(0, 25);
    $pdf->SetFont('freeserif', 'B', 17);
    // Если в свойствах выставки отмечено "Есть сессия НВ"
    if($arResult["PARAM_EXHIBITION"]["PROPERTIES"]["HB_EXIST"]['VALUE']){    
        // Если в настройках встреч отмечено "Сессия с НВ"
        if ($arResult["EXHIBITION"]["IS_HB"]) {
            $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_RU"]['VALUE'] .= " Hosted Buyers сессия\n";
            $dayline = "День 1, 1 марта 2018";
        } else {
            $dayline = "День 2, 2 марта 2018";
            $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_RU"]['VALUE'] .= "\n";
        }
    }
    $pdf->multiCell(210, 5, "Расписание встреч на утренней сессии\n" . $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_RU"]['VALUE'] . $dayline, 0, C);
    /*$pdf->multiCell(210, 5, "Список неподтвержденных запросов на\nLuxury Travel Mart Баку", 0, C);*/
    $pdf->SetFont('freeserif', '', 14);
    $pdf->setXY(30, $pdf->getY() + 2);
    $pdf->multiCell(210, 5, $arResult["USER"]['COMPANY'] . ", " . $arResult["USER"]['CITY'], 0, L);
    $pdf->setXY(30, $pdf->getY() + 1);
    if ($arResult["USER"]['COL_REP'] == "") {
        $pdf->multiCell(210, 5, $arResult["USER"]['REP'], 0, L);
    } else {
        $pdf->multiCell(210, 5, $arResult["USER"]['REP'] . ", " . $arResult["USER"]['COL_REP'], 0, L);
    }
    $pdf->setXY(30, $pdf->getY() + 1);
    $pdf->multiCell(210, 5, "Мобильный телефон: " . $arResult["USER"]['MOB'], 0, L);
    $pdf->setXY(30, $pdf->getY() + 1);
    $pdf->multiCell(210, 5, "Телефон: " . $arResult["USER"]['PHONE'], 0, L);
    $pdf->setXY(30, $pdf->getY() + 4);

    if ($arResult["EXHIBITION"]["IS_HB"] && $arResult["HALL"] != "None") {
        $pdf->multiCell(210, 5, "Hall, Table: " . $arResult["HALL"] . ", " . $arResult["TABLE"], 0, L);
        $pdf->setXY(0, 90);
        $pdf->SetFont('freeserif', '', 15);
        $pdf->multiCell(210, 5, "Ваше расписание", 0, C);
        $pdf->setXY(20, 100);
    } elseif ($arResult["EXHIBITION"]["IS_HB"]) {
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
    if ($arResult["EXHIBITION"]["IS_HB"]) {
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
    foreach ($arResult['SCHEDULE'] as $freeseriflot) {
        $count++;
        if ($freeseriflot['status'] == 'free') {
            $tbl .= '<tr nobr="true">
                        <td width="85" align="center">' . $freeseriflot['name'] . '</td>
                        <td colspan="3" width="450" align="center">Свободно</td>
                    </tr>';
        } else if ($freeseriflot['status'] == 'coffee') {
            $tbl .= '<tr nobr="true">
                        <td  width="85" align="center">' . $freeseriflot['name'] . '</td>
                        <td colspan="3" width="450" align="center">Перерыв на кофе</td>
                    </tr>';
        } else if ($freeseriflot['status'] == 'lunch') {
            $lunchText = ($arResult['APP_ID'] == 1) ? 'Легкий обед' : 'Перерыв на обед';
            $tbl .= '<tr nobr="true">
                        <td width="85" align="center">' . $freeseriflot['name'] . '</td>
                        <td colspan="3" width="450" align="center">' . $lunchText . '</td>
                </tr>';
        } else {
            $tbl .= '<tr nobr="true">
                        <td width="85" align="center">' . $freeseriflot['name'] . '</td>';
                        if ($arResult["EXHIBITION"]["IS_HB"]) {
                            $tbl .= '<td width="340">Компания: ' . $freeseriflot['company_name'] . '<br />Представитель: ' . $freeseriflot['company_rep'] . '</td>
                            <td width="110" align="center">' . $freeseriflot['notes'] . '</td>';
                        } else {
                            $tbl .= '<td width="240">Компания: ' . $freeseriflot['company_name'] . '<br />Представитель: ' . $freeseriflot['company_rep'] . '</td>
                                    <td width="100" align="center">' . $freeseriflot['notes'] . '</td>';
                            if ($freeseriflot['hall']) {
                                $tbl .= '<td width="110">' . $freeseriflot['hall'] . ', ' . $freeseriflot['table'] . '</td>';
                            } else {
                                $tbl .= '<td width="110"></td>';
                            }
                        }               
            if (!$arResult["EXHIBITION"]["IS_HB"]) {

            }
            $tbl .= '</tr>';
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
//    $html = '';
//    $pdf->writeHTMLCell('', '', 20, $y, $html, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = true);

    $pdf->Output("print.pdf", I);
    die();

}
