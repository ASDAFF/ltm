<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? if(!isset($arResult["ERROR"])):?>
<?php //--> ���������?>
	<h2><?= $arResult["NAME"]?></h2>
	<div class="sub-headline"><?= $arResult["DATE"]?>, <?= $arResult["VENUE"]?></div>
<?php //<-- ���������?>

	<?php //--> ����� ���� ��� ����� 1 ������ ?>
	<div id="exhibition-session" class="exhibition-session">
	
		<?//--> ���� 1 ������ ?>
			<ul class="pull-overflow main-tab">
				<li><a href="#exhibition-tab-1" title=""><?= GetMessage("PROFILE_DEADLINES")?></a></li>
				<li><a href="#exhibition-tab-2" title=""><?= GetMessage("PROFILE_M_SESSION")?></a></li>
				<li><a href="#exhibition-tab-3" title=""><?= GetMessage("PROFILE_HB_SESSION")?></a></li>
				<li><a href="#exhibition-tab-4" title=""><?= GetMessage("PROFILE_E_SESSION")?></a></li>
				<li><a href="#exhibition-tab-5" title=""><?= GetMessage("PROFILE_MESSAGES")?></a></li>
			</ul>
		<?//<-- ���� 1 ������ ?>
		
		<?//--> ���������� ���� 1 ?>
		<div id="exhibition-tab-1">
			<? include("tabs/deadline/deadline.php")?>
		</div>
		<?//<-- ���������� ���� 1 ?>
		
		<?//--> ���������� ���� 2?>
		<div id="exhibition-tab-2">
			<?// ���� 2 ������ ?>
			
			<div class="morning-session" id="morning-session">
				<?//--> ������ ����� 2 ������ ?>
				<ul class="pull-overflow sub-tab-session">
					<li><a href="#session-tab-1" title=""><?= GetMessage("PROFILE_SCHEDULE_TAB")?></a></li>
					<li><a href="#session-tab-2" title=""><?= GetMessage("PROFILE_M_GUESTS_TAB")?></a></li>
				</ul>
				<?//<--������ ����� 2 ������ ?>
				
				<?//-->��������� ����������� ���� 2 ������ ?>
				<div id="session-tab-1">
				<? include("tabs/morning_session/my_schedule.php")?>
				</div>
				
				<?//-->���������� ���� 2 ������ 2 ������� ?>
				<div id="session-tab-2">
				<? include("tabs/morning_session/morning_session_guests.php")?>
				</div>
			</div>
		</div>
		<?php //<--���������� ���� 2 ?>
		
		<?php //-->C��������� ���� 3 ?>
			<div id="exhibition-tab-3">
				<? include("tabs/hb_session/hb_session.php")?>
			</div>
		<?php //<--���������� ���� 3 ?>
		<?php //-->C��������� ���� 4 ?>
			<div id="exhibition-tab-4" class="evening-session">
				<? include("tabs/evening_session/")?>
			</div>
		<?php //<--���������� ���� 4 ?>
		
		<?php //-->C��������� ���� 5 ?>
			<div id="exhibition-tab-5" class="message-box">
				<div id="message-box-function">
					<ul class="message-list-tab pull-overflow">
						<li><a href="#message-tab-1" title="Inbox"><?= GetMessage("PROFILE_MESS_INBOX")?></a></li>
						<li><a href="#message-tab-2" title="Sent"><?= GetMessage("PROFILE_MESS_SENT")?></a></li>
						<li><a href="#message-tab-3" title="New message"><?= GetMessage("PROFILE_MESS_NEW_MESSAGE")?></a></li>
						<li><a href="#message-tab-4" title="Contact Organizers"><?= GetMessage("PROFILE_MESS_CONTACT_ORGANIZERS")?></a></li>
					</ul>
					
					<div id="message-tab-1" class="inbox morning-session">
						<? include("tabs/messages/inbox.php")?>
					</div>
					
					<div id="message-tab-2" class="sendbox morning-session">
						<? include("tabs/messages/inbox.php")?>
					</div>
					
					<div id="message-tab-3" class="new-message">
						<? include("tabs/messages/new.php")?>
					</div>
					<div id="message-tab-4" class="new-message">
						<? include("tabs/messages/organizers.php")?>
					</div>
				</div>
			</div>
		<?php //<--���������� ���� 5 ?>
	</div>
<script>
	$(function() {
	$( "#exhibition-session,#morning-session" ).tabs();
	});
</script>
<? else:?>
	<p>You are not allowed to access this section</p>
<? endif;?>
