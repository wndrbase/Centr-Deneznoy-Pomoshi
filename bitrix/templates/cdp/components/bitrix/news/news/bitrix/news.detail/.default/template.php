<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
ob_start();
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

<div class="news">
					
	<div class="news__detail">
		
		<div class="news__detail-picture">
			<time class="news__detail-date" datetime="<?=date("Y-m-d", strtotime($arResult["ACTIVE_FROM"]))?>"><?=Loc::getMessage("CDP_N_PUBLISHED")?>: <?=$arResult["DISPLAY_ACTIVE_FROM"]?></time>
			<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
				<img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>" title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>">
			<?else:?>
				<div class="news__detail-pad"></div>
			<?endif?>
		</div>

		<?if(strlen($arResult["DETAIL_TEXT"])>0):?>
			<?=$arResult["DETAIL_TEXT"];?>
		<?else:?>
			<?=$arResult["PREVIEW_TEXT"];?>
		<?endif?>
		
		<div class="shared-block">
			<p><?=Loc::getMessage("CDP_N_SHARE")?></p>			
			<ul class="shared-block__list clr">
				<li class="shared-block__item shared-block__item--facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?=('http'.($_SERVER['HTTP_X_HTTPS']?'s':'').'://'.$_SERVER['HTTP_HOST']).$arResult['DETAIL_PAGE_URL']?>" target="_blank"><?=Loc::getMessage("CDP_N_SHARE_SEND")?></a></li>
				<li class="shared-block__item shared-block__item--vkontakte"><a href="https://vk.com/share.php?url=<?=('http'.($_SERVER['HTTP_X_HTTPS']?'s':'').'://'.$_SERVER['HTTP_HOST']).$arResult['DETAIL_PAGE_URL']?>" target="_blank"><?=Loc::getMessage("CDP_N_SHARE_SEND")?></a></li>
				<li class="shared-block__item shared-block__item--odnoklassniki"><a href="https://www.odnoklassniki.ru/dk?st.cmd=addShare&amp;st.s=1&amp;st._surl=<?=('http'.($_SERVER['HTTP_X_HTTPS']?'s':'').'://'.$_SERVER['HTTP_HOST']).$arResult['DETAIL_PAGE_URL']?>" target="_blank"><?=Loc::getMessage("CDP_N_SHARE_SEND")?></a></li>
			</ul>
		</div>

	</div>

</div>

#MORE_NEWS#

<?$this->__component->arResult["CACHED_TPL"] = @ob_get_contents();
  ob_get_clean();?>