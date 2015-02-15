<? 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
$file = PATH_LOGO . basename($_FILES['uploadfile']['name']); 
 
$ext = substr($_FILES['uploadfile']['name'],strpos($_FILES['uploadfile']['name'],'.'),strlen($_FILES['uploadfile']['name'])-1); 
$filetypes = array('.jpg', '.JPG','.jpeg','.JPEG');

if(!in_array($ext,$filetypes)){
	echo "<p>Данный формат файлов не поддерживается</p>";}
else{ 
	if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) { 
	  echo "success"; 
	} else {
		echo "error";
	}
}
?>