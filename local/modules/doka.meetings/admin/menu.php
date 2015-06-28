<?php
IncludeModuleLangFile(__FILE__);

$MODULE_ID = 'doka.meetings';

if ($APPLICATION->GetGroupRight($MODULE_ID) > 'D') {
    $aMenu = array(
		'parent_menu' => 'global_menu_services',
		'section' => 'doka',
		'sort' => 1000,
		// 'url' => $MODULE_ID . '_index.php?lang=' . LANGUAGE_ID,
		'text' => GetMessage('DOKA_MENU_MAIN'),
		'title' => GetMessage('DOKA_MENU_MAIN_TITLE'),
		'icon' => 'doka_menu_icon',
		'page_icon' => 'doka_page_icon',
		'module_id' => $MODULE_ID,
		'items_id' => $MODULE_ID . '_menu',
		"items" => array(
			array(
				"text" => GetMessage("doka_requests_text"),
				"url" => $MODULE_ID . "_requests.php?lang=".LANGUAGE_ID,
				"more_url" => array( $MODULE_ID . "_requests_edit.php"),
				"title" => GetMessage("doka_requests_text")
			),
			array(
				"text" => GetMessage("doka_settings_text"),
				"url" => $MODULE_ID . "_settings.php?lang=".LANGUAGE_ID,
				"more_url" => array( $MODULE_ID . "_settings_edit.php"),
				"title" => GetMessage("doka_settings_text")
			),
			array(
				"text" => GetMessage("doka_settings_timeslots_text"),
				"url" => $MODULE_ID . "_settings_timeslots.php?lang=".LANGUAGE_ID,
				"more_url" => array( $MODULE_ID . "_settings_timeslots_edit.php"),
				"title" => GetMessage("doka_settings_timeslots_text")
			),
		)
	);
	return $aMenu;
} else {
    return false;
}
