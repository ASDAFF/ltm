<? if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
    <div class="modal-window ">

    </div>
<?
switch ($arResult['USER_TYPE_NAME']) {
    case 'ADMIN':
    case 'PARTICIPANT':
        include_once(dirname(__FILE__).'/participant.php');
        break;
    case 'GUEST':
        include_once(dirname(__FILE__).'/guest.php');
}
?>