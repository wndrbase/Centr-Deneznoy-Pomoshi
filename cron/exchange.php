<?
@set_time_limit(600);
@ini_set("memory_limit", "1024M");


//die();

if( /*isset($argv[1]) && $argv[1] == 'CRONTAB'*/ true ) {

	define("SITE_ID", "s1");
	define("NO_KEEP_STATISTIC", true);
	define('BX_SESSION_ID_CHANGE', false);
	define('NO_AGENT_CHECK', true);

	$_SERVER["DOCUMENT_ROOT"] = "/home/d/division2/bitrix/public_html";
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


	if( CModule::IncludeModule("iblock") && CModule::IncludeModule("main") ) {

		$JSON_data = objectToArray(json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"]."/cron/exchange/site.xml")));

		$CIBlockSection = new CIBlockSection;
		$CIBlockElement = new CIBlockElement;

		/*echo '<pre>';
		var_dump($JSON_data);
		die();*/

		//Обновление видов займа
		if($JSON_data["ВидыЗайма"]) {

			//Получим все виды займов с сайта
			$rsLoansViews = $CIBlockElement->GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => 9, "ACTIVE" => "Y"), false, false, Array("ID", "XML_ID"));
			$arSiteLoansViews = Array();

			//Сделаем массив ключ -> значение (ID вида займа -> наименование вида займа)
			$arLoansViewsUID_NAME = Array();
			$arLoansViewsID_UID = Array();
			while($arLV = $rsLoansViews->Fetch()) {
				$arSiteLoansViews[$arLV['ID']] = $arLV['XML_ID'];
			}

			foreach($JSON_data["ВидыЗайма"] as $key => $arLoanView) {

				$arLoanViewFields = Array(
					"IBLOCK_ID" => 9,
					"IBLOCK_SECTION_ID" => false,
					"ACTIVE" => "Y",
					"NAME" => preg_replace("/\s{2,}/", " ", trim($arLoanView['СвойствоНаименование'])),
					"XML_ID" => $arLoanView['ВидЗайма']
				);

				$arLoansViewsUID_NAME[$arLoanView['ВидЗайма']] = preg_replace("/\s{2,}/", " ", trim($arLoanView['СвойствоНаименование']));

				//Если на сайте нет вида займа, который есть в выгрузке, то добавим
				if(!in_array($arLoanView['ВидЗайма'], $arSiteLoansViews)) {
					//Добавляем организацию
					$NEW_LOAN_VIEW_ID = $CIBlockElement->Add($arLoanViewFields);
					$arLoansViewsID_UID[$NEW_LOAN_VIEW_ID] = $arLoanView['ВидЗайма'];
				}
				//Иначе вид займа есть на сайте
				else {
					//Обновление данных по виду займа
					$UPDATE_LOAN_VIEW_ID = array_search($arLoanView['ВидЗайма'], $arSiteLoansViews);
					$CIBlockElement->Update($UPDATE_LOAN_VIEW_ID, $arLoanViewFields);
					$arLoansViewsID_UID[$UPDATE_LOAN_VIEW_ID] = $arLoanView['ВидЗайма'];
					//Исключим вид займа из массива видов займа, которые есть на сайте для дольнейшего удаления
					unset($arSiteLoansViews[$UPDATE_LOAN_VIEW_ID]);
				}

			}

			//Если в массиве виды займов, значит их не было в выгрузке и их нужно удалить с сайта
			if($arSiteLoansViews) {
				foreach($arSiteLoansViews as $LVkey => $loanViewUID) {
					$CIBlockElement->Delete($LVkey);
				}
			}


		}//Обновление видов займа

		//Обновление акций
		if($JSON_data["Акции"]) {

			//Получим все акции с сайта
			$rsStocks = $CIBlockElement->GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => 11, "ACTIVE" => "Y"), false, false, Array("ID", "XML_ID"));
			$arSiteStocks = Array();

			//Сделаем массив ключ -> значение (UID акции -> поля акции)
			$arStocksUID_FIELDS = Array();
			while($arStock = $rsStocks->Fetch()) {
				$arSiteStocks[$arStock['ID']] = $arStock['XML_ID'];
			}

			foreach($JSON_data["Акции"] as $key => $arStock) {

				$arStockFields = Array(
					"IBLOCK_ID" => 11,
					"IBLOCK_SECTION_ID" => false,
					"ACTIVE" => "Y",
					"NAME" => preg_replace("/\s{2,}/", " ", trim($arStock['СвойствоНаименование'])),
					"XML_ID" => $arStock['Акция']
				);

				$arStocksUID_FIELDS[$arStock['Акция']] = $arStock;

				//Если на сайте нет акции, которая есть в выгрузке, то добавим
				if(!in_array($arStock['Акция'], $arSiteStocks)) {
					//Добавляем организацию
					$NEW_STOCK_ID = $CIBlockElement->Add($arStockFields);
					$arStocksUID_FIELDS[$arStock['Акция']]['ИдентификаторНаСайте'] = $NEW_STOCK_ID;
					//$arSiteStocksID_UID[$NEW_STOCK_ID] = $arStock['Акция'];
				}
				//Иначе акция есть на сайте
				else {
					//Обновление данных по акции
					$UPDATE_STOCK_ID = array_search($arStock['Акция'], $arSiteStocks);
					$CIBlockElement->Update($UPDATE_STOCK_ID, $arStockFields);
					$arStocksUID_FIELDS[$arStock['Акция']]['ИдентификаторНаСайте'] = $UPDATE_STOCK_ID;
					//Исключим акцию из массива акций, которые есть на сайте для дальнейшего удаления
					unset($arSiteStocks[$UPDATE_STOCK_ID]);
				}

			}

			//Если в массиве frwbb, значит их не было в выгрузке и их нужно удалить с сайта
			if($arSiteStocks) {
				foreach($arSiteStocks as $Skey => $stockUID) {
					$CIBlockElement->Delete($Skey);
				}
			}

		}//Обновление акций

		//Обновление описаний видов займа
		if($JSON_data["ОписанияВидовЗайма"]) {

			//Получим все описания видов займов с сайта
			$rsDescLoansViews = $CIBlockElement->GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => 10, "ACTIVE" => "Y"), false, false, Array("ID", "XML_ID"));
			$arSiteDescLoansViews = Array();

			//Сделаем массив ключ -> значение (ID описание вида займа -> UID описание вида займа)
			$arSiteDescLoansViewsID_OFFICE_UID = Array();
			while($arDLV = $rsDescLoansViews->Fetch()) {
				$arSiteDescLoansViews[$arDLV['ID']] = $arDLV['XML_ID'];
			}

			foreach($JSON_data["ОписанияВидовЗайма"] as $key => $arDescLoanView) {

				$sDescLoanViewName = $arLoansViewsUID_NAME[$arDescLoanView['ВидЗайма']];

				$arDLV_PROPS = Array();

				$arDLV_PROPS["C_DLV_CML_LINK_LOAN_VIEW"] = array_search($arDescLoanView['ВидЗайма'], $arLoansViewsID_UID);
				$arDLV_PROPS["C_DLV_RATE"] = $arDescLoanView['Процент'];
				$arDLV_PROPS["C_DLV_TIME_FROM"] = $arDescLoanView['КоличествоДнейЗаймаМин'];
				$arDLV_PROPS["C_DLV_TIME_TO"] = $arDescLoanView['КоличествоДнейЗаймаМакс'];
				$arDLV_PROPS["C_DLV_SUM_FROM"] = 0;
				$arDLV_PROPS["C_DLV_SUM_TO"] = 0;

				if($arDescLoanView['Акция'] && $arStocksUID_FIELDS[$arDescLoanView['Акция']]) {
					$arDLV_PROPS["C_DLV_CML_LINK_STOCK"] = $arStocksUID_FIELDS[$arDescLoanView['Акция']]['ИдентификаторНаСайте'];
					$arDLV_PROPS["C_DLV_STOCK_RATE"] = $arDescLoanView['ПроцентАкции'];
					$sDescLoanViewName .= ' / Акция "'.preg_replace("/\s{2,}/", " ", trim($arStocksUID_FIELDS[$arDescLoanView['Акция']]['СвойствоНаименование'])).'"';
				}

				$arDescLoanViewFields = Array(
					"IBLOCK_ID" => 10,
					"IBLOCK_SECTION_ID" => false,
					"ACTIVE" => "Y",
					"NAME" => $sDescLoanViewName,
					"XML_ID" => $arDescLoanView['ВидЗайма']."-".$arDescLoanView['Подразделение'],
					"PROPERTY_VALUES" => $arDLV_PROPS
				);

				//Если на сайте нет описания вида займа, который есть в выгрузке, то добавим
				if(!in_array($arDescLoanView['ВидЗайма']."-".$arDescLoanView['Подразделение'], $arSiteDescLoansViews)) {
					//Добавляем описание вида займа
					$NEW_DESC_LOAN_VIEW_ID = $CIBlockElement->Add($arDescLoanViewFields);
					$arSiteDescLoansViewsID_OFFICE_UID[$NEW_DESC_LOAN_VIEW_ID] = $arDescLoanView['Подразделение'];
				}
				//Иначе описание вида займа есть на сайте
				else {
					//Обновление данных по описанию вида займа
					$UPDATE_DESC_LOAN_VIEW_ID = array_search($arDescLoanView['ВидЗайма']."-".$arDescLoanView['Подразделение'], $arSiteDescLoansViews);
					$CIBlockElement->Update($UPDATE_DESC_LOAN_VIEW_ID, $arDescLoanViewFields);
					$arSiteDescLoansViewsID_OFFICE_UID[$UPDATE_DESC_LOAN_VIEW_ID] = $arDescLoanView['Подразделение'];
					//Исключим описание вида займа из массива описаний видов займов, которые есть на сайте для дольнейшего удаления
					unset($arSiteDescLoansViews[$UPDATE_DESC_LOAN_VIEW_ID]);
				}

			}

			//Если в массиве описания виды займов, значит их не было в выгрузке и их нужно удалить с сайта
			if($arSiteDescLoansViews) {
				foreach($arSiteDescLoansViews as $DLVkey => $descLoanViewUID) {
					$CIBlockElement->Delete($DLVkey);
				}
			}

		}

		/*echo '<pre>';
		//var_dump($arSiteDescLoansViewsID_OFFICE_UID);
		print_r(array_keys($arSiteDescLoansViewsID_OFFICE_UID, "a3bf794e-f2bb-11e2-9d6b-20cf30394ccf"));
		die();*/

		//Обновление организаций
		//Есть организации?
		if($JSON_data["Организации"]) {

			//Получим все области (регионы) с сайта
			$rsOrganizations = $CIBlockElement->GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => 8, "ACTIVE" => "Y"), false, false, Array("ID", "NAME", "XML_ID"));
			$arSiteOrganizations = Array();

			//Сделаем массив ключ -> значение (ID организации -> UID организации)
			$arSiteOrganizationsID_UID = Array();
			while($arOrg = $rsOrganizations->Fetch()) {
				$arSiteOrganizations[$arOrg['ID']] = $arOrg['XML_ID'];
				$arSiteOrganizationsID_UID[$arOrg['ID']] = $arOrg['XML_ID'];
			}

			foreach($JSON_data["Организации"] as $key => $arOrganization) {

				$arORG_PROPS = Array();

				$arORG_PROPS["CDP_O_INN"] = $arOrganization['СвойствоИНН'];
				$arORG_PROPS["CDP_O_KPP"] = $arOrganization['СвойствоКПП'];
				$arORG_PROPS["CDP_O_OGRN"] = $arOrganization['СвойствоОГРН'];
				$arORG_PROPS["CDP_O_ADDRESS"] = $arOrganization['СвойствоЮрАдресОрганизации'];
				$arORG_PROPS["CDP_O_PHONE"] = $arOrganization['СвойствоТелефонОрганизации'];

				//Если на сайте нет организации, которая есть в выгрузке, то добавим
				if(!in_array($arOrganization['Организация'], $arSiteOrganizations)) {

					//Добавляем организацию
					$NEW_ORG_ID = $CIBlockElement->Add(
						Array(
							"IBLOCK_ID" => 8,
							"IBLOCK_SECTION_ID" => false,
							"ACTIVE" => "Y",
							"NAME" => preg_replace("/\s{2,}/", " ", trim($arOrganization['СвойствоНаименованиеПолное'])),
							"XML_ID" => $arOrganization['Организация'],
							"PROPERTY_VALUES" => $arORG_PROPS
						)
					);

					$arSiteOrganizationsID_UID[$NEW_ORG_ID] = $arOrganization['Организация'];

				}
				//Иначе организация есть на сайте
				else {
					//Обновление данных по региону
					$UPDATE_ORG_ID = array_search($arOrganization['Организация'], $arSiteOrganizations);
					$CIBlockElement->Update($UPDATE_ORG_ID,
						Array(
							"IBLOCK_ID" => 8,
							"IBLOCK_SECTION_ID" => false,
							"ACTIVE" => "Y",
							"NAME" => preg_replace("/\s{2,}/", " ", trim($arOrganization['СвойствоНаименованиеПолное'])),
							"XML_ID" => $arOrganization['Организация'],
							//"PROPERTY_VALUES" => $arORG_PROPS
						)
					);
					$CIBlockElement->SetPropertyValuesEx($UPDATE_ORG_ID, 8, $arORG_PROPS);
					//Исключим область (регион) из массива областей (регионов), которые есть на сайте для дольнейшего удаления
					unset($arSiteOrganizations[$UPDATE_ORG_ID]);
				}

			}

			//Если в массиве jhufybpfwbb, значит их не было в выгрузке и их нужно удалить с сайта
			if($arSiteOrganizations) {
				foreach($arSiteOrganizations as $key => $orgUID) {
					$CIBlockElement->Delete($key);
				}
			}

		}//Есть организации?

		if($JSON_data["РасписанияПодразделений"]) {

			//График работ подроазделений
			$arOfficesSchedule = Array();
			foreach($JSON_data["РасписанияПодразделений"] as $schedule)
				$arOfficesSchedule[$schedule['Подразделение']] = $schedule['Расписание'];

		}

		//Обновление областей/городов/офисов
		//Есть области?
		if($JSON_data["Области"]) {

			//Сформируем выгрузку для удобства
			$arRegionsAndCities = Array();

			foreach($JSON_data["Области"] as $key => $arRegion) {

				if(!$arRegion['Область'] || !$arRegion['ОбластьНаименование'])
					continue;

				$REGION_NAME = trim($arRegion['ОбластьНаименование']);

				if( !isset($arRegionsAndCities[$REGION_NAME]) ) {
					$arRegionsAndCities[$REGION_NAME] = Array(
						"UID" => $arRegion['Область'],
						"NAME" => $REGION_NAME,
						"CITIES" => Array()
					);
				}

				//Добавление городов в область
				foreach($arRegion['НаселенныеПункты'] as $arCity) {

					if(!$arCity['НаселенныйПункт'] || !$arCity['НаселенныйПунктНаименование'])
						continue;

					$CITY_NAME = trim($arCity['НаселенныйПунктНаименование']);

					if( !isset($arRegionsAndCities[$REGION_NAME]['CITIES'][$CITY_NAME]) ) {
						$arRegionsAndCities[$REGION_NAME]['CITIES'][$CITY_NAME] = Array(
							"UID" => $arCity['НаселенныйПункт'],
							//"NAME" => trim(preg_replace("/(с\.)|(п\.)|(рп\.)|(пгт\.)|(т\.)|(ст\.)|(пос\.)/Uis", "", $CITY_NAME)),
							"NAME" => $CITY_NAME,
							"OFFICES" => Array()
						);

					}

					//Добавление офисов в город
					foreach($arCity['Подразделения'] as $arOffice) {

						if(!$arOffice['Подразделение'] || !$arOffice['СвойствоНаименование'])
							continue;

						$arRegionsAndCities[$REGION_NAME]['CITIES'][$CITY_NAME]['OFFICES'][] = Array(
							"UID" => $arOffice['Подразделение'],
							"NAME" => preg_replace("/\s{2,}/", " ", trim($arOffice['СвойствоНаименование'])),
							"ADDRESS" => trim($arOffice['СвойствоФактАдрес']),
							//"ADAPTIVE_ADDRESS" => trim($arOffice['АдаптивныйАдрес']),
							"PHONE" => trim($arOffice['СвойствоТелефон']),
							"LOCATION" => $arOffice['СвойствоКоордината1'].",".$arOffice['СвойствоКоордината2'],
							"ORGANIZATION" => $arOffice['СвойствоОрганизация'],
							//"LOAN_VIEW" => $arOffice['ВидЗайма'],
						);

					}

				}

			}//Сформируем выгрузку для удобства

			//Сортируем области в алфавитном порядке
			ksort($arRegionsAndCities);
			//Сортируем города в областях в алфавитном порядке
			foreach ($arRegionsAndCities as $key => $arSortRegion) {
				ksort($arSortRegion['CITIES']);
			}

			/*echo '<pre>';
			print_r($arSiteOrganizationsID_UID);
			print_r($arRegionsAndCities);
			die();*/

			//Добавим в офисы виды займа
			/*if($JSON_data["ОписанияВидовЗайма"]) {

				foreach($JSON_data["ОписанияВидовЗайма"] as $key => $arDescLoanView) {

					if(isset($arOfficesUID_LOANS[$arDescLoanView['Подразделение']]))
						$arOfficesUID_LOANS[$arDescLoanView['Подразделение']][] = $arDescLoanView;
				}

			}*/

			//Выгрузка сформирована?
			if($arRegionsAndCities) {

				//============= ОБНОВЛЕНИЕ ОБЛАСТЕЙ (РЕГИОНОВ) =============

				//Получим все области (регионы) с сайта
				$rsRegions = $CIBlockSection->GetList(Array(), Array("IBLOCK_ID" => 1, "DEPTH_LEVEL" => 1), false, Array("ID", "NAME", "XML_ID"));
				$arSiteRegions = Array();
				//Сделаем массив ключ -> значение (ID области (региона) -> UID области (региона))
				$arSiteRegionsID_UID = Array();
				while($arRegion = $rsRegions->Fetch()) {
					$arSiteRegions[$arRegion['ID']] = $arRegion['XML_ID'];
					$arSiteRegionsID_UID[$arRegion['ID']] = $arRegion['XML_ID'];
				}

				/*echo '<pre>';
				print_r($arSiteRegions);
				die();*/

				foreach($arRegionsAndCities as $key => $arRegion) {

					//Если на сайте нет области (региона), который есть в выгрузке, то добавим
					if(!in_array($arRegion['UID'], $arSiteRegions)) {

						//Добавляем область (регион)
						$NEW_REGION_ID = $CIBlockSection->Add(
							Array(
								"IBLOCK_ID" => 1,
								"IBLOCK_SECTION_ID" => false,
								"ACTIVE" => "Y",
								"NAME" => $arRegion['NAME'],
								"XML_ID" => $arRegion['UID']
							)
						);

						$arSiteRegionsID_UID[$NEW_REGION_ID] = $arRegion['UID'];

					}
					//Иначе область (регион) есть на сайте
					else {
						//Обновление данных по региону
						$UPDATE_REGION_ID = array_search($arRegion['UID'], $arSiteRegions);
						$CIBlockSection->Update($UPDATE_REGION_ID,
							Array(
								"IBLOCK_ID" => 1,
								"IBLOCK_SECTION_ID" => false,
								"ACTIVE" => "Y",
								"NAME" => $arRegion['NAME'],
								"XML_ID" => $arRegion['UID']
							)
						);
						//Исключим область (регион) из массива областей (регионов), которые есть на сайте для дольнейшего удаления
						unset($arSiteRegions[$UPDATE_REGION_ID]);
					}

				}

				//Если в массиве области (регионы), значит их не было в выгрузке и их нужно удалить с сайта
				if($arSiteRegions) {
					foreach($arSiteRegions as $Rkey => $regionUID) {
						$CIBlockSection->Delete($Rkey);
					}
				}

				//============= ОБНОВЛЕНИЕ ГОРОДОВ =============

				//Получим все города с сайта
				$rsCities = $CIBlockSection->GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => 1, "DEPTH_LEVEL" => 2), false, Array("ID", "NAME", "XML_ID", "SECTION_ID"));
				$arSiteCities = Array();
				//Сделаем массив ключ -> значение (ID города -> UID города)
				$arSiteCitiesID_UID = Array();
				while($arCity = $rsCities->Fetch()) {
					$arSiteCities[$arCity['ID']] = $arCity['XML_ID'];
					$arSiteCitiesID_UID[$arCity['ID']] = $arCity['XML_ID'];
				}

				foreach($arRegionsAndCities as $Rkey => $arRegion) {

					foreach ($arRegion['CITIES'] as $Ckey => $arCity) {

						//Принадлежит ли город к области?
						if(in_array($arRegion['UID'], $arSiteRegionsID_UID))
							$PARENT_REGION_ID = array_search($arRegion['UID'], $arSiteRegionsID_UID);
						else
							continue;

						//Если на сайте нет области (региона), который есть в выгрузке, то добавим
						if(!in_array($arCity['UID'], $arSiteCities)) {

							//Добавляем область (регион)
							$NEW_CITY_ID = $CIBlockSection->Add(
								Array(
									"IBLOCK_ID" => 1,
									"IBLOCK_SECTION_ID" => $PARENT_REGION_ID,
									"ACTIVE" => "Y",
									"NAME" => $arCity['NAME'],
									"XML_ID" => $arCity['UID']
								)
							);

							$arSiteCitiesID_UID[$NEW_CITY_ID] = $arCity['UID'];

						}
						//Иначе область (регион) есть на сайте
						else {
							//Обновление данных по региону
							$UPDATE_CITY_ID = array_search($arCity['UID'], $arSiteCities);
							$CIBlockSection->Update($UPDATE_CITY_ID,
								Array(
									"IBLOCK_ID" => 1,
									"IBLOCK_SECTION_ID" => $PARENT_REGION_ID,
									"ACTIVE" => "Y",
									"NAME" => $arCity['NAME'],
									"XML_ID" => $arCity['UID']
								)
							);
							//Исключим область (регион) из массива областей (регионов), которые есть на сайте для дольнейшего удаления
							unset($arSiteCities[$UPDATE_CITY_ID]);
						}

					}

				}

				//Если в массиве города, значит их не было в выгрузке и их нужно удалить с сайта
				if($arSiteCities) {
					foreach($arSiteCities as $Ckey => $cityUID) {
						$CIBlockSection->Delete($Ckey);
					}
				}


				//============= ОБНОВЛЕНИЕ ОФИСОВ =============

				//Получим все офисы с сайта
				$rsOffices = $CIBlockElement->GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => 1), false, false, Array("ID", "IBLOCK_ID", "XML_ID", "SECTION_ID"));
				$arSiteOffices = Array();
				//Сделаем массив ключ -> значение (ID города -> UID города)
				$arSiteOfficesID_UID = Array();
				while( $arOffice = $rsOffices->Fetch() ) {
					$arSiteOffices[$arOffice['ID']] = $arOffice['XML_ID'];
					$arSiteOfficesID_UID[$arOffice['ID']] = $arOffice['XML_ID'];
				}

				//Добавим или обновим офисы (цикл)
				foreach ($arRegionsAndCities as $Rkey => $arRegion) {

					foreach ($arRegion['CITIES'] as $Ckey => $arCity) {

						foreach ($arCity['OFFICES'] as $Okey => $arOffice) {

							$arOfficeFields = Array(
								"IBLOCK_ID" => 1,
								"IBLOCK_SECTION_ID" => array_search($arCity['UID'], $arSiteCitiesID_UID),
								"ACTIVE" => "Y",
								"NAME" => $arOffice['NAME'],
								"XML_ID" => $arOffice['UID'],

							);

							$arOfficePROPS = Array();
							$arOfficePROPS['CDP_G_OFFICE_PLACEMARK'] = $arOffice['LOCATION'];
							$arOfficePROPS['CDP_G_OFFICE_PHONE'] = $arOffice['PHONE'];
							$arOfficePROPS['CDP_G_OFFICE_ADDRESS'] = $arOffice['ADDRESS'];
							$arOfficePROPS['CDP_G_OFFICE_CML_LINK_ORGANIZATION'] = array_search($arOffice['ORGANIZATION'], $arSiteOrganizationsID_UID);

							if($arOfficesSchedule[$arOffice['UID']])
								$arOfficePROPS['CDP_G_OFFICE_SCHEDULE'] = Array("VALUE" => Array ("TEXT" => serialize($arOfficesSchedule[$arOffice['UID']]), "TYPE" => "text"));

							if($arOfficeDescLoanViews = array_keys($arSiteDescLoansViewsID_OFFICE_UID, $arOffice['UID'])) {
								$arOfficePROPS['CDP_G_CML_LINK_LOAN_VIEW'] = $arOfficeDescLoanViews;
								$rsODLV = $CIBlockElement->GetList(Array(), Array("IBLOCK_ID" => 10, "ID" => $arOfficeDescLoanViews), false, false, Array("ID", "NAME"));
								while($arODLV = $rsODLV->Fetch()) {
									$CIBlockElement->Update($arODLV['ID'], Array("NAME" => $arODLV['NAME']." / ".$arOfficePROPS['CDP_G_OFFICE_ADDRESS']));
								}
							}

							//Если на сайте нет офиса, который есть в выгрузке, то добавим
							if(!in_array($arOffice['UID'], $arSiteOffices)) {

								$arOfficeFields["PROPERTY_VALUES"] = $arOfficePROPS;
								//Добавляем офис
								$NEW_OFFICE_ID = $CIBlockElement->Add($arOfficeFields);
								$arSiteOfficesID_UID[$NEW_OFFICE_ID] = $arOffice['UID'];

							}
							else {

								$UPDATE_OFFICE_ID = array_search($arOffice['UID'], $arSiteOffices);
								//Обновляем офис
								$CIBlockElement->Update($UPDATE_OFFICE_ID, $arOfficeFields);
								$CIBlockElement->SetPropertyValuesEx($UPDATE_OFFICE_ID, 1, $arOfficePROPS);
								//Исключим офис из массива офисов, которые есть на сайте для дальнейшего удаления
								unset($arSiteOffices[$UPDATE_OFFICE_ID]);

							}

						}//Офисы

					}//Города

				}//Области

				//Если в массиве офисов сайта есть элементы, значит их не было в выгрузке и их нужно удалить с сайта
				//Но для начала необходимо удалить связанные виды займов офисов
				if($arSiteOffices) {
					/*foreach($arSiteOffices as $Okey => $officeUID) {
						$rsOffices = $CIBlockElement->GetList(Array("SORT" => "ASC"), Array("IBLOCK_ID" => 1, "ID" => $Okey), false, false, Array("ID", "IBLOCK_ID", "PROPERTY_GO_OFFICE_LOAN_VIEW"));
						while($arOffice = $rsOffices->Fetch()) {
							$CIBlockElement->Delete($arOffice['PROPERTY_GO_OFFICE_LOAN_VIEW_VALUE']);
						}
						$CIBlockElement->Delete($Okey);
					}*/
					foreach($arSiteOffices as $Okey => $officeUID) {
						$CIBlockElement->Delete($Okey);
					}
				}

			}//Выгрузка сформирована?

		}//Есть области?

		if(defined('BX_COMP_MANAGED_CACHE'))
			$GLOBALS['CACHE_MANAGER']->ClearByTag('iblock_id_1');

	}//Модули подключены?

}

function objectToArray($d) {
	if (is_object($d))
		$d = get_object_vars($d);

	if (is_array($d))
		return array_map(__FUNCTION__, $d);
	else
		return $d;
}


?>