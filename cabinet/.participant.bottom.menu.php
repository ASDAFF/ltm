<?
$aMenuLinks = array();
$exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);
$iblockId = 15;

if($exhibCode)
{
    $aMenuLinks = array(
      array(
        "Deadlines & info",
        "/cabinet/" . $exhibCode . "/deadline/",
        array(),
        array()
      ),
      array(
        "Morning session",
        "/cabinet/" . $exhibCode . "/morning/",
        array(),
        array()
      ),
      array(
        "Hosted Buyers Session",
        "/cabinet/" . $exhibCode . "/hb/",
        array(),
        array()
      ),
      array(
        "Evening session",
        "/cabinet/" . $exhibCode . "/evening/",
        array(),
        array()
      ),
      array(
        "Messages",
        "/cabinet/" . $exhibCode . "/messages/",
        array(),
        array()
      ),
    );

    $hideHB = false;
    if(CModule::IncludeModule('iblock')) {
        $rs = CIblockElement::GetList(array("SORT"=>"ASC"),
          array("IBLOCK_ID"=>$iblockId, "ACTIVE"=>"Y", "CODE" => $exhibCode), false, false,
          array("ID", "NAME", "CODE", "PROPERTY_TAB_TITLE", "PROPERTY_USER_GROUP_ID", "PROPERTY_IN_MENU", "PROPERTY_HB_EXIST"));
        while($arItem = $rs->Fetch()) {
            if($arItem["PROPERTY_HB_EXIST_VALUE"] != 'Y') {
                $hideHB = true;
            }
        }
    }

    if($hideHB) {
        unset($aMenuLinks[2]);
    }
}
?>