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
	);

	private static $arAnswerRequisiteIDByForm = array(
	    4 => array(
	        1338,//�� ��������� ����� ������������
	        1339,//������ �����
	    ),
	    5 => array(
	        1344,//�� ��������� ����� ������������
	        1345,//������ �����
	    ),
	    6 => array(
	        1348,//�� ��������� ����� ������������
	        1349,//������ �����
	    ),
	    7 => array(
	        1352,//�� ��������� ����� ������������
	        1353,//������ �����
	    ),
	    8 => array(
	        1356,//�� ��������� ����� ������������
	        1357,//������ �����
	    ),
	    25 => array(
	        1360,//�� ��������� ����� ������������
	        1361,//������ �����
	    ),
	    26 => array(
	        1385,//�� ��������� ����� ������������
	        1386,//������ �����
	    ),
	    27 => array(
	        1404,//�� ��������� ����� ������������
	        1405,//������ �����
	    ),
	    28 => array(
	        1423,//�� ��������� ����� ������������
	        1424,//������ �����
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
			    37,//E-mail
			    38,//Please confirm your e-mail
			    39,//Alternative e-mail
			    101,//������������ ����
			    106,//Salutation
			    496,//���
			    497,//����
			    508,//����� �����
			    509,//����� �����
			    510,//���������
			),
			5 => array(
			    40,//Participant first name
			    41,//Participant last name
			    43,//Job title
			    44,//Telephone
			    45,//E-mail
			    46,//Please confirm your e-mail
			    47,//Alternative e-mail
			    102,//������������ ����
			    42,//Salutation
			    498,//���
			    499,//����
			    512,//����� �����
			    513,//����� �����
			    514,//���������
			),
			6 => array(
			    48,//Participant first name
			    49,//Participant last name
			    51,//Job title
			    52,//Telephone
			    53,//E-mail
			    54,//Please confirm your e-mail
			    55,//Alternative e-mail
			    103,//������������ ����
			    50,//Salutation
			    500, //���
			    501,//����
			    515,//����� �����
			    516,//����� �����
			    517,//���������
			),
			7 => array(
			    56,//Participant first name
			    57,//Participant last name
			    59,//Job title
			    60,//Telephone
			    61,//E-mail
			    62,//Please confirm your e-mail
			    63,//Alternative e-mail
			    104,//������������ ����
			    58,//Salutation
			    502, //���
			    503, //����
			    518,//����� �����
			    519,//����� �����
			    520,//���������
			),
			8 => array(
			    64,//Participant first name
			    65,//Participant last name
			    67,//Job title
			    68,//Telephone
			    69,//E-mail
			    70,//Please confirm your e-mail
			    71,//Alternative e-mail
			    105,//������������ ����
			    66,//Salutation
			    504,//���
			    505,//����
			    521,//����� �����
			    522,//����� �����
			    523,//���������
			),
			25 => array(
			    483,//Participant first name
			    484,//Participant last name
			    485,//Job title
			    486,//Telephone
			    487,//E-mail
			    488,//Please confirm your e-mail
			    489,//Alternative e-mail
			    490,//������������ ����
			    491, //Salutation
			    506, //���
			    507,//����
			    524,//����� �����
			    525, //����� �����
			    526, //���������
			),
			26 => array(
			    527,//Participant first name
			    528,//Participant last name
			    530,//Job title
			    531,//Telephone
			    532,//E-mail
			    533,//Please confirm your e-mail
			    534,//Alternative e-mail
			    535,//������������ ����
			    529, //Salutation
			    536, //���
			    537,//����
			    538,//����� �����
			    539, //����� �����
			    540, //���������
			),
			27 => array(
			    541,//Participant first name
			    542,//Participant last name
			    544,//Job title
			    545,//Telephone
			    546,//E-mail
			    547,//Please confirm your e-mail
			    548,//Alternative e-mail
			    549,//������������ ����
			    543, //Salutation
			    550, //���
			    551,//����
			    552,//����� �����
			    553, //����� �����
			    554, //���������
			),
			28 => array(
			    555,//Participant first name
			    556,//Participant last name
			    558,//Job title
			    559,//Telephone
			    560,//E-mail
			    561,//Please confirm your e-mail
			    562,//Alternative e-mail
			    563,//������������ ����
			    557, //Salutation
			    564, //���
			    565,//����
			    566,//����� �����
			    567, //����� �����
			    568, //���������
			)

	);

	private static $arAnswerIDByForm = array(
		4 => array(
			84,//Participant first name
			85,//Participant last name
			87,//Job title
			88,//Telephone
			89,//E-mail
			90,//Please confirm your e-mail
			91,//Alternative e-mail
			195,//������������ ����
			"SIMPLE_QUESTION_889",//Salutation
			"SIMPLE_QUESTION_732", //���
		    1319, //����
		    1336, //����� �����
		    1337,//����� �����
		    "SIMPLE_QUESTION_667", //���������
		),//��������� ������������� ������ �����
		5 => array(
			92,//Participant first name
			93,//Participant last name
			95,//Job title
			96,//Telephone
			97,//E-mail
			98,//Please confirm your e-mail
			99,//Alternative e-mail
			196,//������������ ����
			"SIMPLE_QUESTION_189", //Salutation
			"SIMPLE_QUESTION_386", //���
		    1325, //����
		    1342,//����� �����
		    1343,//����� �����
		    "SIMPLE_QUESTION_758",//���������
		),//��������� ������������� ����
		6 => array(
			100,//Participant first name
			101,//Participant last name
			103,//Job title
			104,//Telephone
			105,//E-mail
			106,//Please confirm your e-mail
			107,//Alternative e-mail
			197,//������������ ����
			"SIMPLE_QUESTION_120",//Salutation
			"SIMPLE_QUESTION_286",//���
		    1327, //����
		    1346,//����� �����
		    1347,//����� �����
		    "SIMPLE_QUESTION_254",//���������
		),//��������� ������������� ����
		7 => array(
			108,//Participant first name
			109,//Participant last name
			111,//Job title
			112,//Telephone
			113,//E-mail
			114,//Please confirm your e-mail
			115,//Alternative e-mail
			198,//������������ ����
			"SIMPLE_QUESTION_270", //Salutation
			"SIMPLE_QUESTION_428", //���
		    1329, //����
		    1350,//����� �����
		    1351,//����� �����
		    "SIMPLE_QUESTION_330"//���������
		),//��������� ������������� ������
		8 => array(
			116,//Participant first name
			117,//Participant last name
			119,//Job title
			120,//Telephone
			121,//E-mail
			122,//Please confirm your e-mail
			123,//Alternative e-mail
			199,//������������ ����
			"SIMPLE_QUESTION_888", //Salutation
			"SIMPLE_QUESTION_824", //���
		    1331, //����
		    1354,//����� �����
		    1355,//����� �����
		    "SIMPLE_QUESTION_183",//���������
		),//��������� ������������� ������ �����
		25 => array(
			1213,//Participant first name
			1214,//Participant last name
			1215,//Job title
			1216,//Telephone
			1217,//E-mail
			1218,//Please confirm your e-mail
			1219,//Alternative e-mail
			1220,//������������ ����
			"SIMPLE_QUESTION_889", //Salutation
			"SIMPLE_QUESTION_713",//���
		    1333, //����
		    1358,//����� �����
		    1359,//����� �����
		    "SIMPLE_QUESTION_391",//���������
		),//��������� ������������� ������ ����� - 2015
		26 => array(
			1368,//Participant first name
			1369,//Participant last name
			1374,//Job title
			1375,//Telephone
			1376,//E-mail
			1377,//Please confirm your e-mail
			1378,//Alternative e-mail
			1379,//������������ ����
			"SIMPLE_QUESTION_270", //Salutation
			"SIMPLE_QUESTION_428",//���
		    1382, //����
		    1383,//����� �����
		    1384,//����� �����
		    "SIMPLE_QUESTION_330",//���������
		),//��������� ������������� ������ 2015
		27 => array(
			1387,//Participant first name
			1388,//Participant last name
			1393,//Job title
			1394,//Telephone
			1395,//E-mail
			1396,//Please confirm your e-mail
			1397,//Alternative e-mail
			1398,//������������ ����
			"SIMPLE_QUESTION_120", //Salutation
			"SIMPLE_QUESTION_286",//���
		    1401, //����
		    1402,//����� �����
		    1403,//����� �����
		    "SIMPLE_QUESTION_254",//���������
		),//��������� ������������� ���� 2015
		28 => array(
			1406,//Participant first name
			1407,//Participant last name
			1412,//Job title
			1413,//Telephone
			1414,//E-mail
			1415,//Please confirm your e-mail
			1416,//Alternative e-mail
			1417,//������������ ����
			"SIMPLE_QUESTION_888", //Salutation
			"SIMPLE_QUESTION_824",//���
		    1420, //����
		    1421,//����� �����
		    1422,//����� �����
		    "SIMPLE_QUESTION_183",//���������
		),//��������� ������������� ������ ����� 2015
	);

	private static $arSIDByForm = array(
		4 => array(
			"SIMPLE_QUESTION_446",//Participant first name
			"SIMPLE_QUESTION_551",//Participant last name
			"SIMPLE_QUESTION_729",//Job title
			"SIMPLE_QUESTION_394",//Telephone
			"SIMPLE_QUESTION_859",//E-mail
			"SIMPLE_QUESTION_585",//Please confirm your e-mail
			"SIMPLE_QUESTION_749",//Alternative e-mail
			"SIMPLE_QUESTION_575",//������������ ����
			"SIMPLE_QUESTION_889",//Salutation
			"SIMPLE_QUESTION_539",//����� �����
		    "SIMPLE_QUESTION_680",//����� �����
		    "SIMPLE_QUESTION_667",//���������
		    "SIMPLE_QUESTION_148",//����
		    "SIMPLE_QUESTION_732",//���
		),//��������� ������������� ������ �����
		5 => array(
			"SIMPLE_QUESTION_708",//Participant first name
			"SIMPLE_QUESTION_599",//Participant last name
			"SIMPLE_QUESTION_895",//Job title
			"SIMPLE_QUESTION_622",//Telephone
			"SIMPLE_QUESTION_650",//E-mail
			"SIMPLE_QUESTION_294",//Please confirm your e-mail
			"SIMPLE_QUESTION_359",//Alternative e-mail
			"SIMPLE_QUESTION_503",//������������ ����
			"SIMPLE_QUESTION_189",//Salutation
			"SIMPLE_QUESTION_820",//����� �����
		    "SIMPLE_QUESTION_527",//����� �����
		    "SIMPLE_QUESTION_758",//���������
		    "SIMPLE_QUESTION_994",//����
		    "SIMPLE_QUESTION_386",//���
		),//��������� ������������� ����
		6 => array(
			"SIMPLE_QUESTION_896",//Participant first name
			"SIMPLE_QUESTION_409",//Participant last name
			"SIMPLE_QUESTION_468",//Job title
			"SIMPLE_QUESTION_992",//Telephone
			"SIMPLE_QUESTION_279",//E-mail
			"SIMPLE_QUESTION_857",//Please confirm your e-mail
			"SIMPLE_QUESTION_527",//Alternative e-mail
			"SIMPLE_QUESTION_975",//������������ ����
			"SIMPLE_QUESTION_120",//Salutation
			"SIMPLE_QUESTION_868",//����� �����
		    "SIMPLE_QUESTION_851",//����� �����
		    "SIMPLE_QUESTION_254",//���������
		    "SIMPLE_QUESTION_471",//����
		    "SIMPLE_QUESTION_286",//���
		),//��������� ������������� ����
		7 => array(
			"SIMPLE_QUESTION_948",//Participant first name
			"SIMPLE_QUESTION_159",//Participant last name
			"SIMPLE_QUESTION_993",//Job title
			"SIMPLE_QUESTION_434",//Telephone
			"SIMPLE_QUESTION_742",//E-mail
			"SIMPLE_QUESTION_111",//Please confirm your e-mail
			"SIMPLE_QUESTION_528",//Alternative e-mail
			"SIMPLE_QUESTION_800",//������������ ����
			"SIMPLE_QUESTION_270",//Salutation
			"SIMPLE_QUESTION_997",//����� �����
		    "SIMPLE_QUESTION_833",//����� �����
		    "SIMPLE_QUESTION_330",//���������
		    "SIMPLE_QUESTION_778",//����
		    "SIMPLE_QUESTION_428",//���
		),//��������� ������������� ������
		8 => array(
			"SIMPLE_QUESTION_119",//Participant first name
			"SIMPLE_QUESTION_869",//Participant last name
			"SIMPLE_QUESTION_652",//Job title
			"SIMPLE_QUESTION_227",//Telephone
			"SIMPLE_QUESTION_786",//E-mail
			"SIMPLE_QUESTION_321",//Please confirm your e-mail
			"SIMPLE_QUESTION_294",//Alternative e-mail
			"SIMPLE_QUESTION_772",//������������ ����
			"SIMPLE_QUESTION_888",//Salutation
			"SIMPLE_QUESTION_638",//����� �����
		    "SIMPLE_QUESTION_168",//����� �����
		    "SIMPLE_QUESTION_183",//���������
		    "SIMPLE_QUESTION_214",//����
		    "SIMPLE_QUESTION_824",//���
		),//��������� ������������� ������ �����
		25 => array(
			"SIMPLE_QUESTION_446",//Participant first name
			"SIMPLE_QUESTION_551",//Participant last name
			"SIMPLE_QUESTION_729",//Job title
			"SIMPLE_QUESTION_394",//Telephone
			"SIMPLE_QUESTION_859",//E-mail
			"SIMPLE_QUESTION_585",//Please confirm your e-mail
			"SIMPLE_QUESTION_749",//Alternative e-mail
			"SIMPLE_QUESTION_575",//������������ ����
			"SIMPLE_QUESTION_889",//Salutation
			"SIMPLE_QUESTION_275",//����� �����
		    "SIMPLE_QUESTION_542",//����� �����
		    "SIMPLE_QUESTION_391",//���������
		    "SIMPLE_QUESTION_418",//����
		    "SIMPLE_QUESTION_713",//���
		),//��������� ������������� ������ ����� - 2015
		26 => array(
			"SIMPLE_QUESTION_948",//Participant first name
			"SIMPLE_QUESTION_159",//Participant last name
			"SIMPLE_QUESTION_993",//Job title
			"SIMPLE_QUESTION_434",//Telephone
			"SIMPLE_QUESTION_742",//E-mail
			"SIMPLE_QUESTION_111",//Please confirm your e-mail
			"SIMPLE_QUESTION_528",//Alternative e-mail
			"SIMPLE_QUESTION_800",//������������ ����
			"SIMPLE_QUESTION_270",//Salutation
			"SIMPLE_QUESTION_997",//����� �����
		    "SIMPLE_QUESTION_833",//����� �����
		    "SIMPLE_QUESTION_330",//���������
		    "SIMPLE_QUESTION_778",//����
		    "SIMPLE_QUESTION_428",//���
		),//��������� ������������� ������ 2015
		27 => array(
			"SIMPLE_QUESTION_896",//Participant first name
			"SIMPLE_QUESTION_409",//Participant last name
			"SIMPLE_QUESTION_468",//Job title
			"SIMPLE_QUESTION_992",//Telephone
			"SIMPLE_QUESTION_279",//E-mail
			"SIMPLE_QUESTION_857",//Please confirm your e-mail
			"SIMPLE_QUESTION_527",//Alternative e-mail
			"SIMPLE_QUESTION_975",//������������ ����
			"SIMPLE_QUESTION_120",//Salutation
			"SIMPLE_QUESTION_868",//����� �����
		    "SIMPLE_QUESTION_851",//����� �����
		    "SIMPLE_QUESTION_254",//���������
		    "SIMPLE_QUESTION_471",//����
		    "SIMPLE_QUESTION_286",//���
		),//��������� ������������� ���� 2015
		28 => array(
			"SIMPLE_QUESTION_119",//Participant first name
			"SIMPLE_QUESTION_869",//Participant last name
			"SIMPLE_QUESTION_652",//Job title
			"SIMPLE_QUESTION_227",//Telephone
			"SIMPLE_QUESTION_786",//E-mail
			"SIMPLE_QUESTION_321",//Please confirm your e-mail
			"SIMPLE_QUESTION_294",//Alternative e-mail
			"SIMPLE_QUESTION_772",//������������ ����
			"SIMPLE_QUESTION_888",//Salutation
			"SIMPLE_QUESTION_638",//����� �����
		    "SIMPLE_QUESTION_168",//����� �����
		    "SIMPLE_QUESTION_183",//���������
		    "SIMPLE_QUESTION_214",//����
		    "SIMPLE_QUESTION_824",//���
		),//��������� ������������� ������ ����� 2015
	);


	static function getAnswerRelBase($baseQ, $needFormID)
	{
		if(empty($baseQ) || !intval($needFormID))
		{
			return;
		}
		$index = array_search($baseQ, self::$arAnswerIDByForm[4]);

		return self::$arAnswerIDByForm[$needFormID][$index];
	}

	static function getSIDRelBase($baseQ, $needFormID)
	{
	    if(empty($baseQ) || !intval($needFormID))
	    {
	        return;
	    }
	    $index = array_search($baseQ, self::$arSIDByForm[4]);

	    return self::$arSIDByForm[$needFormID][$index];
	}

	static function getAnswerSalutationRelBase($baseQ, $needFormID)
	{
		if(empty($baseQ) || !intval($needFormID))
		{
			return;
		}
		$index = array_search($baseQ, self::$arAnswerSalutationIDByForm[4]);

		return self::$arAnswerSalutationIDByForm[$needFormID][$index];
	}
	
	static function getAnswerSalutationBase($answID, $needFormID)
	{
		if(empty($answID) || !intval($needFormID))
		{
			return;
		}
		$index = array_search($answID, self::$arAnswerSalutationIDByForm[$needFormID]);
	
		return self::$arAnswerSalutationIDByForm[4][$index];
	}
	
	static $arCParticipantField = array(
		"��������� ��������",
		"ID ������������",
		"�����",
		"������",
		"Company or hotel name",
		"Area of the business",
		"City",
		"Country",
		"Representative",
		"Telephone number",
		"Email",
		"Table",
		"Hall",
		"����� �����",
		"����� �����",
		"��������"
	);

	static $arUCParticipantField = array(
		"��������� ��������",
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
		"Email",
		"Company or hotel name",
		"��������"
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
				"5" => "Email",
				"6" => "Alternative e-mail",
				"7" => "Title College (Salutation)",
				"8" => "First Name College",
				"9" => "Last Name College",
				"10" => "Job Title College",
				"11" => "Email College",
				"12" => "Table",
				"13" => "Hall"

		),
		"QUEST_CODE" => array(
				"0" => "SIMPLE_QUESTION_889",
				"1" => "SIMPLE_QUESTION_446",
				"2" => "SIMPLE_QUESTION_551",
				"3" => "SIMPLE_QUESTION_729",
				"4" => "SIMPLE_QUESTION_394",
				"5" => "SIMPLE_QUESTION_859",
				"6" => "SIMPLE_QUESTION_749",
				"7" => "SIMPLE_QUESTION_889",
				"8" => "SIMPLE_QUESTION_446",
				"9" => "SIMPLE_QUESTION_551",
				"10" => "SIMPLE_QUESTION_729",
				"11" => "SIMPLE_QUESTION_859",
				"12" => "SIMPLE_QUESTION_148",
				"13" => "SIMPLE_QUESTION_732"
		),//��������� ������������� ������ �����

		"ANS_TYPE" => array(
				"0" => "ANSWER_TEXT",
				"1" => "USER_TEXT",
				"2" => "USER_TEXT",
				"3" => "USER_TEXT",
				"4" => "USER_TEXT",
				"5" => "USER_TEXT",
				"6" => "USER_TEXT",
				"7" => "ANSWER_TEXT",
				"8" => "USER_TEXT",
				"9" => "USER_TEXT",
				"10" => "USER_TEXT",
				"11" => "USER_TEXT",
				"12" => "USER_TEXT",
				"13" => "ANSWER_TEXT"
		),
		"NAMES_AR" => array(
				"0" => "TITLE",
				"1" => "F_NAME",
				"2" => "L_NAME",
				"3" => "JOB",
				"4" => "PHONE",
				"5" => "EMAIL",
				"6" => "EMAIL_ALT",
				"7" => "TITLE_COL",
				"8" => "F_NAME_COL",
				"9" => "L_NAME_COL",
				"10" => "JOB_COL",
				"11" => "EMAIL_COL",
				"12" => "TABLE",
				"13" => "HALL"
		),
	);

	static $arExelGuestField = array(
		"NAMES" => array(
			"0" => "��������",
			"1" => "��� ������������",
			"2" => "���",
			"3" => "�������",
			"4" => "���������",
			"5" => "��� ������� (�� ����)",
			"6" => "������� ������� (�� ����)",
			"7" => "��������� ������� (�� ����)",
			"8" => "E-mail ������� (�� ����)",
			"9" => "���. �������",
			"10" => "�����",			
			"11" => "�����",
			"12" => "������",
			"13" => "������ (other)",
			"14" => "������",
			"15" => "�������",			
			"16" => "E-mail",
			"17" => "Web-site ��������",
			"18" => "������������ �����������",
			"19" => "������������ �����������",
			"20" => "������������ �����������",
			"21" => "������������ �����������",
			"22" => "������������ �����������",
			"23" => "������������ �����������",
			"24" => "�������� ��������",			
			"25" => "���",
			"26" => "����"
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
			"16" => "SIMPLE_QUESTION_373",
			"17" => "SIMPLE_QUESTION_552",
			"18" => "SIMPLE_QUESTION_383",
			"19" => "SIMPLE_QUESTION_244",
			"20" => "SIMPLE_QUESTION_212",
			"21" => "SIMPLE_QUESTION_497",
			"22" => "SIMPLE_QUESTION_526",
			"23" => "SIMPLE_QUESTION_878",
			"24" => "SIMPLE_QUESTION_166",			
			"25" => "SIMPLE_QUESTION_762",
			"26" => "SIMPLE_QUESTION_211"
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
			"18" => "ANSWER_TEXT",
			"19" => "ANSWER_TEXT",
			"20" => "ANSWER_TEXT",
			"21" => "ANSWER_TEXT",
			"22" => "ANSWER_TEXT",
			"23" => "ANSWER_TEXT",
			"24" => "USER_TEXT",			
			"25" => "ANSWER_TEXT",
			"26" => "USER_TEXT"
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
			"16" => "EMAIL",
			"17" => "SITE",
			"18" => "DESTINITIONS",
			"19" => "DESTINITIONS",
			"20" => "DESTINITIONS",
			"21" => "DESTINITIONS",
			"22" => "DESTINITIONS",
			"23" => "DESTINITIONS",
			"24" => "DESC",			
			"25" => "HALL",
			"26" => "TABLE"
		),
	);

	static $arExelEvGuestField = array(
		"NAMES" => array(
			"0" => "��������",
			"1" => "��� ������������",
			"2" => "�����",
			"3" => "������",
			"4" => "�����",
			"5" => "������",
			"6" => "������ (other)",
			"7" => "���",
			"8" => "�������",
			"9" => "���������",
			"10" => "�������",
			"11" => "���. �������",
			"12" => "E-mail",
			"13" => "Web-site ��������",
			"14" => "��� ������� 1",
			"15" => "������� ������� 1",
			"16" => "��������� ������� 1",
			"17" => "E-mail ������� 1",
			"18" => "��� ������� 2",
			"19" => "������� ������� 2",
			"20" => "��������� ������� 2",
			"20" => "E-mail ������� 2",
			"20" => "��� ������� 3",
			"23" => "������� ������� 3",
			"24" => "��������� ������� 3",
			"25" => "E-mail ������� 3"
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
			"9" => "SIMPLE_QUESTION_391",
			"10" => "SIMPLE_QUESTION_636",
			"11" => "SIMPLE_QUESTION_844",
			"12" => "SIMPLE_QUESTION_373",
			"13" => "SIMPLE_QUESTION_552",
			"14" => "SIMPLE_QUESTION_367",
			"15" => "SIMPLE_QUESTION_482",
			"16" => "SIMPLE_QUESTION_187",
			"17" => "SIMPLE_QUESTION_421",
			"18" => "SIMPLE_QUESTION_225",
			"19" => "SIMPLE_QUESTION_770",
			"20" => "SIMPLE_QUESTION_280",
			"21" => "SIMPLE_QUESTION_384",
			"22" => "SIMPLE_QUESTION_765",
			"23" => "SIMPLE_QUESTION_627",
			"24" => "SIMPLE_QUESTION_788",
			"25" => "SIMPLE_QUESTION_230"
		),
		"ANS_TYPE" => array(
			"0" => "USER_TEXT",
			"1" => "ANSWER_TEXT",
			"2" => "USER_TEXT",
			"3" => "USER_TEXT",
			"4" => "USER_TEXT",
			"5" => "ANSWER_TEXT",
			"6" => "USER_TEXT",
			"7" => "USER_TEXT",
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
			"25" => "USER_TEXT"
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
			"12" => "EMAIL",
			"13" => "SITE",
			"14" => "F_NAME_COL1",
			"15" => "L_NAME_COL1",
			"16" => "JOB_COL1",
			"17" => "EMAIL_COL1",
			"18" => "F_NAME_COL2",
			"19" => "L_NAME_COL2",
			"20" => "JOB_COL2",
			"21" => "EMAIL_COL2",
			"22" => "F_NAME_COL3",
			"23" => "L_NAME_COL3",
			"24" => "JOB_COL3",
			"25" => "EMAIL_COL3"
		),
	);

	private static $arFormGuestQuestions = array(
		"NAMES" => array(
					"0"=>"�������� ��������",
					"1"=>"��� ������������",
					"2"=>"����������� ����� ��������",
					"3"=>"������",
					"4"=>"�����",
					"5"=>"������",
					"6"=>"������ (other)",
					"7"=>"���",
					"8"=>"�������",
					"9"=>"���������",
					"10"=>"�������",
					"11"=>"E-mail",
					"12"=>"������� E-mail ��� ���",
					"13"=>"http://",
					"14"=>"��� ������� 1",
					"15"=>"������� ������� 1",
					"16"=>"��������� ������� 1",
					"17"=>"E-mail ������� 1",
					"18"=>"��� ������� 2",
					"19"=>"������� ������� 2",
					"20"=>"E-mail ������� 2",
					"21"=>"��������� ������� 2",
					"22"=>"��� ������� 3",
					"23"=>"������� ������� 3",
					"24"=>"��������� ������� 3",
					"25"=>"E-mail ������� 3",
					"LOGIN"=>"������� �����/�������� ���",
					"27"=>"������� ������",
					"28"=>"��������� ������",
					"29"=>"������� ������� ��������",
					"30"=>"North America",
					"31"=>"Europe",
					"32"=>"South America",
					"33"=>"Africa",
					"34"=>"Asia",
					"35"=>"Oceania",
					"36"=>"��� ������� (�� ����)",
					"37"=>"������� ������� (�� ����)",
					"38"=>"��������� ������� (�� ����)",
					"39"=>"E-mail ������� (�� ����)",
					"40"=>"����",
					"41"=>"�����",
					"42"=>"���",
					"43"=>"����"
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
					"11"=>"117",
					"12"=>"118",
					"13"=>"119",
					"14"=>"120",
					"15"=>"121",
					"16"=>"122",
					"17"=>"123",
					"18"=>"124",
					"19"=>"125",
					"20"=>"126",
					"21"=>"127",
					"22"=>"128",
					"23"=>"129",
					"24"=>"130",
					"25"=>"131",
					"LOGIN"=>"132",
					"27"=>"133",
					"28"=>"134",
					"29"=>"135",
					"30"=>"136",
					"31"=>"137",
					"32"=>"138",
					"33"=>"139",
					"34"=>"475",
					"35"=>"476",
					"36"=>"477",
					"37"=>"478",
					"38"=>"479",
					"39"=>"480",
					"40"=>"481",
					"41"=>"482",
					"42"=>"570",
					"43"=>"571"
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
			"0"=>"�������� ��������",
			"1"=>"��� ������������",
			"2"=>"����������� ����� ��������",
			"3"=>"������",
			"4"=>"�����",
			"5"=>"������",
			"6"=>"������ (other)",
			"7"=>"���",
			"8"=>"�������",
			"9"=>"���������",
			"10"=>"�������",
			"11"=>"E-mail",
			"12"=>"������� E-mail ��� ���",
			"13"=>"http://",
			"14"=>"��� ������� 1",
			"15"=>"������� ������� 1",
			"16"=>"��������� ������� 1",
			"17"=>"E-mail ������� 1",
			"18"=>"��� ������� 2",
			"19"=>"������� ������� 2",
			"20"=>"��������� ������� 2",
			"21"=>"E-mail ������� 2",
			"22"=>"��� ������� 3",
			"23"=>"������� ������� 3",
			"24"=>"��������� ������� 3",
			"25"=>"E-mail ������� 3",
			"26"=>"������� �����/�������� ���",
			"27"=>"������� ������",
			"28"=>"��������� ������",
			"29"=>"������� ������� ��������",
			"30"=>"North America",
			"31"=>"Europe",
			"32"=>"South America",
			"33"=>"Africa",
			"34"=>"Asia",
			"35"=>"Oceania",
			"36"=>"��� ������� (�� ����)",
			"37"=>"������� ������� (�� ����)",
			"38"=>"��������� ������� (�� ����)",
			"39"=>"E-mail ������� (�� ����)",
			"40"=>"����",
			"41"=>"�����",
			"42"=>"���",
			"43"=>"����"
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
			"11"=>"EMAIL",
			"12"=>"EMAIL_REP",
			"13"=>"SITE",
			"14"=>"F_NAME_COL1",
			"15"=>"L_NAME_COL1",
			"16"=>"JOB_COL1",
			"17"=>"EMAIL_COL1",
			"18"=>"F_NAME_COL2",
			"19"=>"L_NAME_COL2",
			"20"=>"JOB_COL2",
			"21"=>"EMAIL_COL2",
			"22"=>"F_NAME_COL3",
			"23"=>"L_NAME_COL3",
			"24"=>"JOB_COL3",
			"25"=>"EMAIL_COL3",
			"26"=>"LOGIN",
			"27"=>"PASSWORD",
			"28"=>"PASSWORD_REP",
			"29"=>"DESC",
			"30"=>"DESTINITIONS",
			"31"=>"DESTINITIONS",
			"32"=>"DESTINITIONS",
			"33"=>"DESTINITIONS",
			"34"=>"DESTINITIONS",
			"35"=>"DESTINITIONS",
			"36"=>"F_NAME_COL",
			"37"=>"L_NAME_COL",
			"38"=>"JOB_COL",
			"39"=>"EMAIL_COL",
			"40"=>"MORNING",
			"41"=>"EVENING",
			"42"=>"HALL",
			"43"=>"TABLE"
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
			"11"=>"SIMPLE_QUESTION_373",
			"12"=>"SIMPLE_QUESTION_279",
			"13"=>"SIMPLE_QUESTION_552",
			"14"=>"SIMPLE_QUESTION_367",
			"15"=>"SIMPLE_QUESTION_482",
			"16"=>"SIMPLE_QUESTION_187",
			"17"=>"SIMPLE_QUESTION_421",
			"18"=>"SIMPLE_QUESTION_225",
			"19"=>"SIMPLE_QUESTION_770",
			"20"=>"SIMPLE_QUESTION_280",
			"21"=>"SIMPLE_QUESTION_384",
			"22"=>"SIMPLE_QUESTION_765",
			"23"=>"SIMPLE_QUESTION_627",
			"24"=>"SIMPLE_QUESTION_788",
			"25"=>"SIMPLE_QUESTION_230",
			"26"=>"SIMPLE_QUESTION_474",
			"27"=>"SIMPLE_QUESTION_435",
			"28"=>"SIMPLE_QUESTION_300",
			"29"=>"SIMPLE_QUESTION_166",
			"30"=>"SIMPLE_QUESTION_383",
			"31"=>"SIMPLE_QUESTION_244",
			"32"=>"SIMPLE_QUESTION_212",
			"33"=>"SIMPLE_QUESTION_497",
			"34"=>"SIMPLE_QUESTION_526",
			"35"=>"SIMPLE_QUESTION_878",
			"36"=>"SIMPLE_QUESTION_816",
			"37"=>"SIMPLE_QUESTION_596",
			"38"=>"SIMPLE_QUESTION_304",
			"39"=>"SIMPLE_QUESTION_278",
			"40"=>"SIMPLE_QUESTION_836",
			"41"=>"SIMPLE_QUESTION_156",
			"42"=>"SIMPLE_QUESTION_762",
			"43"=>"SIMPLE_QUESTION_211"
		)
	);

	private static $arExhForm = array(
		358 => 8, //������, ������. 2 ������� 2014
		357 => 5, //����, ������������. 10 ������ 2014
		359 => 7, //������, ���������. 26 �������� 2014
		360 => 6, //����, �������. 23 �������� 2014
		361 => 4, //������, ������. 13 ����� 2014
		488 => 25, //������, ������. 12 ����� 2015
		3521 => 26, //������, ���������. �������� 2015
		3522 => 27, //����, �������. �������� 2015
		3523 => 28 //������, ������. ������� 2015
	);

	private static $arExhGuestForm = array(
		358 => 24, //������, ������. 2 ������� 2014
		357 => 21, //����, ������������. 10 ������ 2014
		359 => 23, //������, ���������. 26 �������� 2014
		360 => 22, //����, �������. 23 �������� 2014
		361 => 10, //������, ������. 13 ����� 2014
		488 => 10 //������, ������. 12 ����� 2015
	);

	//id �������� ������� ��� ������
	private static $arPostTemplateByExhibID = array(
		358 => array("GUEST_EVENING"=>82, "GUEST_MORNING"=>72), //������, ������. 2 ������� 2014
		357 => array("GUEST_EVENING"=>79, "GUEST_MORNING"=>75), //����, ������������. 10 ������ 2014
		359 => array("GUEST_EVENING"=>81, "GUEST_MORNING"=>73), //������, ���������. 26 �������� 2014
		360 => array("GUEST_EVENING"=>80, "GUEST_MORNING"=>74), //����, �������. 23 �������� 2014
		361 => array("GUEST_EVENING"=>71, "GUEST_MORNING"=>70), //������, ������. 13 ����� 2014
		488 => array("GUEST_EVENING"=>78, "GUEST_MORNING"=>76) //������, ������. 12 ����� 2015 ??
	);

	private static $arExhProp = array(
		358 => array("UF_ID5", "UF_ID10"),//������, ������. 2 ������� 2014
		357 => array("UF_ID2", "UF_ID7"),//����, ������������. 10 ������ 2014
		359 => array("UF_ID4", "UF_ID9"),//������, ���������. 26 �������� 2014
		360 => array("UF_ID3", "UF_ID8"),//����, �������. 23 �������� 2014
		361 => array("UF_ID", "UF_ID6"),//������, ������. 13 ����� 2014
		488 => array("UF_ID11", "UF_ID12"),//������, ������. 12 ����� 2015
		3521 => array("UF_ALM2015", "UF_ALM2015COL"), //������, ���������. �������� 2015
		3522 => array("UF_KIEV2015", "UF_KIEV2015COL"), //����, �������. �������� 2015
		3523 => array("UF_MSCAUT2015", "UF_MSCAUT2015COL") //������, ������. ������� 2015
	);

	static $userFields = array("UF_ID", "UF_ID2", "UF_ID3", "UF_ID4", "UF_ID5","UF_ID11", "UF_ALM2015", "UF_KIEV2015", "UF_MSCAUT2015");

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
			return;
		}

		return self::$arExhForm[$id];
	}

	static function getGResultIDByExh($id)
	{
		if(intval($id) == 0)
		{
			return;
		}

		return self::$arExhGuestForm[$id];
	}

	static function getPropertyIDByExh($id, $member = 0)
	{
		if(intval($id) == 0)
		{
			return;
		}

		if(1 == $member)
		{
			return self::$arExhProp[$id][1];
		}
		else
		{
			return self::$arExhProp[$id][0];
		}

		;
	}

	static function getFormIDByQID($id)
	{
		if(intval($id) == 0)
		{
			return;
		}

		foreach (self::$arFormQuestions as $formID => $questions)
		{
			if(in_array($id, $questions))
			{
				return $formID;
			}
		}
		return;
	}

	static function getIndexQ($id, $form)
	{
		if(!intval($id))
		{
			return;
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
		return;
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
		return;
	}

	static function getQIDByBase($id, $form)
	{
		if(!intval($id))
		{
			return;
		}

		if(intval($form))
		{
			$id = array_search($id, self::$arFormQuestions[4]);
			return self::$arFormQuestions[$form][$id];
		}
		return;
	}
}




?>