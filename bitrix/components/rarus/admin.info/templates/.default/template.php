<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<? if(!empty($arResult["ITEMS"])):?>
    <? if(count($arResult["ITEMS"]) > 1):?>
<!-- ���������� ����� -->
	<div class="tab-content">
		<div class="tab-pane active" id="home">
			<div class="panel-group" id="accordion">
			<? foreach ($arResult["ITEMS"] as $arItem):?>
			<? $href = $arParams["PATH_TO_KAB"] . $arItem["CODE"] . "/";?>
				<div class="panel panel-default">
					<div class="panel-heading"><a data-toggle="collapse" data-parent="#accordion" href="#accord-<?= $arItem["ID"]?>"><?= $arItem["NAME"]?></a></div>
					<div id="accord-<?= $arItem["ID"]?>" class="panel-collapse collapse">
						<div class="panel-body row">
							<div class="col-lg-4 col-md-4 col-sm-4">
								<div class="headline">���������</div>
								<ul class="list">
								    <li><a href="<?= $href?>participant/on/" title="�������������� ����������">�������������� ����������:</a> <strong><?= intval(count($arItem["PARTICIPANT"]["CONFIRMED"]))?></strong>. </li>
									<li><a href="<?= $href?>participant/off/" title="���������������� ����������">���������������� ����������:</a> <strong><?= intval(count($arItem["PARTICIPANT"]["UNCONFIRMED"]))?></strong>. </li>
									<li><a href="<?= $href?>participant/spam/" title="����">����:</a> <strong><?= intval(count($arItem["PARTICIPANT"]["SPAM"]))?></strong>. </li>
									<li><a href="<?= $href?>participant/matrix/" title="�������">�������</a></li>
								</ul>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">
							<div class="headline">�����</div>
								<ul class="list">
									<li><a href="<?= $href?>guest/morning/" title="">�������������� ������ �� ����:</a> <strong><?= intval(count($arItem["GUESTS"]["CONFIRMED"]["MORNING"]))?></strong>.</li>
									<li><a href="<?= $href?>guest/evening/" title="">�������������� ������ �� �����:</a> <strong><?= intval(count($arItem["GUESTS"]["CONFIRMED"]["EVENING"]))?></strong>.</li>
									<li><a href="<?= $href?>guest/hostbuy/" title="">�������������� ������ HB:</a> <strong><?= intval(count($arItem["GUESTS"]["CONFIRMED"]["HOSTED_BUYERS"]))?></strong>.</li>
									<li><a href="<?= $href?>guest/off/" title="">���������������� ������:</a> <strong><?= intval(count($arItem["GUESTS"]["UNCONFIRMED"]))?></strong>.</li>
									<li><a href="<?= $href?>guest/spam/" title="����">����:</a> <strong><?= intval(count($arItem["GUESTS"]["SPAM"]))?></strong>. </li>
									<li><a href="<?= $href?>guest/matrix/" title="�������">�������</a></li>
								</ul>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">
								<div class="headline">���������</div>
								<ul class="list">
									<li><a href="<?= $href?>messages/inbox/" title="">��������:</a> <strong><?= intval($arItem["MESSAGES"]["INBOX"])?></strong>.</li>
									<li><a href="<?= $href?>messages/sent/" title="">�����������:</a> <strong><?= intval($arItem["MESSAGES"]["SENT"])?></strong>.</li>
									<li><a href="<?= $href?>messages/new/" title="">��������</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			<? endforeach;?>
			</div>
		</div>
	</div>
	<? elseif(count($arResult["ITEMS"]) == 1):?>
	    <? $arItem = reset($arResult["ITEMS"]);?>
	    <div class="clearfix tab-content">
	        <div class="panel-heading" style="border-bottom: 1px solid; border-bottom-color: #ddd;"><a href="#"><?= $arItem["NAME"]?></a></div>
    		<div class="col-lg-4 col-md-4 col-sm-4" style="margin-top: 10px;">
			<div class="headline">���������</div>
			<ul class="list">
			    <li><a href="<?= $href?>participant/on/" title="�������������� ����������">�������������� ����������:</a> <strong><?= intval(count($arItem["PARTICIPANT"]["CONFIRMED"]))?></strong>. </li>
				<li><a href="<?= $href?>participant/off/" title="���������������� ����������">���������������� ����������:</a> <strong><?= intval(count($arItem["PARTICIPANT"]["UNCONFIRMED"]))?></strong>. </li>
				<li><a href="<?= $href?>participant/spam/" title="����">����:</a> <strong><?= intval(count($arItem["PARTICIPANT"]["SPAM"]))?></strong>. </li>
				<li><a href="<?= $href?>participant/matrix/" title="�������">�������</a></li>
			</ul>
    		</div>
    		<div class="col-lg-4 col-md-4 col-sm-4" style="margin-top: 10px;">
			<div class="headline">�����</div>
			<ul class="list">
				<li><a href="<?= $href?>guest/morning/" title="">�������������� ������ �� ����:</a> <strong><?= intval(count($arItem["GUESTS"]["CONFIRMED"]["MORNING"]))?></strong>.</li>
				<li><a href="<?= $href?>guest/evening/" title="">�������������� ������ �� �����:</a> <strong><?= intval(count($arItem["GUESTS"]["CONFIRMED"]["EVENING"]))?></strong>.</li>
				<li><a href="<?= $href?>guest/hostbuy/" title="">�������������� ������ HB:</a> <strong><?= intval(count($arItem["GUESTS"]["CONFIRMED"]["HOSTED_BUYERS"]))?></strong>.</li>
				<li><a href="<?= $href?>guest/off/" title="">���������������� ������:</a> <strong><?= intval(count($arItem["GUESTS"]["UNCONFIRMED"]))?></strong>.</li>
				<li><a href="<?= $href?>guest/spam/" title="����">����:</a> <strong><?= intval(count($arItem["GUESTS"]["SPAM"]))?></strong>. </li>
				<li><a href="<?= $href?>guest/matrix/" title="�������">�������</a></li>
			</ul>
    		</div>
    		<div class="col-lg-4 col-md-4 col-sm-4" style="margin-top: 10px;">
			<div class="headline">���������</div>
			<ul class="list">
				<li><a href="<?= $href?>messages/inbox/" title="">��������:</a> <strong><?= intval($arItem["MESSAGES"]["INBOX"])?></strong>.</li>
				<li><a href="<?= $href?>messages/sent/" title="">�����������:</a> <strong><?= intval($arItem["MESSAGES"]["SENT"])?></strong>.</li>
				<li><a href="<?= $href?>messages/new/" title="">��������</a></li>
			</ul>
		</div>
	<? endif;?>
<? endif;?>