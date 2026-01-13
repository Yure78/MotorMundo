<?php
require __DIR__ . '/../../src/Bootstrap.php';

Acl::check('manage_age_groups');

$repo = new AgeGroupRepository();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $group = new AgeGroup(
        null,
        trim($_POST['code']),
        (int)$_POST['min_age'],
        (int)$_POST['max_age']
    );

    $id = $repo->create($group);

    EntityAuditLogger::log(
        'age_group',
        $id,
        'CREATE',
        null,
        [
            'code' => $group->code,
            'minAge' => $group->minAge,
            'maxAge' => $group->maxAge
        ]
    );

    ActionLogger::log(
        'age_group_created',
        ActionLogger::INFO,
        ['id' => $id, 'code' => $group->code]
    );

    header('Location: list.php');
    exit;
}

$title = I18n::t('create_age_group');

$content = function () {
?>
<h1><?= I18n::t('create_age_group') ?></h1>

<form method="post">
    <?= I18n::t('code') ?>:<br>
    <input name="code" required><br><br>

    <?= I18n::t('min_age') ?>:<br>
    <input type="number" name="min_age" required><br><br>

    <?= I18n::t('max_age') ?>:<br>
    <input type="number" name="max_age" required><br><br>

    <button><?= I18n::t('save') ?></button>
</form>
<?php
};

require __DIR__ . '/../../src/View/layout.php';

