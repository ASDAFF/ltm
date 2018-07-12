<?php

namespace Spectr\Meeting\Models;


use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DateTimeField;

class RequestTable extends DataManager
{

    const STATUS_EMPTY     = 0; // Статус не выбран, т.е. слот свободен
    const STATUS_PROCESS   = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_REJECTED  = 3;
    const STATUS_TIMEOUT   = 4;
    const STATUS_RESERVE   = 5;

    static $types = array(
        self::STATUS_EMPTY     => 'empty',
        self::STATUS_PROCESS   => 'process',
        self::STATUS_CONFIRMED => 'confirmed',
        self::STATUS_REJECTED  => 'rejected',
        self::STATUS_TIMEOUT   => 'timeout',
        self::STATUS_RESERVE   => 'reserve',
    );

    public static function getTableName() : string
    {
        return "meetings_requests";
    }

    public static function getMap() : array
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new IntegerField('SENDER_ID'),
            new IntegerField('RECEIVER_ID'),
            new DateTimeField('CREATED_AT'),
            new DateTimeField('UPDATED_AT'),
            new IntegerField('MODIFIED_BY'),
            new IntegerField('TIMESLOT_ID'),
            new StringField('STATUS', [
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
            new IntegerField('EXHIBITION_ID'),
        ];
    }

}