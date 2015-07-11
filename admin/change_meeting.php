<?
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
set_time_limit(0);

CModule::IncludeModule("doka.meetings");
CModule::IncludeModule("form");
CModule::IncludeModule("iblock");

use Doka\Meetings\Settings as DS;
use Doka\Meetings\Requests as DR;
use Doka\Meetings\Timeslots as DT;
use Doka\Meetings\Wishlists as DWL;

$userInfo = array();
$userInfo[1] = array();//Москва Весна 2015
$userInfo[1]["FORM"] = 4;
$userInfo[1]["PROP_NAME"] = "UF_ID11";
$userInfo[1]["FIO"][0][0] = "SIMPLE_QUESTION_446";
$userInfo[1]["FIO"][0][1] = "84";
$userInfo[1]["FIO"][1][0] = "SIMPLE_QUESTION_551";
$userInfo[1]["FIO"][1][1] = "85";
$userInfo[1]["FIO"][2][0] = "SIMPLE_QUESTION_859";
$userInfo[1]["FIO"][2][1] = "89";


$userInfo[2] = array();//Москва Баку 2014
$userInfo[2]["FORM"] = 5;
$userInfo[2]["PROP_NAME"] = "UF_ID2";
$userInfo[2]["FIO"][0][0] = "SIMPLE_QUESTION_709";
$userInfo[2]["FIO"][0][1] = "92";
$userInfo[2]["FIO"][1][0] = "SIMPLE_QUESTION_599";
$userInfo[2]["FIO"][1][1] = "93";
$userInfo[2]["FIO"][2][0] = "SIMPLE_QUESTION_650";
$userInfo[2]["FIO"][2][1] = "97";

$userInfo[3] = array();//Москва Осень 2014
$userInfo[3]["FORM"] = 8;
$userInfo[3]["PROP_NAME"] = "UF_ID5";
$userInfo[3]["FIO"][0][0] = "SIMPLE_QUESTION_119";
$userInfo[3]["FIO"][0][1] = "116";
$userInfo[3]["FIO"][1][0] = "SIMPLE_QUESTION_869";
$userInfo[3]["FIO"][1][1] = "117";
$userInfo[3]["FIO"][2][0] = "SIMPLE_QUESTION_786";
$userInfo[3]["FIO"][2][1] = "121";

$userInfo[4] = array();//Москва Алматы 2014
$userInfo[4]["FORM"] = 7;
$userInfo[4]["PROP_NAME"] = "UF_ID4";
$userInfo[4]["FIO"][0][0] = "SIMPLE_QUESTION_948";
$userInfo[4]["FIO"][0][1] = "108";
$userInfo[4]["FIO"][1][0] = "SIMPLE_QUESTION_159";
$userInfo[4]["FIO"][1][1] = "109";
$userInfo[4]["FIO"][2][0] = "SIMPLE_QUESTION_742";
$userInfo[4]["FIO"][2][1] = "113";

$userInfo[5] = array();//Москва Киев 2014
$userInfo[5]["FORM"] = 6;
$userInfo[5]["PROP_NAME"] = "UF_ID3";
$userInfo[5]["FIO"][0][0] = "SIMPLE_QUESTION_896";
$userInfo[5]["FIO"][0][1] = "100";
$userInfo[5]["FIO"][1][0] = "SIMPLE_QUESTION_409";
$userInfo[5]["FIO"][1][1] = "101";
$userInfo[5]["FIO"][2][0] = "SIMPLE_QUESTION_279";
$userInfo[5]["FIO"][2][1] = "105";

$userInfo[6] = array();//Москва Весна 2015
$userInfo[6]["FORM"] = 4;
$userInfo[6]["PROP_NAME"] = "UF_ID11";
$userInfo[6]["FIO"][0][0] = "SIMPLE_QUESTION_446";
$userInfo[6]["FIO"][0][1] = "84";
$userInfo[6]["FIO"][1][0] = "SIMPLE_QUESTION_551";
$userInfo[6]["FIO"][1][1] = "85";
$userInfo[6]["FIO"][2][0] = "SIMPLE_QUESTION_859";
$userInfo[6]["FIO"][2][1] = "89";

