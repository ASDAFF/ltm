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
        $formId = $exhibition['FORM_ID'];
        $propertyNameParticipant = $exhibition['FORM_RES_CODE'];//свойство участника
        $fio_datesPart = array();
        $fio_datesPart[0][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_446', $formId);//Имя участника
        $fio_datesPart[0][1] = CFormMatrix::getAnswerRelBase(84 ,$formId);
        $fio_datesPart[1][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_551', $formId);//Фамилия участника
        $fio_datesPart[1][1] = CFormMatrix::getAnswerRelBase(85 ,$formId);
        $fio_datesPart[2][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_859', $formId);//Email участника
        $fio_datesPart[2][1] = CFormMatrix::getAnswerRelBase(89 ,$formId);
        $HB_TEG = '';
        if($exhibition['IS_HB']){
            $HB_TEG = ' HB session';
        }

        if($senderType == 'GUEST'){
            $sender = $req_obj->getUserInfo($request['SENDER_ID']);
            $receiver = $req_obj->getUserInfoFull($request['RECEIVER_ID'], $formId, $propertyNameParticipant, $fio_datesPart);
        }
        else{
            $sender = $req_obj->getUserInfoFull($request['SENDER_ID'], $formId, $propertyNameParticipant, $fio_datesPart);
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
if($strReq != ''){
    $mailto = "diana_box@list.ru";
    $mail = "Обработка встреч с сайта Luxury\n".$strReq;
    mail($mailto,"Cron с сайта Luxury",$mail,"Content-Type: text/plain; charset=windows-1251\r\n");
}
?>