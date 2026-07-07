<?php

/**
 * @link https://dcubeconsulting.co.uk
 * @copyright Copyright (c) D Cube Consulting Ltd. All rights reserved.
 * @license Proprietary
 */

use humhub\modules\thiscoveryTheme\Module;

return [
    'id' => 'thiscovery-theme',
    'class' => Module::class,
    'namespace' => 'humhub\modules\thiscoveryTheme',
    'events' => [
        [
            'class' => \humhub\components\View::class,
            'event' => \humhub\components\View::EVENT_BEFORE_RENDER,
            'callback' => [\humhub\modules\thiscoveryTheme\Events::class, 'onViewBeforeRender'],
        ],
    ],
];
