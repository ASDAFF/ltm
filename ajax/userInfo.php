<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
/* Инофрмация о крмпании */
$filter = array( 'ID' => $_REQUEST["id"]);
$select = array(
    'SELECT' => array("UF_ID_COMP"),
    'FIELDS' => array('WORK_COMPANY', 'ID')
);
$rsUser = CUser::GetList(($by="id"), ($order="desc"), $filter, $select);
if ($arUser = $rsUser->Fetch()) {
    $formRes = $arUser["UF_ID_COMP"];
}

if(CModule::IncludeModule("form")){
    $arAnswer = CFormResult::GetDataByID(
        $formRes,
        array(),  // вопрос "Какие области знаний вас интересуют?"
        $arResultTmp,
        $arAnswer2);
}
$arResult["DECS"] = "";
$arResult["SITE"] = "";
$arResult["NAME"] = "";
$arResult["COUNTRY"] = "";
$arResult["CITY"] = "";
foreach($arAnswer2 as $answer){
    $curArr = current($answer);
    if($curArr["TITLE"] == "Company or hotel name" || $curArr["TITLE"] == "Название компании"){
        $arResult["NAME"] = $curArr["USER_TEXT"];
    }
    if($curArr["TITLE"] == "City" || $curArr["TITLE"] == "Город"){
        $arResult["CITY"] = $curArr["USER_TEXT"];
    }
    if($curArr["TITLE"] == "Country"){
        $arResult["COUNTRY"] = $curArr["USER_TEXT"];
    }
    if($curArr["TITLE"] == "http://"){
        $arResult["SITE"] = "http://".$curArr["USER_TEXT"];
    }
    if($curArr["TITLE"] == "Company description" || $curArr["TITLE"] == "Введите краткое описание"){
        $arResult["DECS"] = $curArr["USER_TEXT"];
    }
    if($curArr["TITLE"] == "Страна"){
        $arResult["COUNTRY"] = $curArr["MESSAGE"];
    }
    if($curArr["TITLE"] == "Имя"){
        $arResult["REP"] = $curArr["USER_TEXT"];
    }
    if($curArr["TITLE"] == "Фамилия"){
        $arResult["REP"] .= " ".$curArr["USER_TEXT"];
    }
}

if(isset($_REQUEST["res"]) && $_REQUEST["res"] != "" && $formRes != $_REQUEST["res"]){
    $arResultTmp = $arAnswer2 = array();
    $arAnswer = CFormResult::GetDataByID(
        $_REQUEST["res"],
        array(),  // вопрос "Какие области знаний вас интересуют?"
        $arResultTmp,
        $arAnswer2);
    $arResult["REP"] = "";
    foreach($arAnswer2 as $answer){
        $curArr = current($answer);
        if($curArr["TITLE"] == "Participant first name"){
            $arResult["REP"] = $curArr["USER_TEXT"];
        }
        if($curArr["TITLE"] == "Participant last name"){
            $arResult["REP"] .= " ".$curArr["USER_TEXT"];
        }

    }
}
$arResult["DECS"] = str_replace("\n", "<br>", $arResult["DECS"]);
?>
<div class="shedule-info clearfix">
    <p class="shedule-info__title">
        <?=$arResult["NAME"]?>, <?=$arResult["REP"]?><br>
        <?=$arResult["COUNTRY"]?>, <?=$arResult["CITY"]?>
    </p>
    <p class="shedule-info__desc"><?=$arResult["DECS"]?></p>
    <p class="shedule-info__close">OK</p>
</div>
