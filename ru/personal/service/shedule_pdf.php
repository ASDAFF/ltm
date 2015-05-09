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
	  $arParams["APP_COUNT"] = 17;
	  $arParams["GROUP_RECIVER_ID"] = 4;

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
		  for($i=1; $i<$arParams["APP_COUNT"]+1; $i++){
			  if($myShedule[$i]["ID"] != ''){
				  if($myShedule[$i]["PARTNER_ID"] == $arUsersTemp["ID"]){
					$myShedule[$i]["REP"] = $arUsersTemp["NAME"]." ".$arUsersTemp["LAST_NAME"];
					$myShedule[$i]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
					$arAnswer = CFormResult::GetDataByID($arUsersTemp["UF_ANKETA"], array(), $arTmpResult, $arAnswer2);
					$userHall = '';
					foreach($arAnswer2["user_hall"] as $value){
						$userHall = $value["MESSAGE"];
					}
					if($userHall == 'None' || !isset($arAnswer2["user_hall"])){
						$myShedule[$i]["HALL"] = '';
						$myShedule[$i]["TABLE"] = '';
					}
					else{
						$myShedule[$i]["HALL"] = $userHall;
						$myShedule[$i]["TABLE"] = $arAnswer2["user_table"]["394"]["USER_TEXT"];
					}
				  }
			  }
		  }
	  }
	  $arResult["SHEDULE"] = $myShedule;
	  $arResult["APP_COUNT"] = $arParams["APP_COUNT"];

	require('pdf/tcpdf.php');
	$pdf = new TCPDF('P', 'mm', 'A4', false, 'UTF-8', false);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->AddFont('times','I','timesi.php');
	$pdf->AddPage();
	$pdf->ImageSVG($file='images/logo.svg', $x=30, $y=5, $w='150', $h='', $link='', $align='', $palign='', $border=0, $fitonpage=false);

	$pdf->setXY(0,25);
	$pdf->SetFont('times','B',17);
	$pdf->multiCell(210, 6, "Расписание встреч\nна утренней сессии LTM Moscow 2013", 0, C);
	$pdf->SetFont('times','',15);
	$pdf->setXY(30,44);
	$pdf->multiCell(210, 5, $arResult["USER"]["COMPANY"].", ". $arResult["USER"]["CITY"], 0, L);
	$pdf->setXY(30,52);
	$pdf->multiCell(210, 5,$arResult["USER"]['NAME']." ".$arResult["USER"]["LAST_NAME"], 0, L);
	$pdf->SetFont('times','',13);
	$pdf->setXY(0,60);
	$pdf->multiCell(210, 5, "Ваше расписание", 0, C);

	$pdf->setY(70);
	$pdf->SetX(20);

	/* Формируем таблицу */
        $tbl = '<table cellspacing="0" cellpadding="5" border="1">
    <tr>
        <td align="center" width="70">Время</td>
        <td align="center" width="240">Участники</td>
        <td align="center" width="90">Статус</td>
        <td align="center" width="95">Зал, Стол</td>
    </tr>';
	$pdf->SetFont('times','',10);
	$counter = 0;
	for($i=1; $i<10; $i++){
		if($arResult["SHEDULE"][$i]['NOTES'] == 'FREE'){
			$tbl .= '<tr>
                        <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
                        <td colspan="3" align="center">Свободно</td>
                    </tr>';
			$counter++;
		}
		elseif($arResult["SHEDULE"][$i]['NOTES'] == 'ACT'){
			$tbl .= '<tr>
                        <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
                        <td>Компания: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Представитель: '.$arResult["SHEDULE"][$i]["REP"].'</td>
                        <td align="center">Подтверждена</td>';
			if($arResult["SHEDULE"][$i]['HALL']){
				 $tbl .= '<td>'.$arResult["SHEDULE"][$i]['HALL'].', '.$arResult["SHEDULE"][$i]['TABLE'].'</td>
                        </tr>';
			}
			else{
				$tbl .= '<td> </td>
                        </tr>';
			}
			$counter++;
		}
		else{
			if($arResult["SHEDULE"][$i]['STATUS'] == 'MY'){
				$tbl .= '<tr>
                                <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
                                <td>Компания: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Представитель: '.$arResult["SHEDULE"][$i]["REP"].'</td>
                                <td align="center">От меня</td>';
				if($arResult["SHEDULE"][$i]['HALL']){
					 $tbl .= '<td>'.$arResult["SHEDULE"][$i]['HALL'].', '.$arResult["SHEDULE"][$i]['TABLE'].'</td>
                                </tr>';
				}
				else{
					$tbl .= '<td> </td>
                                </tr>';
				}
			}
			elseif($arResult["SHEDULE"][$i]['STATUS'] == 'ADM'){
				$tbl .= '<tr>
                                <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
                                <td>Компания: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Представитель: '.$arResult["SHEDULE"][$i]["REP"].'</td>
                                <td align="center">Назначено администратором</td>';
				if($arResult["SHEDULE"][$i]['HALL']){
					 $tbl .= '<td>'.$arResult["SHEDULE"][$i]['HALL'].', '.$arResult["SHEDULE"][$i]['TABLE'].'</td>
                                </tr>';
				}
				else{
					$tbl .= '<td> </td>
                                </tr>';
				}
			}
			else{
				 $tbl .= '<tr>
                                <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
                                <td>Компания: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Представитель: '.$arResult["SHEDULE"][$i]["REP"].'</td>
                                <td align="center">Мне</td>';
                                if($arResult["SHEDULE"][$i]['HALL']){
                                    $tbl .= '<td>'.$arResult["SHEDULE"][$i]['HALL'].', '.$arResult["SHEDULE"][$i]['TABLE'].'</td>
                                </tr>';
                                }
                                else{
                                    $tbl .= '<td> </td>
                                </tr>';
                                }
			}
			$counter++;
		}
		if ($counter == 8)
		{
			$tbl .= '<tr>
                        <td>11:55 – 12:10</td>
                        <td colspan="3" align="center">Перерыв на кофе</td>
                    </tr>';
		}
	}
	$tbl .= '</table>';
	$pdf->writeHTML($tbl, true, false, false, false, '');
	$pdf->setXY(0,$pdf->getY() + 1);
	$pdf->multiCell(210, 5, "продолжение на следующей странице", 0, C);

	$pdf->AddPage();

        $tbl = '<table cellspacing="0" cellpadding="5" border="1">
    <tr>
        <td align="center" width="70">Время</td>
        <td align="center" width="240">Участники</td>
        <td align="center" width="90">Статус</td>
        <td align="center" width="95">Зал, Стол</td>
    </tr>';
	$pdf->SetFont('times','',10);
	$pdf->SetX(20);
	for($i=10; $i<$arParams["APP_COUNT"]+1; $i++){
		if($arResult["SHEDULE"][$i]['NOTES'] == 'FREE'){
			$tbl .= '<tr>
                        <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
                        <td colspan="3" align="center">Свободно</td>
                    </tr>';
			$counter++;
		}
		elseif($arResult["SHEDULE"][$i]['NOTES'] == 'ACT'){
			$tbl .= '<tr>
                        <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
                        <td>Компания: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Представитель: '.$arResult["SHEDULE"][$i]["REP"].'</td>
                        <td align="center">Подтверждена</td>';
			if($arResult["SHEDULE"][$i]['HALL']){
				 $tbl .= '<td>'.$arResult["SHEDULE"][$i]['HALL'].', '.$arResult["SHEDULE"][$i]['TABLE'].'</td>
                        </tr>';
			}
			else{
				$tbl .= '<td> </td>
                        </tr>';
			}
			$counter++;
		}
		else{
			if($arResult["SHEDULE"][$i]['STATUS'] == 'MY'){
				$tbl .= '<tr>
                                <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
                                <td>Компания: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Представитель: '.$arResult["SHEDULE"][$i]["REP"].'</td>
                                <td align="center">От меня</td>';
				if($arResult["SHEDULE"][$i]['HALL']){
					 $tbl .= '<td>'.$arResult["SHEDULE"][$i]['HALL'].', '.$arResult["SHEDULE"][$i]['TABLE'].'</td>
                                </tr>';
				}
				else{
					$tbl .= '<td> </td>
                                </tr>';
				}
			}
			elseif($arResult["SHEDULE"][$i]['STATUS'] == 'ADM'){
				$tbl .= '<tr>
                                <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
                                <td>Компания: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Представитель: '.$arResult["SHEDULE"][$i]["REP"].'</td>
                                <td align="center">Назначено администратором</td>';
				if($arResult["SHEDULE"][$i]['HALL']){
					 $tbl .= '<td>'.$arResult["SHEDULE"][$i]['HALL'].', '.$arResult["SHEDULE"][$i]['TABLE'].'</td>
                                </tr>';
				}
				else{
					$tbl .= '<td> </td>
                                </tr>';
				}
			}
			else{
				 $tbl .= '<tr>
                                <td>'.$arResult["SHEDULE"][$i]['TITLE'].'</td>
                                <td>Компания: '.$arResult["SHEDULE"][$i]['COMPANY'].'<br />Представитель: '.$arResult["SHEDULE"][$i]["REP"].'</td>
                                <td align="center">Мне</td>';
                                if($arResult["SHEDULE"][$i]['HALL']){
                                    $tbl .= '<td>'.$arResult["SHEDULE"][$i]['HALL'].', '.$arResult["SHEDULE"][$i]['TABLE'].'</td>
                                </tr>';
                                }
                                else{
                                    $tbl .= '<td> </td>
                                </tr>';
                                }
			}
			$counter++;
		}
	}
	$tbl .= '</table>';
	$pdf->writeHTML($tbl, true, false, false, false, '');

        
	$pdf->setXY(20,$pdf->getY() + 2);
	$y = $pdf->getY();
	$html = '<b>Регистрация гостей и выдача бейджей</b> будет проходить в день мероприятия на стойке регистрации Luxury Travel Mart <b>на втором этаже отеля The Ritz-Carlton, Moscow c 09-30 до 11-30.</b>';
	$pdf->writeHTMLCell('', '', 20, $y, $html, $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true);

	$pdf->setY($pdf->getY() + 10);
	$y = $pdf->getY();
	$html = 'Пожалуйста, имейте при себе <b>достаточное количество визитных карточек на английском</b> языке.';
	$pdf->writeHTMLCell('', '', 20, $y, $html, $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true);

	$pdf->setY($pdf->getY() + 5);
	$y = $pdf->getY();
	$html = '<b>Контактные телефоны</b> для экстренной связи в день мероприятия:<br /><br /><b>Туристические компании Москвы:</b><br />+7 925 847 2602, Марат Хазиханов<br /><br /><b>Туристические компании по программе Hosted Buyer:</b><br />+7 926 346 5886, Анна Марьина';
	$pdf->writeHTMLCell('', '', 20, $y, $html, $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true);


	$pdf->Output("print.pdf", I);
?>