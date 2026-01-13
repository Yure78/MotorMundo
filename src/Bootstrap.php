<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

/**
 * MotorMundo Bootstrap (Passo 0)
 * ------------------------------
 * Responsabilidade única:
 * Preparar o ambiente de execução da aplicação
 * usando apenas estruturas já existentes no projeto.
 */

// 1️⃣ Configuração existente (mantida)
$config = require BASE_PATH . '/config/config.php';

// 2️⃣ Autoload manual existente
spl_autoload_register(function (string $class): void {
    $baseDir = __DIR__ . '/';

    $paths = [
        'DTO',
        'Repository',
        'Database',
        'Helpers',
        'Security',
        'Services',
    ];

    foreach ($paths as $path) {
        $file = $baseDir . $path . '/' . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});


// 3️⃣ Timezone (se existir no config)
if (isset($config['timezone'])) {
    date_default_timezone_set($config['timezone']);
}

// 4️⃣ Sessão (uma única vez)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 5️⃣ Banco de dados (inicialização previsível)
DatabaseConnection::init($config['db'] ?? []);

// 6️⃣ Idioma (decisão mínima, sem lógica de negócio)
$lang = $_GET['lang']
    ?? $_SESSION['lang']
    ?? ($config['default_lang'] ?? 'pt');

$_SESSION['lang'] = $lang;

// 7️⃣ I18n (somente carregar)
I18n::load($lang);

// 8️⃣ ACL (somente inicializar contexto)
Acl::init();
