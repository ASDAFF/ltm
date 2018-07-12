<?php

namespace Spectr\Meeting\Models;


use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;

class WishlistTable extends DataManager
{
    const REASON_EMPTY     = 0;
    const REASON_REJECTED  = 1;
    const REASON_TIMEOUT   = 2;
    const REASON_SELECTED  = 3;

    static $types = array(
        self::REASON_EMPTY    => 'empty',
        self::REASON_REJECTED => 'rejected',
        self::REASON_TIMEOUT  => 'timeout',
        self::REASON_SELECTED => 'selected',
    );

    public static function getTableName() : string
    {
        return "meetings_wishlist";
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
            new DatetimeField('CREATED_AT'),
            new StringField('REASON', [
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