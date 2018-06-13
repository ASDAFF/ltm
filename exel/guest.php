<?php
define('NEED_AUTH', true);
//if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Context;
$request = Context::getCurrent()->getRequest();
$type = $request->get('GUEST_TYPE') ?: 'EVENING';
$format_type = $request->get('FORMAT_TYPE') ?: 'COMPANY';
$exhibition_id = $request->get('EXHIBITION_ID');
$show_fields = [];
if ($format_type === 'COMPANY') {
    switch ($type) {
        case 'HB':
            $show_fields = [
                'USER_LOGIN',
                'USER_UF_PAS',
                'USER_ID',
                'UF_COMPANY',
                'UF_NAME',
                'UF_SURNAME',
                'UF_SALUTATION',
                'UF_POSITION',
                'UF_EMAIL',
                'UF_PHONE',
                'UF_MOBILE',
                'UF_SKYPE',
                'UF_COLLEAGUES_UF_NAME',
                'UF_COLLEAGUES_UF_SURNAME',
                'UF_COLLEAGUES_UF_SALUTATION',
                'UF_COLLEAGUES_UF_JOB_TITLE',
                'UF_COLLEAGUES_UF_EMAIL',
                'UF_COLLEAGUES_UF_MOBILE_PHONE',
                'UF_ADDRESS',
                'UF_CITY',
                'UF_COUNTRY',
                'UF_POSTCODE',
                'UF_DESCRIPTION',
                'UF_PRIORITY_AREAS',
                'Приоритетные направления' => [
                    'UF_NORTH_AMERICA',
                    'UF_EUROPE',
                    'UF_SOUTH_AMERICA',
                    'UF_AFRICA',
                    'UF_ASIA',
                    'UF_OCEANIA',
                ],
                'UF_SITE',
                'UF_HOTEL',
                'UF_HALL',
                'UF_TABLE',
            ];
            break;
        case 'EVENING':
            $show_fields = [
                'USER_ID',
                'UF_COMPANY',
                'UF_NAME',
                'UF_SURNAME',
                'UF_SALUTATION',
                'UF_POSITION',
                'UF_EMAIL',
                'UF_PHONE',
                'UF_MOBILE',
                'UF_SKYPE',
                'UF_COLLEAGUES_UF_NAME',
                'UF_COLLEAGUES_UF_SURNAME',
                'UF_COLLEAGUES_UF_SALUTATION',
                'UF_COLLEAGUES_UF_JOB_TITLE',
                'UF_COLLEAGUES_UF_EMAIL',
                'UF_COLLEAGUES_UF_MOBILE_PHONE',
                'UF_ADDRESS',
                'UF_CITY',
                'UF_COUNTRY',
                'UF_POSTCODE',
                'UF_PRIORITY_AREAS',
                'UF_SITE',
                'UF_HOTEL',
            ];
            break;
        default:
            $show_fields = [
                'USER_LOGIN',
                'USER_UF_PAS',
                'USER_ID',
                'UF_COMPANY',
                'UF_NAME',
                'UF_SURNAME',
                'UF_SALUTATION',
                'UF_POSITION',
                'UF_EMAIL',
                'UF_PHONE',
                'UF_MOBILE',
                'UF_SKYPE',
                'UF_COLLEAGUES_UF_NAME',
                'UF_COLLEAGUES_UF_SURNAME',
                'UF_COLLEAGUES_UF_SALUTATION',
                'UF_COLLEAGUES_UF_JOB_TITLE',
                'UF_COLLEAGUES_UF_EMAIL',
                'UF_COLLEAGUES_UF_MOBILE_PHONE',
                'UF_ADDRESS',
                'UF_CITY',
                'UF_COUNTRY',
                'UF_POSTCODE',
                'UF_DESCRIPTION',
                'UF_PRIORITY_AREAS',
                'Приоритетные направления' => [
                    'UF_NORTH_AMERICA',
                    'UF_EUROPE',
                    'UF_SOUTH_AMERICA',
                    'UF_AFRICA',
                    'UF_ASIA',
                    'UF_OCEANIA',
                ],
                'UF_SITE',
                'UF_HOTEL',
            ];
            break;
    }
} elseif ($format_type === 'PEOPLE') {
    switch ($type) {
        case 'HB':
            $show_fields = [
                'USER_LOGIN',
                'USER_UF_PAS',
                'USER_ID',
                'UF_COMPANY',
                'UF_NAME',
                'UF_SURNAME',
                'UF_SALUTATION',
                'UF_POSITION',
                'UF_EMAIL',
                'UF_PHONE',
                'UF_MOBILE',
                'UF_SKYPE',
                'UF_ADDRESS',
                'UF_CITY',
                'UF_COUNTRY',
                'UF_POSTCODE',
                'UF_DESCRIPTION',
                'UF_PRIORITY_AREAS',
                'Приоритетные направления' => [
                    'UF_NORTH_AMERICA',
                    'UF_EUROPE',
                    'UF_SOUTH_AMERICA',
                    'UF_AFRICA',
                    'UF_ASIA',
                    'UF_OCEANIA',
                ],
                'UF_SITE',
                'UF_HOTEL',
                'UF_HALL',
                'UF_TABLE',
            ];
            break;
        case 'EVENING':
            $show_fields = [
                'USER_ID',
                'UF_COMPANY',
                'UF_NAME',
                'UF_SURNAME',
                'UF_SALUTATION',
                'UF_POSITION',
                'UF_EMAIL',
                'UF_PHONE',
                'UF_MOBILE',
                'UF_SKYPE',
                'UF_ADDRESS',
                'UF_CITY',
                'UF_COUNTRY',
                'UF_POSTCODE',
                'UF_PRIORITY_AREAS',
                'UF_SITE',
                'UF_HOTEL',
            ];
            break;
        default:
            $show_fields = [
                'USER_LOGIN',
                'USER_UF_PAS',
                'USER_ID',
                'UF_COMPANY',
                'UF_NAME',
                'UF_SURNAME',
                'UF_SALUTATION',
                'UF_POSITION',
                'UF_EMAIL',
                'UF_PHONE',
                'UF_MOBILE',
                'UF_SKYPE',
                'UF_ADDRESS',
                'UF_CITY',
                'UF_COUNTRY',
                'UF_POSTCODE',
                'UF_DESCRIPTION',
                'UF_PRIORITY_AREAS',
                'Приоритетные направления' => [
                    'UF_NORTH_AMERICA',
                    'UF_EUROPE',
                    'UF_SOUTH_AMERICA',
                    'UF_AFRICA',
                    'UF_ASIA',
                    'UF_OCEANIA',
                ],
                'UF_SITE',
                'UF_HOTEL',
            ];
            break;
    }
}else{
    exit('Нужен правильный тип');
}


$APPLICATION->IncludeComponent(
    'ds:xlsx.generator',
    '',
    [
        'EXHIBITION_ID' => $exhibition_id,
        'GUEST_TYPE' => $type, // [MORNING, EVENING, HB, SPAM, UNCONFIRMED]
        'FORMAT_TYPE' => $format_type, // [COMPANY, PEOPLE]
        'REGISTER_GUEST_ENTITY_ID' => REGISTER_GUEST_ENTITY_ID,
        'REGISTER_GUEST_COLLEAGUES_ENTITY_ID' => REGISTER_GUEST_COLLEAGUES_ENTITY_ID,
        'SHOW_FIELDS_IN_FILE' => $show_fields
    ]
);