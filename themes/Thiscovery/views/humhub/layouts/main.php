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
use humhub\modules\user\widgets\AccountTopMenu;
use humhub\widgets\NotificationArea;
use humhub\widgets\SiteLogo;
use humhub\widgets\TopMenu;
use humhub\widgets\TopMenuRightStack;

/* @var $this View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= strip_tags((string)$this->pageTitle) ?></title>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <?php $this->head() ?>
    <?= $this->render('head') ?>
</head>

<?= Html::beginTag('body', ['class' => DeviceDetectorHelper::getBodyClasses()]) ?>
<?php $this->beginBody() ?>

<div id="topbar" class="topbar fixed-top navbar">
    <div class="container flex-nowrap">
        <div class="topbar-brand d-flex text-nowrap overflow-hidden">
            <?= SiteLogo::widget() ?>
        </div>

        <ul class="flex-grow-1 nav" id="top-menu-nav">
            <?= Chooser::widget() ?>
            <?= TopMenu::widget() ?>
        </ul>

        <ul class="nav" id="search-menu-nav">
            <?= TopMenuRightStack::widget() ?>
        </ul>

        <div class="notifications">
            <?= NotificationArea::widget() ?>
        </div>

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
