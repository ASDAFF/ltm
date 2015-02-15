<?
//����� ��� ������ ����������� � ���. ������������

class LuxorConfig{
	/////// ������ ������������� ��������� ////////
	
	public static  $GROUP_E_AR           =  Array(9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 20, 21);
	
	/////// ID ���������� �������� �������� (����) ////////
	
	const GROUP_NAME_FORM      =  'SIMPLE_QUESTION_106';
	
	/////// ID ����� ���������� ////////
	
	const ID_E_FORM            =  3;
	
	//"��������� ������������� ������ �����"
	const ID_E_MOSC_SP         =  4;
	//"��������� ������������� ����"
	const ID_E_BAK             =  5;
	//"��������� ������������� ����"
	const ID_E_KIEV            =  6;
	//"��������� ������������� ������"
	const ID_E_ALM             =  7;
	//"��������� ������������� ������ �����"
	const ID_E_MOSC_OT         =  8;
	//"��������� ������������� ������ ����� - 2015"
	const ID_E_MOSC_SP15       =  25;
	//"��������� ������������ ���� 2015"
	const ID_E_KIEV15          =  27;
	//"��������� ������������� ������ 2015"
	const ID_E_ALM15           =  26;
	//"��������� ������������� ������ ����� 2015"
	const ID_E_MOSC_OT15       =  28;
	
	/////// �������� ////////
	
	const PAGE_MEET            = 'meet';
	
	/////// ������ ������������� ////////
	
	//��������� ������ ����� ��������������
	const GROUP_USER_M_SP_P    = 10;
	//��������� ���� ��������������
	const GROUP_USER_BAKU_P    = 12;
	
	//��������� ���� �������������
	const GROUP_USER_KIEV_P = 14;
	//��������� ���� �������������
	const GROUP_USER_KIEV_NP = 13;
	
	//��������� ����-��� �������������
	const GROUP_USER_ALM_P = 16;
	//��������� ����-��� �������������
	const GROUP_USER_ALM_NP = 15;
	
	//��������� ������ ����� �������������
	const GROUP_USER_MO_P = 18;
	//��������� ������ ����� �������������
	const GROUP_USER_MO_NP = 17;
	
	//��������� ������ ����� 2015 �������������
	const GROUP_USER_MS15_P = 21;
	//��������� ������ ����� 2015 �������������
	const GROUP_USER_MS15_NP = 20;
	
	//����� ������ ����� �� ��������������
	const GROUP_GUEST_INACT     = 19; 
	//����� ������ ����� ��������������
	const GROUP_GUEST_M_SP     = 22; 
	//����� ����
	const GROUP_GUEST_B        = 25;
	//����� ����
	const GROUP_GUEST_K        = 23;
	//����� ������
	const GROUP_GUEST_A        = 26;

	//����� ������ �����
	const GROUP_GUEST_MO        = 24;
	
	//������� � ���� ������
	const MEET_REQ             = 'meetings_requests';
	const MEET_SET             = 'meetings_settings';
	const MEET_TIME            = 'meetings_timeslots';
	
	//��������� ������ ������
	const ERROR_OTP_SEND_MES         = '������� ������ ��� ����� � ������� ����������';
	const ERROR_POL_SEND_MES         = '������� ������������ ��� ��� ����� ��������� ������ � ������� ����������';
	const ERROR_USER_NOT_FOUND       = '�� ���������� ������������ � ����� ID, �������� ��������� ������';
	const ERROR_USER_IS_GUEST        = '��� ������������ �������� �������';
	const ERROR_NO_MEET              = '������ �� �������';
	
	//������� ������
	//��������
	const M_OTM                      = 'rejected';
	//������� 
	const M_TIME                     = 'timeout';
	
	/**
	 * ���������� �������������� ������. 
	 * ������ ��������� ������� ������ � ���������� ��������� ������� � ������.
	 * 
	 */
	function getPas($str, $passw=""){
		$salt = "Dn8*#2n!9j";
		$len = strlen($str);
		$gamma = '';
		$n = $len>100 ? 8 : 2;
		while( strlen($gamma)<$len ){
			$gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
		}
		return $str^$gamma;
	}
	function returnPas($pas){
		return LuxorConfig::getPas(base64_decode($pas), 'luxoran');
	}
	
	/**
	*�������� �� �������� � ��������� ������ ��� ���?
	���� �� - ���������� true, � ��������� ������ - false
	*/
	function isMeetPage(){
		global $APPLICATION;
		$dirName = $APPLICATION->GetCurDir();
		$dirAr = explode('/', $dirName);
		if(!is_array($dirAr))
			return false;
		if($dirAr[1] == self::PAGE_MEET){
			return true;
		}else{
			return false;
		}
	}
	
