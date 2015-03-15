<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $USER;

$arResult = array();
$arResult["ERROR_MESSAGE"] = array();

if(!($USER->IsAuthorized()))
{
    $arResult["ERROR_MESSAGE"][] = "�� �� ������������!<br />";
}

if(!($USER->IsAdmin()))
{
    $arResult["ERROR_MESSAGE"][] = "�� �� �������������!<br />";
}

if(!CModule::IncludeModule("iblock"))
{
    $arResult["ERROR_MESSAGE"][] = "������ ����������� �������!<br />";
}

if(isset($_REQUEST["upload"])){

    $arResult["COUNT_ALL"] = $arResult["COUNT_SUCCESS"] = 0;

    if(isset($_REQUEST["sect"]) && $_REQUEST["sect"] != 0){
        $sectionID = $_REQUEST["sect"];
        $el = new CIBlockElement;

        foreach($_FILES["file"]["name"] as $fileNumber => $fileName){
            $arResult["COUNT_ALL"]++;
            if($_FILES["file"]["error"][$fileNumber]){
                $arResult["ERROR_MESSAGE"][] = $_FILES["file"]["error"][$fileNumber];
            }
            elseif($_FILES["file"]["type"][$fileNumber] != 'image/jpeg'){
                $arResult["ERROR_MESSAGE"][] = "�� ������ ��� ����� ".$fileName;
            }
            else{
                $fileArr = array(
                    "name" => $fileName,
                    "type" => $_FILES["file"]["type"][$fileNumber],
                    "tmp_name" => $_FILES["file"]["tmp_name"][$fileNumber],
                    "error" => $_FILES["file"]["error"][$fileNumber],
                    "size" => $_FILES["file"]["size"][$fileNumber],
                );

                $arLoadProductArray = Array(
                    "MODIFIED_BY"    => $USER->GetID(), // ������� ������� ������� �������������
                    "IBLOCK_SECTION_ID" => $sectionID,          // ������� ����� � ����� �������
                    "IBLOCK_ID"      => $arParams["IBLOCK_ID"],
                    "NAME"           => $fileName,
                    "DETAIL_PICTURE" => $fileArr
                );

                if($PRODUCT_ID = $el->Add($arLoadProductArray, false, false, true)){
                    $arResult["COUNT_SUCCESS"] ++;
                }
                else{
                    $arResult["ERROR_MESSAGE"][] = $el->LAST_ERROR;
                }
            }
        }
    }
    else{
        $arResult["ERROR_MESSAGE"][] = "�� �� ������� ������";
    }
}


//��������� ��������
$arFilter = array(
	"IBLOCK_ID" => $arParams["IBLOCK_ID"]
);

$arSelect = array(
	"ID",
    "NAME",
    "IBLOCK_ID",
    "CODE",
    "ELEMENT_CNT"
);

$db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true, $arSelect);
while($ar_result = $db_list->GetNext())
{
    $arResult["SECT"][$ar_result["ID"]] = $ar_result;
}


//����� �������
$this->IncludeComponentTemplate();