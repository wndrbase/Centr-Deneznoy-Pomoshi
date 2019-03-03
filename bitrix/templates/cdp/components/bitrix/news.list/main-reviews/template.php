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

		<div class="reviews reviews--home">
			<div class="overtitle"><?=Loc::getMessage("CDP_R_ABOUT_SAYS")?></div>
			<div class="h1"><?=Loc::getMessage("CDP_R_TITLE")?></div>
            <? if ($arResult['TOTAL'] > 0): ?>
                <div class="reviews__all">
                    <a href="/reviews/">
                        Всего
                       <span><?= $arResult['TOTAL']; ?></span>
                        <?= AB::declOfNum($arResult['TOTAL'], ['отзыв', 'отзыва','отзывов']); ?>
                    </a>
                </div>
            <? else: ?>
                <div class="news__all"><a href="/reviews/">Все отзывы</a></div>
            <? endif; ?>
			<div class="reviews__list">
				<?foreach($arResult["ITEMS"] as $arItem):?>
					<?
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>
					<article class="reviews__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<div class="reviews__item-box">
							<div class="reviews__item-photo">
								<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
									<img src="<?=$arItem['PREVIEW_PICTURE']['SRC_RESIZE']?>" alt="<?=htmlspecialcharsbx($arItem['NAME'])?>" title="<?=htmlspecialcharsbx($arItem['NAME'])?>">
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
								<div class="reviews__item-text"><?=$arItem["PREVIEW_TEXT"];?></div>
							<?endif;?>
						</div>
					</article>
				<?endforeach;?>
			</div>
		</div>

	</div>
<?endif;?>