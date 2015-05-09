<?
$iblockId = 15;

if(CModule::IncludeModule('iblock')) {
    $aMenuLinksExt = array();

    $arAdditionalLinksMenuHB = array(
        "Участники Подтвержденные"=>"participant/on/",
        "Участники Неподтвержденные"=>"participant/off/",
        "Участники Матрица"=>"participant/matrix/",
        "Участники Матрица HB"=>"participant/matrix_hb/",
        "Участники Спам"=>"participant/spam/",
        "Гости Утро"=>"guest/morning/",
        "Гости Вечер"=>"guest/evening/",
        "Гости НВ"=>"guest/hostbuy/",
        "Гости Неподтвержденные"=>"guest/off/",
        "Гости Спам"=>"guest/spam/",
        "Гости Матрица"=>"guest/matrix/",
        "Гости Матрица HB"=>"guest/matrix_hb/",
    );
    $arAdditionalLinksMenu = array(
        "Участники Подтвержденные"=>"participant/on/",
        "Участники Неподтвержденные"=>"participant/off/",
        "Участники Матрица"=>"participant/matrix/",
        "Участники Спам"=>"participant/spam/",
        "Гости Утро"=>"guest/morning/",
        "Гости Вечер"=>"guest/evening/",
        "Гости НВ"=>"guest/hostbuy/",
        "Гости Неподтвержденные"=>"guest/off/",
        "Гости Спам"=>"guest/spam/",
        "Гости Матрица"=>"guest/matrix/",
    );

    $rs = CIblockElement::GetList(array("SORT"=>"ASC"),
        array("IBLOCK_ID"=>$iblockId, "ACTIVE" => "Y"), false, false,
        array("ID", "NAME", "CODE", "PROPERTY_menu_ru", "PROPERTY_menu_en"));
    while($ar = $rs->Fetch()) {
        $prefix = "/admin/{$ar["CODE"]}/";
        $aMenuLinksExt[] = array($ar["PROPERTY_MENU_RU_VALUE"], $prefix, array(), array("FROM_IBLOCK"=>1, "IS_PARENT"=>"Y", "DEPTH_LEVEL"=>1), "");
        if($ar["CODE"] == 'moscow-russia-march-12-2015'){
            $linksTmp = $arAdditionalLinksMenuHB;
        }
        else{
            $linksTmp = $arAdditionalLinksMenu;
        }
        foreach($linksTmp as $name=>$postfix) {
            $aMenuLinksExt[] = array($name, $prefix.$postfix, array(), array("FROM_IBLOCK"=>1, "IS_PARENT"=>false, "DEPTH_LEVEL"=>2), "");
        }
    }

    $aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
}

?>