<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

	use Bitrix\Main\Localization\Loc;
	Loc::loadMessages(__FILE__);
	global $APPLICATION;
	global $B_INDEX_PAGE;
	global $FULL_VERSION;
?>

		</main>

		<?if($APPLICATION->GetProperty("PROMO_BLOCK") == 'Y' && !$B_INDEX_PAGE):?>
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

		<footer class="footer">

			<div class="center clr">

				<div class="footer__social clr">
					<ul class="footer__social-list">
						<li class="footer__social-item footer__social-item--vk"><a href="https://vk.com/money4help" target="_blank" rel="nofollow"></a></li>
						<li class="footer__social-item footer__social-item--fb"><a href="https://www.facebook.com/money4help/" target="_blank" rel="nofollow"></a></li>
						<li class="footer__social-item footer__social-item--ok"><a href="https://ok.ru/group/53690351026353" target="_blank" rel="nofollow"></a></li>
						<li class="footer__social-item footer__social-item--ig"><a href="https://www.instagram.com/money_cdp/" target="_blank" rel="nofollow"></a></li>
						<li class="footer__social-item footer__social-item--tw"><a href="https://twitter.com/money4help" target="_blank" rel="nofollow"></a></li>
						<li class="footer__social-item footer__social-item--yt"><a href="https://www.youtube.com/channel/UClvFVCqte2KP_TXdLHNFn4g" target="_blank" rel="nofollow"></a></li>
					</ul>
				</div>

				<?$APPLICATION->IncludeComponent(
					"bitrix:menu",
					"footer",
					array(
						"ALLOW_MULTI_SELECT" => "N",
						"CHILD_MENU_TYPE" => "footer",
						"DELAY" => "N",
						"MAX_LEVEL" => "1",
						"MENU_CACHE_GET_VARS" => array(""),
						"MENU_CACHE_TIME" => "3600",
						"MENU_CACHE_TYPE" => "N",
						"MENU_CACHE_USE_GROUPS" => "Y",
						"ROOT_MENU_TYPE" => "footer",
						"USE_EXT" => "N"
					)
				);?>

				<div class="footer__hr"></div>

				<div class="footer__copyright">
					<p><?=Loc::getMessage("F_COPY", Array("#YEAR#" => date("Y")))?></p>
					<?/*<p><?=Loc::getMessage("F_REG")?></p>*/?>
				</div>

                <div class="footer__wonderbase">
				Разработка сайта &mdash; <a href="//wndrbase.com" target="_blank" rel="nofollow">Wondrbase</a>
                </div>

				<div class="footer__full-version">
					<?if(!$FULL_VERSION):?>
					<a href="<?=$APPLICATION->GetCurDir()?>?full_version=Y"><?=Loc::getMessage("F_FULL_VERSION")?></a>
					<?else:?>
					<a href="<?=$APPLICATION->GetCurDir()?>?mobile_version=Y"><?=Loc::getMessage("F_MOBILE_VERSION")?></a>
					<?endif;?>
				</div>


			</div>

		</footer>


        <button id="btn-scroll-top" title="Наверх">
            <svg width="17" height="19" viewBox="0 0 17 19" xmlns="http://www.w3.org/2000/svg"><path d="M9.5 18.5V3.914l5.293 5.293 1.414-1.414L8.5.086.793 7.793l1.414 1.414L7.5 3.914V18.5z" fill="#21405B" fill-rule="nonzero"/></svg>
        </button>

		<!-- alert__up -->
		<div class="alert_up alert_up--hide flexbox flexbox--center flexbox--column flexbox--align-center">

			<div class="alert_up__window alert_up__window--no-close alert_up__window--test-mode">
				<form class="alert_up__box" novalidate autocomplete="off" id="form-test-mode">
					<?=bitrix_sessid_post()?>
					<h3><?=Loc::getMessage("F_AU_TEST_MODE_TITLE")?></h3>
					<div class="input-line">
						<label class="checkbox">
                            <input type="checkbox" name="CB_TEST_MODE" value="1">
                            <i></i>
                            <?=Loc::getMessage("F_AU_TEST_MODE_CHECKBOX")?>
                        </label>
					</div>
					<div class="input-line input-line--submit">
						<label class="btn btn--red"><?=Loc::getMessage("F_AU_TEST_MODE_SUBMIT")?><input type="submit"></label>
					</div>
				</form>

			</div>

			<div class="alert_up__window alert_up__window--callback">
				<form class="alert_up__box" novalidate autocomplete="off" id="form-callback">
					<?=bitrix_sessid_post()?>
					<h3><?=Loc::getMessage("F_AU_FORM_CALLBACK_TITLE")?></h3>
					<div class="input-line">
						<input type="text" name="USER_NAME" class="input" placeholder="<?=Loc::getMessage("F_AU_FORM_CALLBACK_PH_NAME")?>">
					</div>
					<div class="input-line">
						<input type="tel" name="USER_PHONE" class="input mask-tel" placeholder="<?=Loc::getMessage("F_AU_FORM_CALLBACK_PH_PHONE")?>">
					</div>
					<?/*<div class="input-line">
						<input type="text" name="USER_CITY" class="input input--only-l" placeholder="<?=Loc::getMessage("F_AU_FORM_CALLBACK_PH_CITY")?>">
					</div>*/?>
					<div class="input-line">
						<select name="USER_CITY">
							<option value="none"><?=Loc::getMessage("F_AU_FORM_CALLBACK_PH_CITY")?></option>
							<?if($AR_CITIES):?>
								<?foreach($AR_CITIES as $key => $value):?>
									<option value="<?=$value['NAME']?>"<?=(isset(AB_S1::$GEODATA['CITY_ID'])&&AB_S1::$GEODATA['CITY_ID']==$key?' selected':'')?>><?=$value['NAME']?></option>
								<?endforeach;?>
							<?endif;?>
						</select>
					</div>
					<div class="input-line">
						<label class="checkbox">
                            <input type="checkbox" name="FZ_ACCEPT" value="1">
                            <i></i>
                            Я согласен(а) на обработку персональных данных и ознакомлен(а) с <a href="/upload/medialibrary/04d/PDn-TSDP-sayt.pdf" target="_blank">Политикой конфиденциальности ООО МКК "ЦДП"</a>, <a href="/upload/medialibrary/2e8/PDn-TSentr-sayt.pdf" target="_blank">Политикой конфиденциальности ООО МКК "ЦДП-ЦЕНТР"</a>, <a href="/upload/medialibrary/0c0/PDn-Don-sayt.pdf" target="_blank">Политикой конфиденциальности ООО МКК "ЦДП-ДОН"</a>
                        </label>
					</div>
					<?if(USE_GOOGLE_CAPTCHA_V2):?>
					<div class="input-line">
						<div id="g-recaptcha-callback" class="g-recaptcha"></div>
					</div>
					<?endif;?>
					<div class="input-line input-line--submit">
						<input type="hidden" name="AJAX_ORDER_CALLBACK" value="Y">
						<label class="btn"><?=Loc::getMessage("F_AU_FORM_CALLBACK_SUBMIT")?><input type="submit"></label>
					</div>
					<a class="alert_up__close ico ico--close"></a>
				</form>

			</div>

			<div class="alert_up__window alert_up__window--jobs">

				<form class="alert_up__box" novalidate autocomplete="off" id="form-job">
					<?=bitrix_sessid_post()?>
					<h3><?=Loc::getMessage("F_AU_FORM_JOB_TITLE")?></h3>
					<p class="job-name"></p>
					<p class="job-city"></p>
					<div class="input-line input-line--city">
						<select name="USER_CITY" id="jobs-city-select">
							<option value="none"><?=Loc::getMessage("F_AU_FORM_JOB_SELECT_CITY")?></option>
						</select>
					</div>
					<div class="input-line">
						<input type="text" name="USER_NAME" class="input" placeholder="<?=Loc::getMessage("F_AU_FORM_JOB_PH_NAME")?>">
					</div>
					<div class="input-line">
						<input type="tel" name="USER_PHONE" class="input mask-tel" placeholder="<?=Loc::getMessage("F_AU_FORM_JOB_PH_PHONE")?>">
					</div>
					<div class="input-line clr" data-msg-error="">
						<a href="javascript:;" class="btn btn--red" id="remove-resume-file">Удалить файл</a>
						<label class="input input--file"><?=Loc::getMessage("F_AU_FORM_JOB_BTN_ATTACH_RESUME")?><input type="file" name="USER_RESUME" class="input"></label>
						<span><?=Loc::getMessage("F_AU_FORM_JOB_BTN_ATTACH_NOTE")?></span>
					</div>
					<div class="input-line">
						<label class="checkbox">
                            <input type="checkbox" name="FZ_ACCEPT" value="1">
                            <i></i>
                            <?=Loc::getMessage("F_AU_FORM_JOB_FZ")?>
                        </label>
					</div>
					<div class="input-line input-line--submit">
						<input type="hidden" name="USER_JOB_NAME" value="">
						<input type="hidden" name="USER_JOB_CITY" value="">
						<input type="hidden" name="USER_RESUME_FILE" value="">
						<input type="hidden" name="AJAX_JOB" value="Y">
						<label class="btn"><?=Loc::getMessage("F_AU_FORM_JOB_SUBMIT")?><input type="submit"></label>
					</div>
					<a class="alert_up__close ico ico--close"></a>
				</form>

			</div>

			<div class="alert_up__window alert_up__window--order-loan">

				<form class="alert_up__box" novalidate autocomplete="off" id="order-loan">
					<?=bitrix_sessid_post()?>
					<h3><?=Loc::getMessage("F_AU_FORM_LOAN_TITLE")?></h3>
					<div class="input-line">
						<input type="text" name="LAST_NAME" class="input" placeholder="<?=Loc::getMessage("F_AU_FORM_LOAN_PH_LAST_NAME")?>">
					</div>
					<div class="input-line">
						<input type="text" name="FIRST_NAME" class="input" placeholder="<?=Loc::getMessage("F_AU_FORM_LOAN_PH_FIRST_NAME")?>">
					</div>
					<div class="input-line">
						<input type="text" name="MIDDLE_NAME" class="input" placeholder="<?=Loc::getMessage("F_AU_FORM_LOAN_PH_MIDDLE_NAME")?>">
					</div>
					<div class="input-line">
						<input type="text" name="BIRTHDAY" class="input mask-date" placeholder="<?=Loc::getMessage("F_AU_FORM_LOAN_PH_BIRTHDAY")?>">
					</div>
					<div class="input-line">
						<input type="tel" name="PHONE" class="input mask-tel" placeholder="<?=Loc::getMessage("F_AU_FORM_LOAN_PH_PHONE")?>">
					</div>
					<div class="input-line">
						<input type="email" name="EMAIL" class="input" placeholder="<?=Loc::getMessage("F_AU_FORM_LOAN_PH_EMAIL")?>">
					</div>
					<div class="input-line">
						<label class="checkbox">
                            <input type="checkbox" name="FZ_ACCEPT" value="1">
                            <i></i>
                            <?=Loc::getMessage("F_AU_FORM_LOAN_FZ")?>
                        </label>
					</div>
					<?if(USE_GOOGLE_CAPTCHA_V2):?>
					<div class="input-line">
						<div id="g-recaptcha-loan" class="g-recaptcha"></div>
					</div>
					<?endif;?>
					<div class="input-line input-line--submit">
						<label class="btn btn--red">
                            <input type="submit">
                            <span class="btn__container">
                                <span class="btn__tick"><?=Loc::getMessage("F_AU_FORM_LOAN_SUBMIT")?></span>
                            </span>
                        </label>
					</div>
					<a class="alert_up__close ico ico--close"></a>
				</form>


			</div>

			<div class="alert_up__window alert_up__window--calc-geo">

				<form class="alert_up__box" novalidate autocomplete="off" id="form-calculator-mobile">
					<?=bitrix_sessid_post()?>
					<h3><?=Loc::getMessage("F_AU_FORM_CALC_GEO_TITLE")?></h3>
					<?if($AR_REGIONS_AND_CITIES):?>
						<div class="input-line">
							<select name="REGION_ID">
								<option value="none"><?=Loc::getMessage("F_AU_FORM_CALC_GEO_REGION")?></option>
								<?foreach($AR_REGIONS_AND_CITIES as $key => $value):?>
								<option value="<?=$key?>"<?=(isset(AB_S1::$GEODATA['REGION_ID'])&&AB_S1::$GEODATA['REGION_ID']==$key?' selected':'')?>><?=$value['NAME']?></option>
								<?endforeach;?>
							</select>
						</div>
						<div class="input-line">
							<select name="CITY_ID">
								<option value="none"><?=Loc::getMessage("F_AU_FORM_CALC_GEO_CITY")?></option>
								<?if(isset(AB_S1::$GEODATA['REGION_ID']) && $AR_REGIONS_AND_CITIES[AB_S1::$GEODATA['REGION_ID']]['CITIES']):?>
									<?foreach($AR_REGIONS_AND_CITIES[AB_S1::$GEODATA['REGION_ID']]['CITIES'] as $key => $value):?>
										<option value="<?=$key?>"<?=(isset(AB_S1::$GEODATA['CITY_ID'])&&AB_S1::$GEODATA['CITY_ID']==$key?' selected':'')?>><?=$value['NAME']?></option>
									<?endforeach;?>
								<?endif;?>
							</select>
						</div>
						<div class="input-line">
							<select name="OFFICE_ID">
								<option value="none"><?=Loc::getMessage("F_AU_FORM_CALC_GEO_OFFICE")?></option>
								<?if(AB_S1::$GEODATA['OFFICES']):?>
									<?foreach(AB_S1::$GEODATA['OFFICES'] as $key => $value):?>
									<option value="<?=$key?>"<?=(isset(AB_S1::$GEODATA['OFFICE_ID'])&&AB_S1::$GEODATA['OFFICE_ID']==$key?' selected':'')?>><?=$value['ADDRESS']?></option>
									<?endforeach;?>
								<?endif;?>
							</select>
						</div>
						<div class="input-line">
							<select name="LOAN_VIEW" class="calculator__btn-toggle">
								<option value="none"><?=Loc::getMessage("F_AU_FORM_CALC_GEO_STATUS")?></option>
								<?if(AB_S1::$GEODATA['LOAN_VIEWS']):?>
									<?foreach(AB_S1::$GEODATA['LOAN_VIEWS'] as $key => $value):
									 echo '<option value="'.$key.'" data-loan-view-uid="'.$value['LOAN_UID'].'" data-stock-uid="'.$value['STOCK_UID'].'" data-rate="'.$value['C_DLV_RATE']['VALUE'].'" data-rate-stock="'.$value['C_DLV_STOCK_RATE']['VALUE'].'" data-time-min="'.$value['C_DLV_TIME_FROM']['VALUE'].'" data-time-max="'.$value['C_DLV_TIME_TO']['VALUE'].'" data-sum-min="'.$value['C_DLV_SUM_FROM']['VALUE'].'" data-sum-max="'.$value['C_DLV_SUM_TO']['VALUE'].'">'.$value['NAME'].'</option>';
									endforeach;?>
								<?endif;?>
							</select>
						</div>
						<div class="input-line input-line--submit">
							<label class="btn btn--red"><?=Loc::getMessage("F_AU_FORM_CALC_GEO_SUBMIT")?><input type="submit"></label>
						</div>
					<?endif;?>
					<a class="alert_up__close ico ico--close"></a>
				</form>

			</div>

			<?if(AB_S1::$GEODATA['GEO_EXIST'] == 1 && AB_S1::$GEODATA['GEO_ACCEPT'] == 0 && AB_S1::$GEODATA['OFF_GEOCITY_WINDOW'] != 1):?>
			<div class="alert_up__window alert_up__window--geo-accept">

				<form class="alert_up__box" novalidate autocomplete="off">
					<?=bitrix_sessid_post()?>
					<h3><?=Loc::getMessage("F_AU_FORM_ACCEPT_GEO_TITLE")?></h3>
					<p><?=Loc::getMessage("F_AU_FORM_ACCEPT_GEO_CITY")?> <?=AB_S1::$GEODATA['CITY_NAME']?>?</p>
					<div class="input-line input-line--submit">
						<a class="btn" data-city-id="<?=AB_S1::$GEODATA['CITY_ID']?>" id="accept-geocity"><?=Loc::getMessage("F_AU_FORM_ACCEPT_GEO_YES")?></a>
						<a class="btn-alert_up btn btn--gray" data-alert-up="geo"><?=Loc::getMessage("F_AU_FORM_ACCEPT_GEO_NO")?></a>
					</div>
					<a class="alert_up__close ico ico--close alert_up__geo-window-close"></a>
				</form>

			</div>
			<script>
				showAlertUp('geo-accept');
			</script>
			<?elseif(AB_S1::$GEODATA['GEO_EXIST'] == 0 && AB_S1::$GEODATA['OFF_GEOCITY_WINDOW'] != 1/* && !AB_S1::isBot()*/):?>
				<script>
					showAlertUp('geo');
				</script>
			<?endif;?>

			<?if($AR_REGIONS_AND_CITIES):?>
			<div class="alert_up__window alert_up__window--geo">

				<form class="alert_up__box" novalidate autocomplete="off" id="form-set-geolocation">
					<?=bitrix_sessid_post()?>
					<h3><?=Loc::getMessage("F_AU_FORM_GEO_TITLE")?></h3>
					<div class="input-line">
						<select name="REGION_ID">
							<option value="none"><?=Loc::getMessage("F_AU_FORM_GEO_REGION")?></option>
							<?foreach($AR_REGIONS_AND_CITIES as $key => $value):?>
							<option value="<?=$key?>"<?=(isset(AB_S1::$GEODATA['REGION_ID'])&&AB_S1::$GEODATA['REGION_ID']==$key?' selected':'')?>><?=$value['NAME']?></option>
							<?endforeach;?>
						</select>
					</div>
					<div class="input-line">
						<select name="CITY_ID"<?=(!isset(AB_S1::$GEODATA['REGION_ID']) || !$AR_REGIONS_AND_CITIES[AB_S1::$GEODATA['REGION_ID']]['CITIES']?' disabled="disabled"':'')?>>
							<option value="none"><?=Loc::getMessage("F_AU_FORM_GEO_CITY")?></option>
							<?if(isset(AB_S1::$GEODATA['REGION_ID']) && $AR_REGIONS_AND_CITIES[AB_S1::$GEODATA['REGION_ID']]['CITIES']):?>
								<?foreach($AR_REGIONS_AND_CITIES[AB_S1::$GEODATA['REGION_ID']]['CITIES'] as $key => $value):?>
									<option value="<?=$key?>"<?=(isset(AB_S1::$GEODATA['CITY_ID'])&&AB_S1::$GEODATA['CITY_ID']==$key?' selected':'')?>><?=$value['NAME']?></option>
								<?endforeach;?>
							<?endif;?>
						</select>
					</div>
					<div class="input-line input-line--submit">
						<label class="btn btn--red"><?=Loc::getMessage("F_AU_FORM_GEO_SUBMIT")?><input type="submit"></label>
					</div>
					<a class="alert_up__close ico ico--close alert_up__geo-window-close"></a>
				</form>

			</div>
			<?endif;?>

		</div><!-- alert__up -->

	</div>

	<!-- Yandex.Metrika counter -->
	<script>
	    (function (d, w, c) {
	        (w[c] = w[c] || []).push(function() {
	            try {
	                w.yaCounter45766392 = new Ya.Metrika({
	                    id:45766392,
	                    clickmap:true,
	                    trackLinks:true,
	                    accurateTrackBounce:true,
	                    webvisor:true
	                });
	            } catch(e) { }
	        });

	        var n = d.getElementsByTagName("script")[0],
	            s = d.createElement("script"),
	            f = function () { n.parentNode.insertBefore(s, n); };
	        s.type = "text/javascript";
	        s.async = true;
	        s.src = "https://mc.yandex.ru/metrika/watch.js";

	        if (w.opera == "[object Opera]") {
	            d.addEventListener("DOMContentLoaded", f, false);
	        } else { f(); }
	    })(document, window, "yandex_metrika_callbacks");
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/45766392" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex.Metrika counter -->

</body>
</html>