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

$obParser = new CTextParser;
?>

<?if($arResult["ITEMS"]):?>

	<div class="reviews">
		<ul class="reviews__list clr">
			<!--RestartBuffer-->
			<?foreach($arResult["ITEMS"] as $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<li class="reviews__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<div class="reviews__item-box">
						<div class="reviews__item-photo">
							<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
								<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC_RESIZE"]?>" alt="<?=htmlspecialcharsbx($arItem['NAME'])?>" title="<?=htmlspecialcharsbx($arItem['NAME'])?>">
							<?else:?>
								<img src="<?=SITE_TEMPLATE_PATH?>/img/photo_blank.jpg" alt="<?=htmlspecialcharsbx($arItem['NAME'])?>" title="<?=htmlspecialcharsbx($arItem['NAME'])?>">
							<?endif;?>
						</div>
						<div class="reviews__item-person">
							<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
								<div class="reviews__item-name"><?=$arItem["NAME"]?></div>
							<?endif;?>
							<?if($arItem["DISPLAY_PROPERTIES"]["CDP_R_POST"]["VALUE"]):?>
								<div class="reviews__item-post"><?=$arItem["DISPLAY_PROPERTIES"]["CDP_R_POST"]["VALUE"]?></div>
							<?endif;?>
						</div>
						<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
							<time class="reviews__item-date" datetime="<?=date('Y-m-d', strtotime($arItem["ACTIVE_FROM"]))?>"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></time>
						<?endif?>
						<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
							<?if(strlen(strip_tags($arItem["PREVIEW_TEXT"])) > 110):?>
								<div class="reviews__item-text">
									<div class="reviews__item-text--short"><?=$obParser->html_cut($arItem["PREVIEW_TEXT"], 110);?></div>
									<div class="reviews__item-text--full"><?=$arItem["PREVIEW_TEXT"]?></div>
									<a href="javascript:;" class="reviews__item-read" data-link-text-short="<?=Loc::getMessage('CDP_R_READ_SHORT')?>" data-link-text-full="<?=Loc::getMessage('CDP_R_READ_FULL')?>"><?=Loc::getMessage("CDP_R_READ_FULL")?></a>
								</div>
							<?else:?>
								<div class="reviews__item-text"><?=$arItem["PREVIEW_TEXT"]?></div>
							<?endif;?>
						<?endif;?>
					</div>
				</li>
			<?endforeach;?>
			<!--RestartBuffer-->
		</ul>
		<?if($arResult['ALL_NEWS_COUNT'] > $arParams['NEWS_COUNT']):?>
			<a href="javascript:void(0)" class="btn reviews__btn-more" id="ajax-load-reviews" data-all-news-count="<?=$arResult['ALL_NEWS_COUNT']?>" data-news-per-page="<?=$arParams['NEWS_COUNT']?>" data-current-page="1" data-page-url="<?=$APPLICATION->GetCurDir()?>"><?=Loc::getMessage("CDP_R_DOWNLOAD_MORE")?></a>
		<?endif;?>
	</div>

<?endif;?>