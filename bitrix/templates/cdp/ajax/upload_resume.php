<?
define("NO_KEEP_STATISTIC", true);
define('BX_SESSION_ID_CHANGE', false);
define('NO_AGENT_CHECK', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!check_bitrix_sessid()) {
	echo json_encode(Array("ERROR" => 1, "MESSAGE" => 'Неверная сессия'));
	die();
}
else {
	$dir = $_SERVER["DOCUMENT_ROOT"]."/upload/resume/".bitrix_sessid()."/";
	if(!is_dir($dir))
		mkdir($dir);
}

if( isset($_REQUEST["FILE_UPLOAD"]) && $_REQUEST["FILE_UPLOAD"] == "Y" ) {

	/*echo json_encode(Array("ERROR" => 1, "MESSAGE" => $_FILES['file']));
	die();*/

	$sError = false;
    $uploaddir = $dir;
    $arAcceptedTypes = Array("image/jpeg", "image/jpg", "image/png", "application/pdf", "application/msword", "text/plain");

    if(!$_FILES['file'])
		$sError = "Не указан файл";
    elseif($_FILES['file']['size'] > (5 * 1000 * 1000))
		$sError = "Размер файла превышает 5 Мб";

	if(!$sError) {

		$extension = pathinfo($_FILES['file']['name'])['extension'];
		
		if(!in_array($_FILES['file']['type'], $arAcceptedTypes))
			$sError = "Допустимые форматы: JPG, PNG, PDF, DOC/DOCX, TXT";

		if(!$sError) {

			$_FILES['file']['name'] = AB_S1::generateRandomString(32).".".$extension;

		    if(move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir .basename($_FILES['file']['name'])))
		        $FILE = "/upload/resume/".bitrix_sessid()."/" .$_FILES['file']['name'];
		    else
		        $sError = "Ошибка загрузки файла";

		}

	}

   	if($sError) {
   		echo json_encode(Array("ERROR" => 1, "MESSAGE" => $sError));
   	}
   	else
		echo json_encode(Array("SUCCESS" => 1, "MESSAGE" => "Файл загружен", "FILE" => $FILE));
}

die();