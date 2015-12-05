<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
$dataRes = array("error"=>'', "text"=>"");
global $USER_FIELD_MANAGER;
if (!CModule::IncludeModule("highloadblock")):
    $dataRes["error"] = "Нет модуля сообщений";
    return 0;
elseif (!$USER->IsAuthorized()):
    $dataRes["error"] = "Необходимо авторизоваться";
    return 0;
endif;

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
$arParams["HLID"] = 2;

$hlblock = HL\HighloadBlockTable::getById($arParams["HLID"])->fetch();
// получаем сущность
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$HLDataClass = $entity->getDataClass();


switch ($_REQUEST["action"]){
    case "read":
        foreach($_REQUEST["mess"] as $message){
            $arMessage["UF_IS_READ"] =  1;
            $arMessage["ID"] = $message;
            $USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$hlblock['ID'], null, $arMessage);
            $result = $HLDataClass::Update($arMessage["ID"], $arMessage);
            if(!$result->isSuccess()){
                $dataRes["error"] = "Error";
            }
        }
        break;
    case "unread":
        foreach($_REQUEST["mess"] as $message){
            $arMessage["UF_IS_READ"] =  '';
            $arMessage["ID"] = $message;
            $USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$hlblock['ID'], null, $arMessage);
            $result = $HLDataClass::Update($arMessage["ID"], $arMessage);
            if(!$result->isSuccess()){
                $dataRes["error"] = "Error";
            }
        }
        break;
    case "delete":
        foreach($_REQUEST["mess"] as $message){
            $HLDataClass::delete($message);
        }
        $dataRes["text"] = "Y";
        break;
    default:
        $dataRes["error"] = "Нельзя выполнить требуемое действие";
        break;
}
echo json_encode($dataRes);
?>