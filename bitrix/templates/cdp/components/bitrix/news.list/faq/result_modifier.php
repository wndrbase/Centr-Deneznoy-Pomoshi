<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$CIBlockElement = new CIBlockElement;
$CIBlockSection = new CIBlockSection;

$rsSections = $CIBlockSection->GetList(Array($arParams['SORT_BY1'] => $arParams['SORT_ORDER1'], $arParams['SORT_BY2'] => $arParams['SORT_ORDER2']), Array("IBLOCK_ID" => $arParams['IBLOCK_ID']), true, Array("ID", "NAME"));
$arResult['SECTIONS'] = Array();
while($arSection = $rsSections->GetNext()) {
	$arResult['SECTIONS'][$arSection['ID']] = $arSection;
	$arResult['SECTIONS'][$arSection['ID']]['ITEMS'] = Array();
}


$arNotID = Array();

foreach($arResult['ITEMS'] as $arItem) {
	$arNotID[] = $arItem['ID'];
}

$rsElements = $CIBlockElement->GetList(Array($arParams['SORT_BY1'] => $arParams['SORT_ORDER1'], $arParams['SORT_BY2'] => $arParams['SORT_ORDER2']), Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "!ID" => $arNotID), false, false, Array());
while($arElement = $rsElements->GetNext()) {
	$arResult['ITEMS'][] = $arElement;
}

foreach($arResult['ITEMS'] as $arItem) {
	$arResult['SECTIONS'][$arItem['IBLOCK_SECTION_ID']]['ITEMS'][] = $arItem;
}

$arResult['SECTIONS'] = array_values($arResult['SECTIONS']);

unset($arResult['ITEMS']);