<?php
/**
 * Created by PhpStorm.
 * User: amf1k
 * Date: 10.07.18
 * Time: 12:53
 */

namespace Spectr\Models;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Highloadblock as HL;
use CUserTypeEntity;
use CUserFieldEnum;
use Bitrix\Main\Loader;

Loader::includeModule('highloadblock');

class RegistrGuestColleagueTable extends DataManager
{
    public static function getTableName(): string
    {
        return "ltm_registr_buyer_colleague";
    }

    public static function getId(): int
    {
        try {
            $HLBlock = HL\HighloadBlockTable::getList([
                'filter' => [
                    'TABLE_NAME' => self::getTableName()
                ]
            ])->fetch();
        } catch (ArgumentException $e) {
            return 0;
        }

        return intval($HLBlock['ID']);
    }

    public static function getHlId(): string
    {
        return 'HLBLOCK_' . self::getId();
    }

    private static function getUserFieldId(string $code): int
    {
        if (strlen($code) == 0) {
            return 0;
        }
        $result = CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => self::getHlId(),
                'FIELD_NAME' => $code
            ]
        )->Fetch();
        return $result['ID'];
    }

    public static function getEnumValueXMLIDById(int $id, string $code)
    {
        $userFieldId = self::getUserFieldId($code);
        $result = CUserFieldEnum::GetList([], [
            'ID' => $id,
            'USER_FIELD_ID' => $userFieldId,
        ])->Fetch();
        return $result['XML_ID'];
    }

    public static function getEnumValueIdByXMLID($xml_id, string $code) : int
    {
        $userFieldId = self::getUserFieldId($code);
        $result = CUserFieldEnum::GetList([], [
            'XML_ID' => $xml_id,
            'USER_FIELD_ID' => $userFieldId,
        ])->Fetch();
        return $result['ID'] ?: 0;
    }

    public static function getMap(): array
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new StringField('UF_MOBILE_PHONE'),
            new IntegerField('UF_SALUTATION'),
            new IntegerField('UF_DAYTIME', [
                'save_data_modification' => function () {
                    return [
                        function ($value) {
                            if(is_array($value)){
                                $value = reset($value);
                            }
                            return serialize([self::getEnumValueIdByXMLID($value, 'UF_DAYTIME')]);
                        }
                    ];
                },
                'fetch_data_modification' => function () {
                    return [
                        function ($value) {
                            $unserValue = reset(unserialize($value));
                            $ufEnumValue = self::getEnumValueXMLIDById(intval($unserValue), 'UF_DAYTIME');
                            if ($ufEnumValue) {
                                return $ufEnumValue;
                            } else {
                                return null;
                            }
                        }
                    ];
                }
            ]),
            new StringField('UF_EMAIL'),
            new IntegerField('UF_PHOTO'),
            new StringField('UF_JOB_TITLE'),
            new StringField('UF_SURNAME'),
            new StringField('UF_NAME'),
        ];
    }

    public static function moveColleagueToStorage(int $colleague_id)
    {
        $row = self::getRowById($colleague_id);
        if($row){
            $result = GuestStorageColleagueTable::add($row);
            if($result->isSuccess()){
                return $result->getId();
            }else{
                return null;
            }
        }else{
            return null;
        }
    }
}