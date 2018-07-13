<?php

namespace Spectr\Meeting\Models;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use CUtil;

class TimeslotTable extends DataManager
{

    const TYPE_MEET = 1;
    const TYPE_COFFEE = 2;
    const TYPE_LUNCH = 4;
    const TYPE_FREE = 8;

    public static $types = [
        self::TYPE_MEET => 'meet',
        self::TYPE_COFFEE => 'coffee',
        self::TYPE_LUNCH => 'lunch',
        self::TYPE_FREE => 'free',
    ];

    public static function getTableName(): string
    {
        return "meetings_timeslots";
    }

    public static function getMap(): array
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new IntegerField('EXHIBITION_ID'),
            new IntegerField('SORT'),
            new StringField('NAME'),
            new IntegerField('TIME_FROM'),
            new IntegerField('TIME_TO'),
            new StringField('SLOT_TYPE', [
                'save_data_modification' => function () {
                    return [
                        function ($value) {
                            return self::$types[$value];
                        },
                    ];
                },
                'fetch_data_modification' => function () {
                    return [
                        function ($value) {
                            return array_search($value, self::$types);
                        },
                    ];
                },
            ]),
        ];
    }

    public static function getTypes(int $idType = null): array
    {
        if (is_null($idType) || !isset(self::$types[$idType])) {
            return self::$types;
        } else {
            return self::$types[$idType];
        }
    }

    public static function getHtmlInput(int $currentValue, string $exhibCode = "EXHIBITION_ID", string $timeslotCode = "TIMESLOT_ID", array $filterExhib = [], array $filterTimeslot = []): string
    {
        $html = '';
        $randString = randString();
        $exhibitionList = SettingsTable::getList([
            'filter' => $filterExhib,
        ])->fetchAll();
        $timeslotList = self::getList([
            'filter' => $filterTimeslot,
        ])->fetchAll();
        $timeslotListFormated = [];
        foreach ($timeslotList as $timeslot) {
            $timeslotListFormated[$timeslot['EXHIBITION_ID']][] = $timeslot;
        }
        unset($timeslotList);
        $html .= '<script type="text/javascript">
        function OnType_' . $randString . '_Changed(typeSelect, iblockSelectID)
        {
            var arSecondSelectVals = ' . CUtil::PhpToJSObject($timeslotListFormated) . ';
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
        </script>';

        $htmlexhibCode = htmlspecialcharsbx($exhibCode);
        $htmltimeslotCode = htmlspecialcharsbx($timeslotCode);
        $onChangeType = 'OnType_' . $randString . '_Changed(this, \'' . CUtil::JSEscape($timeslotCode) . '\');';
        $html .= '<select name="' . $htmlexhibCode . '" id="' . $htmlexhibCode . '" onchange="' . htmlspecialcharsbx($onChangeType) . '">' . "\n";
        foreach ($exhibitionList as $key => $value) {
            if ($currentValue === false)
                $currentValue = $key;
            $html .= '<option value="' . htmlspecialcharsbx($key) . '"' . ($currentValue === $key ? ' selected' : '') . '>' . htmlspecialcharsEx($value) . '</option>' . "\n";
        }
        $html .= "</select>\n";
        $html .= "&nbsp;\n";
        $html .= '<select name="' . $htmltimeslotCode . '" id="' . $htmltimeslotCode . '">' . "\n";
        foreach ($timeslotListFormated[$currentValue] as $key => $value) {
            $html .= '<option value="' . htmlspecialcharsbx($key) . '"' . ($currentValue == $key ? ' selected' : '') . '>' . htmlspecialcharsEx($value) . '</option>' . "\n";
        }
        $html .= "</select>\n";
        return $html;
    }

}