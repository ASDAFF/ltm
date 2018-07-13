<?php

namespace Spectr\Meeting\Models;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;

class TimeslotTable extends DataManager
{

    const TYPE_MEET = 1;
    const TYPE_COFFEE = 2;
    const TYPE_LUNCH = 4;
    const TYPE_FREE = 8;

    private static $types = [
        self::TYPE_MEET => 'meet',
        self::TYPE_COFFEE => 'coffee',
        self::TYPE_LUNCH => 'lunch',
        self::TYPE_FREE => 'free'
    ];

    public static function getTableName() : string
    {
        return "meetings_timeslots";
    }

    public static function getMap() : array
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new IntegerField('EXHIBITION_ID'),
            new IntegerField('SORT'),
            new StringField('NAME'),
            new IntegerField('TIME_FROM'),
            new IntegerField('TIME_TO'),
            new StringField('SLOT_TYPE', [
                'save_data_modification' => function(){
                    return [
                      function($value){
                        return self::$types[$value];
                      }
                    ];
                },
                'fetch_data_modification' => function(){
                    return [
                        function($value){
                            return array_search($value, self::$types);
                        }
                    ];
                }
            ]),
        ];
    }

    public static function getTypes(){
        return self::$types;
    }

}