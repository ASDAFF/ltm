<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
switch ($arResult['USER_TYPE_NAME']) {
    case 'PARTICIPANT':
        include_once(dirname(__FILE__) . '/participant.php');
        break;
    case 'GUEST':
        include_once(dirname(__FILE__) . '/guest.php');
        break;
}

?>

