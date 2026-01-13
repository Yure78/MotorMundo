<?php
declare(strict_types=1);

final class DatabaseConnection
{
    private static ?mysqli $connection = null;

    public static function get(): mysqli
    {
        if (self::$connection === null) {
            $config = $GLOBALS['app_config']['db'];

            self::$connection = new mysqli(
                $config['host'],
                $config['user'],
                $config['pass'],
                $config['name']
            );

            if (self::$connection->connect_errno) {
                throw new RuntimeException(
                    'Erro de conexão: ' . self::$connection->connect_error
                );
            }

            self::$connection->set_charset($config['charset']);
        }

        return self::$connection;
    }

    private function __construct() {}
}
