<?php
declare(strict_types=1);

session_start();


error_reporting(E_ALL);
ini_set('display_errors', '1');

define('BASE_PATH', dirname(__DIR__));

$config = require BASE_PATH . '/config/config.php';

$GLOBALS['app_config'] = $config;


/*
|--------------------------------------------------------------------------
| Autoload simples (PSR-4-like)
|--------------------------------------------------------------------------
*/
spl_autoload_register(function (string $class) {
    $baseDir = __DIR__ . '/';

    $paths = [
        'DTO/',
        'Repository/',
        'Database/',
        'Helpers/',
    ];

    foreach ($paths as $path) {
        $file = $baseDir . $path . $class . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

$lang = $_GET['lang']
     ?? $_SESSION['lang']
     ?? 'pt';

$_SESSION['lang'] = $lang;

I18n::load($lang);

