<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__FILE__);

if ($arResult['RESULT']->isSuccess()) {
    echo Loc::getMessage('WISHLIST_ADD_SUCCESS');
} else {
    echo Loc::getMessage('WISHLIST_ADD_ERROR');
}
?>
<script type="text/javascript">
    setTimeout(function () {
        window.close()
    },5000)
</script>
<script type='text/javascript'>top.opener.document.location.reload()</script>

