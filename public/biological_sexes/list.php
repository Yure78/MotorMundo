<?php
declare(strict_types=1);

require __DIR__ . '/../../src/Bootstrap.php';

/* =========================
   Segurança
========================= */
Acl::check('manage_biological_sexes');

/* =========================
   Dados
========================= */
$repo  = new BiologicalSexRepository();
$items = $repo->findAll();

/* =========================
   View
========================= */
$title = I18n::t('biological_sexes');

$content = function () use ($items) {
    $currentLang = $_SESSION['lang'] ?? 'pt';

    require __DIR__ . '/../../src/View/partials/language_selector.php';
    ?>
    <h1><?= I18n::t('biological_sexes') ?></h1>

    <p>
        <a href="create.php"><?= I18n::t('create_new') ?></a>
    </p>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th><?= I18n::t('code') ?></th>
                <th><?= I18n::t('actions') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= (int)$item->id ?></td>
                <td><?= htmlspecialchars($item->code) ?></td>
                <td>
                    <!-- Editar -->
                    <a href="edit.php?id=<?= (int)$item->id ?>">
                        <?= I18n::t('edit') ?>
                    </a>

                    |

                    <!-- Histórico -->
                    <a href="/MotorMundo/public/logs.php?entity=biological_sex&id=<?= (int)$item->id ?>">
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
