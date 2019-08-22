<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

	use Bitrix\Main\Page\Asset;
	use Bitrix\Main\Localization\Loc;

	Loc::loadMessages(__FILE__);

	CJSCore::Init(array("fx"));

	global $APPLICATION;
	global $USER;

	global $B_INDEX_PAGE;
	$B_INDEX_PAGE = $APPLICATION->GetCurDir() == "/" && $APPLICATION->GetCurPage() == "/" ? true : false;

	global $B_ABOUT_PAGE;
	$B_ABOUT_PAGE = $APPLICATION->GetCurDir() == "/about/" ? true : false;

    global $B_JOBS_PAGE;
    $B_JOBS_PAGE = \CSite::InDir('/jobs/');

	global $B_404_PAGE;
	$B_404_PAGE = http_response_code() == 404 ? true : false;

	global $AR_REGIONS_AND_CITIES;
	$AR_REGIONS_AND_CITIES = AB_S1::getRegionsAndCities();

	global $AR_CITIES;
	$AR_CITIES = AB_S1::getCities();

	if( !($sWrapperClass = $APPLICATION->GetProperty("WRAPPER_CLASS")) )
			$sWrapperClass = "";

	if( !($sBackgroundImage = $APPLICATION->GetProperty("BACKGROUND_IMAGE")) )
			$sBackgroundImage = false;

	if($B_INDEX_PAGE) {
		$sWrapperClass = "home";
	}
	elseif($B_404_PAGE) {
		$sWrapperClass = "p404";
	}

	global $FULL_VERSION;
	$FULL_VERSION = false;

	if($_GET['full_version'] == 'Y') {
		$APPLICATION->set_cookie("FULL_VERSION", 1, time() + 60 * 60 * 24, "/");
		LocalRedirect($APPLICATION->GetCurDir());
	}

	if($APPLICATION->get_cookie("FULL_VERSION") == 1)
		$FULL_VERSION = true;

	if($_GET['mobile_version'] == 'Y') {
		$APPLICATION->set_cookie("FULL_VERSION", 1, time() - 60 * 60 * 24, "/");
		$FULL_VERSION = false;
	}

?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
	<title><?=$APPLICATION->ShowTitle()?></title>
    
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="apple-touch-icon-precomposed" href="/favicon.png">
	<meta name="format-detection" content="telephone=no">
	<?if(!$FULL_VERSION):?>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<?else:?>
	<meta name="viewport" content="width=1200">
	<?endif;?>
	<meta name="theme-color" content="#2b57d4">
	<meta name="yandex-verification" content="e400b674d5ef7ce0" />
	<meta name="yandex-verification" content="5fd9c51302efbad1" />
	<meta name="google-site-verification" content="B8cumWZRHmyVA8gpj5WCl5hCuR5128tAksshiOjOiA4" />

	<?
	$APPLICATION->ShowHead();

	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/jquery.bxslider.css');
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/nouislider.css');
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/select2.css');
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/animate.css');
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/slick.css');
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/slick-theme.css');
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/default.css');
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/style.css');
	if(!$FULL_VERSION)
	Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/media.css');

	//Asset::getInstance()->addJs('//maps.googleapis.com/maps/api/js?key=AIzaSyBnvs1bUx7yGlAzjtOGBIGK2hQOiaue9rU&amp;callback=initMap');
	Asset::getInstance()->addJs('//api-maps.yandex.ru/2.1/?lang=ru_RU');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.min.js');
	//Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.ui.touch-punch.min.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.touchwipe.min.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/wNumb.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.bxslider.min.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/nouislider.min.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/slick.min.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/select2/select2.min.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/select2/i18n/ru.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.maskedinput.min.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/calculator.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/js.js');
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/add.js');
	?>

	<script>
		var SITE_TEMPLATE_PATH = '<?=SITE_TEMPLATE_PATH?>',
			DOCUMENT_DOMAIN = '<?=$_SERVER["HTTP_HOST"]?>',
			REGIONS_AND_CITIES = <?=CUtil::PhpToJSObject($AR_REGIONS_AND_CITIES)?>,
			CITIES = <?=CUtil::PhpToJSObject($AR_CITIES)?>,
			gMap,
			gMarkersArray = [];
	</script>
	<script>!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?159",t.onload=function(){VK.Retargeting.Init("VK-RTRG-295294-bxw3l"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script>

	<script>
		var widgetCallbackId,
			widgetFeedbackId,
			widgetLoanId;

		var onloadCallback = function() {

			if ( $('#g-recaptcha-callback').length ) {

				widgetCallbackId = grecaptcha.render(document.getElementById('g-recaptcha-callback'), {
					'sitekey' : '<?=GOOGLE_CAPTCHA_V2_SITE_CODE?>',
					'theme' : 'light'
				});

			}

			if ( $('#g-recaptcha-feedback').length ) {

				widgetFeedbackId = grecaptcha.render(document.getElementById('g-recaptcha-feedback'), {
					'sitekey' : '<?=GOOGLE_CAPTCHA_V2_SITE_CODE?>',
					'theme' : 'light'
				});

			}

			if ( $('#g-recaptcha-loan').length ) {

				widgetLoanId = grecaptcha.render(document.getElementById('g-recaptcha-loan'), {
					'sitekey' : '<?=GOOGLE_CAPTCHA_V2_SITE_CODE?>',
					'theme' : 'light'
				});

			}

		};
	</script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-144819268-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-144819268-1');
    </script>

    <script src="//maps.googleapis.com/maps/api/js?key=AIzaSyBnvs1bUx7yGlAzjtOGBIGK2hQOiaue9rU"></script>
	<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
