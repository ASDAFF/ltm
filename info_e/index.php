<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Информация о компаниях и их представителях");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("Информация о компаниях и их представителях");
?>
<script src = "/info_e/info_e.js"></script>
<link href="/info_e/info_e.css" type="text/css" rel="stylesheet" />
<?
CModule::IncludeModule("form");
//Все результаты формы
CForm::GetResultAnswerArray(
	LuxorConfig::ID_E_FORM, 
	$arrColumns, 
	$arrAnswers, 
	$arrAnswersVarname, 
	array()
);
$arForm = array();
foreach($arrAnswersVarname as $k=>$v){
	$arForm[] = $v[LuxorConfig::GROUP_NAME_FORM][0];
}
$arCompanyName = array();
foreach($arForm as $v){
	$arCompanyName[$v['RESULT_ID']] = $v['USER_TEXT'];
}
$mainAr = array();
$i = 0;
//Получаем массив с данными по участникам и привязываем его к компаниям
foreach($arCompanyName as $key=>$val){
	$filter = Array(
		"UF_ID_COMP"          => $key,
		"GROUPS_ID"           => LuxorConfig::$GROUP_E_AR
	); 
	$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter, array("SELECT"=>array("UF_ID_COMP", "UF_PAS")));
	while ($arUser = $rsUsers->Fetch()){
		$mainAr[$i]['NAME_COMPANY'] = $val;
		$mainAr[$i]['E_LOGIN'] = $arUser['LOGIN'];
		$mainAr[$i]['E_MAIL'] = $arUser['EMAIL'];
		$mainAr[$i]['E_PAS'] = $depas = LuxorConfig::returnPas($arUser['UF_PAS']);
		$i++;
	}
}
//сортируем по дате регистрации компании. Самые новые - вверху таблицы
$mainAr = array_reverse($mainAr);
//Вывод
echo '<table id = "e_info">';
	echo '<tr class = "tr_e_f">';
		echo '<td>Название компании</td>';
		echo '<td style = "width: 120px;">Логин участника</td>';
		echo '<td style = "width: 300px;">E-mail участника</td>';
		echo '<td>Пароль участника</td>';
	echo '</tr>';
foreach($mainAr as $mV){
	echo '<tr>';
		echo '<td>'.$mV['NAME_COMPANY'].'</td>';
		echo '<td>'.$mV['E_LOGIN'].'</td>';
		echo '<td>'.$mV['E_MAIL'].'</td>';
		echo '<td>'.$mV['E_PAS'].'</td>';
	echo '</tr>';
}
echo '</table>';
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>