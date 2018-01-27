<?php

namespace Ltm\Domain\Profile;

use Ltm\Domain\Profile\Type;
use Mts\Domain\Profile\Question;

class Form
{
  const QUESTION_IBLOCK_ID = 25;
  protected $questionList;
  protected $dataProvider = null;/**
 * @var Question\Type []
 */
  protected $types = [];

  protected function __construct($sectionId)
  {
    $this->dataProvider = new ProfileDataProvider();
    $this->setUsedQuestionTypes();
    $this->loadQuestionList($sectionId);
  }

  protected function loadQuestionList($sectionId, $questionList = [])
  {
    $this->questionList = [];

    if(empty($questionList)) {
      $questionList = $this->dataProvider->getQuestionListBySectionId($sectionId);
    }

    foreach($questionList as $questionInfo ) {
      $question = new Question($questionInfo);
      $questionType = $this->getQuestionType($question);
      if(is_null($questionType)) {
        continue;
      }
      $question->setClass($questionType);

      $this->$question[$question->getCode()] = $question;
    }

    return $this;


  }

  protected function setUsedQuestionTypes()
  {
    $this->addType(new Type\Text());
  }

  /** Добавление типов иконок для которых производится расчет
  * @param Type $questionType
  * @return $this
  */
  public function addType(Type\AbstractType $questionType)
  {
    $this->types[] = $questionType;
    return $this;
  }
}