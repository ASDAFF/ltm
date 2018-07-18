<?php

namespace Spectr\Meeting\Models;


use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DateTimeField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;

class RequestTable extends DataManager
{

    const STATUS_EMPTY = 0; // Статус не выбран, т.е. слот свободен
    const STATUS_PROCESS = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_REJECTED = 3;
    const STATUS_TIMEOUT = 4;
    const STATUS_RESERVE = 5;

    static $statuses = [
        self::STATUS_EMPTY => 'empty',
        self::STATUS_PROCESS => 'process',
        self::STATUS_CONFIRMED => 'confirmed',
        self::STATUS_REJECTED => 'rejected',
        self::STATUS_TIMEOUT => 'timeout',
        self::STATUS_RESERVE => 'reserve',
    ];

    public static function getTableName(): string
    {
        return "meetings_requests";
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
            new DateTimeField('CREATED_AT'),
            new DateTimeField('UPDATED_AT'),
            new IntegerField('MODIFIED_BY'),
            new IntegerField('TIMESLOT_ID'),
            new StringField('STATUS', [
                'save_data_modification' => function () {
                    return [
                        function ($value) {
                            return self::$statuses[$value];
                        },
                    ];
                },
                'fetch_data_modification' => function () {
                    return [
                        function ($value) {
                            return self::$statuses[array_search($value, self::$statuses)];
                        },
                    ];
                },
            ]),
            new IntegerField('EXHIBITION_ID'),
            new ReferenceField('TIMESLOT',
                'Spectr\Meeting\Models\TimeslotTable',
                [
                    '=this.TIMESLOT_ID' => 'ref.ID',
                ]
            ),
            new ReferenceField('RECEIVER_USER', 'Bitrix\Main\UserTable', [
                '=this.RECEIVER_ID' => 'ref.ID',
            ]),
        ];
    }

    public static function getStatuses()
    {
        return self::$statuses;
    }

    public static function getFreeCompanies(int $exhibId, int $groupId)
    {
        return self::getList([
            'select' => [
                'RECEIVER_USER.WORK_COMPANY',
            ],
            'filter' => [
                '=EXHIBITION_ID' => $exhibId,
                '!=STATUS' => self::STATUS_CONFIRMED,
                '=TIMESLOT.SLOT_TYPE' => TimeslotTable::TYPE_MEET,

            ],
        ])->fetchAll();
    }

}