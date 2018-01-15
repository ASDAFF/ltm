<?php
namespace Ltm\Domain\Data;

use Ltm\Domain\HlblockOrm\Manager as HlBlockManager;
use Ltm\Domain\HlblockOrm\EntityProvider\DefaultProvider;
use Ltm\Domain\HlblockOrm\Model;
use Ltm\Domain\IblockOrm\Manager;
use Bitrix\Main\Entity;
use Bitrix\Main\Entity\DataManager;

trait HLEntityTrait
{
    public $entityName;
    public $entityPrefix;

    public function getHighloadFields()
    {
        /* @var HlBlockManager $manager */
        $manager = HlBlockManager::getInstance();

        /* @var DefaultProvider $provider */
        $provider = $manager->getProvider($this->entityName);

        /* @var Model $model */
        $model = $provider->getModel();

        return $model->getEntityFields();
    }

    public function getIblockFields()
    {
        /* @var Manager $manager */
        $manager = Manager::getInstance();

        /* @var \Ltm\Domain\IblockOrm\DefaultEntityProvider $provider */
        $provider = $manager->getProvider(25);

        /* @var DataManager $sectionClass */
        $sectionClass = $provider->getSectionTableClassName();
        $section = $sectionClass::getList([
            'filter' => [
                'IBLOCK_ID' => 25,
                'CODE' => $this->entityName,
            ],
            'select' => ['ID'],
        ])->fetch();
        if ($section && array_key_exists('ID', $section)) {
            /* @var DataManager $elementClass */
            $elementClass = $provider->getElementTableClassName();
            $questions = $elementClass::getList([
                'filter' => [
                    'IBLOCK_ID' => 25,
                    'IBLOCK_SECTION_ID' => $section['ID'],
                ],
                'select' => [
                    'ID',
                    'NAME',
                    'CODE',
                ],
            ]);
            $result = [];
            while ($question = $questions->fetch()) {
                $result[$question['CODE']] = $question['NAME'];
            }

            return $result;
        }

        return [];
    }

    public function getMap()
    {
        $iblockFields = $this->getIblockFields();
        $highloadFields = $this->getHighloadFields();
        $result = [];
        foreach ($iblockFields as $code => $title) {
            if(array_key_exists($code, $highloadFields)) {
                $t = $highloadFields[$code];
                $t['QUESTION_TITLE'] = $title;
                $result[$code] = $t;
            }
        }
        return $result;
    }

    /*
     * Get fields of the entity - only those codes available both in Infoblock and Highload
     * */
    public function getFields()
    {
        $fields = [];
        $iblockFields = $this->getIblockFields();
        $highloadFields = $this->getHighloadFields();
        foreach ($iblockFields as $code => $title) {
            if(array_key_exists($code, $highloadFields)) {
                $fields[] = str_replace('UF_', $this->entityPrefix, $code);
            }
        }
        return $fields;
    }
}