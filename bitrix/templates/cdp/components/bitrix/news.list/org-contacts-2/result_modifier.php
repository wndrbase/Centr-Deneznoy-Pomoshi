<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$CIBlockElement = new CIBlockElement;

if( $rsOffices = $CIBlockElement->GetList(Array("NAME" => "ASC"), Array("IBLOCK_ID" => 1, "ACTIVE" => "Y", "SECTION_ACTIVE" => "Y", "SECTION_GLOBAL_ACTIVE" => "Y"), false, false, Array("ID", "IBLOCK_SECTION_ID", "PROPERTY_CDP_G_OFFICE_CML_LINK_ORGANIZATION", "PROPERTY_CDP_G_OFFICE_ADDRESS")) ) {

	global $AR_REGIONS_AND_CITIES;

	while($arOffice = $rsOffices->Fetch()) {
		foreach($arResult["ITEMS"] as $key => &$arItem) {
			if($arItem['ID'] == $arOffice['PROPERTY_CDP_G_OFFICE_CML_LINK_ORGANIZATION_VALUE']) {
				if(!isset($arItem["OFFICES"]))
					$arItem["OFFICES"] = Array();
				$arItem["OFFICES"][] = Array("CITY_ID" => $arOffice['IBLOCK_SECTION_ID'], "ADDRESS" => $arOffice['PROPERTY_CDP_G_OFFICE_ADDRESS_VALUE']);
			}
		}
	}

	foreach($arResult["ITEMS"] as $key => &$arItem) {

		$arOffices = $arItem['OFFICES'];
		$arItem['OFFICES'] = Array();

		foreach($arOffices as $arOffice) {
			foreach($AR_REGIONS_AND_CITIES as $Rkey => $arRegion) {
				foreach($arRegion['CITIES'] as $Ckey => $arCity) {
					if($arOffice['CITY_ID'] == $arCity['ID']) {
						if(!isset($arItem['OFFICES'][$arRegion['ID']]))
							$arItem['OFFICES'][$arRegion['ID']] = Array("NAME" => $arRegion['NAME'], "CITIES" => Array());
						if(!isset($arItem['OFFICES'][$arRegion['ID']]["CITIES"][$arCity['ID']]))
							$arItem['OFFICES'][$arRegion['ID']]["CITIES"][$arCity['ID']] = Array("NAME" => $arCity['NAME'], "OFFICES" => Array());
						$arItem['OFFICES'][$arRegion['ID']]["CITIES"][$arCity['ID']]['OFFICES'][] = $arOffice['ADDRESS'];
					}
				}
			}
		}

		usort($arItem['OFFICES'], function($a, $b){
		        return ($b['NAME'] < $a['NAME']);
		});

	}


}
