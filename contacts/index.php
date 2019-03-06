<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?><div class="center clr">

	<?/*<div class="contacts clr">
		<h1>Контактная информация</h1>

		<div class="contacts__common clr">
			<div class="contacts__common-left">
				<div class="contacts__common-title">ООО МКК &laquo;Центр Денежной Помощи&raquo;</div>
				<div class="contacts__common-loc">Регистрационный номер записи в государственном реестре №1112932002149</div>
				<?/*<div class="contacts__common-desc">Режим работы: понедельник-пятница с 09:00 до 17:00</div>*//*?>
			</div>
		<div class="contacts__common clr">
			<div class="contacts__common-left">
				<div class="contacts__common-title">ООО МКК &laquo;Центр Денежной Помощи-Центр&raquo;</div>
				<div class="contacts__common-loc">Регистрационный номер записи в государственном реестре №1132932002444</div>
				<?/*<div class="contacts__common-desc">Режим работы: понедельник-пятница с 09:00 до 17:00</div>*//*?>
			</div>
		<div class="contacts__common clr">
			<div class="contacts__common-left">
				<div class="contacts__common-title">ООО МКК &laquo;Центр Денежной Помощи-ДОН&raquo;</div>
				<div class="contacts__common-loc">Регистрационный номер записи в государственном реестре №1132932002455</div>
				<?/*<div class="contacts__common-desc">Режим работы: понедельник-пятница с 09:00 до 17:00</div>*//*?>
			</div>
			<div class="contacts__common-right">
				<div class="contacts__common-title">Единый контактный телефон:</div>
				<div class="contacts__common-loc"><a href="tel:8 (800) 302-8-302">8 (800) 302-8-302</a></div>
				<div class="contacts__common-desc">Звонок бесплатный</div>
			</div>
		</div>
	</div>*/?>

	<?/*$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"org-contacts",
	array(
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
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "8",
		"IBLOCK_TYPE" => "cdp",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "30",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Организации",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "CDP_O_INN",
			1 => "CDP_O_KPP",
			2 => "CDP_O_OGRN",
			3 => "CDP_O_ADDRESS",
			4 => "",
		),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "ID",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "DESC",
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "org-contacts"
	),
	false
);*/?>

	<?
	global $arOfficesFilter;
	$arOfficesFilter['SECTION_ID'] = intval(AB_S1::$GEODATA['CITY_ID']) > 0 ? intval(AB_S1::$GEODATA['CITY_ID']) : DEFAULT_CITY_ID;
	?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"branch-addr",
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
			"FILTER_NAME" => "arOfficesFilter",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"IBLOCK_ID" => "1",
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
			"PAGER_TITLE" => "Офисы",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"PREVIEW_TRUNCATE_LEN" => "",
			"PROPERTY_CODE" => array("CDP_G_OFFICE_PLACEMARK", "CDP_G_OFFICE_ADDRESS", "CDP_G_OFFICE_PHONE", "CDP_G_OFFICE_SCHEDULE"),
			"SET_BROWSER_TITLE" => "N",
			"SET_LAST_MODIFIED" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_404" => "N",
			"SORT_BY1" => "NAME",
			"SORT_BY2" => "SORT",
			"SORT_ORDER1" => "ASC",
			"SORT_ORDER2" => "ASC",
			"STRICT_SECTION_CHECK" => "N",
			"WRAPPER_CLASS" => "offices--contacts"
		)
	);?>

	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"org-contacts-2",
		array(
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
			"DISPLAY_PREVIEW_TEXT" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"FIELD_CODE" => array(
				0 => "",
				1 => "",
			),
			"FILTER_NAME" => "",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"IBLOCK_ID" => "8",
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
			"PAGER_TITLE" => "Организации",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"PREVIEW_TRUNCATE_LEN" => "",
			"PROPERTY_CODE" => array(
				0 => "CDP_O_INN",
				1 => "CDP_O_KPP",
				2 => "CDP_O_OGRN",
				3 => "CDP_O_ADDRESS",
				4 => "CDP_O_PHONE",
				5 => "CDP_O_MORE_CONTACTS"
			),
			"SET_BROWSER_TITLE" => "N",
			"SET_LAST_MODIFIED" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_404" => "N",
			"SORT_BY1" => "SORT",
			"SORT_BY2" => "NAME",
			"SORT_ORDER1" => "ASC",
			"SORT_ORDER2" => "ASC",
			"STRICT_SECTION_CHECK" => "N",
			"COMPONENT_TEMPLATE" => "organizations"
		),
		false
	);?>

</div>

<form class="form contacts__form" id="form-feedback">

	<div class="center clr">

		<div class="h1">Обратная связь</div>

		<div class="contacts__form-box">

			<div class="input-line">
                <input type="text" name="USER_NAME" class="input" placeholder="Имя">
            </div>

			<div class="input-line input-line--left">
                <input type="tel" name="USER_PHONE" class="input mask-tel" placeholder="Контактный телефон">
            </div>

			<div class="input-line input-line--right">
                <input type="text" name="USER_EMAIL" class="input" placeholder="Почта">
            </div>

			<div class="input-line">
				<select name="USER_CITY">
					<option value="none">Ваш город</option>
					<?if($AR_CITIES):?>
						<?foreach($AR_CITIES as $key => $value):?>
							<option value="<?=$value['NAME']?>"<?=(isset(AB_S1::$GEODATA['CITY_ID'])&&AB_S1::$GEODATA['CITY_ID']==$key?' selected':'')?>><?=$value['NAME']?></option>
						<?endforeach;?>
					<?endif;?>
				</select>
			</div>

			<div class="input-line">
				<textarea name="USER_MESSAGE" class="input" placeholder="Ваше сообщение"></textarea>
			</div>

			<div class="input-line">
				<label class="checkbox"><input type="checkbox" name="FZ_ACCEPT" value="1">
                    Я согласен(а) на обработку персональных данных и ознакомлен(а)
                    с
                    <a href="/upload/docs/privacy-policy.pdf" target="_blank">Политикой конфиденциальности</a>
                    и
                    <a href="/upload/docs/position.pdf" target="_blank">Положением о порядке рассмотрения обращений, требования и рекомендации к содержанию обращений</a>
                </label>
			</div>

			<?if(USE_GOOGLE_CAPTCHA_V2):?>
			<div class="input-line">
				<div id="g-recaptcha-feedback" class="g-recaptcha"></div>
			</div>
			<?endif;?>

			<div class="input-line input-line--submit">
				<input type="hidden" name="AJAX_CONTACTS_FEEDBACK" value="Y">
				<label class="btn btn--red contacts__form-btn">Отправить<input type="submit"></label>
			</div>
		</div>

	</div>

</form>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>