<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/../src/Bootstrap.php';

/* =========================
   Exigir login
========================= */
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang'] ?? 'pt') ?>">
<head>
    <meta charset="UTF-8">
    <title><?= I18n::t('dashboard') ?></title>
</head>
<body>

<h1><?= I18n::t('welcome') ?>, <?= htmlspecialchars($username) ?></h1>

<p><?= I18n::t('choose_action') ?></p>

<ul>

    <?php if (Acl::checkSilent('manage_i18n')): ?>
        <li>
            <a href="i18n/list.php">
                <?= I18n::t('manage_translations') ?>
            </a>
        </li>
    <?php endif; ?>

    <?php if (Acl::checkSilent('rebuild_i18n_cache')): ?>
        <li>
            <a href="i18n/rebuild.php">
                <?= I18n::t('rebuild_translations') ?>
            </a>
        </li>
    <?php endif; ?>

    <?php if (Acl::checkSilent('manage_biological_sexes')): ?>
        <li>
            <a href="biological_sexes/list.php">
                <?= I18n::t('biological_sexes') ?>
            </a>
        </li>
    <?php endif; ?>

    <?php if (Acl::checkSilent('manage_age_groups')): ?>
        <li>
            <a href="age_groups/list.php">
                <?= I18n::t('age_groups') ?>
            </a>
        </li>
    <?php endif; ?>

    <?php if (Acl::checkSilent('manage_species')): ?>
        <li>
            <a href="species/list.php">
                <?= I18n::t('species') ?>
            </a>
        </li>
    <?php endif; ?>

    <?php if (Acl::checkSilent('manage_users')): ?>
        <li>
            <a href="users/list.php">
                <?= I18n::t('users') ?>
            </a>
        </li>
    <?php endif; ?>

</ul>

<hr>

<p>
    <a href="logout.php"><?= I18n::t('logout') ?></a>
</p>

</body>
</html>