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
   Validação de entrada
========================= */
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$id) {
    http_response_code(400);
    echo 'Invalid ID';
    exit;
}

/* =========================
   Repositórios
========================= */
$sexRepo = new BiologicalSexRepository();
$trRepo  = new BiologicalSexTranslationRepository();
$langRepo = new LanguageRepository();

/* =========================
   Dados atuais
========================= */
$sex = $sexRepo->findById($id);
if (!$sex) {
    http_response_code(404);
    echo 'Not found';
    exit;
}

$languages = $langRepo->findAll();
$currentLang = $_SESSION['lang'] ?? 'pt';

$translation = $trRepo->findOne($id, $currentLang);

/* =========================
   POST
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        /* =========================
           BEFORE (para auditoria)
        ========================= */
        $beforeSex = [
            'code'          => $sex->code,
            'can_gestate'   => $sex->canGestate,
            'can_fertilize' => $sex->canFertilize
        ];

        $beforeTranslation = $translation ? [
            'language'    => $currentLang,
            'label'       => $translation->label,
            'description' => $translation->description
        ] : null;

        /* =========================
           Atualizar entidade
        ========================= */
        $sex->code = trim($_POST['code']);
        $sex->canGestate   = isset($_POST['can_gestate']);
        $sex->canFertilize = isset($_POST['can_fertilize']);

        $sexRepo->update($sex);

        /* =========================
           Auditoria entidade
        ========================= */
        EntityAuditLogger::log(
            'biological_sex',
            $id,
            'UPDATE',
            $beforeSex,
            [
                'code'          => $sex->code,
                'can_gestate'   => $sex->canGestate,
                'can_fertilize' => $sex->canFertilize
            ]
        );

        /* =========================
           Atualizar / criar tradução
        ========================= */
        $newTranslation = new BiologicalSexTranslation(
            $id,
            $_POST['language'],
            trim($_POST['label']),
            $_POST['description'] !== '' ? trim($_POST['description']) : null
        );

        $trRepo->upsert($newTranslation);

        /* =========================
           Auditoria tradução
        ========================= */
        EntityAuditLogger::log(
            'biological_sex_translation',
            $id,
            $beforeTranslation ? 'UPDATE' : 'CREATE',
            $beforeTranslation,
            [
                'language'    => $_POST['language'],
                'label'       => $_POST['label'],
                'description' => $_POST['description'] ?? null
            ]
        );

        /* =========================
           Log de ação
        ========================= */
        ActionLogger::log(
            'biological_sex_updated',
            ActionLogger::INFO,
            [
                'id'       => $id,
                'code'     => $sex->code,
                'language' => $_POST['language']
            ]
        );

        header('Location: list.php');
        exit;

    } catch (Throwable $e) {

        ActionLogger::log(
            'biological_sex_update_failed',
            ActionLogger::ERROR,
            [
                'id'      => $id,
                'message' => $e->getMessage()
            ]
        );

        throw $e;
    }
}

/* =========================
   View
========================= */
$title = I18n::t('edit_biological_sex');

$content = function () use ($sex, $languages, $translation, $currentLang) {
    ?>
    <h1><?= I18n::t('edit_biological_sex') ?></h1>

    <?php
    require __DIR__ . '/../../src/View/partials/language_selector.php';
    ?>

    <form method="post">

        <h2><?= I18n::t('technical_data') ?></h2>

        <label>
            <?= I18n::t('code') ?>:<br>
            <input name="code" value="<?= htmlspecialchars($sex->code) ?>" required>
        </label>
        <br><br>

        <label>
            <input type="checkbox" name="can_gestate" <?= $sex->canGestate ? 'checked' : '' ?>>
            <?= I18n::t('can_gestate') ?>
        </label>
        <br>

        <label>
            <input type="checkbox" name="can_fertilize" <?= $sex->canFertilize ? 'checked' : '' ?>>
            <?= I18n::t('can_fertilize') ?>
        </label>

        <h2><?= I18n::t('translation') ?></h2>

        <input type="hidden" name="language" value="<?= htmlspecialchars($currentLang) ?>">

        <label>
            <?= I18n::t('label') ?>:<br>
            <input name="label"
                   value="<?= htmlspecialchars($translation->label ?? '') ?>"
                   required>
        </label>
        <br><br>

        <label>
            <?= I18n::t('description') ?>:<br>
            <textarea name="description"><?= htmlspecialchars($translation->description ?? '') ?></textarea>
        </label>
        <br><br>

        <button><?= I18n::t('save') ?></button>

        |
        <a href="/MotorMundo/public/logs.php?entity=biological_sex&id=<?= (int)$sex->id ?>">
            <?= I18n::t('history') ?>
        </a>

    </form>
    <?php
};

require __DIR__ . '/../../src/View/layout.php';
