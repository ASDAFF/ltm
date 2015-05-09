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
					$string= "Ordering customer / Плательщик: \n".$realUser['COMPANY'].",\n ".$realUser['ADDRESS'].",\n ".$realUser['CITY'].", ".$realUser['COUNTRY'].",\n ".$realUser["PHONE"]."\n\n  Beneficiary / Получатель: Polanskiy Artem Valentinovich,\nregistered as independent entrepreneur\nwith State Registration Number 309503525800010\nat Federal Tax Service Inspectorate in \nPavlosvkiy Posad, Moscow Region\n\nИндивидуальный предприниматель \nПоланский Артём Валентинович,\nзарегистрированный Инспекцией Федеральной Налоговой Службы \nпо г. Павловский Посад Московской области, \nгосударственный регистрационный номер 309503525800010\nИНН 503507510512\n\nMoscow, Russia / Москва, Российская Федерация\n" . date("d.m.Y");
					$pdf = new TCPDF('P', 'mm', 'A4', false, 'UTF-8', false);
					$pdf->AddFont('times','I','timesi.php');
					$pdf->setPrintHeader(false);
					$pdf->setPrintFooter(false); 
						/*Начало контракта*/
						$pdf->AddPage();
						$pdf->setXY(0,20);
						$pdf->SetFont('times','B',14);
						$pdf->multiCell(190, 5, "LUXURY TRAVEL MART 2013 AUTUMN EDITION, MOSCOW, RUSSIA\nPARTICIPATION AGREEMENT N ".$realUser["ID"]."\n\n", 0, C);
						$pdf->SetFont('times','B',12);
						$pdf->multiCell(190, 5, "Between ".$realUser['COMPANY']."  and\n Polanskiy Artem Valentinovich, on behalf of the Luxury Travel Mart\n", 0, C);
						$pdf->SetFont('times','',12);
						$pdf->multiCell(190, 5, "Date: ".date("d.m.Y")."\n\n", 0, L);
						$pdf->SetFont('times','BI',10);
						$pdf->multiCell(190, 5, "1. Definitions\n", 0, L);
						$pdf->SetFont('times','',10);
						$pdf->multiCell(190, 5, "1.1. In these terms & conditions: Agreement means the agreement between the Exhibitor and Polanskiy Artem Valentinovich, registered as independent entrepreneur, hereafter the Organizer, whereby the Organizer has agreed to allocate space to the Exhibitor for the purpose of exhibiting at the Exhibition; Deposit means a sum equal to 100% of the Fee; Exhibition means the Luxury Travel Mart 2013 Autumn Edition in Moscow, Russian Federation; Exhibitor means the company which will exhibit at the Exhibition; Fee means the charges payable by the Exhibitor under the Agreement; Force majeure means, in relation to either party, any circumstances beyond the reasonable control of that party (including, without limitation, fire, storm, tempest, lightning, material emergency, war, terrorist or military activity, labour disputes, strikes or lockouts, civil disturbances, explosions, inevitable accident, intervention or regulation); Stand means the space allocated to the Exhibitor under the Agreement; Terms means these terms and conditions and the terms; Venue means the site at which the Exhibition is to be held.\n", 0, L);
						$pdf->SetFont('times','BI',10);
						$pdf->multiCell(190, 5, "2. Terms of the agreement\n", 0, L);
						$pdf->SetFont('times','',10);
						$pdf->multiCell(190, 5, "2.1. These Terms constitute the entire agreement between the parties; and supersede any previous agreement or understanding. All other terms, express or implied by statute or otherwise, are excluded to the fullest extent permitted by law.\n", 0, L);
						$pdf->multiCell(190, 5, "2.2. The Organizer must agree any changes or additions to the Agreement or the Terms in writing. The Organizer may, from time to time, vary or amend these Terms, provided that such variations or amendments do not operate to diminish the rights reserved to the Exhibitor under the Agreement, and shall not operate to increase the liabilities of the Organizer or its agents.\n", 0, L);
						$pdf->SetFont('times','BI',10);
						$pdf->multiCell(190, 5, "3. Fees\n", 0, L);
						$pdf->SetFont('times','',10);
						$pdf->multiCell(190, 5, "3.1. The Organizer shall invoice the Exhibitor for the Deposit on acceptance of the Agreement, which must be paid by the May 15, 2013.\n", 0, L);
						$pdf->multiCell(190, 5, "3.2. The balance of the Fee, if any, shall be invoiced at least 180 days prior to the start of the Exhibition.\n", 0, L);
						$pdf->multiCell(190, 5, "3.3. All invoices should be payable within 30 days of the date of the relevant invoice.\n", 0, L);
						$pdf->multiCell(190, 5, "3.4. The Exhibitor shall not be permitted to exhibit unless payment in full has been made of the Fee prior to commencement of the Exhibition.\n", 0, L);
						$pdf->multiCell(190, 5, "3.5. If you default on this Agreement by not paying your fees by the due date, the Fee will be increased from the Early Booking Fee of 2800 (two thousand eight hundred) euro + 18% VAT to a Regular Fee of 3300 (three thousand three hundred) euro + 18% VAT, and a new invoice will be sent automatically on the 15th of May 2013.\n", 0, L);
						$pdf->SetFont('times','BI',10);
						$pdf->multiCell(190, 5, "4. Cancelation\n", 0, L);
						$pdf->SetFont('times','',10);
						$pdf->multiCell(190, 5, "4.1. The Organizer shall be entitled to cancel the Agreement, at its sole discretion, if it considers that the products or services provided by the Exhibitor or any person sharing the Stand do not fit the profile of the Exhibition. A full refund of the Fee will be made to the Exhibitor.\n", 0, L);
						$pdf->multiCell(190, 5, "4.2. The Exhibitor may cancel the Agreement in its entirety by giving prior notice in writing to the Organizer. A full refund of the participation fee will be made if canceled by May 15, 2013. If canceled in the period from May 15, 2013 to June 15, 2013 - 50% of the participation fee will be charged for the cancelation. No refund of the participation fees will be made if canceled after the 15th of June 2013.\n", 0, L);
						$pdf->multiCell(190, 5, "4.3. Neither the Organizer or its agents shall be required to assist the Exhibitor to obtain any documents necessary for entry into the country where the Exhibition is to be held. Any failure of the Exhibitor to obtain any such documents from the relevant authorities shall not constitute frustration of the Agreement. The Exhibitor, however, may substitute another person to take the Stand, subject to approval of such person by the Organizer. In the event of such substitution, the Exhibitor shall remain primarily liable to the Organizer under the Agreement.\n", 0, L);
						$pdf->multiCell(190, 5, "4.4. Either party may (without limiting any other remedy) at any time terminate the Agreement by giving written notice to the other if the other commits any breach of these Terms and (if capable of remedy) fails to remedy the breach within 30 days after being required by written notice to do so, or if the other goes into liquidation, becomes bankrupt, makes a voluntary arrangement with its creditors or has a receiver or administrator appointed.\n", 0, L);
						$pdf->SetFont('times','BI',10);
						$pdf->multiCell(190, 5, "5. Indemnity\n", 0, L);
						$pdf->SetFont('times','',10);
						$pdf->multiCell(190, 5, "5.1. The Exhibitor shall indemnify and hold harmless the Organizer against any loss, damages, costs, expenses or other claims arising from: a) Breach of these Terms by the Exhibitor or any person sharing the Stand; and b) Acts or omissions of the Exhibitor, it’s employees, agents, sub contractors or any person sharing the Stand, whether negligent or otherwise.\n\n", 0, L);
						$pdf->SetFont('times','BI',10);
						$pdf->multiCell(190, 5, "6. General\n", 0, L);
						$pdf->SetFont('times','',10);
						$pdf->multiCell(190, 5, "6.1. A notice required to be given by either party to the other under these Terms shall be addressed in writing to the other party at its registered office or principal place of business or such other address as may have been notified pursuant to this provision to the party giving the notice.\n", 0, L);
						$pdf->multiCell(190, 5, "6.2. No failure or delay by either party in exercising any of its rights under the Agreement shall be deemed to be a waiver of that right, and no waiver by either party of any breach of the Agreement by the other shall be considered as a waiver of any subsequent breach of the same or any other provision.\n", 0, L);
						$pdf->multiCell(190, 5, "6.3. The parties will resolve all disputes which may arise from this Agreement by way of negotiation.\n", 0, L);
						$pdf->multiCell(190, 5, "6.4. Any disputes arising from or in connection with this Agreement shall be subject to resolution at the Arbitration Court of the City of Moscow.\n", 0, L);
						$pdf->multiCell(190, 5, "6.5. An official catalogue of Exhibitors will be issued. The Organizer does not accept any responsibility for any omissions, misquotations or other errors, which may occur in the compilation of the catalogue.\n", 0, L);
						$pdf->multiCell(190, 5, "6.6. The Exhibitor agrees to give the Organizer consent under privacy laws to a) use personal information for internal purposes, including giving consent under privacy laws to: accounts processing, exhibitor analyses, event invitations b) give your personal information to exhibition contractors and our members worldwide to develop our exhibition businesses and services.\n", 0, L);
						$pdf->SetFont('times','BI',10);
						$pdf->multiCell(190, 5, "7. Limitation of liability\n", 0, L);
						$pdf->SetFont('times','',10);
						$pdf->multiCell(190, 5, "7.1. Except in respect of death or personal injury caused by negligence or as expressly provided in these Terms, neither the Organizer, its servants or agents shall be liable to the Exhibitor by reason of any representation (unless fraudulent), or any implied warranty, condition or other term, or any duty at common law, or under the express terms of the Agreement, for any loss of profit or any indirect, special or consequential loss, damage, costs, expenses or other claims (whether caused by the negligence of the Organizer, its servants or agents or otherwise) which arise out of or in connection with the Exhibition (including any delay or cancellation of the Exhibition).\n", 0, L);
						$pdf->multiCell(190, 5, "7.2. Neither the Organizer nor the Venue operator or their agents or employees shall have any liability for any loss, damage or delay incurred by the Exhibitor arising: \na) as a result of an act of Force majeure; \nb) in relation to the movement of freight to and from the Venue; or \nc) other than personal injury or death, by fire, theft or injury of any nature.\n", 0, L);
						$pdf->multiCell(190, 5, "7.3. If the Exhibition is cancelled, postponed, abandoned or curtailed, or the Venue becomes wholly or partially unavailable for the holding of the Exhibition as a result of; a) an act of Force majeure; or b) conflicts or misinterpretations arising with the national or local authorities of the host county, its sponsors, agents or other bodies regarding any and all aspects of the Exhibition; then: c) the Organizer or any of its agents or servants shall not have any liability for any loss, damage or delay to the Exhibitor arising as a result of such circumstances; d) the Organizer shall be entitled, but not obliged, to reschedule the Exhibition to another date and/or at an alternative site; and e) the Organizer shall be entitled to retain such part of all sums paid by the Exhibitor as it, in it’s absolute discretion, considers necessary to meet any expenses incurred by it in connection with the Exhibition.\n", 0, L);
						$pdf->SetFont('times','BI',10);
						$pdf->multiCell(190, 5, "8. Conduct of Exhibitor\n", 0, L);
						$pdf->SetFont('times','',10);
						$pdf->multiCell(190, 5, "8.1. The Exhibitor has no right to occupy any particular space, although the Organizer will endeavour to take into account the Exhibitor’s preferences when allocating space.\n", 0, L);
						$pdf->multiCell(190, 5, "8.2. The Exhibitor shall not assign any of its rights under the Agreement, or share, sub-let or grant licences in respect of the whole or any part of the Stand, save as permitted in writing by the Organizer.\n", 0, L);
						$pdf->multiCell(190, 5, "8.3. The Exhibitor shall not:\n a) remove or dismantle any part of its exhibit from its Stand, prior to the official close of the Exhibition, and shall have an authorized representative present at the Stand at all times when the Exhibition is open to visitors and during installation and dismantling of the exhibit; b) obstruct the view of adjoining exhibits nor operate in any manner intrusive or damaging to other exhibitors, including, without limitation, unreasonable use of light, banners, and noise; or c) display or distribute any political, illegal, immoral or offensive material at the Exhibition. No lotteries, games of chance or raffles will be conducted without the prior written consent of the Organizer.\n", 0, L);
						$pdf->multiCell(190, 5, "8.4. The Exhibitor shall comply with all reasonable instructions of the Organizer, the Venue operator and all statutory regulations.\n", 0, L);
						$pdf->SetFont('times','BI',10);
						$pdf->multiCell(190, 5, "9. General terms of participation (summary)\n", 0, L);
						$pdf->SetFont('times','',10);
						$pdf->multiCell(190, 5, "9.1. Participation at the Exhibition under this Agreement includes:\na) The Exhibitor’s participation at all events of the Exhibition, specified below.\nb) Working space (Stand) at all events, including the name sign of the Exhibitor\nc) Exhibitor’s inclusion in the Exhibition catalogue, including translation of the text from English to Russian, if necessary (maximum 800 words).\nd) Lunch and coffee breaks as at the schedule; drinks are included.\ne) Access to the Exhibition’s web site for appointment schedules and guests databases\n", 0, L);
						$pdf->multiCell(190, 5, "9.2. The schedule of events of the Exhibition is as follows: 10:00 – 14:20 – Morning session with up to 17 pre-scheduled appointments, 14:30  – 15:30 – Lunch, 15:30 – 18:00 – non-pre scheduled appointments with the hosted buyers, 18:30 – 21:30 – Evening session at the cocktail-workshop format.\n", 0, L);
						$pdf->multiCell(190, 5, "Up to 17 appointments may be scheduled for exhibitors at the morning session. The Organizer cannot guarantee that the Exhibitor will meet only accounts in which the Exhibitor is interested, and the Organizer accepts no responsibility for no-shows (except for hosted buyers).\n", 0, L);
						$pdf->multiCell(190, 5, "Stands will be allocated based on the registration and payment days, and it’s not possible to pre-book any particular Stand in advance.\n", 0, L);
						$pdf->multiCell(190, 5, "A maximum of 2 persons are allowed at the Stand of the Exhibitor. A third person may attend only at the evening session and at an additional cost of 500 (five hundred) euros + 18% VAT, payable in advance.\n", 0, L);
						$pdf->multiCell(190, 5, "Stand sharing is possible subject to the Organizer’s approval only for hotels from the same geographical region (country or city). Stand sharing between hotels or hotel groups and other travel service providers are not allowed.\nNo banners and posters are allowed at the Exhibition.\n", 0, L);
						$pdf->multiCell(190, 5, "9.3. The Organizer reserves the right to change the date and venue of the event, exhibitors will be notified in writing.\n", 0, L);
						$pdf->SetFont('times','B',14);
	$pdf->multiCell(190, 5, "\n\n I have read and agree to the terms and conditions of this agreement.\n", 0, C);
						$pdf->SetFont('times','',10);
						$pdf->MultiCell(100, 5, 'Exhibitor ', 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(100, 5, "Organizer:\n"
								."Polanskiy Artem Valentinovich,\n registered as independent entrepreneur\n with State Registration Number 309503525800010\n at Federal Tax Service Inspectorate in\n Pavlosvkiy Posad, Moscow Region\n"
								."Individual tax-payer number: 503507510512\n"
								."Address: Ulitsa Chapaeva 5-38\n 142500 Pavlosvkiy Posad, Moscow Region, Russia\n"
								."Telephone: +7 916 204 3136\n", 0, 'L', 0, 1, '', '', true);
						$pdf->SetFont('times','BI',10);
						$pdf->multiCell(190, 5, "\n", 0, L);
						$pdf->SetFont('times','',10);
						$pdf->MultiCell(100, 5, 'Signature _________________________'.$txt, 0, 'L', 0, 0, '', '', true);
						$pdf->MultiCell(100, 5, 'Signature _________________________'.$txt, 0, 'L', 0, 1, '', '', true);
	$stampY = $pdf->getY();
	$pdf->Image('images/stamp_polansky.png',75,$stampY,130,55,'','','',false,72,'',false,false,1);
						/*Конец контракта*/

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
					$pdf->multiCell(185, 0, "Participation in the Luxury Travel Mart exhibition on September 24, 2013 at the\n Intercontinental Hotel Kiev, Ukraine, organized by Artem Polanskiy.\nPayment made on non-contractual basis\n\nУчастие в выставке Luxury Travel Mart, организуемой ИП Поланский Артём Валентинович\n 24 сентября 2013 года в отеле Интерконтиненталь, Киев, Украина.\n Договор не заключался и не предусмотрен условиями участия.\n\n", 0, C); 
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
					$pdf->multiCell(0, 10, "This invoice is valid for payments until the 20th of March 2013\nBank charges at payer's expense\nИнвойс действителен до 20 марта 2013 года\nБанковские сборы и комиссии за счет плательщика\n\n", 0, C);
					$pdf->SetFont('times','',12);
					$pdf->multiCell(300, 5, "Artem V. Polanskiy /\nПоланский Артём Валентинович\n\n", 0, L);
					$pdf->SetFont('times','',8);
					$pdf->multiCell(0, 5, "(Electronic copy, without signature and company stamp / электронная копия, без подписи и печати)", 0, C);
				}
				elseif($_REQUEST["rekv"] == 2){
					$string= "Ordering customer / Плательщик: \n".$realUser['COMPANY'].",\n ".$realUser['ADDRESS'].",\n ".$realUser['CITY'].", ".$realUser['COUNTRY'].",\n ".$realUser["PHONE"]."\n\n  Beneficiary / Получатель: Travel Media,\nregistered as Society with limited liability\nwith State Registration Number 1047796617472\nat Federal Tax Service Inspectorate No 46 in\nMoscow\n\nОбщество с ограниченной ответственностью «Трэвэл Медиа»,\nзарегистрированное Инспекцией Федеральной Налоговой Службы № 46 \nпо г. Москве, \nгосударственный регистрационный номер 1047796617472\nИНН 7707525284\n\nMoscow, Russia / Москва, Российская Федерация\n" . date("d.m.Y");
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
					$pdf->multiCell(185, 0, "Participation in the Luxury Travel Mart exhibition on September 24, 2013 at the\n Intercontinental Hotel Kiev, Ukraine, organized by Travel Media.\nPayment made on non-contractual basis\n\nУчастие в выставке Luxury Travel Mart, организуемой ООО «Трэвэл Медиа»\n 24 сентября 2013 года в отеле Интерконтиненталь, Киев, Украина.\n Договор не заключался и не предусмотрен условиями участия.\n\n", 0, C); 
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
					$pdf->multiCell(0, 10, "This invoice is valid for payments until the 20th of March 2013\n Bank charges at payer's expense\n Инвойс действителен до 20 марта 2013 года\n Банковские сборы и комиссии за счет плательщика\n\n", 0, C);
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
<p>This is to confirm that your application form has been successfully processed and we are very pleased to welcome you to the <strong>3rd Luxury Travel Mart, Kiev</strong> which will be held at the Intercontinental Hotel Kiev on the <strong>24th of September 2013</strong>.</p>
<p><strong>Important deadlines and actions to be taken:</strong></p>
<p>Please pay the attached invoice by March 20, 2013. <strong>Payment</strong> must be received by the organizers <strong>within the specified time</strong>.  Please note that no original invoices will be sent. However, if your accounting department requires an original invoice for payment, please let us know and in this case we'll send you an original invoice.</p>
<p>Also, if you need to make any changes in the invoice (like, for example, the payment company is different from the company you registered for the LTM) - please let us know and we will change accordingly.</p>
<p><strong>By May 31, 2013</strong>: for the exhibitors catalogue please submit minimum 6 medium-resolution images of your hotel/company, logotype and the text you would like to have published (full A4 page).</p>
<p><strong>July 1, 2013</strong>: Opening of registration for the buyers.</p>
<p><strong>August 29, 2013</strong>: Password, user name and detailed instruction on how to use the online system and to set up your appointments will be sent for your attention.</p>
<p><strong>September 2 – September 18, 2013</strong>: Scheduling of appointments for the morning session.</p>
<p><strong>September 19, 2013</strong>: Your schedule should be ready and available for printing.</p>
<p><strong>September 19, 2013</strong>: Final exhibitors check for the badges</p>
<p><strong>General terms and conditions:</strong></p>
<ul>
    <li>Payment should be received by organizers on time, as specified in the invoice. Your participation will be canceled automatically if payment isn't received within the specified time.</li>
    <li>Up to 17 appointments can be scheduled for exhibitors at the morning session. Organizers cannot guarantee that you'll meet only the accounts you are interested in and no responsibility will be taken by organizers for no-shows (except for the hosted buyers).</li>
    <li>Organizers reserve the right to change the date and venue of the event, exhibitors will be notified in writing.</li>
    <li>All cancelation of participation must be received in writing by fax or email. A full refund of the participation fee will be made if canceled by March 20, 2013. If canceled in the period from March 20, 2013 to April 20, 2013 - 50% of the participation fee will be charged for the cancelation. No refunds of the participation fees will be made if canceled after the 20th of April 2013.</li>
</ul>
<p>Thank you very much for your support and we are looking forward to seeing you in Kiev in September 2013.</p>
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
				  $addr = $realUser['COMPANY'].': LTM Kiev 2013 - confirmation & invoice';
				 
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