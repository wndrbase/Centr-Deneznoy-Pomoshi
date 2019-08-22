<?
include_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/urlrewrite.php';

CHTTP::SetStatus('404 Not Found');
@define('ERROR_404', 'Y');

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

$APPLICATION->SetTitle('404 Not Found');
$APPLICATION->AddChainItem('Страница не найдена', '');

\Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/jquery.min.js');
\Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/plax.js');
?>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .main {
            min-height: calc(100vh - 158px - 93px - 196px);
        }

        .page-404 {
            position: relative;
            height: 580px;
            overflow: hidden;
        }

        .page-404__item {
            position: absolute;
            transform: translate3d(0,0,0);
        }

        .page-404__item img {
            display: block;
            margin: 0 auto;
        }

        @media (min-width: 768px) {
            .page-404__item--clouds {
                top: 74px;
                left: 0;
                right: 0;
            }

            .page-404__item--anna {
                top: 68px;
                left: 370px;
                right: 0;
            }

            .page-404__item--text {
                top: 380px;
                left: 50px;
                right: 500px;
            }

            .page-404__item--404 {
                top: 177px;
                left: 50px;
                right: 510px;
            }
        }

        @media (max-width: 767px) {
            .main {
                min-height: calc(100vh - 107px - 40px - 303px);
            }

            .page-404__item {
                left: 0;
                right: 0;
            }

            .page-404__item--clouds {
                top: 268px;
            }

            .page-404__item--anna {
                top: 265px;
            }

            .page-404__item--text {
                top: 198px;
            }

            .page-404__item--404 {
                top: 79px;
            }
        }
    </style>

    <div class="page-404">

        <div class="page-404__item page-404__item--clouds" data-xrange="50" data-yrange="20">

            <picture>

                <source
                ="<?= SITE_TEMPLATE_PATH; ?>/images/404/clouds--mobile.svg" media="(max-width: 767px)">
                <source
                ="<?= SITE_TEMPLATE_PATH; ?>/images/404/clouds.svg">

                <img src="<?= SITE_TEMPLATE_PATH; ?>/images/404/clouds.svg" alt="">

            </picture>

        </div>

        <div class="page-404__item page-404__item--anna" data-xrange="10" data-yrange="10">

            <picture>

                <source
                ="<?= SITE_TEMPLATE_PATH; ?>/images/404/anna--mobile.svg" media="(max-width: 767px)">
                <source
                ="<?= SITE_TEMPLATE_PATH; ?>/images/404/anna.svg">

                <img src="<?= SITE_TEMPLATE_PATH; ?>/images/404/anna.svg" alt="">

            </picture>

        </div>

        <div class="page-404__item page-404__item--text" data-xrange="20" data-yrange="10">

            <picture>

                <source
                ="<?= SITE_TEMPLATE_PATH; ?>/images/404/text--mobile.svg" media="(max-width: 767px)">
                <source
                ="<?= SITE_TEMPLATE_PATH; ?>/images/404/text.svg">

                <img src="<?= SITE_TEMPLATE_PATH; ?>/images/404/text.svg" alt="">

            </picture>

        </div>

        <div class="page-404__item page-404__item--404" data-xrange="20" data-yrange="20">

            <picture>

                <source
                ="<?= SITE_TEMPLATE_PATH; ?>/images/404/404--mobile.svg" media="(max-width: 767px)">
                <source
                ="<?= SITE_TEMPLATE_PATH; ?>/images/404/404.svg">

                <img src="<?= SITE_TEMPLATE_PATH; ?>/images/404/404.svg" alt="">

            </picture>

        </div>

    </div>

    <script>
        $(document).ready(function () {
            $('.page-404__item').plaxify();
            $.plax.enable()
        });
    </script>


<? require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';