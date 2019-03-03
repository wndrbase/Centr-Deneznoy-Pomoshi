<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) exit;
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @var CBitrixComponentTemplate $this */

if ($arResult['ITEMS']) {
    foreach ($arResult['ITEMS'] as &$arItem) {
        if ($arItem['PREVIEW_PICTURE'] && ($arItem['PREVIEW_PICTURE']['HEIGHT'] < 80 || $arItem['PREVIEW_PICTURE']['WIDTH'] < 80))
            $arItem['PREVIEW_PICTURE']['SRC_RESIZE'] = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_EXACT, true)['src'];
        else
            $arItem['PREVIEW_PICTURE']['SRC_RESIZE'] = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width' => 80, 'height' => 80), BX_RESIZE_IMAGE_EXACT, true)['src'];
    }
    unset($arItem);
}

$arResult['TOTAL'] = (int)$arResult['NAV_RESULT']->NavRecordCount;