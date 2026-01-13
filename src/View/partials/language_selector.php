<?php
declare(strict_types=1);

/**
 * Partial: Language Selector
 *
 * Variáveis esperadas:
 * - $currentLang (string)
 */

$langRepo = new LanguageRepository();
$languages = $langRepo->findAll();

$currentLang = $currentLang ?? ($_SESSION['lang'] ?? 'pt');
?>

<form method="get" style="margin-bottom:15px;">
    <label>
        <?= I18n::t('language') ?>:
        <select name="lang" onchange="this.form.submit()">
            <?php foreach ($languages as $language): ?>
                <option value="<?= htmlspecialchars($language['code']) ?>"
                    <?= $language['code'] === $currentLang ? 'selected' : '' ?>>
                    <?= htmlspecialchars($language['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <?php
    // 🔁 Preserva outros parâmetros GET (ex: id)
    foreach ($_GET as $key => $value):
        if ($key === 'lang') continue;
    ?>
        <input type="hidden"
               name="<?= htmlspecialchars($key) ?>"
               value="<?= htmlspecialchars((string)$value) ?>">
    <?php endforeach; ?>
</form>
