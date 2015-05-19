<?
class CFormMatrix
{
	private static $arAnswerSalutationIDByForm = array(
		4 => array(
			200,//Mr.
			201,//Mrs.
			202,//Ms.
			203//Dr.
		),
		5 => array(
			94,//Mr.
			1298,//Mrs.
			1299,//Ms.
			1300//Dr.
		),
		6 => array(
			102,//Mr.
			1301,//Mrs.
			1302,//Ms.
			1303//Dr.
		),
		7 => array(
			110,//Mr.
			1304,//Mrs.
			1305,//Ms.
			1306//Dr.
		),
		8 => array(
			118,//Mr.
			1307,//Mrs.
			1308,//Ms.
			1309//Dr.
		),
		25 => array(
			1221,//Mr.
			1222,//Mrs.
			1223,//Ms.
			1224//Dr.
		),
		26 => array(
			1370,//Mr.
			1371,//Mrs.
			1372,//Ms.
			1373//Dr.
		),
		27 => array(
			1389,//Mr.
			1390,//Mrs.
			1391,//Ms.
			1392//Dr.
		),
		28 => array(
			1408,//Mr.
			1409,//Mrs.
			1410,//Ms.
			1411//Dr.
		),
		29 => array(
			1456,//Mr.
			1457,//Mrs.
			1458,//Ms.
			1459//Dr.
		),
	);
	private static $arAnswerRequisiteIDByForm = array(
		4 => array(
			1338,//ИП Поланский Артем Валентинович
			1339,//Трэвэл Медиа
		),
		5 => array(
			1344,//ИП Поланский Артем Валентинович
			1345,//Трэвэл Медиа
		),
		6 => array(
			1348,//ИП Поланский Артем Валентинович
			1349,//Трэвэл Медиа
		),
		7 => array(
			1352,//ИП Поланский Артем Валентинович
			1353,//Трэвэл Медиа
		),
		8 => array(
			1356,//ИП Поланский Артем Валентинович
			1357,//Трэвэл Медиа
		),
		25 => array(
			1360,//ИП Поланский Артем Валентинович
			1361,//Трэвэл Медиа
		),
		26 => array(
			1385,//ИП Поланский Артем Валентинович
			1386,//Трэвэл Медиа
		),
		27 => array(
			1404,//ИП Поланский Артем Валентинович
			1405,//Трэвэл Медиа
		),
		28 => array(
			1423,//ИП Поланский Артем Валентинович
			1424,//Трэвэл Медиа
		),
		29 => array(
			1472,//ИП Поланский Артем Валентинович
			1473,//Трэвэл Медиа
		),
	);
	static function getIndexRequisiteIDByForm($id, $formID)
	{
		$index = array_search($id, self::$arAnswerRequisiteIDByForm[$formID]);
		return $index;
	}
	static function getIndexRequisiteRelBase($id, $formID)
	{
		$index = array_search($id, self::$arAnswerRequisiteIDByForm[4]);
		return self::$arAnswerRequisiteIDByForm[$formID][$index];
	}
	private static $arFormQuestions = array(
		4 => array(
			32,//Participant first name
			33,//Participant last name
			35,//Job title
			36,//Telephone
			586,//Skype
			37,//E-mail
			38,//Please confirm your e-mail
			39,//Alternative e-mail
			101,//Персональное фото
			106,//Salutation
			496,//Зал
			497,//Стол
			508,//Номер счета
			509,//Сумма счета
			510,//Реквизиты
		),
		5 => array(
			40,//Participant first name
			41,//Participant last name
			43,//Job title
			44,//Telephone
			588,//Skype
			45,//E-mail
			46,//Please confirm your e-mail
			47,//Alternative e-mail
			102,//Персональное фото
			42,//Salutation
			498,//Зал
			499,//Стол
			512,//Номер счета
			513,//Сумма счета
			514,//Реквизиты
		),
		6 => array(
			48,//Participant first name
			49,//Participant last name
			51,//Job title
			52,//Telephone
			589,//Skype
			53,//E-mail
			54,//Please confirm your e-mail
			55,//Alternative e-mail
			103,//Персональное фото
			50,//Salutation
			500, //Зал
			501,//Стол
			515,//Номер счета
			516,//Сумма счета
			517,//Реквизиты
		),
		7 => array(
			56,//Participant first name
			57,//Participant last name
			59,//Job title
			60,//Telephone
			590,//Skype
			61,//E-mail
			62,//Please confirm your e-mail
			63,//Alternative e-mail
			104,//Персональное фото
			58,//Salutation
			502, //Зал
			503, //Стол
			518,//Номер счета
			519,//Сумма счета
			520,//Реквизиты
		),
		8 => array(
			64,//Participant first name
			65,//Participant last name
			67,//Job title
			68,//Telephone
			590,//Skype
			69,//E-mail
			70,//Please confirm your e-mail
			71,//Alternative e-mail
			105,//Персональное фото
			66,//Salutation
			504,//Зал
			505,//Стол
			521,//Номер счета
			522,//Сумма счета
			523,//Реквизиты
		),
		25 => array(
			483,//Participant first name
			484,//Participant last name
			485,//Job title
			486,//Telephone
			592,//Skype
			487,//E-mail
			488,//Please confirm your e-mail
			489,//Alternative e-mail
			490,//Персональное фото
			491, //Salutation
			506, //Зал
			507,//Стол
			524,//Номер счета
			525, //Сумма счета
			526, //Реквизиты
		),
		26 => array(
			527,//Participant first name
			528,//Participant last name
			530,//Job title
			531,//Telephone
			594,//Skype
			532,//E-mail
			533,//Please confirm your e-mail
			534,//Alternative e-mail
			535,//Персональное фото
			529, //Salutation
			536, //Зал
			537,//Стол
			538,//Номер счета
			539, //Сумма счета
			540, //Реквизиты
		),
		27 => array(
			541,//Participant first name
			542,//Participant last name
			544,//Job title
			545,//Telephone
			593,//Skype
			546,//E-mail
			547,//Please confirm your e-mail
			548,//Alternative e-mail
			549,//Персональное фото
			543, //Salutation
			550, //Зал
			551,//Стол
			552,//Номер счета
			553, //Сумма счета
			554, //Реквизиты
		),
		28 => array(
			555,//Participant first name
			556,//Participant last name
			558,//Job title
			559,//Telephone
			595,//Skype
			560,//E-mail
			561,//Please confirm your e-mail
			562,//Alternative e-mail
			563,//Персональное фото
			557, //Salutation
			564, //Зал
			565,//Стол
			566,//Номер счета
			567, //Сумма счета
			568, //Реквизиты
		),
		29 => array(
			572,//Participant first name
			573,//Participant last name
			574,//Job title
			575,//Telephone
			587,//Skype
			576,//E-mail
			577,//Please confirm your e-mail
			578,//Alternative e-mail
			579,//Персональное фото
			580, //Salutation
			581, //Зал
			582,//Стол
			583,//Номер счета
			584, //Сумма счета
			585, //Реквизиты
		)
	);
	private static $arAnswerIDByForm = array(
		4 => array(
			84,//Participant first name
			85,//Participant last name
			87,//Job title
			88,//Telephone
			1474,//Skype
			89,//E-mail
			90,//Please confirm your e-mail
			91,//Alternative e-mail
			195,//Персональное фото
			"SIMPLE_QUESTION_889",//Salutation
			"SIMPLE_QUESTION_732", //Зал
			1319, //Стол
			1336, //Номер счета
			1337,//Сумма счета
			"SIMPLE_QUESTION_667", //Реквизиты
		),//Участники Представители Москва Весна
		5 => array(
			92,//Participant first name
			93,//Participant last name
			95,//Job title
			96,//Telephone
			1473,//Skype
			97,//E-mail
			98,//Please confirm your e-mail
			99,//Alternative e-mail
			196,//Персональное фото
			"SIMPLE_QUESTION_189", //Salutation
			"SIMPLE_QUESTION_386", //Зал
			1325, //Стол
			1342,//Номер счета
			1343,//Сумма счета
			"SIMPLE_QUESTION_758",//Реквизиты
		),//Участники Представители Баку
		6 => array(
			100,//Participant first name
			101,//Participant last name
			103,//Job title
			104,//Telephone
			1477,//Skype
			105,//E-mail
			106,//Please confirm your e-mail
			107,//Alternative e-mail
			197,//Персональное фото
			"SIMPLE_QUESTION_120",//Salutation
			"SIMPLE_QUESTION_286",//Зал
			1327, //Стол
			1346,//Номер счета
			1347,//Сумма счета
			"SIMPLE_QUESTION_254",//Реквизиты
		),//Участники Представители Киев
		7 => array(
			108,//Participant first name
			109,//Participant last name
			111,//Job title
			112,//Telephone
			1478,//Skype
			113,//E-mail
			114,//Please confirm your e-mail
			115,//Alternative e-mail
			198,//Персональное фото
			"SIMPLE_QUESTION_270", //Salutation
			"SIMPLE_QUESTION_428", //Зал
			1329, //Стол
			1350,//Номер счета
			1351,//Сумма счета
			"SIMPLE_QUESTION_330"//Реквизиты
		),//Участники Представители Алматы
		8 => array(
			116,//Participant first name
			117,//Participant last name
			119,//Job title
			120,//Telephone
			1479,//Skype
			121,//E-mail
			122,//Please confirm your e-mail
			123,//Alternative e-mail
			199,//Персональное фото
			"SIMPLE_QUESTION_888", //Salutation
			"SIMPLE_QUESTION_824", //Зал
			1331, //Стол
			1354,//Номер счета
			1355,//Сумма счета
			"SIMPLE_QUESTION_183",//Реквизиты
		),//Участники Представители Москва Осень
		25 => array(
			1213,//Participant first name
			1214,//Participant last name
			1215,//Job title
			1216,//Telephone
			1480,//Skype
			1217,//E-mail
			1218,//Please confirm your e-mail
			1219,//Alternative e-mail
			1220,//Персональное фото
			"SIMPLE_QUESTION_889", //Salutation
			"SIMPLE_QUESTION_713",//Зал
			1333, //Стол
			1358,//Номер счета
			1359,//Сумма счета
			"SIMPLE_QUESTION_391",//Реквизиты
		),//Участники Представители Москва Весна - 2015
		26 => array(
			1368,//Participant first name
			1369,//Participant last name
			1374,//Job title
			1375,//Telephone
			1482,//Skype
			1376,//E-mail
			1377,//Please confirm your e-mail
			1378,//Alternative e-mail
			1379,//Персональное фото
			"SIMPLE_QUESTION_270", //Salutation
			"SIMPLE_QUESTION_428",//Зал
			1382, //Стол
			1383,//Номер счета
			1384,//Сумма счета
			"SIMPLE_QUESTION_330",//Реквизиты
		),//Участники Представители Алматы 2015
		27 => array(
			1387,//Participant first name
			1388,//Participant last name
			1393,//Job title
			1394,//Telephone
			1481,//Skype
			1395,//E-mail
			1396,//Please confirm your e-mail
			1397,//Alternative e-mail
			1398,//Персональное фото
			"SIMPLE_QUESTION_120", //Salutation
			"SIMPLE_QUESTION_286",//Зал
			1401, //Стол
			1402,//Номер счета
			1403,//Сумма счета
			"SIMPLE_QUESTION_254",//Реквизиты
		),//Участники Представители Киев 2015
		28 => array(
			1406,//Participant first name
			1407,//Participant last name
			1412,//Job title
			1413,//Telephone
			1483,//Skype
			1414,//E-mail
			1415,//Please confirm your e-mail
			1416,//Alternative e-mail
			1417,//Персональное фото
			"SIMPLE_QUESTION_888", //Salutation
			"SIMPLE_QUESTION_824",//Зал
			1420, //Стол
			1421,//Номер счета
			1422,//Сумма счета
			"SIMPLE_QUESTION_183",//Реквизиты
		),//Участники Представители Москва Осень 2015
		29 => array(
			1448,//Participant first name
			1449,//Participant last name
			1450,//Job title
			1451,//Telephone
			1475,//Skype
			1452,//E-mail
			1453,//Please confirm your e-mail
			1454,//Alternative e-mail
			1455,//Персональное фото
			"SIMPLE_QUESTION_889", //Salutation
			"SIMPLE_QUESTION_732",//Зал
			1469, //Стол
			1470,//Номер счета
			1471,//Сумма счета
			"SIMPLE_QUESTION_667",//Реквизиты
		),//Участники Представители Москва Весна 2016
	);
	private static $arSIDByForm = array(
		4 => array(
			"SIMPLE_QUESTION_446",//Participant first name
			"SIMPLE_QUESTION_551",//Participant last name
			"SIMPLE_QUESTION_729",//Job title
			"SIMPLE_QUESTION_394",//Telephone
			"SIMPLE_QUESTION_211",//Skype
			"SIMPLE_QUESTION_859",//E-mail
			"SIMPLE_QUESTION_585",//Please confirm your e-mail
			"SIMPLE_QUESTION_749",//Alternative e-mail
			"SIMPLE_QUESTION_575",//Персональное фото
			"SIMPLE_QUESTION_889",//Salutation
			"SIMPLE_QUESTION_539",//Номер счета
			"SIMPLE_QUESTION_680",//Сумма счета
			"SIMPLE_QUESTION_667",//Реквизиты
			"SIMPLE_QUESTION_148",//Стол
			"SIMPLE_QUESTION_732",//Зал
		),//Участники Представители Москва Весна
		5 => array(
			"SIMPLE_QUESTION_708",//Participant first name
			"SIMPLE_QUESTION_599",//Participant last name
			"SIMPLE_QUESTION_895",//Job title
			"SIMPLE_QUESTION_622",//Telephone
			"SIMPLE_QUESTION_141",//Skype
			"SIMPLE_QUESTION_650",//E-mail
			"SIMPLE_QUESTION_294",//Please confirm your e-mail
			"SIMPLE_QUESTION_359",//Alternative e-mail
			"SIMPLE_QUESTION_503",//Персональное фото
			"SIMPLE_QUESTION_189",//Salutation
			"SIMPLE_QUESTION_820",//Номер счета
			"SIMPLE_QUESTION_527",//Сумма счета
			"SIMPLE_QUESTION_758",//Реквизиты
			"SIMPLE_QUESTION_994",//Стол
			"SIMPLE_QUESTION_386",//Зал
		),//Участники Представители Баку
		6 => array(
			"SIMPLE_QUESTION_896",//Participant first name
			"SIMPLE_QUESTION_409",//Participant last name
			"SIMPLE_QUESTION_468",//Job title
			"SIMPLE_QUESTION_992",//Telephone
			"SIMPLE_QUESTION_318",//Skype
			"SIMPLE_QUESTION_279",//E-mail
			"SIMPLE_QUESTION_857",//Please confirm your e-mail
			"SIMPLE_QUESTION_527",//Alternative e-mail
			"SIMPLE_QUESTION_975",//Персональное фото
			"SIMPLE_QUESTION_120",//Salutation
			"SIMPLE_QUESTION_868",//Номер счета
			"SIMPLE_QUESTION_851",//Сумма счета
			"SIMPLE_QUESTION_254",//Реквизиты
			"SIMPLE_QUESTION_471",//Стол
			"SIMPLE_QUESTION_286",//Зал
		),//Участники Представители Киев
		7 => array(
			"SIMPLE_QUESTION_948",//Participant first name
			"SIMPLE_QUESTION_159",//Participant last name
			"SIMPLE_QUESTION_993",//Job title
			"SIMPLE_QUESTION_434",//Telephone
			"SIMPLE_QUESTION_563",//Skype
			"SIMPLE_QUESTION_742",//E-mail
			"SIMPLE_QUESTION_111",//Please confirm your e-mail
			"SIMPLE_QUESTION_528",//Alternative e-mail
			"SIMPLE_QUESTION_800",//Персональное фото
			"SIMPLE_QUESTION_270",//Salutation
			"SIMPLE_QUESTION_997",//Номер счета
			"SIMPLE_QUESTION_833",//Сумма счета
			"SIMPLE_QUESTION_330",//Реквизиты
			"SIMPLE_QUESTION_778",//Стол
			"SIMPLE_QUESTION_428",//Зал
		),//Участники Представители Алматы
		8 => array(
			"SIMPLE_QUESTION_119",//Participant first name
			"SIMPLE_QUESTION_869",//Participant last name
			"SIMPLE_QUESTION_652",//Job title
			"SIMPLE_QUESTION_227",//Telephone
			"SIMPLE_QUESTION_686",//Skype
			"SIMPLE_QUESTION_786",//E-mail
			"SIMPLE_QUESTION_321",//Please confirm your e-mail
			"SIMPLE_QUESTION_294",//Alternative e-mail
			"SIMPLE_QUESTION_772",//Персональное фото
			"SIMPLE_QUESTION_888",//Salutation
			"SIMPLE_QUESTION_638",//Номер счета
			"SIMPLE_QUESTION_168",//Сумма счета
			"SIMPLE_QUESTION_183",//Реквизиты
			"SIMPLE_QUESTION_214",//Стол
			"SIMPLE_QUESTION_824",//Зал
		),//Участники Представители Москва Осень
		25 => array(
			"SIMPLE_QUESTION_446",//Participant first name
			"SIMPLE_QUESTION_551",//Participant last name
			"SIMPLE_QUESTION_729",//Job title
			"SIMPLE_QUESTION_394",//Telephone
			"SIMPLE_QUESTION_119",//Skype
			"SIMPLE_QUESTION_859",//E-mail
			"SIMPLE_QUESTION_585",//Please confirm your e-mail
			"SIMPLE_QUESTION_749",//Alternative e-mail
			"SIMPLE_QUESTION_575",//Персональное фото
			"SIMPLE_QUESTION_889",//Salutation
			"SIMPLE_QUESTION_275",//Номер счета
			"SIMPLE_QUESTION_542",//Сумма счета
			"SIMPLE_QUESTION_391",//Реквизиты
			"SIMPLE_QUESTION_418",//Стол
			"SIMPLE_QUESTION_713",//Зал
		),//Участники Представители Москва Весна - 2015
		26 => array(
			"SIMPLE_QUESTION_948",//Participant first name
			"SIMPLE_QUESTION_159",//Participant last name
			"SIMPLE_QUESTION_993",//Job title
			"SIMPLE_QUESTION_434",//Telephone
			"SIMPLE_QUESTION_495",//Skype
			"SIMPLE_QUESTION_742",//E-mail
			"SIMPLE_QUESTION_111",//Please confirm your e-mail
			"SIMPLE_QUESTION_528",//Alternative e-mail
			"SIMPLE_QUESTION_800",//Персональное фото
			"SIMPLE_QUESTION_270",//Salutation
			"SIMPLE_QUESTION_997",//Номер счета
			"SIMPLE_QUESTION_833",//Сумма счета
			"SIMPLE_QUESTION_330",//Реквизиты
			"SIMPLE_QUESTION_778",//Стол
			"SIMPLE_QUESTION_428",//Зал
		),//Участники Представители Алматы 2015
		27 => array(
			"SIMPLE_QUESTION_896",//Participant first name
			"SIMPLE_QUESTION_409",//Participant last name
			"SIMPLE_QUESTION_468",//Job title
			"SIMPLE_QUESTION_992",//Telephone
			"SIMPLE_QUESTION_952",//Skype
			"SIMPLE_QUESTION_279",//E-mail
			"SIMPLE_QUESTION_857",//Please confirm your e-mail
			"SIMPLE_QUESTION_527",//Alternative e-mail
			"SIMPLE_QUESTION_975",//Персональное фото
			"SIMPLE_QUESTION_120",//Salutation
			"SIMPLE_QUESTION_868",//Номер счета
			"SIMPLE_QUESTION_851",//Сумма счета
			"SIMPLE_QUESTION_254",//Реквизиты
			"SIMPLE_QUESTION_471",//Стол
			"SIMPLE_QUESTION_286",//Зал
		),//Участники Представители Киев 2015
		28 => array(
			"SIMPLE_QUESTION_119",//Participant first name
			"SIMPLE_QUESTION_869",//Participant last name
			"SIMPLE_QUESTION_652",//Job title
			"SIMPLE_QUESTION_227",//Telephone
			"SIMPLE_QUESTION_830",//Skype
			"SIMPLE_QUESTION_786",//E-mail
			"SIMPLE_QUESTION_321",//Please confirm your e-mail
			"SIMPLE_QUESTION_294",//Alternative e-mail
			"SIMPLE_QUESTION_772",//Персональное фото
			"SIMPLE_QUESTION_888",//Salutation
			"SIMPLE_QUESTION_638",//Номер счета
			"SIMPLE_QUESTION_168",//Сумма счета
			"SIMPLE_QUESTION_183",//Реквизиты
			"SIMPLE_QUESTION_214",//Стол
			"SIMPLE_QUESTION_824",//Зал
		),//Участники Представители Москва Осень 2015
		29 => array(
			"SIMPLE_QUESTION_446",//Participant first name
			"SIMPLE_QUESTION_551",//Participant last name
			"SIMPLE_QUESTION_729",//Job title
			"SIMPLE_QUESTION_394",//Telephone
			"SIMPLE_QUESTION_400",//Skype
			"SIMPLE_QUESTION_859",//E-mail
			"SIMPLE_QUESTION_585",//Please confirm your e-mail
			"SIMPLE_QUESTION_749",//Alternative e-mail
			"SIMPLE_QUESTION_575",//Персональное фото
			"SIMPLE_QUESTION_889",//Salutation
			"SIMPLE_QUESTION_539",//Номер счета
			"SIMPLE_QUESTION_680",//Сумма счета
			"SIMPLE_QUESTION_667",//Реквизиты
			"SIMPLE_QUESTION_148",//Стол
			"SIMPLE_QUESTION_732",//Зал
		),//Участники Представители Москва Весна 2016
	);
	static function getAnswerRelBase($baseQ, $needFormID)
	{
		if(empty($baseQ) || !intval($needFormID))
		{
			return false;
		}
		$index = array_search($baseQ, self::$arAnswerIDByForm[4]);
		return self::$arAnswerIDByForm[$needFormID][$index];
	}
	static function getSIDRelBase($baseQ, $needFormID)
	{
		if(empty($baseQ) || !intval($needFormID))
		{
			return false;
		}
		$index = array_search($baseQ, self::$arSIDByForm[4]);
		return self::$arSIDByForm[$needFormID][$index];
	}
	static function getAnswerSalutationRelBase($baseQ, $needFormID)
	{
		if(empty($baseQ) || !intval($needFormID))
		{
			return false;
		}
		$index = array_search($baseQ, self::$arAnswerSalutationIDByForm[4]);
		return self::$arAnswerSalutationIDByForm[$needFormID][$index];
	}

