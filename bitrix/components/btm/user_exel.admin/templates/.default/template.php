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
		if($arResult["FIELDS"][$i]["TITLE"] == "Телефон"){
			if($countPhone == 0){
				$thisStr[] = "Телефон";
				$countPhone++;
			}
		}
		elseif($arResult["FIELDS"][$i]["TITLE"] == "Имя" || $arResult["FIELDS"][$i]["TITLE"] == "Фамилия"){
			if($countName == 0){
				$thisStr[] = "Представитель";
				$countName++;
			}
		}
		elseif($arResult["FIELDS"][$i]["TITLE"] != "Пароль" && strpos($arResult["FIELDS"][$i]["TITLE"], "коллеги") === false && $arResult["FIELDS"][$i]["TITLE"] != "Альтернативный e-mail" && $arResult["FIELDS"][$i]["TITLE"] != "Описание деятельности компании"){

			$thisStr[] = $arResult["FIELDS"][$i]["TITLE"];
		}
	}
	$thisStr[] = "Пароль";
	
	$excel->writeLine($thisStr);
	
	for($j=0; $j<$arResult["USERS"]["COUNT"]; $j++){
		$countPhone = 0;
		$countName = 0;
		$thisStr = array();
		$phoneStr = "";
		$thisStr[] = $arResult["USERS"][$j]["ID"];
		
		for($i=0; $i<$arResult["FIELDS"]["COUNT"]; $i++){
			if($arResult["FIELDS"][$i]["TITLE"] == "Телефон"){
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
			elseif($arResult["FIELDS"][$i]["TITLE"] == "Имя" || $arResult["FIELDS"][$i]["TITLE"] == "Фамилия"){
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
			elseif($arResult["FIELDS"][$i]["TITLE"] != "Пароль" && strpos($arResult["FIELDS"][$i]["TITLE"], "коллеги") === false && $arResult["FIELDS"][$i]["TITLE"] != "Альтернативный e-mail" && $arResult["FIELDS"][$i]["TITLE"] != "Описание деятельности компании"){
				$arResult["USERS"][$j]["FIELDS"][$i]=str_replace("Индивидуальные встречи с участниками, по заранее составленному расписанию","Встречи с участниками",$arResult["USERS"][$j]["FIELDS"][$i]);
				$arResult["USERS"][$j]["FIELDS"][$i]=str_replace("Посещение семинара A","Семинар А",$arResult["USERS"][$j]["FIELDS"][$i]);
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