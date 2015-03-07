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

//������ �������� �� ���������� �� id �������� �� ������
$arResult = array();
$fio_dates = array();
$arResult["EXHIB"] = array();
$rs = CIBlockElement::GetList(array("SORT"=>"ASC"),
        array("IBLOCK_ID"=>$arParams["IBLOCK_ID_EXHIB"], "ACTIVE"=>"Y"), false, false,
        array("ID", "IBLOCK_ID", "NAME", "CODE", "PROPERTY_STATUS", "PROPERTY_STATUS_G_M", "PROPERTY_USER_GROUP_ID", "PROPERTY_C_GUESTS_GROUP", "PROPERTY_APP_ID", "PROPERTY_V_EN", "PROPERTY_APP_HB_ID"));
while($ar = $rs->Fetch()) {
    $arResult["EXHIB"][$ar["PROPERTY_APP_ID_VALUE"]] = $ar;
    if($ar["PROPERTY_APP_HB_ID_VALUE"]){
        $arResult["EXHIB"][$ar["PROPERTY_APP_HB_ID_VALUE"]] = $ar;
    }
}


// ������ �������� �� ������ � ����������� ���������
$rsExhibitions = DS::GetList(array(), array("ACTIVE" => 1)); //�������� "IS_LOCKED" => 0

while ($exhibition = $rsExhibitions->Fetch()) {
        $req_obj = new DR($exhibition['ID']);
        $wishlist_obj = new DWL($exhibition['ID']);
        $arResult["MAIL_LIST"][$exhibition['ID']] = array();
        $arResult["MAIL_LIST"][$exhibition['ID']]["PARTICIP_IN"] = array();//������ � ����������� ������� ����� ������� ��������� � ��������� ������ (�������� ��� INFO � ������� � ������� ���������)
        $arResult["MAIL_LIST"][$exhibition['ID']]["GUEST_IN"] = array();
        $formId = $exhibition['FORM_ID'];
        $propertyNameParticipant = $exhibition['FORM_RES_CODE'];//�������� ���������
        $fio_datesPart = array();
        $fio_datesPart[0][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_446', $formId);//��� ���������
        $fio_datesPart[0][1] = CFormMatrix::getAnswerRelBase(84 ,$formId);
        $fio_datesPart[1][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_551', $formId);//������� ���������
        $fio_datesPart[1][1] = CFormMatrix::getAnswerRelBase(85 ,$formId);
        $fio_datesPart[2][0] = CFormMatrix::getSIDRelBase('SIMPLE_QUESTION_859', $formId);//Email ���������
        $fio_datesPart[2][1] = CFormMatrix::getAnswerRelBase(89 ,$formId);
        $HB_TEG = '';
        if($exhibition['IS_HB']){
            $HB_TEG = ' HB session';
        }
       
        //������ ��������� ���������� � ������
        $freeParticip = $req_obj->getUsersFreeTimesByGroup($exhibition["MEMBERS_GROUP"]);
        $freeGuest = $req_obj->getUsersFreeTimesByGroup($exhibition["GUESTS_GROUP"]);
        
        foreach($freeParticip as $personID => $personInfo) {
            $wishListIn = $wishlist_obj->getWishlists($personID); //������ ����� � ������� ���� ������� � ��������
            foreach ($wishListIn["WISH_IN"] as $key => $personWish) {
                if(isset($freeGuest[$key])){
                    $result = array_diff ($personInfo["TIMES"], $freeGuest[$key]["TIMES"]);
                    if ($result != $personInfo["TIMES"]) {
                        $arResult["MAIL_LIST"][$exhibition['ID']]["PARTICIP_IN"][$personID]["LIST"][$key] = $personWish;
                        $arResult["MAIL_LIST"][$exhibition['ID']]["PARTICIP_IN"][$personID]["INFO"] = $req_obj->getUserInfoFull($personID, $formId, $propertyNameParticipant, $fio_datesPart);
                    }
                }
            }
        }
        
        //������ ��������� ������
        foreach($freeGuest as $personID => $personInfo) {
            $wishListIn = $wishlist_obj->getWishlists($personID); //������ ����� � ������� ���� ������� � ��������
            foreach ($wishListIn["WISH_IN"] as $key => $personWish) {
                if(isset($freeParticip[$key])){
                    $result = array_diff ($personInfo["TIMES"], $freeParticip[$key]["TIMES"]);
                    if ($result != $personInfo["TIMES"]) {
                        $arResult["MAIL_LIST"][$exhibition['ID']]["GUEST_IN"][$personID]["LIST"][$key] = $personWish;
                        $arResult["MAIL_LIST"][$exhibition['ID']]["GUEST_IN"][$personID]["INFO"] = $req_obj->getUserInfo($personID);
                    }
                }
            }
        }
        /* ������� ��������� �� �������� */
        foreach ($arResult["MAIL_LIST"][$exhibition['ID']]["PARTICIP_IN"] as $userId => $userInfo) {
            $arFieldsMes = array(
                "EXIB" => $arResult["EXHIB"][$exhibition['ID']]["PROPERTY_V_EN_VALUE"].$HB_TEG,
                "EMAIL" => $userInfo["INFO"]["email"],
                "COMPANY" => "",
            );
            foreach ($userInfo["LIST"] as $key => $value) {
                $arFieldsMes["COMPANY"] = $value["company_name"];
                CEvent::Send("FREE_FROM_WISHLIST","s1",$arFieldsMes);
            }        
        }
        foreach ($arResult["MAIL_LIST"][$exhibition['ID']]["GUEST_IN"] as $userId => $userInfo) {
            $arFieldsMes = array(
                "EXIB" => $arResult["EXHIB"][$exhibition['ID']]["PROPERTY_V_EN_VALUE"].$HB_TEG,
                "EMAIL" => $userInfo["INFO"]["email"],
                "COMPANY" => "",
            );
            foreach ($userInfo["LIST"] as $key => $value) {
                $arFieldsMes["COMPANY"] = $value["company_name"];
                CEvent::Send("FREE_FROM_WISHLIST","s1",$arFieldsMes);
            }        
        }
}
echo "<pre>"; print_r($arResult["MAIL_LIST"]); echo "</pre>";

?>

