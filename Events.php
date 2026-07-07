<?php

/**
 * @link https://dcubeconsulting.co.uk
 * @copyright Copyright (c) D Cube Consulting Ltd. All rights reserved.
 * @license Proprietary
 */

namespace humhub\modules\thiscoveryTheme;

use humhub\assets\TopNavigationAsset;
use humhub\components\View;
use humhub\modules\thiscoveryTheme\Module;

class Events
{
    public static function onViewBeforeRender($event): void
    {
        /** @var View $view */
        $view = $event->sender;

        if (!Module::isThemeBasedActive()) {
            return;
        }

        unset($view->assetBundles[TopNavigationAsset::class]);
    }
}
