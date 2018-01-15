<?php
namespace Ltm\Domain\Data;

class Exhibitor {
    use HLEntityTrait;

    public function __construct()
    {
        $this->entityName = 'RegistrExhibitor';
    }
}