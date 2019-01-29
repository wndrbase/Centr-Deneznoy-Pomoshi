<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<?if($arResult["ITEMS"]):?>

	<?if($arParams['CUSTOM_TITLE']):?>
	<h3><?=$arParams['CUSTOM_TITLE']?></h3>
	<?endif;?>

	<ul class="org__docs-list">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<li class="org__docs-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<a href="<?=$arItem['DISPLAY_PROPERTIES']['CDP_D_FILE']['FILE_VALUE']['SRC']?>" target="_blank" title="<?=(strlen($arItem['DISPLAY_PROPERTIES']['CDP_D_FILE_DESCRIPTION']['VALUE']['TEXT'])>0?$arItem['DISPLAY_PROPERTIES']['CDP_D_FILE_DESCRIPTION']['VALUE']['TEXT']:$arItem["NAME"])?>">
					<?
					if(strlen($arItem['DISPLAY_PROPERTIES']['CDP_D_FILE_DESCRIPTION']['VALUE']['TEXT']) > 0) {
						echo TruncateText($arItem['DISPLAY_PROPERTIES']['CDP_D_FILE_DESCRIPTION']['VALUE']['TEXT'], 180);
					}
					else
						echo TruncateText($arItem["NAME"], 180);
					?>
				</a>
			</li>
		<?endforeach;?>
	</ul>

<?endif;?>
