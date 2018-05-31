<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?IncludeTemplateLangFile(__FILE__);?>
<!DOCTYPE html>
<html class="<?= LANGUAGE_ID?>">
<head>
	<title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery-1.10.2.min.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery-ui-1.10.4.custom.min.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/fancybox/jquery.fancybox.js')?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/js/fancybox/jquery.fancybox.css',true)?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/js/fancybox/jquery.fancybox-thumbs.css')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/fancybox/jquery.fancybox-thumbs.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/ajaxupload.3.5.js')?>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/js/validity/jquery.validity.css')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/validity/jquery.validity.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/script.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/normalize.css')?>
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<?$APPLICATION->ShowHead();?>
</head>
<body <? if(stristr($APPLICATION->GetCurPage(), "/cabinet/")):?>class="cabinet"<?endif;?>>
<!-- Включаемая область для pop-up на главной странице -->
     <?$APPLICATION->IncludeComponent("bitrix:main.include", "", 
        Array(
            "AREA_FILE_SHOW" => "page", 
            "AREA_FILE_SUFFIX" => "popup", 
            "EDIT_TEMPLATE" => ""
        )
    );?>

    
	<div class="main_container">
	<? CJSCore::Init("ajax"); ?>
	<? if ($USER->isAdmin())$APPLICATION->ShowPanel();?>
	<header>
		<div class="layoutCenterWrapper">
			<a href = "/" id = "logo" alt = "Luxury Travel Mart" /></a>
			<div class="title">
				<p class="title-name">Luxury Travel Mart</p>
				<p class="title-exhib">Moscow, Spring / Kiev / Almaty / Moscow, Autumn</p>
			</div>
			<div id = "lang">
			    <a class = "en<?= (LANGUAGE_ID == "en")?" act":"";?>" href="<?= $APPLICATION->GetCurPageParam("lang_ui=en",array("lang_ui", "EXHIBIT_CODE", "CODE"));?>"><?= (LANGUAGE_ID == "en")?">":"";?>English</a>
                <a class = "ru<?= (LANGUAGE_ID == "ru")?" act":"";?>" href="<?= $APPLICATION->GetCurPageParam("lang_ui=ru",array("lang_ui", "EXHIBIT_CODE", "CODE"));?>"><?= (LANGUAGE_ID == "ru")?">":"";?>Русский</a>
			</div>
		</div>
	</header>

	<?$APPLICATION->IncludeComponent("bitrix:menu", "top", array(
       	"ROOT_MENU_TYPE" => "top_".LANGUAGE_ID,
       	"MENU_CACHE_TYPE" => "A",
       	"MENU_CACHE_TIME" => "3600",
       	"MENU_CACHE_USE_GROUPS" => "Y",
       	"MENU_CACHE_GET_VARS" => array("UID","EXHIBIT_CODE"
       	),
       	"MAX_LEVEL" => "1",
       	"CHILD_MENU_TYPE" => "top_".LANGUAGE_ID,
       	"USE_EXT" => "Y",
       	"DELAY" => "N",
       	"ALLOW_MULTI_SELECT" => "N"
       	),
      	false
    );?>
	<div id = "content" class="layoutCenterWrapper clearfix">
		<h1><?$APPLICATION->ShowTitle(false)?></h1>
		<div id = "center">
		<? if(stristr($APPLICATION->GetCurPage(), "/cabinet/")):?>
		    <?
		    $APPLICATION->SetPageProperty("title", "Luxury Travel Mart");
            $APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
            $APPLICATION->SetTitle("THE LEADING LUXURY TRAVEL EXHIBITION");?>

		    <div class="exhibition-block">
		    <?  $exhibCode = trim($_REQUEST["EXHIBIT_CODE"]);

			    if($exhibCode)
			    {
			        $userType = $_SESSION["USER_TYPE"]; //генерится в компоненте авторизации rarus:auth.form

			        $userId;
			        if($USER->IsAdmin() && isset($_REQUEST["UID"])) {
			            $userId = intval($_REQUEST["UID"]);
			        } else {
			            $userId = $USER->GetID();
			        }

			        $bUserTypeIsset = false;
			        $arUserGroups = CUser::GetUserGroup($userId);

    			    if(!$userType)//если не получилось получить из сессии получаем таким образом, обычно сюда не должно попадать
                    {

                    	//прверка на участника
                    	if(!$bUserTypeIsset)
                    	{
                    	    $arPartGroupID = array("10","9", "12", "11", "14","13", "16", "15", "18", "17", "21", "20", "43", "42", "46","47","50","51","55","56");
                        	foreach ($arCPartGroupID as $userGroupID)
                        	{
                        		if(in_array($userGroupID, $arUserGroups))
                        		{
                        			$userType = "PARTICIPANT";
                        			$bUserTypeIsset = true;
                        			break;
                        		}
                        	}
                    	}

                    	//прверка на гостя
                    	if(!$bUserTypeIsset)
                    	{
                    	    $arGuestGroupID = array("22", "25", "23", "26", "24", "27", "19", "44","48","52","57");
                        	foreach ($arGuestGroupID as $userGroupID)
                        	{
                        		if(in_array($userGroupID, $arUserGroups))
                        		{
                        			$userType = "GUEST";
                        			$bUserTypeIsset = true;
                        			break;
                        		}
                        	}
                    	}

                        $_SESSION["USER_TYPE"] = $userType;
                    }

			        if("PARTICIPANT" == $userType /* && ("Y" == PARTICIPANT_CABINET || $USER->IsAdmin())*/)
			        {
			            //проверка на доступ пользователя к кабинету участника

			            CModule::IncludeModule("iblock");
			            $rsExhib = CIBlockElement::GetList(array(), array("IBLOCK_ID" => "15", "CODE" => $exhibCode, "ACTIVE" => "Y"), false, false, array("PROPERTY_USER_GROUP_ID", "PROPERTY_APP_HB_ID"));
			            if($arExhib = $rsExhib->Fetch())
			            {

			            	$exhibGoup = $arExhib["PROPERTY_USER_GROUP_ID_VALUE"];

							$menuType = 'participant.bottom';
							if($arExhib["PROPERTY_APP_HB_ID_VALUE"] != ""){
								$menuType = 'hb_participant.bottom';
							}
							else{
								$menuType = 'participant.bottom';
							}
			            	if(in_array($exhibGoup, $arUserGroups) || $USER->IsAdmin())//если в группе подтвержденных
			            	{

			            	    $APPLICATION->IncludeComponent("bitrix:menu", "participant_top",
			            	        Array(
			            	            "ROOT_MENU_TYPE" => "participant.top",	// Тип меню для первого уровня
			            	            "MAX_LEVEL" => "2",	// Уровень вложенности меню
			            	            "CHILD_MENU_TYPE" => "",	// Тип меню для остальных уровней
			            	            "USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
			            	            "DELAY" => "N",	// Откладывать выполнение шаблона меню
			            	            "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
			            	            "MENU_CACHE_TYPE" => "N",	// Тип кеширования
			            	            "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
			            	            "MENU_CACHE_USE_GROUPS" => "N",	// Учитывать права доступа
			            	            "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
			            	        ),
			            	        false
	    			            );?>
	    			            <?
	    			            //тут будет название и место проведения выставки
	    			            $APPLICATION->IncludeComponent("rarus:exhibition.header", "",
	    		            		Array(
	    		            				"EXHIB_IBLOCK_ID" => "15",
	    									"CACHE_TIME" => 3600,
	    									"EXHIB_CODE" => $exhibCode
	    		            		),
	    		            		false
	                			);

	    			            ?>


	                            <div  class="exhibition-session">
	    			    		<?$APPLICATION->IncludeComponent("bitrix:menu", "participant_bottom",
	    			    		    Array(
	    			    		        "ROOT_MENU_TYPE" => $menuType,	// Тип меню для первого уровня
	    			    		        "MAX_LEVEL" => "2",	// Уровень вложенности меню
	    			    		        "CHILD_MENU_TYPE" => "",	// Тип меню для остальных уровней
	    			    		        "USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
	    			    		        "DELAY" => "N",	// Откладывать выполнение шаблона меню
	    			    		        "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
	    			    		        "MENU_CACHE_TYPE" => "N",	// Тип кеширования
	    			    		        "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
	    			    		        "MENU_CACHE_USE_GROUPS" => "N",	// Учитывать права доступа
	    			    		        "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
	    			    		    ),
	    			    		    false
	    			    		);
			            	}
			            	else
			            	    {
			            	        $error = "Permissions error";
    			    	        }
			            }
			            else
			            {
			                $error = "Permissions error";
			            }
			    	}
			    	elseif("GUEST" == $userType/* && ("Y" == GUEST_CABINET || $USER->IsAdmin())*/)
			    	{
			    	    //проверка на доступ пользователя к кабинету участника

			    	    CModule::IncludeModule("iblock");
			    	    $rsExhib = CIBlockElement::GetList(array(), array("IBLOCK_ID" => "15", "CODE" => $exhibCode, "ACTIVE" => "Y"), false, false, array("PROPERTY_C_GUESTS_GROUP", "PROPERTY_APP_HB_ID"));
			    	    if($arExhib = $rsExhib->Fetch())
			    	    {
			    	        $exhibGoup = $arExhib["PROPERTY_C_GUESTS_GROUP_VALUE"];

							$filter = Array( "ID" => $userId);
							$rsUser = CUser::GetList(($by="name"), ($order="asc"), $filter, array("SELECT"=>array("UF_MR", "UF_HB"))); //
							// выбираем
							// пользователей
			    	        $arUser = $rsUser->Fetch();
			    	        $classHB = '';

							$menuType = 'guest.bottom';
			    	        if($arUser["UF_HB"] && $arExhib["PROPERTY_APP_HB_ID_VALUE"] != ""){
			    	        	$menuType = 'hb.bottom';
			    	        	$classHB = ' hb';
			    	        }
			    	        else{
			    	        	$menuType = 'guest.bottom';
			    	        }/**/

			    	        if((in_array($exhibGoup, $arUserGroups) && $arUser["UF_MR"]) || $USER->IsAdmin())//если в группе подтвержденных
			    	        {
			    	            //тут будет название и место проведения выставки
			    	            $APPLICATION->IncludeComponent("rarus:exhibition.header", "",
			    	                Array(
			    	                    "EXHIB_IBLOCK_ID" => "15",
			    	                    "CACHE_TIME" => 3600,
			    	                    "EXHIB_CODE" => $exhibCode
			    	                ),
			    	                false
			    	            );
			    	            ?>
        			    	    <div class="exhibition-session<?=$classHB?>">
        			    	    <? $APPLICATION->IncludeComponent("bitrix:menu", "participant_bottom",
        			    	        Array(
        			    	            "ROOT_MENU_TYPE" =>  $menuType,	// Тип меню для первого уровня
        			    	            "MAX_LEVEL" => "2",	// Уровень вложенности меню
        			    	            "CHILD_MENU_TYPE" => "",	// Тип меню для остальных уровней
        			    	            "USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
        			    	            "DELAY" => "N",	// Откладывать выполнение шаблона меню
        			    	            "ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
        			    	            "MENU_CACHE_TYPE" => "N",	// Тип кеширования
        			    	            "MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
        			    	            "MENU_CACHE_USE_GROUPS" => "N",	// Учитывать права доступа
        			    	            "MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
        			    	        ),
        			    	        false
        			    	    );
			    	        }
			    	        else
			    	        {
			            	        $error = "Permissions error";
    			    	    }
			    	    }
			    	    else
			    	        {
			            	  $error = "Permissions error";
    			    	    }
			    	}
			    	else
			    	{
			    	    $error = "Permissions error";
	    	        }

	    	        if($error && !$USER->IsAdmin())
	    	        {
	    	            LocalRedirect("/");// если пользователь пытается залезть в кабинет без доступа попадет на главную
	    	        }
			    }
			    else
			    {?>
			    	<p>Unknown exhibition</p>
			    <?}
			    ?>

			<? endif;?>