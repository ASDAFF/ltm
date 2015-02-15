<?

//Подключаем API битрикса
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

//Отключаем статистику Bitrix
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

//Создаём массив с любой служебной информацией, в нашем случае это массив с параметрами запроса
$header = array(
    'REQUEST' => $_REQUEST
);

//Объявляем переменную в которую будем передавать результат работы
$json = array();

//Обрабатываем некое действие
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

//Возвращаем результат
echo json_encode($json);
?>
