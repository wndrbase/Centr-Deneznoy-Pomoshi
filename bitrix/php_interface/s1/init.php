<?
/*--------------------------------
   Developed by Andrey Bazykin (AB)
   Site: http://andreybazykin.com
   Skype: skaterfat
   E-mail: andreybazykin@gmail.com
   ~=Development of Dreams=~
 -------------------------------*/

use Bitrix\Main\EventManager;
use \Bitrix\Main\Service\GeoIp;

GeoIp\Manager::getRealIp();

EventManager::getInstance()->addEventHandler("main", "OnPageStart", Array("AB_S1", "OnPageStartHandler"));

class AB_S1 extends AB
{
	static $GEODATA;

	function OnPageStartHandler() {

		if(CModule::IncludeModule("altasib.geoip") && CModule::IncludeModule("iblock") && CModule::IncludeModule("main")) {

			global $APPLICATION;

			if( $nCityID = $APPLICATION->get_cookie("CITY_ID") ) {
				self::$GEODATA['CITY_ID'] = $nCityID;
			}

			if( $sCityName = $APPLICATION->get_cookie("CITY_NAME") ) {
				self::$GEODATA['CITY_NAME'] = $sCityName;
			}

			if( $nRegionID = $APPLICATION->get_cookie("REGION_ID") ) {
				self::$GEODATA['REGION_ID'] = $nRegionID;
			}

			if( $sRegionName = $APPLICATION->get_cookie("REGION_NAME") ) {
				self::$GEODATA['REGION_NAME'] = $sRegionName;
			}

			if( $sOffices = $APPLICATION->get_cookie("OFFICES") ) {
				self::$GEODATA['OFFICES'] = unserialize($sOffices);
			}

			if( $nOfficeID = $APPLICATION->get_cookie("OFFICE_ID") ) {
				self::$GEODATA['OFFICE_ID'] = $nOfficeID;
				self::$GEODATA['LOAN_VIEWS'] = self::getOfficeLoanViews($nOfficeID);
			}

			/*if( $sIndexMainPicture = $APPLICATION->get_cookie("INDEX_PAGE_MAIN_PICTURE") ) {
				self::$GEODATA['INDEX_PAGE_MAIN_PICTURE'] = $sIndexMainPicture;
			}*/

			if( $nGeoCityWindow = $APPLICATION->get_cookie("OFF_GEOCITY_WINDOW") ) {
				self::$GEODATA['OFF_GEOCITY_WINDOW'] = $nGeoCityWindow;
			}

			if(
				!isset(self::$GEODATA['CITY_ID']) ||
				!isset(self::$GEODATA['CITY_NAME']) ||
				!isset(self::$GEODATA['REGION_ID']) ||
				!isset(self::$GEODATA['REGION_NAME'])
			) {

				self::$GEODATA = ALX_GeoIP::GetAddr(GeoIp\Manager::getRealIp());
				if($nGeoCityWindow)
					self::$GEODATA['OFF_GEOCITY_WINDOW'] = $nGeoCityWindow;
				/*
				Array
				(
					[inetnum] => 176.117.112.0 - 176.117.127.255
					[country] => RU
					[city] => Дзержинский
					[region] => Московская область
					[district] => Центральный федеральный округ
					[lat] => 55.628078
					[lng] => 37.839806
				)
				 */
				//Если есть данные с HTML5 Geolocation, то перезапишем данные GeoIP
				if(($sGeolocationRegionName = $APPLICATION->get_cookie("GEOLOCATION_REGION_NAME")) && ($sGeolocationCityName = $APPLICATION->get_cookie("GEOLOCATION_CITY_NAME"))) {
					self::$GEODATA['region'] = $sGeolocationRegionName;
					self::$GEODATA['city'] = $sGeolocationCityName;
				}
				/*self::$GEODATA['region'] = "Архангельская область";
				self::$GEODATA['city'] = "Архангельск";
				print_r(self::$GEODATA);*/

				//Извлекаем область
				if($arRegion = CIBlockSection::GetList(Array("NAME" => "ASC"), Array("IBLOCK_ID" => 1, "ACTIVE" => "Y", "NAME" => self::$GEODATA['region']), false, Array("ID", "IBLOCK_ID", "NAME"/*, "PICTURE"*/))->Fetch()) {

					if($arRegion['PICTURE']) {
						self::$GEODATA['INDEX_PAGE_MAIN_PICTURE'] = CFile::GetFileArray($arRegion['PICTURE'])["SRC"];
					}

					//Извлекаем город (если город есть, записываем геолокацию в куки)
					if($arCity = CIBlockSection::GetList(Array("NAME" => "ASC"), Array("IBLOCK_ID" => 1, "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y", "SECTION_ID" => $arRegion['ID'], "NAME" => self::$GEODATA['city']), false, Array("ID", "IBLOCK_ID", "NAME"/*, "PICTURE"*/))->Fetch()) {

						/*if($arCity['PICTURE']) {
						}*/

						//Получим все офисы в этом городе
						$rsOffices = CIBlockElement::GetList(Array(), Array("IBLOCK_ID" => 1, "ACTIVE" => "Y", "SECTION_ID" => $arCity['ID']/*, "!PROPERTY_GO_OFFICE_PLACEMARK" => false*/), false, false, Array("ID", "NAME", "PROPERTY_CDP_G_OFFICE_PLACEMARK", "PROPERTY_CDP_G_OFFICE_ADDRESS", "PROPERTY_CDP_G_OFFICE_PHONE"));
						$arOffices = Array();
						$nOneOfficeID = false;

						while ($arOffice = $rsOffices->Fetch()) {
							if($arOffice['PROPERTY_CDP_G_OFFICE_PLACEMARK_VALUE']) {
								$arOffices[$arOffice['ID']]['NAME'] = $arOffice['NAME'];
								$arOffices[$arOffice['ID']]['ADDRESS'] = $arOffice['PROPERTY_CDP_G_OFFICE_ADDRESS_VALUE'];
								$arOffices[$arOffice['ID']]['PHONE'] = $arOffice['PROPERTY_CDP_G_OFFICE_PHONE_VALUE'];
							}
							$nOneOfficeID = $arOffice['ID'];
						}

						$APPLICATION->set_cookie("REGION_ID", $arRegion['ID'], COOKIE_LIFETIME, "/", SITE_SERVER_NAME);
						$APPLICATION->set_cookie("REGION_NAME", $arRegion['NAME'], COOKIE_LIFETIME, "/", SITE_SERVER_NAME);
						$APPLICATION->set_cookie("CITY_ID", $arCity['ID'], COOKIE_LIFETIME, "/", SITE_SERVER_NAME);
						$APPLICATION->set_cookie("CITY_NAME", $arCity['NAME'], COOKIE_LIFETIME, "/", SITE_SERVER_NAME);
						$APPLICATION->set_cookie("GEO_ACCEPT", 0, COOKIE_LIFETIME, "/", SITE_SERVER_NAME);

						self::$GEODATA = Array();

						self::$GEODATA['CITY_ID'] = $arCity['ID'];
						self::$GEODATA['CITY_NAME'] = $arCity['NAME'];
						self::$GEODATA['REGION_ID'] = $arRegion['ID'];
						self::$GEODATA['REGION_NAME'] = $arRegion['NAME'];
						self::$GEODATA['GEO_EXIST'] = 1;
						self::$GEODATA['GEO_ACCEPT'] = 0;

						if($arOffices) {
							$APPLICATION->set_cookie("OFFICES", serialize($arOffices), COOKIE_LIFETIME, "/", SITE_SERVER_NAME);
							self::$GEODATA['OFFICES'] = $arOffices;

							if(count($arOffices) == 1) {
								$APPLICATION->set_cookie("OFFICE_ID", $nOneOfficeID, COOKIE_LIFETIME, "/", SITE_SERVER_NAME);
							}

						}

						/*if($arCity['PICTURE'])
							self::$GEODATA['INDEX_PAGE_MAIN_PICTURE'] = CFile::GetFileArray($arCity['PICTURE'])["SRC"];
						elseif($arRegion['PICTURE'])
							self::$GEODATA['INDEX_PAGE_MAIN_PICTURE'] = CFile::GetFileArray($arRegion['PICTURE'])["SRC"];
						if(self::$GEODATA['INDEX_PAGE_MAIN_PICTURE'])
							$APPLICATION->set_cookie("INDEX_PAGE_MAIN_PICTURE", self::$GEODATA['INDEX_PAGE_MAIN_PICTURE'], COOKIE_LIFETIME, "/", SITE_SERVER_NAME);*/

					}
					else {
						self::$GEODATA['GEO_EXIST'] = 0;
					}
				}
				else {
					self::$GEODATA['GEO_EXIST'] = 0;
				}

			}
			else {
				self::$GEODATA['GEO_EXIST'] = 1;
				self::$GEODATA['GEO_ACCEPT'] = $APPLICATION->get_cookie("GEO_ACCEPT");
			}


		}//Модули

	}//OnPageStartHandler

