<?php
namespace Ltm\Domain\Data;

class Participant {
    use HLEntityTrait;

    public function __construct()
    {
        $this->entityName = 'RegistrExhibitorParticipant';
    }
}