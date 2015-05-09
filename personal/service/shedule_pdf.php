<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
  CModule::IncludeModule('iblock');

  $times = array(
	  '10:00 – 10:10', '10:15 – 10:25',
	  '10:30 – 10:40', '10:45 – 10:55',
	  '11:00 – 11:10', '11:15 – 11:25',
	  '11:30 – 11:40', '11:45 – 11:55',
	  '12:10 – 12:20', '12:25 – 12:35',
	  '12:40 – 12:50', '12:55 – 13:05',
	  '13:10 – 13:20', '13:25 – 13:35',
	  '13:40 – 13:50', '13:55 – 14:05',
	  '14:10 – 14:20'
  );

  /*---------------------------------------------------*/
  //           ФОРМИРУЕМ ВЫВОД ДЛЯ ШАБЛОНА             //
  /*---------------------------------------------------*/
	  $rsUser = CUser::GetByID($USER->GetID());
	  $thisUser = $rsUser->Fetch();
	  $arResult["USER"]["NAME"] = $thisUser["NAME"]." ".$thisUser["LAST_NAME"];
	  $arResult["USER"]["COMPANY"] = $thisUser["WORK_COMPANY"];
	  $arAnswer = CFormResult::GetDataByID($thisUser["UF_ANKETA"], array(), $arTmpResult, $arAnswer2);
	  $userHall = '';
	  foreach($arAnswer2["user_hall"] as $value){
		  $userHall = $value["MESSAGE"];
	  }
	  $arResult["USER"]["HALL"] = $userHall;
	  $arResult["USER"]["TABLE"] = $arAnswer2["user_table"]["394"]["USER_TEXT"];
	  $arParams["APP_COUNT"] = 17;
	  $arParams["GROUP_RECIVER_ID"] = 6;

	  $myShedule = array();
	  $myFreeMeet = array();
	  $myBeasyMeet = array();
	  $myFreeCount = 0;
	  //Формируем основу для массива встреч
	  for($i=1; $i<$arParams["APP_COUNT"]+1; $i++){
		  $myShedule[$i]["ID"] = $thisUser["UF_SHEDULE_".$i];
		  $myShedule[$i]["TITLE"] = $times[$i-1];
		  $myShedule[$i]["STATUS"] = '';
		  $myShedule[$i]["NOTES"] = 'FREE';
		  $myShedule[$i]["PARTNER_ID"] = '';
		  $myShedule[$i]["REP"] = '';
		  $myShedule[$i]["COMPANY"] = '';
		  $myShedule[$i]["LIST"]["COUNT"] = 0;
		  $myShedule[$i]["LIST"]["COMPANYS"] = array();
		  if($thisUser["UF_SHEDULE_".$i] == ''){
			$myFreeMeet[] = $i;
			$myFreeCount++;
		  }
		  else{
			$myShedule[$i]["NOTES"] = 'ACT';
			$myBeasyMeet[] = $thisUser["UF_SHEDULE_".$i];
		  }
	  }
	  //СПИСОК НАЗНАЧЕННЫХ ВСТРЕЧ
	  if($myBeasyMeet){
		  $arFilterM = Array(
			 "IBLOCK_ID" => $arParams["APP_ID"],
			 "ID" => $meeting_list
			 );
		  $arSelect = Array("DATE_CREATE", "ID", "NAME", "ACTIVE", "PROPERTY_SENDER_ID", "PROPERTY_RECIVER_ID", "PROPERTY_STATUS", "PROPERTY_TIME");
		  $resMeet = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilterM, false, false, $arSelect);
		  while($ar_meet = $resMeet->GetNext()){
			for($i=1; $i<$arParams["APP_COUNT"]+1; $i++){
				if($thisUser["UF_SHEDULE_".$i] == $ar_meet["ID"]){
				  if($ar_meet["ACTIVE"] == 'N'){
					$myShedule[$i]["NOTES"] = 'N';
				  }
				  if($ar_meet['PROPERTY_SENDER_ID_VALUE'] == $thisUser['ID']){
					$myShedule[$i]["STATUS"] = 'MY';
					$myShedule[$i]["PARTNER_ID"] = $ar_meet['PROPERTY_RECIVER_ID_VALUE'];
				  }
				  else{
					$myShedule[$i]["STATUS"] = 'PEP';
					$myShedule[$i]["PARTNER_ID"] = $ar_meet['PROPERTY_SENDER_ID_VALUE'];
				  }
				  if($ar_meet['PROPERTY_STATUS_VALUE'] == 'ADM'){
					$myShedule[$i]["STATUS"] = 'ADM';
				  }
				}
			}
		  }
	  }
	  //СПИСОК ПОЛЬЗОВАТЕЛЕЙ
	  $filter = Array(
		  "GROUPS_ID"  => Array($arParams["GROUP_RECIVER_ID"])
	  );
	  $rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter, array("SELECT"=>array("UF_*"))); // выбираем пользователей
	  $myWishIn = array();
	  $myWishOut = array();
	  $notFreeTimes = array();
	  while($arUsersTemp=$rsUsers->Fetch()){
		  $countFree = 0;
		  for($i=1; $i<$arParams["APP_COUNT"]+1; $i++){
			  if($myShedule[$i]["ID"] != ''){
				  if($myShedule[$i]["PARTNER_ID"] == $arUsersTemp["ID"]){
					$myShedule[$i]["REP"] = $arUsersTemp["NAME"]." ".$arUsersTemp["LAST_NAME"];
					$myShedule[$i]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
				  }
			  }
			  if($arUsersTemp["UF_SHEDULE_".$i] != ''){
				  $countFree++;
			  }

		  }
		  if($countFree == $arParams["APP_COUNT"]){
			  $notFreeTimes[$arUsersTemp["ID"]] = $arUsersTemp["WORK_COMPANY"];
		  }
		  if(stripos($thisUser["UF_WISH_OUT"], ", ".$arUsersTemp["ID"]." ") !== false){
			  $myWishOut[$arUsersTemp["ID"]] = $arUsersTemp["WORK_COMPANY"];
		  }
		  if(stripos($thisUser["UF_WISH_IN"], ", ".$arUsersTemp["ID"]." ") !== false){
			  $myWishIn[$arUsersTemp["ID"]] = $arUsersTemp["WORK_COMPANY"];
		  }

	  }
	  $arResult["SHEDULE"] = $myShedule;
	  $arResult["APP_COUNT"] = $arParams["APP_COUNT"];
	  $arResult["WISH_IN"] = $myWishIn;
	  $arResult["WISH_OUT"] = $myWishOut;
	  $arResult["NOT_FREE"] = $notFreeTimes;

  /*print_r($arResult["SHEDULE"]);
  die();*/

	require('pdf/tcpdf.php');
	$pdf = new TCPDF('P', 'mm', 'A4', false, 'UTF-8', false);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->AddFont('times','I','timesi.php');
	$pdf->AddPage();
	$pdf->ImageSVG($file='images/logo.svg', $x=30, $y=5, $w='150', $h='', $link='', $align='', $palign='', $border=0, $fitonpage=false);
	$pdf->setXY(0,22);
	$pdf->SetFont('Times','B',17);
	$pdf->multiCell(210, 6, "Personal diary during the morning session at LTM Moscow 2013", 0, C);
	$pdf->SetFont('Times','',14);
	$pdf->setXY(15,35);
	$pdf->multiCell(180, 5, $arResult["USER"]["COMPANY"], 0, L);

	$pdf->setXY(15,$pdf->getY() + 1);
	$pdf->multiCell(210, 5,$arResult["USER"]['NAME']." ".$arResult["USER"]["LAST_NAME"], 0, L);
	$pdf->setXY(15,$pdf->getY() + 2);
	if($arResult["USER"]["HALL"] != "None"){
		$pdf->multiCell(210, 5, "Hall, Table: ".$arResult["USER"]["HALL"].", ".$arResult["USER"]["TABLE"], 0, L);
	}
	else{
		$pdf->multiCell(210, 5, "Hall, Table: ", 0, L);
	}
	$pdf->setXY(0,65);

	$pdf->SetFont('Times','',11);
	$pdf->SetX(20);

	/* Формируем таблицу */
	$tbl = '<table cellspacing="0" cellpadding="5" border="1">
		<tr>
			<td align="center" width="75">Time</td>
			<td align="center" width="340">Companies</td>
			<td align="center" width="90"> </td>
		</tr>';
	$counter = 0;
	for($i=1; $i<9; $i++){
		if($arResult["SHEDULE"][$i]['NOTES'] == 'FREE'){
			$tbl .= '<tr>
				  <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
				  <td colspan="2" align="center">Free time</td>
			  </tr>';
			$counter++;
		}
		elseif($arResult["SHEDULE"][$i]['NOTES'] == 'ACT'){
			$tbl .= '<tr>
				  <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
				  <td>Company: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Representative: '.$arResult["SHEDULE"][$i]["REP"].'</td>
				  <td align="center">Accepted</td>
			  </tr>';
			$counter++;
		}
		else{
			if($arResult["SHEDULE"][$i]['STATUS'] == 'MY'){
				$tbl .= '<tr>
					  <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
					  <td>Company: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Representative: '.$arResult["SHEDULE"][$i]["REP"].'</td>
					  <td align="center">Request sent</td>
				  </tr>';
			}
			elseif($arResult["SHEDULE"][$i]['STATUS'] == 'ADM'){
				$tbl .= '<tr>
					  <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
					  <td>Company: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Representative: '.$arResult["SHEDULE"][$i]["REP"].'</td>
					  <td align="center">From administrator</td>
				  </tr>';
			}
			else{
				$tbl .= '<tr>
					  <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
					  <td>Company: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Representative: '.$arResult["SHEDULE"][$i]["REP"].'</td>
					  <td align="center">Request received</td>
				  </tr>';
			}
			$counter++;
		}
	}
	$tbl .= '<tr>
		  <td>11:55 – 12:10</td>
		  <td colspan="2" align="center">Coffee break</td>
	  </tr>';
	$tbl .= '</table>';
	$pdf->writeHTML($tbl, true, false, false, false, '');
	$pdf->setXY(0,$pdf->getY() + 1);
	$pdf->multiCell(197, 5, "continue on the next page", 0, R);

	$pdf->AddPage();
	$tbl = '<table cellspacing="0" cellpadding="5" border="1">
		<tr>
			<td align="center" width="75">Time</td>
			<td align="center" width="340">Companies</td>
			<td align="center" width="90"> </td>
		</tr>';
	for($i=9; $i<$arParams["APP_COUNT"]+1; $i++){
		if($arResult["SHEDULE"][$i]['NOTES'] == 'FREE'){
			$tbl .= '<tr>
				  <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
				  <td colspan="2" align="center">Free time</td>
			  </tr>';
			$counter++;
		}
		elseif($arResult["SHEDULE"][$i]['NOTES'] == 'ACT'){
			$tbl .= '<tr>
				  <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
				  <td>Company: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Representative: '.$arResult["SHEDULE"][$i]["REP"].'</td>
				  <td align="center">Accepted</td>
			  </tr>';
			$counter++;
		}
		else{
			if($arResult["SHEDULE"][$i]['STATUS'] == 'MY'){
				$tbl .= '<tr>
					  <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
					  <td>Company: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Representative: '.$arResult["SHEDULE"][$i]["REP"].'</td>
					  <td align="center">Request sent</td>
				  </tr>';
			}
			elseif($arResult["SHEDULE"][$i]['STATUS'] == 'ADM'){
				$tbl .= '<tr>
					  <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
					  <td>Company: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Representative: '.$arResult["SHEDULE"][$i]["REP"].'</td>
					  <td align="center">From administrator</td>
				  </tr>';
			}
			else{
				$tbl .= '<tr>
					  <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
					  <td>Company: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Representative: '.$arResult["SHEDULE"][$i]["REP"].'</td>
					  <td align="center">Request received</td>
				  </tr>';
			}
			$counter++;
		}
	}
	$tbl .= '</table>';
	$pdf->writeHTML($tbl, true, false, false, false, '');


	$pdf->setXY(0,$pdf->getY() + 1);
	$pdf->multiCell(210, 5, "Please make your appointments in time; any delay in timing will effect the next exhibitor after you.", 0, C);
	$pdf->setXY(0,$pdf->getY() + 2);
	$pdf->multiCell(210, 5, "Please report all no-shows to your Hall Manager or to the registration desk of the Luxury Travel Mart.", 0, C);

	$pdf->Output("print.pdf", I);
?>