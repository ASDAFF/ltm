<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? IncludeTemplateLangFile(__FILE__);?>
<!DOCTYPE html>
<html class="<?= LANGUAGE_ID?>">
<head>

	<title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/js/bootstrap/bootstrap.css')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery-1.10.2.min.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/bootstrap/bootstrap.min.js')?>
	<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/script.js')?>
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<?$APPLICATION->ShowHead()?>
</head>
<body>
	<div class="clearfix">
		<?/*<div id="panel" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?$APPLICATION->ShowPanel();?></div>*/ ?>
	</div>
	<? if(!$USER->IsAdmin()){LocalRedirect("/");}?>
	<?//������� ��������� ��� ��������� ������� ?>
	<div class="admin-container clearfix">

		<?//������� ����� ��������� ?>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<? if(strpos($APPLICATION->GetCurDir(), "service")  === false):?>
			<?$APPLICATION->IncludeComponent("bitrix:menu", "admin", Array(
				"ROOT_MENU_TYPE" => "admin.top",	// ��� ���� ��� ������� ������
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
		<? endif;?>
		<?//������� ������ ���������?>
		<div class="row content">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<script>

				$(document).ready(function(){

    				$(".action img").click(function(e) {
    					var $popup = $(this).next();
    					var id = $(this).parent().attr("id");

        				if ($popup.css('display') != 'block') {
        					$popup.show();


    				        var firstClick = true;
    				        $(document).bind('click.myEvent_'+ id, function(e) {
    				           if (!firstClick && $(e.target).closest('ul.ul-popup').length == 0) {
    				            	$popup.hide();
    				                $(document).unbind('click.myEvent_' + id);
    				            }
    				            firstClick = false;
    				        });

        				    }
        				else
        				{
        					$popup.hide();
			                $(document).unbind('click.myEvent_' + id);
            			}
        				    e.preventDefault();
        				});
				});
			</script>