<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
$file = PATH_PHOTOS . basename($_FILES['uploadfile']['name']); 
 
$ext = substr($_FILES['uploadfile']['name'],strpos($_FILES['uploadfile']['name'],'.'),strlen($_FILES['uploadfile']['name'])-1); 
$filetypes = array('.jpg', '.JPG','.jpeg','.JPEG');

if(!in_array($ext,$filetypes)){
	echo "<p>Р”Р°РЅРЅС‹Р№ С„РѕСЂРјР°С‚ С„Р°Р№Р»РѕРІ РЅРµ РїРѕРґРґРµСЂР¶РёРІР°РµС‚СЃСЏ</p>";}
else{ 
	if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) { 
	  echo "success"; 
	} else {
		echo "error";
	}
}
?>