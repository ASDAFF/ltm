<?
set_time_limit(0);
ignore_user_abort(true);
session_write_close();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
use \Bitrix\Iblock;
use \Bitrix\Main;
use \Bitrix\Main\Entity;

use Ltm\Domain\Util;

if (!CModule::IncludeModule("form") || !CModule::IncludeModule("ltm.domain")) {
  ShowError("404 Not Found");
  @define("ERROR_404", "Y");
  CHTTP::SetStatus("404 Not Found");
}

//Готовим исходные данные
$arParams["TYPE"] = strip_tags($_REQUEST['type']);
$arParams["APP_CODE"] = strip_tags($_REQUEST['app']);
$arParams["IBLOCK_ID"] = 15;

//Получаем информацию по выставке
$propEnt = Util\BitrixOrmHelper::getIBlockPropertiesEntity(
  $arParams["IBLOCK_ID"]);

$arSelect = array("ID", "CODE","IBLOCK_ID", "NAME", "APP_ID" => "PROP.APP_ID", "APP_HB_ID" => "PROP.APP_HB_ID",
  "C_GUESTS_GROUP" => "PROP.C_GUESTS_GROUP", "UC_GUESTS_GROUP" => "PROP.UC_GUESTS_GROUP",
  "GUEST_SPAM_GROUP" => "PROP.GUEST_SPAM_GROUP");
$arFilter = [
  "=CODE" => $arParams["APP_CODE"],
  "IBLOCK_ID" => $arParams["IBLOCK_ID"]
];

$rsElement = Iblock\ElementTable::getList(array(
  'select'  => $arSelect,
  'filter'  => $arFilter,
  'limit'   => 1,
  'runtime' => [
    new Entity\ReferenceField(
      'PROP',
      $propEnt,
      array('=this.ID' => 'ref.IBLOCK_ELEMENT_ID'),
      array('join_type' => 'LEFT')
    ),
  ]
));

$arResult["ITEMS"] = [];
while($arInfo = $rsElement->Fetch()) {
  $arResult["PARAM_EXHIBITION"] = $arInfo;
}

//Получение данных из списка пользователей
$isAll = false; //Коллеги отдельной строкой
$isEvening = false; //Гости на вечер
$isHB = false; //Гости HB
$resultAllCode = \CFormMatrix::getPropertyIDByExh($arResult["PARAM_EXHIBITION"]["ID"]);//Поле с ID формы О КОМПАНИИ
$formId = 10; //Id формы

if(in_array($arParams["TYPE"], ['guests', 'guests_all'])){
  $filter["GROUP.GROUP_ID"] = $arResult["PARAM_EXHIBITION"]["C_GUESTS_GROUP"];
  $filter["UF_MR"] = 1;
  $fileName = "guest_".$arParams["APP_CODE"]."_confirm";
  if($arParams["TYPE"] == 'guests_all') {
    $isAll = true;
    $fileName = $fileName."_full";
  }
} elseif(in_array($arParams["TYPE"], ['guests_hb', 'guests_hb_all'])){
  $filter["GROUP.GROUP_ID"] = $arResult["PARAM_EXHIBITION"]["C_GUESTS_GROUP"];
  $filter["UF_HB"] = 1;
  $isHB = true;
  $fileName = "guest_".$arParams["APP_CODE"]."_hb";
  if($arParams["TYPE"] == 'guests_hb_all') {
    $isAll = true;
    $fileName = $fileName."_full";
  }
} elseif( in_array($arParams["TYPE"], ['guests_ev', 'guests_ev_all']) ){
  $filter["GROUP.GROUP_ID"] = $arResult["PARAM_EXHIBITION"]["C_GUESTS_GROUP"];
  $filter["UF_EV"] = 1;
  $isEvening = true;
  $fileName = "guest_".$arParams["APP_CODE"]."_evening";
  if($arParams["TYPE"] == 'guests_ev_all') {
    $isAll = true;
    $fileName = $fileName."_full";
  }
} elseif( in_array($arParams["TYPE"], ['guests_no', 'guests_no_all']) ){
  $filter["GROUP.GROUP_ID"] = $arResult["PARAM_EXHIBITION"]["UC_GUESTS_GROUP"];
  $isEvening = true;
  $fileName = "guest_".$arParams["APP_CODE"]."_no";
  if($arParams["TYPE"] == 'guests_no_all') {
    $isAll = true;
    $fileName = $fileName."_full";
  }
}
elseif($arParams["TYPE"] == 'guests_spam'){
  $filter["GROUP.GROUP_ID"] = $arResult["PARAM_EXHIBITION"]["GUEST_SPAM_GROUP"];
  $fileName = "guest_".$arParams["APP_CODE"]."_spam";
  $isEvening = false;
  $formId = 10;
}
else{
  echo 'Oops, we are not found this type.';
}

