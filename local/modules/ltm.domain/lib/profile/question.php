<?php

namespace Mts\Domain\Profile;

use Mts\Domain\Profile\Type;

class Question
{
    /** Id вопроса
     * @var int
     */
    protected $id;

    /** Код вопроса
     * @var string
     */
    protected $code;

    /** Сортировка
     * @var int
     */
    protected $sort;

    /** Код типа вопроса
     * @var string
     */
    protected $typeCode;

    /** Класс типа вопроса
     * @var Type\AbstractType
     */
    protected $class;

    /**
     * @var array
     */
    protected $info;

    public function __construct($questionInfo)
    {
        $this->info = $questionInfo;

        $this->setId($questionInfo["ID"])
            ->setCode($questionInfo["CODE"])
            ->setTypeCode($questionInfo["TYPE"])
            ->setSort($questionInfo["SORT"]);
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $code string
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param $fieldName string
     * @return mixed
     */
    public function getFieldByName($fieldName)
    {
        return $this->info[$fieldName];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;
        return $this;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     * @return $this
     */
    public function setSort($sort)
    {
        $this->sort = (int)$sort;
        return $this;
    }

    /**
     * @return string
     */
    public function getTypeCode(): string
    {
        return $this->typeCode;
    }

    /**
     * @param string $typeCode
     * @return $this
     */
    public function setTypeCode($typeCode)
    {
        $this->typeCode = $typeCode;
        return $this;
    }

    /**
     * @return Type\AbstractType
     */
    public function getClass(): Type\AbstractType
    {
        return $this->class;
    }

    /**
     * @param Type\AbstractType $class
     * @return $this
     */
    public function setClass(Type\AbstractType $class)
    {
        $this->class = $class;
        return $this;
    }
}