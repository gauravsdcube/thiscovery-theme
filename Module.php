<?php

/**
 * Thiscovery Theme module for HumHub.
 *
 * @link https://dcubeconsulting.co.uk
 * @copyright Copyright (c) D Cube Consulting Ltd. All rights reserved.
 * @license Proprietary
 */

namespace humhub\modules\thiscoveryTheme;

use humhub\helpers\ThemeHelper;
use humhub\modules\thiscoveryTheme\libs\ProfileBannerImage;
use Yii;
use yii\helpers\Url;

class Module extends \humhub\components\Module
{
    public const VERSION = '2.1.0';
    public const THEME_NAME = 'Thiscovery';

    /**
     * @var string defines the icon
     */
    public $icon = 'paint-brush';

    public function init()
    {
        parent::init();
        $this->registerProfileBannerImageClass();
    }

    private function registerProfileBannerImageClass(): void
    {
        $bannerClass = ProfileBannerImage::class;

        \yii\base\Event::on(
            \humhub\modules\space\models\Space::class,
            \yii\db\BaseActiveRecord::EVENT_INIT,
            static function ($event) use ($bannerClass): void {
                $event->sender->profileBannerImageClass = $bannerClass;
            },
        );

        \yii\base\Event::on(
            \humhub\modules\user\models\User::class,
            \yii\db\BaseActiveRecord::EVENT_INIT,
            static function ($event) use ($bannerClass): void {
                $event->sender->profileBannerImageClass = $bannerClass;
            },
        );
    }

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

    public static function isThemeBasedActive(): bool
    {
        foreach (ThemeHelper::getThemeTree(Yii::$app->view->theme) as $theme) {
            if ($theme->name === self::THEME_NAME) {
                return true;
            }
        }

        return false;
    }
}
