<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карта сайта");
?>
<div class="center clr">
	<?$APPLICATION->IncludeComponent(
		"bitrix:main.map",
		"sitemap",
		Array(
			"CACHE_TIME" => "3600",
			"CACHE_TYPE" => "A",
			"COL_NUM" => "2",
			"LEVEL" => "3",
			"SET_TITLE" => "Y",
			"SHOW_DESCRIPTION" => "N"
		)
	);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>