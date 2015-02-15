<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
  CModule::IncludeModule('iblock');

  $times = array(
	  '10:00 � 10:10', '10:15 � 10:25',
	  '10:30 � 10:40', '10:45 � 10:55',
	  '11:00 � 11:10', '11:15 � 11:25',
	  '11:30 � 11:40', '11:45 � 11:55',
	  '12:10 � 12:20', '12:25 � 12:35',
	  '12:40 � 12:50', '12:55 � 13:05',
	  '13:10 � 13:20', '13:25 � 13:35',
	  '13:40 � 13:50', '13:55 � 14:05',
	  '14:10 � 14:20'
  );

  /*---------------------------------------------------*/
  //           ��������� ����� ��� �������             //
  /*---------------------------------------------------*/
	  $rsUser = CUser::GetByID($USER->GetID());
	  $thisUser = $rsUser->Fetch();
	  $arResult["USER"]["NAME"] = $thisUser["NAME"]." ".$thisUser["LAST_NAME"];
	  $arResult["USER"]["COMPANY"] = $thisUser["WORK_COMPANY"];
	  $arParams["APP_COUNT"] = 17;
	  $arParams["GROUP_RECIVER_ID"] = 6;

	  //������ �������������
	  $thisUser["UF_WISH_OUT"] = trim(str_replace("  "," ",str_replace(",", "|", substr($thisUser["UF_WISH_OUT"],2))));
	  $thisUser["UF_WISH_IN"] = trim(str_replace("  "," ",str_replace(",", "|", substr($thisUser["UF_WISH_IN"],2))));

	  $myWishIn = array();
	  $countIn = 0;
	  $myWishOut = array();
	  $countOut = 0;
	  if($thisUser["UF_WISH_OUT"] != ''){
		  $filter = Array(
			  "ID"  => $thisUser["UF_WISH_OUT"]
		  );
		  $rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // �������� �������������
		  while($arUsersTemp=$rsUsers->Fetch()){
			  $myWishOut[$countOut]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
			  $myWishOut[$countOut]["ID"] = $arUsersTemp["ID"];
			  $myWishOut[$countOut]["REP"] = $arUsersTemp["NAME"]." ".$arUsersTemp["LAST_NAME"];
			  $countOut++;
		  }
	  }
	  if($thisUser["UF_WISH_IN"] != ''){
		  $filter = Array(
			  "ID"  => $thisUser["UF_WISH_IN"]
		  );
		  $rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // �������� �������������
		  while($arUsersTemp=$rsUsers->Fetch()){
			  $myWishIn[$countIn]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
			  $myWishIn[$countIn]["ID"] = $arUsersTemp["ID"];
			  $myWishIn[$countIn]["REP"] = $arUsersTemp["NAME"]." ".$arUsersTemp["LAST_NAME"];
			  $countIn++;
		  }
	  }
	  $arResult["WISH_IN"] = $myWishIn;
	  $arResult["WISH_OUT"] = $myWishOut;

	require('pdf/tcpdf.php');
	$pdf = new TCPDF('P', 'mm', 'A4', false, 'UTF-8', false);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->AddFont('times','I','timesi.php');
	$pdf->AddPage();
	$pdf->ImageSVG($file='images/logo.svg', $x=30, $y=5, $w='150', $h='', $link='', $align='', $palign='', $border=0, $fitonpage=false);
	$pdf->setXY(0,30);
	$pdf->SetFont('times','B',17);
	$pdf->multiCell(210, 5, "������ ���������������� �������� �� LTM Moscow 2013", 0, C);
	$pdf->SetFont('times','',15);
	$pdf->setXY(30,40);
	$pdf->multiCell(210, 5, $arResult["USER"]["COMPANY"].", ". $arResult["USER"]["CITY"], 0, L);
	$pdf->setXY(30,50);
	$pdf->multiCell(210, 5,$arResult["USER"]['NAME']." ".$arResult["USER"]["LAST_NAME"], 0, L);
	$pdf->SetFont('times','',13);
	$pdf->SetX(50);

	$pdf->SetFont('times','B',13);
	$pdf->setXY(0,60);
	$pdf->multiCell(210, 5, "�� ����� ������ �� ����������� �� ���������� ����������", 0, C);

	$pdf->SetFont('times','',10);
	$pdf->setXY(0,65);
	$pdf->multiCell(210, 5, "(��������, ������ ��������� ��������� ���� ������� ��� �� ���������� ��� ������):", 0, C);



	/* ��������� ������� */
	if($countOut){
		$pdf->setXY(20,$pdf->getY() + 5);
		$pdf->SetFont('times','',13);

		$tbl = '<table cellspacing="0" cellpadding="5" border="1">
			<tr>
				<td align="center" width="60">�</td>
				<td align="center" width="250">��������</td>
				<td align="center" width="200">�������������</td>
			</tr>';
		$counter = 1;
		for($i = 0; $i < $countOut; $i++){
                    $tbl .= '<tr>
				<td align="center">'.$counter.'</td>
				<td>'.$arResult["WISH_OUT"][$i]["COMPANY"].'</td>
				<td>'.$arResult["WISH_OUT"][$i]["REP"].'</td>
			</tr>';
			$counter++;
		}
          $tbl .= '</table>';
	  $pdf->writeHTML($tbl, true, false, false, false, '');
	}
	else{
		$pdf->SetFont('times','',13);
		$pdf->setXY(0,$pdf->getY() + 5);
		$pdf->multiCell(210, 5, "��� �������� � ������ ������.", 0, C);
	}

	$pdf->SetFont('times','B',13);
	$pdf->setXY(0,$pdf->getY() + 10);
	$pdf->multiCell(210, 5, "� ���� ����� ������ �� ����������� ��������� ��������", 0, C);

	$pdf->SetFont('times','',10);
	$pdf->setX(0);
	$pdf->multiCell(210, 5, "(��������, �� ��������� ������� �� ���� ���������� ��� ���� ���������� ��� ������):", 0, C);


	if($countIn){

		$pdf->setXY(20,$pdf->getY() + 5);
		$pdf->SetFont('Times','',13);

		$tbl = '<table cellspacing="0" cellpadding="5" border="1">
			<tr>
				<td align="center" width="60">�</td>
				<td align="center" width="250">��������</td>
				<td align="center" width="200">�������������</td>
			</tr>';
		$counter = 1;
		for($i = 0; $i < $countIn; $i++){
			$tbl .= '<tr>
				<td align="center">'.$counter.'</td>
				<td>'.$arResult["WISH_IN"][$i]["COMPANY"].'</td>
				<td>'.$arResult["WISH_IN"][$i]["REP"].'</td>
			</tr>';
			  $counter++;
		}
          $tbl .= '</table>';
	  $pdf->writeHTML($tbl, true, false, false, false, '');
	}
	else{
		$pdf->SetFont('times','',13);
		$pdf->setXY(0,$pdf->getY() + 5);
		$pdf->multiCell(210, 5, "��� �������� � ������ ������.", 0, C);
	}
$pdf->setXY(20,$pdf->getY() + 10);
	$y = $pdf->getY();
	$html = '�� ������ ����������� �� ����� ����������, ���������� ����, � ����� ������ ����� Luxury Travel Mart, ��������, �� ����� ����� ��� �� �������� ������ � 18:30 �� 21:00.';
	$pdf->writeHTMLCell('', '', 20, $y, $html, $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true);
	


	$pdf->Output("print_wish.pdf", I);
?>