	static function getAnswerSalutationBase($answID, $needFormID)
	{
		if(empty($answID) || !intval($needFormID))
		{
			return false;
		}
		$index = array_search($answID, self::$arAnswerSalutationIDByForm[$needFormID]);

		return self::$arAnswerSalutationIDByForm[4][$index];
	}

	static $arCParticipantField = array(
		"Групповые действия",
		"ID пользователя",
		"Логин",
		"Пароль",
		"Company or hotel name",
		"Area of the business",
		"City",
		"Country",
		"Representative",
		"Telephone number",
		"Skype",
		"Email",
		"Table",
		"Hall",
		"Номер счета",
		"Сумма счета",
		"Действия"
	);
	static $arUCParticipantField = array(
		"Групповые действия",
		"Company or hotel name",
		"Area of the business",
		"City",
		"Country",
		"Adress",
		"web-site",
		"Description",
		"Representative",
		"Title",
		"Job title",
		"Telephone number",
		"Skype",
		"Email",
		"Company or hotel name",
		"Действия"
	);
	static $arExelCompParticipantField = array(
		"NAMES" => array(
			"0" => "Company or hotel name",
			"1" => "Area of the business",
			"2" => "Adress",
			"3" => "City",
			"4" => "Country",
			"5" => "Web-site",
			"6" => "Description",
			"7" => "Priority destinations",
			"8" => "Priority destinations",
			"9" => "Priority destinations",
			"10" => "Priority destinations",
			"11" => "Priority destinations",
			"12" => "Priority destinations"
		),
		"QUEST_ID" => array(
			"0" => "17",
			"1" => "19",
			"2" => "20",
			"3" => "21",
			"4" => "22",
			"5" => "23",
			"6" => "24",
			"7" => "25",
			"8" => "26",
			"9" => "27",
			"10" => "28",
			"11" => "29",
			"12" => "30",
		),
		"QUEST_CODE" => array(
			"0" => "SIMPLE_QUESTION_988",
			"1" => "SIMPLE_QUESTION_284",
			"2" => "SIMPLE_QUESTION_295",
			"3" => "SIMPLE_QUESTION_320",
			"4" => "SIMPLE_QUESTION_778",
			"5" => "SIMPLE_QUESTION_501",
			"6" => "SIMPLE_QUESTION_163",
			"7" => "SIMPLE_QUESTION_876",
			"8" => "SIMPLE_QUESTION_367",
			"9" => "SIMPLE_QUESTION_328",
			"10" => "SIMPLE_QUESTION_459",
			"11" => "SIMPLE_QUESTION_931",
			"12" => "SIMPLE_QUESTION_445"
		),
		"ANS_TYPE" => array(
			"0" => "USER_TEXT",
			"1" => "ANSWER_TEXT",
			"2" => "USER_TEXT",
			"3" => "USER_TEXT",
			"4" => "USER_TEXT",
			"5" => "USER_TEXT",
			"6" => "USER_TEXT",
			"7" => "ANSWER_TEXT",
			"8" => "ANSWER_TEXT",
			"9" => "ANSWER_TEXT",
			"10" => "ANSWER_TEXT",
			"11" => "ANSWER_TEXT",
			"12" => "ANSWER_TEXT"
		),
		"NAMES_AR" => array(
			"0" => "COMPANY",
			"1" => "AREA",
			"2" => "ADRESS",
			"3" => "CITY",
			"4" => "COUNTRY",
			"5" => "SITE",
			"6" => "DESC",
			"7" => "DESTINITIONS",
			"8" => "DESTINITIONS",
			"9" => "DESTINITIONS",
			"10" => "DESTINITIONS",
			"11" => "DESTINITIONS",
			"12" => "DESTINITIONS"
		),
	);
	static $arExelRepParticipantField = array(
		"NAMES" => array(
			"0" => "Title (salutation)",
			"1" => "Participant first name",
			"2" => "Participant last name",
			"3" => "Job Title",
			"4" => "Telephone number",
			"5" => "Skype",
			"6" => "Email",
			"7" => "Alternative e-mail",
			"8" => "Title College (Salutation)",
			"9" => "First Name College",
			"10" => "Last Name College",
			"11" => "Job Title College",
			"12" => "Email College",
			"13" => "Table",
			"14" => "Hall"
		),
		"QUEST_CODE" => array(
			"0" => "SIMPLE_QUESTION_889",
			"1" => "SIMPLE_QUESTION_446",
			"2" => "SIMPLE_QUESTION_551",
			"3" => "SIMPLE_QUESTION_729",
			"4" => "SIMPLE_QUESTION_394",
			"5" => "SIMPLE_QUESTION_211",
			"6" => "SIMPLE_QUESTION_859",
			"7" => "SIMPLE_QUESTION_749",
			"8" => "SIMPLE_QUESTION_889",
			"9" => "SIMPLE_QUESTION_446",
			"10" => "SIMPLE_QUESTION_551",
			"11" => "SIMPLE_QUESTION_729",
			"12" => "SIMPLE_QUESTION_859",
			"13" => "SIMPLE_QUESTION_148",
			"14" => "SIMPLE_QUESTION_732"
		),//Участники Представители Москва Весна
		"ANS_TYPE" => array(
			"0" => "ANSWER_TEXT",
			"1" => "USER_TEXT",
			"2" => "USER_TEXT",
			"3" => "USER_TEXT",
			"4" => "USER_TEXT",
			"5" => "USER_TEXT",
			"6" => "USER_TEXT",
			"7" => "USER_TEXT",
			"8" => "ANSWER_TEXT",
			"9" => "USER_TEXT",
			"10" => "USER_TEXT",
			"11" => "USER_TEXT",
			"12" => "USER_TEXT",
			"13" => "USER_TEXT",
			"14" => "ANSWER_TEXT"
		),
		"NAMES_AR" => array(
			"0" => "TITLE",
			"1" => "F_NAME",
			"2" => "L_NAME",
			"3" => "JOB",
			"4" => "PHONE",
			"5" => "SKYPE",
			"6" => "EMAIL",
			"7" => "EMAIL_ALT",
			"8" => "TITLE_COL",
			"9" => "F_NAME_COL",
			"10" => "L_NAME_COL",
			"11" => "JOB_COL",
			"12" => "EMAIL_COL",
			"13" => "TABLE",
			"14" => "HALL"
		),
	);
	static $arExelGuestField = array(
		"NAMES" => array(
			"0" => "Компания",
			"1" => "Вид деятельности",
			"2" => "Имя",
			"3" => "Фамилия",
			"4" => "Должность",
			"5" => "Имя коллеги (на утро)",
			"6" => "Фамилия коллеги (на утро)",
			"7" => "Должность коллеги (на утро)",
			"8" => "E-mail коллеги (на утро)",
			"9" => "Моб. телефон",
			"10" => "Адрес",
			"11" => "Город",
			"12" => "Страна",
			"13" => "Страна (other)",
			"14" => "Индекс",
			"15" => "Телефон",
			"16" => "Skype",
			"17" => "E-mail",
			"18" => "Web-site компании",
			"19" => "Приоритетные направления",
			"20" => "Приоритетные направления",
			"21" => "Приоритетные направления",
			"22" => "Приоритетные направления",
			"23" => "Приоритетные направления",
			"24" => "Приоритетные направления",
			"25" => "Описание компании",
			"26" => "Зал",
			"27" => "Стол"
		),
		"QUEST_CODE" => array(
			"0" => "SIMPLE_QUESTION_115",
			"1" => "SIMPLE_QUESTION_677",
			"2" => "SIMPLE_QUESTION_750",
			"3" => "SIMPLE_QUESTION_823",
			"4" => "SIMPLE_QUESTION_391",
			"5" => "SIMPLE_QUESTION_816",
			"6" => "SIMPLE_QUESTION_596",
			"7" => "SIMPLE_QUESTION_304",
			"8" => "SIMPLE_QUESTION_278",
			"9" => "SIMPLE_QUESTION_844",
			"10" => "SIMPLE_QUESTION_773",
			"11" => "SIMPLE_QUESTION_672",
			"12" => "SIMPLE_QUESTION_678",
			"13" => "SIMPLE_QUESTION_243",
			"14" => "SIMPLE_QUESTION_756",
			"15" => "SIMPLE_QUESTION_636",
			"16" => "SIMPLE_QUESTION_111",
			"17" => "SIMPLE_QUESTION_373",
			"18" => "SIMPLE_QUESTION_552",
			"19" => "SIMPLE_QUESTION_383",
			"20" => "SIMPLE_QUESTION_244",
			"21" => "SIMPLE_QUESTION_212",
			"22" => "SIMPLE_QUESTION_497",
			"23" => "SIMPLE_QUESTION_526",
			"24" => "SIMPLE_QUESTION_878",
			"25" => "SIMPLE_QUESTION_166",
			"26" => "SIMPLE_QUESTION_762",
			"27" => "SIMPLE_QUESTION_211"
		),
		"ANS_TYPE" => array(
			"0" => "USER_TEXT",
			"1" => "ANSWER_TEXT",
			"2" => "USER_TEXT",
			"3" => "USER_TEXT",
			"4" => "USER_TEXT",
			"5" => "USER_TEXT",
			"6" => "USER_TEXT",
			"7" => "USER_TEXT",
			"8" => "USER_TEXT",
			"9" => "USER_TEXT",
			"10" => "USER_TEXT",
			"11" => "USER_TEXT",
			"12" => "ANSWER_TEXT",
			"13" => "USER_TEXT",
			"14" => "USER_TEXT",
			"15" => "USER_TEXT",
			"16" => "USER_TEXT",
			"17" => "USER_TEXT",
			"18" => "USER_TEXT",
			"19" => "ANSWER_TEXT",
			"20" => "ANSWER_TEXT",
			"21" => "ANSWER_TEXT",
			"22" => "ANSWER_TEXT",
			"23" => "ANSWER_TEXT",
			"24" => "ANSWER_TEXT",
			"25" => "USER_TEXT",
			"26" => "ANSWER_TEXT",
			"27" => "USER_TEXT"
		),
		"NAMES_AR" => array(
			"0" => "COMPANY",
			"1" => "AREA",
			"2" => "F_NAME",
			"3" => "L_NAME",
			"4" => "JOB",
			"5" => "F_NAME_COL",
			"6" => "L_NAME_COL",
			"7" => "JOB_COL",
			"8" => "EMAIL_COL",
			"9" => "MOB_PHONE",
			"10" => "ADRESS",
			"11" => "CITY",
			"12" => "COUNTRY",
			"13" => "COUNTRY",
			"14" => "INDEX",
			"15" => "PHONE",
			"16" => "SKYPE",
			"17" => "EMAIL",
			"18" => "SITE",
			"19" => "DESTINITIONS",
			"20" => "DESTINITIONS",
			"21" => "DESTINITIONS",
			"22" => "DESTINITIONS",
			"23" => "DESTINITIONS",
			"24" => "DESTINITIONS",
			"25" => "DESC",
			"26" => "HALL",
			"27" => "TABLE"
		),
	);
	static $arExelEvGuestField = array(
		"NAMES" => array(
			"0" => "Компания",
			"1" => "Вид деятельности",
			"2" => "Адрес",
			"3" => "Индекс",
			"4" => "Город",
			"5" => "Страна",
			"6" => "Страна (other)",
			"7" => "Имя",
			"8" => "Фамилия",
			"9" => "Должность",
			"10" => "Телефон",
			"11" => "Моб. телефон",
			"12" => "Скайп",
			"13" => "E-mail",
			"14" => "Web-site компании",
			"15" => "Имя коллеги 1",
			"16" => "Фамилия коллеги 1",
			"17" => "Должность коллеги 1",
			"18" => "E-mail коллеги 1",
			"19" => "Имя коллеги 2",
			"20" => "Фамилия коллеги 2",
			"21" => "Должность коллеги 2",
			"22" => "E-mail коллеги 2",
			"23" => "Имя коллеги 3",
			"24" => "Фамилия коллеги 3",
			"25" => "Должность коллеги 3",
			"26" => "E-mail коллеги 3"
		),
		"QUEST_CODE" => array(
			"0" => "SIMPLE_QUESTION_115", //назв комп
			"1" => "SIMPLE_QUESTION_677", //вид деят
			"2" => "SIMPLE_QUESTION_773", //факт адр
			"3" => "SIMPLE_QUESTION_756", //индекс
			"4" => "SIMPLE_QUESTION_672", //город
			"5" => "SIMPLE_QUESTION_678", //страна
			"6" => "SIMPLE_QUESTION_243", //Страна (other)
			"7" => "SIMPLE_QUESTION_750", //Имя
			"8" => "SIMPLE_QUESTION_823", //Фамилия
			"9" => "SIMPLE_QUESTION_391", //Должность
			"10" => "SIMPLE_QUESTION_636", //Телефон
			"11" => "SIMPLE_QUESTION_844", //Мобильный телефон
			"12" => "SIMPLE_QUESTION_111", //Skype
			"13" => "SIMPLE_QUESTION_373", //E-mail
			"14" => "SIMPLE_QUESTION_552", //http://
			"15" => "SIMPLE_QUESTION_367", //Имя коллеги 1
			"16" => "SIMPLE_QUESTION_482", //Фамилия коллеги 1
			"17" => "SIMPLE_QUESTION_187", //Должность коллеги 1
			"18" => "SIMPLE_QUESTION_421", //E-mail коллеги 1
			"19" => "SIMPLE_QUESTION_225", //Имя коллеги 2
			"20" => "SIMPLE_QUESTION_770", //Фамилия коллеги 2
			"21" => "SIMPLE_QUESTION_280", //Должность коллеги 2
			"22" => "SIMPLE_QUESTION_384", //E-mail коллеги 2
			"23" => "SIMPLE_QUESTION_765", //Имя коллеги 3
			"24" => "SIMPLE_QUESTION_627", //Фамилия коллеги 3
			"25" => "SIMPLE_QUESTION_788", //Должность коллеги 3
			"26" => "SIMPLE_QUESTION_230" //E-mail коллеги 3
		),
		"ANS_TYPE" => array(
			"0" => "USER_TEXT",//назв комп
			"1" => "ANSWER_TEXT",//вид деят
			"2" => "USER_TEXT",//факт адр
			"3" => "USER_TEXT",//индекс
			"4" => "USER_TEXT",//город
			"5" => "ANSWER_TEXT",//страна
			"6" => "USER_TEXT",//Страна (other)
			"7" => "USER_TEXT",//Имя
			"8" => "USER_TEXT",
			"9" => "USER_TEXT",
			"10" => "USER_TEXT",
			"11" => "USER_TEXT",
			"12" => "USER_TEXT",
			"13" => "USER_TEXT",
			"14" => "USER_TEXT",
			"15" => "USER_TEXT",
			"16" => "USER_TEXT",
			"17" => "USER_TEXT",
			"18" => "USER_TEXT",
			"19" => "USER_TEXT",
			"20" => "USER_TEXT",
			"21" => "USER_TEXT",
			"22" => "USER_TEXT",
			"23" => "USER_TEXT",
			"24" => "USER_TEXT",
			"25" => "USER_TEXT",
			"26" => "USER_TEXT"
		),
		"NAMES_AR" => array(
			"0" => "COMPANY",
			"1" => "AREA",
			"2" => "ADRESS",
			"3" => "INDEX",
			"4" => "CITY",
			"5" => "COUNTRY",
			"6" => "COUNTRY",
			"7" => "F_NAME",
			"8" => "L_NAME",
			"9" => "JOB",
			"10" => "PHONE",
			"11" => "MOB_PHONE",
			"12" => "SKYPE",
			"13" => "EMAIL",
			"14" => "SITE",
			"15" => "F_NAME_COL1",
			"16" => "L_NAME_COL1",
			"17" => "JOB_COL1",
			"18" => "EMAIL_COL1",
			"19" => "F_NAME_COL2",
			"20" => "L_NAME_COL2",
			"21" => "JOB_COL2",
			"22" => "EMAIL_COL2",
			"23" => "F_NAME_COL3",
			"24" => "L_NAME_COL3",
			"25" => "JOB_COL3",
			"26" => "EMAIL_COL3"
		),
	);
	private static $arFormGuestQuestions = array(
		"NAMES" => array(
			"0"=>"Название компании",
			"1"=>"Вид деятельности",
			"2"=>"Фактический адрес компании",
			"3"=>"Индекс",
			"4"=>"Город",
			"5"=>"Страна",
			"6"=>"Страна (other)",
			"7"=>"Имя",
			"8"=>"Фамилия",
			"9"=>"Должность",
			"10"=>"Телефон",
			"11"=>"Мобильный телефон",
			"12"=>"Скайп",
			"13"=>"E-mail",
			"14"=>"Введите E-mail ещё раз",
			"15"=>"http://",
			"16"=>"Имя коллеги 1",
			"17"=>"Фамилия коллеги 1",
			"18"=>"Должность коллеги 1",
			"19"=>"E-mail коллеги 1",
			"20"=>"Имя коллеги 2",
			"21"=>"Фамилия коллеги 2",
			"22"=>"E-mail коллеги 2",
			"23"=>"Должность коллеги 2",
			"24"=>"Имя коллеги 3",
			"25"=>"Фамилия коллеги 3",
			"26"=>"Должность коллеги 3",
			"27"=>"E-mail коллеги 3",
			"LOGIN"=>"Введите логин/гостевое имя",
			"29"=>"Введите пароль",
			"30"=>"Повторите пароль",
			"31"=>"Введите краткое описание",
			"32"=>"North America",
			"33"=>"Europe",
			"34"=>"South America",
			"35"=>"Africa",
			"36"=>"Asia",
			"37"=>"Oceania",
			"38"=>"Имя коллеги (на утро)",
			"39"=>"Фамилия коллеги (на утро)",
			"40"=>"Должность коллеги (на утро)",
			"41"=>"E-mail коллеги (на утро)",
			"42"=>"Утро",
			"43"=>"Вечер",
			"44"=>"Зал",
			"45"=>"Стол"
		),
		10 => array(
			"0"=>"107",
			"1"=>"108",
			"2"=>"109",
			"3"=>"110",
			"4"=>"111",
			"5"=>"112",
			"6"=>"305",
			"7"=>"113",
			"8"=>"114",
			"9"=>"115",
			"10"=>"116",
			"11"=>"569", //моб телефон
			"12"=>"596", //скайп
			"13"=>"117",
			"14"=>"118",
			"15"=>"119",
			"16"=>"120",
			"17"=>"121",
			"18"=>"122",
			"19"=>"123",
			"20"=>"124",
			"21"=>"125",
			"22"=>"126",
			"23"=>"127",
			"24"=>"128",
			"25"=>"129",
			"26"=>"130",
			"27"=>"131",
			"LOGIN"=>"132",
			"29"=>"133",
			"30"=>"134",
			"31"=>"135",
			"32"=>"136",
			"33"=>"137",
			"34"=>"138",
			"35"=>"139",
			"36"=>"475",
			"37"=>"476",
			"38"=>"477",
			"39"=>"478",
			"40"=>"479",
			"41"=>"480",
			"42"=>"481",
			"43"=>"482",
			"44"=>"570",
			"45"=>"571"
		),
		21 => array(
			"0"=>"339",
			"1"=>"340",
			"2"=>"341",
			"3"=>"342",
			"4"=>"343",
			"5"=>"344",
			"6"=>"345",
			"7"=>"346",
			"8"=>"347",
			"9"=>"348",
			"10"=>"349",
			"11"=>"350",
			"12"=>"351",
			"13"=>"352",
			"14"=>"353",
			"15"=>"354",
			"16"=>"355",
			"17"=>"356",
			"18"=>"357",
			"19"=>"358",
			"20"=>"359",
			"21"=>"360",
			"22"=>"361",
			"23"=>"362",
			"24"=>"363",
			"25"=>"364",
			"LOGIN"=>"365",
			"27"=>"366",
			"28"=>"367",
			"29"=>"368",
			"30"=>"369",
			"31"=>"370",
			"32"=>"",
			"33"=>"371",
			"34"=>"372",
			"35"=>"",
			"36"=>"",
			"37"=>"",
			"38"=>"",
			"39"=>"",
			"40"=>"",
			"41"=>"",
		),
		22 => array(
			"0"=>"373",
			"1"=>"374",
			"2"=>"375",
			"3"=>"376",
			"4"=>"377",
			"5"=>"378",
			"6"=>"379",
			"7"=>"380",
			"8"=>"381",
			"9"=>"382",
			"10"=>"383",
			"11"=>"384",
			"12"=>"385",
			"13"=>"386",
			"14"=>"387",
			"15"=>"388",
			"16"=>"389",
			"17"=>"390",
			"18"=>"391",
			"19"=>"392",
			"20"=>"393",
			"21"=>"394",
			"22"=>"395",
			"23"=>"396",
			"24"=>"397",
			"25"=>"398",
			"LOGIN"=>"399",
			"27"=>"400",
			"28"=>"401",
			"29"=>"402",
			"30"=>"403",
			"31"=>"404",
			"32"=>"",
			"33"=>"405",
			"34"=>"406",
			"35"=>"",
			"36"=>"",
			"37"=>"",
			"38"=>"",
			"39"=>"",
			"40"=>"",
			"41"=>"",
		),
		23 => array(
			"0"=>"407",
			"1"=>"408",
			"2"=>"409",
			"3"=>"410",
			"4"=>"411",
			"5"=>"412",
			"6"=>"413",
			"7"=>"414",
			"8"=>"415",
			"9"=>"416",
			"10"=>"417",
			"11"=>"418",
			"12"=>"419",
			"13"=>"420",
			"14"=>"421",
			"15"=>"422",
			"16"=>"423",
			"17"=>"424",
			"18"=>"425",
			"19"=>"426",
			"20"=>"427",
			"21"=>"428",
			"22"=>"429",
			"23"=>"430",
			"24"=>"431",
			"25"=>"432",
			"LOGIN"=>"433",
			"27"=>"434",
			"28"=>"435",
			"29"=>"436",
			"30"=>"437",
			"31"=>"438",
			"32"=>"",
			"33"=>"439",
			"34"=>"440",
			"35"=>"",
			"36"=>"",
			"37"=>"",
			"38"=>"",
			"39"=>"",
			"40"=>"",
			"41"=>"",
		),
		24 => array(
			"0"=>"441",
			"1"=>"442",
			"2"=>"443",
			"3"=>"444",
			"4"=>"445",
			"5"=>"446",
			"6"=>"447",
			"7"=>"448",
			"8"=>"449",
			"9"=>"450",
			"10"=>"451",
			"11"=>"452",
			"12"=>"453",
			"13"=>"454",
			"14"=>"455",
			"15"=>"456",
			"16"=>"457",
			"17"=>"458",
			"18"=>"459",
			"19"=>"460",
			"20"=>"461",
			"21"=>"462",
			"22"=>"463",
			"23"=>"464",
			"24"=>"465",
			"25"=>"466",
			"26"=>"467",
			"27"=>"468",
			"28"=>"469",
			"29"=>"470",
			"30"=>"471",
			"31"=>"472",
			"32"=>"",
			"33"=>"473",
			"34"=>"474",
			"35"=>"",
			"36"=>"",
			"37"=>"",
			"38"=>"",
			"39"=>"",
			"40"=>"",
			"41"=>"",
		)
	);
	private static $arFormGuestQuestionsSID = array(
		"NAMES" => array(
			"0"=>"Название компании",
			"1"=>"Вид деятельности",
			"2"=>"Фактический адрес компании",
			"3"=>"Индекс",
			"4"=>"Город",
			"5"=>"Страна",
			"6"=>"Страна (other)",
			"7"=>"Имя",
			"8"=>"Фамилия",
			"9"=>"Должность",
			"10"=>"Телефон",
			"11"=>"Мобильный телефон",
			"12"=>"Скайп",
			"13"=>"E-mail",
			"14"=>"Введите E-mail ещё раз",
			"15"=>"http://",
			"16"=>"Имя коллеги 1",
			"17"=>"Фамилия коллеги 1",
			"18"=>"Должность коллеги 1",
			"19"=>"E-mail коллеги 1",
			"20"=>"Имя коллеги 2",
			"21"=>"Фамилия коллеги 2",
			"22"=>"Должность коллеги 2",
			"23"=>"E-mail коллеги 2",
			"24"=>"Имя коллеги 3",
			"25"=>"Фамилия коллеги 3",
			"26"=>"Должность коллеги 3",
			"27"=>"E-mail коллеги 3",
			"28"=>"Введите логин/гостевое имя",
			"29"=>"Введите пароль",
			"30"=>"Повторите пароль",
			"31"=>"Введите краткое описание",
			"32"=>"North America",
			"33"=>"Europe",
			"34"=>"South America",
			"35"=>"Africa",
			"36"=>"Asia",
			"37"=>"Oceania",
			"38"=>"Имя коллеги (на утро)",
			"39"=>"Фамилия коллеги (на утро)",
			"40"=>"Должность коллеги (на утро)",
			"41"=>"E-mail коллеги (на утро)",
			"42"=>"Утро",
			"43"=>"Вечер",
			"44"=>"Зал",
			"45"=>"Стол"
		),
		"NAMES_AR" => array(
			"0"=>"COMPANY",
			"1"=>"AREA",
			"2"=>"ADRESS",
			"3"=>"INDEX",
			"4"=>"CITY",
			"5"=>"COUNTRY",
			"6"=>"COUNTRY_OTHER",
			"7"=>"F_NAME",
			"8"=>"L_NAME",
			"9"=>"JOB",
			"10"=>"PHONE",
			"11"=>"MOBILE_PHONE",
			"12"=>"SKYPE",
			"13"=>"EMAIL",
			"14"=>"EMAIL_REP",
			"15"=>"SITE",
			"16"=>"F_NAME_COL1",
			"17"=>"L_NAME_COL1",
			"18"=>"JOB_COL1",
			"19"=>"EMAIL_COL1",
			"20"=>"F_NAME_COL2",
			"21"=>"L_NAME_COL2",
			"22"=>"JOB_COL2",
			"23"=>"EMAIL_COL2",
			"24"=>"F_NAME_COL3",
			"25"=>"L_NAME_COL3",
			"26"=>"JOB_COL3",
			"27"=>"EMAIL_COL3",
			"28"=>"LOGIN",
			"29"=>"PASSWORD",
			"30"=>"PASSWORD_REP",
			"31"=>"DESC",
			"32"=>"DESTINITIONS",
			"33"=>"DESTINITIONS",
			"34"=>"DESTINITIONS",
			"35"=>"DESTINITIONS",
			"36"=>"DESTINITIONS",
			"37"=>"DESTINITIONS",
			"38"=>"F_NAME_COL",
			"39"=>"L_NAME_COL",
			"40"=>"JOB_COL",
			"41"=>"EMAIL_COL",
			"42"=>"MORNING",
			"43"=>"EVENING",
			"44"=>"HALL",
			"45"=>"TABLE"
		),
		"QUEST_CODE" => array(
			"0"=>"SIMPLE_QUESTION_115",
			"1"=>"SIMPLE_QUESTION_677",
			"2"=>"SIMPLE_QUESTION_773",
			"3"=>"SIMPLE_QUESTION_756",
			"4"=>"SIMPLE_QUESTION_672",
			"5"=>"SIMPLE_QUESTION_678",
			"6"=>"SIMPLE_QUESTION_243",
			"7"=>"SIMPLE_QUESTION_750",
			"8"=>"SIMPLE_QUESTION_823",
			"9"=>"SIMPLE_QUESTION_391",
			"10"=>"SIMPLE_QUESTION_636",
			"11"=>"SIMPLE_QUESTION_844",
			"12"=>"SIMPLE_QUESTION_111",
			"13"=>"SIMPLE_QUESTION_373",
			"14"=>"SIMPLE_QUESTION_279",
			"15"=>"SIMPLE_QUESTION_552",
			"16"=>"SIMPLE_QUESTION_367",
			"17"=>"SIMPLE_QUESTION_482",
			"18"=>"SIMPLE_QUESTION_187",
			"19"=>"SIMPLE_QUESTION_421",
			"20"=>"SIMPLE_QUESTION_225",
			"21"=>"SIMPLE_QUESTION_770",
			"22"=>"SIMPLE_QUESTION_280",
			"23"=>"SIMPLE_QUESTION_384",
			"24"=>"SIMPLE_QUESTION_765",
			"25"=>"SIMPLE_QUESTION_627",
			"26"=>"SIMPLE_QUESTION_788",
			"27"=>"SIMPLE_QUESTION_230",
			"28"=>"SIMPLE_QUESTION_474",
			"29"=>"SIMPLE_QUESTION_435",
			"30"=>"SIMPLE_QUESTION_300",
			"31"=>"SIMPLE_QUESTION_166",
			"32"=>"SIMPLE_QUESTION_383",
			"33"=>"SIMPLE_QUESTION_244",
			"34"=>"SIMPLE_QUESTION_212",
			"35"=>"SIMPLE_QUESTION_497",
			"36"=>"SIMPLE_QUESTION_526",
			"37"=>"SIMPLE_QUESTION_878",
			"38"=>"SIMPLE_QUESTION_816",
			"39"=>"SIMPLE_QUESTION_596",
			"40"=>"SIMPLE_QUESTION_304",
			"41"=>"SIMPLE_QUESTION_278",
			"42"=>"SIMPLE_QUESTION_836",
			"43"=>"SIMPLE_QUESTION_156",
			"44"=>"SIMPLE_QUESTION_762",
			"45"=>"SIMPLE_QUESTION_211"
		)
	);
	private static $arExhForm = array(
		358 => 8, //Москва, Россия. 2 октября 2014
		357 => 5, //Баку, Айзербайджан. 10 апреля 2014
		359 => 7, //Алматы, Казахстан. 26 сентября 2014
		360 => 6, //Киев, Украина. 23 сентября 2014
//		361 => 4, //Москва, Россия. 13 марта 2014 ** СТАРОЕ ЗНАЧЕНИЕ, МОЖНО ВЕРНУТЬ ТУТ
		361 => 29, //отдельная форма для москва весна 2016, была 4
		488 => 25, //Москва, Россия. 12 марта 2015
		3521 => 26, //Алматы, Казахстан. сентябрь 2015
		3522 => 27, //Киев, Украина. сентябрь 2015
		3523 => 28 //Москва, Россия. октябрь 2015
	);
	private static $arExhGuestForm = array(
		358 => 24, //Москва, Россия. 2 октября 2014
		357 => 21, //Баку, Айзербайджан. 10 апреля 2014
		359 => 23, //Алматы, Казахстан. 26 сентября 2014
		360 => 22, //Киев, Украина. 23 сентября 2014
		361 => 10, //Москва, Россия. 13 марта 2014
		488 => 10, //Москва, Россия. 12 марта 2015
		3523 => 24, //Москва, Россия. 2 октября 2014
		3521 => 23, //Алматы, Казахстан. 26 сентября 2014
		3522 => 22, //Киев, Украина. 23 сентября 2014
	);
	//id почтовых событий для гостей
	private static $arPostTemplateByExhibID = array(
		358 => array("GUEST_EVENING"=>82, "GUEST_MORNING"=>72), //Москва, Россия. 2 октября 2014
		357 => array("GUEST_EVENING"=>79, "GUEST_MORNING"=>75), //Баку, Айзербайджан. 10 апреля 2014
		359 => array("GUEST_EVENING"=>81, "GUEST_MORNING"=>73), //Алматы, Казахстан. 26 сентября 2014
		360 => array("GUEST_EVENING"=>80, "GUEST_MORNING"=>74), //Киев, Украина. 23 сентября 2014
		361 => array("GUEST_EVENING"=>71, "GUEST_MORNING"=>70), //Москва, Россия. 13 марта 2014
		488 => array("GUEST_EVENING"=>78, "GUEST_MORNING"=>76), //Москва, Россия. 12 марта 2015
		3523 => array("GUEST_EVENING"=>82, "GUEST_MORNING"=>72), //Москва, Россия. октябрь 2015
		3521 => array("GUEST_EVENING"=>81, "GUEST_MORNING"=>73), //Алматы, Казахстан. сентябрь 2015
		3522 => array("GUEST_EVENING"=>80, "GUEST_MORNING"=>74), //Киев, Украина. сентябрь 2015
	);
	private static $arExhProp = array(
		358 => array("UF_ID5", "UF_ID10"),//Москва, Россия. 2 октября 2014
		357 => array("UF_ID2", "UF_ID7"),//Баку, Айзербайджан. 10 апреля 2014
		359 => array("UF_ID4", "UF_ID9"),//Алматы, Казахстан. 26 сентября 2014
		360 => array("UF_ID3", "UF_ID8"),//Киев, Украина. 23 сентября 2014
//		361 => array("UF_ID", "UF_ID6"),//Москва, Россия. 13 марта 2014 ** СТАРОЕ ЗНАЧЕНИЕ, МОЖНО ВЕРНУТЬ ТУТ
		361 => array("UF_MSCSPRING2016", "UF_MSCSPRING2016COL"),//Москва, Весна 2016
		488 => array("UF_ID11", "UF_ID12"),//Москва, Россия. 12 марта 2015
		3521 => array("UF_ALM2015", "UF_ALM2015COL"), //Алматы, Казахстан. сентябрь 2015
		3522 => array("UF_KIEV2015", "UF_KIEV2015COL"), //Киев, Украина. сентябрь 2015
		3523 => array("UF_MSCAUT2015", "UF_MSCAUT2015COL") //Москва, Россия. октябрь 2015
	);
	static $userFields = array("UF_MSCSPRING2016", "UF_ID2", "UF_ID3", "UF_ID4", "UF_ID5","UF_ID11", "UF_ALM2015", "UF_KIEV2015", "UF_MSCAUT2015");
	static function getPostTemplateByExhibID($exhibId, $name) {
		return isset(self::$arPostTemplateByExhibID[$exhibId][$name]) ? self::$arPostTemplateByExhibID[$exhibId][$name] : false;
	}
	static function getFormQuestionIdByFormIDAndQuestionName($formId, $questionName) {
		return isset(self::$arFormGuestQuestions[$formId][$questionName]) ? self::$arFormGuestQuestions[$formId][$questionName] : false;
	}
	static function getPFormIDByExh($id)
	{
		if(intval($id) == 0)
		{
			return false;
		}
		return self::$arExhForm[$id];
	}
	static function getGResultIDByExh($id)
	{
		if(intval($id) == 0)
		{
			return false;
		}
		return self::$arExhGuestForm[$id];
	}
	static function getPropertyIDByExh($id, $member = 0)
	{
		if(intval($id) == 0)
		{
			return false;
		}
		if(1 == $member)
		{
			return self::$arExhProp[$id][1];
		}
		else
		{
			return self::$arExhProp[$id][0];
		}
	}
	static function getFormIDByQID($id)
	{
		if(intval($id) == 0)
		{
			return false;
		}
		foreach (self::$arFormQuestions as $formID => $questions)
		{
			if(in_array($id, $questions))
			{
				return $formID;
			}
		}
		return false;
	}
	static function getIndexQ($id, $form)
	{
		if(!intval($id))
		{
			return false;
		}
		if(intval($form))
		{
			return array_search($id, self::$arFormQuestions[$form]);
		}
		else
		{
			foreach (self::$arFormQuestions as $formID => $questions)
			{
				$ind = array_search($id, $questions);
				if($ind)
				{
					return $ind;
				}
			}
		}
		return false;
	}
	static function getQByIndex($index, $form)
	{
		if(intval($form))
		{
			return self::$arFormQuestions[$form][$index];
		}
		else
		{
			foreach (self::$arFormQuestions as $formID => $questions)
			{
				$exist = array_key_exists($index, $questions);
				if($exist)
				{
					return $questions[$index];
				}
			}
		}
		return false;
	}
	static function getQIDByBase($id, $form)
	{
		if(!intval($id))
		{
			return false;
		}
		if(intval($form))
		{
			$id = array_search($id, self::$arFormQuestions[4]);
			return self::$arFormQuestions[$form][$id];
		}
		return false;
	}
}