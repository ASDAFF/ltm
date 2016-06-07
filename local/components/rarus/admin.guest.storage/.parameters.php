<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arFields = array(
	612 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_115'), //Название компании
	613 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_677'), //Вид деятельности
	614 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_773'), //Фактический адрес компании
	615 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_756'), //Индекс
	616 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_672'), //Город
	617 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_678'), //Страна
	618 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_243'), //Страна (other)
	619 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_750'), //Имя
	620 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_823'), //Фамилия
	621 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_391'), //Должность
	622 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_636'), //Телефон
	623 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_844'), //Мобильный телефон
	624 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_111'), //Skype
	625 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_373'), //E-mail
	626 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_279'), //Введите E-mail ещё раз
	627 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_552'), //http://
	628 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_367'), //Имя коллеги 1
	629 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_482'), //Фамилия коллеги 1
	630 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_187'), //Должность коллеги 1
	631 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_421'), //E-mail коллеги 1
	632 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_225'), //Имя коллеги 2
	633 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_770'), //Фамилия коллеги 2
	634 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_384'), //E-mail коллеги 2
	635 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_280'), //Должность коллеги 2
	636 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_765'), //Имя коллеги 3
	637 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_627'), //Фамилия коллеги 3
	638 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_788'), //Должность коллеги 3
	639 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_230'), //E-mail коллеги 3
	640 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_474'), //Введите логин/гостевое имя
	641 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_435'), //Введите пароль
	642 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_300'), //Повторите пароль
	643 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_166'), //Введите краткое описание
	644 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_383'), //North America
	645 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_244'), //Europe
	646 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_212'), //South America
	647 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_497'), //Africa
	648 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_526'), //Asia
	649 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_878'), //Oceania
	652 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_304'), //Должность коллеги (на утро)
	653 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_278'), //E-mail коллеги (на утро)
	654 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_836'), //Утро
	655 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_156'), //Вечер
	656 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_269'), //Фото
	657 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_873'), //Фото коллеги на утро
	658 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_762'), //Зал
	659 => Loc::getMessage('STORAGE_SIMPLE_QUESTION_211'), //Стол
);
$arComponentParameters = array(
	"PARAMETERS" => array(
		"COUNT" => array(
				"NAME" => Loc::getMessage("STORAGE_COUNT"),
				"TYPE" => "STRING",
				"DEFAULT" => "30",
				"PARENT" => "BASE",
		),
		"FIELDS" => array(
			"NAME" => Loc::getMessage("STORAGE_FIELDS"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"PARENT" => "BASE",
			"VALUES" => $arFields
		),
	),

);
?>