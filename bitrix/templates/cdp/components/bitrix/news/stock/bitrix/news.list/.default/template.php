<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
?>

<?if($arResult["ITEMS"]):?>
	<ul class="stock-list clr">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<li class="stock-list__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<div class="stock-list__box">
					<?if($arItem["DATE_ACTIVE_TO"]):?>
						<div class="stock-list__time"><?=Loc::getMessage("CDP_S_DATE_END")?> <?=strtolower(FormatDate("j F Y", MakeTimeStamp($arItem["DATE_ACTIVE_TO"])))?></div>
					<?endif?>
					<?if($arItem['DISPLAY_PROPERTIES']['CDP_S_NAME_HTML']['VALUE']['TEXT']):?>
						<div class="stock-list__title"><?=$arItem['DISPLAY_PROPERTIES']['CDP_S_NAME_HTML']['~VALUE']['TEXT']?></div>
					<?elseif($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
						<div class="stock-list__title"><?=$arItem["NAME"]?></div>
					<?endif;?>
					<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="btn stock-list__btn-more">Смотреть</a>
					<?endif;?>
				</div>
				<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
					<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>">
				<?endif?>
			</li>
		<?endforeach;?>
	</ul>
	<?/*if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?>
	<?endif;*/?>
<?endif;?>
