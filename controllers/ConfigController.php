<?php

/**
 * @link https://dcubeconsulting.co.uk
 * @copyright Copyright (c) D Cube Consulting Ltd. All rights reserved.
 * @license Proprietary
 */

namespace humhub\modules\thiscoveryTheme\controllers;

use humhub\helpers\ThemeHelper;
use humhub\modules\admin\components\Controller;
use humhub\modules\admin\permissions\ManageSettings;
use humhub\modules\thiscoveryTheme\models\ConfigForm;
use humhub\modules\thiscoveryTheme\models\ThemeImportForm;
use Throwable;
use Yii;
use yii\helpers\Json;

class ConfigController extends Controller
{
    public function getAccessRules(): array
    {
        return [['permissions' => ManageSettings::class]];
    }

    public function actionIndex()
    {
        $model = new ConfigForm();
        $importModel = new ThemeImportForm();
        $post = Yii::$app->request->post();

        if (isset($post['ThemeImportForm'])) {
            if ($importModel->load($post) && $importModel->import()) {
                if ($this->activateThemeAndBuildCss()) {
                    $this->view->saved();
                    return $this->refresh();
                }
            } elseif (!$importModel->hasErrors()) {
                $this->view->error(Yii::t('ThiscoveryThemeModule.base', 'Could not import theme configuration.'));
            }
        } elseif ($model->load($post)) {
            try {
                if ($model->save()) {
                    if ($this->activateThemeAndBuildCss()) {
                        $this->view->saved();
                        return $this->refresh();
                    }
                } else {
                    $this->view->error(Yii::t('ThiscoveryThemeModule.base', 'Could not save theme settings.'));
                }
            } catch (Throwable $e) {
                $this->view->error($e->getMessage());
            }
        }

        return $this->render('index', [
            'model' => $model,
            'importModel' => $importModel,
        ]);
    }

    public function actionExport()
    {
        $model = new ConfigForm();

        return Yii::$app->response->sendContentAsFile(
            Json::encode($model->toExportArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            'thiscovery-theme-configuration.json',
            [
                'mimeType' => 'application/json',
                'inline' => false,
            ],
        );
    }

    private function activateThemeAndBuildCss(): bool
    {
        $theme = ThemeHelper::getThemeByName('Thiscovery');
        if ($theme === null) {
            $this->view->error(Yii::t('ThiscoveryThemeModule.base', 'Thiscovery theme not found.'));
            return false;
        }

        $theme->activate();
        $buildResult = ThemeHelper::buildCss($theme);
        if ($buildResult === true) {
            return true;
        }

        $this->view->error($buildResult);
        return false;
    }
}
