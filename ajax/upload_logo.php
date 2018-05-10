<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");

$file = $_FILES["logo"];

$finfo = new finfo(FILEINFO_MIME_TYPE, "/usr/share/misc/magic");

$result = array();

if(!check_bitrix_sessid("sid"))
{
	$result["STATUS"] = "ERROR";
	$result["MESSAGE"] = "Error session id";
}

elseif(!($finfo->file($file['tmp_name']) === "image/jpeg" || $finfo->file($file['tmp_name']) === "image/svg+xml")) //Wrong way to check file type. Should use finfo_open
{
    $result["STATUS"] = "ERROR";
    $result["MESSAGE"] = "Photo format should be only jpg or svg";
}

elseif($file["size"] > 2097152)
{
	$result["STATUS"] = "ERROR";
	$result["MESSAGE"] = "Maximum file size - 2 Mb";

}
else
{
	$newPath = $_SERVER["DOCUMENT_ROOT"] .  IMG_TMP_PATH . "logo/" . $_POST["sid"] . "/" ;


	if(!file_exists($newPath))
	{
		mkdir($newPath, 0777, true);
	}



	$newFile = $newPath . randString(10) . "_" . translit_file_name($file["name"]);

	if(move_uploaded_file($file['tmp_name'],  $newFile ))
	{
		$result["STATUS"] = "OK";
		$result["PATH"] = $newFile;
	}
	else
	{
		$result["STATUS"] = "ERROR";
		$result["MESSAGE"] = "Save file error";
	}
}

echo (CUtil::PhpToJSObject($result));
?>