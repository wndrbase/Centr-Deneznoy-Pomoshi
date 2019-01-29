<?
	define("NO_KEEP_STATISTIC", true);
	define('BX_SESSION_ID_CHANGE', false);
	define('NO_AGENT_CHECK', true);

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

	/*if(!check_bitrix_sessid()) {
		echo "Неверный идентификатор сессии";
		die();
	}*/

	if(isset($_REQUEST['AJAX_GET_OFFICES_ADDRESS']) && $_REQUEST['AJAX_GET_OFFICES_ADDRESS'] == "Y") {

		if(!intval($_REQUEST['CITY_ID'])) {
			echo 'Не установлен идентификатор города';
			die();
		}

		if( CModule::IncludeModule("main") && CModule::IncludeModule("iblock")  ) {

			$CIBlockElement = new CIBlockElement;

			$rsOffices = $CIBlockElement->GetList(Array(), Array("IBLOCK_ID" => 1, "SECTION_ID" => intval($_REQUEST['CITY_ID']), "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y"), false, false, Array("ID", "NAME", "PROPERTY_CDP_G_OFFICE_PLACEMARK", "PROPERTY_CDP_G_OFFICE_ADDRESS", "PROPERTY_CDP_G_OFFICE_PHONE", "PROPERTY_CDP_G_OFFICE_SCHEDULE"));
			while($arOffice = $rsOffices->Fetch()) {
				if($arOffice['PROPERTY_CDP_G_OFFICE_PLACEMARK_VALUE']['TEXT']) {

					$sSchedule = '';

					if($arOffice['PROPERTY_CDP_G_OFFICE_SCHEDULE_VALUE']) {
						$sSchedule = '
						<div class="offices__line">
							<span class="offices__line-label">График работы</span>
							<span class="offices__line-value">'.implode('<br>', AB_S1::getFormatSchedule(unserialize($arOffice['PROPERTY_CDP_G_OFFICE_SCHEDULE_VALUE']['TEXT']))).'</span>
						</div>
						';
					}

					echo '<li class="offices__item" data-ll="'.$arOffice['PROPERTY_CDP_G_OFFICE_PLACEMARK_VALUE'].'">
						<div class="offices__line">
							<span class="offices__line-label">Телефон</span>
							<span class="offices__line-value">'.$arOffice['PROPERTY_CDP_G_OFFICE_PHONE_VALUE'].'</span>
						</div>
						<div class="offices__line">
							<span class="offices__line-label">Адрес</span>
							<span class="offices__line-value">'.htmlspecialchars_decode($arOffice['PROPERTY_CDP_G_OFFICE_ADDRESS_VALUE']).'</span>
						</div>' . $sSchedule . '
					</li>';

				}
			}

		}
		else {
			echo "Необходимые модули не подключены";
		}

	}

	die();

?>