	function getAnswerFormSimple($WEB_FORM_ID, &$arrAnswersSID, $arFilter=Array()){
		global $DB, $strError;
		$WEB_FORM_ID = intval($WEB_FORM_ID);
		$arSqlSearch = Array();
		$strSqlSearch = "";
		if (is_array($arFilter))
		{
			if (strlen($arFilter["FIELD_SID"])>0) $arFilter["FIELD_VARNAME"] = $arFilter["FIELD_SID"];
			elseif (strlen($arFilter["FIELD_VARNAME"])>0) $arFilter["FIELD_SID"] = $arFilter["FIELD_VARNAME"];

			$filter_keys = array_keys($arFilter);
			for ($i=0; $i<count($filter_keys); $i++)
			{
				$key = $filter_keys[$i];
				$val = $arFilter[$filter_keys[$i]];
				if (strlen($val)<=0 || "$val"=="NOT_REF") continue;
				if (is_array($val) && count($val)<=0) continue;
				$match_value_set = (in_array($key."_EXACT_MATCH", $filter_keys)) ? true : false;
				$key = strtoupper($key);
				switch($key)
				{
					case "FIELD_ID":
					case "RESULT_ID":
						$match = ($arFilter[$key."_EXACT_MATCH"]=="N" && $match_value_set) ? "Y" : "N";
						$arSqlSearch[] = GetFilterQuery("RA.".$key, $val, $match);
						break;
					case "FIELD_SID":
						$match = ($arFilter[$key."_EXACT_MATCH"]=="Y" && $match_value_set) ? "N" : "Y";
						$arSqlSearch[] = GetFilterQuery("F.SID", $val, $match);
						break;
					case "IN_RESULTS_TABLE":
					case "IN_EXCEL_TABLE":
						$arSqlSearch[] = ($val=="Y") ? "F.".$key."='Y'" : "F.".$key."='N'";
						break;
				}
			}
		}
		$strSqlSearch = GetFilterSqlSearch($arSqlSearch);
		$strSql = "
			SELECT
				RA.RESULT_ID, RA.FIELD_ID, F.SID,
				RA.ANSWER_ID, RA.ANSWER_TEXT, RA.USER_TEXT
			FROM
				b_form_result_answer RA
			INNER JOIN b_form_field F ON (F.ID = RA.FIELD_ID and F.ACTIVE='Y')
			LEFT JOIN b_form_answer A ON (A.ID = RA.ANSWER_ID)
			WHERE
			$strSqlSearch
			and RA.FORM_ID = $WEB_FORM_ID
			ORDER BY RA.RESULT_ID
			";
		//echo "<pre>".$strSql."</pre>";
		$z = $DB->Query($strSql, false, __LINE__);
		while ($zr = $z->Fetch())
		{
			$arrAnswersSID[$zr["RESULT_ID"]][$zr["SID"]][]=$zr;
		}
	}
	
	
	/*=== ��������� ������ ��� �� ===*/
	/**
	* ����� ������� ��� ������� �� ��������
	*/
	function getAllMeet(){
		global $DB;
		$arName = array();
		$sSQL = 'SELECT * FROM '.self::MEET_REQ;
		$res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($data = $res->Fetch()){
			$meetsInfo[] = $data;
		}
		return $meetsInfo;
	}
	
	/**
	* �������� ������ ��������
	*/
	function getMeetThis(){
		global $DB;
		$arName = array();
		$sSQL = 'SELECT * FROM '.self::MEET_REQ;
		$res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($data = $res->Fetch()){
			$meetsInfo[] = $data;
		}
		foreach($meetsInfo as $k=>$v){
			if(!in_array($v['EXHIBITION_ID'], $arName)){
				$arName[] = $v['EXHIBITION_ID'];
				$meetsMod[$k]['NAME_E'] = $v['EXHIBITION_ID'];
			}		
		}
		return $meetsMod;
	}
	
	/**
	*����� ������� �������� �������� � �������
	*/
	function getNameEx($id){
		global $DB;
		if ($id < 1)
			return false;
		$sSQL = 'SELECT NAME, ID FROM '.self::MEET_SET. ' WHERE id = '.$id;
		$res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($data = $res->Fetch()){
			$namesEx = $data['NAME'];
		}
		return $namesEx;
	}
	
