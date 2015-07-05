<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$arParams["FORM_ID"] = "1";
		require("pdf/tcpdf.php");
		$rsUser = CUser::GetByID($_GET[["id"]);
		$thisUser = $rsUser->Fetch();
		CForm::GetResultAnswerArray($arParams["FORM_ID"], $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $thisUser["UF_ANKETA"]));
		$realUser = array();
		$realUser["ID"] = $arParams["USER"];
		$realUser["NAME"] = $arrAnswers[$thisUser["UF_ANKETA"]][1][1]["USER_TEXT"];
		$realUser["SURNAME"] = $arrAnswers[$thisUser["UF_ANKETA"]][2][2]["USER_TEXT"];
		$realUser["COMPANY"] = $arrAnswers[$thisUser["UF_ANKETA"]][6][10]["USER_TEXT"];
		$realUser["CITY"] = $arrAnswers[$thisUser["UF_ANKETA"]][8][17]["USER_TEXT"];
		$realUser["COUNTRY"] = $arrAnswers[$thisUser["UF_ANKETA"]][9][18]["USER_TEXT"];
		$realUser["PHONE"] = $arrAnswers[$thisUser["UF_ANKETA"]][12][21]["USER_TEXT"];
		$realUser["ADDRESS"] = $arrAnswers[$thisUser["UF_ANKETA"]][185][393]["USER_TEXT"];
		$realUser["DATE"] = date("d.m.Y");
		$realUser["PAY_COUNT"] = $thisUser["UF_PAY_COUNT"];
		
		$rsTempRekvizit = CUserFieldEnum::GetList(array(), array("USER_FIELD_ID" => "5"));
		$countRekv = 0;
		while($rsRekvizit = $rsTempRekvizit->GetNext()){
			$realUser["REKV"][$countRekv]["ID"] = $rsRekvizit["ID"];
			$realUser["REKV"][$countRekv]["VALUE"] = $rsRekvizit["VALUE"];
			if($rsRekvizit["ID"] == $thisUser["UF_REKVIZIT"]){
				$realUser["REKV"][$countRekv]["ACTIVE"] = 'Y';
			}
			else{
				$realUser["REKV"][$countRekv]["ACTIVE"] = 'N';
			}
			$countRekv++;
		}
		$string= "Beneficiary: Artem Polanskiy\n\nFortis Banque SA,\nMontagne du Parc 3 B - 1000 Bruxelles\nIBAN: BE45 0014 27790789\n\nSWIFT: GEBABEBB\n\nAccount: 001-4277907-89";
		$pdf = new TCPDF('P', 'mm', 'A4', false, 'UTF-8', false);
		$pdf->AddFont('times','I','timesi.php');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false); 
		$pdf->AddPage();
		$pdf->Image('logo_ltm.jpg');
		$pdf->SetFont('times','I',10);
		$pdf->setXY(35,12);
		$pdf->multiCell(150, 5, $string, 0, R);
		$pdf->setXY(0,100);
		$pdf->SetFont('times','I',12);
		$pdf->multiCell(200, 5, "INVOICE N ".$realUser['ID']."\n\n", 0, C);
		$pdf->SetFont('times','I',12);
		$pdf->multiCell(180, 5, "Participation fee for at the Luxury Travel Mart 2010 - ".$realUser['UF_PAY_AMOUNT']." Euro", 0, C); 
		$pdf->multiCell(180, 5, "\nVAT 18%: ".$realUser['UF_VAT']." Euro\n\n", 0, C); 
		$pdf->SetFont('times','I',12);
		$pdf->multiCell(180, 5, "Amount to pay:  ".$realUser['UF_PAY']." Euro\n\n", 0, C); 
		$pdf->SetFont('times','I',12);
		$pdf->setXY(0,145);
		$pdf->multiCell(200, 5, "\nBANK DETAILS:", 0, C);
		$pdf->setXY(20,125);
		$pdf->SetFont('times','I',12);
		  $pdf->setXY(20,155);
		  $pdf->multiCell(160, 5, "Beneficiary's Bank:\n\nVTB Bank (open joint-stock company)\n29, Bolshaya Morskaya, Saint-Petersburg,\nRussia, 190000\nSWIFT: VTBRRUMM \n\nBeneficiary: TRAVEL MEDIA\nAccount: 40702-978-9-0014-0010240\n ", 0, C);
		$pdf->SetFont('times','I',10);
		$pdf->multiCell(0, 10, "This invoice expires on January 15, 2010. Bank charges at payer's expense\n\n", 0, C);
		$pdf->SetFont('times','I',12);
		$pdf->multiCell(300, 5, "Artem V. Polanskiy\n", 0, L);
		$pdf->SetFont('times','I',8);
		$pdf->multiCell(0, 5, "(Electronic copy, without signature and company stamp)", 0, C);
		$pdf->Output("count.pdf", I);
?>