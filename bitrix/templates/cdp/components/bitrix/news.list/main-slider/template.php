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
	<div class="slide clr">
		<ul class="slide__list bxslider">
			<?foreach($arResult["ITEMS"] as $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<li class="slide__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">							
					<div class="slide__anounce">
						<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
							<div class="slide__title"><?=$arItem["NAME"]?></div>
						<?endif;?>
						<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
							<div class="slide__text"><?=$arItem["PREVIEW_TEXT"];?></div>
						<?endif;?>
						<?if($arItem['DISPLAY_PROPERTIES']['CDP_S_LINK']['VALUE']):?>
							<a href="<?=$arItem['DISPLAY_PROPERTIES']['CDP_S_LINK']['VALUE']?>" class="btn slide__more-btn"><?=Loc::getMessage("CDP_S_MORE")?></a>
						<?endif;?>
					</div>
					<picture>
						<?if($arItem['DISPLAY_PROPERTIES']['CDP_S_IMAGE_DESKTOP']['VALUE']):?>
							<source media="(min-width: 960px)" srcset="<?=$arItem['DISPLAY_PROPERTIES']['CDP_S_IMAGE_DESKTOP']['FILE_VALUE']['SRC']?>">
						<?endif;?>
						<?if($arItem['DISPLAY_PROPERTIES']['CDP_S_IMAGE_MOBILE']['VALUE']):?>
							<img src="<?=$arItem['DISPLAY_PROPERTIES']['CDP_S_IMAGE_MOBILE']['FILE_VALUE']['SRC']?>" alt="">
						<?endif;?>
					</picture>
				</li>
			<?endforeach;?>
		</ul>
	</div>
<?endif;?>
