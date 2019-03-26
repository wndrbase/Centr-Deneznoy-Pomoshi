<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Вакансии");
?>
<div class="center clr">

    <div class="h1">Работа в Центре Денежной Помощи</div>

    <ul class="blue-icons-list">
        <li class="blue-icons-list__item">
            <div class="blue-icons-list__ico blue-icons-list__ico--clients"></div>
            <div class="blue-icons-list__title">Доверие клиентов</div>
            <?/*<div class="blue-icons-list__text">Пожалуйста, обращайтесь в ближайший офис или головной офис в Северодвинске.</div>*/?>
        </li>
        <li class="blue-icons-list__item">
            <div class="blue-icons-list__ico blue-icons-list__ico--wallet"></div>
            <div class="blue-icons-list__title">Достойная заработная плата</div>
            <?/*<div class="blue-icons-list__text">Пожалуйста, обращайтесь в ближайший офис или головной офис в Северодвинске.</div>*/?>
        </li>
        <li class="blue-icons-list__item">
            <div class="blue-icons-list__ico blue-icons-list__ico--bag"></div>
            <div class="blue-icons-list__title">Успешная карьера</div>
            <?/*<div class="blue-icons-list__text">Пожалуйста, обращайтесь в ближайший офис или головной офис в Северодвинске.</div>*/?>
        </li>
        <li class="blue-icons-list__item">
            <div class="blue-icons-list__ico blue-icons-list__ico--checklist"></div>
            <div class="blue-icons-list__title">Профессионализм</div>
            <?/*<div class="blue-icons-list__text">Пожалуйста, обращайтесь в ближайший офис или головной офис в Северодвинске.</div>*/?>
        </li>
    </ul>

	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"jobs",
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
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"DISPLAY_DATE" => "N",
			"DISPLAY_NAME" => "Y",
			"DISPLAY_PICTURE" => "N",
			"DISPLAY_PREVIEW_TEXT" => "Y",
			"DISPLAY_TOP_PAGER" => "N",
			"FIELD_CODE" => array("", ""),
			"FILTER_NAME" => "",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"IBLOCK_ID" => "7",
			"IBLOCK_TYPE" => "cdp",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"INCLUDE_SUBSECTIONS" => "Y",
			"MESSAGE_404" => "",
			"NEWS_COUNT" => "50",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"PAGER_TITLE" => "Вакансии",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"PREVIEW_TRUNCATE_LEN" => "",
			"PROPERTY_CODE" => array("CDP_J_CITY", ""),
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
			"SORT_ORDER2" => "DESC"
		)
	);?>
</div>

    <aside class="promo-block flexbox flexbox--align-center">
        <div class="center">
            <div class="h1">Хочешь работать у нас?</div>
            <p>Ознакомься с полным списком вакансий, а также оставить свое резюме Вы можете на сайте headhunter.ru</p>

            <p>
                <a href="//severodvinsk.hh.ru/employer/2507675" class="link" rel="nofollow" target="_blank">
                    <img src="/upload/images/HH.png" height="50" width="250" alt="Вакансии на hh.ru" title="Вакансии на hh.ru">
                </a>
            </p>
        </div>
    </aside>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>