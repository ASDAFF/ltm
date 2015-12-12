<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
$dataRes = array("error"=>'', "text"=>"");
global $USER_FIELD_MANAGER;
if (!CModule::IncludeModule("doka.meetings")):
    $dataRes["error"] = "Нет модуля встреч";
    return 0;
elseif (!$USER->IsAuthorized()):
    $dataRes["error"] = "Необходимо авторизоваться";
    return 0;
endif;

use Doka\Meetings\Requests as DokaRequest;

if(!empty($_REQUEST["app"])){
    $curUser = $USER->GetID();
    foreach($_REQUEST["app"] as $app){
        $req_obj = new DokaRequest($app);
        $dataRes[$app] = $req_obj->getUnconfirmedRequestsTotal($curUser);
    }
}
else{
    $dataRes["error"] = "Необходимо передать параметр выставки";
}
echo json_encode($dataRes);
?>