	function getCities() {

		$obCache = new CPHPCache();
		$cacheLifetime = 36000000;
		$cacheID = 'CPD_Cities';
		$cachePath = '/' . $cacheID;

		if($obCache->startDataCache($cacheLifetime, $cacheID, $cachePath)) {

			$arCities = Array();

			$rsC = CIBlockSection::GetList(Array("NAME" => "ASC"), Array("IBLOCK_ID" => 1, "ACTIVE" => "Y", "DEPTH_LEVEL" => 2), false, Array("ID", "IBLOCK_ID", "NAME", "DEPTH_LEVEL", "IBLOCK_SECTION_ID"));
			while($arElement = $rsC->Fetch())
				$arCities[] = Array(
					"ID" => $arElement['ID'],
					"NAME" => $arElement['NAME']
				);

			$GLOBALS['CACHE_MANAGER']->StartTagCache($cachePath);
			$GLOBALS['CACHE_MANAGER']->RegisterTag('iblock_id_1');
			$GLOBALS['CACHE_MANAGER']->EndTagCache();
			$obCache->EndDataCache($arCities);

		}
		else {

			//Получаем данные из кеша
			$arCities = $obCache->GetVars();

		}

		return $arCities;

	}

	function getRegionsAndCities() {

		$obCache = new CPHPCache();
		$cacheLifetime = 36000000;
		$cacheID = 'CPD_RegionsAndCities';
		$cachePath = '/' . $cacheID;

		if($obCache->startDataCache($cacheLifetime, $cacheID, $cachePath)) {

			$arRegionsAndCities = Array();

			$arRC[] = Array();

			$rsRC = CIBlockSection::GetList(Array("NAME" => "ASC"), Array("IBLOCK_ID" => 1, "ACTIVE" => "Y"), false, Array("ID", "IBLOCK_ID", "NAME", "DEPTH_LEVEL", "IBLOCK_SECTION_ID"));
			while($arElement = $rsRC->Fetch()) {
				$arRC[] = $arElement;
			}

			foreach($arRC as $rc) {
				if($rc['DEPTH_LEVEL'] == 1) {
					$arRegionsAndCities[$rc['ID']] = Array(
						"ID" => $rc['ID'],
						"NAME" => $rc['NAME'],
						"CITIES" => Array()
					);
				}
			}

			foreach($arRC as $rc) {
				if($rc['DEPTH_LEVEL'] == 2) {
					$arRegionsAndCities[$rc['IBLOCK_SECTION_ID']]['CITIES'][$rc['ID']] = Array(
						"ID" => $rc['ID'],
						"NAME" => $rc['NAME']
					);
				}
			}

			$GLOBALS['CACHE_MANAGER']->StartTagCache($cachePath);
			$GLOBALS['CACHE_MANAGER']->RegisterTag('iblock_id_1');
			$GLOBALS['CACHE_MANAGER']->EndTagCache();
			$obCache->EndDataCache($arRegionsAndCities);

		}
		else {

			//Получаем данные из кеша
			$arRegionsAndCities = $obCache->GetVars();

		}

		return $arRegionsAndCities;

	}

