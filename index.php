<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "центр денежной помощи, ооо мкк цдп, займы, быстрые деньги, микрозаймы, мфо, финансовая помощь, взять кредит, мкк онлайн, денежные займы, сайт денежной помощи, оформить займ");
$APPLICATION->SetPageProperty("description", "Получить заем легко и быстро! Простое решение финансовых трудностей в кратчайшие сроки без справок, скрытых комиссий. Снижаем ставку новым и постоянным клиентам, пенсионерам, военнослужащим, привели друга или обратились впервые.");
$APPLICATION->SetTitle("Центр денежной помощи");
?><div class="center clr">

	<div class="calculator">
		<a name="calculator"></a>
		<form action="" method="get" class="calculator__form" id="form-calculator">

			<input class="calculator__data-values hide" type="radio" checked="checked"
				data-sum-step="100"
				data-sum-min="1000"
				data-sum-max="30000"
				data-sum-value="12000"
				data-date-step="1"
				data-date-min="1"
				data-date-max="60"
				data-date-value="30"
				data-rate="1">

			<input type="hidden" name="USER_FIRST_NAME">
			<input type="hidden" name="USER_LAST_NAME">
			<input type="hidden" name="USER_MIDDLE_NAME">
			<input type="hidden" name="USER_BIRTHDAY">
			<input type="hidden" name="USER_PHONE">
			<input type="hidden" name="USER_EMAIL">
			<input type="hidden" name="OFFICE_NAME">
			<input type="hidden" name="LOAN_NAME">
			<input type="hidden" name="LOAN_VIEW_UID">
			<input type="hidden" name="LOAN_STOCK_UID">
			<input type="hidden" name="AJAX_LOAN" value="Y">
			<input type="hidden" name="g-recaptcha-response" value="">

			<div class="calculator__head visible-xs">
				<a href="javascript:;" class="btn btn--red btn-alert_up calculator__select-office-btn" data-alert-up="calc-geo">Выберите офис</a>
			</div>

			<ul class="calculator__head hidden-xs">
				<?if($AR_REGIONS_AND_CITIES):?>
					<li>
						<select name="REGION_ID">
							<option value="none">Выбрать область</option>
							<?foreach($AR_REGIONS_AND_CITIES as $key => $value):?>
							<option value="<?=$key?>"<?=(isset(AB_S1::$GEODATA['REGION_ID'])&&AB_S1::$GEODATA['REGION_ID']==$key?' selected':'')?>><?=$value['NAME']?></option>
							<?endforeach;?>
						</select>
					</li>
					<li>
						<select name="CITY_ID"<?=(!isset(AB_S1::$GEODATA['REGION_ID']) || !$AR_REGIONS_AND_CITIES[AB_S1::$GEODATA['REGION_ID']]['CITIES']?' disabled="disabled"':'')?>>
							<option value="none">Выбрать город</option>
							<?if(isset(AB_S1::$GEODATA['REGION_ID']) && $AR_REGIONS_AND_CITIES[AB_S1::$GEODATA['REGION_ID']]['CITIES']):?>
								<?foreach($AR_REGIONS_AND_CITIES[AB_S1::$GEODATA['REGION_ID']]['CITIES'] as $key => $value):?>
									<option value="<?=$key?>"<?=(isset(AB_S1::$GEODATA['CITY_ID'])&&AB_S1::$GEODATA['CITY_ID']==$key?' selected':'')?>><?=$value['NAME']?></option>
								<?endforeach;?>
							<?endif;?>
						</select>
					</li>
					<li>
						<select name="OFFICE_ID"<?=(!isset(AB_S1::$GEODATA['OFFICE_ID']) && !isset(AB_S1::$GEODATA['CITY_ID'])?' disabled="disabled"':'')?>>
							<option value="none">Выбрать отделение</option>
							<?if(AB_S1::$GEODATA['OFFICES']):?>
								<?foreach(AB_S1::$GEODATA['OFFICES'] as $key => $value):?>
								<option value="<?=$key?>"<?=(isset(AB_S1::$GEODATA['OFFICE_ID'])&&AB_S1::$GEODATA['OFFICE_ID']==$key?' selected':'')?>><?=$value['ADDRESS']?></option>
								<?endforeach;?>
							<?endif;?>
						</select>
					</li>
					<li class="last">
						<select name="LOAN_VIEW" class="calculator__btn-toggle"<?=(!isset(AB_S1::$GEODATA['OFFICE_ID'])?' disabled="disabled"':'')?>>
							<option value="none">Статус клиента</option>
							<?if(AB_S1::$GEODATA['LOAN_VIEWS']):?>
								<?foreach(AB_S1::$GEODATA['LOAN_VIEWS'] as $key => $value):
								 echo '<option value="'.$key.'" data-loan-view-uid="'.$value['LOAN_UID'].'" data-stock-uid="'.$value['STOCK_UID'].'" data-rate="'.$value['C_DLV_RATE']['VALUE'].'" data-rate-stock="'.$value['C_DLV_STOCK_RATE']['VALUE'].'" data-time-min="'.$value['C_DLV_TIME_FROM']['VALUE'].'" data-time-max="'.$value['C_DLV_TIME_TO']['VALUE'].'" data-sum-min="'.$value['C_DLV_SUM_FROM']['VALUE'].'" data-sum-max="'.$value['C_DLV_SUM_TO']['VALUE'].'">'.$value['NAME'].'</option>';
								endforeach;?>
							<?endif;?>
						</select>
					</li>
				<?endif;?>
			</ul>

			<div class="calculator__body clr">

				<div class="calculator__left">
					<div class="calculator__box clr">
						<div class="calculator__box-left">
							<div class="calculator__box-label">Вы занимаете</div>
							<div class="calculator__box-input">
								<input type="text" name="LOAN_SUM" value="7 400" id="sum-set"><span class="calculator__box-currency">₽</span>
							</div>
						</div>
						<div class="calculator__box-right hidden-xs">
							<div class="calculator__box-label calculator__box-label--min">Сумма за пользование займом <span class="calculator__sum-commission">1 170</span> <span class="calculator__box-currency">₽</span></div>
						</div>
						<div class="clr"></div>
						<div class="calculator__slider">
							<div id="calc-sum-slider"></div>
							<div class="calculator__slider-from">0</div>
							<div class="calculator__slider-to">30000</div>
						</div>
					</div>
					<div class="calculator__box clr">
						<div class="calculator__box-left">
							<div class="calculator__box-label">На срок</div>
							<div class="calculator__box-input calculator__box-input--days">
								<input type="text" name="LOAN_TIME" value="20" id="date-set"><span class="calculator__box-days" data-declension="день,дня,дней">дней</span>
							</div>
						</div>
						<div class="calculator__box-right hidden-xs">
							<div class="calculator__box-label calculator__box-label--min">Дата возврата кредита <span class="calculator__date-comeback">25 апреля</span></div>
						</div>
						<div class="clr"></div>
						<div class="calculator__slider">
							<div id="calc-date-slider"></div>
							<div class="calculator__slider-from">1</div>
							<div class="calculator__slider-to">30</div>
						</div>
					</div>

					<div class="calculator__box calculator__box--mobile visible-xs clr">
						<div class="calculator__box-label calculator__box-label--min">Комиссия <span class="calculator__sum-commission">1 170</span> <span class="calculator__box-currency">₽</span></div>
					</div>
					<div class="calculator__box calculator__box--mobile visible-xs clr">
						<div class="calculator__box-label calculator__box-label--min">Дата возврата кредита <span class="calculator__date-comeback">25 апреля</span></div>
					</div>
					<div class="calculator__box calculator__box--mobile visible-xs clr">
						<div class="calculator__box-label calculator__box-label--min">Сумма к возврату <span class="calculator__total-sum"><span>1 170</span></span> <span class="calculator__box-currency">₽</span></div>
					</div>
				</div>

				<div class="calculator__right">
					<div class="calculator__total hidden-sm">
						<div class="calculator__total-text">Сумма к возврату:</div>
						<div class="calculator__total-sum"><span>8 570</span> <b>₽</b></div>
					</div>
					<label class="btn btn--red calculator__btn">Получить решение по займу</label>
				</div>

			</div>
		</form>
	</div>

	<div class="advantages">
		<div class="overtitle advantages__q">Почему выбирают нас?</div>
		<div class="h1">Наши преимущества</div>
		<ul class="advantages__list clr">
			<li class="advantages__item">
				<div class="advantages__item-icon"></div>
				<div class="advantages__item-caption">Быстрое одобрение</div>
				<div class="advantages__item-text"></div>
			</li>
			<li class="advantages__item">
				<div class="advantages__item-icon"></div>
				<div class="advantages__item-caption">Без скрытых комиссий</div>
				<div class="advantages__item-text"></div>
			</li>
			<li class="advantages__item">
				<div class="advantages__item-icon"></div>
				<div class="advantages__item-caption">Без справок</div>
				<div class="advantages__item-text"></div>
			</li>
			<li class="advantages__item">
				<div class="advantages__item-icon"></div>
				<div class="advantages__item-caption">Конфиденциальность</div>
				<div class="advantages__item-text"></div>
			</li>
		</ul>
	</div>

