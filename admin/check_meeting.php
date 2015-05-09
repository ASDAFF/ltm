<? 
$_SERVER["DOCUMENT_ROOT"] = "/home/u24601/luxurytravelmart.ru/www";
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
set_time_limit(0);

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("form") || !CModule::IncludeModule("doka.meetings"))
    {
        $this->AbortResultCache();
        throw new Exception("Can't load modules iblock form");
    }

use Doka\Meetings\Settings as DS;
use Doka\Meetings\Requests as DR;
use Doka\Meetings\Timeslots as DT;
use Doka\Meetings\Wishlists as DWL;

$arParams["IBLOCK_ID_EXHIB"] = 15;

//список выставок из Инфогруппы по id выставки из модуля
$arResult = array();
$fio_dates = array();
$erStrAll = '';
$arResult["EXHIB"] = array();
$rs = CIBlockElement::GetList(array("SORT"=>"ASC"),
        array("IBLOCK_ID"=>$arParams["IBLOCK_ID_EXHIB"], "ACTIVE"=>"Y"), false, false,
        array("ID", "IBLOCK_ID", "NAME", "CODE", "PROPERTY_STATUS", "PROPERTY_STATUS_G_M", "PROPERTY_USER_GROUP_ID", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_APP_ID", "PROPERTY_V_EN"));
while($ar = $rs->Fetch()) {
    $arResult["EXHIB"][$ar["PROPERTY_APP_ID_VALUE"]] = $ar;
}

// список выставок из модуля и составление вишлистов
$rsExhibitions = DS::GetList(array(), array("ACTIVE" => 1)); //добавить "IS_LOCKED" => 0

while ($exhibition = $rsExhibitions->Fetch()) {
	$erStr = '';
    $req_obj = new DR($exhibition['ID']);
    $timeslotsId = $req_obj->getMeetTimeslotsIds();
    $timeslots = $req_obj->getAllMeetTimeslots(); //[id]["name"]
    
    /* Гости */
    $guestTmp = $req_obj->getAllMeetChooseByGroup($exhibition["GUESTS_GROUP"]);
    $guest = array();
    while ($arParticip = $guestTmp->Fetch()) {
    	$guest[$arParticip["USER_ID"]]=array();
    	foreach ($timeslotsId as $key => $timeId) {
    		$guest[$arParticip["USER_ID"]][$timeId]["USER"] = substr($arParticip["MEET_".$timeId], 0, -1);
    		$guest[$arParticip["USER_ID"]][$timeId]["STATUS"] = $arParticip["STATUS_".$timeId];
    	}        
    }

    /* Участники */
    $participTmp = $req_obj->getAllMeetChooseByGroup($exhibition["MEMBERS_GROUP"]);
    $particip = array();
    while ($arParticip = $participTmp->Fetch()) {
    	$particip[$arParticip["USER_ID"]]=array();
    	foreach ($timeslotsId as $key => $timeId) {
    		$userTmp = substr($arParticip["MEET_".$timeId], 0, -1);//для читабельности
    		$statusTmp = $arParticip["STATUS_".$timeId];//для читабельности

    		$particip[$arParticip["USER_ID"]][$timeId]["USER"] = $userTmp;
    		$particip[$arParticip["USER_ID"]][$timeId]["STATUS"] = $statusTmp;
    		/* Сразу же проверяем со стороны участников*/
    		if($userTmp != '' && $guest[$userTmp][$timeId]["USER"] != $arParticip["USER_ID"]){//Если у участника и гостя не совпадают оппоненты, но при этом статусы не Таймаут и не Отменен
    			if($statusTmp != 3 && $statusTmp != 4){
    				$erStr .= '(Diff user) Timeslot '.$timeslots[$timeId]["name"].'('.$timeId.') 
    				Particip to Guest - '.$arParticip["USER_ID"].'-'.$userTmp.'('.$statusTmp.') 
    				Guest to Particip - '.$userTmp.'-'.$guest[$userTmp][$timeId]["USER"].'('.$guest[$userTmp][$timeId]["STATUS"].')<br />';
    			}
    			
    		}
    		elseif($userTmp != '' && $guest[$userTmp][$timeId]["STATUS"] != $statusTmp){//Если оппоненты совпадают, но статусы разные
				$erStr .= '(Diff status) Timeslot '.$timeslots[$timeId]["name"].'('.$timeId.') 
				Particip to Guest - '.$arParticip["USER_ID"].'-'.$userTmp.'('.$statusTmp.') 
				Guest to Particip - '.$userTmp.'-'.$guest[$userTmp][$timeId]["USER"].'('.$guest[$userTmp][$timeId]["STATUS"].')<br />';
    		}
    	}    	
    }
    /* Проверяем со стороны гостей */
    foreach ($guest as $partId => $arParticip) {
        foreach ($timeslotsId as $key => $timeId) {
            $userTmp = $arParticip[$timeId]["USER"];//для читабельности
            $statusTmp = $arParticip[$timeId]["STATUS"];//для читабельности

            if($userTmp != '' && $particip[$userTmp][$timeId]["USER"] != $partId){//Если у участника и гостя не совпадают оппоненты, но при этом статусы не Таймаут и не Отменен
                if($statusTmp != 3 && $statusTmp != 4){
                    $erStr .= '(Diff user) Timeslot '.$timeslots[$timeId]["name"].'('.$timeId.') 
                    Particip to Guest - '.$partId.'-'.$userTmp.'('.$statusTmp.') 
                    Guest to Particip - '.$userTmp.'-'.$particip[$userTmp][$timeId]["USER"].'('.$particip[$userTmp][$timeId]["STATUS"].')<br />';
                }
                
            }
            elseif($userTmp != '' && $particip[$userTmp][$timeId]["STATUS"] != $statusTmp){//Если оппоненты совпадают, но статусы разные
                $erStr .= '(Diff status) Timeslot '.$timeslots[$timeId]["name"].'('.$timeId.') 
                Particip to Guest - '.$partId.'-'.$userTmp.'('.$statusTmp.') 
                Guest to Particip - '.$userTmp.'-'.$particip[$userTmp][$timeId]["USER"].'('.$particip[$userTmp][$timeId]["STATUS"].')<br />';
            }
        }
    }

    if($erStr != ''){
    	$erStrAll .= 'Exibition '.$exhibition['NAME'].'('.$exhibition['ID'].')<br />';
    	$erStrAll .= $erStr;
    }
    else{
    	$erStrAll .= 'Exibition '.$exhibition['NAME'].'('.$exhibition['ID'].')<br />';
    	$erStrAll .= 'It\'s OK<br />';
    }
}
print_r($erStrAll);
//echo "<pre>"; print_r($arResult); echo "</pre>";

?>

