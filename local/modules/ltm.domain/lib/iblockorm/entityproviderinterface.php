<?php

namespace Ltm\Domain\IblockOrm;

/**
 * Интерфейс для провайдера сущностей по конкретному инфоблоку: определяет набор
 * методов, возращающих наименования сущностей и названия табличных классов
 */
interface EntityProviderInterface
{
    /**
     * Возвращает код сущности элементов инфоблока
     * 
     * @return string 
     */
    public function getElementEntityName(): string;
    
    /**
     * Возващает код сущности секций инфоблока
     * 
     * @return string
     */
    public function getSectionEntityName(): string;
    
    /**
     * Возвращает код сущности значений свойств элемента инфоблока
     * 
     * @return string
     */
    public function getElementPropertyEntityName(): string;
    
    /**
     * Возвращает код сущности значений множественных свойств элемента инфоблока
     * 
     * @return string
     */
    public function getElementPropertyMultipleEntityName(): string;
    
    /**
     * Возвращает имя табличного класса для элементов инфоьлока
     * 
     * @return string 
     */
    public function getElementTableClassName(): string;
    
    /**
     * Возвращает имя табличного класса для секций инфоблока
     * 
     * @return string 
     */
    public function getSectionTableClassName(): string;
    
    /**
     * Возвращает имя табличного класса значений свойств элемента инфоблока
     * 
     * @return string
     */
    public function getElementPropertyTableClassName(): string;
    
    /**
     * Возвращает имя табличног класса значений множественных свойств элементов инфоблока
     * 
     * @return string
     */
    public function getElementPropertyMultipleTableClassName(): string;
}