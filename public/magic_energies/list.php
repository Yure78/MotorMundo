<?php
declare(strict_types=1);

require __DIR__ . '/../../src/Bootstrap.php';

Acl::check('manage_magic_energies');

$repo   = new MagicEnergyRepository();
$trRepo = new MagicEnergyTranslationRepository();

$items = $repo->findAll();
$lang  = $_SESSION['lang'] ?? 'pt';

$title = I18n::t('magic_energies');

$content = function () use ($items, $trRepo, $lang) {
    ?>
    <h1><?= I18n::t('magic_energies') ?></h1>

    <?php require __DIR__ . '/../../src/View/partials/language_selector.php'; ?>

    <p>
        <a href="create.php"><?= I18n::t('create_new') ?></a>
    </p>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th><?= I18n::t('code') ?></th>
                <th><?= I18n::t('name') ?></th>
                <th><?= I18n::t('actions') ?></th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($items as $item): ?>
            <?php
            $translation = $trRepo->findOne($item->id, $lang);
            $name = $translation?->name ?? $item->code;
            ?>
            <tr>
                <td><?= (int)$item->id ?></td>
                <td><?= htmlspecialchars($item->code) ?></td>
                <td><?= htmlspecialchars($name) ?></td>
                <td>
                    <a href="edit.php?id=<?= (int)$item->id ?>">
                        <?= I18n::t('edit') ?>
                    </a>
                    |
                    <a href="/MotorMundo/public/logs.php?entity=magic_energy&id=<?= (int)$item->id ?>">
                        <?= I18n::t('history') ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
    <?php
};

require __DIR__ . '/../../src/View/layout.php';

