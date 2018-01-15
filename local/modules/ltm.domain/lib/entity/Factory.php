<?php
namespace Ltm\Domain\Entity;

class Factory
{
    public static function create($data)
    {
        return self::createFormResult($data);
    }

    private static function createFormResult(array $data)
    {
        $result = new GuestFormResult();
        try {
            foreach ($data as $key => $value) {
                $result->{'set'.$key}($value);
            }
        } catch (Exception $e) {
            return false;
        }
        return $result;
    }
}