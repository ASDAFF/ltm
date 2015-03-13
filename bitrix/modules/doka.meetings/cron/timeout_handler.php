<? 
$_SERVER["DOCUMENT_ROOT"] = dirname(dirname(dirname(dirname(__DIR__))));
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("doka.meetings");

use Doka\Meetings\Settings as DS;
use Doka\Meetings\Requests as DR;
use Doka\Meetings\Timeslots as DT;
use Doka\Meetings\Wishlists as DWL;
// Получим список выставок
$rsExhibitions = DS::GetList(array(), array());
while ($exhibition = $rsExhibitions->Fetch()) {
    $timemout_value = $exhibition['TIMEOUT_VALUE'] * 3600; // sec
    // Для каждой выставки отвергаем заявки, если превышен лимит ожидания ответа
    $rsRequests = DR::GetList(array(), array(
        '<=UPDATED_AT' => ConvertTimeStamp(time() - $timemout_value, "FULL", "ru"),
        'STATUS' => DR::STATUS_PROCESS,
        'EXHIBITION_ID' => $exhibition['ID']
    ));
    $req_obj = new DR($exhibition['ID']);
    $wish_obj = new DWL($exhibition['ID']);
    while ($request = $rsRequests->Fetch()) {

        // Отменяем запрос
        $req_obj->timeoutRequest($request);
        // Добавляем компанию в вишлист
        $fields = array(
            'REASON' => DWL::REASON_TIMEOUT,
            'SENDER_ID' => $request['SENDER_ID'],
            'RECEIVER_ID' => $request['RECEIVER_ID']
        );
        $wish_obj->Add($fields);

        $timeslot = $req_obj->getTimeslot($request['TIMESLOT_ID']);
        // Берем доп данные по пользователям для почтовых событий
        $sender = $req_obj->getUserInfo($request['SENDER_ID']);
        $receiver = $req_obj->getUserInfo($request['RECEIVER_ID']);
        $mail_fields = array(
            'EMAIL_TO' => $sender['email'],
            'BCC' => $receiver['email'],
            'TIMESLOT_NAME' => $timeslot['name'],
            'SENDER_COMPANY' => $sender['company_name'],
            // 'SENDER_REP' => $sender['repr_name'],
            'RECEIVER_COMPANY' => $receiver['company_name'],
            // 'RECEIVER_REP' => $receiver['repr_name'],
        );

        CEvent::Send($exhibition['EVENT_TIMEOUT'], SITE_ID, $mail_fields, false);
    }
}

?>