	/**
	*����� ������� �������� � ������� �� id ������� � id ������ ��������� � ��
	*/
	function getTimeSlotAr($id_ex, $id){
		global $DB;
		if ($id_ex < 1 || $id < 1)
			return false;
		$sSQL = 'SELECT NAME, EXHIBITION_ID, ID FROM '.self::MEET_TIME. ' WHERE EXHIBITION_ID = '.$id_ex. ' AND ID = '.$id;
		$res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($data = $res->Fetch()){
			$timeS = $data['NAME'];
		}
		return $timeS;
	}
	
	
	/*
	const ERROR_OTP_SEND_MES         = '������� ������ ��� ����� � ������� ����������';
	const ERROR_POL_SEND_MES         = '������� ������������ ��� ��� ����� ��������� ������ � ������� ����������';
	const ERROR_USER_NOT_FOUND       = '�� ���������� ������������ � ����� ID, �������� ��������� ������';
	const ERROR_USER_IS_GUEST        = '��� ������������ �������� �������';
	*/
	/**
	*����� ������� ������ ���� ERROR_OTP_SEND_MES
	*/
	function getErrorsOtpSend(){
		global $DB;
		//������ �� ������ ERROR_OTP_SEND_MES
		$sSQL = 'SELECT ID, SENDER_ID, TIMESLOT_ID, STATUS FROM '.self::MEET_REQ.' GROUP BY SENDER_ID, TIMESLOT_ID HAVING COUNT(*)>1 ORDER BY ID';
		$res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($data = $res->Fetch()){
			if($data['STATUS'] != self::M_OTM && $data['STATUS'] != self::M_TIME){
				$arId[] = $data['ID'];
			}
		}
		if(!empty($arId)){
			return $arId;
		}else{
			return false;
		}
	}
	/**
	*����� ������� ������ ���� ERROR_POL_SEND_MES
	*/
	function getErrorsPolSend(){
		global $DB;
		//������ �� ������ ERROR_POL_SEND_MES
		$sSQL = 'SELECT ID, RECEIVER_ID, TIMESLOT_ID, STATUS FROM '.self::MEET_REQ.' GROUP BY RECEIVER_ID, TIMESLOT_ID HAVING COUNT(*)>1 ORDER BY ID';
		$res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($data = $res->Fetch()){
			if($data['STATUS'] != self::M_OTM && $data['STATUS'] != self::M_TIME){
				$arId[] = $data['ID'];
			}
		}
		if(!empty($arId)){
			return $arId;
		}else{
			return false;
		}
	}
	/**
	*����� ������� ������ ���� ERROR_USER_NOT_FOUND
	*/
	function getErrorsUserNotFound(){
		global $DB;
		//������ �� ������ ERROR_USER_NOT_FOUND
		$sSQL = 'SELECT ID, RECEIVER_ID, STATUS FROM '.self::MEET_REQ.' ORDER BY ID';
		$res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($data = $res->Fetch()){
			if(!self::getUserLoginSimple($data['RECEIVER_ID'])){
				if($data['STATUS'] != self::M_OTM && $data['STATUS'] != self::M_TIME){
					$arId[$data['ID']] = $data['RECEIVER_ID'];
				}
			}
		}
		if(!empty($arId)){
			return $arId;
		}else{
			return false;
		}
	}
	/**
	*����� ������� ������ ���� ERROR_USER_IS_GUEST
	*/
	function getErrorsBouthGuest(){
		global $DB;
		//������ �� ������ ERROR_USER_IS_GUEST
		$sSQL = 'SELECT ID, SENDER_ID, RECEIVER_ID, STATUS FROM '.self::MEET_REQ.' ORDER BY ID';
		$res = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($data = $res->Fetch()){
			if(in_array(self::getUserGroubSimple($data['SENDER_ID']), array(19, 22, 23, 24, 25, 26, 27)) && in_array(self::getUserGroubSimple($data['RECEIVER_ID']), array(19, 22, 23, 24, 25, 26, 27))){
				if($data['STATUS'] != self::M_OTM && $data['STATUS'] != self::M_TIME){
					$arId[] = $data['ID'];
				}
			}
		}
		if(!empty($arId)){
			return $arId;
		}else{
			return false;
		}
	}
	
	
	
	
	/**
	//�������� id ������ �� id ������������ (����� �������� ��� �� �������)
	*/
	function getUserGroubSimple($user_id){
		global $DB;
		if ($user_id < 1)
			return false;
		$sSQL = 'SELECT GROUP_ID FROM b_user_group WHERE USER_ID = '.$user_id;
		$resSub = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($dataSub = $resSub->Fetch()){
			$userGr = $dataSub['GROUP_ID'];
		}
		if($userGr != ''){
			return $userGr;
		}else{
			return false;
		}
	}
	/**
	//�������� ������ ������������ �� id
	*/
	function getUserLoginSimple($user_id){
		global $DB;
		if ($user_id < 1)
			return false;
		$sSQL = 'SELECT LOGIN FROM b_user WHERE ID = '.$user_id;
		$resSub = $DB->Query($sSQL, false, 'FILE: '.__FILE__.'<br />LINE: ' . __LINE__);
		while($dataSub = $resSub->Fetch()){
			$userLogin = $dataSub['LOGIN'];
		}
		if($userLogin != ''){
			return $userLogin;
		}else{
			return false;
		}
	}
	
}

?>