</head>

<body<?=($FULL_VERSION?' class="full-version"':'')?>>
    <noscript><img src="https://vk.com/rtrg?p=VK-RTRG-295294-bxw3l" style="position:fixed; left:-999px;" alt=""/></noscript>

	<? $APPLICATION->ShowPanel();?>

	<script>!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?157",t.onload=function(){VK.Retargeting.Init("VK-RTRG-263899-fDTPi"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-263899-fDTPi" style="position:fixed; left:-999px;" alt=""/></noscript>

	<div class="frontend <?=$sWrapperClass?>">

		<header id="header" class='header<?if(strlen($sBackgroundImage) > 0):?> header--background<?endif;?> clr'>

			<div class="center header--center clr">

				<div>

					<div class="header__mobile-container clr">

						<a href="javascript:;" class="btn-alert_up header__geocity<?if(AB_S1::$GEODATA['GEO_EXIST']==1):?> header__geocity--selected<?endif;?>" data-alert-up="geo"><?=(AB_S1::$GEODATA['GEO_EXIST']==1?AB_S1::$GEODATA['CITY_NAME']:Loc::getMessage("H_BTN_SELECT_CITY"))?></a>

						<div class="header__phone">
							<?=Loc::getMessage("H_HOT_LINE_PHONE")?>
							<a href="tel:<?=preg_replace("/\D/", "", file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.SITE_TEMPLATE_PATH.'/include_areas/h_phone.php'))?>"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_TEMPLATE_PATH."/include_areas/h_phone.php", "EDIT_TEMPLATE" => "standard.php"), false);?></a>
						</div>

						<a href="javascript:;" class="menu-mobile-toggle__line visible-sm"><span></span></a>

						<a href="/" class="header__logo"></a>

						<nav class="header__nav clr">

							<div class="center clr">

								<?$APPLICATION->IncludeComponent(
									"bitrix:menu",
									"header",
									Array(
										"ALLOW_MULTI_SELECT" => "N",
										"CHILD_MENU_TYPE" => "top",
										"DELAY" => "N",
										"MAX_LEVEL" => "1",
										"MENU_CACHE_GET_VARS" => array(""),
										"MENU_CACHE_TIME" => "3600",
										"MENU_CACHE_TYPE" => "N",
										"MENU_CACHE_USE_GROUPS" => "Y",
										"ROOT_MENU_TYPE" => "top",
										"USE_EXT" => "N"
									)
								);?>

								<div class="header__btn-holder">

									<?if(!$B_INDEX_PAGE):?>
										<a href="/#calculator" class="btn header__apply-btn btn--red"><?=Loc::getMessage("H_BTN_SEND_LOAN")?></a>
									<?else:?>
										<a href="javascript:;" class="btn header__apply-btn btn--red header__apply-btn--calculator"><?=Loc::getMessage("H_BTN_SEND_LOAN")?></a>
									<?endif;?>

								</div>

							</div>

						</nav>

					</div>

				</div>

				<?if($B_INDEX_PAGE):?>

					<?$APPLICATION->IncludeComponent(
						"bitrix:news.list",
						"main-slider",
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
							"DISPLAY_PREVIEW_TEXT" => "Y",
							"DISPLAY_TOP_PAGER" => "N",
							"FIELD_CODE" => array("", ""),
							"FILTER_NAME" => "",
							"HIDE_LINK_WHEN_NO_DETAIL" => "N",
							"IBLOCK_ID" => "3",
							"IBLOCK_TYPE" => "cdp",
							"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
							"INCLUDE_SUBSECTIONS" => "Y",
							"MESSAGE_404" => "",
							"NEWS_COUNT" => "7",
							"PAGER_BASE_LINK_ENABLE" => "N",
							"PAGER_DESC_NUMBERING" => "N",
							"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
							"PAGER_SHOW_ALL" => "N",
							"PAGER_SHOW_ALWAYS" => "N",
							"PAGER_TEMPLATE" => ".default",
							"PAGER_TITLE" => "Слайды",
							"PARENT_SECTION" => "",
							"PARENT_SECTION_CODE" => "",
							"PREVIEW_TRUNCATE_LEN" => "",
							"PROPERTY_CODE" => array("CDP_S_LINK", "CDP_S_IMAGE_DESKTOP", "CDP_S_IMAGE_MOBILE"),
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

				<?endif;?>

				<?if(strlen($sBackgroundImage) > 0):?>
					<div class="header__bg clr">
						<div class="header__bg-content">
							<h1><?=$APPLICATION->ShowTitle(false)?></h1>
							<?$APPLICATION->IncludeComponent(
								"bitrix:breadcrumb",
								"breadcrumbs-header",
								Array(
									"PATH" => "",
									"SITE_ID" => "s1",
									"START_FROM" => "0"
								)
							);?>
						</div>
						<?/*if($sWrapperClass == 'page-reviews'):?>
							<div class="header__review-text">
								Большое спасибо компании за быстроту решения и помощь.Очень довольна. Выручили в трудную минуту,когда очень нужна была помощь.
							</div>
						<?endif;*/?>
						<img src="<?=$sBackgroundImage?>" alt="">
					</div>
				<?endif;?>

				<a href="javascript:;" class="btn-alert_up callback-order animated" data-alert-up="callback">
					<div class="callback-order__icon"></div>
					<div class="callback-order__text"><?=Loc::getMessage("H_BTN_CALLBACK")?></div>
				</a>

			</div>

		</header><!-- /header -->

		<main class="main<?if(strlen($sBackgroundImage)>0):?> main--pad<?endif;?><?if($B_ABOUT_PAGE || $B_JOBS_PAGE):?> main--no-pad-bottom<?endif;?>">

			<?if(strlen($sBackgroundImage) == 0 && !$B_INDEX_PAGE && $sWrapperClass != 'page-contacts' && !defined('ERROR_404')):?>
			<div class="center clr">
				<h1><?=$APPLICATION->ShowTitle(false)?></h1>
				<?$APPLICATION->IncludeComponent(
					"bitrix:breadcrumb",
					"breadcrumbs",
					Array(
						"PATH" => "",
						"SITE_ID" => "s1",
						"START_FROM" => "0"
					)
				);?>
			</div>
			<?endif;?>