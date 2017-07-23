<?php

namespace Mts\Domain\Util;

use Bitrix\Highloadblock;
use Bitrix\Main\Entity;
use Mts\Domain\Service;
use Mts\Domain\Service\Provider\Configuration;

class HLBlockHelper
{
    /**
     * Возвращает Русское название по названию Сущности
     *
     * @param string $entity
     * @return string|null
     *
     * @uses getEntityParam()
     */
    public static function getTitleByEntity($entity)
    {
        return static::getEntityParam($entity, 'TITLE');
    }

    /**
     * Возвращает массив с Русскими названиями Хайлоад блоков
     *
     * @return array
     */
    public static function getTitlesArr()
    {
        $settings = static::getSettings();

        return array_combine(array_keys($settings), array_column($settings, 'TITLE'));
    }

    /**
     * Возвращает id Хайлоад блока по названию таблицы
     *
     * @param string $tableName
     * @param bool $noCache
     *
     * @return int|null
     */
    public static function getIdByBase($tableName, $noCache = false)
    {
        static $base;

        if(!isset($base) || $noCache === true) {
            $settings = static::getSettings();
            $base = array_combine(array_column($settings, 'TABLE_NAME'), array_column($settings, 'ID'));
        }

        return $base[$tableName];
    }

    /**
     * Возвращает id Хайлоад блока по названию сущности
     *
     * @param string $entity
     * @return int|null
     *
     * @uses getEntityParam()
     */
    public static function getIdByEntity($entity)
    {
        return static::getEntityParam($entity, 'ID');
    }

    /**
     * @param string $entityName
     * @return \Bitrix\Main\Entity\Base|null
     */
    public static function getEntity($entityName)
    {
        $description = static::getEntityDescription($entityName);
        if(!empty($description)) {
            return static::getEntityByDescription($description);
        }

        return null;
    }

    /**
     * @param int $id
     * @return \Bitrix\Main\Entity\Base|null
     */
    public static function getEntityById($id)
    {
        $description = static::getEntityDescriptionById($id);
        if(!empty($description)) {
            return static::getEntityByDescription($description);
        }

        return null;
    }

    /**
     * @param string $entityName
     * @return array|null
     */
    public static function getEntityDescription($entityName)
    {
        $settings = static::getSettings();

        if(!empty($settings[$entityName])) {
            $result = $settings[$entityName];
            $result['NAME'] = $entityName;

            return $result;
        }

        return null;
    }

    /**
     * @staticvar array $cache
     *
     * @param int $id
     * @param bool $noCache
     *
     * @return array
     */
    public static function getEntityDescriptionById($id, $noCache = false)
    {
        static $cache = array();
        if(empty($cache) || $noCache == true) {
            $settings = static::getSettings();

            foreach($settings as $entityName => $ent) {
                $ent['NAME'] = $entityName;
                $cache[$ent['ID']] = $ent;
            }
        }

        return $cache[$id];
    }

    /**
     * @param string $entityName
     * @param string $param
     * @return mixed|null
     *
     * @uses getSettings()
     */
    public static function getEntityParam($entityName, $param)
    {
        $settings = static::getSettings();
        if(!empty($settings[$entityName]) && isset($settings[$entityName][$param])) {
            return $settings[$entityName][$param];
        }

        return null;
    }

    /**
     * @param \Bitrix\Main\Entity\Base|string $entityName
     * @param array $params
     * @return \Bitrix\Main\DB\Result
     *
     * @uses callEntityMethod()
     */
    public static function getList($entityName, array $params = array())
    {
        return static::callEntityMethod($entityName, 'getList', $params);
    }

    /**
     * @param \Bitrix\Main\Entity\Base|string $entityName
     * @param string $eventKey
     *
     * @return string
     *
     * @uses getOrmEntityName()
     */
    public static function getOrmEntityEventKey($entityName, $eventKey)
    {
        return sprintf(
            '%s::%s',
            static::getOrmEntityName($entityName, true),
            $eventKey
        );
    }

    /**
     * @param \Bitrix\Main\Entity\Base|string $entityName
     * @param bool $withNamespace
     *
     * @return string
     *
     * @uses getEntity()
     */
    public static function getOrmEntityName($entityName, $withNamespace = false)
    {
        /** @var \Bitrix\Main\Entity\Base */
        $entity = static::getRealEntity($entityName);

        return sprintf(
            '%s%s',
            (
                $withNamespace
                ? $entity->getNamespace()
                : ''
            ),
            $entity->getName()
        );
    }

