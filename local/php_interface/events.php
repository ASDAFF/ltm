<?php
AddEventHandler("main", "OnAfterUserLogout", array("CHandlers","OnAfterUserLogoutHandler"));
AddEventHandler("main", "OnBeforeEventAdd", array("CHandlers","OnBeforeEventAddHandler"));
AddEventHandler('form', 'onBeforeResultAdd', array("CHandlers","onBeforeResultAddHandlers"));//??? ?????
AddEventHandler('form', 'onAfterResultUpdate', array("CHandlers","OnProfilePhotoResize"));
AddEventHandler('form', 'onAfterResultAdd', array("CHandlers","OnProfilePhotoResize"));
AddEventHandler('main', 'OnBuildGlobalMenu', array("CHandlers","OnBuildGlobalMenuHandler"));
?>