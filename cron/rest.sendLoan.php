<?

use Bitrix\Main\Loader;

@set_time_limit(1200);
@ini_set('memory_limit', '1024M');

define('SITE_ID', 's1');
define('NO_KEEP_STATISTIC', true);
define('BX_SESSION_ID_CHANGE', false);
define('NO_AGENT_CHECK', true);

if (empty($_SERVER['DOCUMENT_ROOT']))
    $_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

$bPing = ping1C();

restLog([
    'MESSAGE' => 'PING_1C',
    'RESULT' => $bPing ? 'Y' : 'N'
]);

if ($bPing && Loader::includeModule('iblock') && Loader::includeModule('main')) {
    $obElement = new CIBlockElement;

    //Получим все заявки созданные на сайте, которые ожидают отправку в 1С
    $rsLoanApplies = \CIBlockElement::GetList(
        array('ID' => 'DESC'),
        array(
            'IBLOCK_ID' => 2,
            'ACTIVE' => 'Y',
            'PROPERTY_CDP_LA_STATUS' => 1
        )
    );

    while ($rsLoanApply = $rsLoanApplies->GetNextElement()) {
        $arLoan = $rsLoanApply->GetFields();
        $arLoan['PROPERTIES'] = $rsLoanApply->GetProperties();

        $arOffice = \CIBlockElement::GetList(
            array(),
            array(
                'IBLOCK_ID' => 1,
                'ACTIVE' => 'Y',
                'ID' => $arLoan['PROPERTIES']['CDP_LA_CML_LINK_OFFICE']['VALUE']
            ),
            false,
            ['nTopCount' => 1],
            array('ID', 'IBLOCK_ID', 'XML_ID')
        )->Fetch();

        if (!$arOffice)
            continue;

        $arLoan['PROPERTIES']['CDP_LA_CML_LINK_OFFICE']['VALUE'] = $arOffice['XML_ID'];

        $arResponse = sendOrder($arLoan);

        restLog([
            'MESSAGE' => 'SEND_LOAN',
            'LOAN_ID' => $arLoan['ID'],
            'RESULT' => $arResponse
        ]);

        //Заявка прошла
        if ($arResponse['HTTP_CODE'] == 200 && $arResponse['RESPONSE']['ГУИД'] != 'Ошибка создания заявки') {
            //Переведем статус заявки в 'Отправлен в 1С' и запишем UID заявки
            $arLoanProps = array(
                'CDP_LA_STATUS' => 2,
                'CDP_LA_1C_GUID' => $arResponse['RESPONSE']['ГУИД'],
                'CDP_LA_1C_LOG' => array(
                    'VALUE' => array(
                        'TYPE' => 'TEXT',
                        'TEXT' => print_r($arResponse, true)
                    )
                )
            );

            $obElement->SetPropertyValuesEx($arLoan['ID'], 2, $arLoanProps);
        } else {
            $arLoanProps = array(
                'CDP_LA_STATUS' => 3,
                'CDP_LA_1C_LOG' => array(
                    'VALUE' => array(
                        'TYPE' => 'TEXT',
                        'TEXT' => print_r($arResponse, true)
                    )
                )
            );

            $obElement->SetPropertyValuesEx($arLoan['ID'], 2, $arLoanProps);
        }
    }
}

function sendOrder($arOrder)
{
    $arName = explode(' ', $arOrder['PROPERTIES']['CDP_LA_NAME']['VALUE']);

    $arPostData = array(
        'ИД' => $arOrder['ID'],
        'Имя' => $arName[1],
        'Фамилия' => $arName[0],
        'Отчество' => $arName[2],
        'ДатаРождения' => date('Y-m-d\T00:00:00', strtotime($arOrder['PROPERTIES']['CDP_LA_BIRTHDAY']['VALUE'])),
        'Телефон' => '7' . substr(preg_replace('/[^0-9]/', '', $arOrder['PROPERTIES']['CDP_LA_PHONE']['VALUE']), 1),
        'Почта' => $arOrder['PROPERTIES']['CDP_LA_EMAIL']['VALUE'],
        'ВидЗайма' => $arOrder['PROPERTIES']['CDP_LA_LOAN_VIEW_UID']['VALUE'],
        'Акция' => $arOrder['PROPERTIES']['CDP_LA_STOCK_UID']['VALUE'],
        'Срок' => intval(str_replace(' ', '', $arOrder['PROPERTIES']['CDP_LA_TIME']['VALUE'])),
        'Сумма' => intval(str_replace(' ', '', $arOrder['PROPERTIES']['CDP_LA_SUM']['VALUE'])),
        'Подразделение' => $arOrder['PROPERTIES']['CDP_LA_CML_LINK_OFFICE']['VALUE'],
        'Дата' => date('Y-m-d\TH:i:s', strtotime($arOrder['ACTIVE_FROM']))
    );

    $url = 'http://83.219.139.1:13805/bp3lk/hs/lk/GetRequest';

    $curl = curl_init($url);

    if (!$curl)
        return false;

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arPostData));
    /*curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'X-API-KEY: 75c467c3-e797-4de9-84e6-cfa1edb87d32'
        )
    );*/
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return array(
        'HTTP_CODE' => $httpCode,
        'RESPONSE' => json_decode($response, true),
        'BODY_REQUEST' => $arPostData
    );
}

function ping1C()
{
    return file_get_contents('http://83.219.139.1:13805/bp3lk/hs/lk/ping') === 'LK Pong';
}

function restLog($arData)
{
    $sMessage = print_r(array_merge([
        'DATE' => date('d.m.Y H:i:s')
    ], $arData), true);

    file_put_contents(
        $_SERVER['DOCUMENT_ROOT'] . '/upload/send-loan.log',
        $sMessage . PHP_EOL,
        FILE_APPEND
    );
}

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');