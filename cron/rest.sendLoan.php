<?
@set_time_limit(1200);
@ini_set("memory_limit", "1024M");

//die();

define("SITE_ID", "s1");
define("NO_KEEP_STATISTIC", true);
define('BX_SESSION_ID_CHANGE', false);
define('NO_AGENT_CHECK', true);

$_SERVER["DOCUMENT_ROOT"] = "/home/d/division2/bitrix/public_html";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if( /*isset($argv[1]) && $argv[1] == 'CRONTAB'*/ true && ping1C() ) {


	if( CModule::IncludeModule("iblock") && CModule::IncludeModule("main") ) {

		$CIBlockElement = new CIBlockElement;

		//Получим все заявки созданные на сайте, которые ожидают отправку в 1С
		$rsLoanApplies = $CIBlockElement->GetList(Array("ID" => "DESC"), Array("IBLOCK_ID" => 2, "ACTIVE" => "Y", "PROPERTY_CDP_LA_STATUS" => 1), false, false, Array());

		while($objLoanApply = $rsLoanApplies->GetNextElement()) {

			$arLoanApply = $objLoanApply->GetFields();
			$arLoanApply['PROPERTIES'] = $objLoanApply->GetProperties();

			if( $arOffice = $CIBlockElement->GetList(Array(), Array("IBLOCK_ID" => 1, "ACTIVE" => "Y", "ID" => $arLoanApply['PROPERTIES']['CDP_LA_CML_LINK_OFFICE']['VALUE']), false, false, Array("ID", "IBLOCK_ID", "XML_ID"))->Fetch() ) {

				$arLoanApply['PROPERTIES']['CDP_LA_CML_LINK_OFFICE']['VALUE'] = $arOffice['XML_ID'];

				$arCDP1CResponse = sendOrder($arLoanApply);

				//Заявка прошла
				if($arCDP1CResponse['HTTP_CODE'] == 200 && $arCDP1CResponse['RESPONSE']["ГУИД"] != "Ошибка создания заявки") {

					//Переведем статус заявки в "Отправлен в 1С" и запишем UID заявки
					$arLoanProps = Array(
						"CDP_LA_STATUS" => 2,
						"CDP_LA_1C_GUID" => $arCDP1CResponse['RESPONSE']["ГУИД"],
						"CDP_LA_1C_LOG" => Array('VALUE' => Array('TYPE' => 'TEXT', 'TEXT' => print_r($arCDP1CResponse, true))),
					);

					$CIBlockElement->SetPropertyValuesEx($arLoanApply['ID'], 2, $arLoanProps);

				}
				else {
					$arLoanProps = Array(
						"CDP_LA_STATUS" => 3,
						"CDP_LA_1C_LOG" => Array('VALUE' => Array('TYPE' => 'TEXT', 'TEXT' => print_r($arCDP1CResponse, true))),
					);

					$CIBlockElement->SetPropertyValuesEx($arLoanApply['ID'], 2, $arLoanProps);
				}

				echo '<pre>';
				print_r($arCDP1CResponse);

			}

		}

	}

}

function sendOrder($data_order) {

	$arName = explode(" ", $data_order['PROPERTIES']['CDP_LA_NAME']['VALUE']);

	$arPostData = Array(
		"ИД" => $data_order['ID'],
		"Имя" => $arName[1],
		"Фамилия" => $arName[0],
		"Отчество" => $arName[2],
		"ДатаРождения" => date("Y-m-d\T00:00:00", strtotime($data_order['PROPERTIES']['CDP_LA_BIRTHDAY']['VALUE'])),
		"Телефон" => "7".substr(preg_replace("/[^0-9]/", '', $data_order['PROPERTIES']['CDP_LA_PHONE']['VALUE']), 1),
		"Почта" => $data_order['PROPERTIES']['CDP_LA_EMAIL']['VALUE'],
		"ВидЗайма" => $data_order['PROPERTIES']['CDP_LA_LOAN_VIEW_UID']['VALUE'],
		"Акция" => $data_order['PROPERTIES']['CDP_LA_STOCK_UID']['VALUE'],
		"Срок" => intval(str_replace(" ", "", $data_order['PROPERTIES']['CDP_LA_TIME']['VALUE'])),
		"Сумма" => intval(str_replace(" ", "", $data_order['PROPERTIES']['CDP_LA_SUM']['VALUE'])),
		"Подразделение" => $data_order['PROPERTIES']['CDP_LA_CML_LINK_OFFICE']['VALUE'],
		"Дата" => date("Y-m-d\TH:i:s", strtotime($data_order['ACTIVE_FROM']))
	);

	/*echo '<pre>';
	print_r(ping1C());
	die();*/

    $url = "http://83.219.139.1:13805/bp3lk/hs/lk/GetRequest";

	if($curl = curl_init($url)) {

	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arPostData));
	    /*curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		    	'Content-Type: application/json',
		    	'X-API-KEY: 75c467c3-e797-4de9-84e6-cfa1edb87d32'
		    )
		);*/
	    $CDP_response = curl_exec($curl);
	    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	    curl_close($curl);

	    return Array('HTTP_CODE' => $httpCode, 'RESPONSE' => json_decode($CDP_response, true), "BODY_REQUEST" => $arPostData);

	}

	return false;

}

function ping1C() {
	return file_get_contents("http://83.219.139.1:13805/bp3lk/hs/lk/ping") === 'LK Pong';
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");