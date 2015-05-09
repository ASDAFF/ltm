<?

//РџРѕРґРєР»СЋС‡Р°РµРј API Р±РёС‚СЂРёРєСЃР°
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

//РћС‚РєР»СЋС‡Р°РµРј СЃС‚Р°С‚РёСЃС‚РёРєСѓ Bitrix
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

//РЎРѕР·РґР°С‘Рј РјР°СЃСЃРёРІ СЃ Р»СЋР±РѕР№ СЃР»СѓР¶РµР±РЅРѕР№ РёРЅС„РѕСЂРјР°С†РёРµР№, РІ РЅР°С€РµРј СЃР»СѓС‡Р°Рµ СЌС‚Рѕ РјР°СЃСЃРёРІ СЃ РїР°СЂР°РјРµС‚СЂР°РјРё Р·Р°РїСЂРѕСЃР°
$header = array(
    'REQUEST' => $_REQUEST
);

//РћР±СЉСЏРІР»СЏРµРј РїРµСЂРµРјРµРЅРЅСѓСЋ РІ РєРѕС‚РѕСЂСѓСЋ Р±СѓРґРµРј РїРµСЂРµРґР°РІР°С‚СЊ СЂРµР·СѓР»СЊС‚Р°С‚ СЂР°Р±РѕС‚С‹
$json = array();

//РћР±СЂР°Р±Р°С‚С‹РІР°РµРј РЅРµРєРѕРµ РґРµР№СЃС‚РІРёРµ
switch($_REQUEST['action'])
{
    case 'addIBlockElement':
        if(CModule::IncludeModule('iblock')){

        }else{
            $json = array(
                'header' => $header,
                'error' => true,
                'error_msg' => 'Bitrix API error. Unable to include some modules.',
                'error_code' => 002
            );
        };
        break;
    default:
        $json = array(
            'header' => $header,
            'error' => true,
            'error_msg' => 'Do not have an action',
            'error_code' => 001
        );
        break;
};

//Р’РѕР·РІСЂР°С‰Р°РµРј СЂРµР·СѓР»СЊС‚Р°С‚
echo json_encode($json);
?>