// Получим список выставок
$rsExhibitions = DS::GetList(array(), array());
while ($exhibition = $rsExhibitions->Fetch()) {
    if(isset($exhibition["CODE"]) && $exhibition["CODE"]!=''){
        $rsExhib = CIBlockElement::GetList(
                array(),
                array(
                        "IBLOCK_ID" => "15",
                        "CODE" => $exhibition["CODE"]
                    ),
                false,
                false,
                array("ID", "CODE", "NAME", "IBLOCK_ID","PROPERTY_*")
                );
        while($oExhib = $rsExhib->GetNextElement(true, false))
        {
            $arResult["PARAM_EXHIBITION"] = $oExhib->GetFields();
            $arResult["PARAM_EXHIBITION"]["PROPERTIES"] = $oExhib->GetProperties();
            unset($arResult["PARAM_EXHIBITION"]["PROPERTIES"]["MORE_PHOTO"]);
        }
    }
    $timemout_value = $exhibition['TIMEOUT_VALUE'] * 3600; // sec
    // Для каждой выставки отвергаем заявки, если превышен лимит ожидания ответа
    $rsRequests = DR::GetList(array(), array(
        '<=UPDATED_AT' => ConvertTimeStamp(time() - $timemout_value, "FULL", "ru"),
        'STATUS' => DR::getStatusCode(DR::STATUS_PROCESS),
        'EXHIBITION_ID' => $exhibition['ID']
    ));
    $req_obj = new DR($exhibition['ID']);
    $wish_obj = new DWL($exhibition['ID']);
	$strReq = '';
    while ($request = $rsRequests->Fetch()) {
        // Отменяем запрос
        $req_obj->timeoutRequest($request);
		$arTmpStr = '';
		$arTmpStr .= 'Встреча от '.$request['SENDER_ID'].' к '.$request['RECEIVER_ID']."\n";
		$arTmpStr .= 'ID встречи - '.$request['ID']."\n";
		$arTmpStr .= 'ID таймслота - '.$request['TIMESLOT_ID'];
		$strReq .= $arTmpStr."\n********\n";
		echo "<pre>";echo $arTmpStr; echo "</pre>";
		
        // Добавляем компанию в вишлист
        $fields = array(
            'REASON' => DWL::REASON_TIMEOUT,
            'SENDER_ID' => $request['SENDER_ID'],
            'RECEIVER_ID' => $request['RECEIVER_ID']
        );
        $wish_obj->Add($fields);

        $timeslot = $req_obj->getTimeslot($request['TIMESLOT_ID']);
        // Берем доп данные по пользователям для почтовых событий
        $senderType = $req_obj->getUserTypeById($request['SENDER_ID']);
        if($senderType == 'GUEST'){
            $sender = $req_obj->getUserInfo($request['SENDER_ID']);
            $receiver = $req_obj->getUserInfoFull($request['RECEIVER_ID'], $userInfo[$exhibition['ID']]["FORM"], $userInfo[$exhibition['ID']]["PROP_NAME"], $userInfo[$exhibition['ID']]["FIO"]);
        }
        else{
            $sender = $req_obj->getUserInfoFull($request['SENDER_ID'], $userInfo[$exhibition['ID']]["FORM"], $userInfo[$exhibition['ID']]["PROP_NAME"], $userInfo[$exhibition['ID']]["FIO"]);
            $receiver = $req_obj->getUserInfo($request['RECEIVER_ID']);
        }
        
        $mail_fields = array(
            'EMAIL' => $sender['email'],
            'BCC' => $receiver['email'],
            'TIMESLOT_NAME' => $timeslot['name'],
            'SENDER_COMPANY' => $sender['company_name'],
            // 'SENDER_REP' => $sender['repr_name'],
            'RECEIVER_COMPANY' => $receiver['company_name'],
            // 'RECEIVER_REP' => $receiver['repr_name'],
            "EXIB_NAME" => $arResult["PARAM_EXHIBITION"]["NAME"],
            "EXIB_SHORT" => $arResult["PARAM_EXHIBITION"]["PROPERTIES"]["V_EN"]["VALUE"]
        );

        CEvent::Send($exhibition['EVENT_TIMEOUT'], "s1", $mail_fields);
    }
	
}
$mailto = "diana_box@list.ru";
    $mail = "Обработка встреч с сайта Luxury\n".$strReq;
    mail($mailto,"Cron с сайта Luxury",$mail,"Content-Type: text/plain; charset=windows-1251\r\n");
?>