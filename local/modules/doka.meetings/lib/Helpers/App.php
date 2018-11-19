<?php

namespace Spectr\Meeting\Helpers;

use Bitrix\Main\Loader;
use Spectr\Meeting\Models\SettingsTable;

class App
{
    private $filter = [];
    private $id;
    private $otherId;
    private $data = [];
    private $settings = [];

    /**
     * @throws \Exception
     *
     * @param array $arParams
     */
    public function __construct($arParams = [])
    {
        $preparedParams = $this->prepareParams($arParams);
        $this->checkRequiredParams($preparedParams)->loadDependencies()->init($preparedParams);
    }

    private function prepareParams($params)
    {
        return [
            'IBLOCK_ID' => (int)$params['IBLOCK_ID'],
            'ID'        => (int)$params['ID'],
            'CODE'      => (string)$params['CODE'],
            'IS_HB'     => (bool)$params['IS_HB'],
        ];
    }


    /**
     * @throws \Exception
     *
     * @param array $params
     *
     * @return self
     */
    private function checkRequiredParams($params = [])
    {
        $errors = [];
        if (($params['EXHIBITION_IBLOCK_ID'] < 0)) {
            $errors[] = static::class.': IBLOCK_ID NOT SET';
        }
        if ($params['CODE'] === '' && $params['ID'] <= 0) {
            $errors[] = static::class.': ID OR CODE NOT SET';
        }
        if ( !empty($errors)) {
            throw new \Exception(implode(' , ', $errors));
        }

        return $this;
    }

    /**
     * @throws \Exception
     * @return self
     */
    private function loadDependencies()
    {
        Loader::includeModule('iblock');
        Loader::includeModule('doka.meetings');

        return $this;
    }

    private function init($params)
    {
        $this->filter = $params;

        $arFilter = ['IBLOCK_ID' => $this->filter['IBLOCK_ID']];
        if ($this->filter['CODE']) {
            $arFilter['CODE'] = $this->filter['CODE'];
        }
        if ($this->filter['ID']) {
            if ($this->filter['IS_HB']) {
                $arFilter['PROPERTY_APP_HB_ID'] = $this->filter['ID'];
            } else {
                $arFilter['PROPERTY_APP_ID'] = $this->filter['ID'];
            }
        }

        $rsExhib = \CIBlockElement::GetList([], $arFilter);
        if ($oExhib = $rsExhib->GetNextElement(true, false)) {
            $this->data               = $oExhib->GetFields();
            $this->data['PROPERTIES'] = $oExhib->GetProperties();
        }
        if ($this->filter['IS_HB']) {
            $this->id      = $this->data['PROPERTIES']['APP_HB_ID']['VALUE'];
            $this->otherId = $this->data['PROPERTIES']['APP_ID']['VALUE'];
        } else {
            $this->id      = $this->data['PROPERTIES']['APP_ID']['VALUE'];
            $this->otherId = $this->data['PROPERTIES']['APP_HB_ID']['VALUE'];
        }

        $this->settings = SettingsTable::getById($this->getId())->fetch();
    }

    public function getData()
    {
        return $this->data;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOtherId()
    {
        return $this->otherId;
    }

    public function getSettings()
    {
        return $this->settings;
    }
}