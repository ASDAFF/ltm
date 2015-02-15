<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

foreach ($_FILES as $key => $fileData)
{
	$folder = str_replace("uploadfile-", "", $key);
	$uploaddir = '/upload/tmp/'. bitrix_sessid()."/".$folder."/";
	$filename = $fileData["name"];
	
	$ext = mb_strrchr($filename, ".", false, "CP1251");
	
	$name = randString(10).$ext;
	$file = $uploaddir.$name;//$_FILES['uploadfile-personal']['name'];
	
	$type = $fileData["type"];
	$filetypes = array('image/jpeg', 'image/png');
	
	$arResponse = array(
			"NAME" => $filename,
			"FILE_NAME" => $name,
			"DIR" => $uploaddir,
			"FILE" => $file,
			"SID" => bitrix_sessid()
	);
	
	if(!in_array($type,$filetypes)){
		$arResponse["ERROR_TEXT"] = "This file format is not supported";
	}
	else
	{
		//создаем папку, если нет
		if (!file_exists($_SERVER["DOCUMENT_ROOT"].$uploaddir)) {
			mkdir($_SERVER["DOCUMENT_ROOT"].$uploaddir, 0766, true);
		}
	
		//перемещаем файл
		if (move_uploaded_file($fileData['tmp_name'], $_SERVER["DOCUMENT_ROOT"]. $file))
		{
			$arResponse["STATUS"] = "success";
		}
		else
		{
			$arResponse["STATUS"] = "error";
			$arResponse["ERROR_TEXT"] = "Could not create file";
		}
	}
	
	echo CUtil::PhpToJSObject($arResponse);
}




?>