<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__FILE__);

Loader::includeModule("highloadblock");
Loader::includeModule("iblock");


$docs = function($matches) use ($arResult) {

	ob_start();

		global $APPLICATION;

		$DOCS_ID = preg_replace("/[^0-9]/", '', $matches)[0];

		if($arResult["DOCS_SECTION_ID"][$DOCS_ID]) {

			$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"organization-docs",
				Array(
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"ADD_SECTIONS_CHAIN" => "N",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"AJAX_OPTION_HISTORY" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "N",
					"CACHE_FILTER" => "N",
					"CACHE_GROUPS" => "Y",
					"CACHE_TIME" => "36000000",
					"CACHE_TYPE" => "A",
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"DISPLAY_DATE" => "N",
					"DISPLAY_NAME" => "Y",
					"DISPLAY_PICTURE" => "N",
					"DISPLAY_PREVIEW_TEXT" => "N",
					"DISPLAY_TOP_PAGER" => "N",
					"FIELD_CODE" => array("", ""),
					"FILTER_NAME" => "",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"IBLOCK_ID" => $arResult['DOCS_LINK_IBLOCK_ID'],
					"IBLOCK_TYPE" => "",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"INCLUDE_SUBSECTIONS" => "Y",
					"MESSAGE_404" => "",
					"NEWS_COUNT" => "500",
					"PAGER_BASE_LINK_ENABLE" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => ".default",
					"PAGER_TITLE" => "Документы",
					"PARENT_SECTION" => $arResult["DOCS_SECTION_ID"][$DOCS_ID],
					"PARENT_SECTION_CODE" => "",
					"PREVIEW_TRUNCATE_LEN" => "",
					"PROPERTY_CODE" => array("", "CDP_D_FILE", "CDP_D_FILE_DESCRIPTION"),
					"SET_BROWSER_TITLE" => "N",
					"SET_LAST_MODIFIED" => "N",
					"SET_META_DESCRIPTION" => "N",
					"SET_META_KEYWORDS" => "N",
					"SET_STATUS_404" => "N",
					"SET_TITLE" => "N",
					"SHOW_404" => "N",
					"SORT_BY1" => "SORT",
					"SORT_BY2" => "ACTIVE_FROM",
					"SORT_ORDER1" => "ASC",
					"SORT_ORDER2" => "DESC",
					"STRICT_SECTION_CHECK" => "N",
					"CUSTOM_TITLE" => Loc::getMessage('CDP_O_DOCS')
				)
			);

		}
		else
			echo '';

	$retrunStr = ob_get_clean();

	return $retrunStr;
};

foreach($arResult["ELEMENTS"] as $elementID)
	$arResult["CACHED_TPL"] = preg_replace_callback("/#DOCS_{$elementID}#/is".BX_UTF_PCRE_MODIFIER, $docs, $arResult["CACHED_TPL"]);

$docs_archive = function($matches) use ($arResult) {

	ob_start();

		global $APPLICATION;

		$DOCS_ID = preg_replace("/[^0-9]/", '', $matches)[0];

		if($arResult["DOCS_SECTION_ID"][$DOCS_ID]) {

			global $arrDocsArchiveFilter;

			$arrDocsArchiveFilter = Array("ACTIVE_DATE" => "", "<DATE_ACTIVE_TO" => ConvertTimeStamp());

			$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"organization-docs",
				Array(
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"ADD_SECTIONS_CHAIN" => "N",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"AJAX_OPTION_HISTORY" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "N",
					"CACHE_FILTER" => "N",
					"CACHE_GROUPS" => "Y",
					"CACHE_TIME" => "36000000",
					"CACHE_TYPE" => "A",
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"DISPLAY_DATE" => "N",
					"DISPLAY_NAME" => "Y",
					"DISPLAY_PICTURE" => "N",
					"DISPLAY_PREVIEW_TEXT" => "N",
					"DISPLAY_TOP_PAGER" => "N",
					"FIELD_CODE" => array("", ""),
					"FILTER_NAME" => "arrDocsArchiveFilter",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"IBLOCK_ID" => $arResult['DOCS_LINK_IBLOCK_ID'],
					"IBLOCK_TYPE" => "",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"INCLUDE_SUBSECTIONS" => "Y",
					"MESSAGE_404" => "",
					"NEWS_COUNT" => "500",
					"PAGER_BASE_LINK_ENABLE" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => ".default",
					"PAGER_TITLE" => "Документы",
					"PARENT_SECTION" => $arResult["DOCS_SECTION_ID"][$DOCS_ID],
					"PARENT_SECTION_CODE" => "",
					"PREVIEW_TRUNCATE_LEN" => "",
					"PROPERTY_CODE" => array("", "CDP_D_FILE", ""),
					"SET_BROWSER_TITLE" => "N",
					"SET_LAST_MODIFIED" => "N",
					"SET_META_DESCRIPTION" => "N",
					"SET_META_KEYWORDS" => "N",
					"SET_STATUS_404" => "N",
					"SET_TITLE" => "N",
					"SHOW_404" => "N",
					"SORT_BY1" => "ACTIVE_FROM",
					"SORT_BY2" => "SORT",
					"SORT_ORDER1" => "DESC",
					"SORT_ORDER2" => "ASC",
					"STRICT_SECTION_CHECK" => "N",
					"CUSTOM_TITLE" => Loc::getMessage('CDP_O_DOCS_ARCHIVE')
				)
			);

		}
		else
			echo '';

	$retrunStr = ob_get_clean();

	return $retrunStr;
};

foreach($arResult["ELEMENTS"] as $elementID)
	$arResult["CACHED_TPL"] = preg_replace_callback("/#DOCS_ARCHIVE_{$elementID}#/is".BX_UTF_PCRE_MODIFIER, $docs_archive, $arResult["CACHED_TPL"]);


echo $arResult["CACHED_TPL"];