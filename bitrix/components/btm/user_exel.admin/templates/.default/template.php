<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($arResult["ERROR_MESSAGE"] == ''){
	
	include("excelwriter.inc.php");
	$excel=new ExcelWriter("myXls.xls");
	if($excel==false)	
		echo $excel->error;
	$countPhone = 0;
	$countName = 0;
	$thisStr = array();
	$thisStr[] = "ID";
	for($i=0; $i<$arResult["FIELDS"]["COUNT"]; $i++){
		if($arResult["FIELDS"][$i]["TITLE"] == "�������"){
			if($countPhone == 0){
				$thisStr[] = "�������";
				$countPhone++;
			}
		}
		elseif($arResult["FIELDS"][$i]["TITLE"] == "���" || $arResult["FIELDS"][$i]["TITLE"] == "�������"){
			if($countName == 0){
				$thisStr[] = "�������������";
				$countName++;
			}
		}
		elseif($arResult["FIELDS"][$i]["TITLE"] != "������" && strpos($arResult["FIELDS"][$i]["TITLE"], "�������") === false && $arResult["FIELDS"][$i]["TITLE"] != "�������������� e-mail" && $arResult["FIELDS"][$i]["TITLE"] != "�������� ������������ ��������"){

			$thisStr[] = $arResult["FIELDS"][$i]["TITLE"];
		}
	}
	$thisStr[] = "������";
	
	$excel->writeLine($thisStr);
	
	for($j=0; $j<$arResult["USERS"]["COUNT"]; $j++){
		$countPhone = 0;
		$countName = 0;
		$thisStr = array();
		$phoneStr = "";
		$thisStr[] = $arResult["USERS"][$j]["ID"];
		
		for($i=0; $i<$arResult["FIELDS"]["COUNT"]; $i++){
			if($arResult["FIELDS"][$i]["TITLE"] == "�������"){
				if($countPhone == 0){
					$phoneStr .= $arResult["USERS"][$j]["FIELDS"][$i];
					$countPhone++;
				}
				elseif($countPhone==2){
					$phoneStr .= " ".$arResult["USERS"][$j]["FIELDS"][$i];
					$thisStr[] = $phoneStr;
					$countPhone++;
				}
				else{
					$phoneStr .= " (".$arResult["USERS"][$j]["FIELDS"][$i].")";
					$countPhone++;
				}
			}
			elseif($arResult["FIELDS"][$i]["TITLE"] == "���" || $arResult["FIELDS"][$i]["TITLE"] == "�������"){
				if($countName == 0){
					$phoneStr = $arResult["USERS"][$j]["FIELDS"][$i];
					$countName++;
				}
				else{
					$phoneStr .= " ".$arResult["USERS"][$j]["FIELDS"][$i];
					$thisStr[] = $phoneStr;
					$phoneStr = '';
					$countName++;
				}
			}
			elseif($arResult["FIELDS"][$i]["TITLE"] != "������" && strpos($arResult["FIELDS"][$i]["TITLE"], "�������") === false && $arResult["FIELDS"][$i]["TITLE"] != "�������������� e-mail" && $arResult["FIELDS"][$i]["TITLE"] != "�������� ������������ ��������"){
				$arResult["USERS"][$j]["FIELDS"][$i]=str_replace("�������������� ������� � �����������, �� ������� ������������� ����������","������� � �����������",$arResult["USERS"][$j]["FIELDS"][$i]);
				$arResult["USERS"][$j]["FIELDS"][$i]=str_replace("��������� �������� A","������� �",$arResult["USERS"][$j]["FIELDS"][$i]);
				$thisStr[] = $arResult["USERS"][$j]["FIELDS"][$i];
			}
		}
		$thisStr[] = $arResult["USERS"][$j]["PASS"];
		$excel->writeLine($thisStr);
	}
	$excel->close();
	echo "data is write into myXls.xls Successfully.";
	//echo "<pre>"; print_r($arResult); echo "</pre>";
	LocalRedirect("/admin/service/myXls.xls");
	//print_r($arResult);
}
else{
	echo "<p>".$arResult["ERROR_MESSAGE"]."</p>";
}
?>