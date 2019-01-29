<?
define("NO_KEEP_STATISTIC", true);
define('BX_SESSION_ID_CHANGE', false);
define('NO_AGENT_CHECK', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

/*if(!check_bitrix_sessid()) {
	echo json_encode(Array("ERROR" => 1, "MESSAGE" => 'Неверный идентификатор сессии'));
	die();
}*/


if( isset($_REQUEST["AJAX_JOB"]) && $_REQUEST["AJAX_JOB"] == "Y" ) {

	if( CModule::IncludeModule("main") && CModule::IncludeModule("iblock") ) {

		extract($_REQUEST);

		$USER_NAME = trim(htmlspecialchars($USER_NAME));
		$USER_PHONE = trim(htmlspecialchars($USER_PHONE));
		$USER_JOB_NAME = trim(htmlspecialchars($USER_JOB_NAME));
		$USER_JOB_CITY = trim(htmlspecialchars($USER_JOB_CITY));
		$USER_RESUME_FILE = $_SERVER["DOCUMENT_ROOT"].trim(htmlspecialchars($USER_RESUME_FILE));

		/*echo json_encode(Array("ERROR" => 1, "MESSAGE" => $USER_RESUME_FILE));
		die();*/

		$arError = Array();

		if(strlen($USER_NAME) == 0) {
			$arError[] = Array("FIELD" => "USER_NAME", "MESSAGE" => "Вы не указали имя");
		}

		if(strlen($USER_PHONE) == 0) {
			$arError[] = Array("FIELD" => "USER_PHONE", "MESSAGE" => "Вы не указали телефон");
		}
		
		if(strlen($USER_RESUME_FILE) == 0 || !is_file($USER_RESUME_FILE)) {
			$arError[] = Array("FIELD" => "USER_RESUME_FILE", "MESSAGE" => "Не загружен файл резюме");
		}		

		if(!$arError) {

			$arSendFields = Array(
				"NAME" => $USER_NAME,
				"PHONE" => $USER_PHONE,
				"JOB_NAME" => $USER_JOB_NAME,
				"JOB_CITY" => $USER_JOB_CITY
			);

			CEvent::Send("USER_JOB", SITE_ID, $arSendFields, "Y", "", Array($USER_RESUME_FILE));

			echo json_encode(Array("SUCCESS" => 1, "MESSAGE" => "Ваши данные отправлены. Наш сотрудник свяжется с Вами в ближайшее время."));
		}
		else
			echo json_encode(Array("ERROR" => 1, "MESSAGE" => $arError));
	}
}

die();