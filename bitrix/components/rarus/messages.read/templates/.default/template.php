<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

if (!empty($arResult["ERROR_MESSAGE"])):
?>
<p class="error"><?=ShowError($arResult["ERROR_MESSAGE"], "forum-note-error");?></p>
<?
endif;
if (!empty($arResult["OK_MESSAGE"])):
?>
<p class="error"><?=ShowNote($arResult["OK_MESSAGE"], "forum-note-success")?></p>
<?
endif;
?>
	<div class="reed-letter pull-overflow">
		<form action="">
			<div class="head-letter pull-overflow">
				<div class="pull-left contact-info">
					<div><strong><?=GetMessage("HLM_FROM")?>:</strong> <?=$arResult["MESSAGE"]["AUTHOR"]["NAME"]?></div>
					<div><strong><?=GetMessage("HLM_TO")?>:</strong> <?=$arResult["MESSAGE"]["RECIPIENT"]["NAME"]?></div>
				</div>
				<div class="pull-right">
					<div class="date">
					<?=date($arParams["DATE_FORMAT"],$arResult["MESSAGE"]["POST_DATE"])?>
					<br />
					<?=date($arParams["DATE_TIME_FORMAT"],$arResult["MESSAGE"]["POST_DATE"])?>
					</div>
				</div>
				<div class="pull-overflow theme"><strong><?=GetMessage("HLM_SUBJECT")?>:</strong> <b><?=$arResult["MESSAGE"]["SUBJECT"];?></b></div>
			</div>
			<div class="message-text">
				<?=$arResult["MESSAGE"]["MESSAGE"]?>
			</div>
			<div class="send">
				<a title="<?=GetMessage('HLM_ACT_REPLY').' '.$arResult["MESSAGE"]["RECIPIENT_NAME"]?>" href="<?=$arResult["MESSAGE"]["URL_HLM_NEW"]?>&mes=<?=$arResult["MESSAGE"]["ID"]?>"><?=GetMessage('HLM_ACT_REPLY')?></a>
			</div>
		</form>
		</div>