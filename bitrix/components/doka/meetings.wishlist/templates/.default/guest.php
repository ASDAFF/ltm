<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div>����� �� ������ ��������� ������ ��� ����������, ��� ���������� ���������</div>
<table class="section-request">
    <tr>
        <td class="appointments">
            <div class="wish-list">�� ������ �� ������ ��������� ��������</div>
                <table class="morning-time">
                <tr>
			<th>N</th>
			<th>��������</th>
                </tr>
				<? $counter=0;
				foreach ($arResult['WISH_IN'] as $item):?>
                <? $counter++;?>
                    <tr>
                        <td><?=$counter?></td>
                        <td> <?=$item['company_name']?></td>
                    </tr>
                <?endforeach;?>
                </table>
            <form action="">
                <div class="send-request">
                 <a href="/cabinet/service/wish.php?id=<?=$arResult['USER_ID']?>&to=0&app=<?=$arResult['APP_ID']?>"
                    target="_blank"
                    onclick="newWish('<?=$arResult['USER_ID']?>','<?=$arResult['APP_ID']?>','/cabinet/service/wish.php'); return false;">��������� ������</a>
               	</div>
                <select name="wishlistComp" id="wishlistComp">
                    <option value="0">�������� ��������</option>
 				<? foreach ($arResult['COMPANIES'] as $item):?>
                    <option value="<?=$item['company_id']?>"><?=$item['company_name']?></option>
                <?endforeach;?>
               </select>
            </form>
        </td>
        <td>
            <div class="wish-list">� ���� ����� ������ �� ����������� ��������� ���������</div>
            <table class="morning-time">
                <tr>
			<th>N</th>
			<th>��������</th>
                </tr>
				<? $counter=0;
				foreach ($arResult['WISH_OUT'] as $item):?>
                <? $counter++;?>
                    <tr>
                        <td><?=$counter?></td>
                        <td> <?=$item['company_name']?></td>
                    </tr>
                <?endforeach;?>
            </table>
        </td>
    </tr>
</table>