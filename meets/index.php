<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "�������� ������");
?>
<script src = "/meet/meet.js"></script>
<link href="/meet/meet.css" type="text/css" rel="stylesheet" />
<?
//���������� ������ ��� ������ � �������
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
		case 'process':   $arMeet[$key]['STAT_EX'] = '��������'; break;
		case 'confirmed': $arMeet[$key]['STAT_EX'] = '����������'; break;
		case 'rejected':  $arMeet[$key]['STAT_EX'] = '������'; break;
		case 'timeout':   $arMeet[$key]['STAT_EX'] = '������� ����� �������� ������'; break;
	}
	$arMeet[$key]['TIMESLOT'] = $MEET::getTimeSlotAr($val['EXHIBITION_ID'], $val['TIMESLOT_ID']);
	$arMeet[$key]['ERROR_TYPE'] = $MEET::getErrorsMeet($val['ID']);
}
echo '<table class = "meet">';
	echo '<tr>
			<td>ID �������</td>
			<td>�������� ��������</td>
			<td>�����������</td>
			<td>����������</td>
			<td>����� ������ ������?</td>
			<td>��� ������ ������?</td>
			<td>��������</td>
			<td>������</td>
			<td>��� ������</td>
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