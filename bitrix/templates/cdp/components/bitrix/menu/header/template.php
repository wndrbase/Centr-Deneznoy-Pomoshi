<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>

<ul class="header__nav-list">

<?
foreach($arResult as $arItem):
	if( ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) || $arItem['PARAMS']['HIDDEN']) 
		continue;
?>
	<?if($arItem["SELECTED"]):?>
		<li class="header__nav-item header__nav-item--active"><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
	<?else:?>
		<li class="header__nav-item"><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
	<?endif?>
	
<?endforeach?>

</ul>
<?endif?>