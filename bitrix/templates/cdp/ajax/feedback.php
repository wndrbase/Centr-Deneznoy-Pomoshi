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


if( isset($_REQUEST["AJAX_CONTACTS_FEEDBACK"]) && $_REQUEST["AJAX_CONTACTS_FEEDBACK"] == "Y" ) {

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
			$USER_EMAIL = trim(htmlspecialchars($USER_EMAIL));
			$USER_PHONE = trim(htmlspecialchars($USER_PHONE));
			$USER_CITY = trim(htmlspecialchars($USER_CITY));
			$USER_SUBJECT = intval($USER_SUBJECT);
			$USER_MESSAGE = trim(htmlspecialchars($USER_MESSAGE));

			$arSubjects = AB_S1::getFeedbackFormSubjects();

			$arError = Array();

			if(strlen($USER_NAME) == 0) {
				$arError[] = Array("FIELD" => "USER_NAME", "MESSAGE" => "Вы не указали имя");
			}

			if(strlen($USER_EMAIL) == 0 && strlen($USER_PHONE) == 0) {
				$arError[] = Array("FIELD" => "USER_EMAIL", "MESSAGE" => "E-mail не заполнен");
				$arError[] = Array("FIELD" => "USER_PHONE", "MESSAGE" => "Не указан телефон");
			}
			elseif(strlen($USER_EMAIL) > 0 && !filter_var($USER_EMAIL, FILTER_VALIDATE_EMAIL))
				$arError[] = Array("FIELD" => "USER_EMAIL", "MESSAGE" => "E-mail заполнен некорректно");

			if(strlen($USER_MESSAGE) == 0) {
				$arError[] = Array("FIELD" => "USER_MESSAGE", "MESSAGE" => "Вы не написали сообщение");
			}

			if(strlen($USER_CITY) == 0) {
				$arError[] = Array("FIELD" => "USER_CITY", "MESSAGE" => "Вы не указали город");
			}

			if(!$arError) {

				if($_REQUEST['USER_FILES_PATH']) {

					$arAttachedFiles = Array();

					foreach($_REQUEST['USER_FILES_PATH'] as &$f) {
						$f = trim(htmlspecialchars($f));
						if(file_exists($_SERVER["DOCUMENT_ROOT"].$f))
							$arAttachedFiles[] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].$f);
					}

				}

				$CIBlockElement = new CIBlockElement;

				$PROPERTY_VALUES = Array(
					'CDP_FFR_NAME' => $USER_NAME,
					'CDP_FFR_PHONE' => $USER_PHONE ? $USER_PHONE : "&mdash;",
					'CDP_FFR_EMAIL' => $USER_EMAIL ? $USER_EMAIL : "&mdash;",
					'CDP_FFR_CITY' => $USER_CITY,
					'CDP_FFR_SUBJECT' => $USER_SUBJECT && isset($arSubjects[$USER_SUBJECT]) ? $arSubjects[$USER_SUBJECT] : "&mdash;",
				);

				if($arAttachedFiles)
					$PROPERTY_VALUES['CDP_FFR_ATTACH'] = $arAttachedFiles;

				$FORM_MESS_ID = $CIBlockElement->Add(
					Array(
						'NAME' => 'Сообщение формы обратной связи',
						'IBLOCK_ID' => '15',
						'ACTIVE' => 'Y',
						'PREVIEW_TEXT' => $USER_MESSAGE,
						'PROPERTY_VALUES' => $PROPERTY_VALUES
					)
				);

				if(intval($FORM_MESS_ID) > 0) {

					$CIBlockElement->Update($FORM_MESS_ID, Array('NAME' => 'Сообщение формы обратной связи #'.intval($FORM_MESS_ID)));

					$arSendFields = Array(
						"NAME" => $USER_NAME,
						"PHONE" => $USER_PHONE ? $USER_PHONE : "&mdash;",
						"EMAIL" => $USER_EMAIL ? $USER_EMAIL : "&mdash;",
						"CITY" => $USER_CITY,
						"SUBJECT" => $USER_SUBJECT && isset($arSubjects[$USER_SUBJECT]) ? $arSubjects[$USER_SUBJECT] : "&mdash;",
						"MESSAGE" => $USER_MESSAGE,
						"ID" => intval($FORM_MESS_ID)
					);

					CEvent::Send("USER_FEEDBACK", SITE_ID, $arSendFields, "Y", "", $_REQUEST['USER_FILES_PATH']);

				}
				else {
					echo json_encode(Array("ERROR" => 1, "MESSAGE" => $CIBlockElement->LAST_ERROR));
					die();
				}

				echo json_encode(Array("SUCCESS" => 1, "MESSAGE" => "Сообщение отправлено. Наш сотрудник ответит Вам в ближайшее время."));
			}
			else
				echo json_encode(Array("ERROR" => 1, "MESSAGE" => $arError));
		}

	}
	else echo json_encode(Array("ERROR" => 1, "MESSAGE" => "Мне кажется, что вы робот", "CAPTCHA" => 1));
}

die();