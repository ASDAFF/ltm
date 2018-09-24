<?
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

$arComponentParameters = array(
    'GROUPS'     => [],
    'PARAMETERS' => array(
        'APP_ID'               => [
            'PARENT'  => 'BASE',
            'NAME'    => Loc::getMessage('APP_ID'),
            'TYPE'    => 'STRING',
            'DEFAULT' => '={$_REQUEST["APP_ID"]}',
        ],
        'EXHIBITION_IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME'   => Loc::getMessage('EXHIBITION_IBLOCK_ID'),
            'TYPE'   => 'STRING',
        ]
    ),
);