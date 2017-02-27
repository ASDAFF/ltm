<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*--------------- TO DO -------------------*/
//Добавить в параметры FORM_ID

$arResult["ERROR_MESSAGE"] = "";
$arResult["MESSAGE"] = "";

if(strLen($arParams["PATH_TO_KAB"])<=0){
	$arParams["PATH_TO_KAB"] = "/admin/";
}

if(strLen($arParams["PATH_TO_DATA"])<=0){
    $arParams["PATH_TO_DATA"] =  __DIR__ . "/data/";
}

if(strLen($arParams["AUTH_PAGE"])<=0){
	$arParams["AUTH_PAGE"] = "/admin/login.php";
}

if(strLen($arParams["USER_ID"])<=0){
	$arResult["ERROR_MESSAGE"] = "Не введены данные по Пользователю!<br />";
}

if(strLen($arParams["EXHIB_ID"])<=0){
    $arResult["ERROR_MESSAGE"] = "Не введены данные по Выставке!<br />";
}

if(!($USER->IsAuthorized()))
{
	$arResult["ERROR_MESSAGE"] = "Вы не авторизованы!<br />";
}

if(!($USER->IsAdmin()))
{
    $arResult["ERROR_MESSAGE"] = "Вы не администратор!<br />";
}

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form"))
{
    $arResult["ERROR_MESSAGE"] = "Ошибка подключения модулей!<br />";
}


//сохранение данных
if($arResult["ERROR_MESSAGE"] == '' && $_SERVER["REQUEST_METHOD"] == "POST" && $_REQUEST["save"])
{
    dataSave($arParams["USER_ID"], $_POST);
}


