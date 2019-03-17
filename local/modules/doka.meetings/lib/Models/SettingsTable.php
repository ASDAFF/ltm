<?php

namespace Spectr\Meeting\Models;


use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Loader;
use CIBlockElement;

Loader::includeModule("iblock");

class SettingsTable extends DataManager
{
    public static function getTableName(): string
    {
        return "meetings_settings";
    }

    public static function getMap(): array
    {
        return [
            new IntegerField('ID', [
                'primary'      => true,
                'autocomplete' => true,
            ]),
            new StringField('NAME'),
            new StringField('CODE'),
            new BooleanField('IS_LOCKED', [
                'save_data_modification'  => function () {
                    return [
                        function ($value) {
                            if (is_bool($value)) {
                                return $value;
                            } else {
                                return (int)$value == 1;
                            }
                        },
                    ];
                },
                'fetch_data_modification' => function () {
                    return [
                        function ($value) {
                            if (is_bool($value)) {
                                return $value;
                            } else {
                                return (int)$value == 1;
                            }
                        },
                    ];
                },
            ]),
            new BooleanField('ACTIVE', [
                'save_data_modification'  => function () {
                    return [
                        function ($value) {
                            if (is_bool($value)) {
                                return $value;
                            } else {
                                return (int)$value == 1;
                            }
                        },
                    ];
                },
                'fetch_data_modification' => function () {
                    return [
                        function ($value) {
                            if (is_bool($value)) {
                                return $value;
                            } else {
                                return (int)$value == 1;
                            }
                        },
                    ];
                },
            ]),
            new IntegerField('GUESTS_GROUP'),
            new BooleanField('IS_GUEST', [
                'save_data_modification'  => function () {
                    return [
                        function ($value) {
                            if (is_bool($value)) {
                                return $value;
                            } else {
                                return (int)$value == 1;
                            }
                        },
                    ];
                },
                'fetch_data_modification' => function () {
                    return [
                        function ($value) {
                            if (is_bool($value)) {
                                return $value;
                            } else {
                                return (int)$value == 1;
                            }
                        },
                    ];
                },
            ]),
            new BooleanField('IS_HB', [
                'save_data_modification'  => function () {
                    return [
                        function ($value) {
                            if (is_bool($value)) {
                                return $value;
                            } else {
                                return (int)$value == 1;
                            }
                        },
                    ];
                },
                'fetch_data_modification' => function () {
                    return [
                        function ($value) {
                            if (is_bool($value)) {
                                return $value;
                            } else {
                                return (int)$value == 1;
                            }
                        },
                    ];
                },
            ]),
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

    public static function getSettingsByCode(string $exhibCode): array
    {
        return self::getList([
            'filter' => [
                'CODE' => $exhibCode,
            ],
        ])->fetch();
    }

    public static function getExhibition(array $filter): array
    {
        $result  = [];
        $rsExhib = CIBlockElement::GetList([], $filter);
        if ($oExhib = $rsExhib->GetNextElement(true, false)) {
            $result["PARAM_EXHIBITION"]               = $oExhib->GetFields();
            $result["PARAM_EXHIBITION"]["PROPERTIES"] = $oExhib->GetProperties();
        }

        return $result;
    }
}