<?php

use yii\helpers\Html;

/**
 * @var string $title
 * @var string|null $description
 * @var bool $showDivider
 */

$showDivider = $showDivider ?? true;
?>
<?php if ($showDivider): ?>
<hr class="tc-settings-divider">
<?php endif; ?>
<div class="tc-settings-subsection">
    <h6 class="tc-settings-subheading"><?= Html::encode($title) ?></h6>
    <?php if (!empty($description)): ?>
        <p class="text-body-secondary small mb-3"><?= $description ?></p>
    <?php endif; ?>
</div>
