<?php
declare(strict_types=1);

require __DIR__ . '/../../src/Bootstrap.php';

Acl::check('manage_i18n');

$key  = $_GET['code'] ?? null;
$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'pt');

if (!$key) {
    throw new RuntimeException('Missing translation key');
}

$repo = new I18nTranslationRepository();

/**
 * Recupera todas as traduções do idioma
 * e encontra a chave desejada
 */
$items = $repo->findAllByLanguage($lang);

$current = null;
foreach ($items as $item) {
    if ($item['code'] === $key) {
        $current = $item;
        break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $repo->upsertByCode(
        $key,
        $lang,
        $_POST['value']
    );

    I18nCacheBuilder::build($lang);

    ActionLogger::log(
        'update',
        'i18n_translations',
        null,
        [
            'key'   => $key,
            'lang'  => $lang,
            'value' => $_POST['value']
        ]
    );

    header("Location: list.php?lang=$lang");
    exit;
}

$title = I18n::t('edit_translation');

$content = function () use ($key, $lang, $current) {
    ?>
    <h1><?= I18n::t('edit_translation') ?></h1>

    <p>
        <strong><?= htmlspecialchars($key) ?></strong>
        (<?= htmlspecialchars($lang) ?>)
    </p>

    <form method="post">

        <label>
            <?= I18n::t('value') ?><br>
            <textarea name="value" rows="4" cols="60"><?= htmlspecialchars($current['value'] ?? '') ?></textarea>
        </label><br><br>

        <button><?= I18n::t('save') ?></button>
        <p>
            <a href="list.php" class="btn-back">
                ← <?= I18n::t('back') ?>
            </a>
        </p>

    </form>
    <?php
};

require __DIR__ . '/../../src/View/layout.php';

