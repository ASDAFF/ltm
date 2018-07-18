<?php

namespace Spectr\Meeting\Models;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;

class CompanyScheduleTable extends DataManager
{
    public static function getTableName(): string
    {
        return "meetings_company_schedule";
    }

    public static function getMap(): array
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new IntegerField('USER_ID'),
            new IntegerField('STATUS'),
            new IntegerField('VISITOR_ID'),
            new IntegerField('EXHIBITION_ID'),
        ];
    }
}