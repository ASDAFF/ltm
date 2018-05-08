<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/service/([0-9a-zA-Z-_]+).php",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/service/\$2.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/messages2/contact/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/messages_old/contact/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([^/]+)/guest/matrix_hb/.*.*.*.*.*.*.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/guest/matrix_hb/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/messages/contact/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/messages/contact/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/morning/schedule/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/morning/schedule/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/messages2/inbox/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/messages_old/inbox/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/edit/colleague/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/edit/colleague.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/messages2/sent/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/messages_old/sent/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/messages/inbox/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/messages/inbox/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/messages/read/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/messages/read/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/messages/sent/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/messages/sent/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([^/]+)/guest/matrix/.*.*.*.*.*.*.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/guest/matrix/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/messages2/new/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/messages_old/new/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([0-9a-zA-Z-_]+)/messages/inbox/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/messages/inbox/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/morning/list/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/morning/list/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/messages/new/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/messages/new/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/edit/profile/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/edit/profile.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/edit/company/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/edit/participant-company.php",
	),
	array(
		"CONDITION" => "#^/admin/([0-9a-zA-Z-_]+)/messages/sent/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/messages/sent/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/hb/schedule/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/hb/schedule/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([^/]+)/participant/matrix_hb/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/participant/matrix_hb/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([0-9a-zA-Z-_]+)/messages/new/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/messages/new/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/messages2/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/messages_old/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/messages/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/messages/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/deadline/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/deadline/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([^/]+)/participant/matrix/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/participant/matrix/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/evening/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/evening/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/morning/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/morning/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/catalog/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/catalog/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/hb/list/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/hb/list/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([0-9a-zA-Z-_]+)/messages/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/messages/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([^/]+)/participant/spam/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/participant/spam/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([^/]+)/participant/off/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/participant/off/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([^/]+)/participant/on/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/participant/on/index.php",
	),
	array(
		"CONDITION" => "#^/members/([0-9]+)/.*.*.*.*.*.*.*#",
		"RULE" => "ID=\$1",
		"ID" => "",
		"PATH" => "/members/detail.php",
	),
	array(
		"CONDITION" => "#^/admin/([^/]+)/guest/([^/]+)/.*#",
		"RULE" => "EXHIBIT_CODE=\$1&ACT=\$2",
		"ID" => "",
		"PATH" => "/admin/guest/guest-list.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/hb/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/hb/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/([0-9a-zA-Z-_]+)/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/cabinet/profile.php",
	),
	array(
		"CONDITION" => "#^/members/([0-9a-zA-Z-_]+)/.*#",
		"RULE" => "CODE=\$1",
		"ID" => "",
		"PATH" => "/members/index.php",
	),
	array(
		"CONDITION" => "#^/admin/([0-9a-zA-Z-_]+)/.*#",
		"RULE" => "EXHIBIT_CODE=\$1",
		"ID" => "",
		"PATH" => "/admin/index.php",
	),
	array(
		"CONDITION" => "#^/organizers/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/organizers/index.php",
	),
	array(
		"CONDITION" => "#^/partners/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/partners/index.php",
	),
	array(
		"CONDITION" => "#^/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/news/index.php",
	),



    array(
        "CONDITION" => "#^/rst/user/getSelf/([0-9a-zA-Z-_]+)/#",
        "RULE" => "call=user&func=getSelf&login=$1&$2",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/getInfo/([0-9]+)/#",
        "RULE" => "call=user&func=getInfo&id=$1&$2",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/getExhibitorsList/([0-9]+)/([0-9]+)/#",
        "RULE" => "call=user&func=getExhibitorsList&offset=$1&limit=$2&$3",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    /*
	array(
		"CONDITION" => "#^/rst/user/getExhibitorsList/([0-9a-zA-Z-_]+)/#",
		"RULE" => "call=user&func=getExhibitorsList&login=$1&$2",
		"ID" => "bitrix:news",
		"PATH" => "/rst/api.php",
	),*/
    array(
        "CONDITION" => "#^/rst/user/getExhibitorsList/#",
        "RULE" => "call=user&func=getExhibitorsList&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/getBuyersList/([0-9a-zA-Z-_]+)/#",
        "RULE" => "call=user&func=getBuyersList&login=$1&$2",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/getBuyersList/#",
        "RULE" => "call=user&func=getBuyersList&login=&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/getFavorites/#",
        "RULE" => "call=user&func=getFavorites&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/getNearUser/([0-9]+)/([0-9]+)/#",
        "RULE" => "call=user&func=getNearUser&offset=$1&limit=$2&$3",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/feedbackQuestions/#",
        "RULE" => "call=user&func=feedbackQuestions&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/sendFeedback/#",
        "RULE" => "call=user&func=sendFeedback&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),

    array(
        "CONDITION" => "#^/rst/user/find/#",
        "RULE" => "call=user&func=find&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/find/([0-9a-zA-Z-_]+)/#",
        "RULE" => "call=user&func=find&q=$1&$2",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),

    array(
        "CONDITION" => "#^/rst/user/addToFavorite/#",
        "RULE" => "call=user&func=addToFavorite&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/removeFromFavorite/#",
        "RULE" => "call=user&func=removeFromFavorite&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/checkIn/#",
        "RULE" => "call=user&func=checkIn&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/checkOut/#",
        "RULE" => "call=user&func=checkOut&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),

    array(
        "CONDITION" => "#^/rst/user/addToBlockList/#",
        "RULE" => "call=user&func=addToBlockList&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/removeFromBlockList/#",
        "RULE" => "call=user&func=removeFromBlockList&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/removeAllFromBlockList/#",
        "RULE" => "call=user&func=removeAllFromBlockList&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/getSettings/([0-9]+)/#",
        "RULE" => "call=user&func=getSettings&user_id=$1&$2",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/ring/#",
        "RULE" => "call=user&func=ring&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/user/abuse/#",
        "RULE" => "call=user&func=abuse&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),

    array(
        "CONDITION" => "#^/rst/user/logout/#",
        "RULE" => "call=user&func=logout&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),


    array(
        "CONDITION" => "#^/rst/user/addAvatar/#",
        "RULE" => "call=user&func=addAvatar&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),



    array(
        "CONDITION" => "#^/rst/chat/getMessage/([0-9]+)/([0-9]+)/([0-9]+)/#",
        "RULE" => "call=chat&func=getMessage&ID=$1&AUTHOR_ID=$2&TO_USER=$3&$4",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/chat/getAdminMessage/([0-9]+)/([0-9]+)/([0-9]+)/#",
        "RULE" => "call=chat&func=getAdminMessage&ID=$1&AUTHOR_ID=$2&TO_USER=$3&$4",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/chat/getMessages/([0-9]+)/([0-9]+)/([0-9]+)/([0-9]+)/#",
        "RULE" => "call=chat&func=getMessages&AUTHOR_ID=$1&TO_USER=$2&offset=$3&limit=$4&$5",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/chat/getAdminMessages/([0-9]+)/([0-9]+)/([0-9]+)/#",
        "RULE" => "call=chat&func=getAdminMessages&TO_USER=$1&offset=$2&limit=$3&$4",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/chat/postMessage/#",
        "RULE" => "call=chat&func=postMessage&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/chat/postAdminMessage/#",
        "RULE" => "call=chat&func=postAdminMessage&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/chat/getChats/#",
        "RULE" => "call=chat&func=getChats&$1",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/shedule/getUserShedule/([0-9]+)/#",
        "RULE" => "call=shedule&func=getUserShedule&h=".time()."&SENDER_ID=$1&$2",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/shedule/postUserShedule/#",
        "RULE" => "call=shedule&func=postUserShedule",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/shedule/postSheduleStatusSender/#",
        "RULE" => "call=shedule&func=postSheduleStatusSender",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
    array(
        "CONDITION" => "#^/rst/shedule/postSheduleStatusReceiver/#",
        "RULE" => "call=shedule&func=postSheduleStatusReceiver",
        "ID" => "bitrix:news",
        "PATH" => "/rst/api.php",
    ),
);

?>