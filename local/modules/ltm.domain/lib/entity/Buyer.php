<?php
namespace Ltm\Domain\Entity;
use Ltm\Domain\Data\HLEntityTrait;

class Buyer
{
    use HLEntityTrait;

    public function __construct()
    {
        $this->entityName = 'RegistrGuest';
        $this->entityPrefix = 'B_';
    }
}