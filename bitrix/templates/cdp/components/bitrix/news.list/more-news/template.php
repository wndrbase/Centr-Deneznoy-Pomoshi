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

	<div class="center clr">

		<div class="news news--more">
			<div class="h1"><?=Loc::getMessage("CDP_N_MORE_NEWS")?></div>
			<ul class="news__list clr">
				<?foreach($arResult["ITEMS"] as $arItem):?>
					<?
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>
					<li class="news__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="news__item-img">
						<?else:?>
							<a href="javascript:;" class="news__item-img">
						<?endif;?>
						<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
							<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>">
						<?else:?>
							<img src="<?=SITE_TEMPLATE_PATH?>/img/news_blank.jpg" alt="<?=htmlspecialcharsbx($arItem["NAME"])?>" title="<?=htmlspecialcharsbx($arItem["NAME"])?>">
						<?endif;?>
							</a>
						<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
							<time class="news__item-date" datetime="<?=date("Y-m-d", strtotime($arItem["ACTIVE_FROM"]))?>"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></time>
						<?endif?>
						<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="news__item-title">
						<?else:?>
							<a href="javascript:;" class="news__item-title">
						<?endif;?>
								<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
									<span class="news__item-caption"><?=$arItem["NAME"]?></span>
								<?endif;?>
								<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
									<span class="news__item-anounce"><?=$arItem["PREVIEW_TEXT"];?></span>
								<?endif;?>
							</a>
					</li>
				<?endforeach;?>
			</ul>
			<a href="<?=$arResult["ITEMS"][0]["LIST_PAGE_URL"]?>" class="btn btn--red news__btn-more news__btn-more--all"><?=Loc::getMessage("CDP_N_ALL")?></a>
		</div>

	</div>

<?endif;?>