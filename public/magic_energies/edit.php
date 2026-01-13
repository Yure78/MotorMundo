<?php
declare(strict_types=1);

require __DIR__ . '/../../src/Bootstrap.php';

Acl::check('manage_magic_energies');

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    http_response_code(400);
    exit('Invalid ID');
}

$energyRepo = new MagicEnergyRepository();
$trRepo     = new MagicEnergyTranslationRepository();
$langRepo   = new LanguageRepository();

$energy = $energyRepo->findById($id);
if (!$energy) {
    http_response_code(404);
    exit('Not found');
}

$languages   = $langRepo->findAll();
$currentLang = $_SESSION['lang'] ?? 'pt';

$translation = $trRepo->findOne($id, $currentLang);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        /* =========================
           BEFORE
        ========================= */
        $beforeEnergy = [
            'code' => $energy->code
        ];

        $beforeTranslation = $translation ? [
            'language'    => $translation->languageCode,
            'name'        => $translation->name,
            'description' => $translation->description
        ] : null;

        /* =========================
           Atualizar energia
        ========================= */
        $energy->code = trim($_POST['code']);
        $energyRepo->update($energy);

        EntityAuditLogger::log(
            'magic_energy',
            $energy->id,
            'UPDATE',
            $beforeEnergy,
            ['code' => $energy->code]
        );


        /* =========================
           Atualizar / criar tradução
        ========================= */
        $newTranslation = new MagicEnergyTranslation(
            $energy->id,
            $_POST['language'],
            trim($_POST['name']),
            $_POST['description'] !== '' ? trim($_POST['description']) : null
        );

        $trRepo->upsert($newTranslation);

        EntityAuditLogger::log(
            'magic_energy_translation',
            $energy->id,
            $beforeTranslation ? 'UPDATE' : 'CREATE',
            $beforeTranslation,
            [
                'language'    => $newTranslation->languageCode,
                'name'        => $newTranslation->name,
                'description' => $newTranslation->description
            ]
        );

        /* =========================
           Log de ação
        ========================= */
        ActionLogger::log(
            'magic_energy_updated',
            ActionLogger::INFO,
            [
                'id'       => $energy->id,
                'code'     => $energy->code,
                'language' => $newTranslation->languageCode
            ]
        );

        header('Location: list.php');
        exit;

    } catch (Throwable $e) {
        ActionLogger::log(
            'magic_energy_update_failed',
            ActionLogger::ERROR,
            [
                'id'      => $energy->id,
                'message' => $e->getMessage()
            ]
        );
        throw $e;
    }
}

$title = I18n::t('edit_magic_energy');

$content = function () use ($energy, $translation, $languages, $currentLang) {
    require __DIR__ . '/../../src/View/partials/language_selector.php';
?>
<h1><?= I18n::t('edit_magic_energy') ?></h1>

<form method="post">

    <h2><?= I18n::t('technical_data') ?></h2>

    <?= I18n::t('code') ?>:<br>
    <input name="code" value="<?= htmlspecialchars($energy->code) ?>" required>
    <br><br>

    <h2><?= I18n::t('translation') ?></h2>

    <input type="hidden" name="language" value="<?= htmlspecialchars($currentLang) ?>">

    <?= I18n::t('name') ?>:<br>
    <input name="name" value="<?= htmlspecialchars($translation->name ?? '') ?>" required>
    <br><br>

    <?= I18n::t('description') ?>:<br>
    <textarea name="description"><?= htmlspecialchars($translation->description ?? '') ?></textarea>
    <br><br>

    <button><?= I18n::t('save') ?></button>

    |
    <a href="/MotorMundo/public/logs.php?entity=magic_energy&id=<?= (int)$energy->id ?>">
        <?= I18n::t('history') ?>
    </a>

</form>
<?php
};

require __DIR__ . '/../../src/View/layout.php';

