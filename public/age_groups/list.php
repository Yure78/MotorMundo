<?php
require __DIR__ . '/../../src/Bootstrap.php';

Acl::check('manage_age_groups');

$repo = new AgeGroupRepository();
$items = $repo->findAll();

$title = I18n::t('age_groups');

$content = function () use ($items) {
?>
<h1><?= I18n::t('age_groups') ?></h1>

<p>
    <a href="create.php"><?= I18n::t('create_new') ?></a>
</p>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th><?= I18n::t('code') ?></th>
        <th><?= I18n::t('range') ?></th>
        <th><?= I18n::t('actions') ?></th>
    </tr>

    <?php foreach ($items as $item): ?>
    <tr>
        <td><?= $item->id ?></td>
        <td><?= htmlspecialchars($item->code) ?></td>
        <td><?= $item->minAge ?> – <?= $item->maxAge ?></td>
        <td>
            <a href="edit.php?id=<?= $item->id ?>"><?= I18n::t('edit') ?></a>
            |
            <a href="/MotorMundo/public/logs.php?entity=age_group&id=<?= $item->id ?>">
                <?= I18n::t('history') ?>
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php
};

require __DIR__ . '/../../src/View/layout.php';

