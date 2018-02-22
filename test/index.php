<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("THE MAIN EXHIBITION IN THE LUXURY TRAVEL INDUSTRY");

	// test case
	use Bitrix\Highloadblock\HighloadBlockTable as HLBT;

	CModule::IncludeModule('highloadblock');

	function GetEntityDataClass($HlBlockId) {
	    if (empty($HlBlockId) || $HlBlockId < 1)
	    {
	        return false;
	    }
	    $hlblock = HLBT::getById($HlBlockId)->fetch();   
	    $entity = HLBT::compileEntity($hlblock);
	    $entity_data_class = $entity->getDataClass();
	    return $entity_data_class;
	}

	$entity_data_class = GetEntityDataClass(15);

	$result = $entity_data_class::add(array(
      	'UF_NAME'  => 'тест',
   	));
	// test case end

/*
function strcode($str, $passw=""){
		$salt = "Dn8*#2n!9j";
		$len = strlen($str);
		$gamma = '';
		$n = $len>100 ? 8 : 2;
		while( strlen($gamma)<$len ){
			$gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
		}
		return $str^$gamma;
	}
$txt = strcode(base64_decode("WVPWOlWIjMen6Q=="), 'luxoran');
echo $txt;
*/
/*
function strcode($str, $passw=""){
	$salt = "Dn8*#2n!9j";
	$len = strlen($str);
	$gamma = '';
	$n = $len>100 ? 8 : 2;
	while( strlen($gamma)<$len ){
		$gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
	}
	return $str^$gamma;
}
$pasAr = array('S', '*', 't', 'f', '[', '$', '3', 'A', 'b', '7');
shuffle($pasAr);
$pasStr = implode('', $pasAr);
$pas = base64_encode(strcode($pasStr, 'luxoran'));
$pas2 = strcode(base64_decode($pas), 'luxoran');

echo $pas.' - зашифрованный';
echo '<br>';
echo $pasStr.' - исходный';
echo '<br>';
echo $pas2.' - расшифрованный';
echo '<br>';
*/
/*
$logotip = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/upload/ajax_photo/logotip/Book.jpg');
c($logotip);
$v = CFile::SaveFile($logotip, "upload");
c($v);
$file = CFile::ResizeImageGet($v, array('width'=>200, 'height'=> 99999), BX_RESIZE_IMAGE_PROPORTIONAL, true);    
c($file);
$img = '<img src="'.$file['src'].'" width="'.$file['width'].'" height="'.$file['height'].'" />';
echo $img;
$logotip2 = CFile::MakeFileArray($file['src']);
c($logotip2);
*/
/*
CModule::IncludeModule("form");
$FORM_ID_COMP = 10;
$arValues = array (
	"form_checkbox_SIMPLE_QUESTION_156"                 => ARRAY(844)
);
if ($RESULT_ID = CFormResult::Add($FORM_ID_COMP, $arValues)){
	echo $RESULT_ID;
}
*/
/*$user = new CUser;
$gr = array(3, 4, 5, 6, 19);
$pas = 1;
$arFields = Array(
	"EMAIL"             => 'weewf@dsf.er',
	"LOGIN"             => 'sdfdfdfdf3',
	"LID"               => "ru",
	"ACTIVE"            => "Y",
	"GROUP_ID"          => $gr,
	"PASSWORD"          => $pas,
	"CONFIRM_PASSWORD"  => $pas
);
$ID = $user->Add($arFields);
if (intval($ID) > 0){
	echo 'yes';
}else{
	echo $user->LAST_ERROR;
}*/
/*CModule::IncludeModule("form");
CFormOutput::ShowInput('SIMPLE_QUESTION_115');*/
/*
$user = new CUser;
$arFields = Array(
  "EMAIL"             => "qwqw12@microsoft.com",
  "LOGIN"             => "Butterfly Travel ",
  "ACTIVE"            => "Y",
  "PASSWORD"          => "123456",
  "CONFIRM_PASSWORD"  => "123456"
);

$ID = $user->Add($arFields);
if (intval($ID) > 0)
    echo "Пользователь успешно добавлен.";
else
    echo $user->LAST_ERROR;


$filter = Array("ACTIVE"              => "Y");
$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter);
$i = 0;
while($rsUsers->NavNext(true, "f_")){
	$i++;
	if($i==1){
		$last = $f_ID;
	}
	$ar[] = $f_ID;
}
for($j=0; $j<$last;$j++){
	if(!in_array($j, $ar)){
		echo $j.'<br>';
	}
}*/
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>