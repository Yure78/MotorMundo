<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require __DIR__ . '/../src/Bootstrap.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $auth = new AuthRepository();

    $user = $auth->authenticate(
        trim($_POST['username']),
        $_POST['password']
    );

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header('Location: index.php');
        exit;
    }

    $error = I18n::t('invalid_login');
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($_SESSION['lang'] ?? 'pt') ?>">
<head>
    <meta charset="UTF-8">
    <title><?= I18n::t('login') ?></title>
</head>
<body>

<h1><?= I18n::t('login') ?></h1>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post">

    <label>
        <?= I18n::t('username') ?>:<br>
        <input type="text" name="username" required>
    </label><br><br>

    <label>
        <?= I18n::t('password') ?>:<br>
        <input type="password" name="password" required>
    </label><br><br>

    <button type="submit"><?= I18n::t('login') ?></button>
</form>

</body>
</html>