<?php

use humhub\modules\thiscoveryTheme\models\ConfigForm;
use humhub\modules\thiscoveryTheme\models\ThemeImportForm;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\form\ActiveForm;

/* @var ConfigForm $model */
/* @var ThemeImportForm $importModel */
/* @var array<int, array{id: string, label: string, group: string}> $topMenuItems */
/* @var array<int, array{id: string, label: string, group: string}> $accountMenuItems */

$formName = $model->formName();
$customCssSelectorName = $formName . '[customCssSelector][]';
$customCssDeclarationsName = $formName . '[customCssDeclarations][]';
$customCssDescriptionName = $formName . '[customCssDescription][]';
$hamburgerCustomLinkIdName = $formName . '[hamburgerCustomLinkId][]';
$hamburgerCustomLinkLabelName = $formName . '[hamburgerCustomLinkLabel][]';
$hamburgerCustomLinkUrlName = $formName . '[hamburgerCustomLinkUrl][]';
$hamburgerCustomLinkSortOrderName = $formName . '[hamburgerCustomLinkSortOrder][]';
$hamburgerCustomLinksSentName = $formName . '[hamburgerCustomLinksSent]';
$hasCustomCssRules = !empty($model->customCssRules);
?>
<div class="panel panel-default thiscovery-theme-config">
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
        <h6 class="mb-2 mt-3"><strong><?= Yii::t('ThiscoveryThemeModule.base', 'Danger badges') ?></strong></h6>
        <p class="text-body-secondary"><?= Yii::t('ThiscoveryThemeModule.base', 'Notification counts and other red badges. Separate from the general danger brand colour.') ?></p>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'dangerBadgeBackgroundColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'dangerBadgeTextColor']) ?></div>
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

        <?= $form->beginCollapsibleFields(Yii::t('ThiscoveryThemeModule.base', 'Mobile settings')) ?>

        <?= $this->render('_settingsSubsection', [
            'title' => Yii::t('ThiscoveryThemeModule.base', 'Navigation style'),
            'description' => Yii::t('ThiscoveryThemeModule.base', 'Choose how navigation appears on phones and small tablets. Desktop navigation is unchanged.'),
            'showDivider' => false,
        ]) ?>
        <div class="row">
            <div class="col-md-8"><?= $form->field($model, 'mobileMenuStyle')->dropDownList(ConfigForm::getMobileMenuStyleOptions()) ?></div>
        </div>

        <?= $this->render('_settingsSubsection', [
            'title' => Yii::t('ThiscoveryThemeModule.base', 'Menu items'),
            'description' => Yii::t('ThiscoveryThemeModule.base', 'Choose which links appear in the floating bottom bar and which appear in the hamburger menu on mobile. Leave a list empty to use sensible defaults.'),
        ]) ?>
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label"><?= $model->getAttributeLabel('floatingNavMenuItemIds') ?></label>
                <p class="text-body-secondary small"><?= Yii::t('ThiscoveryThemeModule.base', 'Used with floating bottom navigation. Main navigation links shown in the bar at the bottom of the screen.') ?></p>
                <?= $this->render('_mobileMenuCheckboxes', [
                    'model' => $model,
                    'attribute' => 'floatingNavMenuItemIds',
                    'orderAttribute' => 'floatingNavMenuItemSortOrder',
                    'items' => $topMenuItems,
                ]) ?>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label"><?= $model->getAttributeLabel('hamburgerNavMenuItemIds') ?></label>
                <p class="text-body-secondary small"><?= Yii::t('ThiscoveryThemeModule.base', 'Main navigation links shown in the hamburger slide-down panel.') ?></p>
                <?= $this->render('_mobileMenuCheckboxes', [
                    'model' => $model,
                    'attribute' => 'hamburgerNavMenuItemIds',
                    'orderAttribute' => 'hamburgerNavMenuItemSortOrder',
                    'items' => $topMenuItems,
                ]) ?>
            </div>
        </div>
        <?= $this->render('_settingsSubsection', [
            'title' => Yii::t('ThiscoveryThemeModule.base', 'Custom hamburger links'),
            'description' => Yii::t('ThiscoveryThemeModule.base', 'Add your own links to the mobile hamburger menu. Use sort order to control where each link appears relative to other hamburger items.'),
        ]) ?>
        <div class="row mb-3">
            <div class="col-md-12">
                <?= $this->render('_hamburgerCustomLinks', [
                    'model' => $model,
                    'linkIdName' => $hamburgerCustomLinkIdName,
                    'linkLabelName' => $hamburgerCustomLinkLabelName,
                    'linkUrlName' => $hamburgerCustomLinkUrlName,
                    'linkSortOrderName' => $hamburgerCustomLinkSortOrderName,
                    'linksSentName' => $hamburgerCustomLinksSentName,
                ]) ?>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12">
                <label class="form-label"><?= $model->getAttributeLabel('hamburgerAccountMenuItemIds') ?></label>
                <p class="text-body-secondary small"><?= Yii::t('ThiscoveryThemeModule.base', 'Account links shown in the hamburger panel (settings, administration, logout, etc.).') ?></p>
                <?= $this->render('_mobileMenuCheckboxes', [
                    'model' => $model,
                    'attribute' => 'hamburgerAccountMenuItemIds',
                    'items' => $accountMenuItems,
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12"><?= $form->field($model, 'showProfileInFloatingNav')->checkbox() ?></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'showLegalsInFloatingNav')->checkbox()
                    ->hint(Yii::t('ThiscoveryThemeModule.base', 'When the Site Footer module is enabled, Legals appears in the hamburger menu by default. Enable this to show it in the floating bottom bar instead.')) ?>
            </div>
        </div>

        <?= $this->render('_settingsSubsection', [
            'title' => Yii::t('ThiscoveryThemeModule.base', 'Hamburger menu appearance'),
            'description' => Yii::t('ThiscoveryThemeModule.base', 'Colours and spacing for the slide-down hamburger panel on mobile.'),
        ]) ?>
        <div class="row">
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'mobileMenuBackgroundColor']) ?></div>
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'mobileMenuTextColor']) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'mobileMenuFontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 12, 'max' => 24]) ?></div>
        </div>
        <div class="row">
            <div class="col-md-4"><?= $form->field($model, 'mobileMenuItemPaddingX')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'mobileMenuItemPaddingY')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'mobileMenuHighlightActive')->checkbox() ?></div>
        </div>

        <?= $this->render('_settingsSubsection', [
            'title' => Yii::t('ThiscoveryThemeModule.base', 'Floating bottom navigation'),
            'description' => Yii::t('ThiscoveryThemeModule.base', 'Used when floating bottom navigation is selected. Main links appear in a floating bar at the bottom. Search, notifications, and messages stay in the top bar.'),
        ]) ?>
        <div class="row">
            <div class="col-md-3"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'floatingMenuBackgroundColor']) ?></div>
            <div class="col-md-3"><?= $form->field($model, 'floatingMenuBackgroundOpacity')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 100]) ?></div>
            <div class="col-md-3"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'floatingMenuTextColor']) ?></div>
            <div class="col-md-3"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'floatingMenuActiveColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($model, 'hideFloatingMenuItemLabels')->checkbox() ?></div>
            <div class="col-md-6"><?= $form->field($model, 'hideFloatingMenuOnScrollDown')->checkbox() ?></div>
        </div>

        <?= $this->render('_settingsSubsection', [
            'title' => Yii::t('ThiscoveryThemeModule.base', 'Mobile content layout'),
            'description' => Yii::t('ThiscoveryThemeModule.base', 'Spacing for dashboard, spaces, streams, and other pages on phones and small tablets.'),
        ]) ?>
        <div class="row">
            <div class="col-md-4"><?= $form->field($model, 'mobileTopbarPaddingX')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'mobileContentPaddingX')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'mobileContentPaddingY')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
        </div>
        <div class="row">
            <div class="col-md-4"><?= $form->field($model, 'mobileContentGutter')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'mobilePanelSpacing')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'mobilePanelBodyPadding')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
        </div>
        <?= $form->endCollapsibleFields() ?>

        <?= $form->beginCollapsibleFields(Yii::t('ThiscoveryThemeModule.base', 'Typography')) ?>
        <?= $this->render('_settingsSubsection', [
            'title' => Yii::t('ThiscoveryThemeModule.base', 'Base fonts'),
            'showDivider' => false,
        ]) ?>
        <div class="row">
            <div class="col-md-6"><?= $form->field($model, 'fontFamily')->textInput() ?></div>
            <div class="col-md-6"><?= $form->field($model, 'headingFontFamily')->textInput() ?></div>
        </div>
        <div class="row">
            <div class="col-md-4"><?= $form->field($model, 'fontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 12, 'max' => 24]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'fontWeight')->textInput(['type' => 'number', 'step' => 100, 'min' => 100, 'max' => 900]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'fontBoldWeight')->textInput(['type' => 'number', 'step' => 100, 'min' => 100, 'max' => 900]) ?></div>
        </div>
        <?= $this->render('_settingsSubsection', [
            'title' => Yii::t('ThiscoveryThemeModule.base', 'Heading sizes'),
        ]) ?>
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
        <h6 class="mb-2"><strong><?= Yii::t('ThiscoveryThemeModule.base', 'Primary buttons') ?></strong></h6>
        <p class="text-body-secondary"><?= Yii::t('ThiscoveryThemeModule.base', 'Colours for main action buttons (Save, Submit, etc.).') ?></p>
        <div class="row">
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'buttonBackgroundColor']) ?></div>
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'buttonTextColor']) ?></div>
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'buttonBorderColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'buttonHoverBackgroundColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'buttonHoverTextColor']) ?></div>
        </div>
        <h6 class="mb-2 mt-3"><strong><?= Yii::t('ThiscoveryThemeModule.base', 'Secondary buttons') ?></strong></h6>
        <p class="text-body-secondary"><?= Yii::t('ThiscoveryThemeModule.base', 'Colours for secondary, light, and default buttons.') ?></p>
        <div class="row">
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'buttonSecondaryBackgroundColor']) ?></div>
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'buttonSecondaryTextColor']) ?></div>
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'buttonSecondaryBorderColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'buttonSecondaryHoverBackgroundColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'buttonSecondaryHoverTextColor']) ?></div>
        </div>
        <h6 class="mb-2 mt-3"><strong><?= Yii::t('ThiscoveryThemeModule.base', 'Button sizing') ?></strong></h6>
        <div class="row">
            <div class="col-md-4"><?= $form->field($model, 'buttonPaddingX')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'buttonPaddingY')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'buttonFontWeight')->textInput(['type' => 'number', 'step' => 100, 'min' => 100, 'max' => 900]) ?></div>
        </div>
        <h6 class="mb-2 mt-3"><strong><?= Yii::t('ThiscoveryThemeModule.base', 'Form fields') ?></strong></h6>
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


        <?= $form->beginCollapsibleFields(Yii::t('ThiscoveryThemeModule.base', 'Accordions')) ?>
        <p class="text-body-secondary">
            <?= Yii::t('ThiscoveryThemeModule.base', 'Styles FAQ accordions (.faq-accordion) and TinyMCE rich-text accordions (details.mce-accordion) in posts, pages, and streams.') ?>
        </p>

        <h6 class="mb-2"><strong><?= Yii::t('ThiscoveryThemeModule.base', 'Container') ?></strong></h6>
        <div class="row">
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'accordionBorderColor']) ?></div>
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'accordionBackgroundColor']) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'accordionBorderRadius')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 30]) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $form->field($model, 'accordionMarginBottom')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
            <div class="col-md-6"><?= $form->field($model, 'accordionBoxShadow')->textInput() ?></div>
        </div>

        <h6 class="mb-2 mt-3"><strong><?= Yii::t('ThiscoveryThemeModule.base', 'Header (closed state)') ?></strong></h6>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'accordionHeaderBackgroundColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'accordionHeaderTextColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-3"><?= $form->field($model, 'accordionHeaderFontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 12, 'max' => 32]) ?></div>
            <div class="col-md-3"><?= $form->field($model, 'accordionHeaderFontWeight')->textInput(['type' => 'number', 'step' => 100, 'min' => 100, 'max' => 900]) ?></div>
            <div class="col-md-3"><?= $form->field($model, 'accordionHeaderPaddingY')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
            <div class="col-md-3"><?= $form->field($model, 'accordionHeaderPaddingX')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
        </div>

        <h6 class="mb-2 mt-3"><strong><?= Yii::t('ThiscoveryThemeModule.base', 'Header hover & open states') ?></strong></h6>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'accordionHeaderHoverBackgroundColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'accordionHeaderHoverTextColor']) ?></div>
        </div>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'accordionHeaderOpenBackgroundColor']) ?></div>
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'accordionHeaderOpenTextColor']) ?></div>
        </div>

        <h6 class="mb-2 mt-3"><strong><?= Yii::t('ThiscoveryThemeModule.base', 'Content area') ?></strong></h6>
        <div class="row">
            <div class="col-md-4"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'accordionContentTextColor']) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'accordionContentFontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 12, 'max' => 24]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'accordionContentPadding')->textInput(['type' => 'number', 'step' => 1, 'min' => 0, 'max' => 40]) ?></div>
        </div>

        <h6 class="mb-2 mt-3"><strong><?= Yii::t('ThiscoveryThemeModule.base', 'FAQ section title') ?></strong></h6>
        <div class="row">
            <div class="col-md-6"><?= $this->render('_colorField', ['form' => $form, 'model' => $model, 'attribute' => 'accordionFaqTitleColor']) ?></div>
            <div class="col-md-6"><?= $form->field($model, 'accordionFaqTitleFontSize')->textInput(['type' => 'number', 'step' => 1, 'min' => 16, 'max' => 72]) ?></div>
        </div>

        <div class="row">
            <div class="col-md-12"><?= $form->field($model, 'accordionEnableAnimation')->checkbox() ?></div>
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
.tc-settings-divider {
    border: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    margin: 1.5rem 0 1rem;
}
.tc-settings-subheading {
    font-size: 0.82rem;
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #6c757d;
    margin-bottom: 0;
}
.tc-menu-item-checkboxes {
    margin-left: 0;
}
.tc-menu-item-checkboxes .tc-menu-item-checkbox {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    padding-left: 0;
    min-height: auto;
}
.tc-menu-item-checkboxes .form-check-input {
    float: none;
    margin-left: 0;
    margin-top: 0.2rem;
    flex-shrink: 0;
}
.tc-menu-item-checkboxes .form-check-label {
    line-height: 1.4;
    flex: 1 1 auto;
}
.tc-menu-item-checkboxes .tc-sort-order-label {
    margin: 0.15rem 0 0 0.5rem;
    white-space: nowrap;
}
.tc-menu-item-checkboxes .tc-sort-order-input {
    width: 72px;
    margin-left: 0.4rem;
    padding: 0.1rem 0.35rem;
    height: 30px;
}
.thiscovery-theme-config .form-check {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    padding-left: 0;
    min-height: auto;
}
.thiscovery-theme-config .form-check-input {
    float: none;
    margin-left: 0;
    margin-top: 0.2rem;
    flex-shrink: 0;
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
