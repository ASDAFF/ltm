<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/
//Добавить в параметры FORM_ID




$arResult["ERROR_MESSAGE"] = "";
$arResult["MESSAGE"] = "";

if(strLen($arParams["PATH_TO_KAB"])<=0){
	$arParams["PATH_TO_KAB"] = "/admin/";
}

if(strLen($arParams["GROUP_ID"])<=0){
	$arParams["GROUP_ID"] = "1";
}

if(strLen($arParams["AUTH_PAGE"])<=0){
	$arParams["AUTH_PAGE"] = "/admin/login.php";
}

if(strLen($arParams["USER_TYPE"])<=0){
	$arParams["USER_TYPE"] = "PARTICIP";
}

if(strLen($arParams["USER"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Пользователю!<br />";
}

if($arParams["USER_TYPE"] == "PARTICIP"){
	$arParams["FORM_ID"] = '1';
}

if(!($USER->IsAuthorized()))
{
	$arResult["ERROR_MESSAGE"] = "Вы не авторизованы!<br />";
}

/*---------------------------------------------------*/
//           ФОРМИРУЕМ ВЫВОД ДЛЯ ШАБЛОНА             //
/*---------------------------------------------------*/
if($arResult["ERROR_MESSAGE"] == '')
{
	$userId= $USER->GetID();
	$userGroups = CUser::GetUserGroup($userId);
	if($USER->IsAdmin() || in_array($arParams["GROUP_ID"], $userGroups)){
		$rsUser = CUser::GetByID($arParams["USER"]);
		$thisUser = $rsUser->Fetch();
		CForm::GetResultAnswerArray($arParams["FORM_ID"], $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $thisUser["UF_ANKETA"]));
		$realUser = array();
		$realUser["ID"] = $arParams["USER"];
		$realUser["EMAIL"] = $arrAnswers[$thisUser["UF_ANKETA"]][10][19]["USER_TEXT"];
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
	
		if(isset($_POST["type"]) == "form"){
			if(isset($_REQUEST["count_look"])){
				$user = new CUser;
				$fields = Array(
				  "NAME"              => $realUser["NAME"],
				  "LAST_NAME"         => $realUser["SURNAME"],
				  "UF_PAY_COUNT"      => $_REQUEST["pay"],
				  "UF_REKVIZIT"       => $_REQUEST["rekv"],
				  );
				$user->Update($arParams["USER"], $fields);
				$strError .= $user->LAST_ERROR;

				$realUser["PAY_COUNT"] = $_REQUEST["pay"];
				$countRekv = 0;
				foreach($realUser["REKV"] as $tempRek){
					if($tempRek["ID"] == $_REQUEST["rekv"]){
						$realUser["REKV"][$countRekv]["ACTIVE"] = 'Y';
					}
					else{
						$realUser["REKV"][$countRekv]["ACTIVE"] = 'N';
					}
					$countRekv++;
				}
				$arResult["TYPE"] = "FORM";
				$arResult["USER"] = $realUser;
				?>
				<script type="text/javascript">
					var recHref = "/admin/service/count_pdf.php?id=<?=$arResult["USER"]["ID"]?>&type=look";
                	window.open(recHref,'count_look', 'scrollbars=yes,resizable=yes,width=800, height=600, left='+(screen.availWidth/2-400)+', top='+(screen.availHeight/2-300)+'');
                </script>
				<?
			}
			elseif(isset($_REQUEST["count_make"])){
				$user = new CUser;
				$fields = Array(
				  "NAME"              => $realUser["NAME"],
				  "LAST_NAME"         => $realUser["SURNAME"],
				  "UF_PAY_COUNT"      => $_REQUEST["pay"],
				  "UF_REKVIZIT"       => $_REQUEST["rekv"],
				  );
				$user->Update($arParams["USER"], $fields);
				$strError .= $user->LAST_ERROR;

				$realUser["PAY_COUNT"] = $_REQUEST["pay"];
				$countRekv = 0;
				foreach($realUser["REKV"] as $tempRek){
					if($tempRek["ID"] == $_REQUEST["rekv"]){
						$realUser["REKV"][$countRekv]["ACTIVE"] = 'Y';
					}
					else{
						$realUser["REKV"][$countRekv]["ACTIVE"] = 'N';
					}
					$countRekv++;
				}
				$arResult["TYPE"] = "FORM";
				$arResult["USER"] = $realUser;
				
/*---------------------------------------------------*/
//           ФОРМИРУЕМ СЧЕТА НА ОТПРАВКУ             //
/*---------------------------------------------------*/
				require('pdf/tcpdf.php');
				if($_REQUEST["rekv"] == 1){
					$string= "Ordering customer / Плательщик: \n".$realUser['COMPANY']."\n ".$realUser['ADDRESS']."\n".$realUser['CITY']."\n".$realUser['COUNTRY']."\n".$realUser["PHONE"]."\n\n  Beneficiary / Получатель: Polanskiy Artem Valentinovich,\nregistered as independent entrepreneur\nwith State Registration Number 309503525800010\nat Federal Tax Service Inspectorate in \nPavlosvkiy Posad, Moscow Region\n\nИндивидуальный предприниматель \nПоланский Артём Валентинович,\nзарегистрированный Инспекцией Федеральной Налоговой Службы \nпо г. Павловский Посад Московской области, \nгосударственный регистрационный номер 309503525800010\nИНН 503507510512\n\nMoscow, Russia / Москва, Российская Федерация\n" . date("d.m.Y");
					$pdf = new TCPDF('P', 'mm', 'A4', false, 'UTF-8', false);
					$pdf->AddFont('times','I','timesi.php');
					$pdf->setPrintHeader(false);
					$pdf->setPrintFooter(false); 
					$pdf->AddPage();
					$pdf->Image('images/logo_ltm.jpg');
					$pdf->SetFont('times','',9);
					$pdf->setXY(35,12);
					$pdf->multiCell(150, 5, $string, 0, R);

$pdf->setXY(0,$pdf->getY() + 5);

					$pdf->SetFont('times','B',12);
					$pdf->multiCell(200, 5, "INVOICE N/ Счёт № ".$realUser["ID"]."-Kiev\n\n", 0, C);
					$pdf->SetFont('times','B',12);
					$pdf->multiCell(190, 5, "Details of payment / Предмет счёта:", 0, C);
					$pdf->SetFont('times','',10);
					$pdf->multiCell(185, 0, "Participation in the Luxury Travel Mart exhibition on September 23, 2014 at the\nIntercontinental Hotel Kiev, Ukraine, organized by Artem Polanskiy.\nPayment made on non-contractual basis\n\nУчастие в выставке Luxury Travel Mart, организуемой ИП Поланский Артём Валентинович\n23 сентября 2014 года в отеле Интерконтиненталь, Киев, Украина.\nДоговор не заключался и не предусмотрен условиями участия.\n\n", 0, C); 
					$pdf->SetFont('times','B',14);
					$pdf->multiCell(0, 5, "Total amount of payment / Сумма платежа: ".$realUser["PAY_COUNT"]." Euro\n", 0, C); 
					$pdf->SetFont('times','B',12);
					$pdf->multiCell(0, 5, "Payment information / Детали платежа:", 0, C); 
					$pdf->SetFont('times','',10);
					$pdf->multiCell(0, 5, "Please put the invoice number / Укажите номер счёта\n\n", 0, C); 
					$pdf->SetFont('times','B',12);
					$pdf->multiCell(0, 5, "Bank details / Банковские реквизиты:", 0, C);
					$pdf->SetFont('times','',12); 
					$pdf->multiCell(0, 5, "Beneficiary's Bank:\nVTB 24 (JSC), Moscow, Russia\nSWIFT: CBGURUMM\nBeneficiary: Polanskiy Artem Valentinovich\nAccount: 40802978700001002738\n\n", 0, C);
					$pdf->SetFont('times','',10);
					$pdf->multiCell(0, 10, "This invoice is valid for payments until the 20th of March 2014\nBank charges at payer's expense\nИнвойс действителен до 20 марта 2014 года\nБанковские сборы и комиссии за счет плательщика\n\n", 0, C);
					$pdf->SetFont('times','',12);
					$pdf->multiCell(300, 5, "Artem V. Polanskiy /\nПоланский Артём Валентинович\n\n", 0, L);
					$pdf->SetFont('times','',8);
					$pdf->multiCell(0, 5, "(Electronic copy, without signature and company stamp / электронная копия, без подписи и печати)", 0, C);
				}
				elseif($_REQUEST["rekv"] == 2){
					$string= "Ordering customer / Плательщик: \n".$realUser['COMPANY']."\n ".$realUser['ADDRESS']."\n".$realUser['CITY']."\n".$realUser['COUNTRY']."\n".$realUser["PHONE"]."\n\n  Beneficiary / Получатель: Travel Media,\nregistered as Society with limited liability\nwith State Registration Number 1047796617472\nat Federal Tax Service Inspectorate No 46 in\nMoscow\n\nОбщество с ограниченной ответственностью «Трэвэл Медиа»,\nзарегистрированное Инспекцией Федеральной Налоговой Службы № 46 \nпо г. Москве, \nгосударственный регистрационный номер 1047796617472\nИНН 7707525284\n\nMoscow, Russia / Москва, Российская Федерация\n" . date("d.m.Y");
					$pdf = new TCPDF('P', 'mm', 'A4', false, 'UTF-8', false);
					$pdf->AddFont('times','I','timesi.php');
					$pdf->setPrintHeader(false);
					$pdf->setPrintFooter(false); 
					$pdf->AddPage();
					$pdf->Image('images/logo_ltm.jpg');
					$pdf->SetFont('times','',9);
					$pdf->setXY(35,12);
					$pdf->multiCell(150, 5, $string, 0, R);

$pdf->setXY(0,$pdf->getY() + 5);

					$pdf->SetFont('times','B',12);
					$pdf->multiCell(200, 5, "INVOICE N/ Счёт № ".$realUser["ID"]."-Kiev\n\n", 0, C);
					$pdf->SetFont('times','B',12);
					$pdf->multiCell(190, 5, "Details of payment / Предмет счёта:", 0, C);
					$pdf->SetFont('times','',10);
					$pdf->multiCell(185, 0, "Participation in the Luxury Travel Mart exhibition on September 23, 2014 at the\nIntercontinental Hotel Kiev, Ukraine, organized by Travel Media.\nPayment made on non-contractual basis\n\nУчастие в выставке Luxury Travel Mart, организуемой ООО «Трэвэл Медиа»\n23 сентября 2014 года в отеле Интерконтиненталь, Киев, Украина.\nДоговор не заключался и не предусмотрен условиями участия.\n\n", 0, C); 
					$pdf->SetFont('times','B',14);
					$pdf->multiCell(0, 5, "Total amount of payment / Сумма платежа: ".$realUser["PAY_COUNT"]." Euro\n", 0, C); 
					$pdf->SetFont('times','B',12);
					$pdf->multiCell(0, 5, "Payment information / Детали платежа:", 0, C); 
					$pdf->SetFont('times','',10);
					$pdf->multiCell(0, 5, "Please put the invoice number / Укажите номер счёта\n\n", 0, C); 
					$pdf->SetFont('times','B',12);
					$pdf->multiCell(0, 5, "Bank details / Банковские реквизиты:", 0, C);
					$pdf->SetFont('times','',12); 
					$pdf->multiCell(0, 5, "Beneficiary's Bank:\nVTB Bank (open joint-stock company), Moscow, Russia\nSWIFT: VTBRRUMM\nBeneficiary: Travel Media\nAccount: 40702978900140010240\n\n", 0, C);
					$pdf->SetFont('times','',10);
					$pdf->multiCell(0, 10, "This invoice is valid for payments until the 20th of March 2014\nBank charges at payer's expense\nИнвойс действителен до 20 марта 2014 года\nБанковские сборы и комиссии за счет плательщика\n\n", 0, C);
					$pdf->SetFont('times','',12);
					$pdf->multiCell(300, 5, "Elena Vetrova /\nВетрова Елена Васильевна, Генеральный директор\n\n", 0, L);
					$pdf->SetFont('times','',8);
					$pdf->multiCell(0, 5, "(Electronic copy, without signature and company stamp / электронная копия, без подписи и печати)", 0, C);
					}
				else{
					$string= "Ordering customer / Плательщик: \n".$realUser['COMPANY'].",\n ".$realUser['ADDRESS'].",\n ".$realUser['CITY'].", ".$realUser['COUNTRY'].",\n ".$realUser["PHONE"]."\n\n  Beneficiary / Получатель: Supralux Transit LLP\n Enterprise House, 82 Whitchurch Road,\n Cardiff, CF14 3LX, Wales, Great Britain\n\n\n Cardiff, United Kingdom\n\n" . date("d.m.Y");					
					$pdf = new TCPDF('P', 'mm', 'A4', false, 'UTF-8', false);
					$pdf->AddFont('times','I','timesi.php');
					$pdf->setPrintHeader(false);
					$pdf->setPrintFooter(false); 
					$pdf->AddPage();
					$pdf->Image('images/logo_ltm.jpg');
					$pdf->SetFont('times','',9);
					$pdf->setXY(35,12);
					$pdf->multiCell(150, 5, $string, 0, R);

$pdf->setXY(0,$pdf->getY() + 5);

					$pdf->SetFont('times','B',12);
					$pdf->multiCell(200, 5, "INVOICE N/ Счёт № ".$realUser["ID"]."/Kiev\n\n", 0, C);
					$pdf->SetFont('times','B',12);
					$pdf->multiCell(190, 5, "Details of payment / Предмет счёта:", 0, C);
					$pdf->SetFont('times','',10);
					$pdf->multiCell(185, 0, "Participation in the Luxury Travel Mart exhibition on September 24, 2013 at the\n Intercontinental Hotel Kiev, Ukraine, organized by Supralux Transit.\nPayment made on non-contractual basis\n\nУчастие в выставке Luxury Travel Mart, организуемой Supralux Transit\n 24 сентября 2013 года в отеле Интерконтиненталь, Киев, Украина.\n Договор не заключался и не предусмотрен условиями участия.\n\n", 0, C); 
					$pdf->SetFont('times','B',14);
					$pdf->multiCell(0, 5, "Total amount of payment / Сумма платежа: ".$realUser["PAY_COUNT"]." Euro\n", 0, C); 
					$pdf->SetFont('times','B',12);
					$pdf->multiCell(0, 5, "Payment information / Детали платежа:", 0, C); 
					$pdf->SetFont('times','',10);
					$pdf->multiCell(0, 5, "Please put the invoice number / Укажите номер счёта\n\n", 0, C); 
					$pdf->SetFont('times','B',12);
					$pdf->multiCell(0, 5, "Bank details / Банковские реквизиты:", 0, C);
					$pdf->SetFont('times','',12); 
					$pdf->multiCell(0, 5, "SUPRALUX TRANSIT LLP\nBeneficiary account IBAN: LV86 KBRB 1111 2144 9100 1\nBank Of  Beneficiary: Trasta Komercbanka A/O, Miesnieku iela 9, Riga LV-1050, Latvija\nSWIFT: KBRBLV2X\n\n", 0, C);
					$pdf->SetFont('times','',10);
					$pdf->multiCell(0, 10, "This invoice is valid for payments until the 20th of March 2013\n Bank charges at payer's expense\n Инвойс действителен до 20 марта 2013 года\n Банковские сборы и комиссии за счет плательщика\n\n", 0, C);
					$pdf->SetFont('times','',12);
					$pdf->multiCell(300, 5, "Valerii Dzuba / Валерий Дзюба,\nDirector / Директор\n\n", 0, L);
					$pdf->SetFont('times','',8);
					$pdf->multiCell(0, 5, "(Electronic copy, without signature and company stamp / электронная копия, без подписи и печати)", 0, C);
				}
// ОТПРАВЛЯЕМ ПИСЬМО СО СЧЕТОМ
				$headers = "From: noreply@luxurytravelmart.ru";
				$fileatt_name = "LTM_invoice.pdf";
				$fileatt_type = "application/pdf";
					
				$text= "<html><body><p><strong>THIS IS AN AUTOMATICALLY GENERATED EMAIL. PLEASE DO NOT REPLY TO THIS ADDRESS. FOR ANY ENQUIRES PLEASE CONTACT ARTYOM POLANSKIY AT artyom.polanskiy@luxurytravelmart.ru</strong></p>
<p>This is to confirm that your application form has been successfully processed and we are very pleased to welcome you to the <strong>4th Luxury Travel Mart, Kiev</strong> which will be held at the Intercontinental Hotel Kiev on the <strong>23rd of September 2014</strong>.</p>
<p><strong>Important deadlines and actions to be taken:</strong></p>
<p>Please pay the attached invoice by March 20, 2014. <strong>Payment</strong> must be received by the organizers <strong>within the specified time</strong>.  Please note that no original invoices will be sent. However, if your accounting department requires an original invoice for payment, please let us know and in this case we'll send you an original invoice.</p>
<p>Also, if you need to make any changes in the invoice (like, for example, the payment company is different from the company you registered for the LTM) - please let us know and we will change accordingly.</p>
<p><strong>By May 31, 2014</strong>: for the exhibitors catalogue please submit minimum 6 medium-resolution images of your hotel/company, logotype and the text you would like to have published (full A4 page).</p>
<p><strong>July 1, 2014</strong>: Opening of registration for the buyers.</p>
<p><strong>August 29, 2014</strong>: Password, user name and detailed instruction on how to use the online system and to set up your appointments will be sent for your attention.</p>
<p><strong>September 1 – September 18, 2014</strong>: Scheduling of appointments for the morning session.</p>
<p><strong>September 19, 2014</strong>: Your schedule should be ready and available for printing.</p>
<p><strong>September 19, 2014</strong>: Final exhibitors check.</p>
<p><strong>General terms and conditions:</strong></p>
<ul>
    <li>Payment should be received by organizers on time, as specified in the invoice. Your participation will be canceled automatically if payment isn't received within the specified time.</li>
    <li>Up to 17 appointments can be scheduled for exhibitors at the morning session. Organizers cannot guarantee that you'll meet only the accounts you are interested in and no responsibility will be taken by organizers for no-shows (except for the hosted buyers).</li>
    <li>Organizers reserve the right to change the date and venue of the event, exhibitors will be notified in writing.</li>
    <li>All cancelation of participation must be received in writing by fax or email. A full refund of the participation fee will be made if canceled by March 20, 2014. If canceled in the period from March 20, 2014 to April 20, 2014 - 50% of the participation fee will be charged for the cancelation. No refunds of the participation fees will be made if canceled after the 20th of April 2014.</li>
</ul>
<p>Thank you very much for your support and we are looking forward to seeing you in Kiev in September 2014.</p>
<p>Should you require any further information, please do not hesitate to contact us anytime.</p>
<p>Yours sincerely,<br />
Luxury Travel Mart Team</p></body></html>";
				// Generate a boundary string
				 $semi_rand = md5(time());
				 $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
				 $data = $pdf->Output("invoice.pdf",S) ;
				 // Add the headers for a file attachment
				 $headers .= "\nMIME-Version: 1.0\n" .
							 "Content-Type: multipart/mixed;\n" .
							 " boundary=\"{$mime_boundary}\"";
				
							 // Add a multipart boundary above the plain message
				 $message = "This is a multi-part message in MIME format.\n\n" .
							"--{$mime_boundary}\n" .
							"Content-Type: text/html; charset=\"iso-8859-1\"\n" .
							"Content-Transfer-Encoding: 7bit\n\n" .
							$text . "\n\n";
							// Base64 encode the file data
					$data = chunk_split(base64_encode($data));
				 // Add file attachment to the message
				 $message .= "--{$mime_boundary}\n" .
							 "Content-Type: {$fileatt_type};\n" .
							 " name=\"{$fileatt_name}\"\n" .
							 "Content-Disposition: attachment;\n" .
							 " filename=\"{$fileatt_name}\"\n" .
							 "Content-Transfer-Encoding: base64\n\n" .
							 $data. "\n\n" .
							 
							 "--{$mime_boundary}--\n";
				  $addr = $realUser['COMPANY'].': LTM Kiev 2014 - confirmation & invoice';
				 
				  if (mail($realUser['EMAIL'], $addr,$message,$headers,$path)){
					$user = new CUser;
					$fields = Array(
					  "UF_INVOICE" => "SENT",
					  );
					$user->Update($arParams["USER"], $fields);
					$strError .= $user->LAST_ERROR;
					mail("artyom.polanskiy@1st-pr.ru", $addr,$message,$headers);
					$arResult["TYPE"] = "SENT";
					$arResult["USER"] = $realUser;
					}
				  else{
					$arResult["TYPE"] = "SENT";
					$arResult["ERROR_MESSAGE"] = "Не удалось отправит счет<br />";
				  }
				
			}			
			elseif($_REQUEST["count_save"] == "Сохранить"){
				$user = new CUser;
				$fields = Array(
				  "NAME"              => $realUser["NAME"],
				  "LAST_NAME"         => $realUser["SURNAME"],
				  "UF_PAY_COUNT"      => $_REQUEST["pay"],
				  "UF_REKVIZIT"       => $_REQUEST["rekv"],
				  );
				$user->Update($arParams["USER"], $fields);
				$strError .= $user->LAST_ERROR;
				
				$realUser["PAY_COUNT"] = $_REQUEST["pay"];
				$countRekv = 0;
				foreach($realUser["REKV"] as $tempRek){
					if($tempRek["ID"] == $_REQUEST["rekv"]){
						$realUser["REKV"][$countRekv]["ACTIVE"] = 'Y';
					}
					else{
						$realUser["REKV"][$countRekv]["ACTIVE"] = 'N';
					}
					$countRekv++;
				}
				$arResult["TYPE"] = "FORM";
				$arResult["USER"] = $realUser;
			}			
		}
		elseif(isset($_GET["type"]) && $_GET["type"] == "look"){
			$arResult["TYPE"] = "COUNT";
			$arResult["USER"] = $realUser;
		}
		elseif(isset($_GET["type"]) && $_GET["type"] == "count"){
			$arResult["TYPE"] = "FORM";
			$arResult["USER"] = $realUser;
		}
	}
	else{
		$arResult["ERROR_MESSAGE"] = "У вас недостаточно прав для просмотра данной страницы!";
	}
}
//echo "<pre>"; print_r($arrAnswers); echo "</pre>";

$this->IncludeComponentTemplate();
?>