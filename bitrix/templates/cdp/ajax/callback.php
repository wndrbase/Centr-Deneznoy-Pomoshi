<?
define("NO_KEEP_STATISTIC", true);
define('BX_SESSION_ID_CHANGE', false);
define('NO_AGENT_CHECK', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/classes/recaptchalib.php");

/*if(!check_bitrix_sessid()) {
	echo json_encode(Array("ERROR" => 1, "MESSAGE" => 'Неверный идентификатор сессии'));
	die();
}*/

if( isset($_REQUEST["AJAX_ORDER_CALLBACK"]) && $_REQUEST["AJAX_ORDER_CALLBACK"] == "Y" ) {

	if(USE_GOOGLE_CAPTCHA_V2) {

		// пустой ответ
		$response = null;
		// проверка секретного ключа
		$reCaptcha = new ReCaptcha(GOOGLE_CAPTCHA_V2_SECRET_CODE);

		// Was there a reCAPTCHA response?
		if ($_REQUEST["g-recaptcha-response"]) {
		    $response = $reCaptcha->verifyResponse(
		        $_SERVER["REMOTE_ADDR"],
		        $_REQUEST["g-recaptcha-response"]
		    );
		}

	}

	if ( ($response != null && $response->success && USE_GOOGLE_CAPTCHA_V2) || !USE_GOOGLE_CAPTCHA_V2 )  {

		if( CModule::IncludeModule("main") && CModule::IncludeModule("iblock") ) {

			extract($_REQUEST);

			$USER_NAME = trim(htmlspecialchars($USER_NAME));
			$USER_PHONE = trim(htmlspecialchars($USER_PHONE));
			$USER_CITY = trim(htmlspecialchars($USER_CITY));


			$arError = Array();

			if(strlen($USER_NAME) == 0) {
				$arError[] = Array("FIELD" => "USER_NAME", "MESSAGE" => "Вы не указали имя");
			}

			if(strlen($USER_PHONE) == 0) {
				$arError[] = Array("FIELD" => "USER_PHONE", "MESSAGE" => "Не указан телефон");
			}

			if(strlen($USER_CITY) == 0) {
				$arError[] = Array("FIELD" => "USER_CITY", "MESSAGE" => "Не указан город");
			}

			if(!$arError) {

				$arSendFields = Array(
					"NAME" => $USER_NAME,
					"PHONE" => $USER_PHONE,
					"CITY" => $USER_CITY,
				);

				CEvent::Send("USER_CALLBACK", SITE_ID, $arSendFields);

				echo json_encode(Array("SUCCESS" => 1, "MESSAGE" => "Сообщение отправлено. Наш сотрудник свяжется с вами в ближайшее время."));
			}
			else
				echo json_encode(Array("ERROR" => 1, "MESSAGE" => $arError));
		}

	}
	else echo json_encode(Array("ERROR" => 1, "MESSAGE" => "Мне кажется, что вы робот", "CAPTCHA" => 1));

}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");

die();