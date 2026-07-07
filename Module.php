<?php

/**
 * Thiscovery Theme module for HumHub.
 *
 * @link https://dcubeconsulting.co.uk
 * @copyright Copyright (c) D Cube Consulting Ltd. All rights reserved.
 * @license Proprietary
 */

namespace humhub\modules\thiscoveryTheme;

use Yii;
use yii\helpers\Url;

class Module extends \humhub\components\Module
{
    public const VERSION = '1.0.0';

    /**
     * @var string defines the icon
     */
    public $icon = 'paint-brush';

    public function getConfigUrl(): string
    {
        return Url::to(['/thiscovery-theme/config']);
    }

    public function getName(): string
    {
        return Yii::t('ThiscoveryThemeModule.base', 'Thiscovery Theme');
    }

    public function getDescription(): string
    {
        return Yii::t('ThiscoveryThemeModule.base', 'Configure and apply a Thiscovery site theme.');
    }
}
