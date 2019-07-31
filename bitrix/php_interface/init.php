<?
/*--------------------------------
   Developed by Andrey Bazykin (AB)
   Site: http://andreybazykin.com
   Skype: skaterfat
   E-mail: andreybazykin@gmail.com
   ~=Development of Dreams=~
 -------------------------------*/

use Bitrix\Main\EventManager;


/*EventManager::getInstance()->addEventHandler("main", "OnBeforeUserRegister", Array("AB", "OnBeforeUserRegisterHandler"));
EventManager::getInstance()->addEventHandler("main", "OnBeforeUserUpdate", Array("AB", "OnBeforeUserUpdateHandler"));
EventManager::getInstance()->addEventHandler("iblock", "OnStartIBlockElementUpdate", Array("AB", "OnStartIBlockElementUpdateHandler"));*/

EventManager::getInstance()->addEventHandler("main", "OnEndBufferContent", ["AB", "deleteTypeForScripts"]);

class AB
{
    /**
     * Функция склонения числительных в русском языке
     *
     * @param int $number Число которое нужно просклонять
     * @param array $titles Массив слов для склонения
     * @return string
     **/
    static function declOfNum($number, $titles)
    {
        $cases = [2, 0, 1, 1, 1, 2];
        return $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
    }

    static function deleteTypeForScripts(&$content)
    {
        global $USER, $APPLICATION;
        if ((is_object($USER) && $USER->IsAuthorized()) || strpos($APPLICATION->GetCurDir(), '/bitrix/') !== false) return;

        $content = str_replace('<script type="text/javascript"', '<script', $content);
    }
}

AddEventHandler('iblock', 'OnAfterIBlockElementAdd', ['CMyCache', 'AddClearingAgent']);
AddEventHandler('iblock', 'OnAfterIBlockElementUpdate', ['CMyCache', 'AddClearingAgent']);

class CMyCache
{
    // При добавлении/редактировании элемента с датой создания в будущем...
    // ...добавляем агент на очистку тегированного кеша.
    // Агент взводится на каждый элемент отдельно.
    function AddClearingAgent(&$arFields)
    {
        if (!defined('BX_COMP_MANAGED_CACHE'))
            return true;

        $ID = array_key_exists('ID', $arFields) ? $arFields['ID'] : false;
        $IBLOCK_ID = array_key_exists('IBLOCK_ID', $arFields) ? $arFields['IBLOCK_ID'] : false;
        $date = array_key_exists('ACTIVE_FROM', $arFields) ? MakeTimeStamp($arFields['ACTIVE_FROM']) : 0;

        if ($ID && $IBLOCK_ID && $date > time()) {
            $agentName = "CMyCache::ClearCacheByIBlockID($IBLOCK_ID, $ID);";
            // удаляем агент, если он есть
            CAgent::RemoveAgent($agentName, 'main');
            // добавляем агент
            CAgent::AddAgent(
                $agentName,
                'main',
                'N',
                0,
                '',
                'Y',
                ConvertTimeStamp($date, 'FULL')
            );
        }
    }

    // функция, вызываемая агентом
    function ClearCacheByIBlockID($IBLOCK_ID, $ID)
    {
        if (defined('BX_COMP_MANAGED_CACHE') && is_object($GLOBALS['CACHE_MANAGER']))
            $GLOBALS['CACHE_MANAGER']->ClearByTag('iblock_id_' . $IBLOCK_ID);

        return '';
    }
}