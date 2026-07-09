<?php

use humhub\helpers\Html;
use humhub\modules\thiscoveryTheme\libs\MobileMenuHelper;
use humhub\modules\thiscoveryTheme\models\ConfigForm;

/* @var ConfigForm $model */
/* @var string $linkIdName */
/* @var string $linkLabelName */
/* @var string $linkUrlName */
/* @var string $linkSortOrderName */
/* @var string $linksSentName */

$links = $model->hamburgerCustomLinks;
?>
<div class="text-body-secondary mb-3">
    <?= Yii::t('ThiscoveryThemeModule.base', 'Add extra links to the mobile hamburger panel. Each link has a label, URL, and sort order.') ?>
</div>

<?= Html::hiddenInput($linksSentName, '1') ?>

<div id="tc-hamburger-custom-links-empty" class="text-body-secondary mb-2<?= empty($links) ? '' : ' d-none' ?>">
    <?= Yii::t('ThiscoveryThemeModule.base', 'No custom hamburger links yet.') ?>
</div>

<div class="list-group mb-2" id="tc-hamburger-custom-links">
    <?php foreach ($links as $link): ?>
        <?php
        $id = (string)($link['id'] ?? '');
        $label = trim((string)($link['label'] ?? ''));
        $url = trim((string)($link['url'] ?? ''));
        $sortOrder = (int)($link['sortOrder'] ?? 100);
        $title = $label !== '' ? $label : Yii::t('ThiscoveryThemeModule.base', 'Untitled link');
        ?>
        <div class="tc-hamburger-custom-link-item list-group-item" data-link-id="<?= Html::encode($id) ?>">
            <div class="tc-hamburger-custom-link-summary d-flex justify-content-between align-items-center gap-2">
                <div class="tc-hamburger-custom-link-labels min-w-0">
                    <div class="tc-hamburger-custom-link-title text-truncate"><?= Html::encode($title) ?></div>
                    <div class="tc-hamburger-custom-link-subtitle text-body-secondary small text-truncate<?= $url === '' ? ' d-none' : '' ?>">
                        <?= Html::encode($url) ?>
                    </div>
                </div>
                <div class="d-flex gap-1 flex-shrink-0">
                    <button type="button" class="btn btn-sm btn-secondary tc-edit-hamburger-custom-link"><?= Yii::t('ThiscoveryThemeModule.base', 'Edit') ?></button>
                    <button type="button" class="btn btn-sm btn-danger tc-delete-hamburger-custom-link"><?= Yii::t('ThiscoveryThemeModule.base', 'Delete') ?></button>
                </div>
            </div>
            <div class="tc-hamburger-custom-link-editor d-none mt-3 border-top pt-3">
                <?= Html::hiddenInput($linkIdName, $id, ['class' => 'tc-hamburger-custom-link-id']) ?>
                <div class="mb-2">
                    <label class="form-label"><?= Yii::t('ThiscoveryThemeModule.base', 'Label') ?></label>
                    <input type="text" class="form-control tc-hamburger-custom-link-label" name="<?= Html::encode($linkLabelName) ?>" value="<?= Html::encode($label) ?>" placeholder="<?= Html::encode(Yii::t('ThiscoveryThemeModule.base', 'e.g. Help centre')) ?>">
                </div>
                <div class="mb-2">
                    <label class="form-label"><?= Yii::t('ThiscoveryThemeModule.base', 'Link') ?></label>
                    <input type="text" class="form-control tc-hamburger-custom-link-url" name="<?= Html::encode($linkUrlName) ?>" value="<?= Html::encode($url) ?>" placeholder="/help or https://example.com">
                </div>
                <div class="mb-2">
                    <label class="form-label"><?= Yii::t('ThiscoveryThemeModule.base', 'Order') ?></label>
                    <input type="number" class="form-control tc-hamburger-custom-link-sort-order" name="<?= Html::encode($linkSortOrderName) ?>" value="<?= $sortOrder ?>" min="1" step="1">
                </div>
                <button type="button" class="btn btn-light btn-sm tc-cancel-hamburger-custom-link"><?= Yii::t('ThiscoveryThemeModule.base', 'Done') ?></button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<button type="button" class="btn btn-secondary btn-sm" id="tc-add-hamburger-custom-link"><?= Yii::t('ThiscoveryThemeModule.base', 'Add link') ?></button>

