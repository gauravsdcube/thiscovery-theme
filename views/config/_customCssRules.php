<?php

use humhub\helpers\Html;
use humhub\modules\thiscoveryTheme\models\ConfigForm;

/* @var ConfigForm $model */
/* @var string $customCssSelectorName */
/* @var string $customCssDeclarationsName */
/* @var string $customCssDescriptionName */

$rules = $model->customCssRules;
?>
<div class="text-body-secondary mb-3">
    <?= Yii::t('ThiscoveryThemeModule.base', 'Target specific elements with a CSS selector and declarations. Add a description to identify each rule easily. Save the form to apply changes.') ?>
</div>

<div id="govuk-custom-css-empty" class="text-body-secondary mb-2<?= empty($rules) ? '' : ' d-none' ?>">
    <?= Yii::t('ThiscoveryThemeModule.base', 'No custom CSS rules yet.') ?>
</div>

<div class="list-group mb-2" id="govuk-custom-css-rules">
    <?php foreach ($rules as $rule): ?>
        <?php
        $description = trim((string)($rule['description'] ?? ''));
        $selector = trim((string)($rule['selector'] ?? ''));
        $declarations = trim((string)($rule['declarations'] ?? ''));
        $title = $description !== ''
            ? $description
            : ($selector !== '' ? $selector : Yii::t('ThiscoveryThemeModule.base', 'Untitled rule'));
        ?>
        <div class="govuk-custom-rule-item list-group-item">
            <div class="govuk-custom-rule-summary d-flex justify-content-between align-items-center gap-2">
                <div class="govuk-custom-rule-labels min-w-0">
                    <div class="govuk-custom-rule-title text-truncate"><?= Html::encode($title) ?></div>
                    <div class="govuk-custom-rule-subtitle text-body-secondary small text-truncate<?= ($description !== '' && $selector !== '') ? '' : ' d-none' ?>">
                        <?= Html::encode($selector) ?>
                    </div>
                </div>
                <div class="d-flex gap-1 flex-shrink-0">
                    <button type="button" class="btn btn-sm btn-secondary govuk-edit-custom-rule"><?= Yii::t('ThiscoveryThemeModule.base', 'Edit') ?></button>
                    <button type="button" class="btn btn-sm btn-danger govuk-delete-custom-rule"><?= Yii::t('ThiscoveryThemeModule.base', 'Delete') ?></button>
                </div>
            </div>
            <div class="govuk-custom-rule-editor d-none mt-3 border-top pt-3">
                <div class="mb-2">
                    <label class="form-label"><?= Yii::t('ThiscoveryThemeModule.base', 'Description') ?></label>
                    <input type="text" class="form-control govuk-custom-description" name="<?= Html::encode($customCssDescriptionName) ?>" value="<?= Html::encode($description) ?>" placeholder="<?= Html::encode(Yii::t('ThiscoveryThemeModule.base', 'e.g. Sidebar link hover colour')) ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label"><?= Yii::t('ThiscoveryThemeModule.base', 'Item selector') ?></label>
                    <input type="text" class="form-control govuk-custom-selector" name="<?= Html::encode($customCssSelectorName) ?>" value="<?= Html::encode($selector) ?>" placeholder=".list-group-item:hover">
                </div>
                <div class="mb-2">
                    <label class="form-label"><?= Yii::t('ThiscoveryThemeModule.base', 'CSS declarations') ?></label>
                    <textarea class="form-control govuk-custom-declarations" name="<?= Html::encode($customCssDeclarationsName) ?>" rows="4" placeholder="background: #f3f2f1;"><?= Html::encode($declarations) ?></textarea>
                </div>
                <button type="button" class="btn btn-light btn-sm govuk-cancel-custom-rule"><?= Yii::t('ThiscoveryThemeModule.base', 'Done') ?></button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<button type="button" class="btn btn-secondary btn-sm" id="govuk-add-custom-rule"><?= Yii::t('ThiscoveryThemeModule.base', 'Add CSS item') ?></button>

