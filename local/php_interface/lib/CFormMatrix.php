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
		30 => array(
			1508,//Mr.
			1509,//Mrs.
			1510,//Ms.
			1511//Dr.
		),
		32 => array(
			1922,//Mr.
			1923,//Mrs.
			1924,//Ms.
			1925//Dr.
		),
	);

	private static $arAnswerRequisiteIDByForm = array(
		4 => array(
			1338,//ИП Поланский Артем Валентинович
			1339,//Трэвэл Медиа
			1487,//ИП Ветрова Елена Васильевна
			1902,//Europae Media
		),
		5 => array(
			1344,//ИП Поланский Артем Валентинович
			1345,//Трэвэл Медиа
			1489,//ИП Ветрова Елена Васильевна
			1905,//Europae Media
		),
		6 => array(
			1348,//ИП Поланский Артем Валентинович
			1349,//Трэвэл Медиа
			1490,//ИП Ветрова Елена Васильевна
			1906,//Europae Media
		),
		7 => array(
			1352,//ИП Поланский Артем Валентинович
			1353,//Трэвэл Медиа
			1491,//ИП Ветрова Елена Васильевна
			1907,//Europae Media
		),
		8 => array(
			1356,//ИП Поланский Артем Валентинович
			1357,//Трэвэл Медиа
			1492,//ИП Ветрова Елена Васильевна
			1908,//Europae Media
		),
		25 => array(
			1360,//ИП Поланский Артем Валентинович
			1361,//Трэвэл Медиа
			1493,//ИП Ветрова Елена Васильевна
			1909,//Europae Media
		),
		26 => array(
			1385,//ИП Поланский Артем Валентинович
			1386,//Трэвэл Медиа
			1494,//ИП Ветрова Елена Васильевна
			1911,//Europae Media
		),
		27 => array(
			1404,//ИП Поланский Артем Валентинович
			1405,//Трэвэл Медиа
			1495,//ИП Ветрова Елена Васильевна
			1910,//Europae Media
		),
		28 => array(
			1423,//ИП Поланский Артем Валентинович
			1424,//Трэвэл Медиа
			1496,//ИП Ветрова Елена Васильевна
			1912,//Europae Media
		),
		29 => array(
			1472,//ИП Поланский Артем Валентинович
			1473,//Трэвэл Медиа
			1488,//ИП Ветрова Елена Васильевна
			1903,//Europae Media
		),
		30 => array(
			1525,//ИП Поланский Артем Валентинович
			1526,//Трэвэл Медиа
			1527,//ИП Ветрова Елена Васильевна
			1904,//Europae Media
		),
		32 => array(
			1939,//ИП Поланский Артем Валентинович
			1940,//Трэвэл Медиа
			1941,//ИП Ветрова Елена Васильевна
			1942,//Europae Media
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
		),
		30 => array(
			597,//Participant first name
			598,//Participant last name
			599,//Job title
			600,//Telephone
			601,//Skype
			602,//E-mail
			603,//Please confirm your e-mail
			604,//Alternative e-mail
			605,//Персональное фото
			606, //Salutation
			607, //Зал
			608,//Стол
			609,//Номер счета
			610, //Сумма счета
			611, //Реквизиты
		),
		32 => array(
			674,//Participant first name
			675,//Participant last name
			676,//Job title
			677,//Telephone
			678,//Skype
			679,//E-mail
			680,//Please confirm your e-mail
			681,//Alternative e-mail
			682,//Персональное фото
			683, //Salutation
			684, //Зал
			685,//Стол
			686,//Номер счета
			687, //Сумма счета
			688, //Реквизиты
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
		30 => array(
			1499,//Participant first name
			1500,//Participant last name
			1501,//Job title
			1502,//Telephone
			1503,//Skype
			1504,//E-mail
			1505,//Please confirm your e-mail
			1506,//Alternative e-mail
			1507,//Персональное фото
			"SIMPLE_QUESTION_889", //Salutation
			"SIMPLE_QUESTION_732",//Зал
			1522, //Стол
			1523,//Номер счета
			1524,//Сумма счета
			"SIMPLE_QUESTION_667",//Реквизиты
		),//Участники Представители Алматы Весна 2017
		32 => array(
			1913,//Participant first name
			1914,//Participant last name
			1915,//Job title
			1916,//Telephone
			1917,//Skype
			1918,//E-mail
			1919,//Please confirm your e-mail
			1920,//Alternative e-mail
			1921,//Персональное фото
			"SIMPLE_QUESTION_889", //Salutation
			"SIMPLE_QUESTION_732",//Зал
			1936, //Стол
			1937,//Номер счета
			1938,//Сумма счета
			"SIMPLE_QUESTION_667",//Реквизиты
		),//Участники Представители Алматы Весна 2018
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
		30 => array(
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
		),//Участники Представители Алматы Весна 2017
		32 => array(
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
		),//Участники Представители Алматы Весна 2018
	);

	static function getAnswerRelBase($baseQ, $needFormID)
	{
		if(empty($baseQ) || !intval($needFormID)){
			return false;
		}
		$index = array_search($baseQ, self::$arAnswerIDByForm[4]);
		return self::$arAnswerIDByForm[$needFormID][$index];
	}

	static function getAnswerRelForm($answerID, $fromFormID, $toFormID)
	{
		if(!$answerID || !$fromFormID || !$toFormID){
			return false;
		}
		$index = array_search($answerID, self::$arAnswerIDByForm[$fromFormID]);
		return self::$arAnswerIDByForm[$toFormID][$index];
	}

	static function getSIDRelBase($baseQ, $needFormID)
	{
		if(empty($baseQ) || !intval($needFormID)){
			return false;
		}
		$index = array_search($baseQ, self::$arSIDByForm[4]);
		return self::$arSIDByForm[$needFormID][$index];
	}

	static function getAnswerSalutationRelBase($baseQ, $needFormID)
	{
		if(empty($baseQ) || !intval($needFormID)){
			return false;
		}
		$index = array_search($baseQ, self::$arAnswerSalutationIDByForm[4]);
		return self::$arAnswerSalutationIDByForm[$needFormID][$index];
	}

	static function getAnswerSalutationRelForm($answerID, $fromFormID, $toFormID)
	{
		if(!$answerID || !$fromFormID || !$toFormID){
			return false;
		}
		$index = array_search($answerID, self::$arAnswerSalutationIDByForm[$fromFormID]);
		return self::$arAnswerSalutationIDByForm[$toFormID][$index];
	}

	static function getAnswerSalutationBase($answID, $needFormID)
	{
		if(empty($answID) || !intval($needFormID)){
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
	static $arCParticipantFieldSort = array(
		"",
		"ID",
		"LOGIN",
		"",
		"COMPANY",
		"BUSINESS",
		"",
		"",
		"REP",
		"PHONE",
		"",
		"EMAIL",
		"",
		"",
		"",
		"",
		""
	);
	static $arCParticipantFieldFilter = array(
		"",
		"ID",
		"LOGIN",
		"",
		"COMPANY",
		"BUSINESS",
		"",
		"",
		"REP",
		"PHONE",
		"",
		"EMAIL",
		"",
		"",
		"",
		"",
		""
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
	static $arUCParticipantFieldSort = array(
		"",
		"COMPANY",
		"BUSINESS",
		"",
		"",
		"",
		"",
		"",
		"REP",
		"",
		"",
		"PHONE",
		"",
		"EMAIL",
		"COMPANY",
		""
	);
	static $arUCParticipantFieldFilter = array(
		"",
		"COMPANY",
		"BUSINESS",
		"",
		"",
		"",
		"",
		"",
		"REP",
		"",
		"",
		"PHONE",
		"",
		"EMAIL",
		"",
		""
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
			"14" => "Hall",
			"15" => "Pay Count",
			"16" => "Requisite"
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
			"14" => "SIMPLE_QUESTION_732",
			"15" => "SIMPLE_QUESTION_680",
			"16" => "SIMPLE_QUESTION_667"
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
			"14" => "ANSWER_TEXT",
			"15" => "USER_TEXT",
			"16" => "ANSWER_TEXT"
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
			"14" => "HALL",
			"15" => "PAY_COUNT",
			"16" => "REQUISITE"
		),
	);
	static $arExelGuestField = array(
		"NAMES" => array(
			"0" => "Компания",
			"1" => "Имя",
			"2" => "Фамилия",
			"3" => "Обращение",
			"4" => "Должность",
			"5" => "E-mail",
			"6" => "Телефон",
			"7" => "Моб. телефон",
			"8" => "Skype",
			"9" => "Имя коллеги (на утро)",
			"10" => "Фамилия коллеги (на утро)",
			"11" => "Обращение коллеги (на утро)",
			"12" => "Должность коллеги (на утро)",
			"13" => "E-mail коллеги (на утро)",
			"14" => "Адрес",
			"15" => "Город",
			"16" => "Страна",
			"17" => "Страна (other)",
			"18" => "Индекс",
			"19" => "Описание компании",
			"20" => "Вид деятельности",
			"21" => "Приоритетные направления",
			"22" => "Приоритетные направления",
			"23" => "Приоритетные направления",
			"24" => "Приоритетные направления",
			"25" => "Приоритетные направления",
			"26" => "Приоритетные направления",
			"27" => "Web-site компании",
			"28" => "Зал",
			"29" => "Стол",
			"30" => "Отель"
		),
		"QUEST_CODE" => array(
			"0" => "SIMPLE_QUESTION_115",
			"1" => "SIMPLE_QUESTION_750",
			"2" => "SIMPLE_QUESTION_823",
			"3" => "SALUTATION",
			"4" => "SIMPLE_QUESTION_391",
			"5" => "SIMPLE_QUESTION_373",
			"6" => "SIMPLE_QUESTION_636",
			"7" => "SIMPLE_QUESTION_844",
			"8" => "SIMPLE_QUESTION_111",
			"9" => "SIMPLE_QUESTION_816",
			"10" => "SIMPLE_QUESTION_596",
			"11" => "SALUTATION_COL",
			"12" => "SIMPLE_QUESTION_304",
			"13" => "SIMPLE_QUESTION_278",
			"14" => "SIMPLE_QUESTION_773",
			"15" => "SIMPLE_QUESTION_672",
			"16" => "SIMPLE_QUESTION_678",
			"17" => "SIMPLE_QUESTION_243",
			"18" => "SIMPLE_QUESTION_756",
			"19" => "SIMPLE_QUESTION_166",
			"20" => "SIMPLE_QUESTION_677",
			"21" => "SIMPLE_QUESTION_383",
			"22" => "SIMPLE_QUESTION_244",
			"23" => "SIMPLE_QUESTION_212",
			"24" => "SIMPLE_QUESTION_497",
			"25" => "SIMPLE_QUESTION_526",
			"26" => "SIMPLE_QUESTION_878",
			"27" => "SIMPLE_QUESTION_552",
			"28" => "SIMPLE_QUESTION_762",
			"29" => "SIMPLE_QUESTION_211",
			"30" => "HOTEL"
		),
		"ANS_TYPE" => array(
			"0" => "USER_TEXT",
			"1" => "USER_TEXT",
			"2" => "USER_TEXT",
			"3" => "ANSWER_TEXT",
			"4" => "USER_TEXT",
			"5" => "USER_TEXT",
			"6" => "USER_TEXT",
			"7" => "USER_TEXT",
			"8" => "USER_TEXT",
			"9" => "USER_TEXT",
			"10" => "USER_TEXT",
			"11" => "ANSWER_TEXT",
			"12" => "USER_TEXT",
			"13" => "USER_TEXT",
			"14" => "USER_TEXT",
			"15" => "USER_TEXT",
			"16" => "ANSWER_TEXT",
			"17" => "USER_TEXT",
			"18" => "USER_TEXT",
			"19" => "USER_TEXT",
			"20" => "ANSWER_TEXT",
			"21" => "ANSWER_TEXT",
			"22" => "ANSWER_TEXT",
			"23" => "ANSWER_TEXT",
			"24" => "ANSWER_TEXT",
			"25" => "ANSWER_TEXT",
			"26" => "ANSWER_TEXT",
			"27" => "USER_TEXT",
			"28" => "ANSWER_TEXT",
			"29" => "USER_TEXT",
			"30" => "ANSWER_TEXT",
		),
		"NAMES_AR" => array(
			"0" => "COMPANY",
			"1" => "F_NAME",
			"2" => "L_NAME",
			"3" => "SALUTATION",
			"4" => "JOB",
			"5" => "EMAIL",
			"6" => "PHONE",
			"7" => "MOB_PHONE",
			"8" => "SKYPE",
			"9" => "F_NAME_COL",
			"10" => "L_NAME_COL",
			"11" => "SALUTATION_COL",
			"12" => "JOB_COL",
			"13" => "EMAIL_COL",
			"14" => "ADRESS",
			"15" => "CITY",
			"16" => "COUNTRY",
			"17" => "COUNTRY",
			"18" => "INDEX",
			"19" => "DESC",
			"20" => "AREA",
			"21" => "DESTINITIONS",
			"22" => "DESTINITIONS",
			"23" => "DESTINITIONS",
			"24" => "DESTINITIONS",
			"25" => "DESTINITIONS",
			"26" => "DESTINITIONS",
			"27" => "SITE",
			"28" => "HALL",
			"29" => "TABLE",
			"30" => "HOTEL"
		),
	);
	static $arExelEvGuestField = array(
		"NAMES" => array(
			"0" => "Компания",
			"1" => "Имя",
			"2" => "Фамилия",
			"3" => "Обращение",
			"4" => "Должность",
			"5" => "E-mail",
			"6" => "Телефон",
			"7" => "Моб. телефон",
			"8" => "Скайп",
			"9" => "Имя коллеги 1",
			"10" => "Фамилия коллеги 1",
			"11" => "Обращение коллеги 1",
			"12" => "Должность коллеги 1",
			"13" => "E-mail коллеги 1",
			"14" => "Имя коллеги 2",
			"15" => "Фамилия коллеги 2",
			"16" => "Обращение коллеги 2",
			"17" => "Должность коллеги 2",
			"18" => "E-mail коллеги 2",
			"19" => "Имя коллеги 3",
			"20" => "Фамилия коллеги 3",
			"21" => "Обращение коллеги 3",
			"22" => "Должность коллеги 3",
			"23" => "E-mail коллеги 3",
			"24" => "Адрес",
			"25" => "Город",
			"26" => "Страна",
			"27" => "Страна (other)",
			"28" => "Индекс",
			"29" => "Вид деятельности",
			"30" => "Web-site компании",
			"31" => "Отель"
		),
		"QUEST_CODE" => array(
			"0" => "SIMPLE_QUESTION_115", //назв комп
			"1" => "SIMPLE_QUESTION_750", //Имя
			"2" => "SIMPLE_QUESTION_823", //Фамилия
			"3" => "SALUTATION", 					//Обращение
			"4" => "SIMPLE_QUESTION_391", //Должность
			"5" => "SIMPLE_QUESTION_373", //E-mail
			"6" => "SIMPLE_QUESTION_636", //Телефон
			"7" => "SIMPLE_QUESTION_844", //Мобильный телефон
			"8" => "SIMPLE_QUESTION_111", //Skype
			"9" => "SIMPLE_QUESTION_367", //Имя коллеги 1
			"10" => "SIMPLE_QUESTION_482", //Фамилия коллеги 1
			"11" => "SALUTATION_1", 			//Обращение коллеги 1
			"12" => "SIMPLE_QUESTION_187", //Должность коллеги 1
			"13" => "SIMPLE_QUESTION_421", //E-mail коллеги 1
			"14" => "SIMPLE_QUESTION_225", //Имя коллеги 2
			"15" => "SIMPLE_QUESTION_770", //Фамилия коллеги 2
			"16" => "SALUTATION_2", 			//Обращение коллеги 2
			"17" => "SIMPLE_QUESTION_280", //Должность коллеги 2
			"18" => "SIMPLE_QUESTION_384", //E-mail коллеги 2
			"19" => "SIMPLE_QUESTION_765", //Имя коллеги 3
			"20" => "SIMPLE_QUESTION_627", //Фамилия коллеги 3
			"21" => "SALUTATION_3", 			//Обращение коллеги 3
			"22" => "SIMPLE_QUESTION_788", //Должность коллеги 3
			"23" => "SIMPLE_QUESTION_230", //E-mail коллеги 3
			"24" => "SIMPLE_QUESTION_773", //факт адр
			"25" => "SIMPLE_QUESTION_672", //город
			"26" => "SIMPLE_QUESTION_678", //страна
			"27" => "SIMPLE_QUESTION_243", //Страна (other)
			"28" => "SIMPLE_QUESTION_756", //индекс
			"29" => "SIMPLE_QUESTION_677", //вид деят
			"30" => "SIMPLE_QUESTION_552", //http://
			"31" => "HOTEL", //Отель
		),
		"ANS_TYPE" => array(
			"0" => "USER_TEXT",
			"1" => "USER_TEXT",
			"2" => "USER_TEXT",
			"3" => "ANSWER_TEXT",
			"4" => "USER_TEXT",
			"5" => "USER_TEXT",
			"6" => "USER_TEXT",
			"7" => "USER_TEXT",
			"8" => "USER_TEXT",
			"9" => "USER_TEXT",
			"10" => "USER_TEXT",
			"11" => "ANSWER_TEXT",
			"12" => "USER_TEXT",
			"13" => "USER_TEXT",
			"14" => "USER_TEXT",
			"15" => "USER_TEXT",
			"16" => "ANSWER_TEXT",
			"17" => "USER_TEXT",
			"18" => "USER_TEXT",
			"19" => "USER_TEXT",
			"20" => "USER_TEXT",
			"21" => "ANSWER_TEXT",
			"22" => "USER_TEXT",
			"23" => "USER_TEXT",
			"24" => "USER_TEXT",
			"25" => "USER_TEXT",
			"26" => "ANSWER_TEXT",
			"27" => "USER_TEXT",
			"28" => "USER_TEXT",
			"29" => "ANSWER_TEXT",
			"30" => "USER_TEXT",
			"31" => "ANSWER_TEXT"
		),
		"NAMES_AR" => array(
			"0" => "COMPANY",
			"1" => "F_NAME",
			"2" => "L_NAME",
			"3" => "SALUTATION",
			"4" => "JOB",
			"5" => "EMAIL",
			"6" => "PHONE",
			"7" => "MOB_PHONE",
			"8" => "SKYPE",
			"9" => "F_NAME_COL1",
			"10" => "L_NAME_COL1",
			"11" => "SALUTATION_COL1",
			"12" => "JOB_COL1",
			"13" => "EMAIL_COL1",
			"14" => "F_NAME_COL2",
			"15" => "L_NAME_COL2",
			"16" => "SALUTATION_COL2",
			"17" => "JOB_COL2",
			"18" => "EMAIL_COL2",
			"19" => "F_NAME_COL3",
			"20" => "L_NAME_COL3",
			"21" => "SALUTATION_COL3",
			"22" => "JOB_COL3",
			"23" => "EMAIL_COL3",
			"24" => "ADRESS",
			"25" => "CITY",
			"26" => "COUNTRY",
			"27" => "COUNTRY",
			"28" => "INDEX",
			"29" => "AREA",
			"30" => "SITE",
			"31" => "HOTEL",
		),
	);
	private static $arFormGuestQuestions = array(
		"NAMES" => array(
			"0" => "Название компании",
			"1" => "Вид деятельности",
			"2" => "Фактический адрес компании",
			"3" => "Индекс",
			"4" => "Город",
			"5" => "Страна",
			"6" => "Страна (other)",
			"7" => "Имя",
			"8" => "Фамилия",
			"9" => "Обращение",
			"10" => "Должность",
			"11" => "Телефон",
			"12" => "Мобильный телефон",
			"13" => "Скайп",
			"14" => "E-mail",
			"15" => "Введите E-mail ещё раз",
			"16" => "http://",
			"17" => "Имя коллеги 1",
			"18" => "Фамилия коллеги 1",
			"19" => "Обращение коллеги 1",
			"20" => "Должность коллеги 1",
			"21" => "E-mail коллеги 1",
			"22" => "Имя коллеги 2",
			"23" => "Фамилия коллеги 2",
			"24" => "Обращение коллеги 2",
			"25" => "E-mail коллеги 2",
			"26" => "Должность коллеги 2",
			"27" => "Имя коллеги 3",
			"28" => "Фамилия коллеги 3",
			"29" => "Обращение коллеги 3",
			"30" => "Должность коллеги 3",
			"31" => "E-mail коллеги 3",
			"LOGIN" => "Введите логин/гостевое имя",
			"33" => "Введите пароль",
			"34" => "Повторите пароль",
			"35" => "Введите краткое описание",
			"36" => "North America",
			"37" => "Europe",
			"38" => "South America",
			"39" => "Africa",
			"40" => "Asia",
			"41" => "Oceania",
			"42" => "Имя коллеги (на утро)",
			"43" => "Фамилия коллеги (на утро)",
			"44" => "Обращение коллеги (на утро)",
			"45" => "Должность коллеги (на утро)",
			"46" => "E-mail коллеги (на утро)",
			"47" => "Утро",
			"48" => "Вечер",
			"49" => "Зал",
			"50" => "Стол",
			"51" => "Отель"
		),
		10 => array(
			"0" => "107",
			"1" => "108",
			"2" => "109",
			"3" => "110",
			"4" => "111",
			"5" => "112",
			"6" => "305",
			"7" => "113",
			"8" => "114",
			"9" => "660",
			"10" => "115",
			"11" => "116",
			"12" => "569", //моб телефон
			"13" => "596", //скайп
			"14" => "117",
			"15" => "118",
			"16" => "119",
			"17" => "120",
			"18" => "121",
			"19" => "661",
			"20" => "122",
			"21" => "123",
			"22" => "124",
			"23" => "125",
			"24" => "662",
			"25" => "126",
			"26" => "127",
			"27" => "128",
			"28" => "129",
			"29" => "663",
			"30" => "130",
			"31" => "131",
			"LOGIN" => "132",
			"33" => "133",
			"34" => "134",
			"35" => "135",
			"36" => "136",
			"37" => "137",
			"38" => "138",
			"39" => "139",
			"40" => "475",
			"41" => "476",
			"42" => "477",
			"43" => "478",
			"44" => "665",
			"45" => "479",
			"46" => "480",
			"47" => "481",
			"48" => "482",
			"49" => "570",
			"50" => "571",
			"51" => "666"
		),
		21 => array(
			"0" => "339",
			"1" => "340",
			"2" => "341",
			"3" => "342",
			"4" => "343",
			"5" => "344",
			"6" => "345",
			"7" => "346",
			"8" => "347",
			"9" => "348",
			"10" => "349",
			"11" => "350",
			"12" => "351",
			"13" => "352",
			"14" => "353",
			"15" => "354",
			"16" => "355",
			"17" => "356",
			"18" => "357",
			"19" => "358",
			"20" => "359",
			"21" => "360",
			"22" => "361",
			"23" => "362",
			"24" => "363",
			"25" => "364",
			"LOGIN" => "365",
			"27" => "366",
			"28" => "367",
			"29" => "368",
			"30" => "369",
			"31" => "370",
			"32" => "",
			"33" => "371",
			"34" => "372",
			"35" => "",
			"36" => "",
			"37" => "",
			"38" => "",
			"39" => "",
			"40" => "",
			"41" => "",
		),
		22 => array(
			"0" => "373",
			"1" => "374",
			"2" => "375",
			"3" => "376",
			"4" => "377",
			"5" => "378",
			"6" => "379",
			"7" => "380",
			"8" => "381",
			"9" => "382",
			"10" => "383",
			"11" => "384",
			"12" => "385",
			"13" => "386",
			"14" => "387",
			"15" => "388",
			"16" => "389",
			"17" => "390",
			"18" => "391",
			"19" => "392",
			"20" => "393",
			"21" => "394",
			"22" => "395",
			"23" => "396",
			"24" => "397",
			"25" => "398",
			"LOGIN" => "399",
			"27" => "400",
			"28" => "401",
			"29" => "402",
			"30" => "403",
			"31" => "404",
			"32" => "",
			"33" => "405",
			"34" => "406",
			"35" => "",
			"36" => "",
			"37" => "",
			"38" => "",
			"39" => "",
			"40" => "",
			"41" => "",
		),
		23 => array(
			"0" => "407",
			"1" => "408",
			"2" => "409",
			"3" => "410",
			"4" => "411",
			"5" => "412",
			"6" => "413",
			"7" => "414",
			"8" => "415",
			"9" => "416",
			"10" => "417",
			"11" => "418",
			"12" => "419",
			"13" => "420",
			"14" => "421",
			"15" => "422",
			"16" => "423",
			"17" => "424",
			"18" => "425",
			"19" => "426",
			"20" => "427",
			"21" => "428",
			"22" => "429",
			"23" => "430",
			"24" => "431",
			"25" => "432",
			"LOGIN" => "433",
			"27" => "434",
			"28" => "435",
			"29" => "436",
			"30" => "437",
			"31" => "438",
			"32" => "",
			"33" => "439",
			"34" => "440",
			"35" => "",
			"36" => "",
			"37" => "",
			"38" => "",
			"39" => "",
			"40" => "",
			"41" => "",
		),
		24 => array(
			"0" => "441",
			"1" => "442",
			"2" => "443",
			"3" => "444",
			"4" => "445",
			"5" => "446",
			"6" => "447",
			"7" => "448",
			"8" => "449",
			"9" => "450",
			"10" => "451",
			"11" => "452",
			"12" => "453",
			"13" => "454",
			"14" => "455",
			"15" => "456",
			"16" => "457",
			"17" => "458",
			"18" => "459",
			"19" => "460",
			"20" => "461",
			"21" => "462",
			"22" => "463",
			"23" => "464",
			"24" => "465",
			"25" => "466",
			"26" => "467",
			"27" => "468",
			"28" => "469",
			"29" => "470",
			"30" => "471",
			"31" => "472",
			"32" => "",
			"33" => "473",
			"34" => "474",
			"35" => "",
			"36" => "",
			"37" => "",
			"38" => "",
			"39" => "",
			"40" => "",
			"41" => "",
		)
	);
	private static $arFormGuestQuestionsSID = array(
		"NAMES" => array(
			"0" => "Название компании",
			"1" => "Вид деятельности",
			"2" => "Фактический адрес компании",
			"3" => "Индекс",
			"4" => "Город",
			"5" => "Страна",
			"6" => "Страна (other)",
			"7" => "Имя",
			"8" => "Фамилия",
			"9" => "Обращение",
			"10" => "Должность",
			"11" => "Телефон",
			"12" => "Мобильный телефон",
			"13" => "Скайп",
			"14" => "E-mail",
			"15" => "Введите E-mail ещё раз",
			"16" => "http://",
			"17" => "Имя коллеги 1",
			"18" => "Фамилия коллеги 1",
			"19" => "Обращение коллеги 1",
			"20" => "Должность коллеги 1",
			"21" => "E-mail коллеги 1",
			"22" => "Имя коллеги 2",
			"23" => "Фамилия коллеги 2",
			"24" => "Обращение коллеги 2",
			"25" => "Должность коллеги 2",
			"26" => "E-mail коллеги 2",
			"27" => "Имя коллеги 3",
			"28" => "Фамилия коллеги 3",
			"29" => "Обращение коллеги 3",
			"30" => "Должность коллеги 3",
			"31" => "E-mail коллеги 3",
			"32" => "Введите логин/гостевое имя",
			"33" => "Введите пароль",
			"34" => "Повторите пароль",
			"35" => "Введите краткое описание",
			"36" => "North America",
			"37" => "Europe",
			"38" => "South America",
			"39" => "Africa",
			"40" => "Asia",
			"41" => "Oceania",
			"42" => "Имя коллеги (на утро)",
			"43" => "Фамилия коллеги (на утро)",
			"44" => "Обращение коллеги (на утро)",
			"45" => "Должность коллеги (на утро)",
			"46" => "E-mail коллеги (на утро)",
			"47" => "Утро",
			"48" => "Вечер",
			"49" => "Зал",
			"50" => "Стол",
			"51" => "Отель"
		),
		"NAMES_AR" => array(
			"0" => "COMPANY",
			"1" => "AREA",
			"2" => "ADRESS",
			"3" => "INDEX",
			"4" => "CITY",
			"5" => "COUNTRY",
			"6" => "COUNTRY_OTHER",
			"7" => "F_NAME",
			"8" => "L_NAME",
			"9" => "SALUTATION",
			"10" => "JOB",
			"11" => "PHONE",
			"12" => "MOBILE_PHONE",
			"13" => "SKYPE",
			"14" => "EMAIL",
			"15" => "EMAIL_REP",
			"16" => "SITE",
			"17" => "F_NAME_COL1",
			"18" => "L_NAME_COL1",
			"19" => "SALUTATION_COL1",
			"20" => "JOB_COL1",
			"21" => "EMAIL_COL1",
			"22" => "F_NAME_COL2",
			"23" => "L_NAME_COL2",
			"24" => "SALUTATION_COL2",
			"25" => "JOB_COL2",
			"26" => "EMAIL_COL2",
			"27" => "F_NAME_COL3",
			"28" => "L_NAME_COL3",
			"29" => "SALUTATION_COL3",
			"30" => "JOB_COL3",
			"34" => "EMAIL_COL3",
			"32" => "LOGIN",
			"33" => "PASSWORD",
			"34" => "PASSWORD_REP",
			"35" => "DESC",
			"36" => "DESTINITIONS",
			"37" => "DESTINITIONS",
			"38" => "DESTINITIONS",
			"39" => "DESTINITIONS",
			"40" => "DESTINITIONS",
			"41" => "DESTINITIONS",
			"42" => "F_NAME_COL",
			"43" => "L_NAME_COL",
			"44" => "SALUTATION_COL",
			"45" => "JOB_COL",
			"46" => "EMAIL_COL",
			"47" => "MORNING",
			"48" => "EVENING",
			"49" => "HALL",
			"50" => "TABLE",
			"51" => "HOTEL"
		),
		"QUEST_CODE" => array(
			"0" => "SIMPLE_QUESTION_115",
			"1" => "SIMPLE_QUESTION_677",
			"2" => "SIMPLE_QUESTION_773",
			"3" => "SIMPLE_QUESTION_756",
			"4" => "SIMPLE_QUESTION_672",
			"5" => "SIMPLE_QUESTION_678",
			"6" => "SIMPLE_QUESTION_243",
			"7" => "SIMPLE_QUESTION_750",
			"8" => "SIMPLE_QUESTION_823",
			"9" => "SALUTATION", 					//Обращение
			"10" => "SIMPLE_QUESTION_391",
			"11" => "SIMPLE_QUESTION_636",
			"12" => "SIMPLE_QUESTION_844",
			"13" => "SIMPLE_QUESTION_111",
			"14" => "SIMPLE_QUESTION_373",
			"15" => "SIMPLE_QUESTION_279",
			"16" => "SIMPLE_QUESTION_552",
			"17" => "SIMPLE_QUESTION_367",
			"18" => "SIMPLE_QUESTION_482",
			"19" => "SALUTATION_1", 					//Обращение
			"20" => "SIMPLE_QUESTION_187",
			"21" => "SIMPLE_QUESTION_421",
			"22" => "SIMPLE_QUESTION_225",
			"23" => "SIMPLE_QUESTION_770",
			"24" => "SALUTATION_2", 					//Обращение
			"25" => "SIMPLE_QUESTION_280",
			"26" => "SIMPLE_QUESTION_384",
			"27" => "SIMPLE_QUESTION_765",
			"28" => "SIMPLE_QUESTION_627",
			"29" => "SALUTATION_3", 					//Обращение
			"30" => "SIMPLE_QUESTION_788",
			"31" => "SIMPLE_QUESTION_230",
			"32" => "SIMPLE_QUESTION_474",
			"33" => "SIMPLE_QUESTION_435",
			"34" => "SIMPLE_QUESTION_300",
			"35" => "SIMPLE_QUESTION_166",
			"36" => "SIMPLE_QUESTION_383",
			"37" => "SIMPLE_QUESTION_244",
			"38" => "SIMPLE_QUESTION_212",
			"39" => "SIMPLE_QUESTION_497",
			"40" => "SIMPLE_QUESTION_526",
			"41" => "SIMPLE_QUESTION_878",
			"42" => "SIMPLE_QUESTION_816",
			"43" => "SIMPLE_QUESTION_596",
			"44" => "SALUTATION_COL", 					//Обращение
			"45" => "SIMPLE_QUESTION_304",
			"46" => "SIMPLE_QUESTION_278",
			"47" => "SIMPLE_QUESTION_836",
			"48" => "SIMPLE_QUESTION_156",
			"49" => "SIMPLE_QUESTION_762",
			"50" => "SIMPLE_QUESTION_211",
			"51" => "HOTEL"
		)
	);
	public static $arExhForm = array(
		358 => 8, //Москва, Россия. 2 октября 2014
		357 => 5, //Баку, Айзербайджан. 10 апреля 2014
		359 => 7, //Алматы, Казахстан. 26 сентября 2014
		360 => 6, //Киев, Украина. 23 сентября 2014
//		361 => 4, //Москва, Россия. 13 марта 2014 ** СТАРОЕ ЗНАЧЕНИЕ, МОЖНО ВЕРНУТЬ ТУТ
		361 => 29, //отдельная форма для москва весна 2016, была 4
		488 => 25, //Москва, Россия. 12 марта 2015
		3521 => 26, //Алматы, Казахстан. сентябрь 2015
		3522 => 27, //Киев, Украина. сентябрь 2015
		3523 => 28, //Москва, Россия. октябрь 2015
		14025 => 30, //Алматы, Казахстан. весна 2017
		19485 => 32, //Алматы, Казахстан. весна 2018
	);
	public static $arExhGuestForm = array(
		358 => 24, //Москва, Россия. 2 октября 2014
		357 => 21, //Баку, Айзербайджан. 10 апреля 2014
		359 => 23, //Алматы, Казахстан. 26 сентября 2014
		360 => 22, //Киев, Украина. 23 сентября 2014
		361 => 10, //Москва, Россия. 13 марта 2014
		488 => 10, //Москва, Россия. 12 марта 2015
		3523 => 24, //Москва, Россия. 2 октября 2014
		3521 => 23, //Алматы, Казахстан. 26 сентября 2014
		3522 => 22, //Киев, Украина. 23 сентября 2014
		14025 => 23, //Алматы, Казахстан. весна 2017
		19485 => 23, //Алматы, Казахстан. весна 2018
	);
	//id почтовых событий для гостей
	private static $arPostTemplateByExhibID = array(
		358 => array("GUEST_EVENING" => 82, "GUEST_MORNING" => 72), //Москва, Россия. 2 октября 2014
		357 => array("GUEST_EVENING" => 79, "GUEST_MORNING" => 75), //Баку, Айзербайджан. 10 апреля 2014
		359 => array("GUEST_EVENING" => 81, "GUEST_MORNING" => 73), //Алматы, Казахстан. 26 сентября 2014
		360 => array("GUEST_EVENING" => 80, "GUEST_MORNING" => 74), //Киев, Украина. 23 сентября 2014
		361 => array("GUEST_EVENING" => 71, "GUEST_MORNING" => 70), //Москва, Россия. 13 марта 2014
		488 => array("GUEST_EVENING" => 78, "GUEST_MORNING" => 76), //Москва, Россия. 12 марта 2015
		3523 => array("GUEST_EVENING" => 82, "GUEST_MORNING" => 72), //Москва, Россия. октябрь 2015
		3521 => array("GUEST_EVENING" => 81, "GUEST_MORNING" => 73), //Алматы, Казахстан. сентябрь 2015
		3522 => array("GUEST_EVENING" => 80, "GUEST_MORNING" => 74), //Киев, Украина. сентябрь 2015
		14025 => array("GUEST_EVENING" => 136, "GUEST_MORNING" => 135), //Алматы, Казахстан. весна 2017
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
		3523 => array("UF_MSCAUT2015", "UF_MSCAUT2015COL"), //Москва, Россия. октябрь 2015
		14025 => array("UF_ALMSP2017", "UF_ALMSP2017COL"), //Алматы, Казахстан. весна 2017
		19485 => array("UF_ALMSP2018", "UF_ALMSP2018COL") //Алматы, Казахстан. весна 2018
	);
	static $userFields = array("UF_MSCSPRING2016", "UF_ID2", "UF_ID3", "UF_ID4", "UF_ID5", "UF_ID11", "UF_ALM2015", "UF_KIEV2015", "UF_MSCAUT2015", "UF_ALMSP2017", "UF_ALMSP2018");

	static function getPostTemplateByExhibID($exhibId, $name)
	{
		return isset(self::$arPostTemplateByExhibID[$exhibId][$name]) ? self::$arPostTemplateByExhibID[$exhibId][$name] : false;
	}

	static function getFormQuestionIdByFormIDAndQuestionName($formId, $questionName)
	{
		return isset(self::$arFormGuestQuestions[$formId][$questionName]) ? self::$arFormGuestQuestions[$formId][$questionName] : false;
	}

	static function getPFormIDByExh($id)
	{
		if(intval($id) == 0){
			return false;
		}
		return self::$arExhForm[$id];
	}

	static function getGResultIDByExh($id)
	{
		if(intval($id) == 0){
			return false;
		}
		return self::$arExhGuestForm[$id];
	}

	static function getPropertyIDByExh($id, $member = 0)
	{
		if(intval($id) == 0){
			return false;
		}
		if(1 == $member){
			return self::$arExhProp[$id][1];
		}else{
			return self::$arExhProp[$id][0];
		}
	}

	static function getFormIDByQID($id)
	{
		if(intval($id) == 0){
			return false;
		}
		foreach(self::$arFormQuestions as $formID => $questions){
			if(in_array($id, $questions)){
				return $formID;
			}
		}
		return false;
	}

	static function getIndexQ($id, $form)
	{
		if(!intval($id)){
			return false;
		}
		if(intval($form)){
			return array_search($id, self::$arFormQuestions[$form]);
		}else{
			foreach(self::$arFormQuestions as $formID => $questions){
				$ind = array_search($id, $questions);
				if($ind){
					return $ind;
				}
			}
		}
		return false;
	}

	static function getQByIndex($index, $form)
	{
		if(intval($form)){
			return self::$arFormQuestions[$form][$index];
		}else{
			foreach(self::$arFormQuestions as $formID => $questions){
				$exist = array_key_exists($index, $questions);
				if($exist){
					return $questions[$index];
				}
			}
		}
		return false;
	}

	static function getQIDByBase($id, $form)
	{
		if(!intval($id)){
			return false;
		}
		if(intval($form)){
			$id = array_search($id, self::$arFormQuestions[4]);
			return self::$arFormQuestions[$form][$id];
		}
		return false;
	}


	#часть связанная с хранилищем гостей
	private static $arGuestFormQuestions = array(
		10 => array(
			107,//Название компании
			108,//Вид деятельности
			109,//Фактический адрес компании
			110,//Индекс
			111,//Город
			112,//Страна
			305,//Страна (other)
			113,//Имя
			114,//Фамилия
			115,//Должность
			116,//Телефон
			569,//Мобильный телефон
			596,//Skype
			117,//E-mail
			118,//Введите E-mail ещё раз
			119,//http://
			120,//Имя коллеги 1
			121,//Фамилия коллеги 1
			122,//Должность коллеги 1
			123,//E-mail коллеги 1
			124,//Имя коллеги 2
			125,//Фамилия коллеги 2
			126,//E-mail коллеги 2
			127,//Должность коллеги 2
			128,//Имя коллеги 3
			129,//Фамилия коллеги 3
			130,//Должность коллеги 3
			131,//E-mail коллеги 3
			132,//Введите логин/гостевое имя
			133,//Введите пароль
			134,//Повторите пароль
			135,//Введите краткое описание
			136,//North America
			137,//Europe
			138,//South America
			139,//Africa
			475,//Asia
			476,//Oceania
			477,//Имя коллеги (на утро)
			478,//Фамилия коллеги (на утро)
			479,//Должность коллеги (на утро)
			480,//E-mail коллеги (на утро)
			481,//Утро
			482,//Вечер
			494,//Фото
			495,//Фото коллеги на утро
			570,//Зал
			571,//Стол
		),        //Гости Москва Весна
		31 => array(
			612,//Название компании
			613,//Вид деятельности
			614,//Фактический адрес компании
			615,//Индекс
			616,//Город
			617,//Страна
			618,//Страна (other)
			619,//Имя
			620,//Фамилия
			621,//Должность
			622,//Телефон
			623,//Мобильный телефон
			624,//Skype
			625,//E-mail
			626,//Введите E-mail ещё раз
			627,//http://
			628,//Имя коллеги 1
			629,//Фамилия коллеги 1
			630,//Должность коллеги 1
			631,//E-mail коллеги 1
			632,//Имя коллеги 2
			633,//Фамилия коллеги 2
			634,//E-mail коллеги 2
			635,//Должность коллеги 2
			636,//Имя коллеги 3
			637,//Фамилия коллеги 3
			638,//Должность коллеги 3
			639,//E-mail коллеги 3
			640,//Введите логин/гостевое имя
			641,//Введите пароль
			642,//Повторите пароль
			643,//Введите краткое описание
			644,//North America
			645,//Europe
			646,//South America
			647,//Africa
			648,//Asia
			649,//Oceania
			650,//Имя коллеги (на утро)
			651,//Фамилия коллеги (на утро)
			652,//Должность коллеги (на утро)
			653,//E-mail коллеги (на утро)
			654,//Утро
			655,//Вечер
			656,//Фото
			657,//Фото коллеги на утро
			658,//Зал
			659,//Стол
		),        //Общая база гостей - результаты
	);

	private static $arAnswerIDGuestForm = array(
		10 => array(
			204,//Название компании
			'SIMPLE_QUESTION_677',//Вид деятельности
			208,//Фактический адрес компании
			209,//Индекс
			210,//Город
			'SIMPLE_QUESTION_678',//Страна
			510,//Страна (other)
			216,//Имя
			217,//Фамилия
			218,//Должность
			219,//Телефон
			1425,//Мобильный телефон
			1484,//Skype
			220,//E-mail
			221,//Введите E-mail ещё раз
			222,//http://
			223,//Имя коллеги 1
			224,//Фамилия коллеги 1
			225,//Должность коллеги 1
			226,//E-mail коллеги 1
			227,//Имя коллеги 2
			228,//Фамилия коллеги 2
			229,//E-mail коллеги 2
			230,//Должность коллеги 2
			231,//Имя коллеги 3
			232,//Фамилия коллеги 3
			233,//Должность коллеги 3
			234,//E-mail коллеги 3
			235,//Введите логин/гостевое имя
			236,//Введите пароль
			237,//Повторите пароль
			238,//Введите краткое описание
			'SIMPLE_QUESTION_383',//North America
			'SIMPLE_QUESTION_244',//Europe
			'SIMPLE_QUESTION_212',//South America
			'SIMPLE_QUESTION_497',//Africa
			'SIMPLE_QUESTION_526',//Asia
			'SIMPLE_QUESTION_878',//Oceania
			839,//Имя коллеги (на утро)
			840,//Фамилия коллеги (на утро)
			841,//Должность коллеги (на утро)
			842,//E-mail коллеги (на утро)
			'SIMPLE_QUESTION_836',//Утро
			'SIMPLE_QUESTION_156',//Вечер
			1312,//Фото
			1313,//Фото коллеги на утро
			'SIMPLE_QUESTION_762',//Зал
			1432,//Стол
		),        //Гости Москва Весна
		31 => array(
			1528,//Название компании
			'SIMPLE_QUESTION_677',//Вид деятельности
			1534,//Фактический адрес компании
			1535,//Индекс
			1536,//Город
			'SIMPLE_QUESTION_678',//Страна
			1553,//Страна (other)
			1554,//Имя
			1555,//Фамилия
			1556,//Должность
			1557,//Телефон
			1558,//Мобильный телефон
			1559,//Skype
			1560,//E-mail
			1561,//Введите E-mail ещё раз
			1562,//http://
			1563,//Имя коллеги 1
			1564,//Фамилия коллеги 1
			1565,//Должность коллеги 1
			1566,//E-mail коллеги 1
			1567,//Имя коллеги 2
			1568,//Фамилия коллеги 2
			1569,//E-mail коллеги 2
			1570,//Должность коллеги 2
			1571,//Имя коллеги 3
			1572,//Фамилия коллеги 3
			1573,//Должность коллеги 3
			1574,//E-mail коллеги 3
			1575,//Введите логин/гостевое имя
			1576,//Введите пароль
			1577,//Повторите пароль
			1578,//Введите краткое описание
			'SIMPLE_QUESTION_383',//North America
			'SIMPLE_QUESTION_244',//Europe
			'SIMPLE_QUESTION_212',//South America
			'SIMPLE_QUESTION_497',//Africa
			'SIMPLE_QUESTION_526',//Asia
			'SIMPLE_QUESTION_878',//Oceania
			1827,//Имя коллеги (на утро)
			1828,//Фамилия коллеги (на утро)
			1829,//Должность коллеги (на утро)
			1830,//E-mail коллеги (на утро)
			'SIMPLE_QUESTION_836',//Утро
			'SIMPLE_QUESTION_156',//Вечер
			1833,//Фото
			1834,//Фото коллеги на утро
			'SIMPLE_QUESTION_762',//Зал
			1844,//Стол
		),        //Общая база гостей - результаты
	);

	private static $arSIDGuestForm = array(
		10 => array(
			'SIMPLE_QUESTION_115', //Название компании
			'SIMPLE_QUESTION_677', //Вид деятельности
			'SIMPLE_QUESTION_773', //Фактический адрес компании
			'SIMPLE_QUESTION_756', //Индекс
			'SIMPLE_QUESTION_672', //Город
			'SIMPLE_QUESTION_678', //Страна
			'SIMPLE_QUESTION_243', //Страна (other)
			'SIMPLE_QUESTION_750', //Имя
			'SIMPLE_QUESTION_823', //Фамилия
			'SIMPLE_QUESTION_391', //Должность
			'SIMPLE_QUESTION_636', //Телефон
			'SIMPLE_QUESTION_844', //Мобильный телефон
			'SIMPLE_QUESTION_111', //Skype
			'SIMPLE_QUESTION_373', //E-mail
			'SIMPLE_QUESTION_279', //Введите E-mail ещё раз
			'SIMPLE_QUESTION_552', //http://
			'SIMPLE_QUESTION_367', //Имя коллеги 1
			'SIMPLE_QUESTION_482', //Фамилия коллеги 1
			'SIMPLE_QUESTION_187', //Должность коллеги 1
			'SIMPLE_QUESTION_421', //	E-mail коллеги 1
			'SIMPLE_QUESTION_225', //Имя коллеги 2
			'SIMPLE_QUESTION_770', //Фамилия коллеги 2
			'SIMPLE_QUESTION_384', //E-mail коллеги 2
			'SIMPLE_QUESTION_280', //Должность коллеги 2
			'SIMPLE_QUESTION_765', //Имя коллеги 3
			'SIMPLE_QUESTION_627', //Фамилия коллеги 3
			'SIMPLE_QUESTION_788', //Должность коллеги 3
			'SIMPLE_QUESTION_230', //E-mail коллеги 3
			'SIMPLE_QUESTION_474', //Введите логин/гостевое имя
			'SIMPLE_QUESTION_435', //Введите пароль
			'SIMPLE_QUESTION_300', //Повторите пароль
			'SIMPLE_QUESTION_166', //Введите краткое описание
			'SIMPLE_QUESTION_383', //North America
			'SIMPLE_QUESTION_244', //Europe
			'SIMPLE_QUESTION_212', //South America
			'SIMPLE_QUESTION_497', //Africa
			'SIMPLE_QUESTION_526', //Asia
			'SIMPLE_QUESTION_878', //Oceania
			'SIMPLE_QUESTION_816', //Имя коллеги (на утро)
			'SIMPLE_QUESTION_596', //Фамилия коллеги (на утро)
			'SIMPLE_QUESTION_304', //Должность коллеги (на утро)
			'SIMPLE_QUESTION_278', //E-mail коллеги (на утро)
			'SIMPLE_QUESTION_836', //Утро
			'SIMPLE_QUESTION_156', //Вечер
			'SIMPLE_QUESTION_269', //Фото
			'SIMPLE_QUESTION_873', //Фото коллеги на утро
			'SIMPLE_QUESTION_762', //	Зал
			'SIMPLE_QUESTION_211', //Стол
		),
		31 => array(
			'SIMPLE_QUESTION_115', //Название компании
			'SIMPLE_QUESTION_677', //Вид деятельности
			'SIMPLE_QUESTION_773', //Фактический адрес компании
			'SIMPLE_QUESTION_756', //Индекс
			'SIMPLE_QUESTION_672', //Город
			'SIMPLE_QUESTION_678', //Страна
			'SIMPLE_QUESTION_243', //Страна (other)
			'SIMPLE_QUESTION_750', //Имя
			'SIMPLE_QUESTION_823', //Фамилия
			'SIMPLE_QUESTION_391', //Должность
			'SIMPLE_QUESTION_636', //Телефон
			'SIMPLE_QUESTION_844', //Мобильный телефон
			'SIMPLE_QUESTION_111', //Skype
			'SIMPLE_QUESTION_373', //E-mail
			'SIMPLE_QUESTION_279', //Введите E-mail ещё раз
			'SIMPLE_QUESTION_552', //http://
			'SIMPLE_QUESTION_367', //Имя коллеги 1
			'SIMPLE_QUESTION_482', //Фамилия коллеги 1
			'SIMPLE_QUESTION_187', //Должность коллеги 1
			'SIMPLE_QUESTION_421', //	E-mail коллеги 1
			'SIMPLE_QUESTION_225', //Имя коллеги 2
			'SIMPLE_QUESTION_770', //Фамилия коллеги 2
			'SIMPLE_QUESTION_384', //E-mail коллеги 2
			'SIMPLE_QUESTION_280', //Должность коллеги 2
			'SIMPLE_QUESTION_765', //Имя коллеги 3
			'SIMPLE_QUESTION_627', //Фамилия коллеги 3
			'SIMPLE_QUESTION_788', //Должность коллеги 3
			'SIMPLE_QUESTION_230', //E-mail коллеги 3
			'SIMPLE_QUESTION_474', //Введите логин/гостевое имя
			'SIMPLE_QUESTION_435', //Введите пароль
			'SIMPLE_QUESTION_300', //Повторите пароль
			'SIMPLE_QUESTION_166', //Введите краткое описание
			'SIMPLE_QUESTION_383', //North America
			'SIMPLE_QUESTION_244', //Europe
			'SIMPLE_QUESTION_212', //South America
			'SIMPLE_QUESTION_497', //Africa
			'SIMPLE_QUESTION_526', //Asia
			'SIMPLE_QUESTION_878', //Oceania
			'SIMPLE_QUESTION_816', //Имя коллеги (на утро)
			'SIMPLE_QUESTION_596', //Фамилия коллеги (на утро)
			'SIMPLE_QUESTION_304', //Должность коллеги (на утро)
			'SIMPLE_QUESTION_278', //E-mail коллеги (на утро)
			'SIMPLE_QUESTION_836', //Утро
			'SIMPLE_QUESTION_156', //Вечер
			'SIMPLE_QUESTION_269', //Фото
			'SIMPLE_QUESTION_873', //Фото коллеги на утро
			'SIMPLE_QUESTION_762', //	Зал
			'SIMPLE_QUESTION_211', //Стол
		),
	);

	private static $arListAnswerGuestForm = array(
		10 => array(
			'SIMPLE_QUESTION_677' => array(        //Вид деятельности
				205,//Tour Operator
				206,//Travel Agency
				207,//Corporate Client
				498,//Press
				1367,//Concierge Services
			),
			'SIMPLE_QUESTION_678' => array(        //Страна
				211,//Armenia
				212,//Azerbaijan
				213,//Belarus
				214,//Estonia
				215,//Georgia
				499,//Kazakhstan
				500,//Kyrgyzstan
				501,//Latvia
				502,//Lithuania
				503,//Moldova
				504,//Russia
				505,//Tajikistan
				506,//Turkmenistan
				507,//Ukraine
				508,//Uzbekistan
				509,//other
			),
			'SIMPLE_QUESTION_383' => array(        //North America
				239,//Anguilla
				240,//Antigua and Barbuda
				241,//Aruba
				806,//Bahamas
				807,//Barbados
				808,//Belize
				809,//Bermuda
				810,//Bonaire
				1225,//British Virgin Islands
				1226,//Canada
				1227,//Cayman Islands
				1228,//Clipperton Island
				1229,//Costa Rica
				1230,//Cuba
				1231,//Curaçao
				1232,//Dominica
				1233,//Dominican Republic
				1234,//El Salvador
				1235,//Greenland
				1236,//Grenada
				1237,//Guadeloupe
				1238,//Guatemala
				1239,//Haiti
				1240,//Honduras
				1241,//Jamaica
				1242,//Martinique
				1243,//Mexico
				1244,//Montserrat
				1245,//Navassa Island
				1246,//Nicaragua
				1247,//Panama
				1248,//Puerto Rico
				1249,//Saba
				1250,//Saint Barthélemy
				1251,//Saint Kitts and Nevis
				1252,//Saint Lucia
				1253,//Saint Martin
				1254,//Saint Pierre and Miquelon
				1255,//Saint Vincent and the Grenadines
				1256,//Sint Eustatius
				1257,//Sint Maarten
				1258,//Trinidad and Tobago
				1259,//Turks and Caicos Islands
				1260,//United States of America
				1261,//United States Virgin Islands
			),
			'SIMPLE_QUESTION_244' => array(        //Europe
				242,//Albania
				243,//Andorra
				811,//Austria
				812,//Azerbaijan
				813,//Belarus
				814,//Belgium
				1170,//Bosnia and Herzegovina
				1171,//Bulgaria
				1172,//Croatia
				1173,//Cyprus
				1174,//Czech Republic
				1175,//Denmark
				1176,//Estonia
				1177,//Faroe Islands
				1178,//Finland
				1179,//France
				1180,//Germany
				1181,//Georgia
				1182,//Greece
				1183,//Hungary
				1184,//Iceland
				1185,//Ireland
				1186,//Italy
				1187,//Latvia
				1188,//Liechtenstein
				1189,//Lithuania
				1190,//Luxembourg
				1191,//Macedonia
				1192,//Malta
				1193,//Moldova
				1194,//Monaco
				1195,//Montenegro
				1196,//Netherlands
				1197,//Norway
				1198,//Poland
				1199,//Portugal
				1200,//Romania
				1201,//Russia
				1202,//San Marino
				1203,//Serbia
				1204,//Slovakia
				1205,//Slovenia
				1206,//Spain
				1207,//Sweden
				1208,//Switzerland
				1209,//Turkey
				1210,//Ukraine
				1211,//United Kingdom
				1212,//Vatican City/Holy See
			),
			'SIMPLE_QUESTION_212' => array(        //South America
				244,//Argentina
				245,//Bolivia
				246,//Brazil
				815,//Chile
				816,//Colombia
				817,//Ecuador
				1262,//Falkland Islands
				1263,//French Guiana
				1264,//Guyana
				1265,//Paraguay
				1266,//Peru
				1267,//Suriname
				1268,//Uruguay
				1269,//Venezuela
			),
			'SIMPLE_QUESTION_497' => array(        //Africa
				247,//Algeria
				248,//Angola
				249,//Benin
				250,//Botswana
				251,//Burkina Faso
				818,//Burundi
				1077,//Cameroon
				1078,//Cape Verde
				1079,//Central African Republic
				1080,//Chad
				1081,//Comoros
				1082,//Congo (Congo-Brazzaville)
				1083,//Côte d'Ivoire (Ivory Coast)
				1084,//Democratic Republic of the Congo (Congo-Kinshasa)
				1085,//Djibouti
				1086,//Egypt
				1087,//Equatorial Guinea
				1088,//Eritrea
				1089,//Ethiopia
				1090,//Gabon
				1091,//Gambia
				1092,//Ghana
				1093,//Guinea
				1094,//Guinea-Bissau
				1095,//Kenya
				1096,//Lesotho
				1097,//Liberia
				1098,//Libya
				1099,//Madagascar
				1100,//Malawi
				1101,//Mali
				1102,//Mauritania
				1103,//Mauritius
				1104,//Mayotte
				1105,//Morocco
				1106,//Mozambique
				1107,//Namibia
				1108,//Niger
				1109,//Nigeria
				1110,//Réunion
				1111,//Rwanda
				1112,//São Tomé and Príncipe
				1113,//Senegal
				1114,//Seychelles
				1115,//Sierra Leone
				1116,//Somalia
				1117,//Somaliland
				1118,//South Africa
				1119,//South Sudan
				1120,//Sudan
				1121,//Swaziland
				1122,//Tanzania
				1123,//Togo
				1124,//Tunisia
				1125,//Uganda
				1126,//Zambia
				1127,//Zimbabwe
			),
			'SIMPLE_QUESTION_526' => array(        //Asia
				819,//Afghanistan
				820,//Armenia
				822,//Bangladesh
				824,//Brunei
				1128,//Cambodia
				1129,//China
				1130,//Christmas Island
				1131,//East Timor (Timor-Leste)
				1132,//Hong Kong
				1133,//India
				1134,//Indonesia
				1135,//Iran
				1136,//Iraq
				1137,//Israel
				1138,//Japan
				1139,//Jordan
				1140,//Kazakhstan
				1141,//Kuwait
				1142,//Kyrgyzstan
				1143,//Laos
				1144,//Lebanon
				1145,//Macau
				1146,//Malaysia
				1147,//Maldives
				1148,//Mongolia
				1149,//Myanmar (Burma)
				1150,//Nepal
				1151,//North Korea
				1152,//Oman
				1153,//Pakistan
				1154,//Palestine
				1155,//Philippines
				1156,//Qatar
				1157,//Saudi Arabia
				1158,//Singapore
				1159,//South Korea
				1160,//Sri Lanka
				1161,//Syria
				1162,//Taiwan
				1163,//Tajikistan
				1164,//Thailand
				1165,//Turkmenistan
				1166,//United Arab Emirates
				1167,//Uzbekistan
				1168,//Vietnam
				1169,//Yemen
			),
			'SIMPLE_QUESTION_878' => array(        //Oceania
				825,//Arctic
				826,//Antarctica
				827,//American Samoa
				828,//Ashmore and Cartier Islands
				829,//Australia
				830,//Baker Island
				831,//Cook Islands
				832,//Coral Sea Islands
				833,//Fiji
				1270,//French Polynesia
				1271,//Guam
				1272,//Howland Island
				1273,//Johnston Atoll
				1274,//Kingman Reef
				1275,//Kiribati
				1276,//Marshall
				1277,//Islands
				1278,//Micronesia
				1279,//Midway Atoll
				1280,//Nauru
				1281,//New Caledonia
				1282,//New Zealand
				1283,//Niue
				1284,//Norfolk Island
				1285,//Northern Mariana Islands
				1286,//Palau
				1287,//Palmyra Atoll
				1288,//Papua New Guinea
				1289,//Pitcairn Islands
				1290,//Samoa
				1291,//Solomon Islands
				1292,//Tokelau
				1293,//Tonga
				1294,//Tuvalu
				1295,//Vanuatu
				1296,//Wake Island
				1297,//Wallis and Futuna
			),
			'SIMPLE_QUESTION_836' => array(        //Утро
				843,//Выбран
			),
			'SIMPLE_QUESTION_156' => array(        //Вечер
				844,//Выбран
			),
			'SALUTATION' => array(        //Обращение
				1845,//Mr.
				1846,//Mrs.
				1847,//Ms.
				1848,//Dr.
			),
			'SALUTATION_1' => array(        //Обращение 1
				1849,//Mr.
				1850,//Mrs.
				1851,//Ms.
				1852,//Dr.
			),
			'SALUTATION_2' => array(        //Обращение 2
				1853,//Mr.
				1854,//Mrs.
				1855,//Ms.
				1856,//Dr.
			),
			'SALUTATION_3' => array(        //Обращение 3
				1857,//Mr.
				1858,//Mrs.
				1859,//Ms.
				1860,//Dr.
			),
			'SALUTATION_COL' => array(        //Обращение утро
				1862,//Mr.
				1863,//Mrs.
				1864,//Ms.
				1865,//Dr.
			),
		),
		31 => array(
			'SIMPLE_QUESTION_677' => array(        //Вид деятельности
				1529,//Tour Operator
				1530,//Travel Agency
				1531,//Corporate Client
				1532,//Press
				1533,//Concierge Services
			),
			'SIMPLE_QUESTION_678' => array(        //Страна
				1537,//Armenia
				1538,//Azerbaijan
				1539,//Belarus
				1540,//Estonia
				1541,//Georgia
				1542,//Kazakhstan
				1543,//Kyrgyzstan
				1544,//Latvia
				1545,//Lithuania
				1546,//Moldova
				1547,//Russia
				1548,//Tajikistan
				1549,//Turkmenistan
				1550,//Ukraine
				1551,//Uzbekistan
				1552,//other
			),
			'SIMPLE_QUESTION_383' => array(        //North America
				1579,//Anguilla
				1580,//Antigua and Barbuda
				1581,//Aruba
				1582,//Bahamas
				1583,//Barbados
				1584,//Belize
				1585,//Bermuda
				1586,//Bonaire
				1587,//British Virgin Islands
				1588,//Canada
				1589,//Cayman Islands
				1590,//Clipperton Island
				1591,//Costa Rica
				1592,//Cuba
				1593,//Curaçao
				1594,//Dominica
				1595,//Dominican Republic
				1596,//El Salvador
				1597,//Greenland
				1598,//Grenada
				1599,//Guadeloupe
				1600,//Guatemala
				1601,//Haiti
				1602,//Honduras
				1603,//Jamaica
				1604,//Martinique
				1605,//Mexico
				1606,//Montserrat
				1607,//Navassa Island
				1608,//Nicaragua
				1609,//Panama
				1610,//Puerto Rico
				1611,//Saba
				1612,//Saint Barthélemy
				1613,//Saint Kitts and Nevis
				1614,//Saint Lucia
				1615,//Saint Martin
				1616,//Saint Pierre and Miquelon
				1617,//Saint Vincent and the Grenadines
				1618,//Sint Eustatius
				1619,//Sint Maarten
				1620,//Trinidad and Tobago
				1621,//Turks and Caicos Islands
				1622,//United States of America
				1623,//United States Virgin Islands
			),
			'SIMPLE_QUESTION_244' => array(        //Europe
				1624,//Albania
				1625,//Andorra
				1626,//Austria
				1627,//Azerbaijan
				1628,//Belarus
				1629,//Belgium
				1630,//Bosnia and Herzegovina
				1631,//Bulgaria
				1632,//Croatia
				1633,//Cyprus
				1634,//Czech Republic
				1635,//Denmark
				1636,//Estonia
				1637,//Faroe Islands
				1638,//Finland
				1639,//France
				1640,//Germany
				1641,//Georgia
				1642,//Greece
				1643,//Hungary
				1644,//Iceland
				1645,//Ireland
				1646,//Italy
				1647,//Latvia
				1648,//Liechtenstein
				1649,//Lithuania
				1650,//Luxembourg
				1651,//Macedonia
				1652,//Malta
				1653,//Moldova
				1654,//Monaco
				1655,//Montenegro
				1656,//Netherlands
				1657,//Norway
				1658,//Poland
				1659,//Portugal
				1660,//Romania
				1661,//Russia
				1662,//San Marino
				1663,//Serbia
				1664,//Slovakia
				1665,//Slovenia
				1666,//Spain
				1667,//Sweden
				1668,//Switzerland
				1669,//Turkey
				1670,//Ukraine
				1671,//United Kingdom
				1672,//Vatican City/Holy See
			),
			'SIMPLE_QUESTION_212' => array(        //South America
				1673,//Argentina
				1674,//Bolivia
				1675,//Brazil
				1676,//Chile
				1677,//Colombia
				1678,//Ecuador
				1679,//Falkland Islands
				1680,//French Guiana
				1681,//Guyana
				1682,//Paraguay
				1683,//Peru
				1684,//Suriname
				1685,//Uruguay
				1686,//Venezuela
			),
			'SIMPLE_QUESTION_497' => array(        //Africa
				1687,//Algeria
				1688,//Angola
				1689,//Benin
				1690,//Botswana
				1691,//Burkina Faso
				1692,//Burundi
				1693,//Cameroon
				1694,//Cape Verde
				1695,//Central African Republic
				1696,//Chad
				1697,//Comoros
				1698,//Congo (Congo-Brazzaville)
				1699,//Côte d'Ivoire (Ivory Coast)
				1700,//Democratic Republic of the Congo (Congo-Kinshasa)
				1701,//Djibouti
				1702,//Egypt
				1703,//Equatorial Guinea
				1704,//Eritrea
				1705,//Ethiopia
				1706,//Gabon
				1707,//Gambia
				1708,//Ghana
				1709,//Guinea
				1710,//Guinea-Bissau
				1711,//Kenya
				1712,//Lesotho
				1713,//Liberia
				1714,//Libya
				1715,//Madagascar
				1716,//Malawi
				1717,//Mali
				1718,//Mauritania
				1719,//Mauritius
				1720,//Mayotte
				1721,//Morocco
				1722,//Mozambique
				1723,//Namibia
				1724,//Niger
				1725,//Nigeria
				1726,//Réunion
				1727,//Rwanda
				1728,//São Tomé and Príncipe
				1729,//Senegal
				1730,//Seychelles
				1731,//Sierra Leone
				1732,//Somalia
				1733,//Somaliland
				1734,//South Africa
				1735,//South Sudan
				1736,//Sudan
				1737,//Swaziland
				1738,//Tanzania
				1739,//Togo
				1740,//Tunisia
				1741,//Uganda
				1742,//Zambia
				1743,//Zimbabwe
			),
			'SIMPLE_QUESTION_526' => array(        //Asia
				1744,//Afghanistan
				1745,//Armenia
				1746,//Bangladesh
				1747,//Brunei
				1748,//Cambodia
				1749,//China
				1750,//Christmas Island
				1751,//East Timor (Timor-Leste)
				1752,//Hong Kong
				1753,//India
				1754,//Indonesia
				1755,//Iran
				1756,//Iraq
				1757,//Israel
				1758,//Japan
				1759,//Jordan
				1760,//Kazakhstan
				1761,//Kuwait
				1762,//Kyrgyzstan
				1763,//Laos
				1764,//Lebanon
				1765,//Macau
				1766,//Malaysia
				1767,//Maldives
				1768,//Mongolia
				1769,//Myanmar (Burma)
				1770,//Nepal
				1771,//North Korea
				1772,//Oman
				1773,//Pakistan
				1774,//Palestine
				1775,//Philippines
				1776,//Qatar
				1777,//Saudi Arabia
				1778,//Singapore
				1779,//South Korea
				1780,//Sri Lanka
				1781,//Syria
				1782,//Taiwan
				1783,//Tajikistan
				1784,//Thailand
				1785,//Turkmenistan
				1786,//United Arab Emirates
				1787,//Uzbekistan
				1788,//Vietnam
				1789,//Yemen
			),
			'SIMPLE_QUESTION_878' => array(        //Oceania
				1790,//Arctic
				1791,//Antarctica
				1792,//American Samoa
				1793,//Ashmore and Cartier Islands
				1794,//Australia
				1795,//Baker Island
				1796,//Cook Islands
				1797,//Coral Sea Islands
				1798,//Fiji
				1799,//French Polynesia
				1800,//Guam
				1801,//Howland Island
				1802,//Johnston Atoll
				1803,//Kingman Reef
				1804,//Kiribati
				1805,//Marshall
				1806,//Islands
				1807,//Micronesia
				1808,//Midway Atoll
				1809,//Nauru
				1810,//New Caledonia
				1811,//New Zealand
				1812,//Niue
				1813,//Norfolk Island
				1814,//Northern Mariana Islands
				1815,//Palau
				1816,//Palmyra Atoll
				1817,//Papua New Guinea
				1818,//Pitcairn Islands
				1819,//Samoa
				1820,//Solomon Islands
				1821,//Tokelau
				1822,//Tonga
				1823,//Tuvalu
				1824,//Vanuatu
				1825,//Wake Island
				1826,//Wallis and Futuna
			),
			'SIMPLE_QUESTION_836' => array(        //Утро
				1831,//Выбран
			),
			'SIMPLE_QUESTION_156' => array(        //Вечер
				1832,//Выбран
			),
			'SALUTATION' => array(        //Обращение
				1868,//Mr.
				1869,//Mrs.
				1870,//Ms.
				1871,//Dr.
			),
			'SALUTATION_1' => array(        //Обращение 1
				1872,//Mr.
				1873,//Mrs.
				1874,//Ms.
				1875,//Dr.
			),
			'SALUTATION_2' => array(        //Обращение 2
				1876,//Mr.
				1877,//Mrs.
				1878,//Ms.
				1879,//Dr.
			),
			'SALUTATION_3' => array(        //Обращение 3
				1880,//Mr.
				1881,//Mrs.
				1882,//Ms.
				1883,//Dr.
			),
			'SALUTATION_COL' => array(        //Обращение утро
				1885,//Mr.
				1886,//Mrs.
				1887,//Ms.
				1888,//Dr.
			),
		),
	);


	#функции работы с вопросами формы гостей

	/** Возвращает ид вопроса для формы гостя
	 * @param $baseQ - SID вопроса
	 * @param $fromFormID - исходная форма
	 * @param $needFormID - целевая форма
	 * @return bool|string
	 */
	static function getAnswerGuestForm($baseQ, $fromFormID, $needFormID)
	{
		if(empty($baseQ) || !intval($fromFormID) || !intval($needFormID)){
			return false;
		}
		$index = array_search($baseQ, self::$arAnswerIDGuestForm[$fromFormID]);
		return self::$arAnswerIDGuestForm[$needFormID][$index];
	}

	static function getSIDGuestFormBase($baseQ, $needFormID)
	{
		if(empty($baseQ) || !intval($needFormID)){
			return false;
		}
		$index = array_search($baseQ, self::$arSIDGuestForm[10]);
		return self::$arSIDGuestForm[$needFormID][$index];
	}

	static function getSIDListGuestForm($needFormID)
	{
		if(!intval($needFormID)){
			return false;
		}
		return self::$arSIDGuestForm[$needFormID];
	}

	static function getQIDGuestFormBase($id, $form)
	{
		if(!intval($id)){
			return false;
		}
		if(intval($form)){
			$id = array_search($id, self::$arGuestFormQuestions[10]);
			return self::$arGuestFormQuestions[$form][$id];
		}
		return false;
	}

	/**
	 * Возвращает ид ответа для списковых вопросов
	 * @param $fromFormID - ИД исходной формы гостей
	 * @param $needFormID - ИД целевой формы гостей
	 * @param $SID - идентификатр вопроса
	 * @param $id - ИД ответа
	 * @return bool|string
	 */
	static  function getListAnswersIDGuestForm($fromFormID, $needFormID, $SID, $id)
	{
		if(!intval($fromFormID) || !intval($needFormID) || !$SID || !isset(self::$arListAnswerGuestForm[$fromFormID][$SID]) || !isset(self::$arListAnswerGuestForm[$needFormID][$SID])){
			return false;
		}

		$index = array_search($id, self::$arListAnswerGuestForm[$fromFormID][$SID]);
		return self::$arListAnswerGuestForm[$needFormID][$SID][$index];
	}

	static function getExhProp()
	{
		return self::$arExhProp;
	}
}