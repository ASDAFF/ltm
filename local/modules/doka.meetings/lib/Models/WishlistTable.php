<?php

namespace Spectr\Meeting\Models;


use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;

class WishlistTable extends DataManager
{
    const REASON_EMPTY = 0;
    const REASON_REJECTED = 1;
    const REASON_TIMEOUT = 2;
    const REASON_SELECTED = 3;

    static $types = [
        self::REASON_EMPTY => 'empty',
        self::REASON_REJECTED => 'rejected',
        self::REASON_TIMEOUT => 'timeout',
        self::REASON_SELECTED => 'selected',
    ];

    public static function getTableName(): string
    {
        return "meetings_wishlist";
    }

    public static function getMap(): array
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new IntegerField('SENDER_ID'),
            new IntegerField('RECEIVER_ID'),
            new DatetimeField('CREATED_AT'),
            new StringField('REASON', [
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
                            return self::$types[array_search($value, self::$types)];
                        },
                    ];
                },
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
        return self::getList([
            'select' => [
                'ID',
                'COMPANY_NAME' => 'RECEIVER_USER.WORK_COMPANY',
                'REASON',
                'COMPANY_REP',
            ],
            'filter' => [
                'SENDER_ID' => $userId,
                'EXHIBITION_ID' => $exhibId,
            ],
            'runtime' => [
                new ExpressionField('COMPANY_REP',
                    'CONCAT(%s, " ", %s)', ['RECEIVER_USER.NAME', 'RECEIVER_USER.LAST_NAME']),
            ],
        ])->fetchAll();
    }

    public static function getWishlistForUser(int $userId, int $exhibId): array
    {
        return self::getList([
            'select' => [
                'ID',
                'COMPANY_NAME' => 'SENDER_USER.WORK_COMPANY',
                'COMPANY_REP',
                'REASON',

            ],
            'filter' => [
                'RECEIVER_ID' => $userId,
                'EXHIBITION_ID' => $exhibId,
            ],
            'runtime' => [
                new ExpressionField('COMPANY_REP',
                    'CONCAT(%s, " ", %s)', ['SENDER_USER.NAME', 'SENDER_USER.LAST_NAME']),
            ],
        ])->fetchAll();
    }
}