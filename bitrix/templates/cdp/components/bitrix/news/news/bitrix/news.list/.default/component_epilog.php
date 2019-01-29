<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
if (isset($_GET['AJAX']) && $_GET['AJAX'] == "Y") {
 
    $content = ob_get_contents();
    ob_end_clean();
 
    $APPLICATION->RestartBuffer();
 
    list(, $content_html) = explode('<!--RestartBuffer-->', $content);
 
    echo $content_html;
 
    die();
}