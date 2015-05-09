<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
foreach($arResult["SECTIONS"] as $arSection):
?>
    <h1><?=$arSection["NAME"]?></h1>
    <?
        if($arSection["ELEMENT_CNT"] == 0){
            echo "No sponsors.";
        }
        else{
            foreach($arSection["ITEMS"] as $arItem){
            ?>
                <div class="sponsor_box">
                <a href="<?=$arItem[PROPERTY_SITE_VALUE]?>" target="_blank"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" style="float:left; margin: 0 10px 10px 0;" /></a>
                <div class="sponsor_text"><?=$arItem["PREVIEW_TEXT"]?></div>
                </div>
            <?
            }
        }
        ?>
<?endforeach?>