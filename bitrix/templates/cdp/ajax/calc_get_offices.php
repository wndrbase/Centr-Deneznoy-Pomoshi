<?
	define("NO_KEEP_STATISTIC", true);
	define('BX_SESSION_ID_CHANGE', false);
	define('NO_AGENT_CHECK', true);

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

	/*if(!check_bitrix_sessid()) {
		echo "Неверный идентификатор сессии";
		die();
	}*/

	if(isset($_REQUEST['AJAX_CALC_OFFICES']) && $_REQUEST['AJAX_CALC_OFFICES'] == "Y") {

		if(!intval($_REQUEST['CITY_ID'])) {
			echo 'Не установлен идентификатор города';
			die();
		}

		if( CModule::IncludeModule("main") && CModule::IncludeModule("iblock")  ) {

			$CIBlockElement = new CIBlockElement;

			$rsOffices = $CIBlockElement->GetList(Array(), Array("IBLOCK_ID" => 1, "SECTION_ID" => intval($_REQUEST['CITY_ID']),/* "!PROPERTY_GO_OFFICE_PLACEMARK" => false,*/ "ACTIVE" => "Y"), false, false, Array("ID", "NAME", "PROPERTY_CDP_G_OFFICE_ADDRESS"));

			while($arOffice = $rsOffices->Fetch()) {

				echo '<option value="'.$arOffice['ID'].'">'.$arOffice['PROPERTY_CDP_G_OFFICE_ADDRESS_VALUE'].'</option>';
			}

		}
		else {
			echo "Необходимые модули не подключены";		
		}

	}

	die();

?>