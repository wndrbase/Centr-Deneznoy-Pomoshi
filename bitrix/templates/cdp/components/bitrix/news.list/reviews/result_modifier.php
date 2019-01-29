<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$CIBlockElement = new CIBlockElement;

if($arResult["PICTURE"])
	$arResult["PICTURE"] = CFile::GetFileArray($arResult["PICTURE"]);

$arResult['ALL_NEWS_COUNT'] = $CIBlockElement->GetList(Array("ACTIVE_DATE_FROM" => "DESC", "SORT" => "ASC"), Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y"), false, false, Array("ID"))->SelectedRowsCount();


if($arResult['ITEMS']) {

	foreach($arResult['ITEMS'] as &$arItem) {

		if($arItem['PREVIEW_PICTURE'] && ($arItem['PREVIEW_PICTURE']['HEIGHT'] < 80 || $arItem['PREVIEW_PICTURE']['WIDTH'] < 80))
			$arItem['PREVIEW_PICTURE']['SRC_RESIZE'] = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_EXACT, true)['src'];
		else
			$arItem['PREVIEW_PICTURE']['SRC_RESIZE'] = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width' => 80, 'height' => 80), BX_RESIZE_IMAGE_EXACT, true)['src'];


	}

}