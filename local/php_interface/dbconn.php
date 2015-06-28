<?define("BX_CRONTAB_SUPPORT", true);?><?define("BX_CRONTAB_SUPPORT", true);?><?
define("DBPersistent", false);
$DBType = "mysql";
$DBHost = "localhost";


$DBLogin = "root";
$DBPassword = "";
$DBName = "luxurytm";
$DBDebug = false;
$DBDebugToFile = false;

define("DELAY_DB_CONNECT", true);
define("CACHED_b_file", 3600);
define("CACHED_b_file_bucket_size", 10);
define("CACHED_b_lang", 3600);
define("CACHED_b_option", 3600);
define("CACHED_b_lang_domain", 3600);
define("CACHED_b_site_template", 3600);
define("CACHED_b_event", 3600);
define("CACHED_b_agent", 3660);
define("CACHED_menu", 3600);
define("BX_HTTP_AUTH_REALM", 'Luxury Travel Mart Realm');
define("BX_UTF", true);
define("SET NAMES", "utf8");

define("BX_FILE_PERMISSIONS", 0666);
define("BX_DIR_PERMISSIONS", 0777);
@umask(~BX_DIR_PERMISSIONS);
@ini_set("memory_limit", "1024M");
define("BX_DISABLE_INDEX_PAGE", true);

/* session_register("LANG_UI"); 
$_SESSION['LANG_UI'] = 1;
*/
session_start();
if(isset($_REQUEST['lang_ui']))
{
    $_SESSION["LANG_UI"] = ($_REQUEST['lang_ui']=='en'?'en':'ru');
    define(LANGUAGE_ID, $_SESSION["LANG_UI"]);
}

if(!isset($_REQUEST['lang']) && isset($_SESSION["LANG_UI"]))
{
    $lang = ($_SESSION["LANG_UI"]=="en")?"en":"ru";
    define(LANGUAGE_ID, $lang);
}

/*
$lang = 'ru';
if(strpos($_SERVER['REQUEST_URI'],'/en/')!==false) $lang = 'en';
define("LANGUAGE_ID", $lang);
*/
?>
