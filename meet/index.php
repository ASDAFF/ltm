<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "�������� ������");
?>
<script src = "/meet/meet.js"></script>
<link href="/meet/meet.css" type="text/css" rel="stylesheet" />
<?
$arMeet = array();
foreach(LuxorConfig::getAllMeet() as $key=>$val){
	$arMeet[$key]['ID'] = $val['ID'];
	$arMeet[$key]['CREATED_AT'] = $val['CREATED_AT'];
	$arMeet[$key]['USER_OTP']  = LuxorConfig::getUserLoginSimple($val['SENDER_ID']);
	$arMeet[$key]['USER_POL']  = LuxorConfig::getUserLoginSimple($val['RECEIVER_ID']);
	$arMeet[$key]['USER_MAKE'] = LuxorConfig::getUserLoginSimple($val['MODIFIED_BY']);
	switch($val['STATUS']){
		case 'process':   $arMeet[$key]['STAT_EX'] = '��������'; break;
		case 'confirmed': $arMeet[$key]['STAT_EX'] = '����������'; break;
		case 'rejected':  $arMeet[$key]['STAT_EX'] = '������'; break;
		case 'timeout':   $arMeet[$key]['STAT_EX'] = '������� ����� �������� ������'; break;
	}
	$arMeet[$key]['TIMESLOT'] = LuxorConfig::getTimeSlotAr($val['EXHIBITION_ID'], $val['TIMESLOT_ID']);
}
$arErOtpSend      = LuxorConfig::getErrorsOtpSend();
$arErPolSend      = LuxorConfig::getErrorsPolSend();
$arErUserNotFound = LuxorConfig::getErrorsUserNotFound();
$arErBouthGuest   = LuxorConfig::getErrorsBouthGuest();
foreach(LuxorConfig::getMeetThis() as $k=>$m){
	echo '<h2>�������� "'.LuxorConfig::getNameEx($m['NAME_E']).'"</h2>';
	echo '<table class = "meet">';
		echo '<tr class = "first_meet_cl">
				<td>ID �������</td>
				<td>�����������</td>
				<td>����������</td>
				<td>����� ������ ������?</td>
				<td>��� ������ ������?</td>
				<td>��������</td>
				<td>������</td>
				<td>��� ������</td>
			</tr>';
	foreach($arMeet as $val){
		if(in_array($val['ID'], $arErOtpSend)){
			echo '<tr>';
				echo '<td>'.$val['ID'].'</td>';
				echo '<td>'.$val['USER_OTP'].'</td>';
				echo '<td>'.$val['USER_POL'].'</td>';
				echo '<td>'.$val['CREATED_AT'].'</td>';
				echo '<td>'.$val['USER_MAKE'].'</td>';
				echo '<td>'.$val['TIMESLOT'].'</td>';
				echo '<td>'.$val['STAT_EX'].'</td>';
				echo '<td>'.LuxorConfig::ERROR_OTP_SEND_MES.'</td>';
			echo '</tr>';
		}
		if(in_array($val['ID'], $arErPolSend)){
			echo '<tr>';
				echo '<td>'.$val['ID'].'</td>';
				echo '<td>'.$val['USER_OTP'].'</td>';
				echo '<td>'.$val['USER_POL'].'</td>';
				echo '<td>'.$val['CREATED_AT'].'</td>';
				echo '<td>'.$val['USER_MAKE'].'</td>';
				echo '<td>'.$val['TIMESLOT'].'</td>';
				echo '<td>'.$val['STAT_EX'].'</td>';
				echo '<td>'.LuxorConfig::ERROR_POL_SEND_MES.'</td>';
			echo '</tr>';
		}
		if(array_key_exists($val['ID'], $arErUserNotFound)){
			echo '<tr>';
				echo '<td>'.$val['ID'].'</td>';
				echo '<td>'.$val['USER_OTP'].'</td>';
				echo '<td>'.$val['USER_POL'].'</td>';
				echo '<td>'.$val['CREATED_AT'].'</td>';
				echo '<td>'.$val['USER_MAKE'].'</td>';
				echo '<td>'.$val['TIMESLOT'].'</td>';
				echo '<td>'.$val['STAT_EX'].'</td>';
				echo '<td>'.LuxorConfig::ERROR_USER_NOT_FOUND.'<br>ID<sub>user</sub> = '.$arErUserNotFound[$val['ID']].'</td>';
			echo '</tr>';
		}
		if(in_array($val['ID'], $arErBouthGuest)){
			echo '<tr>';
				echo '<td>'.$val['ID'].'</td>';
				echo '<td>'.$val['USER_OTP'].'</td>';
				echo '<td>'.$val['USER_POL'].'</td>';
				echo '<td>'.$val['CREATED_AT'].'</td>';
				echo '<td>'.$val['USER_MAKE'].'</td>';
				echo '<td>'.$val['TIMESLOT'].'</td>';
				echo '<td>'.$val['STAT_EX'].'</td>';
				echo '<td>'.LuxorConfig::ERROR_USER_IS_GUEST.'</td>';
			echo '</tr>';
		}
	}
	echo '</table>';
}
?> 
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>