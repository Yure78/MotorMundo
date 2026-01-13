<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/../../src/Bootstrap.php';

$keyRepo  = new I18nKeyRepository();
$trRepo   = new I18nTranslationRepository();
$langRepo = new LanguageRepository();

/* =========================
   Validação da chave
========================= */
$keyCode = $_GET['key'] ?? null;

if (!$keyCode) {
    die('Chave inválida');
}

/* =========================
   Buscar ID da chave
========================= */
$db = DatabaseConnection::get();

$stmt = $db->prepare(
    "SELECT id FROM i18n_keys WHERE code = ?"
);
$stmt->bind_param('s', $keyCode);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    die('Chave não encontrada');
}

$keyId = (int)$row['id'];

/* =========================
   Processar exclusão
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1️⃣ Idiomas existentes (para regenerar cache depois)
    $languages = $langRepo->findAll();

    // 2️⃣ Remove traduções
    $stmt = $db->prepare(
        "DELETE FROM i18n_translations WHERE i18n_key_id = ?"
    );
    $stmt->bind_param('i', $keyId);
    $stmt->execute();

    // 3️⃣ Remove chave
    $stmt = $db->prepare(
        "DELETE FROM i18n_keys WHERE id = ?"
    );
    $stmt->bind_param('i', $keyId);
    $stmt->execute();

    // 4️⃣ Regenera cache de todos os idiomas
    foreach ($languages as $lang) {
        I18nCacheBuilder::build($lang['code']);
    }

    header('Location: list.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang'] ?? 'pt') ?>">
<head>
    <meta charset="UTF-8">
    <title><?= I18n::t('delete') ?></title>
</head>
<body>

<h1><?= I18n::t('delete_translation_key') ?></h1>

<p>
    <?= I18n::t('confirm_delete_key') ?>
</p>

<p>
    <strong><?= htmlspecialchars($keyCode) ?></strong>
</p>

<form method="post">
    <button type="submit"><?= I18n::t('delete') ?></button>
    <a href="list.php"><?= I18n::t('cancel') ?></a>
</form>

</body>
</html>
