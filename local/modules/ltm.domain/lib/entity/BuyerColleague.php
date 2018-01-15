<?php
namespace Ltm\Domain\Entity;
use Ltm\Domain\Data\HLEntityTrait;

class BuyerColleague
{
    use HLEntityTrait;

    public function __construct()
    {
        $this->entityName = 'RegistrGuestColleague';
    }
}