if($arResult["ERROR_MESSAGE"] == '')
{
    //получение данных пользователя
    $rsUser = CUser::GetList(($by = false), ($order = false), array("ID"=>$arParams["USER_ID"]), array("SELECT"=>array("UF_*"), "FIELDS" => array("ID", "NAME", "LAST_NAME", "LOGIN", "EMAIL", "WORK_COMPANY")));
    $arUser = $rsUser->Fetch();

	//получение данных выставки

    $arFilter = array(
    	"ID" => $arParams["EXHIB_ID"],
        "ACTIVE" => "Y",
    );

    $arSelect = array(
    	"ID",
        "NAME",
        "CODE",
        "IBLOCK_ID",
        "PROPERTY_*",
    );

	$rsExhib = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);

	if($obExhib = $rsExhib->GetNextElement())
	{
	    $arExhib = $obExhib->GetFields();
	    $arExhib["PROPERTIES"] = $obExhib->GetProperties();


	    $arData = array();
	    $arData["CODE"] = $arExhib["CODE"];
	    $arData["ID"] = $arExhib["ID"];
	    $arData["NAME"] = $arExhib["PROPERTIES"]["TAB_TITLE"]["VALUE"];
	    $arData["LONG_NAME"] = $arExhib["PROPERTIES"]["LONG_NAME"]["VALUE"];

	    $arData["DATE"] = date("d.m.Y");
	    $arData["EMAIL"] = $arUser["EMAIL"];

	    $formID = CFormMatrix::getPFormIDByExh($arExhib["ID"]);
	    $formPropName = CFormMatrix::getPropertyIDByExh($arExhib["ID"]);//получение имени свойства пользователя для текущей выставки
	    $resultUserId = $arUser[$formPropName];
	    $arResult["USER_RESULT_ID"] = $resultUserId;
	    $resultCompanyId = $arUser["UF_ID_COMP"];
	    $arResult["COMPANY_RESULT_ID"] = $resultCompanyId;

	    $arResult["FORM_ID"] = $formID;

	    //получение данных из формы пользователя
	    $FieldUserSID = array(
	        "FIRST_NAME" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_446",$formID),//Participant first name
	        "LAST_NAME" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_551",$formID),//Participant last name
	        "JOB_TITLE" =>CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_729",$formID),//Job title
	        "PHONE" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_394",$formID),//Telephone
	        "EMAIL" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_859",$formID),//E-mail
	        "EMAIL_CONF" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_585",$formID),//Please confirm your e-mail
	        "EMAIL_ALT" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_749",$formID),//Alternative e-mail
	        "PHOTO" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_575",$formID),//Персональное фото
	        "SALUTATION" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_889",$formID),//Salutation
	        "PAY_NAME" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_539",$formID),//Номер счета
	        "PAY_COUNT" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_680",$formID),//Сумма счета
	        "PAY_REQUISITE" => CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_667",$formID),//Реквизиты
	    );

	    $arAnswer = CFormResult::GetDataByID(
	        $resultUserId,
	        array(
	            $FieldUserSID["FIRST_NAME"],
	            $FieldUserSID["LAST_NAME"],
	            $FieldUserSID["JOB_TITLE"],
	            $FieldUserSID["PHONE"],
	            $FieldUserSID["EMAIL"],
	            $FieldUserSID["EMAIL_CONF"],
	            $FieldUserSID["EMAIL_ALT"],
	            $FieldUserSID["PHOTO"],
	            $FieldUserSID["SALUTATION"],
	            $FieldUserSID["PAY_NAME"],
	            $FieldUserSID["PAY_COUNT"],
	            $FieldUserSID["PAY_REQUISITE"],
	        ),
	        $arResultFormUser,
	        $arAnswerUserSID);

	    //составляем массив в пользователе из результатов заполнения формы
	     $arUser["FORM_DATA"] = array();
	     foreach ($FieldUserSID as $name => $sid)
	     {
	         if(isset($arAnswerUserSID[$sid]))
	         {
	             $resName = "";
	             $tmp = reset($arAnswerUserSID[$sid]);
	             switch ($tmp["FIELD_TYPE"])
	             {
	             	case "dropdown" : $resName = "ANSWER_TEXT";break;
	             	case "image" : $resName = "USER_FILE_ID"; break;
	             	case "text" : $resName = "USER_TEXT"; break;
	             	case "radio" : $resName = "ANSWER_ID"; break;
	             }

	             ;
	             $arUser["FORM_DATA"][$name] = $tmp[$resName];
	         }
	     }


	     //получение данных из формы компании
	     $FieldCompanySID = array(
	         "COMPANY_NAME_INVOICE" => "SIMPLE_QUESTION_106",// - Official name for invoice
	         "COMPANY_NAME" => "SIMPLE_QUESTION_988",// - Company or hotel name
	         "LOGIN" => "SIMPLE_QUESTION_993",// - Your login
	         "AREA_OF_BUSINESS" => "SIMPLE_QUESTION_284",// - Area of the business
	         "ADDRESS" => "SIMPLE_QUESTION_295",// - Official adress
	         "CITY" => "SIMPLE_QUESTION_320",// - City
	         "COUNTRY" => "SIMPLE_QUESTION_778",// - Country
	         "WEB" => "SIMPLE_QUESTION_501",// - http://
	         "DESCRIPTION" => "SIMPLE_QUESTION_163",// - Company description
	         "NORTH_AMERICA" => "SIMPLE_QUESTION_876",// - North America
	         "EUROPE" => "SIMPLE_QUESTION_367",// - Europe
	         "SOUTH_AMERICA" => "SIMPLE_QUESTION_328",// - South America
	         "AFRICA" => "SIMPLE_QUESTION_459",// - Africa
	         "ASIA" => "SIMPLE_QUESTION_931",// - Asia
	         "OCEANIA" => "SIMPLE_QUESTION_445",// - Oceania
	         "LOGO" => "SIMPLE_QUESTION_395"// - Logo
	     );

	     $arAnswer = CFormResult::GetDataByID(
	         $resultCompanyId,
	         array(
	         	 $FieldCompanySID["COMPANY_NAME_INVOICE"],
	             $FieldCompanySID["COMPANY_NAME"],
	             $FieldCompanySID["ADDRESS"],
	             $FieldCompanySID["CITY"],
	             $FieldCompanySID["COUNTRY"],
	         ),
	         $arResultFormCompany,
	         $arAnswerCompanySID);

	     //составляем массив в пользователе из результатов заполнения формы
	     foreach ($FieldCompanySID as $name => $sid)
	     {
	         if(isset($arAnswerCompanySID[$sid]))
	         {
	             $resName = "";
	             $tmp = reset($arAnswerCompanySID[$sid]);
	             switch ($tmp["FIELD_TYPE"])
	             {
	             	case "dropdown" : $resName = "ANSWER_TEXT";break;
	             	case "image" : $resName = "USER_FILE_ID"; break;
	             	case "text" : $resName = "USER_TEXT"; break;
	             }

	             ;
	             $arUser["FORM_DATA"][$name] = $tmp[$resName];
	         }
	     }


	     $arData["COMPANY_NAME_INVOICE"] = $arUser["FORM_DATA"]["COMPANY_NAME_INVOICE"];
	     $arData["COMPANY_NAME"] = $arUser["FORM_DATA"]["COMPANY_NAME"];
	     $arData["ADDRESS"] = $arUser["FORM_DATA"]["ADDRESS"];
	     $arData["CITY"] = $arUser["FORM_DATA"]["CITY"];
	     $arData["COUNTRY"] = $arUser["FORM_DATA"]["COUNTRY"];
	     $arData["PHONE"] = $arUser["FORM_DATA"]["PHONE"];
	     //$arData["FIRST_NAME"] = $arUser["NAME"];
	     //$arData["LAST_NAME"] = $arUser["LAST_NAME"];
	     if(strlen(trim($arUser["FORM_DATA"]["EMAIL"])) > 0)
	     {
	     	$arData["EMAIL"] = $arUser["FORM_DATA"]["EMAIL"];
	     }
	     if(strlen(trim($arUser["FORM_DATA"]["FIRST_NAME"])) > 0)
	     {
	     	$arData["FIRST_NAME"] = $arUser["FORM_DATA"]["FIRST_NAME"];
	     }
	     if(strlen(trim($arUser["FORM_DATA"]["LAST_NAME"])) > 0)
	     {
	     	$arData["LAST_NAME"] = $arUser["FORM_DATA"]["LAST_NAME"];
	     }

	     $arData["PAY_NAME"] = $arUser["FORM_DATA"]["PAY_NAME"];
	     $arData["PAY_COUNT"] = $arUser["FORM_DATA"]["PAY_COUNT"];
	     $arData["PAY_REQUISITE"] = $arUser["FORM_DATA"]["PAY_REQUISITE"];


	     //получение id ответа реквизитов

	     $WebFormId = CForm::GetDataByID(
	         $formID,
	         $arForm,
	         $arQuestions,
	         $arAnswersList,
	         $arDropDown,
	         $arMultiSelect
	     );

	     $index = CFormMatrix::getIndexRequisiteIDByForm($arData["PAY_REQUISITE"], $formID);

	     foreach ($arAnswersList[$FieldUserSID["PAY_REQUISITE"]] as $arAnswerReq)
	     {
	         $arResult["PAY_REQUISITE"][] = $arAnswerReq;

	         if(!$arData["PAY_REQUISITE"] && "checked" == $arAnswerReq["FIELD_PARAM"])//запоминаем значение по умолчанию
	         {
	             $index = CFormMatrix::getIndexRequisiteIDByForm($arAnswerReq["ID"], $formID);
	         }
	     }

	     switch ($index)
	     {
	     	case 0 : $arData["PAY_REQUISITE_XML"] = "IP"; break;
	     	case 1 : $arData["PAY_REQUISITE_XML"] = "TM"; break;
				case 2 : $arData["PAY_REQUISITE_XML"] = "EV"; break;
				case 3 : $arData["PAY_REQUISITE_XML"] = "EM"; break;
	     	default: $arData["PAY_REQUISITE_XML"] = "IP";
	     }

	}

	$arResult["TYPE"] = "FORM";

	$arResult["DATA"] = $arData;
	$arResult["USER"] = $arUser;

	if(isset($_REQUEST["type"]) && $_REQUEST["type"] == "look")//смотрим счет
	{
	    $oPDF = generatePDF($arData, $arParams["PATH_TO_DATA"]);

	    $APPLICATION->RestartBuffer();
	    $oPDF->Output("invoice.pdf","I") ;
	}
	elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST["button_look"]))//перенаправление на просмотр счета
	{?>

	    <script type="text/javascript">
			var recHref = "/admin/service/count_pdf.php?uid=<?=$arUser["ID"]?>&exhib=<?=$arExhib["ID"]?>&type=look";
			newWind(recHref,'count_look', 800, 600);
        </script>
	<?
	}
	elseif($_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST["make"]))// ОТПРАВЛЯЕМ ПИСЬМО СО СЧЕТОМ
	{

	    $oPDF = generatePDF($arData, $arParams["PATH_TO_DATA"]);

	    $file = $oPDF->Output("invoice.pdf","S") ;

	    $success = sendEmailPDF($arData, $file, $arParams["PATH_TO_DATA"]);

	    if ($success){

	        /*$user = new CUser;
	         $fields = Array(
	             "UF_INVOICE" => "SENT",
	         );
	        $user->Update($arParams["USER"], $fields);
	        $strError .= $user->LAST_ERROR;
	        */
	        //mail("artyom.polanskiy@1st-pr.ru", $addr,$message,$headers);
	        $arResult["TYPE"] = "SENT";
	    }
	    else{
	        $arResult["TYPE"] = "SENT";
	        $arResult["ERROR_MESSAGE"] = "Не удалось отправит счет<br />";
	    }

	}
}



