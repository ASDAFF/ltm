<?
define(DOKA_MEETINGS_MODULE_DIR, dirname(__FILE__));

CModule::AddAutoloadClasses('doka.meetings', array(
	'Doka\Meetings\Settings' => 'classes/general/Settings.php',
	'\Doka\Meetings\Settings' => 'classes/general/Settings.php',
	'Doka\Meetings\Timeslots' => 'classes/general/Timeslots.php',
	'\Doka\Meetings\Timeslots' => 'classes/general/Timeslots.php',
	'Doka\Meetings\Requests' => 'classes/general/Requests.php',
	'\Doka\Meetings\Requests' => 'classes/general/Requests.php',
	'Doka\Meetings\Wishlists' => 'classes/general/Wishlists.php',
	'\Doka\Meetings\Wishlists' => 'classes/general/Wishlists.php',
));
?>
