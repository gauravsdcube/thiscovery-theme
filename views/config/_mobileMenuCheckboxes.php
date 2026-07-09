<?php

use humhub\modules\thiscoveryTheme\models\ConfigForm;
use humhub\modules\thiscoveryTheme\libs\MobileMenuHelper;
use yii\helpers\Html;

/**
 * @var ConfigForm $model
 * @var string $attribute
 * @var array<int, array{id: string, label: string}> $items
 * @var string|null $orderAttribute
 */

$selected = is_array($model->$attribute) ? $model->$attribute : [];
$name = $model->formName() . '[' . $attribute . '][]';
$inputIdPrefix = Html::getInputId($model, $attribute);
$orderAttribute = $orderAttribute ?? null;
$orderValues = $orderAttribute && is_array($model->$orderAttribute) ? $model->$orderAttribute : [];
?>
<?php if ($items === []): ?>
    <p class="text-body-secondary small mb-0">
        <?= Yii::t('ThiscoveryThemeModule.base', 'No menu items are available for the current user.') ?>
    </p>
<?php else: ?>
    <?= Html::hiddenInput($model->formName() . '[' . $attribute . 'Sent]', '1') ?>
    <div class="tc-menu-item-checkboxes row g-2">
        <?php foreach ($items as $index => $item): ?>
            <?php
            $inputId = $inputIdPrefix . '-' . $index;
            $orderName = $orderAttribute ? $model->formName() . '[' . $orderAttribute . '][' . $item['id'] . ']' : null;
            $orderInputId = $orderAttribute ? $inputId . '-order' : null;
            $defaultOrder = $index + 1;
            $orderValue = $defaultOrder;
            foreach ($orderValues as $orderKey => $orderPosition) {
                if (MobileMenuHelper::resolveMenuItemIdAlias((string)$orderKey) === MobileMenuHelper::resolveMenuItemIdAlias($item['id'])) {
                    $orderValue = (int)$orderPosition;
                    break;
                }
            }
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="form-check tc-menu-item-checkbox mb-0">
                    <?= Html::checkbox(
                        $name,
                        MobileMenuHelper::isConfiguredMenuItemId($item['id'], $selected),
                        ['value' => $item['id'], 'class' => 'form-check-input', 'id' => $inputId],
                    ) ?>
                    <?= Html::label(Html::encode($item['label']), $inputId, ['class' => 'form-check-label']) ?>
                    <?php if ($orderName !== null): ?>
                        <label for="<?= Html::encode($orderInputId) ?>" class="small text-body-secondary tc-sort-order-label">
                            <?= Yii::t('ThiscoveryThemeModule.base', 'Order') ?>
                        </label>
                        <?= Html::input('number', $orderName, $orderValue, [
                            'id' => $orderInputId,
                            'class' => 'form-control input-sm tc-sort-order-input',
                            'min' => 1,
                            'step' => 1,
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
