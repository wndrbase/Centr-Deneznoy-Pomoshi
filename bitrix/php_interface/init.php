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

class AB
{
	/**
	* Функция склонения числительных в русском языке
	*
	* @param int      $number  Число которое нужно просклонять
	* @param array  $titles      Массив слов для склонения
	* @return string
	**/
	static function declOfNum($number, $titles) {
		$cases = array (2, 0, 1, 1, 1, 2);
		return $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
	}
}
?>