	function getOfficeLoanViews($OFFICE_ID) {

		if( CModule::IncludeModule("main") && CModule::IncludeModule("iblock")  ) {

			$CIBlockElement = new CIBlockElement;

			$rsOffice = $CIBlockElement->GetList(Array(), Array("IBLOCK_ID" => 1, "ID" => intval($OFFICE_ID),/* "!PROPERTY_GO_OFFICE_PLACEMARK" => false,*/ "ACTIVE" => "Y"), false, false, Array());

			if($objOffice = $rsOffice->GetNextElement()) {

				$arOffice = $objOffice->GetFields();
				$arOffice['PROPERTIES'] = $objOffice->GetProperties();
				$arOffice['PROPERTIES']['CDP_G_CML_LINK_LOAN_VIEW'] = CIBlockFormatProperties::GetDisplayValue($arOffice, $arOffice['PROPERTIES']['CDP_G_CML_LINK_LOAN_VIEW'], "get_offices");

				$rsDescLoansViews = $CIBlockElement->GetList(Array(), Array("IBLOCK_ID" => 10, "ID" => $arOffice['PROPERTIES']['CDP_G_CML_LINK_LOAN_VIEW']['VALUE'], "ACTIVE" => "Y"), false, false, Array());
				$arDescLoansViews = Array();

				while($objDLV = $rsDescLoansViews->GetNextElement()) {

					$arDLV = $objDLV->GetFields();
					$arDLV['PROPERTIES'] = $objDLV->GetProperties();

					$arDescLoansViews[$arDLV['ID']] = Array();
					$arDescLoansViews[$arDLV['ID']] = $arDLV['PROPERTIES'];

					if($arDLV['PROPERTIES']['C_DLV_CML_LINK_LOAN_VIEW']['VALUE']) {

						$rsLoanView = $CIBlockElement->GetList(Array(), Array("IBLOCK_ID" => 9, "ID" => $arDLV['PROPERTIES']['C_DLV_CML_LINK_LOAN_VIEW']['VALUE'], "ACTIVE" => "Y"), false, false, Array("ID", "NAME", "XML_ID"));

						if($arLoanView = $rsLoanView->Fetch()) {
							$arDescLoansViews[$arDLV['ID']]["NAME"] = $arLoanView["NAME"];
							$arDescLoansViews[$arDLV['ID']]["LOAN_UID"] = $arLoanView["XML_ID"];
						}

						if($arDLV['PROPERTIES']['C_DLV_CML_LINK_STOCK']['VALUE']) {

							$rsStock = $CIBlockElement->GetList(Array(), Array("IBLOCK_ID" => 11, "ID" => $arDLV['PROPERTIES']['C_DLV_CML_LINK_STOCK']['VALUE'], "ACTIVE" => "Y"), false, false, Array("ID", "NAME", "XML_ID"));

							if($arStock = $rsStock->Fetch()) {
								//$arDescLoansViews[$arDLV['ID']]["NAME"] .= ' + Акция &laquo;' . $arStock["NAME"] . '&raquo;';
								$arDescLoansViews[$arDLV['ID']]["NAME"] = $arStock["NAME"];
								$arDescLoansViews[$arDLV['ID']]["STOCK_UID"] = $arStock["XML_ID"];
							}

						}

					}

				}

			}

			return $arDescLoansViews;

		}

		return false;

	}

