<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");
$isParticipant = $_REQUEST['userType'] !== 'GUEST';
$id            = (int)$_REQUEST['id'];

$userInfo = [
    'DESC'    => '',
    'SITE'    => '',
    'NAME'    => '',
    'COUNTRY' => '',
    'CITY'    => '',
    'REP'     => '',
];

if ($isParticipant) {
    if (CModule::IncludeModule('form')) {
        // ID результата веб-формы с данными представителя участника
        $formResRepId = (int)$_REQUEST['res'];
        $filter = ['ID' => $id];
        $select = [
            'SELECT' => ['UF_ID_COMP'],
            'FIELDS' => ['WORK_COMPANY', 'ID'],
        ];
        $by     = 'id';
        $order  = 'desc';
        $rsUser = CUser::GetList($by, $order, $filter, $select);
        if ($arUser = $rsUser->Fetch()) {
            // ID результата веб-формы с данными участника
            $formResId            = (int)$arUser['UF_ID_COMP'];
            $arFormRes            = [];
            $arParticipantAnswers = [];
            CFormResult::GetDataByID($formResId, [], $arFormRes, $arParticipantAnswers);
            foreach ($arParticipantAnswers as $answer) {
                $curArr = current($answer);
                switch ($curArr['TITLE']) {
                    case 'Company or hotel name':
                    case 'Название компании':
                        $userInfo['NAME'] = $curArr['USER_TEXT'];
                        break;
                    case 'City':
                    case 'Город':
                        $userInfo['CITY'] = $curArr['USER_TEXT'];
                        break;
                    case 'Country':
                        $userInfo['COUNTRY'] = $curArr['USER_TEXT'];
                        break;
                    case 'Страна':
                        $userInfo['COUNTRY'] = $curArr['MESSAGE'];
                        break;
                    case 'http://':
                        $userInfo['SITE'] = 'http://'.$curArr['USER_TEXT'];
                        break;
                    case 'Company description':
                    case 'Введите краткое описание':
                        $userInfo['DESC'] = $curArr['USER_TEXT'];
                        break;
                    case 'Имя':
                        $userInfo['REP'] = $curArr['USER_TEXT'];
                        break;
                    case 'Фамилия':
                        $userInfo['REP'] .= ' '.$curArr['USER_TEXT'];
                        break;
                }
            }
            if ($formResRepId && $formResId !== $formResRepId) {
                CFormResult::GetDataByID($formResRepId, [], $arRepFormRes, $arRepAnswers);
                $userInfo['REP'] = '';
                foreach ($arRepAnswers as $answer) {
                    $curArr = current($answer);
                    switch ($curArr['TITLE']) {
                        case 'Participant first name':
                            $userInfo['REP'] = $curArr['USER_TEXT'];
                            break;
                        case 'Participant last name':
                            $userInfo['REP'] .= ' '.$curArr['USER_TEXT'];
                            break;
                    }
                }
            }
        }
    }
} else {
    if (CModule::IncludeModule('doka.meetings')) {
        $data                = Spectr\Meeting\Models\RegistrGuestTable::getRowByUserID($id);
        $userInfo['NAME']    = $data['UF_COMPANY'];
        $userInfo['REP']     = "{$data['UF_NAME']} {$data['UF_SURNAME']}";
        $userInfo['COUNTRY'] = $data['COUNTRY_NAME'];
        $userInfo['CITY']    = $data['UF_CITY'];
        $userInfo['DESC']    = $data['UF_DESCRIPTION'];
    }
}
$userInfo['DESC'] = str_replace("\n", "<br>", $userInfo['DESC']);
?>
<div class="shedule-info clearfix">
    <p class="shedule-info__title">
        <?= $userInfo['NAME'] ?>, <?= $userInfo['REP'] ?><br>
        <?= $userInfo['COUNTRY'] ?>, <?= $userInfo['CITY'] ?>
    </p>
    <p class="shedule-info__desc"><?= $userInfo['DESC'] ?></p>
    <p class="shedule-info__close">OK</p>
</div>
