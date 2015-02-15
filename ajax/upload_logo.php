<? 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");

$file = $_FILES["logo"];

$result = array();

if(!check_bitrix_sessid("sid"))
{
	$result["STATUS"] = "ERROR";
	$result["MESSAGE"] = "Error session id";
}

elseif($file["type"] != "image/jpeg")
{
	$result["STATUS"] = "ERROR";
	$result["MESSAGE"] = "Photo format should be only jpg";

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

	$newFile = $newPath . randString(10) . "_" . $file["name"];
	
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

echo (json_encode($result));
?>