	public static function getFormatSchedule($arSchedule) {

		$arWeekDays = Array('Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс');

		$sPd_WorkStart = '';
		$sPd_WorkEnd = '';
		$sPd_DinnerStart = '';
		$sPd_DinnerEnd = '';

		foreach($arSchedule as $key => $value) {

			if(
				$sPd_WorkStart == $value['РаботаНачало'] &&
				$sPd_WorkEnd == $value['РаботаКонец'] &&
				$sPd_DinnerStart == $value['ОбедНачало'] &&
				$sPd_DinnerEnd == $value['ОбедКонец']
			) {

				$arWorkDays[count($arWorkDays)-1]['days'][] = $arWeekDays[$key];
			}

			else {

				$arWorkDays[] = Array(
					'РаботаНачало' => $value['РаботаНачало'],
					'РаботаКонец' => $value['РаботаКонец'],
					'ОбедНачало' => $value['ОбедНачало'],
					'ОбедКонец' => $value['ОбедКонец'],
					'days' => Array($arWeekDays[$key])
				);

			}

			$sPd_WorkStart = $value['РаботаНачало'];
			$sPd_WorkEnd = $value['РаботаКонец'];
			$sPd_DinnerStart = $value['ОбедНачало'];
			$sPd_DinnerEnd = $value['ОбедКонец'];

		}

		$arFormatWD = Array();

		foreach($arWorkDays as $k => $WD) {

			$arFormatWD[$k] = count($WD['days']) > 2 ? $WD['days'][0] . '-' . $WD['days'][count($WD['days'])-1] : (count($WD['days']) == 2 ? $WD['days'][0] . ', ' . $WD['days'][1] : $WD['days'][0]);
			$arFormatWD[$k] .= ': ';
			if(date('H:i', strtotime($WD['РаботаНачало'])) == '00:00') {
				$arFormatWD[$k] .= 'выходной';
			}
			else {

				$sMinutesStart = date('i', strtotime($WD['РаботаНачало'])) == '00' ? '' : '.' . date('i', strtotime($WD['РаботаНачало']));
				$sMinutesEnd = date('i', strtotime($WD['РаботаКонец'])) == '00' ? '' : '.' . date('i', strtotime($WD['РаботаКонец']));

				$arFormatWD[$k] .= date('G', strtotime($WD['РаботаНачало'])) . $sMinutesStart . '-' . date('G', strtotime($WD['РаботаКонец'])) . $sMinutesEnd;

				if(date('H:i', strtotime($WD['ОбедНачало'])) == '00:00') {
					$arFormatWD[$k] .= ', без перерыва';
				}
				else {
					$sMinutesStart = date('i', strtotime($WD['ОбедНачало'])) == '00' ? '' : '.' . date('i', strtotime($WD['ОбедНачало']));
					$sMinutesEnd = date('i', strtotime($WD['ОбедКонец'])) == '00' ? '' : '.' . date('i', strtotime($WD['ОбедКонец']));
					$arFormatWD[$k] .= ', перерыв: ' . date('G', strtotime($WD['ОбедНачало'])) . $sMinutesStart . '-' . date('G', strtotime($WD['ОбедКонец'])) . $sMinutesEnd;
				}
			}

		}

		return $arFormatWD;

	}

