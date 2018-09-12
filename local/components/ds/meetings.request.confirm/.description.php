<?
if ( !defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    'NAME'        => Loc::getMessage('COMPONENT_NAME'),
    'DESCRIPTION' => '',
    'ICON'        => '/images/icon.gif',
    'SORT'        => 10,
    'CACHE_PATH'  => 'Y',
    'PATH'        => ['ID' => 'ds'],
    'COMPLEX'     => 'N',
];
