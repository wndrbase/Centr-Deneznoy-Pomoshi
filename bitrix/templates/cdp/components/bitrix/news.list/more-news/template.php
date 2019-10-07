<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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

if (empty($arResult["ITEMS"])) return;
?>


<div class="center clr">
    <div class="news news--more">
        <div class="h1"><?= Loc::getMessage("CDP_N_MORE_NEWS") ?></div>

        <div class="slider news-slider-more">

            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <?
                $bShowPreview = $arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"];

                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
                ?>
                <div class="news-slider-more-item" >

                    <li class="" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                        <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="news__item-img">
                            <? if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arItem["PREVIEW_PICTURE"])): ?>
                                <img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>" title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>">
                            <? else: ?>
                                <img src="<?= SITE_TEMPLATE_PATH ?>/img/news_blank.jpg" alt="<?= htmlspecialcharsbx($arItem["NAME"]) ?>" title="<?= htmlspecialcharsbx($arItem["NAME"]) ?>">
                            <? endif; ?>
                        </a>

                        <? if ($arParams["DISPLAY_DATE"] != "N" && $arItem["DISPLAY_ACTIVE_FROM"]): ?>
                            <time class="news__item-date" datetime="<?= date("Y-m-d", strtotime($arItem["ACTIVE_FROM"])) ?>"><?= $arItem["DISPLAY_ACTIVE_FROM"] ?></time>
                        <? endif ?>

                        <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="news__item-title<?= $bShowPreview ? '' : ' news__item-title--show-more'; ?>">
                            <? if ($arParams["DISPLAY_NAME"] != "N" && $arItem["NAME"]): ?>
                                <span class="news__item-caption"><?= $arItem["NAME"] ?></span>
                            <? endif; ?>
                            <? if ($bShowPreview): ?>
                                <span class="news__item-anounce"><?= $arItem["PREVIEW_TEXT"]; ?></span>
                            <? else: ?>
                                <span class="news__item-anounce">Читать подробнее...</span>
                            <? endif; ?>
                        </a>
                    </li>

                </div>
            <? endforeach; ?>


        </div>


        <br/>


        <a href="<?= $arResult["ITEMS"][0]["LIST_PAGE_URL"] ?>" class="btn news__btn-more news__btn-more--all"><?= Loc::getMessage("CDP_N_ALL") ?></a>
    </div>
</div>