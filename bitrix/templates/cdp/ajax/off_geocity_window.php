<?
	define("NO_KEEP_STATISTIC", true);
	define('BX_SESSION_ID_CHANGE', false);
	define('NO_AGENT_CHECK', true);

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

	/*if(!check_bitrix_sessid()) {
		echo json_encode(array("ERROR" => 1, "MESSAGE" => "Неверный идентификатор сессии"));
		die();
	}*/

	if(!isset($_REQUEST['OFF_GEOCITY_WINDOW']) && !intval($_REQUEST['OFF_GEOCITY_WINDOW'])) {
		echo json_encode(array("ERROR" => 1, "MESSAGE" => "Не указан тип операции"));
		die();
	}

	if( CModule::IncludeModule("main") ) {

		global $APPLICATION;

		$APPLICATION->set_cookie("OFF_GEOCITY_WINDOW", 1, COOKIE_LIFETIME, "/", SITE_SERVER_NAME);

		echo json_encode(array("SUCCESS" => 1, "MESSAGE" => "Всплывающее окно принудительного выбора геолокации отключено"));

	}
	else {
		echo json_encode(array("ERROR" => 1, "MESSAGE" => "Необходимые модули не подключены"));
	}

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");

	die();