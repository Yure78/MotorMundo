<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/../../src/Bootstrap.php';

Acl::check('rebuild_i18n_cache');

$langRepo = new LanguageRepository();

/* =========================
   Executa rebuild
========================= */
$languages = $langRepo->findAll();

foreach ($languages as $lang) {
    I18nCacheBuilder::build($lang['code']);
}

?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang'] ?? 'pt') ?>">
<head>
    <meta charset="UTF-8">
    <title><?= I18n::t('rebuild_translations') ?></title>
</head>
<body>

<h1><?= I18n::t('rebuild_translations') ?></h1>

<p><?= I18n::t('rebuild_success') ?></p>

<ul>
<?php foreach ($languages as $lang): ?>
    <li><?= htmlspecialchars($lang['name']) ?> (<?= htmlspecialchars($lang['code']) ?>)</li>
<?php endforeach; ?>
</ul>

<p>
    <a href="list.php"><?= I18n::t('back') ?></a>
</p>

</body>
</html>

