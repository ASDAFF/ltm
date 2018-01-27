<?php

namespace Ltm\Domain\Profile\Type;

use Ltm\Domain\Profile;

abstract class AbstractType
{
  protected $typeCode = '';
  /**
   * @var Profile\Question
   */
  protected $question = null;

  public function isThisType(Profile\Question $question): bool
  {
    if($question->getTypeCode() == $this->typeCode) {
      return true;
    }

    return false;
  }

  abstract public function getValue(Profile\Question $action);
}