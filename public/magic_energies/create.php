<?php
declare(strict_types=1);

require __DIR__ . '/../../src/Bootstrap.php';

Acl::check('manage_magic_energies');

$energyRepo = new MagicEnergyRepository();
$trRepo     = new MagicEnergyTranslationRepository();
$langRepo   = new LanguageRepository();

$languages = $langRepo->findAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        /* =========================
           Criar energia (base)
        ========================= */
        $energy = new MagicEnergy(
            null,
            trim($_POST['code']),
            null // descrição agora vive só na tradução
        );

        $energyId = $energyRepo->create($energy);

        EntityAuditLogger::log(
            'magic_energy',
            $energyId,
            'CREATE',
            null,
            [
                'code' => $energy->code
            ]
        );

        /* =========================
           Criar tradução
        ========================= */
        $translation = new MagicEnergyTranslation(
            $energyId,
            $_POST['language'],
            trim($_POST['name']),
            $_POST['description'] !== '' ? trim($_POST['description']) : null
        );

        $trRepo->upsert($translation);

        EntityAuditLogger::log(
            'magic_energy_translation',
            $energyId,
            'CREATE',
            null,
            [
                'language'    => $translation->languageCode,
                'name'        => $translation->name,
                'description' => $translation->description
            ]
        );

        /* =========================
           Log de ação
        ========================= */
        ActionLogger::log(
            'magic_energy_created',
            ActionLogger::INFO,
            [
                'id'       => $energyId,
                'code'     => $energy->code,
                'language' => $translation->languageCode
            ]
        );

        header('Location: list.php');
        exit;

    } catch (Throwable $e) {
        ActionLogger::log(
            'magic_energy_create_failed',
            ActionLogger::ERROR,
            ['message' => $e->getMessage()]
        );
        throw $e;
    }
}

$title = I18n::t('create_magic_energy');

$content = function () use ($languages) {
    require __DIR__ . '/../../src/View/partials/language_selector.php';
?>
<h1><?= I18n::t('create_magic_energy') ?></h1>

<form method="post">

    <h2><?= I18n::t('technical_data') ?></h2>

    <?= I18n::t('code') ?>:<br>
    <input name="code" required><br><br>

    <h2><?= I18n::t('translation') ?></h2>

    <?= I18n::t('language') ?>:<br>
    <select name="language" required>
        <?php foreach ($languages as $lang): ?>
            <option value="<?= htmlspecialchars($lang['code']) ?>"
                <?= $lang['is_default'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($lang['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <?= I18n::t('name') ?>:<br>
    <input name="name" required><br><br>

    <?= I18n::t('description') ?>:<br>
    <textarea name="description"></textarea><br><br>

    <button><?= I18n::t('save') ?></button>

</form>
<?php
};

require __DIR__ . '/../../src/View/layout.php';

