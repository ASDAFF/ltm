<?php

namespace Spectr\Meeting\Models;


use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
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
                            return self::$types[array_search($value, self::$types)];
                        }
                    ];
                }
            ]),
            new IntegerField('EXHIBITION_ID'),
            new ReferenceField('SENDER_USER', 'Bitrix\Main\UserTable', [
                '=this.SENDER_ID' => 'ref.ID',
            ]),
            new ReferenceField('RECEIVER_USER', 'Bitrix\Main\UserTable', [
                '=this.RECEIVER_ID' => 'ref.ID',
            ]),
        ];
    }

    public static function getWishlistFromUser(int $userId, int $exhibId): array
    {
        $wishlist = self::getList([
            'filter' => [
                'SENDER_ID' => $userId,
                'EXHIBITION_ID' => $exhibId,
                '!=REASON' => false,
            ],
        ]);
        while ($item = $wishlist->fetch()) {

        }
        return $wishlist->fetchAll();
    }
}