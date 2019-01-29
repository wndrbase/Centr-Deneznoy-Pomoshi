<?
define("NO_KEEP_STATISTIC", true);
define('BX_SESSION_ID_CHANGE', false);
define('NO_AGENT_CHECK', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

/*if(!check_bitrix_sessid()) {
	echo json_encode(Array("ERROR" => 1, "MESSAGE" => 'Неверный идентификатор сессии'));
	die();
}*/


if( isset($_REQUEST["AJAX_RENT"]) && $_REQUEST["AJAX_RENT"] == "Y" ) {

	if( CModule::IncludeModule("main") && CModule::IncludeModule("iblock") ) {

		extract($_REQUEST);

		$USER_PHONE = trim(htmlspecialchars($USER_PHONE));
		$USER_CITY = trim(htmlspecialchars($USER_CITY));
		$USER_ADDRESS = trim(htmlspecialchars($USER_ADDRESS));
		$USER_SQUARE = trim(htmlspecialchars($USER_SQUARE));
		$USER_MESSAGE = trim(htmlspecialchars($USER_MESSAGE));

		$arError = Array();

		if(strlen($USER_PHONE) == 0) {
			$arError[] = Array("FIELD" => "USER_PHONE", "MESSAGE" => "Вы не указали телефон");
		}

		if(strlen($USER_CITY) == 0) {
			$arError[] = Array("FIELD" => "USER_CITY", "MESSAGE" => "Вы не указали город");
		}

		if(strlen($USER_ADDRESS) == 0) {
			$arError[] = Array("FIELD" => "USER_ADDRESS", "MESSAGE" => "Вы не указали адрес");
		}

		if(strlen($USER_SQUARE) == 0) {
			$arError[] = Array("FIELD" => "USER_SQUARE", "MESSAGE" => "Вы не указали площадь помещения");
		}
		
		if(strlen($USER_MESSAGE) == 0) {
			$arError[] = Array("FIELD" => "USER_MESSAGE", "MESSAGE" => "Вы не написали сообщение");
		}

		if(!$arError) {

			$arSendFields = Array(
				"PHONE" => $USER_PHONE,
				"ADDRESS" => $USER_ADDRESS,
				"CITY" => $USER_CITY,
				"SQUARE" => $USER_SQUARE,
				"MESSAGE" => $USER_MESSAGE
			);

			CEvent::Send("USER_RENT", SITE_ID, $arSendFields);

			echo json_encode(Array("SUCCESS" => 1, "MESSAGE" => "Сообщение отправлено. Наш сотрудник ответит Вам в ближайшее время."));
		}
		else
			echo json_encode(Array("ERROR" => 1, "MESSAGE" => $arError));
	}
}

die();