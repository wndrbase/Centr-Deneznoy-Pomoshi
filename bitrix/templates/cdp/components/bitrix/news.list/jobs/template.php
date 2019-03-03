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

use Bitrix\Main\Localization\Loc;
?>

<?if($arResult["ITEMS"]):?>
	<ul class="jobs-list clr">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<li class="jobs-list__item jobs-list__item--open" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<div class="jobs-list__box clr">
					<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
						<div class="jobs-list__name"><?=$arItem["NAME"]?></div>
					<?endif;?>
					<div class="job-list__hider">			
						<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
							<div class="jobs-list__desc"><?=$arItem["PREVIEW_TEXT"];?></div>
						<?endif;?>
						<div class="jobs-list__city-response">					
							<?if($arItem["DISPLAY_PROPERTIES"]["CDP_J_CITY"]["VALUE"]):?>
								<div class="jobs-list__city"><?=$arItem["DISPLAY_PROPERTIES"]["CDP_J_CITY"]["VALUE"];?></div>
							<?endif;?>
						</div>
					</div>
					<a href="javascript:;" class="jobs-list__read-full" data-link-text-full="Читать полностью &darr;" data-link-text-short="Свернуть &uarr;">Читать полностью &darr;</a>
					<a href="javascript:;" class="btn-alert_up btn jobs-list__response-btn" data-alert-up="jobs"><?=Loc::getMessage("CDP_J_RESPONSE")?></a>
				</div>
			</li>
		<?endforeach;?>
	</ul>
	<?/*if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?>
	<?endif;*/?>
<?endif;?>

