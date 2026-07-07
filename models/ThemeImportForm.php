<?php

/**
 * @link https://dcubeconsulting.co.uk
 * @copyright Copyright (c) D Cube Consulting Ltd. All rights reserved.
 * @license Proprietary
 */

namespace humhub\modules\thiscoveryTheme\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ThemeImportForm extends Model
{
    public $themeFile;

    public function rules(): array
    {
        return [
            [['themeFile'], 'file', 'extensions' => ['json'], 'checkExtensionByMimeType' => false],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'themeFile' => Yii::t('ThiscoveryThemeModule.base', 'Theme configuration file'),
        ];
    }

    public function import(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $file = UploadedFile::getInstance($this, 'themeFile');
        if (!$file instanceof UploadedFile || $file->error === UPLOAD_ERR_NO_FILE) {
            $this->addError('themeFile', Yii::t('ThiscoveryThemeModule.base', 'Please choose a JSON file to import.'));
            return false;
        }

        $raw = file_get_contents($file->tempName);
        if ($raw === false || $raw === '') {
            $this->addError('themeFile', Yii::t('ThiscoveryThemeModule.base', 'The import file is empty.'));
            return false;
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            $this->addError('themeFile', Yii::t('ThiscoveryThemeModule.base', 'The import file is not valid JSON.'));
            return false;
        }

        $config = new ConfigForm();
        if (!$config->applyImportData($data)) {
            $this->copyModelErrors($config);
            return false;
        }

        if (!$config->save()) {
            $this->copyModelErrors($config);
            return false;
        }

        return true;
    }

    private function copyModelErrors(Model $model): void
    {
        foreach ($model->getErrors() as $errors) {
            foreach ($errors as $error) {
                $this->addError('themeFile', $error);
            }
        }
    }
}
