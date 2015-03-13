<?
function DokaGeneratePdf($arResult) {
	$pdf = new TCPDF('P', 'mm', 'A4', false, 'UTF-8', false);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->AddFont('times','I','timesi.php');
	$pdf->AddPage();
	$pdf->ImageSVG($file=DOKA_MEETINGS_MODULE_DIR . '/images/logo.svg', $x=30, $y=5, $w='150', $h='', $link='', $align='', $palign='', $border=0, $fitonpage=false);

	$pdf->setXY(0,25);
	$pdf->SetFont('times', 'B',17);
	if($arResult["EXHIBITION"]["IS_HB"]){
		$arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_RU"]['VALUE'] .= " Hosted Buyers ������";
	}
	$pdf->multiCell(210, 5, "���������� ������ �� �������� ������\n" . $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_RU"]['VALUE'], 0, C);
/*$pdf->multiCell(210, 5, "������ ���������������� �������� ��\nLuxury Travel Mart ����", 0, C);*/
	$pdf->SetFont('times','',15);
	$pdf->setXY(30,44);
	$pdf->multiCell(210, 5, iconv('utf-8', 'windows-1251', $arResult["USER"]['COMPANY']).", ". iconv('utf-8', 'windows-1251', $arResult["USER"]['CITY']), 0, L);
	$pdf->setXY(30,52);
	$pdf->multiCell(210, 5, iconv('utf-8', 'windows-1251', $arResult["USER"]['REP']), 0, L);	
	$pdf->setXY(30,60);
	if($arResult["EXHIBITION"]["IS_HB"] && $arResult["HALL"] != "None"){
		$pdf->multiCell(210, 5, "Hall, Table: ".$arResult["HALL"].", ".$arResult["TABLE"], 0, L);

		$pdf->setXY(0,70);
		$pdf->SetFont('times','',13);
		$pdf->multiCell(210, 5, "���� ����������", 0, C);
		$pdf->setXY(20,80);
	}
	elseif($arResult["EXHIBITION"]["IS_HB"]){
		$pdf->multiCell(210, 5, "Hall, Table: ", 0, L);
		
		$pdf->setXY(0,70);
		$pdf->SetFont('times','',13);
		$pdf->multiCell(210, 5, "���� ����������", 0, C);
		$pdf->setXY(20,80);
	}
	else{
		$pdf->setX(0);
		$pdf->SetFont('times','',13);
		$pdf->multiCell(210, 5, "���� ����������", 0, C);
		$pdf->setXY(20,70);
	}

	$pdf->SetFont('times','',10);

	/* ��������� ������� */
	if($arResult["EXHIBITION"]["IS_HB"]){
        $header = '<table cellspacing="0" cellpadding="5" border="1">
    <tr>
        <td align="center" width="70">�����</td>
        <td align="center" width="240">���������</td>
        <td align="center" width="90">������</td>
    </tr>';
	}
	else{
        $header = '<table cellspacing="0" cellpadding="5" border="1">
    <tr>
        <td align="center" width="70">�����</td>
        <td align="center" width="240">���������</td>
        <td align="center" width="90">������</td>
        <td align="center" width="95">���, ����</td>
    </tr>';		
	}

	$tbl = $header;
	$count = 0;
	$countBreaks = 0;
	foreach ($arResult['SCHEDULE'] as $timeslot) {
		$count++;
		if ($timeslot['status'] == 'free') {
			$tbl .= '<tr>
                        <td>'.iconv('utf-8', 'windows-1251', $timeslot['name']).'</td>
                        <td colspan="3" align="center">��������</td>
                    </tr>';
		}
		else if($timeslot['status'] == 'coffe' && !$countBreaks){
			$tbl .= '<tr>
                        <td>'.iconv('utf-8', 'windows-1251', $timeslot['name']).'</td>
                        <td colspan="3" align="center">������� �� ����</td>
                    </tr>';
            $countBreaks++;
			}
		else if($timeslot['status'] == 'coffe' && $countBreaks){
			$tbl .= '<tr>
                        <td>'.iconv('utf-8', 'windows-1251', $timeslot['name']).'</td>
                        <td colspan="3" align="center">������� �� ����</td>
                    </tr>';
			}
		else {
			$tbl .= '<tr>
							<td>'.iconv('utf-8', 'windows-1251', $timeslot['name']).'</td>
							<td>��������: '.$timeslot['company_name'].'<br />�������������: '.iconv('utf-8', 'windows-1251', $timeslot['company_rep']).'</td>
							<td align="center">' . $timeslot['notes']. '</td>';
			if(!$arResult["EXHIBITION"]["IS_HB"]){
				if($timeslot['hall']){
					 $tbl .= '<td>'.iconv('utf-8', 'windows-1251', $timeslot['hall']).', '.iconv('utf-8', 'windows-1251', $timeslot['table']).'</td>';
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
			$pdf->multiCell(210, 5, "����������� �� ��������� ��������", 0, C);
			$pdf->AddPage();
			$pdf->setX(20);
			$tbl = $header;
		}
	}
	$tbl .= '</table>';
	$pdf->writeHTML($tbl, true, false, false, false, '');

	$pdf->setXY(20,$pdf->getY() + 10);
	$y = $pdf->getY();
	$html = '<b>����������� ������ � ������ �������</b> ����� ��������� � ���� ����������� �� ������ ����������� Luxury Travel Mart <b>� 09:30 �� 11:30.</b>';
	$pdf->writeHTMLCell('', '', 20, $y, $html, $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true);

	$pdf->setY($pdf->getY() + 10);
	$y = $pdf->getY();
	$html = '����������, ������ ��� ���� <b>����������� ���������� �������� �������� �� ���������� �����.</b>';
	$pdf->writeHTMLCell('', '', 20, $y, $html, $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true);

	$pdf->Output("print.pdf", I);
	die();
	// var_dump($test);die();
	
}


?>