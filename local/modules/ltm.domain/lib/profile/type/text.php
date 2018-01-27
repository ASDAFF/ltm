<?php

namespace Ltm\Domain\Profile\Type;

use Ltm\Domain\Profile;

class Text extends AbstractType
{
  const TYPE_CODE = 'text';

  public function __construct()
  {
    $this->typeCode = self::TYPE_CODE;
  }

  public function getValue(Profile\Question $action)
  {
    return $action;
  }
}