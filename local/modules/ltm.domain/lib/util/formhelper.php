<?php
namespace Ltm\Domain\Util;

use \Bitrix\Form;
use \Ltm\Domain\Regions;

class FormHelper{
    
    /** Возвращает ошибки не относящиеся к полям формы
     * @static
     * @param object CFormOutput $FORM
     * @param array $decorate массив с радедлителями для итоговой строки с ошибками
     * @return string
     */
    public static function getNotFieldErrors($FORM, $decorate=[]){
        $errorList = [];
        foreach($FORM->__form_validate_errors as $field => $value){
            if(!isset($FORM->arAnswers[$field])){
                $errorList[$field] = $value;
            }
        }
        $errorStr = '';
        if(!empty($errorList)){
            if(!empty($decorate)){
                if(count($decorate) == 1){
                    $errorStr = implode($decorate[0], $errorList);
                }
                else{
                    $errorStr = $decorate[0].implode($decorate[0].$decorate[1], $errorList).$decorate[1];
                }
            }
            else{
                $errorStr = implode('<br>', $errorList);
            }
        }
        
        return $errorStr;
    }
}