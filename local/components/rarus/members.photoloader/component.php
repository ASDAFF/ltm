<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $DB;
global $USER;
global $APPLICATION;

if(!isset($arParams["CACHE_TIME"]))
  	$arParams["CACHE_TIME"] = 3600;

$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
if(!isset($arParams["IBLOCK_ID"]))
    $arParams["IBLOCK_ID"] = 16;

$arParams["MAX_PHOTO_COUNT"] = intval($arParams["MAX_PHOTO_COUNT"]);
if(!isset($arParams["MAX_PHOTO_COUNT"]))
    $arParams["IBLOCK_ID"] = 12;

$arParams["GALLERY_ID"] = intval($arParams["GALLERY_ID"]);
if(!isset($arParams["GALLERY_ID"]))
	$arParams["GALLERY_ID"] = 16;

$arParams["USER_ID"] = intval($arParams["USER_ID"]);
if(!isset($arParams["USER_ID"]))
	$arParams["USER_ID"] = $USER->GetID();

$arParams['MAX_FILE_SIZE'] = intval($arParams['MAX_FILE_SIZE']);

$arResult = array();

#загрузка файлов
if(isset($_POST["ajax_load_file"]))
{
	$APPLICATION->RestartBuffer();

	header("Content-Type: application/json");


	$mid = "iblock";

	if($_FILES["photo-file"])
	{
		$arFile = $_FILES["photo-file"];
		$arFile["name"] = CUtil::ConvertToLangCharset($arFile["name"]);
		$arFile["MODULE_ID"] = $mid;

		$res = CFile::CheckImageFile($arFile, $arParams['MAX_FILE_SIZE'], 0, 0);

		if (strlen($res) <= 0)
		{
			$fileID = CFile::SaveFile($arFile, $mid);

			//Уменьшаем для превьюшки
			$arFile = CFile::ResizeImageGet($fileID, array("width" => 98, "height" => 75), BX_RESIZE_IMAGE_EXACT, true);
			$arFile = array_change_key_case($arFile, CASE_UPPER);
			$arFile["ID"] = $fileID;
		}

	}
	echo json_encode($arFile);
die();
}


if(isset($_POST["PHOTO"]))
{
	$obIBElement = new CIBlockElement();
	foreach($_POST["PHOTO"] as $index => $sort)
	{
		//если обновляем старый элемент
		if(intval($index))
		{
			if("DELETE" == $sort)
			{
				$obIBElement->Delete($index);
			}
			else
			{
				$obIBElement->Update($index, array("SORT" => $sort, "NAME" => "Фото {$sort}"));
			}

		}
		else
		{
			$fileID = str_ireplace("new:", "", $index);
			if(intval($fileID))
			{
				$arFields = array(
					'IBLOCK_ID' => $arParams["IBLOCK_ID"],
					"ACTIVE" => "Y",
					"NAME" => "Фото {$sort}",
					"SORT" => $sort,
					"IBLOCK_SECTION_ID" => $arParams["GALLERY_ID"],
					"PREVIEW_PICTURE" => CFile::MakeFileArray($fileID)
				);

				if($obIBElement->Add($arFields,false, false, true))
				{
					//удаляем старый файл, чтоб не плодить место
					CFile::Delete($fileID);
				}
			}
		}
	}
}


#if($this->StartResultCache(false, array_merge($arParams, $arResult)))
#{
	if(!CModule::IncludeModule("iblock")) {
		$this->AbortResultCache();
		throw new Exception("Can't load modules iblock form");
	}

	//получаем данные пользователя
	$arUserFilter = array(
		"ID" => $arParams["USER_ID"]
	);
	$arUserSelect = array(
		"FIELDS" => array("ID", "LOGIN", "EMAIL", "PERSONAL_COMPANY"),
		"SELECT" => array("UF_*")
	);
	$rsUser = $USER->GetList($by = "id", $order = "asc", $arUserFilter, $arUserSelect);
	if($arUser = $rsUser->Fetch())
	{
		$arResult["USER"] = $arUser;
	}

	//Получаем данные фото
	$arPhotoFilter = array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"SECTION_ID" => $arParams["GALLERY_ID"]
	);
	$arPhotoSelect = array("ID", "NAME", "ACTIVE", "SORT", "PREVIEW_PICTURE");
	$rsPhotos = CIBlockElement::GetList(array("SORT" => "ASC"), $arPhotoFilter, false, false, $arPhotoSelect);
	while($arPhoto = $rsPhotos->GetNext(true, false))
	{
		if($arPhoto["PREVIEW_PICTURE"])
		{
			$arPhoto["PHOTO"] = CFile::GetFileArray($arPhoto["PREVIEW_PICTURE"]);
			$arPhoto["PHOTO_SMALL"] = CFile::ResizeImageGet($arPhoto["PREVIEW_PICTURE"], array("width" => 98, "height" => 75), BX_RESIZE_IMAGE_EXACT, true);
			$arPhoto["PHOTO_SMALL"] = array_change_key_case($arPhoto["PHOTO_SMALL"], CASE_UPPER);

			unset($arPhoto["PREVIEW_PICTURE"]);

			$arResult["ITEMS"][] = $arPhoto;
		}


	}

	$this->IncludeComponentTemplate();
#}
?>
