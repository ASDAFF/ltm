<?
if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<form action="" method="POST">
    <input type="hidden" name="time" value="<?= $arResult['TIMESLOT']['ID'] ?>"/>
    <input type="hidden" name="id" value="<?= $arResult['SENDER']['ID'] ?>"/>
    <input type="hidden" name="to" value="<?= $arResult['RECEIVER']['ID'] ?>"/>
    <input type="hidden" name="app" value="<?= $arResult['APP_ID'] ?>"/>
    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="form_edit">
        <tr>
            <td width="130">От:</td>
            <td>
                <?= $arResult['SENDER']['COMPANY'] ?><br/>
                <?= $arResult['SENDER']['NAME'] ?>
            </td>
        </tr>
        <tr>
            <td>Кому:</td>
            <td>
                <?= $arResult['RECEIVER']['COMPANY'] ?><br/>
                <?= $arResult['RECEIVER']['NAME'] ?>
            </td>
        </tr>
        <tr>
            <td>Время:</td>
            <td><?= $arResult['TIMESLOT']['NAME'] ?></td>
        </tr>
    </table>
    <p><input type="submit" name="submit"/></p>
</form>