<script <?= Html::nonce() ?>>
    (function () {
        function initGovukCustomCssRules() {
            const container = document.getElementById('govuk-custom-css-rules');
            const emptyState = document.getElementById('govuk-custom-css-empty');
            const addBtn = document.getElementById('govuk-add-custom-rule');
            if (!container || !addBtn || addBtn.dataset.govukCustomCssInit === '1') {
                return;
            }

            addBtn.dataset.govukCustomCssInit = '1';

            const customCssSelectorName = <?= json_encode($customCssSelectorName, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const customCssDeclarationsName = <?= json_encode($customCssDeclarationsName, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const customCssDescriptionName = <?= json_encode($customCssDescriptionName, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const descriptionLabel = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'Description'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const descriptionPlaceholder = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'e.g. Sidebar link hover colour'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const itemSelectorLabel = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'Item selector'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const cssDeclarationsLabel = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'CSS declarations'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const editLabel = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'Edit'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const deleteLabel = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'Delete'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const doneLabel = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'Done'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const untitledLabel = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'Untitled rule'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const newRuleLabel = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'New rule'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

            function getRuleTitle(description, selector) {
                const desc = (description || '').trim();
                const sel = (selector || '').trim();
                if (desc !== '') {
                    return desc;
                }
                return sel !== '' ? sel : untitledLabel;
            }

            function updateEmptyState() {
                if (!emptyState) {
                    return;
                }
                emptyState.classList.toggle('d-none', container.querySelectorAll('.govuk-custom-rule-item').length > 0);
            }

            function closeEditor(item) {
                const editor = item.querySelector('.govuk-custom-rule-editor');
                const editBtn = item.querySelector('.govuk-edit-custom-rule');
                if (editor) {
                    editor.classList.add('d-none');
                }
                if (editBtn) {
                    editBtn.textContent = editLabel;
                }
            }

            function closeAllEditors(exceptItem) {
                container.querySelectorAll('.govuk-custom-rule-item').forEach(function (item) {
                    if (item !== exceptItem) {
                        closeEditor(item);
                    }
                });
            }

            function syncRuleSummary(item) {
                const descriptionInput = item.querySelector('.govuk-custom-description');
                const selectorInput = item.querySelector('.govuk-custom-selector');
                const title = item.querySelector('.govuk-custom-rule-title');
                const subtitle = item.querySelector('.govuk-custom-rule-subtitle');
                const description = descriptionInput ? descriptionInput.value : '';
                const selector = selectorInput ? selectorInput.value : '';

                if (title) {
                    title.textContent = getRuleTitle(description, selector);
                }
                if (subtitle) {
                    const showSubtitle = description.trim() !== '' && selector.trim() !== '';
                    subtitle.textContent = selector.trim();
                    subtitle.classList.toggle('d-none', !showSubtitle);
                }
            }

            function openEditor(item) {
                closeAllEditors(item);
                const editor = item.querySelector('.govuk-custom-rule-editor');
                const editBtn = item.querySelector('.govuk-edit-custom-rule');
                if (editor) {
                    editor.classList.remove('d-none');
                }
                if (editBtn) {
                    editBtn.textContent = doneLabel;
                }
                const descriptionInput = item.querySelector('.govuk-custom-description');
                if (descriptionInput) {
                    descriptionInput.focus();
                }
            }

            function buildRuleNode(description, selector, declarations, openEditorOnCreate) {
                const item = document.createElement('div');
                item.className = 'govuk-custom-rule-item list-group-item';

                const summary = document.createElement('div');
                summary.className = 'govuk-custom-rule-summary d-flex justify-content-between align-items-center gap-2';

                const labels = document.createElement('div');
                labels.className = 'govuk-custom-rule-labels min-w-0';

                const title = document.createElement('div');
                title.className = 'govuk-custom-rule-title text-truncate';
                title.textContent = getRuleTitle(description, selector);

                const subtitle = document.createElement('div');
                subtitle.className = 'govuk-custom-rule-subtitle text-body-secondary small text-truncate';
                const showSubtitle = (description || '').trim() !== '' && (selector || '').trim() !== '';
                subtitle.textContent = (selector || '').trim();
                if (!showSubtitle) {
                    subtitle.classList.add('d-none');
                }

                labels.appendChild(title);
                labels.appendChild(subtitle);

                const actions = document.createElement('div');
                actions.className = 'd-flex gap-1 flex-shrink-0';

                const editBtn = document.createElement('button');
                editBtn.type = 'button';
                editBtn.className = 'btn btn-sm btn-secondary govuk-edit-custom-rule';
                editBtn.textContent = editLabel;

                const deleteBtn = document.createElement('button');
                deleteBtn.type = 'button';
                deleteBtn.className = 'btn btn-sm btn-danger govuk-delete-custom-rule';
                deleteBtn.textContent = deleteLabel;

                actions.appendChild(editBtn);
                actions.appendChild(deleteBtn);
                summary.appendChild(labels);
                summary.appendChild(actions);

                const editor = document.createElement('div');
                editor.className = 'govuk-custom-rule-editor mt-3 border-top pt-3' + (openEditorOnCreate ? '' : ' d-none');

                const descriptionGroup = document.createElement('div');
                descriptionGroup.className = 'mb-2';
                const descriptionLabelEl = document.createElement('label');
                descriptionLabelEl.className = 'form-label';
                descriptionLabelEl.textContent = descriptionLabel;
                const descriptionInput = document.createElement('input');
                descriptionInput.type = 'text';
                descriptionInput.className = 'form-control govuk-custom-description';
                descriptionInput.name = customCssDescriptionName;
                descriptionInput.value = description || '';
                descriptionInput.placeholder = descriptionPlaceholder;
                descriptionGroup.appendChild(descriptionLabelEl);
                descriptionGroup.appendChild(descriptionInput);

                const selectorGroup = document.createElement('div');
                selectorGroup.className = 'mb-2';
                const selectorLabel = document.createElement('label');
                selectorLabel.className = 'form-label';
                selectorLabel.textContent = itemSelectorLabel;
                const selectorInput = document.createElement('input');
                selectorInput.type = 'text';
                selectorInput.className = 'form-control govuk-custom-selector';
                selectorInput.name = customCssSelectorName;
                selectorInput.value = selector || '';
                selectorInput.placeholder = '.list-group-item:hover';
                selectorGroup.appendChild(selectorLabel);
                selectorGroup.appendChild(selectorInput);

                const declarationsGroup = document.createElement('div');
                declarationsGroup.className = 'mb-2';
                const declarationsLabel = document.createElement('label');
                declarationsLabel.className = 'form-label';
                declarationsLabel.textContent = cssDeclarationsLabel;
                const declarationsInput = document.createElement('textarea');
                declarationsInput.className = 'form-control govuk-custom-declarations';
                declarationsInput.name = customCssDeclarationsName;
                declarationsInput.rows = 4;
                declarationsInput.value = declarations || '';
                declarationsInput.placeholder = 'background: #f3f2f1;';
                declarationsGroup.appendChild(declarationsLabel);
                declarationsGroup.appendChild(declarationsInput);

                const doneBtn = document.createElement('button');
                doneBtn.type = 'button';
                doneBtn.className = 'btn btn-light btn-sm govuk-cancel-custom-rule';
                doneBtn.textContent = doneLabel;

                editor.appendChild(descriptionGroup);
                editor.appendChild(selectorGroup);
                editor.appendChild(declarationsGroup);
                editor.appendChild(doneBtn);

                item.appendChild(summary);
                item.appendChild(editor);

                if (openEditorOnCreate) {
                    editBtn.textContent = doneLabel;
                    title.textContent = newRuleLabel;
                }

                descriptionInput.addEventListener('input', function () {
                    syncRuleSummary(item);
                });
                selectorInput.addEventListener('input', function () {
                    syncRuleSummary(item);
                });

                return item;
            }

            addBtn.addEventListener('click', function (event) {
                event.preventDefault();
                const item = buildRuleNode('', '', '', true);
                container.appendChild(item);
                updateEmptyState();
                openEditor(item);
            });

            container.addEventListener('click', function (event) {
                const editBtn = event.target.closest('.govuk-edit-custom-rule');
                if (editBtn) {
                    event.preventDefault();
                    const item = editBtn.closest('.govuk-custom-rule-item');
                    const editor = item?.querySelector('.govuk-custom-rule-editor');
                    if (!item || !editor) {
                        return;
                    }
                    if (editor.classList.contains('d-none')) {
                        openEditor(item);
                    } else {
                        closeEditor(item);
                        syncRuleSummary(item);
                    }
                    return;
                }

                const cancelBtn = event.target.closest('.govuk-cancel-custom-rule');
                if (cancelBtn) {
                    event.preventDefault();
                    const item = cancelBtn.closest('.govuk-custom-rule-item');
                    if (!item) {
                        return;
                    }
                    closeEditor(item);
                    syncRuleSummary(item);
                    return;
                }

                const deleteBtn = event.target.closest('.govuk-delete-custom-rule');
                if (deleteBtn) {
                    event.preventDefault();
                    deleteBtn.closest('.govuk-custom-rule-item')?.remove();
                    updateEmptyState();
                }
            });

            container.addEventListener('input', function (event) {
                if (
                    event.target.classList.contains('govuk-custom-selector')
                    || event.target.classList.contains('govuk-custom-description')
                ) {
                    syncRuleSummary(event.target.closest('.govuk-custom-rule-item'));
                }
            });

            updateEmptyState();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initGovukCustomCssRules);
        } else {
            initGovukCustomCssRules();
        }
    })();
</script>
