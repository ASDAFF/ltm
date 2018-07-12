<?php

namespace Spectr\Meeting\Models;


use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;

class SettingsTable extends DataManager
{

    public static function getTableName()
    {
        return "meetings_settings";
    }

    public static function getMap()
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new StringField('NAME'),
            new StringField('CODE'),
            new BooleanField('IS_LOCKED'),
            new BooleanField('ACTIVE'),
            new IntegerField('GUESTS_GROUP'),
            new BooleanField('IS_GUEST'),
            new BooleanField('IS_HB'),
            new IntegerField('MEMBERS_GROUP'),
            new IntegerField('ADMINS_GROUP'),
            new StringField('EVENT_REJECT'),
            new StringField('EVENT_SENT'),
            new StringField('EVENT_TIMEOUT'),
            new IntegerField('REPR_PROP_ID'),
            new StringField('REPR_PROP_CODE'),
            new IntegerField('FORM_ID'),
            new StringField('FORM_RES_CODE'),
            new IntegerField('TIMEOUT_VALUE'),
        ];
    }

}