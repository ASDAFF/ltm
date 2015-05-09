<?
/**
 * Р’СЃРїРѕРјРѕРіР°С‚РµР»СЊРЅС‹Рµ СЌР»РµРјРµРЅС‚С‹ РґР»СЏ Р°РґРјРёРЅРєРё
 */

/**
 * Р’С‹Р±РѕСЂ С‚Р°Р№РјСЃР»РѕС‚Р°
 *
 * @param int $SELECTED Selected iblock
 * @param string $strTypeName Name of the iblock type select
 * @param string $strSecondSelectName Name of the iblock name select
 * @param bool $arFilter Additional filter for iblock list
 * @param string $onChangeType Additional JS handler for type select
 * @return string
 */
function DokaGetTimeslotDropDownList($SELECTED, $strTypeName, $strSecondSelectName, $arFilter = false, $onChangeType = '')
{
    $html = '';

    static $arFirstSelectVals = array();
    static $arSecondSelectVals = array();

    if(!is_array($arFilter))
        $arFilter = array();
    $filterId = md5(serialize($arFilter));

    if(!isset($arFirstSelectVals[$filterId]))
    {
        $arFirstSelectVals[$filterId] = array(0 => GetMessage("DOKA_MEET_CHOOSE_EXHIBITION"));
        $arSecondSelectVals[$filterId] = array(0 => array(''=>GetMessage("DOKA_MEET_CHOOSE_TIMESLOT")));


        $rsItems = \Doka\Meetings\Settings::GetList(array(), $arFilter, array('ID', 'NAME'));
        while($arItem = $rsItems->Fetch()) {
            $arFirstSelectVals[$filterId][$arItem["ID"]] = $arItem["NAME"]." [".$arItem["ID"]."]";
        }
        $slot_types = \Doka\Meetings\Entity\Timeslot::getTypes();
        $rsSlots = \Doka\Meetings\Timeslots::GetList(array(), array( 'SLOT_TYPE' => $slot_types[\Doka\Meetings\Entity\Timeslot::TYPE_MEET]), array('ID', 'NAME', 'EXHIBITION_ID'));
        while($arSlot = $rsSlots->Fetch()) {
            $arSecondSelectVals[$filterId][$arSlot["EXHIBITION_ID"]][$arSlot['ID']] = $arSlot["NAME"]." [".$arSlot["ID"]."]";
        }

        $html .= '
        <script type="text/javascript">
        function OnType_'.$filterId.'_Changed(typeSelect, iblockSelectID)
        {
            var arSecondSelectVals = '.CUtil::PhpToJSObject($arSecondSelectVals[$filterId]).';
            var iblockSelect = BX(iblockSelectID);
            if(!!iblockSelect)
            {
                for(var i=iblockSelect.length-1; i >= 0; i--)
                    iblockSelect.remove(i);
                for(var j in arSecondSelectVals[typeSelect.value])
                {
                    var newOption = new Option(arSecondSelectVals[typeSelect.value][j], j, false, false);
                    iblockSelect.options.add(newOption);
                }
            }
        }
        </script>
        ';
    }

    // РћРїСЂРµРґРµР»РёРј РІС‹РґРµР»РµРЅРЅС‹Р№ СЌР»РµРјРµРЅС‚ РІРѕ РІС‚РѕСЂРѕРј СЃРµР»РµРєС‚Рµ
    $SELECTED_KEY = false;
    if($SELECTED > 0) {
        foreach($arSecondSelectVals[$filterId] as $id => $vals)
        {
            if(array_key_exists($SELECTED, $vals))
            {
                $SELECTED_KEY = $id;
                break;
            }
        }
    }

    $htmlTypeName = htmlspecialcharsbx($strTypeName);
    $htmlSecondSelectName = htmlspecialcharsbx($strSecondSelectName);
    $onChangeType = 'OnType_'.$filterId.'_Changed(this, \''.CUtil::JSEscape($strSecondSelectName).'\');';

    $html .= '<select name="'.$htmlTypeName.'" id="'.$htmlTypeName.'" onchange="'.htmlspecialcharsbx($onChangeType).'">'."\n";
    foreach($arFirstSelectVals[$filterId] as $key => $value)
    {
        if($SELECTED_KEY === false)
            $SELECTED_KEY = $key;
        $html .= '<option value="'.htmlspecialcharsbx($key).'"'.($SELECTED_KEY===$key? ' selected': '').'>'.htmlspecialcharsEx($value).'</option>'."\n";
    }
    $html .= "</select>\n";
    $html .= "&nbsp;\n";
    $html .= '<select name="'.$htmlSecondSelectName.'" id="'.$htmlSecondSelectName.'">'."\n";
    foreach($arSecondSelectVals[$filterId][$SELECTED_KEY] as $key => $value)
    {
        $html .= '<option value="'.htmlspecialcharsbx($key).'"'.($SELECTED==$key? ' selected': '').'>'.htmlspecialcharsEx($value).'</option>'."\n";
    }
    $html .= "</select>\n";

    return $html;
}



