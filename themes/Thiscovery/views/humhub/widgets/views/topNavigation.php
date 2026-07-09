<?php

/**
 * @link https://dcubeconsulting.co.uk
 * @copyright Copyright (c) D Cube Consulting Ltd. All rights reserved.
 * @license Proprietary
 */

use humhub\helpers\Html;
use humhub\modules\thiscoveryTheme\libs\MobileMenuHelper;
use yii\helpers\Url;

/* @var $this \humhub\components\View */
/* @var $menu \humhub\widgets\TopMenu */
/* @var $entries \humhub\modules\ui\menu\MenuEntry[] */

?>

<?php foreach ($entries as $entry) : ?>
    <?php
    $menuItemId = $entry instanceof \humhub\modules\ui\menu\MenuLink
        ? MobileMenuHelper::resolveTopMenuItemId($entry)
        : $entry->getId();
    ?>
    <li class="nav-item top-menu-item"<?= $menuItemId ? ' data-menu-id="' . Html::encode($menuItemId) . '"' : '' ?>>
        <?php
        $options = $entry->getHtmlOptions();
        $class = $options['class'] ?? '';
        $class = is_array($class) ? implode(' ', $class) : $class;
        $options['class'] = trim('nav-link ' . ($entry->getIsActive() ? 'active ' : '') . $class);
        if ($menuItemId && empty($options['data-menu-id'])) {
            $options['data-menu-id'] = $menuItemId;
        }
        ?>
        <?= Html::a(
            $entry->getIcon() . '<br />' . $entry->getLabel(),
            $entry->getUrl(),
            $options,
        ) ?>
    </li>
<?php endforeach; ?>

<li id="top-menu-sub" class="nav-item dropdown" style="display:none;">
    <a href="#" id="top-dropdown-menu" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
        <i class="fa fa-align-justify"></i>
        <?= Yii::t('base', 'Menu'); ?>
    </a>
    <ul id="top-menu-sub-dropdown" class="dropdown-menu dropdown-menu-end">

    </ul>
</li>