    /**
     * @param \Bitrix\Main\Entity\Base|string $entityName
     * @param array $params
     * @return \Bitrix\Main\DB\Result
     *
     * @uses callEntityMethod()
     */
    public static function getRow($entityName, array $params)
    {
        return static::callEntityMethod($entityName, 'getRow', $params);
    }

    /**
     * @param \Bitrix\Main\Entity\Base|string $entityName
     * @return \Bitrix\Main\Entity\Query
     */
    public static function getQuery($entityName)
    {
        $entity = static::getRealEntity($entityName);

        return new Entity\Query($entity);
    }

    /**
     * @param \Bitrix\Main\Entity\Base|string $entityName
     * @param array $data
     *
     * @return \Bitrix\Main\Entity\AddResult
     *
     * @uses callEntityMethod()
     */
    public static function add($entityName, array $data)
    {
        return static::callEntityMethod($entityName, 'add', $data);
    }

    /**
     * @param \Bitrix\Main\Entity\Base|string $entityName
     * @param mixed $id
     * @param array $data
     *
     * @return \Bitrix\Main\Entity\UpdateResult
     *
     * @uses callEntityMethod()
     */
    public static function update($entityName, $id, array $data)
    {
        return static::callEntityMethod($entityName, 'update', $id, $data);
    }

    /**
     * @param \Bitrix\Main\Entity\Base|string $entityName
     * @param mixed $id
     *
     * @return \Bitrix\Main\Entity\DeleteResult
     *
     * @uses callEntityMethod()
     */
    public static function delete($entityName, $id)
    {
        return static::callEntityMethod($entityName, 'delete', $id);
    }


    /**
     * @param \Bitrix\Main\Entity\Base|string $entity
     * @param string $type
     * @param array|false $data
     * @param mixed|null $primary
     * @param array|false|null $oldData
     *
     * @return \Bitrix\Main\Entity\Result
     */
    public static function triggerEvent($entity, $type, &$data, $primary = null, $oldData = null)
    {
        $entity = static::getRealEntity($entity);

        $eventData = array(
            'fields' => $data
        );

        if(!empty($primary)) {
            $eventData['primary'] = $primary;
            $eventData['id'] = $primary;
        }

        if($oldData !== false) {
            if(empty($oldData) && !empty($primary)) {
                $oldData = static::callEntityMethod($entity, 'getByPrimary', $primary)->fetch();
            }

            if(!empty($oldData)) {
                $eventData['oldFields'] = $oldData;
            }
        }

        $result = new Entity\Result();

        $event = new Entity\Event($entity, $type, $eventData);
        $event->send();
        $event->getErrors($result);
        if(is_array($data)) {
            $data = $event->mergeFields($data);
        }

        $event = new Entity\Event($entity, $type, $eventData, true);
        $event->send();
        $event->getErrors($result);
        if(is_array($data)) {
            $data = $event->mergeFields($data);
        }

        return $result;
    }

    /**
     * @param \Bitrix\Main\Entity\Base|string $entityName
     * @param string $method
     * @param mixed ...$params
     *
     * @return mixed
     * @uses call_user_func_array()
     */
    protected static function callEntityMethod($entityName, $method, ...$params)
    {
        $entity = static::getRealEntity($entityName);
        $className = $entity->getDataClass();

        return call_user_func_array(array($className, $method), $params);
    }

    /**
     * @param array $description
     * @return Bitrix\Main\Entity\Base
     */
    protected static function getEntityByDescription($description)
    {
        return Highloadblock\HighloadBlockTable::compileEntity($description);
    }


    /**
     * @return array
     */
    protected static function getSettings()
    {
        $configuration = Service\Manager::getService(Configuration::SERVICE_ID);
        $hlBlockSettings = $configuration['hlblock'];

        return isset($hlBlockSettings) ? $hlBlockSettings : array();
    }

    /**
     * @param \Bitrix\Main\Entity\Base|string $entity
     * @return \Bitrix\Main\Entity\Base
     */
    protected static function getRealEntity($entity)
    {
        return (
            (is_string($entity))
            ? static::getEntity($entity)
            : $entity
        );
    }
}
