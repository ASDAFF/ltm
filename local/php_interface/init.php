<?
require $_SERVER["DOCUMENT_ROOT"]."/local/php_interface/include/tools/LuxorConfig.php";

if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/constants.php"))
	require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/constants.php");
if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/functions.php"))
	require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/functions.php");
if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/events.php"))
	require_once($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/events.php");

CModule::AddAutoloadClasses('',array(
'CFormMatrix' => "/local/php_interface/lib/CFormMatrix.php",
'CHandlers' => "/local/php_interface/lib/CHandlers.php",
'CHLMFunctions' => "/local/php_interface/lib/CHLMFunctions.php",
'CLTMFunctions' => "/local/php_interface/lib/CLTMFunctions.php",
'CLTMGuestStorage' => "/local/php_interface/lib/CLTMGuestStorage.php",
));
?>