<script <?= Html::nonce() ?>>
    (function () {
        function initHamburgerCustomLinks() {
            const container = document.getElementById('tc-hamburger-custom-links');
            const emptyState = document.getElementById('tc-hamburger-custom-links-empty');
            const addBtn = document.getElementById('tc-add-hamburger-custom-link');
            if (!container || !addBtn || addBtn.dataset.tcHamburgerCustomLinksInit === '1') {
                return;
            }

            addBtn.dataset.tcHamburgerCustomLinksInit = '1';

            const linkIdName = <?= json_encode($linkIdName, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const linkLabelName = <?= json_encode($linkLabelName, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const linkUrlName = <?= json_encode($linkUrlName, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const linkSortOrderName = <?= json_encode($linkSortOrderName, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const labelText = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'Label'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const linkText = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'Link'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const orderText = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'Order'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const editLabel = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'Edit'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const deleteLabel = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'Delete'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const doneLabel = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'Done'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const untitledLabel = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'Untitled link'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const newLinkLabel = <?= json_encode(Yii::t('ThiscoveryThemeModule.base', 'New link'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
            const idPrefix = <?= json_encode(MobileMenuHelper::CUSTOM_HAMBURGER_LINK_ID_PREFIX, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

            function generateLinkId() {
                const randomPart = Math.random().toString(36).slice(2, 12);
                return idPrefix + randomPart;
            }

            function updateEmptyState() {
                if (!emptyState) {
                    return;
                }
                emptyState.classList.toggle('d-none', container.querySelectorAll('.tc-hamburger-custom-link-item').length > 0);
            }

            function closeEditor(item) {
                const editor = item.querySelector('.tc-hamburger-custom-link-editor');
                const editBtn = item.querySelector('.tc-edit-hamburger-custom-link');
                if (editor) {
                    editor.classList.add('d-none');
                }
                if (editBtn) {
                    editBtn.textContent = editLabel;
                }
            }

            function closeAllEditors(exceptItem) {
                container.querySelectorAll('.tc-hamburger-custom-link-item').forEach(function (item) {
                    if (item !== exceptItem) {
                        closeEditor(item);
                    }
                });
            }

            function syncSummary(item) {
                const labelInput = item.querySelector('.tc-hamburger-custom-link-label');
                const urlInput = item.querySelector('.tc-hamburger-custom-link-url');
                const title = item.querySelector('.tc-hamburger-custom-link-title');
                const subtitle = item.querySelector('.tc-hamburger-custom-link-subtitle');
                const label = labelInput ? labelInput.value.trim() : '';
                const url = urlInput ? urlInput.value.trim() : '';

                if (title) {
                    title.textContent = label !== '' ? label : untitledLabel;
                }
                if (subtitle) {
                    subtitle.textContent = url;
                    subtitle.classList.toggle('d-none', url === '');
                }
            }

            function openEditor(item) {
                closeAllEditors(item);
                const editor = item.querySelector('.tc-hamburger-custom-link-editor');
                const editBtn = item.querySelector('.tc-edit-hamburger-custom-link');
                if (editor) {
                    editor.classList.remove('d-none');
                }
                if (editBtn) {
                    editBtn.textContent = doneLabel;
                }
                const labelInput = item.querySelector('.tc-hamburger-custom-link-label');
                if (labelInput) {
                    labelInput.focus();
                }
            }

            function buildLinkNode(id, label, url, sortOrder, openEditorOnCreate) {
                const item = document.createElement('div');
                item.className = 'tc-hamburger-custom-link-item list-group-item';
                item.dataset.linkId = id;

                const summary = document.createElement('div');
                summary.className = 'tc-hamburger-custom-link-summary d-flex justify-content-between align-items-center gap-2';

                const labels = document.createElement('div');
                labels.className = 'tc-hamburger-custom-link-labels min-w-0';

                const title = document.createElement('div');
                title.className = 'tc-hamburger-custom-link-title text-truncate';
                title.textContent = label.trim() !== '' ? label.trim() : untitledLabel;

                const subtitle = document.createElement('div');
                subtitle.className = 'tc-hamburger-custom-link-subtitle text-body-secondary small text-truncate';
                subtitle.textContent = url.trim();
                if (url.trim() === '') {
                    subtitle.classList.add('d-none');
                }

                labels.appendChild(title);
                labels.appendChild(subtitle);

                const actions = document.createElement('div');
                actions.className = 'd-flex gap-1 flex-shrink-0';

                const editBtn = document.createElement('button');
                editBtn.type = 'button';
                editBtn.className = 'btn btn-sm btn-secondary tc-edit-hamburger-custom-link';
                editBtn.textContent = openEditorOnCreate ? doneLabel : editLabel;

                const deleteBtn = document.createElement('button');
                deleteBtn.type = 'button';
                deleteBtn.className = 'btn btn-sm btn-danger tc-delete-hamburger-custom-link';
                deleteBtn.textContent = deleteLabel;

                actions.appendChild(editBtn);
                actions.appendChild(deleteBtn);
                summary.appendChild(labels);
                summary.appendChild(actions);

                const editor = document.createElement('div');
                editor.className = 'tc-hamburger-custom-link-editor mt-3 border-top pt-3' + (openEditorOnCreate ? '' : ' d-none');

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.className = 'tc-hamburger-custom-link-id';
                idInput.name = linkIdName;
                idInput.value = id;

                const labelGroup = document.createElement('div');
                labelGroup.className = 'mb-2';
                const labelLabel = document.createElement('label');
                labelLabel.className = 'form-label';
                labelLabel.textContent = labelText;
                const labelInput = document.createElement('input');
                labelInput.type = 'text';
                labelInput.className = 'form-control tc-hamburger-custom-link-label';
                labelInput.name = linkLabelName;
                labelInput.value = label;
                labelGroup.appendChild(labelLabel);
                labelGroup.appendChild(labelInput);

                const urlGroup = document.createElement('div');
                urlGroup.className = 'mb-2';
                const urlLabel = document.createElement('label');
                urlLabel.className = 'form-label';
                urlLabel.textContent = linkText;
                const urlInput = document.createElement('input');
                urlInput.type = 'text';
                urlInput.className = 'form-control tc-hamburger-custom-link-url';
                urlInput.name = linkUrlName;
                urlInput.value = url;
                urlInput.placeholder = '/help or https://example.com';
                urlGroup.appendChild(urlLabel);
                urlGroup.appendChild(urlInput);

                const orderGroup = document.createElement('div');
                orderGroup.className = 'mb-2';
                const orderLabel = document.createElement('label');
                orderLabel.className = 'form-label';
                orderLabel.textContent = orderText;
                const orderInput = document.createElement('input');
                orderInput.type = 'number';
                orderInput.className = 'form-control tc-hamburger-custom-link-sort-order';
                orderInput.name = linkSortOrderName;
                orderInput.value = sortOrder;
                orderInput.min = '1';
                orderInput.step = '1';
                orderGroup.appendChild(orderLabel);
                orderGroup.appendChild(orderInput);

                const doneBtn = document.createElement('button');
                doneBtn.type = 'button';
                doneBtn.className = 'btn btn-light btn-sm tc-cancel-hamburger-custom-link';
                doneBtn.textContent = doneLabel;

                editor.appendChild(idInput);
                editor.appendChild(labelGroup);
                editor.appendChild(urlGroup);
                editor.appendChild(orderGroup);
                editor.appendChild(doneBtn);

                item.appendChild(summary);
                item.appendChild(editor);

                if (openEditorOnCreate) {
                    title.textContent = newLinkLabel;
                }

                labelInput.addEventListener('input', function () {
                    syncSummary(item);
                });
                urlInput.addEventListener('input', function () {
                    syncSummary(item);
                });

                return item;
            }

            addBtn.addEventListener('click', function (event) {
                event.preventDefault();
                const nextOrder = container.querySelectorAll('.tc-hamburger-custom-link-item').length + 1;
                const item = buildLinkNode(generateLinkId(), '', '', nextOrder, true);
                container.appendChild(item);
                updateEmptyState();
                openEditor(item);
            });

            container.addEventListener('click', function (event) {
                const editBtn = event.target.closest('.tc-edit-hamburger-custom-link');
                if (editBtn) {
                    event.preventDefault();
                    const item = editBtn.closest('.tc-hamburger-custom-link-item');
                    const editor = item?.querySelector('.tc-hamburger-custom-link-editor');
                    if (!item || !editor) {
                        return;
                    }
                    if (editor.classList.contains('d-none')) {
                        openEditor(item);
                    } else {
                        closeEditor(item);
                        syncSummary(item);
                    }
                    return;
                }

                const cancelBtn = event.target.closest('.tc-cancel-hamburger-custom-link');
                if (cancelBtn) {
                    event.preventDefault();
                    const item = cancelBtn.closest('.tc-hamburger-custom-link-item');
                    if (!item) {
                        return;
                    }
                    closeEditor(item);
                    syncSummary(item);
                    return;
                }

                const deleteBtn = event.target.closest('.tc-delete-hamburger-custom-link');
                if (deleteBtn) {
                    event.preventDefault();
                    deleteBtn.closest('.tc-hamburger-custom-link-item')?.remove();
                    updateEmptyState();
                }
            });

            updateEmptyState();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initHamburgerCustomLinks);
        } else {
            initHamburgerCustomLinks();
        }
    })();
</script>
