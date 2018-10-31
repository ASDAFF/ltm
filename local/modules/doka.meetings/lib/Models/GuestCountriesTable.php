<?php

namespace Spectr\Meeting\Models;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;


class GuestCountriesTable extends DataManager
{
    public static function getTableName(): string
    {
        return "ltm_guest_countries";
    }

    /**
     * @throws \Exception
     */
    public static function getMap(): array
    {
        return [
            new IntegerField('ID', [
                'primary'      => true,
                'autocomplete' => true,
            ]),
            new StringField('UF_VALUE'),
            new StringField('UF_XML_ID'),
        ];
    }

}