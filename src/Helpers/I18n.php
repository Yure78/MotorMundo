<?php
declare(strict_types=1);

final class I18n
{
    private static array $messages = [];

    public static function load(string $lang): void
    {
        $basePath = __DIR__ . '/../I18n/';
        $file = $basePath . $lang . '.php';

        if (!file_exists($file)) {
            $file = $basePath . 'en.php';
        }

        $data = require $file;

        // 🔒 Blindagem obrigatória
        if (!is_array($data)) {
            $data = [];
        }

        self::$messages = $data;
    }

    public static function t(string $key): string
    {
        return self::$messages[$key] ?? $key;
    }
}
