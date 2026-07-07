<?php

use humhub\helpers\Html;
use humhub\modules\thiscoveryTheme\models\ConfigForm;
use humhub\widgets\form\ActiveForm;

/* @var ActiveForm $form */
/* @var ConfigForm $model */
/* @var string $attribute */

$inputId = Html::getInputId($model, $attribute);
$pickerId = $inputId . '-picker';
$hexValue = ConfigForm::normalizeHexColor((string)($model->$attribute ?? ''));
$pickerValue = ConfigForm::toPickerHex($hexValue);
$pickerTitle = Yii::t('ThiscoveryThemeModule.base', 'Colour picker');
$hexHint = Yii::t('ThiscoveryThemeModule.base', 'Hex colour, e.g. #f7f7f7');
?>
<?= $form->field($model, $attribute, [
    'template' => "{label}\n<div class=\"tc-color-input-row\">{input}"
        . '<input type="color" id="' . Html::encode($pickerId) . '" class="form-control form-control-color tc-color-picker"'
        . ' value="' . Html::encode($pickerValue) . '"'
        . ' data-input-id="' . Html::encode($inputId) . '"'
        . ' title="' . Html::encode($pickerTitle) . '">'
        . "</div>\n{hint}\n{error}",
])->textInput([
    'id' => $inputId,
    'class' => 'form-control tc-color-hex',
    'placeholder' => '#ffffff',
    'maxlength' => 7,
    'spellcheck' => 'false',
    'autocomplete' => 'off',
])->hint($hexHint) ?>
