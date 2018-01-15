<?php
namespace Ltm\Domain\Entity;

class GuestFormResult
{
    private $fields;

    public function __call($name, $arguments)
    {
        if (strpos($name, 'set') === 0 || strpos($name, 'get') === 0) {
            // if it is setValue or getValue
            $buyer = new Buyer();
            $varName = $buyer->entityPrefix.strtoupper(substr($name, 3));
            $fields = $buyer->getFields();
            if(in_array($varName, $fields, true)) {
                if (strpos($name, 'set') === 0) {
                    $this->fields[$varName] = $arguments[0];
                    return true;
                } elseif (strpos($name, 'get') === 0) {
                    if (array_key_exists($varName, $this->fields)) {
                        return $this->fields[$varName];
                    } else {
                        return null;
                    }
                }
            } else {
                trigger_error('Call to undefined method '.__CLASS__.'::'.$name.'()', E_USER_ERROR);
            }
        } else {
            trigger_error('Call to undefined method '.__CLASS__.'::'.$name.'()', E_USER_ERROR);
        }
        return false;
    }
}