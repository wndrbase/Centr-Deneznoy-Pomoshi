<?
	define("NO_KEEP_STATISTIC", true);
	define('BX_SESSION_ID_CHANGE', false);
	define('NO_AGENT_CHECK', true);

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

	/*if(!check_bitrix_sessid()) {
		echo json_encode(array("ERROR" => 1, "MESSAGE" => "Неверный идентификатор сессии"));
		die();
	}*/

	if(!isset($_REQUEST['CITY_ID']) && !intval($_REQUEST['CITY_ID'])) {
		echo json_encode(array("ERROR" => 1, "MESSAGE" => "Не указан идентификатор города"));
		die();
	}

	if( CModule::IncludeModule("main") && CModule::IncludeModule("iblock") ) {

		extract($_REQUEST);
		$CITY_ID = intval($CITY_ID);

		if($OFFICE_ID)
			$OFFICE_ID = intval($OFFICE_ID);

		if($CITY_ID == 0) {
			echo json_encode(array("SUCCESS" => 1, "MESSAGE" => "Окно закрыто", "WINDOW_CLOSE" => 1));
			die();
		}

		global $APPLICATION;

		if($arCity = CIBlockSection::GetList(Array("NAME" => "ASC"), Array("IBLOCK_ID" => 1, "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y", "ID" => $CITY_ID), false, Array("ID", "IBLOCK_ID", "NAME", "IBLOCK_SECTION_ID"/*, "PICTURE"*/))->Fetch()) {

			/*if($arCity['PICTURE'])
                $APPLICATION->set_cookie("INDEX_PAGE_MAIN_PICTURE", CFile::GetFileArray($arCity['PICTURE'])["SRC"], COOKIE_LIFETIME, "/", SITE_SERVER_NAME);*/

			if($arRegion = CIBlockSection::GetList(Array("NAME" => "ASC"), Array("IBLOCK_ID" => 1, "ACTIVE" => "Y", "ID" => $arCity["IBLOCK_SECTION_ID"]), false, Array("ID", "IBLOCK_ID", "NAME"/*, "PICTURE"*/))->Fetch()) {

				$APPLICATION->set_cookie("REGION_ID", $arRegion['ID'], COOKIE_LIFETIME, "/", SITE_SERVER_NAME);
                $APPLICATION->set_cookie("REGION_NAME", $arRegion['NAME'], COOKIE_LIFETIME, "/", SITE_SERVER_NAME);
                $APPLICATION->set_cookie("CITY_ID", $arCity['ID'], COOKIE_LIFETIME, "/", SITE_SERVER_NAME);
                $APPLICATION->set_cookie("CITY_NAME", $arCity['NAME'], COOKIE_LIFETIME, "/", SITE_SERVER_NAME);
                $APPLICATION->set_cookie("GEO_ACCEPT", 1, COOKIE_LIFETIME, "/", SITE_SERVER_NAME);

            	/*if($arRegion['PICTURE'] && !$arCity['PICTURE'])
                 	$APPLICATION->set_cookie("INDEX_PAGE_MAIN_PICTURE", CFile::GetFileArray($arRegion['PICTURE'])["SRC"], COOKIE_LIFETIME, "/", SITE_SERVER_NAME);

                 if(!$arRegion['PICTURE'] && !$arCity['PICTURE'])
                 	$APPLICATION->set_cookie("INDEX_PAGE_MAIN_PICTURE", "", time()-COOKIE_LIFETIME, "/", SITE_SERVER_NAME);*/

                //Получим все офисы в этом городе
                $rsOffices = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => 1, "ACTIVE" => "Y", "SECTION_ID" => $arCity['ID']/*, "!PROPERTY_GO_OFFICE_PLACEMARK" => false*/), false, false, Array("ID", "NAME", "PROPERTY_CDP_G_OFFICE_PLACEMARK", "PROPERTY_CDP_G_OFFICE_ADDRESS", "PROPERTY_CDP_G_OFFICE_PHONE"));
                $arOffices = Array();
                $nOneOfficeID = false;

                while ($arOffice = $rsOffices->Fetch()) {
					if($arOffice['PROPERTY_CDP_G_OFFICE_PLACEMARK_VALUE'])	{
                   		$arOffices[$arOffice['ID']]['ADDRESS'] = $arOffice['PROPERTY_CDP_G_OFFICE_ADDRESS_VALUE'];
                   		$arOffices[$arOffice['ID']]['PHONE'] = $arOffice['PROPERTY_CDP_G_OFFICE_PHONE_VALUE'];
                   		$arOffices[$arOffice['ID']]['NAME'] = $arOffice['NAME'];
					}
                   	$nOneOfficeID = $arOffice['ID'];
                }

                if($arOffices) {
                    $APPLICATION->set_cookie("OFFICES", serialize($arOffices), COOKIE_LIFETIME, "/", SITE_SERVER_NAME);
                	//Если офис всего 1, то ставим его как основной
                    if(count($arOffices) == 1)
                		$APPLICATION->set_cookie("OFFICE_ID", $nOneOfficeID, COOKIE_LIFETIME, "/", SITE_SERVER_NAME);
                }

                //Если офис был в запросе, то он приоритетней
                if($OFFICE_ID)
                	$APPLICATION->set_cookie("OFFICE_ID", $OFFICE_ID, COOKIE_LIFETIME, "/", SITE_SERVER_NAME);
                elseif(count($arOffices) != 1)
                	$APPLICATION->set_cookie("OFFICE_ID", "", time()-COOKIE_LIFETIME, "/", SITE_SERVER_NAME);

                if($OFFICE_ID || ($arOffices && count($arOffices) == 1))
					echo json_encode(array("SUCCESS" => 1, "MESSAGE" => "Город, область и офис успешно установлены"));
				else
					echo json_encode(array("SUCCESS" => 1, "MESSAGE" => "Город и область успешно установлены"));

			}
			else echo json_encode(array("ERROR" => 1, "MESSAGE" => "Такой области не существует"));


		}
		else echo json_encode(array("ERROR" => 1, "MESSAGE" => "Такого города не существует: ".$CITY_ID));

	}
	else {
		echo json_encode(array("ERROR" => 1, "MESSAGE" => "Необходимые модули не подключены"));
	}

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");

	die();