$this->IncludeComponentTemplate();


function sendEmailPDF($data, $file, $folder)
{
    $path = $folder . "{$data["CODE"]}/email.html";
    $html = file_get_contents($path);

    $headers = "From: noreply@luxurytravelmart.ru";
    $fileatt_name = "LTM_invoice.pdf";
    $fileatt_type = "application/pdf";

    // Generate a boundary string
    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

    // Add the headers for a file attachment
    $headers .= "\nMIME-Version: 1.0\n" .
        "Content-Type: multipart/mixed;\n" .
        " boundary=\"{$mime_boundary}\"";

    // Add a multipart boundary above the plain message
    $message = "This is a multi-part message in MIME format.\n\n" .
        "--{$mime_boundary}\n" .
        "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
        "Content-Transfer-Encoding: 7bit\n\n" .
        $html . "\n\n";
    // Base64 encode the file data
    $file = chunk_split(base64_encode($file));
    // Add file attachment to the message
    $message .= "--{$mime_boundary}\n" .
    "Content-Type: {$fileatt_type};\n" .
    " name=\"{$fileatt_name}\"\n" .
    "Content-Disposition: attachment;\n" .
    " filename=\"{$fileatt_name}\"\n" .
    "Content-Transfer-Encoding: base64\n\n" .
    $file. "\n\n" .

    "--{$mime_boundary}--\n";
    $subject = "{$data["COMPANY_NAME"]}: {$data["NAME"]} Invoice & Confirmation Letter – please sign and return within 5 working days";

    //$data["EMAIL"]

    //$mail = "dmitrz@rarus.ru";
    $success = mail($data["EMAIL"], $subject,$message,$headers);

    if($success)
    {
        mail("artyom.polanskiy@luxurytravelmart.ru", $subject,$message,$headers);
    }

    return $success;
}





