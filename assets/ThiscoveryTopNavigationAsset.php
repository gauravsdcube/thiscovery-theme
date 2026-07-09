<?php

/**
 * @link https://dcubeconsulting.co.uk
 * @copyright Copyright (c) D Cube Consulting Ltd. All rights reserved.
 * @license Proprietary
 */

namespace humhub\modules\thiscoveryTheme\assets;

use humhub\components\assets\AssetBundle;

class ThiscoveryTopNavigationAsset extends AssetBundle
{
    public $sourcePath = '@thiscovery-theme/resources';

    public $forceCopy = true;

    public $jsOptions = [
        'appendTimestamp' => true,
    ];

    public $js = [
        'js/humhub.thiscoveryTheme.topNavigation.js',
    ];
}
