<?php
declare(strict_types=1);

require __DIR__ . '/../../src/Bootstrap.php';

Acl::check('manage_i18n');

$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'pt');

$repo = new I18nTranslationRepository();
$items = $repo->findAllByLanguage($lang);

$title = I18n::t('translations');

$content = function () use ($items, $lang) {
    ?>
    <h1><?= I18n::t('translations') ?></h1>

    <?php require __DIR__ . '/../../src/View/partials/language_selector.php'; ?>

    <p>
        <a href="create.php" class="btn-new">
            + <?= I18n::t('create_new') ?>
        </a>
    </p>


    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th><?= I18n::t('key') ?></th>
                <th><?= I18n::t('value') ?></th>
                <th><?= I18n::t('actions') ?></th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['code']) ?></td>
                <td><?= htmlspecialchars($item['value'] ?? '') ?></td>
                <td>
                    <a href="edit.php?code=<?= urlencode($item['code']) ?>&lang=<?= $lang ?>">
                        <?= I18n::t('edit') ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
    <?php
};

require __DIR__ . '/../../src/View/layout.php';

