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
	<?$APPLICATION->ShowHead()?>
</head>
<body>
	<? if ($USER->isAdmin())$APPLICATION->ShowPanel();?>
	<header>
		<div class="layoutCenterWrapper">
			<a href = "/" id = "logo" alt = "Luxury Travel Mart" /></a>
			<span id = "title">Luxury Travel Mart</span>
			<div id = "lang">
			    <a class = "en<?= (LANGUAGE_ID == "en")?" act":"";?>" href="<?= $APPLICATION->GetCurPageParam("lang_ui=en",array("lang_ui", "EXHIBIT_CODE", "CODE"));?>"><?= (LANGUAGE_ID == "en")?">":"";?>English</a>
                <a class = "ru<?= (LANGUAGE_ID == "ru")?" act":"";?>" href="<?= $APPLICATION->GetCurPageParam("lang_ui=ru",array("lang_ui", "EXHIBIT_CODE", "CODE"));?>"><?= (LANGUAGE_ID == "ru")?">":"";?>�������</a>
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
			        $userType = $_SESSION["USER_TYPE"]; //��������� � ���������� ����������� rarus:auth.form

			        $userId;
			        if($USER->IsAdmin() && isset($_REQUEST["UID"])) {
			            $userId = intval($_REQUEST["UID"]);
			        } else {
			            $userId = $USER->GetID();
			        }

			        $bUserTypeIsset = false;
			        $arUserGroups = CUser::GetUserGroup($userId);

    			    if(!$userType)//���� �� ���������� �������� �� ������ �������� ����� �������, ������ ���� �� ������ ��������
                    {

                    	//������� �� ���������
                    	if(!$bUserTypeIsset)
                    	{
                    	    $arPartGroupID = array("10","9", "12", "11", "14","13", "16", "15", "18", "17", "21", "20");
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

                    	//������� �� �����
                    	if(!$bUserTypeIsset)
                    	{
                    	    $arGuestGroupID = array("22", "25", "23", "26", "24", "27", "19");
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
                    }

			        if("PARTICIPANT" == $userType /* && ("Y" == PARTICIPANT_CABINET || $USER->IsAdmin())*/)
			        {
			            //�������� �� ������ ������������ � �������� ���������

			            CModule::IncludeModule("iblock");
			            $rsExhib = CIBlockElement::GetList(array(), array("IBLOCK_ID" => "15", "CODE" => $exhibCode, "ACTIVE" => "Y"), false, false, array("PROPERTY_USER_GROUP_ID"));
			            if($arExhib = $rsExhib->Fetch())
			            {

			            	$exhibGoup = $arExhib["PROPERTY_USER_GROUP_ID_VALUE"];

			            	if(in_array($exhibGoup, $arUserGroups) || $USER->IsAdmin())//���� � ������ ��������������
			            	{

			            	    $APPLICATION->IncludeComponent("bitrix:menu", "participant_top",
			            	        Array(
			            	            "ROOT_MENU_TYPE" => "participant.top",	// ��� ���� ��� ������� ������
			            	            "MAX_LEVEL" => "2",	// ������� ����������� ����
			            	            "CHILD_MENU_TYPE" => "",	// ��� ���� ��� ��������� �������
			            	            "USE_EXT" => "Y",	// ���������� ����� � ������� ���� .���_����.menu_ext.php
			            	            "DELAY" => "N",	// ����������� ���������� ������� ����
			            	            "ALLOW_MULTI_SELECT" => "N",	// ��������� ��������� �������� ������� ������������
			            	            "MENU_CACHE_TYPE" => "N",	// ��� �����������
			            	            "MENU_CACHE_TIME" => "3600",	// ����� ����������� (���.)
			            	            "MENU_CACHE_USE_GROUPS" => "N",	// ��������� ����� �������
			            	            "MENU_CACHE_GET_VARS" => "",	// �������� ���������� �������
			            	        ),
			            	        false
	    			            );?>
	    			            <?
	    			            //��� ����� �������� � ����� ���������� ��������
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
	    			    		        "ROOT_MENU_TYPE" => "participant.bottom",	// ��� ���� ��� ������� ������
	    			    		        "MAX_LEVEL" => "2",	// ������� ����������� ����
	    			    		        "CHILD_MENU_TYPE" => "",	// ��� ���� ��� ��������� �������
	    			    		        "USE_EXT" => "Y",	// ���������� ����� � ������� ���� .���_����.menu_ext.php
	    			    		        "DELAY" => "N",	// ����������� ���������� ������� ����
	    			    		        "ALLOW_MULTI_SELECT" => "N",	// ��������� ��������� �������� ������� ������������
	    			    		        "MENU_CACHE_TYPE" => "N",	// ��� �����������
	    			    		        "MENU_CACHE_TIME" => "3600",	// ����� ����������� (���.)
	    			    		        "MENU_CACHE_USE_GROUPS" => "N",	// ��������� ����� �������
	    			    		        "MENU_CACHE_GET_VARS" => "",	// �������� ���������� �������
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
			    	    //�������� �� ������ ������������ � �������� ���������

			    	    CModule::IncludeModule("iblock");
			    	    $rsExhib = CIBlockElement::GetList(array(), array("IBLOCK_ID" => "15", "CODE" => $exhibCode, "ACTIVE" => "Y"), false, false, array("PROPERTY_C_GUESTS_GROUP"));
			    	    if($arExhib = $rsExhib->Fetch())
			    	    {

			    	        $exhibGoup = $arExhib["PROPERTY_C_GUESTS_GROUP_VALUE"];

			    	        $rsUser = CUser::GetByID($userId);
			    	        $arUser = $rsUser->Fetch();
			    	        $classHB = '';
			    	        if($arUser[UF_HB] && $exhibCode == 'moscow-russia-march-12-2015'){
			    	        	$menuType = 'hb.bottom';
			    	        	$classHB = ' hb';
			    	        }
			    	        else{
			    	        	$menuType = 'guest.bottom';
			    	        }

			    	        if(in_array($exhibGoup, $arUserGroups) || $USER->IsAdmin())//���� � ������ ��������������
			    	        {
			    	            //��� ����� �������� � ����� ���������� ��������
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
        			    	            "ROOT_MENU_TYPE" =>  $menuType,	// ��� ���� ��� ������� ������
        			    	            "MAX_LEVEL" => "2",	// ������� ����������� ����
        			    	            "CHILD_MENU_TYPE" => "",	// ��� ���� ��� ��������� �������
        			    	            "USE_EXT" => "Y",	// ���������� ����� � ������� ���� .���_����.menu_ext.php
        			    	            "DELAY" => "N",	// ����������� ���������� ������� ����
        			    	            "ALLOW_MULTI_SELECT" => "N",	// ��������� ��������� �������� ������� ������������
        			    	            "MENU_CACHE_TYPE" => "N",	// ��� �����������
        			    	            "MENU_CACHE_TIME" => "3600",	// ����� ����������� (���.)
        			    	            "MENU_CACHE_USE_GROUPS" => "N",	// ��������� ����� �������
        			    	            "MENU_CACHE_GET_VARS" => "",	// �������� ���������� �������
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
	    	            LocalRedirect("/");// ���� ������������ �������� ������� � ������� ��� ������� ������� �� �������
	    	        }
			    }
			    else
			    {?>
			    	<p>Unknown exhibition</p>
			    <?}
			    ?>

			<? endif;?>