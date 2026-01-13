<?php
require __DIR__ . '/../../src/Bootstrap.php';

Acl::check('manage_age_groups');

$id = (int)($_GET['id'] ?? 0);
$repo = new AgeGroupRepository();
$group = $repo->findById($id);

if (!$group) {
    http_response_code(404);
    exit('Not found');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $before = [
        'code' => $group->code,
        'minAge' => $group->minAge,
        'maxAge' => $group->maxAge
    ];

    $group->code   = trim($_POST['code']);
    $group->minAge = (int)$_POST['min_age'];
    $group->maxAge = (int)$_POST['max_age'];

    $repo->update($group);

    EntityAuditLogger::log(
        'age_group',
        $group->id,
        'UPDATE',
        $before,
        [
            'code' => $group->code,
            'minAge' => $group->minAge,
            'maxAge' => $group->maxAge
        ]
    );

    ActionLogger::log(
        'age_group_updated',
        ActionLogger::INFO,
        ['id' => $group->id]
    );

    header('Location: list.php');
    exit;
}

$title = I18n::t('edit_age_group');

$content = function () use ($group) {
?>
<h1><?= I18n::t('edit_age_group') ?></h1>

<form method="post">
    <?= I18n::t('code') ?>:<br>
    <input name="code" value="<?= htmlspecialchars($group->code) ?>" required><br><br>

    <?= I18n::t('min_age') ?>:<br>
    <input type="number" name="min_age" value="<?= $group->minAge ?>" required><br><br>

    <?= I18n::t('max_age') ?>:<br>
    <input type="number" name="max_age" value="<?= $group->maxAge ?>" required><br><br>

    <button><?= I18n::t('save') ?></button>
</form>
<?php
};

require __DIR__ . '/../../src/View/layout.php';

