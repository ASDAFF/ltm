<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent(
    'ds:guest.store',
    '',
    [
        'REGISTER_GUEST_HLBLOCK_ID' => 15,
        'REGISTER_COLLEAGUES_HLBLOCK_ID' => 18,
        'STORAGE_GUEST_HLBLOCK_ID' => 12,
        'STORAGE_COLLEAGUES_HLBLOCK_ID' => 13,
    ]);