<?
$iblockId = 15;
$aMenuLinks = array();

$userId;
if($USER->IsAdmin() && isset($_REQUEST["UID"])) {
	$userId = intval($_REQUEST["UID"]);
} else {
	$userId = $USER->GetID();
}

$arUserGroups = CUser::GetUserGroup($userId); //группы пользователя


if(CModule::IncludeModule('iblock')) {

    $language = strtoupper(LANGUAGE_ID);
    $rs = CIblockElement::GetList(array("SORT"=>"ASC"),
        array("IBLOCK_ID"=>$iblockId), false, false,
        array("ID", "NAME", "CODE", "PROPERTY_SHORT_NAME", "PROPERTY_USER_GROUP_ID"));
    while($arItem = $rs->Fetch()) {
    	
    	//Проверка на доступ пользователя к этой выставке
    	$confirmedGroupID = $arItem["PROPERTY_USER_GROUP_ID_VALUE"];
    	 
    	if(!in_array($confirmedGroupID, $arUserGroups))
    	{
			continue;
    	}
    	
    	
        $text = $arItem["PROPERTY_SHORT_NAME_VALUE"];
        if(!$text)
        {
            $text = $arItem["NAME"];
        }
        $aMenuLinks[] = array($text, "/cabinet/" . $arItem["CODE"] . "/", array(), array());
    }
}

?>