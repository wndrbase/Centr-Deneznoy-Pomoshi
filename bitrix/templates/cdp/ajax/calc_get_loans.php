<?
	define("NO_KEEP_STATISTIC", true);
	define('BX_SESSION_ID_CHANGE', false);
	define('NO_AGENT_CHECK', true);

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

	/*if(!check_bitrix_sessid()) {
		echo "Неверный идентификатор сессии";
		die();
	}*/

	if(isset($_REQUEST['AJAX_CALC_LOANS']) && $_REQUEST['AJAX_CALC_LOANS'] == "Y") {

		if(!intval($_REQUEST['OFFICE_ID'])) {
			echo 'Не установлен идентификатор офиса';
			die();
		}

		$arDescLoansViews = AB_S1::getOfficeLoanViews($_REQUEST['OFFICE_ID']);

        if($arDescLoansViews) {
            foreach ($arDescLoansViews as $key => $value) {
                echo '<option value="'.$key.'" data-loan-view-uid="'.$value['LOAN_UID'].'" data-stock-uid="'.$value['STOCK_UID'].'" data-rate="'.$value['C_DLV_RATE']['VALUE'].'" data-rate-stock="'.$value['C_DLV_STOCK_RATE']['VALUE'].'" data-time-min="'.$value['C_DLV_TIME_FROM']['VALUE'].'" data-time-max="'.$value['C_DLV_TIME_TO']['VALUE'].'" data-sum-min="'.$value['C_DLV_SUM_FROM']['VALUE'].'" data-sum-max="'.$value['C_DLV_SUM_TO']['VALUE'].'">'.$value['NAME'].'</option>';
            }
        }

        //echo print_r($arOffice['PROPERTIES']['CDP_G_CML_LINK_LOAN_VIEW']['VALUE'], true);

	}

	die();

?>