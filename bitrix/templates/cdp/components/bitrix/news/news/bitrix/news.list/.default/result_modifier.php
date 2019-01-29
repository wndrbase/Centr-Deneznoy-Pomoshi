<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$CIBlockElement = new CIBlockElement;

if($arResult["PICTURE"])
	$arResult["PICTURE"] = CFile::GetFileArray($arResult["PICTURE"]);

$arResult['ALL_NEWS_COUNT'] = $CIBlockElement->GetList(Array("ACTIVE_DATE_FROM" => "DESC", "SORT" => "ASC"), Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y", "CHECK_PERMISSIONS" => "Y"), false, false, Array("ID"))->SelectedRowsCount();