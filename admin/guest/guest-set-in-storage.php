<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent(
    'ds:guest.store',
    '',
    [
        'MOVE_TO' => MOVE_TO_STORAGE,
    ]
);