/**
 * Возвращает объект TCPDF с pdf документом
 * @param int $userID
 * @param array $data
 * @return TCPDF
 */
function generatePDF($data, $folder)
{
    switch ($data["PAY_REQUISITE_XML"])
    {
    	case "IP" : {
$data["BENEFICIARY"] = "Polanskiy Artem Valentinovich,
registered as independent entrepreneur
with State Registration Number 309503525800010
at Federal Tax Service Inspectorate in
Pavlosvkiy Posad, Moscow Region

Индивидуальный предприниматель
Поланский Артём Валентинович,
зарегистрированный Инспекцией Федеральной
Налоговой Службы
по г. Павловский Посад Московской области,
государственный регистрационный номер 309503525800010
ИНН 503507510512

Moscow, Russia";

$data["BANK_DETAILS"] = "Beneficiary's Bank:
VTB 24 (PJSC), Moscow, Russia
SWIFT: CBGURUMM
Beneficiary: Polanskiy Artem Valentinovich
Account: 40802978700001002738";

$data["BENEFICIARY_NAME"] = "Artem V. Polanskiy / Поланский Артём Валентинович";
    	}; break;

    	case "TM" : {
$data["BENEFICIARY"] = "Travel Media,
registered as Society with limited liability
with State Registration Number 1047796617472
at Federal Tax Service Inspectorate No 46 in Moscow
Общество с ограниченной ответственностью «Трэвэл Медиа»,
зарегистрированное Инспекцией Федеральной
Налоговой Службы № 46 по г. Москве,
государственный регистрационный номер 1047796617472
ИНН 7707525284

Moscow, Russia";


$data["BANK_DETAILS"] = "Beneficiary's Bank:
VTB Bank (open joint-stock company), Moscow, Russia
SWIFT: VTBRRUMM
Beneficiary: Travel Media
Account: 40702978900140010240";

$data["BENEFICIARY_NAME"] = "Elena Vetrova / Ветрова Елена Васильевна";
    	}; break;

		case "EV" : {
			$data["BENEFICIARY"] = "Vetrova Elena Vasilievna,
Registered as independent entrepreneur
with State Registration Number 315774600341092
at Federal Tax Service Inspectorate in Moscow
Individual tax-payer number: 773319465722

Индивидуальный предприниматель
Ветрова Елена Васильевна,
зарегистрированный Инспекцией Федеральной
Налоговой Службы
по г. Москва, 
государственный регистрационный номер 315774600341092
ИНН 773319465722

Moscow, Russia";

			$data["BANK_DETAILS"] = "Beneficiary's Bank:
ALFA-BANK Moscow, Russia
SWIFT: ALFARUMM
Beneficiary: Vetrova Elena Vasilievna
Account: 40802978702410000010";

			$data["BENEFICIARY_NAME"] = "Elena V. Vetrova / Ветрова Елена Васильевна";
		}; break;

			case "EM" : {
				$data["BENEFICIARY"] = "UAB «Europae Media»,
Įmonės kodas/ company code: 303360184
PVM mokėtojo kodas/ VAT code: LT100008970519
Adresas/Address: Vilnius, Tilto g. 12b-23
Lietuva/Lithuania";


				$data["BANK_DETAILS"] = "UAB «Europae Media»
                
IBAN LT17 7044 0600 0799 5211

Banko kodas/ Bank code: 70440
Bankas /Bank: SEB bankas
SWIFT: CBVILT2X";

				$data["BENEFICIARY_NAME"] = "Elena Vetrova / Ветрова Елена Васильевна";
			}; break;

    }

    global $APPLICATION;

    define("FONT_SIZE", 12);
    define("LINE_INDENT", 3.4);

    //подгружаем библиотеку с классами для работы pdf
    require_once ($_SERVER["DOCUMENT_ROOT"] .  "/local/php_interface/lib/tcpdf/tcpdf.php");

    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(15, 5, 10);

    $pdf->SetAutoPageBreak(TRUE, 5);

    $pdf->AddFont('freeserif','I','freeserifi.php');
    $pdf->AddFont('helvetica','I','helveticai.php');
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);


    $pdf->AddPage();
    HeaderPDF($data, $pdf, $folder); //реквизиты заголовка
    BodyPDF($data, $pdf, $folder); //центральная часть
    FooterPDF($data, $pdf, $folder);//реквизиты подвала

    //добавление контракта

    $pdf->AddPage();
    ContractPDF($data, $pdf, $folder);

    return $pdf;
}

