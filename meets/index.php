<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Проверка встреч");
?>
<script src = "/meet/meet.js"></script>
<link href="/meet/meet.css" type="text/css" rel="stylesheet" />
<?
//подключаем классы для работы с модулем
CModule::IncludeModule("doka.meetings");
$MEET = new Doka\Meetings\Requests;
$arMeet = array();
foreach($MEET::getAllMeet() as $key=>$val){
	$rsUser = CUser::GetByID($val['SENDER_ID']);
	$arUser = $rsUser->Fetch();
	$rsUserP = CUser::GetByID($val['RECEIVER_ID']);
	$arUserP = $rsUserP->Fetch();
	$rsUserM = CUser::GetByID($val['MODIFIED_BY']);
	$arUserM = $rsUserM->Fetch();
	$arMeet[$key] = $val;
	$arMeet[$key]['USER_OTP'] = $arUser['LOGIN'];
	$arMeet[$key]['USER_POL'] = $arUserP['LOGIN'];
	$arMeet[$key]['USER_MAKE'] = $arUserM['LOGIN'];
	$arMeet[$key]['NAME_EX'] = $MEET::getNameEx($val['EXHIBITION_ID']);
	switch($val['STATUS']){
		case 'process':   $arMeet[$key]['STAT_EX'] = 'Ожидание'; break;
		case 'confirmed': $arMeet[$key]['STAT_EX'] = 'Подтверждён'; break;
		case 'rejected':  $arMeet[$key]['STAT_EX'] = 'Отменён'; break;
		case 'timeout':   $arMeet[$key]['STAT_EX'] = 'Истекло время ожидания ответа'; break;
	}
	$arMeet[$key]['TIMESLOT'] = $MEET::getTimeSlotAr($val['EXHIBITION_ID'], $val['TIMESLOT_ID']);
	$arMeet[$key]['ERROR_TYPE'] = $MEET::getErrorsMeet($val['ID']);
}
echo '<table class = "meet">';
	echo '<tr>
			<td>ID встречи</td>
			<td>Название выставки</td>
			<td>Отправитель</td>
			<td>Получатель</td>
			<td>Когда сделан запрос?</td>
			<td>Кем создан запрос?</td>
			<td>Таймслот</td>
			<td>Статус</td>
			<td>Тип ошибки</td>
		</tr>';
foreach($arMeet as $val){
	echo '<tr>';
		echo '<td>'.$val['ID'].'</td>';
		echo '<td>'.$val['NAME_EX'].'</td>';
		echo '<td>'.$val['USER_OTP'].'</td>';
		echo '<td>'.$val['USER_POL'].'</td>';
		echo '<td>'.$val['CREATED_AT'].'</td>';
		echo '<td>'.$val['USER_MAKE'].'</td>';
		echo '<td>'.$val['TIMESLOT'].'</td>';
		echo '<td>'.$val['STAT_EX'].'</td>';
		echo '<td>'.$val['ERROR_TYPE'].'</td>';
	echo '</tr>';
}
echo '</table>';
?> 

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>