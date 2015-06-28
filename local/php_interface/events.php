<?php
AddEventHandler("main", "OnAfterUserLogout", array("CHandlers","OnAfterUserLogoutHandler"));
AddEventHandler("main", "OnBeforeEventAdd", array("CHandlers","OnBeforeEventAddHandler"));
AddEventHandler('form', 'onBeforeResultAdd', array("CHandlers","onBeforeResultAddHandlers"));//??? ?????
?>