function ContractPDF($data, &$oPDF, $folder)
{

    $path = $folder . "{$data["CODE"]}/{$data["PAY_REQUISITE_XML"]}_contract.html";
    $html = file_get_contents($path);

    $arSearch = array("#COMPANY_NAME_INVOICE#", "#PAY_NAME#", "#FIRST_NAME#", "#LAST_NAME#", "#DATE#");
    $arReplace = array($data["COMPANY_NAME_INVOICE"], $data["PAY_NAME"], $data["FIRST_NAME"], $data["LAST_NAME"], $data["DATE"]);//заносим данные в html
    $html = str_replace($arSearch, $arReplace, $html);

    $oPDF->SetFont('helvetica','',FONT_SIZE);
    $oPDF->writeHTML($html, true, false, false, false, '');

    //печати и подписи
    if($data["PAY_REQUISITE_XML"] == "IP")
    {
        $stampY = $oPDF->getY() - 30;
        $stampX = 100;
        $img = __DIR__ . '/images/stamp_polansky.png';
        $height = 40;
        $width = 100.94;

    }
    elseif($data["PAY_REQUISITE_XML"] == "TM")
    {
        $stampY = $oPDF->getY() - 30;
        $stampX = 130;
        $img = __DIR__ . '/images/stamp_tm.png';
        $height = 40;
        $width = 52.88;
    }
	elseif($data["PAY_REQUISITE_XML"] == "EV")
	{
		$stampY = $oPDF->getY() - 30;
		$stampX = 100;
		$img = __DIR__ . '/images/stamp_ev.png';
		$height = 40;
		$width = 100.94;
	}
		elseif($data["PAY_REQUISITE_XML"] == "EM")
		{
			$stampY = $oPDF->getY() - 30;
			$stampX = 130;
			$img = __DIR__ . '/images/stamp_tm.png';
			$height = 40;
			$width = 52.88;
		}

    $oPDF->Image($img,$stampX,$stampY,$width,$height ,'','','',false,72,'',false,false,1);

}

