<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/
//� ���������� ������� ������ �� �������� ���������������� �����
//� ���������� ������� ������ �� �������� ���������������� ����������
//� ���������� ������� ������ �� �������� ������������ (���� ����� � �� �����)
//�������� ��� ���������


$arResult["ERROR_MESSAGE"] = "";

if(strLen($arParams["PATH_TO_KAB"])<=0){
	$arParams["PATH_TO_KAB"] = "/admin/";
}

if(strLen($arParams["GROUP_ID"])<=0){
	$arParams["GROUP_ID"] = "1";
}

if(strLen($arParams["AUTH_PAGE"])<=0){
	$arParams["AUTH_PAGE"] = "/admin/login.php";
}

if(strLen($arParams["GUEST"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ������!<br />";
}

if(strLen($arParams["GUEST_HB"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ������ hosted buyers!<br />";
}

if(strLen($arParams["PARTICIP"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ����������!<br />";
}

/*
if(strLen($arParams["MESSAGE"])<=0){
	$arResult["ERROR_MESSAGE"] = "�� ������� ������ �� ����������!<br />";
}
*/

if(!($USER->IsAuthorized()))
{
	LocalRedirect($arParams["AUTH_PAGE"]);
}
elseif($arResult["ERROR_MESSAGE"] == '')
{
	if($USER->IsAdmin()){
		//�����
		$guest = array();
		$guest["LIST"] = array();
		$guest["COUNT"] = 0;
		$filter = Array(
			"GROUPS_ID"  => Array($arParams["GUEST"])
		);
		$rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter); // �������� �������������
		while($arUsersTemp=$rsUsers->Fetch()){
		  $guest["LIST"][$guest["COUNT"]]["ID"] = $arUsersTemp["ID"];
		  $guest["LIST"][$guest["COUNT"]]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
		  $guest["LIST"][$guest["COUNT"]]["REP"] = $arUsersTemp["NAME"]." ".$arUsersTemp["LAST_NAME"];
		  $guest["LIST"][$guest["COUNT"]]["EMAIL"] = $arUsersTemp["EMAIL"];
		  $guest["COUNT"]++;
		}
		
		//����� HB
		$hb = array();
		$hb["LIST"] = array();
		$hb["COUNT"] = 0;
		$filter = Array(
			"GROUPS_ID"  => Array($arParams["GUEST_HB"])
		);
		$rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter); // �������� �������������
		while($arUsersTemp=$rsUsers->Fetch()){
		  $hb["LIST"][$hb["COUNT"]]["ID"] = $arUsersTemp["ID"];
		  $hb["LIST"][$hb["COUNT"]]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
		  $hb["LIST"][$hb["COUNT"]]["REP"] = $arUsersTemp["NAME"]." ".$arUsersTemp["LAST_NAME"];
		  $hb["LIST"][$hb["COUNT"]]["EMAIL"] = $arUsersTemp["EMAIL"];
		  $hb["COUNT"]++;
		}
		
		//���������
		$particip = array();
		$particip["LIST"] = array();
		$particip["COUNT"] = 0;
		$filter = Array(
			"GROUPS_ID"  => Array($arParams["PARTICIP"])
		);
		$rsUsers = CUser::GetList(($by="WORK_COMPANY"), ($order="asc"), $filter); // �������� �������������
		while($arUsersTemp=$rsUsers->Fetch()){
		  $particip["LIST"][$particip["COUNT"]]["ID"] = $arUsersTemp["ID"];
		  $particip["LIST"][$particip["COUNT"]]["COMPANY"] = $arUsersTemp["WORK_COMPANY"];
		  $particip["LIST"][$particip["COUNT"]]["REP"] = $arUsersTemp["NAME"]." ".$arUsersTemp["LAST_NAME"];
		  $particip["LIST"][$particip["COUNT"]]["EMAIL"] = $arUsersTemp["EMAIL"];
		  $particip["COUNT"]++;
		}
	
		$arResult["GUEST"] = $guest;
		$arResult["HB"] = $hb;
		$arResult["PARTICIP"] = $particip;
		$arResult["MESS"]["SUBJ"] = $_REQUEST["subj"];
		$arResult["MESS"]["TEXT"] = $_REQUEST["message_text"];
		
		if((isset($_POST['mes'])) and ($_POST['mes'] == 'write')){
			$arResult["MESSAGE"] = '';
			if(!(isset($_REQUEST["subj"])) || $_REQUEST["subj"] == ''){
				$arResult["MESSAGE"] = "�� �� ����� ����<br />";
			}
			if(!(isset($_REQUEST["message_text"])) || $_REQUEST["message_text"] == ''){
				$arResult["MESSAGE"] .= "�� �� ����� ����� ���������";
			}
			if($arResult["MESSAGE"] == ''){
				$arFields = Array(
					"AUTHOR_ID"    => 1,
					"POST_SUBJ"    => $_REQUEST["subj"],   
					"POST_MESSAGE" => $_REQUEST["message_text"],
					"USER_ID"      => "",
					"COPY_TO_OUTBOX" => ""
				);
				if((isset($_POST['all_guest']) and $_POST['all_guest'] != '') || (isset($_POST['all_hb']) and $_POST['all_hb'] != '') || (isset($_POST['all_particip']) and $_POST['all_particip'] != '')){
					$ID = 0;
					if(isset($_POST['all_guest']) and $_POST['all_guest'] != ''){
						for($i=0; $i<$arResult["GUEST"]["COUNT"]; $i++){
							$arFields["USER_ID"] = $arResult["GUEST"]["LIST"][$i]['ID'];
							$ID = CForumPrivateMessage::Send($arFields);
							if (IntVal($ID)<=0){
							 $arResult["MESSAGE"] .= "�� ����������� ��������� ������������ ".$arResult["GUEST"]["LIST"][$i]['ID']."<br />";			
							}
							else{
							  $arFieldsMes = array();
							  $arFieldsMes["EMAIL"] = $arResult["GUEST"]["LIST"][$i]['EMAIL'];
							  CEvent::Send("NEW_ADMIN_MESSAGE", "s1", $arFieldsMes );
							}
						}
					}
					if(isset($_POST['all_hb']) and $_POST['all_hb'] != ''){
						for($i=0; $i<$arResult["HB"]["COUNT"]; $i++){
							$arFields["USER_ID"] = $arResult["HB"]["LIST"][$i]['ID'];
							$ID = CForumPrivateMessage::Send($arFields);
							if (IntVal($ID)<=0){
							 $arResult["MESSAGE"] .= "�� ����������� ��������� ������������ ".$arResult["HB"]["LIST"][$i]['ID']."<br />";				
							}
							else{
							  $arFieldsMes = array();
							  $arFieldsMes["EMAIL"] = $arResult["HB"]["LIST"][$i]['EMAIL'];
							  CEvent::Send("NEW_ADMIN_MESSAGE", "s1", $arFieldsMes );							
							}
						}
					}
					if(isset($_POST['all_particip']) and $_POST['all_particip'] != ''){
						for($i=0; $i<$arResult["PARTICIP"]["COUNT"]; $i++){
							$arFields["USER_ID"] = $arResult["PARTICIP"]["LIST"][$i]['ID'];
							$ID = CForumPrivateMessage::Send($arFields);
							if (IntVal($ID)<=0){
							 $arResult["MESSAGE"] .= "�� ����������� ��������� ������������ ".$arResult["PARTICIP"]["LIST"][$i]['ID']."<br />";			
							}
							else{
							  $arFieldsMes = array();
							  $fieldEm = "email".$reciverIDS;
							  $arFieldsMes["EMAIL"] = $_POST[fieldEm];
							  CEvent::Send("NEW_ADMIN_MESSAGE", "s1", $arFieldsMes );							
							}
						}
					}
					if($arResult["MESSAGE"] == ''){
						$arResult["MESSAGE"] = "��� ���� ��������� ������� ����������.";
					}
				}
				elseif(isset($_POST['guests']) || isset($_POST['hb']) || isset($_POST['particip'])){
					if(isset($_POST['guests'])){
						foreach($_POST['guests'] as $reciverIDS){
							$arFields["USER_ID"] = $reciverIDS;
							$arFields["COPY_TO_OUTBOX"] = "Y";
							$ID = CForumPrivateMessage::Send($arFields);
							if (IntVal($ID)<=0){
							 $arResult["MESSAGE"] .= "�� ����������� ��������� ������������ ".$reciverIDS."<br />";				
							}
							else{
							  $arFieldsMes = array();
							  $fieldEm = "email".$reciverIDS;
							  $arFieldsMes["EMAIL"] = $_POST[fieldEm];
							  CEvent::Send("NEW_ADMIN_MESSAGE", "s1", $arFieldsMes );
							}
						}						
					}
					if(isset($_POST['hb'])){
						foreach($_POST['hb'] as $reciverIDS){
							$arFields["USER_ID"] = $reciverIDS;
							$arFields["COPY_TO_OUTBOX"] = "Y";
							$ID = CForumPrivateMessage::Send($arFields);
							if (IntVal($ID)<=0){
							 $arResult["MESSAGE"] .= "�� ����������� ��������� ������������ ".$reciverIDS."<br />";				
							}
							else{
							  $arFieldsMes = array();
							  $arFieldsMes["EMAIL"] = $_POST["email".$reciverID];
							  CEvent::Send("NEW_ADMIN_MESSAGE", "s1", $arFieldsMes );							
							}
						}						
					}
					if(isset($_POST['particip'])){
						foreach($_POST['particip'] as $reciverIDS){
							$arFields["USER_ID"] = $reciverIDS;
							$arFields["COPY_TO_OUTBOX"] = "Y";
							$ID = CForumPrivateMessage::Send($arFields);
							if (IntVal($ID)<=0){
							 $arResult["MESSAGE"] .= "�� ����������� ��������� ������������ ".$reciverIDS."<br />";				
							}
							else{
							  $arFieldsMes = array();
							  $arFieldsMes["EMAIL"] = $_POST["email".$reciverID];
							  CEvent::Send("NEW_ADMIN_MESSAGE", "s1", $arFieldsMes );							
							}
						}						
					}
					if($arResult["MESSAGE"] == ''){
						$arResult["MESSAGE"] = "��� ���� ��������� ������� ����������.";
					}
				}
				else{
					$arResult["MESSAGE"] = "�� �� ������� �������� ��������.";
				}
			}
		 
		}
		
	}
	else{
		$arResult["ERROR_MESSAGE"] = "� ��� ������������ ���� ��� ��������� ������ ��������!";
	}
}

//echo "<pre>"; print_r($arResult); echo "</pre>";

$this->IncludeComponentTemplate();
?>