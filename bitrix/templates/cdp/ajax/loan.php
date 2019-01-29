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

if( isset($_REQUEST["AJAX_LOAN"]) && $_REQUEST["AJAX_LOAN"] == "Y" ) {

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

			$FIRST_NAME = trim(htmlspecialchars($USER_FIRST_NAME));
			$LAST_NAME = trim(htmlspecialchars($USER_LAST_NAME));
			$MIDDLE_NAME = trim(htmlspecialchars($USER_MIDDLE_NAME));
			$BIRTHDAY = trim(htmlspecialchars($USER_BIRTHDAY));
			$USER_PHONE = trim(htmlspecialchars($USER_PHONE));
			$USER_EMAIL = trim(htmlspecialchars($USER_EMAIL));
			$OFFICE_ID = intval($OFFICE_ID);
			$OFFICE_NAME = trim(htmlspecialchars($OFFICE_NAME));
			$LOAN_VIEW = intval($LOAN_VIEW);
			$LOAN_NAME = trim(htmlspecialchars($LOAN_NAME));
			$LOAN_VIEW_UID = trim(htmlspecialchars($LOAN_VIEW_UID));
			$LOAN_STOCK_UID = trim(htmlspecialchars($LOAN_STOCK_UID));
			$LOAN_SUM = trim(htmlspecialchars($LOAN_SUM));
			$LOAN_TIME = trim(htmlspecialchars($LOAN_TIME));


			$arError = Array();

			if(strlen($FIRST_NAME) == 0) {
				$arError[] = Array("FIELD" => "FIRST_NAME", "MESSAGE" => "Вы не указали имя");
			}

			if(strlen($LAST_NAME) == 0) {
				$arError[] = Array("FIELD" => "LAST_NAME", "MESSAGE" => "Вы не указали фамилию");
			}

			if(strlen($MIDDLE_NAME) == 0) {
				$arError[] = Array("FIELD" => "MIDDLE_NAME", "MESSAGE" => "Вы не указали отчество");
			}

			if(strlen($BIRTHDAY) == 0) {
				$arError[] = Array("FIELD" => "BIRTHDAY", "MESSAGE" => "Вы не указали дату рождения");
			}

			if(strlen($USER_PHONE) == 0) {
				$arError[] = Array("FIELD" => "PHONE", "MESSAGE" => "Не указан телефон");
			}

			if(strlen($USER_EMAIL) == 0) {
				$arError[] = Array("FIELD" => "EMAIL", "MESSAGE" => "Не указан E-mail");
			}
			elseif(strlen($USER_EMAIL) > 0 && !filter_var($USER_EMAIL, FILTER_VALIDATE_EMAIL))
				$arError[] = Array("FIELD" => "EMAIL", "MESSAGE" => "E-mail заполнен некорректно");

			if(!$arError) {

				$USER_NAME = $LAST_NAME . " " . $FIRST_NAME . " " . $MIDDLE_NAME;

				$sElementName = "Заявка №#LOAN_NUMBER# от ".date("d.m.Y H:i:s");

				$arIblockFields = Array(
					"IBLOCK_ID" => 2,
					"ACTIVE" => "Y",
					"NAME" => $sElementName,
					"DATE_ACTIVE_FROM" => ConvertTimeStamp(time(), "FULL"),
					"PROPERTY_VALUES" => Array(
						"CDP_LA_LOAN_NAME" => $LOAN_NAME,
						"CDP_LA_LOAN_VIEW_UID" => $LOAN_VIEW_UID,
						"CDP_LA_STOCK_UID" => $LOAN_STOCK_UID ? $LOAN_STOCK_UID : "",
						"CDP_LA_CML_LINK_OFFICE" => $OFFICE_ID,
						"CDP_LA_SUM" => str_replace(" ", "", $LOAN_SUM),
						"CDP_LA_TIME" => $LOAN_TIME,
						"CDP_LA_NAME" => $USER_NAME,
						"CDP_LA_BIRTHDAY" => $BIRTHDAY,
						"CDP_LA_PHONE" => $USER_PHONE,
						"CDP_LA_EMAIL" => $USER_EMAIL,
						"CDP_LA_STATUS" => 1
					)
				);

				$CIBlockElement = new CIBlockElement;

				$LOAN_ID = $CIBlockElement->Add($arIblockFields);

				if(intval($LOAN_ID) > 0) {

					$CIBlockElement->Update($LOAN_ID, Array("NAME" => str_replace("#LOAN_NUMBER#", $LOAN_ID, $sElementName)));

					$arSendFields = Array(
						"NAME" => $USER_NAME,
						"BIRTHDAY" => $BIRTHDAY,
						"PHONE" => $USER_PHONE,
						"EMAIL" => $USER_EMAIL,
						"LOAN_NUMBER" => $LOAN_ID,
						"OFFICE_NAME" => $OFFICE_NAME,
						"LOAN_NAME" => $LOAN_NAME,
						"LOAN_SUM" => $LOAN_SUM . " руб.",
						"LOAN_TIME" => $LOAN_TIME . " " . AB::declOfNum($LOAN_TIME, Array("день", "дня", "дней")),
					);

					CEvent::Send("USER_NEW_LOAN", SITE_ID, $arSendFields);

					echo json_encode(Array("SUCCESS" => 1, "MESSAGE" => "Заявка #".$LOAN_ID." принята. Наш сотрудник свяжется с вами в ближайшее время."));

				}
				else
					echo json_encode(Array("ERROR" => 1, "MESSAGE" => $CIBlockElement->LAST_ERROR));
			}
			else
				echo json_encode(Array("ERROR" => 1, "MESSAGE" => $arError));
		}

	}
	else
		echo json_encode(Array("ERROR" => 1, "MESSAGE" => "Мне кажется, что вы робот", "CAPTCHA" => 1));
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");

die();