function HeaderPDF($data, &$oPDF, $folder)
{
    //генерация хедера
    //$oPDF->Image('/images/logo_for_pdf.jpg'); //логотип
	$oPDF->ImageSVG($file='images/logo_ltm.svg', $x=10, $y=5, $w='40', $h='', $link='', $align='', $palign='', $border=0, $fitonpage=false);


    //текст правой колонки
    $oPDF->setXY(0, 5);
    $oPDF->SetFont('freeserif','B',FONT_SIZE);
    $oPDF->MultiCell(0, 5, "Ordering customer / Плательщик:\n", 0, "R");
    //$oPDF->setXY(0,$oPDF->getY() + LINE_INDENT);

    //вывод инфо о компании
    $oPDF->setXY(80,$oPDF->getY());
    $oPDF->SetFont('freeserif','',FONT_SIZE);
    $sData = "{$data["COMPANY_NAME_INVOICE"]}\n{$data["ADDRESS"]}\n{$data["CITY"]}\n{$data["COUNTRY"]}\n{$data["PHONE"]}";
    $oPDF->MultiCell(120, 5, $sData, 0, "R");
    $oPDF->setXY(0,$oPDF->getY() + LINE_INDENT);

    //вывод инфо получателя
    $oPDF->SetFont('freeserif','B',FONT_SIZE);
    $oPDF->MultiCell(0, 5, "Beneficiary / Получатель:\n", 0, "R");
    //$oPDF->setXY(0,$oPDF->getY() + LINE_INDENT);

    $oPDF->SetFont('freeserif','',FONT_SIZE);

    $oPDF->MultiCell(0, 5, $data["BENEFICIARY"], 0, "R");

    $oPDF->MultiCell(0, 5, $data["DATE"], 0, "R");
    $oPDF->setXY(0,$oPDF->getY() + LINE_INDENT);
}

