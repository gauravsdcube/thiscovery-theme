<?php

/**
 * @link https://dcubeconsulting.co.uk
 * @copyright Copyright (c) D Cube Consulting Ltd. All rights reserved.
 * @license Proprietary
 */

use humhub\assets\AppAsset;
use humhub\components\View;
use humhub\helpers\DeviceDetectorHelper;
use humhub\helpers\Html;
use humhub\modules\space\widgets\Chooser;
use humhub\modules\thiscoveryTheme\assets\ThiscoveryTopNavigationAsset;
use humhub\modules\thiscoveryTheme\models\ConfigForm;
use humhub\modules\thiscoveryTheme\Module;
use humhub\modules\user\widgets\AccountTopMenu;
use humhub\widgets\NotificationArea;
use humhub\widgets\SiteLogo;
use humhub\widgets\TopMenu;
use humhub\widgets\TopMenuRightStack;
use Yii;

/* @var $this View */
/* @var $content string */

/** @var Module|null $module */
$module = Yii::$app->getModule('thiscovery-theme');
$mobileMenuStyle = (string)($module?->settings->get('mobileMenuStyle', ConfigForm::MOBILE_MENU_STYLE_HAMBURGER) ?? ConfigForm::MOBILE_MENU_STYLE_HAMBURGER);
$hideFloatingMenuItemLabels = (bool)($module?->settings->get('hideFloatingMenuItemLabels', false) ?? false);
$hideTextInBottomMenuItems = (bool)($module?->settings->get('hideTextInBottomMenuItems', false) ?? false);
$mobileMenuHighlightActive = (bool)($module?->settings->get('mobileMenuHighlightActive', false) ?? false);

$bodyClasses = DeviceDetectorHelper::getBodyClasses();
$bodyClasses[] = 'thiscovery-theme';
$bodyClasses[] = 'hh-yg-mobile-menu-' . $mobileMenuStyle;
if ($mobileMenuHighlightActive) {
    $bodyClasses[] = 'hh-yg-mobile-menu-highlight-active';
}
if ($hideFloatingMenuItemLabels && $mobileMenuStyle === ConfigForm::MOBILE_MENU_STYLE_FLOATING_BAR) {
    $bodyClasses[] = 'hh-yg-hide-floating-menu-texts';
}
if ($hideTextInBottomMenuItems && $mobileMenuStyle === ConfigForm::MOBILE_MENU_STYLE_BOTTOM_BAR) {
    $bodyClasses[] = 'hh-yg-hide-bottom-menu-texts';
}
if (Yii::$app->user->isGuest) {
    $bodyClasses[] = 'hh-yg-is-guest';
}

AppAsset::register($this);
ThiscoveryTopNavigationAsset::register($this);
$this->registerJsConfig('thiscoveryTheme.topNavigation', [
    'mobileMenuStyle' => $mobileMenuStyle,
]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= strip_tags((string)$this->pageTitle) ?></title>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <?php $this->head() ?>
    <?= $this->render('head') ?>
</head>

<?= Html::beginTag('body', ['class' => $bodyClasses]) ?>
<?php $this->beginBody() ?>

<div id="topbar" class="topbar fixed-top navbar">
    <div class="container flex-nowrap">
        <div class="topbar-brand d-flex text-nowrap overflow-hidden">
            <?= SiteLogo::widget() ?>
        </div>

        <nav id="top-menu-floating-bar" class="top-menu-floating-bar" aria-label="<?= Yii::t('base', 'Menu'); ?>">
            <ul class="flex-grow-1 nav" id="top-menu-nav">
                <?= Chooser::widget() ?>
                <?= TopMenu::widget() ?>
            </ul>
        </nav>

        <div id="top-menu-mobile-panel" class="top-menu-mobile-panel">
            <div id="top-menu-mobile-account-slot" class="top-menu-mobile-account-slot"></div>
        </div>

        <ul class="nav" id="search-menu-nav">
            <?= TopMenuRightStack::widget() ?>
        </ul>

        <div class="notifications">
            <?= NotificationArea::widget() ?>
        </div>

        <button
            type="button"
            id="top-menu-hamburger"
            class="top-menu-hamburger"
            aria-expanded="false"
            aria-controls="top-menu-mobile-panel"
            aria-label="<?= Yii::t('base', 'Menu'); ?>"
        >
            <i class="fa fa-bars" aria-hidden="true"></i>
        </button>

        <div class="topbar-actions">
            <?= AccountTopMenu::widget() ?>
        </div>
    </div>
</div>

<?= $content ?>

<?php $this->endBody() ?>
<?= Html::endTag('body') ?>
</html>
<?php $this->endPage() ?>
