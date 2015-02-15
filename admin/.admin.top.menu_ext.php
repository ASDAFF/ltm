<?
$iblockId = 15;

if(CModule::IncludeModule('iblock')) {
    $aMenuLinksExt = array();

    $arAdditionalLinksMenuHB = array(
        "��������� ��������������"=>"participant/on/",
        "��������� ����������������"=>"participant/off/",
        "��������� �������"=>"participant/matrix/",
        "��������� ������� HB"=>"participant/matrix_hb/",
        "��������� ����"=>"participant/spam/",
        "����� ����"=>"guest/morning/",
        "����� �����"=>"guest/evening/",
        "����� ��"=>"guest/hostbuy/",
        "����� ����������������"=>"guest/off/",
        "����� ����"=>"guest/spam/",
        "����� �������"=>"guest/matrix/",
        "����� ������� HB"=>"guest/matrix_hb/",
    );
    $arAdditionalLinksMenu = array(
        "��������� ��������������"=>"participant/on/",
        "��������� ����������������"=>"participant/off/",
        "��������� �������"=>"participant/matrix/",
        "��������� ����"=>"participant/spam/",
        "����� ����"=>"guest/morning/",
        "����� �����"=>"guest/evening/",
        "����� ��"=>"guest/hostbuy/",
        "����� ����������������"=>"guest/off/",
        "����� ����"=>"guest/spam/",
        "����� �������"=>"guest/matrix/",
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