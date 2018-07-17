<?php

namespace Spectr\Models;

use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use CFile;
use CIBlockElement;
use CUser;

class GuestStorageTable extends DataManager
{

    public static function getTableName(): string
    {
        return "ltm_guest_storage";
    }

    public static function getMap(): array
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new IntegerField('UF_PRIORITY_AREAS', [
                'serialized' => true,
                'save_data_modification' => function () {
                    return [
                        function ($value) {
                            if (!is_array($value)) {
                                return [$value];
                            } else {
                                return $value;
                            }
                        }
                    ];
                },
            ]),
            new IntegerField('UF_COLLEAGUES', [
                'serialized' => true,
                'save_data_modification' => function () {
                    return [
                        function ($value) {
                            if (!is_array($value)) {
                                return [$value];
                            } else {
                                return $value;
                            }
                        }
                    ];
                },
            ]),
            new IntegerField('UF_USER_ID'),
            new StringField('UF_HOTEL'),
            new StringField('UF_TABLE'),
            new StringField('UF_ROOM'),
            new BooleanField('UF_EVENING', [
                'save_data_modification' => function () {
                    return array(
                        function ($value) {
                            if (is_bool($value)) {
                                return $value;
                            } else {
                                return (int)$value == 1;
                            }
                        }
                    );
                },
                'fetch_data_modification' => function () {
                    return array(
                        function ($value) {
                            if (is_bool($value)) {
                                return $value;
                            } else {
                                return (int)$value == 1;
                            }
                        }
                    );
                }
            ]),
            new BooleanField('UF_MORNING', [
                'save_data_modification' => function () {
                    return array(
                        function ($value) {
                            if (is_bool($value)) {
                                return $value;
                            } else {
                                return (int)$value == 1;
                            }
                        }
                    );
                },
                'fetch_data_modification' => function () {
                    return array(
                        function ($value) {
                            if (is_bool($value)) {
                                return $value;
                            } else {
                                return (int)$value == 1;
                            }
                        }
                    );
                }
            ]),
            new IntegerField('UF_OCEANIA', [
                'serialized' => true,
                'save_data_modification' => function () {
                    return [
                        function ($value) {
                            if (!is_array($value)) {
                                return [$value];
                            } else {
                                return $value;
                            }
                        }
                    ];
                },
            ]),
            new IntegerField('UF_ASIA', [
                'serialized' => true,
                'save_data_modification' => function () {
                    return [
                        function ($value) {
                            if (!is_array($value)) {
                                return [$value];
                            } else {
                                return $value;
                            }
                        }
                    ];
                },
            ]),
            new IntegerField('UF_AFRICA', [
                'serialized' => true,
                'save_data_modification' => function () {
                    return [
                        function ($value) {
                            if (!is_array($value)) {
                                return [$value];
                            } else {
                                return $value;
                            }
                        }
                    ];
                },
            ]),
            new IntegerField('UF_SOUTH_AMERICA', [
                'serialized' => true,
                'save_data_modification' => function () {
                    return [
                        function ($value) {
                            if (!is_array($value)) {
                                return [$value];
                            } else {
                                return $value;
                            }
                        }
                    ];
                },
            ]),
            new IntegerField('UF_EUROPE', [
                'serialized' => true,
                'save_data_modification' => function () {
                    return [
                        function ($value) {
                            if (!is_array($value)) {
                                return [$value];
                            } else {
                                return $value;
                            }
                        }
                    ];
                },
            ]),
            new IntegerField('UF_NORTH_AMERICA', [
                'serialized' => true,
                'save_data_modification' => function () {
                    return [
                        function ($value) {
                            if (!is_array($value)) {
                                return [$value];
                            } else {
                                return $value;
                            }
                        }
                    ];
                },
            ]),
            new StringField('UF_DESCRIPTION'),
            new StringField('UF_PASSWORD'),
            new StringField('UF_LOGIN'),
            new StringField('UF_SITE'),
            new StringField('UF_EMAIL'),
            new StringField('UF_SKYPE'),
            new StringField('UF_MOBILE'),
            new StringField('UF_PHONE'),
            new StringField('UF_POSITION'),
            new StringField('UF_SALUTATION'),
            new StringField('UF_SURNAME'),
            new StringField('UF_NAME'),
            new StringField('UF_COUNTRY_OTHER'),
            new IntegerField('UF_COUNTRY'),
            new StringField('UF_CITY'),
            new StringField('UF_POSTCODE'),
            new StringField('UF_ADDRESS'),
            new StringField('UF_COMPANY'),
            new IntegerField('UF_PHOTO'),
        ];
    }

    public static function onBeforeAdd(Event $event)
    {
        $result = new EventResult;
        $data = $event->getParameter("fields");
        $fieldsKey = array_keys(self::getEntity()->getFields());
        if (isset($data['ID'])) {
            $result->unsetField('ID');
        }

        if (isset($data['UF_PHOTO'])) {
            $newFileId = CFile::CopyFile($data['UF_PHOTO']);
            $result->modifyFields(['UF_PHOTO' => $newFileId]);
        }

        foreach ($data as $key => $value) {
            if (!in_array($key, $fieldsKey)) {
                $result->unsetField($key);
            }
        }

        return $result;
    }

    public static function getRowByUserID(int $userId): array
    {
        $result = self::getList([
            'filter' => [
                'UF_USER_ID' => $userId
            ],
            'limit' => 1
        ])->fetch();
        return $result ?: [];
    }

    public static function updateUser(int $userId, int $exibId, bool $morning = null, bool $evening = null): bool
    {
        $exib = CIBlockElement::GetList([], ['ID' => $exibId], false, false, ['PROPERTY_UC_GUESTS_GROUP'])->Fetch();
        if ($exib) {
            $groupId = [$exib['PROPERTY_UC_GUESTS_GROUP_VALUE']];

            $arFields = [
                'UF_MR' => false,
                'UF_EV' => false,
                'UF_HB' => false,
                'GROUP_ID' => $groupId
            ];
            $user = new CUser();
            $user->Update($userId, $arFields);
            if(!$user->LAST_ERROR){
                return true;
            }else{
                return false;
            }
        } else {
            return false;
        }
    }

    public static function moveToExib(int $userId, int $exibId, bool $morning = null, bool $evening = null): bool
    {
        $row = self::getRowByUserID($userId);
        if ($row && self::updateUser($userId, $exibId, $morning, $evening)) {
            if (isset($row['UF_COLLEAGUES'])) {
                $newColleagues = [];
                foreach ($row['UF_COLLEAGUES'] as $colleagueId) {
                    $newId = GuestStorageColleagueTable::moveColleagueToExib($colleagueId);
                    if (!is_null($newId)) {
                        $newColleagues[] = $newId;
                    }
                }
                $row['UF_COLLEAGUES'] = $newColleagues;
            }
            $row['UF_MORNING'] = $morning ?: false;
            $row['UF_EVENING'] = $evening ?: false;
            $row['UF_EXHIB_ID'] = $exibId;
            $result = RegistrGuestTable::add($row);
            if ($result->isSuccess()) {
                self::delete($row['ID']);
                return $result->getId();
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}