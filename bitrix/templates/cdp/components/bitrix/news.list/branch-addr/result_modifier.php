<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach($arResult["ITEMS"] as &$arItem)
	if($arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_SCHEDULE']['~VALUE']['TEXT'])
		$arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_SCHEDULE']['~VALUE']['FORMAT_TEXT'] = AB_S1::getFormatSchedule(unserialize($arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_SCHEDULE']['~VALUE']['TEXT']));