function BodyPDF($data, &$oPDF, $folder)
{
    //вывод основной информации

    $path = $folder . "{$data["CODE"]}/{$data["PAY_REQUISITE_XML"]}_invoice.txt";
    $data["DETAILS_OF_PAYMENT"] = file_get_contents($path);

    $path = $folder . "{$data["CODE"]}/name.txt";
    $data["SHORT_NAME"] = file_get_contents($path);



    $oPDF->SetFont('freeserif','B',FONT_SIZE);
    $oPDF->MultiCell(0, 5, "INVOICE N/ Счёт № {$data["PAY_NAME"]}-{$data["SHORT_NAME"]}\n", 0, "C");
    $oPDF->setXY(0,$oPDF->getY() + LINE_INDENT);

    $oPDF->SetFont('freeserif','B',FONT_SIZE);
    $oPDF->MultiCell(0, 5, "Details of payment / Предмет счёта:\n", 0, "C");
   // $oPDF->setXY(0,$oPDF->getY() + LINE_INDENT);

    $oPDF->SetFont('freeserif','',FONT_SIZE);

    $oPDF->MultiCell(0, 5, $data["DETAILS_OF_PAYMENT"], 0, "C");
    $oPDF->setXY(0,$oPDF->getY() + LINE_INDENT);

    //
    $oPDF->SetFont('freeserif','B',FONT_SIZE);
    $oPDF->MultiCell(0, 5, "Total amount of payment / Сумма платежа: {$data["PAY_COUNT"]} euro\n", 0, "C");
    $oPDF->setXY(0,$oPDF->getY() + LINE_INDENT);

    $oPDF->SetFont('freeserif','B',FONT_SIZE);
    $oPDF->MultiCell(0, 5, "Payment information / Детали платежа:\n", 0, "C");

    $oPDF->SetFont('freeserif','',FONT_SIZE);
    $oPDF->MultiCell(0, 5, "Please put the invoice number / Укажите номер счёта\n", 0, "C");
    $oPDF->setXY(0,$oPDF->getY() + LINE_INDENT);
}

function FooterPDF($data, &$oPDF, $folder)
{
    $oPDF->SetFont('freeserif','B',FONT_SIZE);
    $oPDF->MultiCell(0, 5, "Bank details / Банковские реквизиты:\n", 0, "C");

    $oPDF->SetFont('freeserif','',FONT_SIZE);
    $oPDF->MultiCell(0, 5, $data["BANK_DETAILS"], 0, "C");
    $oPDF->setXY(0,$oPDF->getY() + LINE_INDENT);

    $oPDF->SetFont('freeserif','B',FONT_SIZE);
    $oPDF->MultiCell(0, 5, "PLEASE NOTE THAT IBAN CODES DO NOT EXIST IN THE RUSSIAN FEDERATION\n", 0, "C");
    $oPDF->setXY(0,$oPDF->getY() + LINE_INDENT);

    $oPDF->SetFont('freeserif','',FONT_SIZE);
    $sData  = "This invoice is valid for payments within specified time. Bank charges at payer's expense\n";
    $sData .= "Инвойс действителен в течение оговорённого времени.\n";
    $sData .= "Банковские сборы и комиссии за счет плательщика\n";

    $oPDF->MultiCell(0, 5, $sData, 0, "C");
    $oPDF->setXY(10,$oPDF->getY() + LINE_INDENT);


    $oPDF->SetFont('freeserif','',FONT_SIZE);
    $oPDF->MultiCell(0, 5, $data["BENEFICIARY_NAME"], 0, "L");
    $oPDF->setXY(0,$oPDF->getY() + LINE_INDENT);

    $oPDF->SetFont('freeserif','',FONT_SIZE);
    $oPDF->MultiCell(0, 5, "(Electronic copy, without signature and company stamp / электронная копия, без подписи и печати)", 0, "C");
}


/**
 * Сохранение данных $data в свойства пользователя с ID = PAY_COUNT
 * @param int $userID - ID Пользователя
 * @param array $data - Данные из POST (PPAY_NAME, AY_COUNT, PAY_REQUISITE, )
 * @return boolean | array
 */
function dataSave($userID, $data)
{
    $resultId = $data["RESULT_ID"];
    $formId = $data["FORM_ID"];


    $arFields = array();

    $nameSID = CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_539",$formId);
    if(isset($data[$nameSID]))
    {
        $arFields[$nameSID] = array(CFormMatrix::getAnswerRelBase(1336,$formId) => $data[$nameSID]);
    }

    $countSID = CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_680",$formId);
    if(isset($data[$countSID]))
    {
        $arFields[$countSID] = array(CFormMatrix::getAnswerRelBase(1337,$formId) => $data[$countSID]);
    }

    $requisitSID = CFormMatrix::getSIDRelBase("SIMPLE_QUESTION_667",$formId);
    if(isset($data[$requisitSID]))
    {
        $arFields[$requisitSID] = array($data[$requisitSID] => "");
    }

    foreach ($arFields as $SID => $value)
    {
        CFormResult::SetField($resultId, $SID, $value);
    }
}

?>