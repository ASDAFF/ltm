<? if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?
$MESS['ERROR_EMPTY_USER_TYPE']   = 'Тип юзера не указан';
$MESS['ERROR_EMPTY_USER_ID']     = 'Юзер не авторизован';
$MESS['ERROR_EMPTY_TIMESLOT_ID'] = 'Таймслот не задан';
$MESS['ERROR_WRONG_RECEIVER_ID'] = 'Получатель не указан';


$MESS['PARTICIP_WRONG_TIMESLOT_ID'] = 'Таймслот не существует';
$MESS['GUEST_WRONG_TIMESLOT_ID']    = 'Таймслот не существует';
$MESS['ADMIN_WRONG_TIMESLOT_ID']    = 'Таймслот не существует';

$MESS['PARTICIP_WRONG_SENDER_ID'] = 'Отправитель не существует';
$MESS['GUEST_WRONG_SENDER_ID']    = 'Отправитель не существует';
$MESS['ADMIN_WRONG_SENDER_ID']    = 'Отправитель не существует';

$MESS['PARTICIP_WRONG_RECEIVER_ID'] = 'Получатель не существует';
$MESS['GUEST_WRONG_RECEIVER_ID']    = 'Получатель не существует';
$MESS['ADMIN_WRONG_RECEIVER_ID']    = 'Получатель не существует';


$MESS['ERROR_GROUP_SENDER'] = 'Группа отправителя не соответствует настройкам';
$MESS['ERROR_STATUS']       = 'Неверный статус запроса';


$MESS['PARTICIP_REQUEST_NOT_FOUND'] = 'REQUEST NOT FOUND';
$MESS['ADMIN_REQUEST_NOT_FOUND']    = 'REQUEST NOT FOUND';
$MESS['GUEST_REQUEST_NOT_FOUND']    = 'Запрос не найден';

$MESS['ERROR_WRONG_SENDER_RIGHTS']   = "You can't work with meeting requests";
$MESS['ERROR_WRONG_RECEIVER_RIGHTS'] = "You can't work with meeting requests";

$MESS['ERROR_APPOINTMENT_LOCKED'] = 'Назначение встреч заблокировано администрацией';
?>