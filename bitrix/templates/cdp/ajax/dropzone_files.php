<?
define("NO_KEEP_STATISTIC", true);
define('BX_SESSION_ID_CHANGE', false);
define('NO_AGENT_CHECK', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!check_bitrix_sessid()) {
	echo json_encode(Array("ERROR" => 1, "MESSAGE" => 'Неверный идентификатор сессии'));
	die();
}
else {
	$dir = $_SERVER["DOCUMENT_ROOT"]."/upload/dropzone/".bitrix_sessid()."/";
	if(!is_dir($dir))
		mkdir($dir);
}

if( isset($_REQUEST["DROPZONE_FILES_UPLOAD"]) && $_REQUEST["DROPZONE_FILES_UPLOAD"] == "Y" ) {

	$sError = false;
    $uploaddir = $dir;
    $arAcceptedTypes = Array("image/jpeg", "image/jpg", "image/png", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "text/plain", "application/pdf", "application/vnd.oasis.opendocument.text");
    $arExts = Array();
    $arFiles = Array();

    if(!$_FILES['USER_FILES'])
		$sError = "Не указан файл для загрузки";

	if(!$sError) {

		foreach($_FILES['USER_FILES']['name'] as $key => $FILENAME) {

			$extension = pathinfo($FILENAME)['extension'];

			$arExts[] = strtoupper($extension);

			if(!in_array($_FILES['USER_FILES']['type'][$key], $arAcceptedTypes))
				$sError = "Неверный формат. Разрешены файлы формата jpg, png, pdf, doc, docx, odt и txt. <span class='link-file-btn'>Повторите попытку</span>";

			if(!$sError) {

				$NEW_FILENAME = AB_S1::generateRandomString(32).".".$extension;

			    if(move_uploaded_file($_FILES['USER_FILES']['tmp_name'][$key], $uploaddir .basename($NEW_FILENAME))) {
			        $FILE = "/upload/dropzone/".bitrix_sessid()."/" .$NEW_FILENAME;
			        $arFiles[] = $FILE;
			    }
			    else
			        $sError = "Не удалось загрузить файл. <span class='link-file-btn'>Повторите попытку</span>";

			}

		}

	}

   	if($sError) {
   		echo json_encode(Array("ERROR" => 1, "RESPONSE" => "Ошибка загрузки файла. <span class='link-file-btn'>Повторите попытку</span>"));
   	}
   	else
		echo json_encode(Array("SUCCESS" => 1, "RESPONSE" => Array("FILES_EXT" => $arExts, "FILES_PATH" => $arFiles)));
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");

die();