$filter["!=$resultAllCode"] = false;
$arSelect = ["ID","LOGIN", $resultAllCode, "UF_PAS"];

$rsElement = Main\UserTable::getList(array(
  'select'  => $arSelect,
  'filter'  => $filter,
  'runtime' => [
    new Entity\ReferenceField(
      'GROUP',
      'Bitrix\Main\UserGroupTable',
      array('=this.ID' => 'ref.USER_ID'),
      array('join_type' => 'LEFT')
    ),
  ]
));

$arTmpUsers = [];
$resAllComp = [];
while($arUser = $rsElement->Fetch()) {
  $newUser = [];
  $newUser['ID']       = $arUser['ID'];
  $newUser['LOGIN']    = $arUser['LOGIN'];
  $newUser['PASSWORD'] = makePassDeCode($arUser["UF_PAS"]);
  $newUser['FORM_COMP'] = $arUser[$resultAllCode];

  //Данные по всей компании
  $resAllComp[] = $newUser['FORM_COMP'];

  $arTmpUsers[] = $newUser;
}

//Получение ответов формы Гости
\CForm::GetResultAnswerArray(
  $formId,
  $arTmpResult["QUESTIONS"],
  $arTmpResult["ANSWERS"],
  $arTmpResult["ANSWERS2"],
  array("RESULT_ID" => implode("|", $resAllComp))
);
unset($arTmpResult["QUESTIONS"]);
unset($arTmpResult["ANSWERS"]);
unset($resAllComp);

$arResult["ANSWERS"] = array();

//Получаем данные по полям для Exel из справочника
if($isEvening) {
  $arCompField = \CFormMatrix::$arExelEvGuestField;
  $arComCollegeReplace = \CFormMatrix::$arExelEvGuestFieldAllToReplace;
} else {
  $arCompField = \CFormMatrix::$arExelGuestField;
  $arComCollegeReplace = \CFormMatrix::$arExelGuestFieldAllToReplace;
}

