<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $AR_REGIONS_AND_CITIES;

$rsOffices = \CIBlockElement::GetList(
    array('NAME' => 'ASC'),
    array(
        'IBLOCK_ID' => 1,
        'ACTIVE' => 'Y',
        'SECTION_ACTIVE' => 'Y',
        'SECTION_GLOBAL_ACTIVE' => 'Y'
    ),
    false,
    false,
    array(
        'ID',
        'IBLOCK_SECTION_ID',
        'PROPERTY_CDP_G_OFFICE_CML_LINK_ORGANIZATION',
        'PROPERTY_CDP_G_OFFICE_ADDRESS',
        'PROPERTY_CDP_G_OFFICE_PHONE'
    )
);

while ($arOffice = $rsOffices->Fetch()) {
    foreach ($arResult['ITEMS'] as &$arItem) {
        if ($arItem['ID'] == $arOffice['PROPERTY_CDP_G_OFFICE_CML_LINK_ORGANIZATION_VALUE']) {
            if (!isset($arItem['OFFICES']))
                $arItem['OFFICES'] = array();

            $arItem['OFFICES'][] = array(
                'CITY_ID' => $arOffice['IBLOCK_SECTION_ID'],
                'ADDRESS' => $arOffice['PROPERTY_CDP_G_OFFICE_ADDRESS_VALUE'],
                'PHONE' => $arOffice['PROPERTY_CDP_G_OFFICE_PHONE_VALUE']
            );
        }
    }
    unset($arItem);
}

foreach ($arResult['ITEMS'] as &$arItem) {
    $arOffices = $arItem['OFFICES'];
    $arItem['OFFICES'] = array();

    foreach ($arOffices as $arOffice) {
        foreach ($AR_REGIONS_AND_CITIES as $arRegion) {
            foreach ($arRegion['CITIES'] as $arCity) {
                if ($arOffice['CITY_ID'] == $arCity['ID']) {
                    if (!isset($arItem['OFFICES'][$arRegion['ID']]))
                        $arItem['OFFICES'][$arRegion['ID']] = array('NAME' => $arRegion['NAME'], 'CITIES' => array());

                    if (!isset($arItem['OFFICES'][$arRegion['ID']]['CITIES'][$arCity['ID']]))
                        $arItem['OFFICES'][$arRegion['ID']]['CITIES'][$arCity['ID']] = array('NAME' => $arCity['NAME'], 'OFFICES' => array());

                    $arItem['OFFICES'][$arRegion['ID']]['CITIES'][$arCity['ID']]['OFFICES'][] = [
                        'ADDRESS' => $arOffice['ADDRESS'],
                        'PHONE' => $arOffice['PHONE']
                    ];
                }
            }
        }
    }

    usort($arItem['OFFICES'], function ($a, $b) {
        return ($b['NAME'] < $a['NAME']);
    });
}
unset($arItem);