<?
/** Extend the TCPDF class to create custom Footer*/
class MYPDF extends TCPDF
{
    /** Custom page header and footer are defined by extending the TCPDF class
    and overriding the Header() and Footer() methods*/
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
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);// set auto page breaks

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // $pdf->AddFont('freeserif','I','freeserifi.php');
    $pdf->AddPage();
    $pdf->ImageSVG($file = DOKA_MEETINGS_MODULE_DIR . '/images/logo.svg', $x = 30, $y = 5, $w = '150', $h = '', $link = '', $align = '', $palign = '', $border = 0, $fitonpage = false);
    $arResult["exhib"]["TITLE"] .= "\n";
    if($arResult["exhib"]["HB_EXIST"]){
        if ($arResult["exhib"]["IS_HB"]) {
            $dayline = "Day 1 - March 1, 2018";
        } else {
            $dayline = "Day 2 - March 2, 2018";
        }
    }
    $pdf->setXY(0, 25);
    $pdf->SetFont('freeserif', 'B', 18);
    $pdf->multiCell(220, 6, 'Personal diary during the morning session', 0, C);
    $pdf->SetFont('freeserif', 'B', 18);
    $pdf->multiCell(200, 6, "at " . $arResult["exhib"]["TITLE"] . $dayline, 0, C);
    $pdf->SetFont('freeserif', '', 15);
    $pdf->setXY(30, $pdf->getY() + 2);
    $pdf->multiCell(210, 5, $arResult['name'], 0, L);
    $pdf->setXY(30, $pdf->getY() + 1);
    $pdf->multiCell(210, 5, $arResult['rep'], 0, L);
    $pdf->SetFont('freeserif', '', 13);
    $pdf->setXY(30, $pdf->getY() + 1);
    if ($arResult["exhib"]["IS_HB"] != 'Y') {
        if ($arResult["hall"] != "None") {
            $pdf->multiCell(210, 5, "Hall, Table: " . $arResult["hall"] . ", " . $arResult["table"], 0, L);
        } else {
            $pdf->multiCell(210, 5, "Hall, Table: ", 0, L);
        }
        $pdf->setXY(30, $pdf->getY() + 2);
    }
    $pdf->SetFont('freeserif', '', 11);
    $pdf->SetY($pdf->getY() + 10);
    $pdf->SetX(10);

    /* Формируем таблицу */
    if ($arResult["exhib"]["IS_HB"] == 'Y') {
        $tbl = '
		<table cellspacing="0" cellpadding="5" border="1">
				<thead>
				<tr nobr="true">
					<th align="center" width="85">Time</th>
					<th align="center" width="240">Companies</th>
					<th align="center" width="90"> </th>
					<td align="center" width="110">Hall, Table</td>
				</tr>
			    </thead>
			<tbody>
		';
        $colspanGuest = 3;
    } else {
        $tbl = '
		<table cellspacing="0" cellpadding="5" border="1">
				<thead>
				<tr nobr="true">
					<th align="center" width="85">Time</th>
					<th align="center" width="340">Companies</th>
					<th align="center" width="110"> </th>
				</tr>
			</thead>
			<tbody>
		';
        $colspanGuest = 2;
    }

    $count = 0;
    foreach ($arResult['schedule'] as $freeseriflot) {
        $count++;
        if ($freeseriflot['status'] == 'free') {
            $tbl .= '<tr nobr="true">
				  <td width="85" align="center">' . $freeseriflot['timeslot_name'] . '</td>
				  <td colspan="' . $colspanGuest . '" width="450" align="center">Free time</td>
			  </tr>';
        } elseif ($freeseriflot['status'] == 'reserve') {
            $tbl .= '<tr nobr="true">
				  <td width="85" align="center">' . $freeseriflot['timeslot_name'] . '</td>
				  <td colspan="' . $colspanGuest . '" width="450" align="center">Reserved by you</td>
			  </tr>';
        } elseif ($freeseriflot['status'] == 'coffee') {
            $tbl .= '<tr nobr="true">
				  <td width="85" align="center">' . $freeseriflot['timeslot_name'] . '</td>
				  <td colspan="' . $colspanGuest . '" width="450" align="center">Coffee-break</td>
			  </tr>';
        } else if ($freeseriflot['status'] == 'lunch') {
            $lunchText = ($arResult['APP_ID'] == 1) ? 'Light lunch' : 'Lunch';
            $tbl .= '<tr nobr="true">
				  <td width="85" align="center">' . $freeseriflot['timeslot_name'] . '</td>
				  <td colspan="' . $colspanGuest . '" width="450" align="center">' . $lunchText . '</td>
			  </tr>';
        } else {
            if (!$arResult["exhib"]["IS_HB"]) {
                $tbl .= '<tr nobr="true">
					  <td width="85" align="center">' . $freeseriflot['timeslot_name'] . '</td>
					  <td width="340">Company: ' . $freeseriflot['company_name'] . '<br />Representative: ' . $freeseriflot['company_rep'] . '</td>
					  <td width="110" align="center" >' . $freeseriflot['notes'] . '</td>
				  </tr>';
            } else {
                $tbl .= '<tr nobr="true">
					  <td width="85" align="center">' . $freeseriflot['timeslot_name'] . '</td>
					  <td width="340">Company: ' . $freeseriflot['company_name'] . '<br />Representative: ' . $freeseriflot['company_rep'] . '</td>
					  <td width="110" align="center">' . $freeseriflot['notes'] . '</td>';
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

    $pdf->setXY(0, $pdf->getY() + 10);
    $pdf->multiCell(210, 5, "Please make your appointments in time; any delay in timing will effect the next exhibitor after you.", 0, C);
    $pdf->setXY(0, $pdf->getY() + 5);
    $pdf->multiCell(210, 5, "Please report all no-shows to your Hall Manager or to the registration desk of the Luxury Travel Mart.", 0, C);

    //транслитерация наименования pdf, чтобы убрать лишние символы расширенной латиницы
    $arResult['name'] = str_replace(array(' ', '/'), array('_', ''), $arResult['name']);
    $Params = array("replace_space" => "_", "replace_other" => "_");
    $newName = Cutil::translit($arResult['name'], "en", $Params);
    $arResult['path'] = str_replace($arResult['name'], $newName, $arResult['path']);

    $pdf->Output($arResult['path'], F);
    unset($pdf);
}
