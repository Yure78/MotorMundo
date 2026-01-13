<?php
declare(strict_types=1);

/**
 * Variáveis esperadas:
 * - $title (string)
 * - $content (callable)
 */
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang'] ?? 'pt') ?>">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'MotorMundo') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        header { margin-bottom: 20px; }
        nav a { margin-right: 10px; }
        footer { margin-top: 40px; font-size: 0.9em; color: #666; }
.main-menu {
    background: #222;
    padding: 10px;
}

.main-menu ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 15px;
}

.main-menu a {
    color: #fff;
    text-decoration: none;
}

.main-menu a:hover {
    text-decoration: underline;
}

.separator {
    flex: 1;
}
.btn-back {
    display: inline-block;
    margin-bottom: 15px;
    text-decoration: none;
    color: #444;
}

.btn-back:hover {
    text-decoration: underline;
}


    </style>
</head>
<body>

<?php require __DIR__ . '/header.php'; ?>

<main>
    <?php $content(); ?>
</main>

<?php require __DIR__ . '/footer.php'; ?>

</body>
</html>
