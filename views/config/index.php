<?php

use humhub\modules\thiscoveryTheme\models\ConfigForm;
use humhub\modules\thiscoveryTheme\models\ThemeImportForm;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\form\ActiveForm;

/* @var ConfigForm $model */
/* @var ThemeImportForm $importModel */

$formName = $model->formName();
$customCssSelectorName = $formName . '[customCssSelector][]';
$customCssDeclarationsName = $formName . '[customCssDeclarations][]';
$customCssDescriptionName = $formName . '[customCssDescription][]';
$hasCustomCssRules = !empty($model->customCssRules);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><?= Yii::t('ThiscoveryThemeModule.base', 'Thiscovery Theme') ?></strong>
        <div class="text-body-secondary">
            <?= Yii::t('ThiscoveryThemeModule.base', 'Configure colours, layout, typography, and custom CSS. Expand a section to edit, then save to apply.') ?>
            <br>
            <small><?= Yii::t('ThiscoveryThemeModule.base', 'Version {version} · © D Cube Consulting Ltd', ['version' => \humhub\modules\thiscoveryTheme\Module::VERSION]) ?></small>
        </div>
    </div>

    <div class="panel-body">
        <div id="thiscovery-theme-import-export" class="mb-4">
            <h5><strong><?= Yii::t('ThiscoveryThemeModule.base', 'Import / export theme') ?></strong></h5>
            <p class="text-body-secondary">
                <?= Yii::t('ThiscoveryThemeModule.base', 'Download your current settings as JSON, or upload a file to apply them on this site.') ?>
            </p>
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <?php $importForm = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'flex-grow-1']]); ?>
                <div class="d-flex flex-wrap align-items-start gap-2">
                    <?= $importForm->field($importModel, 'themeFile')
                        ->fileInput(['accept' => '.json,application/json'])
                        ->label(false) ?>
                    <?= Button::save(Yii::t('ThiscoveryThemeModule.base', 'Import'))
                        ->icon('upload')
                        ->confirm(null, Yii::t('ThiscoveryThemeModule.base', 'This will overwrite your current theme configuration.'))
                        ->submit()
                        ->loader(false) ?>
                </div>
                <?php ActiveForm::end(); ?>
                <?= Button::primary(Yii::t('ThiscoveryThemeModule.base', 'Export'))
                    ->icon('download')
                    ->link(['export'])
                    ->loader(false) ?>
            </div>
        </div>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->beginCollapsibleFields(Yii::t('ThiscoveryThemeModule.base', 'Brand colours')) ?>
        <div class="row">
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'themePrimaryColor']) ?></div>
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'themeAccentColor']) ?></div>
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'themeSecondaryColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'themeSuccessColor']) ?></div>
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'themeDangerColor']) ?></div>
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'themeWarningColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'themeInfoColor']) ?></div>
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'themeLightColor']) ?></div>
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'themeDarkColor']) ?></div>
        </div>
        <?= $form->endCollapsibleFields() ?>

        <?= $form->beginCollapsibleFields(Yii::t('ThiscoveryThemeModule.base', 'Layout & top header')) ?>
        <div class="row">
            <div class="col-md-4"><?= $form->field($model, 'containerMaxWidth')->textInput(['type' => 'number', 'step' => 1, 'min' => 800, 'max' => 2600]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'topBarHeight')->textInput(['type' => 'number', 'step' => 1, 'min' => 50, 'max' => 140]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'topBarFontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 8, 'max' => 20]) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($model, 'topMenuNavJustifyContent')->dropDownList(ConfigForm::getJustifyContentOptions()) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'topMenuBackgroundColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'topMenuTextColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'topMenuButtonHoverBackgroundColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'topMenuButtonHoverTextColor']) ?></div>
        </div>
        <?= $form->endCollapsibleFields() ?>

        <?= $form->beginCollapsibleFields(Yii::t('ThiscoveryThemeModule.base', 'Typography')) ?>
        <div class="row">
            <div class="col-md-6"><?= $form->field($model, 'fontFamily')->textInput() ?></div>
            <div class="col-md-6"><?= $form->field($model, 'headingFontFamily')->textInput() ?></div>
        </div>
        <div class="row">
            <div class="col-md-4"><?= $form->field($model, 'fontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 12, 'max' => 24]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'fontWeight')->textInput(['type' => 'number', 'step' => 100, 'min' => 100, 'max' => 900]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'fontBoldWeight')->textInput(['type' => 'number', 'step' => 100, 'min' => 100, 'max' => 900]) ?></div>
        </div>
        <div class="row">
            <div class="col-md-4"><?= $form->field($model, 'h1FontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 12, 'max' => 72]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'h2FontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 12, 'max' => 72]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'h3FontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 12, 'max' => 72]) ?></div>
        </div>
        <div class="row">
            <div class="col-md-4"><?= $form->field($model, 'h4FontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 12, 'max' => 72]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'h5FontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 12, 'max' => 72]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'h6FontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 12, 'max' => 72]) ?></div>
        </div>
        <?= $form->endCollapsibleFields() ?>

        <?= $form->beginCollapsibleFields(Yii::t('ThiscoveryThemeModule.base', 'Page & content')) ?>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'linkColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'textColorMain']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'textColorSecondary']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'backgroundColorMain']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'backgroundColorPage']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'panelBorderColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-4"><?= $form->field($model, 'panelBorderRadius')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 30]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'buttonBorderRadius')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 30]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'panelBoxShadow')->textInput() ?></div>
        </div>
        <?= $form->endCollapsibleFields() ?>

        <?= $form->beginCollapsibleFields(Yii::t('ThiscoveryThemeModule.base', 'Top navigation menus')) ?>
        <div class="row">
            <div class="col-md-3"><?= $form->field($model, 'topMenuTextTransform')->dropDownList(ConfigForm::getTopMenuTextTransformOptions()) ?></div>
            <div class="col-md-3"><?= $form->field($model, 'topMenuFontWeight')->dropDownList(ConfigForm::getTopMenuFontWeightOptions()) ?></div>
            <div class="col-md-3"><?= $form->field($model, 'topMenuFontStyle')->dropDownList(ConfigForm::getTopMenuFontStyleOptions()) ?></div>
            <div class="col-md-3"><?= $form->field($model, 'topMenuLetterSpacing')->textInput(['placeholder' => 'normal'])->hint(Yii::t('ThiscoveryThemeModule.base', 'e.g. normal, 0.03em, 1px')) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'menuItemTextColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'menuItemHoverTextColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'menuItemActiveTextColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'menuItemActiveBackgroundColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($model, 'menuItemPaddingX')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
            <div class="col-md-6"><?= $form->field($model, 'menuItemPaddingY')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
        </div>
        <?= $form->endCollapsibleFields() ?>

        <?= $form->beginCollapsibleFields(Yii::t('ThiscoveryThemeModule.base', 'Forms & buttons')) ?>
        <div class="row">
            <div class="col-md-4"><?= $form->field($model, 'buttonPaddingX')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'buttonPaddingY')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'buttonFontWeight')->textInput(['type' => 'number', 'step' => 100, 'min' => 100, 'max' => 900]) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'inputBackgroundColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'inputTextColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'inputBorderColor']) ?></div>
            <div class="col-md-6"><?= $form->field($model, 'inputBorderRadius')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
        </div>
        <?= $form->endCollapsibleFields() ?>

        <?= $form->beginCollapsibleFields(Yii::t('ThiscoveryThemeModule.base', 'Cards, tables & lists')) ?>
        <div class="row">
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'cardBackgroundColor']) ?></div>
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'cardTextColor']) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'cardPadding')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 60]) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'tableHeaderBackgroundColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'tableHeaderTextColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'tableRowBorderColor']) ?></div>
        </div>
        <hr class="my-3">
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'listGroupItemTextColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'listGroupItemHoverTextColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'listGroupItemActiveTextColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'listGroupItemBackgroundColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'listGroupItemHoverBackgroundColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'listGroupItemActiveBackgroundColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'listGroupItemBorderColor']) ?></div>
            <div class="col-md-6"><?= $form->field($model, 'listGroupItemBorderRadius')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 20]) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($model, 'listGroupItemPaddingX')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
            <div class="col-md-6"><?= $form->field($model, 'listGroupItemPaddingY')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
        </div>
        <?= $form->endCollapsibleFields() ?>

        <?= $form->beginCollapsibleFields(Yii::t('ThiscoveryThemeModule.base', 'Sidebar & side navigation')) ?>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'sidebarBackgroundColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'sidebarTextColor']) ?></div>
        </div>
        <hr class="my-3">
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'sideMenuItemTextColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'sideMenuItemHoverTextColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'sideMenuItemActiveTextColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'sideMenuItemHoverBackgroundColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'sideMenuItemActiveBackgroundColor']) ?></div>
            <div class="col-md-6"><?= $form->field($model, 'sideMenuItemBorderRadius')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 20]) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($model, 'sideMenuItemPaddingX')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 30]) ?></div>
            <div class="col-md-6"><?= $form->field($model, 'sideMenuItemPaddingY')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 30]) ?></div>
        </div>
        <?= $form->endCollapsibleFields() ?>

        <?= $form->beginCollapsibleFields(Yii::t('ThiscoveryThemeModule.base', 'Footer')) ?>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'footerBackgroundColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'footerTextColor']) ?></div>
        </div>
        <?= $form->endCollapsibleFields() ?>

        <?= $form->beginCollapsibleFields(Yii::t('ThiscoveryThemeModule.base', 'Custom CSS rules'), !$hasCustomCssRules) ?>
        <?= $this->render('_customCssRules', [
            'model' => $model,
            'customCssSelectorName' => $customCssSelectorName,
            'customCssDeclarationsName' => $customCssDeclarationsName,
            'customCssDescriptionName' => $customCssDescriptionName,
        ]) ?>
        <?= $form->endCollapsibleFields() ?>

        <div class="border-top pt-3 mt-2">
            <?= Button::save()->submit() ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$this->registerCss(<<<'CSS'
.tc-color-input-row {
    display: flex;
    align-items: center;
    gap: 8px;
}
.tc-color-input-row .tc-color-hex {
    flex: 1 1 auto;
    min-width: 0;
    font-family: monospace;
}
.tc-color-input-row .tc-color-picker {
    flex: 0 0 48px;
    width: 48px;
    height: 38px;
    padding: 2px;
    cursor: pointer;
}
CSS
);
$this->registerJs(<<<'JS'
(function () {
    function normalizeHex(value) {
        value = (value || '').trim();
        if (!value) {
            return '';
        }
        if (value.charAt(0) !== '#') {
            value = '#' + value;
        }
        if (/^#[0-9a-fA-F]{3}$/.test(value)) {
            value = '#' + value.charAt(1) + value.charAt(1)
                + value.charAt(2) + value.charAt(2)
                + value.charAt(3) + value.charAt(3);
        }
        return value.toLowerCase();
    }

    function isValidHex(value) {
        return /^#[0-9a-f]{6}$/.test(value);
    }

    document.querySelectorAll('.tc-color-picker').forEach(function (picker) {
        var input = document.getElementById(picker.getAttribute('data-input-id'));
        if (!input) {
            return;
        }

        picker.addEventListener('input', function () {
            input.value = picker.value;
        });

        input.addEventListener('input', function () {
            var normalized = normalizeHex(input.value);
            if (isValidHex(normalized)) {
                picker.value = normalized;
            }
        });

        input.addEventListener('blur', function () {
            var normalized = normalizeHex(input.value);
            if (isValidHex(normalized)) {
                input.value = normalized;
                picker.value = normalized;
            }
        });
    });
})();
JS
);
?>
