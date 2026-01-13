<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/../../src/Bootstrap.php';

/* =========================
   Segurança
========================= */
Acl::check('manage_biological_sexes');

/* =========================
   Repositórios
========================= */
$sexRepo = new BiologicalSexRepository();
$trRepo  = new BiologicalSexTranslationRepository();

$languageRepo = new LanguageRepository();
$languages = $languageRepo->findAll();

/* =========================
   POST
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        /* =========================
           1. Criar entidade principal
        ========================= */
        $sex = new BiologicalSex(
            null,
            trim($_POST['code']),
            isset($_POST['can_gestate']),
            isset($_POST['can_fertilize'])
        );

        $sexId = $sexRepo->create($sex);

        /* =========================
           2. Auditoria da entidade
        ========================= */
        EntityAuditLogger::log(
            'biological_sex',
            $sexId,
            'CREATE',
            null,
            [
                'code'          => $_POST['code'],
                'can_gestate'   => isset($_POST['can_gestate']),
                'can_fertilize' => isset($_POST['can_fertilize'])
            ]
        );

        /* =========================
           3. Criar tradução
        ========================= */
        $translation = new BiologicalSexTranslation(
            $sexId,
            $_POST['language'],
            trim($_POST['label']),
            $_POST['description'] !== '' ? trim($_POST['description']) : null
        );

        $trRepo->upsert($translation);

        /* =========================
           4. Auditoria da tradução
        ========================= */
        EntityAuditLogger::log(
            'biological_sex_translation',
            $sexId,
            'CREATE',
            null,
            [
                'language'    => $_POST['language'],
                'label'       => $_POST['label'],
                'description' => $_POST['description'] ?? null
            ]
        );

        /* =========================
           5. Log de ação
        ========================= */
        ActionLogger::log(
            'biological_sex_created',
            ActionLogger::INFO,
            [
                'id'       => $sexId,
                'code'     => $_POST['code'],
                'language' => $_POST['language']
            ]
        );

        /* =========================
           6. Redirect
        ========================= */
        header('Location: list.php');
        exit;

    } catch (Throwable $e) {

        /* =========================
           Log de erro
        ========================= */
        ActionLogger::log(
            'biological_sex_create_failed',
            ActionLogger::ERROR,
            [
                'message' => $e->getMessage()
            ]
        );

        throw $e; // Em dev: deixa explodir
    }
}

/* =========================
   View / Template
========================= */
$title = I18n::t('create_biological_sex');

$content = function () use ($languages) {
    ?>
    <h1><?= I18n::t('create_biological_sex') ?></h1>

    <?php
    // Seletor de idioma (partial)
    $currentLang = $_SESSION['lang'] ?? 'pt';
    require __DIR__ . '/../../src/View/partials/language_selector.php';
    ?>

    <form method="post">

        <h2><?= I18n::t('technical_data') ?></h2>

        <label>
            <?= I18n::t('code') ?>:<br>
            <input name="code" required>
        </label>
        <br><br>

        <label>
            <input type="checkbox" name="can_gestate">
            <?= I18n::t('can_gestate') ?>
        </label>
        <br>

        <label>
            <input type="checkbox" name="can_fertilize">
            <?= I18n::t('can_fertilize') ?>
        </label>

        <h2><?= I18n::t('translation') ?></h2>

        <label>
            <?= I18n::t('language') ?>:<br>
            <select name="language" required>
                <?php foreach ($languages as $lang): ?>
                    <option value="<?= htmlspecialchars($lang['code']) ?>"
                        <?= $lang['is_default'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($lang['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <br><br>

        <label>
            <?= I18n::t('label') ?>:<br>
            <input name="label" required>
        </label>
        <br><br>

        <label>
            <?= I18n::t('description') ?>:<br>
            <textarea name="description"></textarea>
        </label>
        <br><br>

        <button><?= I18n::t('save') ?></button>
    </form>
    <?php
};

require __DIR__ . '/../../src/View/layout.php';