</div>

<hr>

<div class="center clr">

	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"main-news",
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
			"DISPLAY_DATE" => "Y",
			"DISPLAY_NAME" => "Y",
			"DISPLAY_PICTURE" => "Y",
			"DISPLAY_PREVIEW_TEXT" => "Y",
			"DISPLAY_TOP_PAGER" => "N",
			"FIELD_CODE" => array("", ""),
			"FILTER_NAME" => "",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"IBLOCK_ID" => "4",
			"IBLOCK_TYPE" => "cdp",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"INCLUDE_SUBSECTIONS" => "Y",
			"MESSAGE_404" => "",
			"NEWS_COUNT" => "16",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"PAGER_TITLE" => "Новости",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"PREVIEW_TRUNCATE_LEN" => "",
			"PROPERTY_CODE" => array("", ""),
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
			"SORT_ORDER2" => "ASC"
		)
	);?>

</div>

<?if($APPLICATION->GetProperty("PROMO_BLOCK")):?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_TEMPLATE_PATH."/include_areas/promo_block.php",
			"EDIT_TEMPLATE" => "standard.php"
		),
		false
	);?>
<?endif;?>

<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"main-reviews",
	Array(
		"ACTIVE_DATE_FORMAT" => "j F Y",
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
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array("", ""),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "6",
		"IBLOCK_TYPE" => "cdp",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "9",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Отзывы клиентов",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "120",
		"PROPERTY_CODE" => array("CDP_R_POST", ""),
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

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>