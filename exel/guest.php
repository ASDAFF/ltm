<?php
//if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$APPLICATION->IncludeComponent(
    'ds:xlsx.generator',
    '',
    [
        'EXHIBITION_ID' => '19485',
        'GUEST_TYPE' => '', // [MORNING, EVENING, HB, SPAM, UNCONFIRMED]
        'FORMAT_TYPE' => 'COMPANY', // [COMPANY, PEOPLE]
        'REGISTER_GUEST_ENTITY_ID' => REGISTER_GUEST_ENTITY_ID,
        'REGISTER_GUEST_COLLEAGUES_ENTITY_ID' => REGISTER_GUEST_COLLEAGUES_ENTITY_ID,
    ]
);