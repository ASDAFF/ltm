<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<? if(!empty($arResult["ITEMS"])):?>
    <? if(count($arResult["ITEMS"]) > 1):?>
<!-- Сожердимое табов -->
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
								<div class="headline">Участники</div>
								<ul class="list">
								    <li><a href="<?= $href?>participant/on/" title="Подтвержденных участников">Подтвержденных участников:</a> <strong><?= intval(count($arItem["PARTICIPANT"]["CONFIRMED"]))?></strong>. </li>
									<li><a href="<?= $href?>participant/off/" title="Неподтвержденных участников">Неподтвержденных участников:</a> <strong><?= intval(count($arItem["PARTICIPANT"]["UNCONFIRMED"]))?></strong>. </li>
									<li><a href="<?= $href?>participant/spam/" title="Спам">Спам:</a> <strong><?= intval(count($arItem["PARTICIPANT"]["SPAM"]))?></strong>. </li>
									<li><a href="<?= $href?>participant/matrix/" title="Матрица">Матрица</a></li>
								</ul>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">
							<div class="headline">Гости</div>
								<ul class="list">
									<li><a href="<?= $href?>guest/morning/" title="">Подтвержденных гостей на УТРО:</a> <strong><?= intval(count($arItem["GUESTS"]["CONFIRMED"]["MORNING"]))?></strong>.</li>
									<li><a href="<?= $href?>guest/evening/" title="">Подтвержденных гостей на ВЕЧЕР:</a> <strong><?= intval(count($arItem["GUESTS"]["CONFIRMED"]["EVENING"]))?></strong>.</li>
									<li><a href="<?= $href?>guest/hostbuy/" title="">Подтвержденных гостей HB:</a> <strong><?= intval(count($arItem["GUESTS"]["CONFIRMED"]["HOSTED_BUYERS"]))?></strong>.</li>
									<li><a href="<?= $href?>guest/off/" title="">Неподтвержденных гостей:</a> <strong><?= intval(count($arItem["GUESTS"]["UNCONFIRMED"]))?></strong>.</li>
									<li><a href="<?= $href?>guest/spam/" title="Спам">Спам:</a> <strong><?= intval(count($arItem["GUESTS"]["SPAM"]))?></strong>. </li>
									<li><a href="<?= $href?>guest/matrix/" title="Матрица">Матрица</a></li>
								</ul>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">
								<div class="headline">Сообщения</div>
								<ul class="list">
									<li><a href="<?= $href?>messages/inbox/" title="">Входящие:</a> <strong><?= intval($arItem["MESSAGES"]["INBOX"])?></strong>.</li>
									<li><a href="<?= $href?>messages/sent/" title="">Отправленые:</a> <strong><?= intval($arItem["MESSAGES"]["SENT"])?></strong>.</li>
									<li><a href="<?= $href?>messages/new/" title="">Написать</a></li>
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
			<div class="headline">Участники</div>
			<ul class="list">
			    <li><a href="<?= $href?>participant/on/" title="Подтвержденных участников">Подтвержденных участников:</a> <strong><?= intval(count($arItem["PARTICIPANT"]["CONFIRMED"]))?></strong>. </li>
				<li><a href="<?= $href?>participant/off/" title="Неподтвержденных участников">Неподтвержденных участников:</a> <strong><?= intval(count($arItem["PARTICIPANT"]["UNCONFIRMED"]))?></strong>. </li>
				<li><a href="<?= $href?>participant/spam/" title="Спам">Спам:</a> <strong><?= intval(count($arItem["PARTICIPANT"]["SPAM"]))?></strong>. </li>
				<li><a href="<?= $href?>participant/matrix/" title="Матрица">Матрица</a></li>
			</ul>
    		</div>
    		<div class="col-lg-4 col-md-4 col-sm-4" style="margin-top: 10px;">
			<div class="headline">Гости</div>
			<ul class="list">
				<li><a href="<?= $href?>guest/morning/" title="">Подтвержденных гостей на УТРО:</a> <strong><?= intval(count($arItem["GUESTS"]["CONFIRMED"]["MORNING"]))?></strong>.</li>
				<li><a href="<?= $href?>guest/evening/" title="">Подтвержденных гостей на ВЕЧЕР:</a> <strong><?= intval(count($arItem["GUESTS"]["CONFIRMED"]["EVENING"]))?></strong>.</li>
				<li><a href="<?= $href?>guest/hostbuy/" title="">Подтвержденных гостей HB:</a> <strong><?= intval(count($arItem["GUESTS"]["CONFIRMED"]["HOSTED_BUYERS"]))?></strong>.</li>
				<li><a href="<?= $href?>guest/off/" title="">Неподтвержденных гостей:</a> <strong><?= intval(count($arItem["GUESTS"]["UNCONFIRMED"]))?></strong>.</li>
				<li><a href="<?= $href?>guest/spam/" title="Спам">Спам:</a> <strong><?= intval(count($arItem["GUESTS"]["SPAM"]))?></strong>. </li>
				<li><a href="<?= $href?>guest/matrix/" title="Матрица">Матрица</a></li>
			</ul>
    		</div>
    		<div class="col-lg-4 col-md-4 col-sm-4" style="margin-top: 10px;">
			<div class="headline">Сообщения</div>
			<ul class="list">
				<li><a href="<?= $href?>messages/inbox/" title="">Входящие:</a> <strong><?= intval($arItem["MESSAGES"]["INBOX"])?></strong>.</li>
				<li><a href="<?= $href?>messages/sent/" title="">Отправленые:</a> <strong><?= intval($arItem["MESSAGES"]["SENT"])?></strong>.</li>
				<li><a href="<?= $href?>messages/new/" title="">Написать</a></li>
			</ul>
		</div>
	<? endif;?>
<? endif;?>