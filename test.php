<?php require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Ltm\Domain\Profile;

$profileProvider = new Profile\ProfileDataProvider();

pri($profileProvider->getQuestionListBySectionId(2579));