	public static function getFeedbackFormSubjects() {

		$arFormSubjects = Array();

		if( CModule::IncludeModule("main") && CModule::IncludeModule("iblock")  ) {

			$CIBlockElement = new CIBlockElement;

			$rsFormSubjects = $CIBlockElement->GetList(Array("SORT" => "ASC", "ID" => "DESC"), Array("IBLOCK_ID" => 14, "ACTIVE" => "Y"), false, false, Array('ID', 'NAME'));

			while($arFS = $rsFormSubjects->Fetch())
				$arFormSubjects[$arFS['ID']] = $arFS['NAME'];

		}

		return $arFormSubjects;
	}

	/* Эта функция будет проверять, является ли посетитель роботом поисковой системы */
	public static function isBot(&$botname = ''){
		$bots = array(
			'rambler','googlebot','aport','yahoo','msnbot','turtle','mail.ru','omsktele',
			'yetibot','picsearch','sape.bot','sape_context','gigabot','snapbot','alexa.com',
			'megadownload.net','askpeter.info','igde.ru','ask.com','qwartabot','yanga.co.uk',
			'scoutjet','similarpages','oozbot','shrinktheweb.com','aboutusbot','followsite.com',
			'dataparksearch','google-sitemaps','appEngine-google','feedfetcher-google',
			'liveinternet.ru','xml-sitemaps.com','agama','metadatalabs.com','h1.hrn.ru',
			'googlealert.com','seo-rus.com','yaDirectBot','yandeG','yandex',
			'yandexSomething','Copyscape.com','AdsBot-Google','domaintools.com',
			'Nigma.ru','bing.com','dotnetdotcom'
		);
		foreach($bots as $bot)
		if(stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false){
			$botname = $bot;
			return true;
		}
		return false;
	}

	/**
	 * [generateRandomString description]
	 * @param  integer $length [description]
	 * @return [type]          [description]
	 */
	public function generateRandomString($length = 10, $bOnlyNums = false) {
		if($bOnlyNums)
			$characters = '0123456789';
		else
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	//Функция получения IP адреса
	public function getIP() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

}//Класс AB_S1

?>