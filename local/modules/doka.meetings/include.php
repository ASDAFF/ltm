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
	'Spectr\Meeting\Models\TimeslotTable' => 'lib/Models/TimeslotTable.php',
	'Spectr\Meeting\Models\SettingsTable' => 'lib/Models/SettingsTable.php',
	'Spectr\Meeting\Models\RequestTable' => 'lib/Models/RequestTable.php',
	'Spectr\Meeting\Models\WishlistTable' => 'lib/Models/WishlistTable.php',
    'Spectr\Meeting\Models\GuestStorageColleagueTable' => 'lib/Models/GuestStorageColleagueTable.php',
    'Spectr\Meeting\Models\GuestStorageTable' => 'lib/Models/GuestStorageTable.php',
    'Spectr\Meeting\Models\RegistrGuestColleagueTable' => 'lib/Models/RegistrGuestColleagueTable.php',
    'Spectr\Meeting\Models\RegistrGuestTable' => 'lib/Models/RegistrGuestTable.php',
));
?>