//Заполняем массив с информацией по пользователям
foreach ($arTmpUsers as $arUser) {
  $newUser = [];
  $collegInfo = [];

  /*Данные из пользователя*/
  if(!$isEvening){
    $newUser["LOGIN"] = $arUser['LOGIN'];
    $newUser["PASSWORD"] = $arUser['PASSWORD'];
  }

  $newUser["ID"] = $arUser['ID'];

  $userResult = $arTmpResult["ANSWERS2"][ $arUser['FORM_COMP'] ];

  foreach ($arCompField["QUEST_CODE"] as $idQuest => $codeRes){
    $curType = $arCompField["ANS_TYPE"][$idQuest];
    $curTitle = $arCompField["NAMES_AR"][$idQuest];
    $curRuName = $arCompField["NAMES"][$idQuest];
    $curFiled = $userResult[$codeRes]["0"][ $curType ];
    if (strpos($curRuName, '(other)') !== false && !empty($curFiled)) {
      $newUser[$curTitle] = $curFiled;
    } elseif(strpos($curRuName, '(other)') !== false && empty($curFiled) ||
             (!$isHB && ($curTitle == 'HALL' || $curTitle == 'TABLE'))) {
      continue;
    } elseif($curTitle != "DESTINITIONS" && $curTitle != "AREA") {
      $newUser[$curTitle] = $curFiled;
    } else {
      foreach ($userResult[$codeRes] as $dest) {
        $newUser[$curTitle][] = $dest[ $curType ];
      }
    }
  }

  $collegeList = [];
  if($isAll) {
    foreach($arComCollegeReplace as $collegeFields) {
      $isCollege = false;
      $newCollegue = [];
      foreach($collegeFields as $college => $toReplace) {
        $newCollegue[$toReplace] = $newUser[$college];
        if(!empty($newUser[$college])) {
          $isCollege = true;
        }
        unset($newUser[$college]);
      }
      if($isCollege) {
        $collegeList[] = $newCollegue;
      }
    }
  }

  $arResult["ANSWERS"][] = $newUser;
  //Добавляем коллег
  foreach($collegeList as $college) {
    $arResult["ANSWERS"][] = array_merge($newUser, $college);
  }
}
unset($arTmpResult["ANSWERS2"]);


//Массив с заголовками
$arResultTitles = [];
if(!$isEvening){
  $arResultTitles["LOGIN"] ="Login";
  $arResultTitles["PASSWORD"] ="Password";
}
$arResultTitles["ID"] =  "uId";

$isDestinationsWrite = false;
foreach($arCompField["QUEST_CODE"] as $idQuest => $codeRes){
  $curType = $arCompField["ANS_TYPE"][$idQuest];
  $curTitle = $arCompField["NAMES_AR"][$idQuest];
  $curRuName = $arCompField["NAMES"][$idQuest];
  $curFiled = $userResult[$codeRes]["0"][ $curType ];
  if(strpos($curRuName, '(other)') !== false){
    continue;
  }
  elseif(!$isHB && ($curTitle == 'HALL' || $curTitle == 'TABLE')){
    continue;
  }
  else{
    $arResultTitles[ $curTitle ] = $curRuName;
  }
}
if($isAll) {
  foreach($arComCollegeReplace as $collegeFields) {
    foreach($collegeFields as $college => $toReplace) {
      unset($arResultTitles[$college]);
    }
  }
}

unset($arComCollegeReplace);
unset($arCompField);


//$arResultTitles
//$arResult["ANSWERS"]
$shotPath = '/upload/excel/';
CheckDirPath($_SERVER['DOCUMENT_ROOT'].$shotPath);
$pdfFolder = $_SERVER['DOCUMENT_ROOT'].$shotPath;
$pathToFile = $_SERVER['DOCUMENT_ROOT'].$shotPath.$fileName;
$handle = fopen($pathToFile, "w");
fputs($handle, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
fputcsv($handle, $arResultTitles, ";");
foreach ($arResult["ANSWERS"] as $answer) {
  $strToWrite = [];
  foreach($arResultTitles as $title => $value) {
    $filedValue = $answer[$title];
    if(is_array($filedValue)) {
      $filedValue = implode(',', $filedValue);
    }
    if(empty($filedValue)) {
      $filedValue = ' ';
    }
    $strToWrite[] = 'DELIMITER_YOK DELIMITER_YOK'.$filedValue;
  }
  fputcsv($handle, $strToWrite, ";");
}
fclose($handle);

@unlink($pathToFile.".csv");
$fin=fopen($pathToFile,"rt");
$fout=fopen($pathToFile.".csv","wt");
while(!feof($fin)) {
  $s = fgets($fin);
  $s = str_replace('DELIMITER_YOK DELIMITER_YOK', '', $s);
  fputs($fout,$s);
}
fclose($fin); fclose($fout);
@unlink($pathToFile);