<?
use Bitrix\Main\Loader;
use Ltm\Domain\Service;
use Ltm\Domain\Service\Configuration\Provider\BitrixSettings;

//require_once(realpath("/home/bitrix/ext_www/lib/vendor/autoload.php"));
require_once(realpath(__DIR__."/../../../lib/vendor/autoload.php"));

Loader::includeModule('ltm.domain');
Loader::includeModule('iblock');
Loader::includeModule('highloadblock');

(new Service\Manager())->getContainer()
	->register(new Service\Provider\MySql())
	/*->register(
		new Service\Provider\TemplateEngine(
			new Service\Configuration\TemplateEngine(
				new BitrixSettings("template"))))*/
	->register(
		new Service\Provider\Configuration(
			new Service\Configuration\Configuration(
				new BitrixSettings("configuration_providers"))));

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