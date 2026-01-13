<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/../../src/Bootstrap.php';

$keyRepo  = new I18nKeyRepository();
$trRepo   = new I18nTranslationRepository();
$langRepo = new LanguageRepository();

/* =========================
   Idiomas disponíveis
========================= */
$languages = $langRepo->findAll();

/* =========================
   Processa POST
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $keyCode = trim($_POST['key_code']);

    if ($keyCode === '') {
        die('Chave inválida');
    }

    // 1️⃣ Cria a chave i18n
    $keyId = $keyRepo->create($keyCode);

    // 2️⃣ Salva traduções por idioma
    foreach ($languages as $lang) {
        $value = trim($_POST['value'][$lang['code']] ?? '');

        if ($value !== '') {
            $trRepo->upsert(
                $keyId,
                $lang['code'],
                $value
            );

            // 3️⃣ Regenera cache apenas se houver tradução
            I18nCacheBuilder::build($lang['code']);
        }
    }

    header('Location: list.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang'] ?? 'pt') ?>">
<head>
    <meta charset="UTF-8">
    <title><?= I18n::t('new_translation_key') ?></title>
</head>
<body>

<h1><?= I18n::t('new_translation_key') ?></h1>

<form method="post">

    <h2><?= I18n::t('technical_data') ?></h2>

    <label>
        <?= I18n::t('key') ?>:<br>
        <input type="text"
               name="key_code"
               required
               placeholder="ex: technical_data">
    </label>

    <h2><?= I18n::t('translations') ?></h2>

    <?php foreach ($languages as $lang): ?>
        <fieldset style="margin-bottom: 10px;">
            <legend><?= htmlspecialchars($lang['name']) ?></legend>

            <textarea name="value[<?= htmlspecialchars($lang['code']) ?>]"
                      rows="3"
                      cols="50"
                      placeholder="<?= I18n::t('enter_translation') ?>"></textarea>
        </fieldset>
    <?php endforeach; ?>

    <button type="submit"><?= I18n::t('save') ?></button>
</form>

<p>
    <a href="list.php"><?= I18n::t('back') ?></a>
</p>

</body>
</html>
