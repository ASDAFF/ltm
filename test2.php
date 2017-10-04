<?php
/**
 * Created by PhpStorm.
 * User: Anatoliy Kim
 * Date: 26.09.2017
 * Time: 22:03
 */

if (!$_SERVER['DOCUMENT_ROOT']) {
    $_SERVER['DOCUMENT_ROOT'] = __DIR__;
}

define('PULL_AJAX_INIT', true);
define('PUBLIC_AJAX_MODE', true);
define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);
define('NOT_CHECK_PERMISSIONS', true);
define('DisableEventsCheck', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');

set_time_limit(0);
@ini_set('zlib.output_compression',0);
@ini_set('implicit_flush',1);
@ob_end_clean();

// prolog
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader;
use Ltm\Domain\GuestStorage\Manager as GuestStorageManager;
use Ltm\Domain\GuestStorage\FormResult as LtmFormResult;

if (Loader::includeModule('ltm.domain')) {
    $guestStorageManager = new GuestStorageManager();
    ob_implicit_flush(true);
    echo '<pre>';

    $ltmFormResult = new LtmFormResult();
    $resultList = $ltmFormResult->getResultList();
    foreach($resultList as $resultId)
    {
        $res = $guestStorageManager->addFormResult($resultId);
        if ($res === false) {
            echo $resultId.' failed to add'."\r\n";
        } else {
            echo $resultId.' done. Resulting ID: '.$res."\r\n";
        }
        flush();
        ob_flush();
        ob_end_flush();
    }
    echo '</pre>';
    ob_implicit_flush(false);
}

// epilog
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';