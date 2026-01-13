<?php
declare(strict_types=1);

/**
 * ActionLogger
 *
 * Responsável por registrar ações do usuário
 * (navegação, segurança, eventos semânticos).
 *
 * NÃO substitui auditoria de entidade.
 * NÃO deve quebrar a aplicação em hipótese alguma.
 */
final class ActionLogger
{
    public const INFO     = 'INFO';
    public const SECURITY = 'SECURITY';
    public const ERROR    = 'ERROR';

    /**
     * Registra uma ação do usuário
     *
     * @param string      $action  Código semântico da ação (ex: login_success)
     * @param string      $level   INFO | SECURITY | ERROR
     * @param array|null  $context Dados adicionais (opcional)
     */
    public static function log(
        string $action,
        string $level = self::INFO,
        ?array $context = null
    ): void {
        try {
            $db = DatabaseConnection::get();

            $userId = $_SESSION['user_id'] ?? null;

            $route  = $_SERVER['REQUEST_URI']     ?? null;
            $method = $_SERVER['REQUEST_METHOD']  ?? null;
            $ip     = $_SERVER['REMOTE_ADDR']     ?? null;
            $ua     = $_SERVER['HTTP_USER_AGENT'] ?? null;

            $contextJson = $context
                ? json_encode($context, JSON_UNESCAPED_UNICODE)
                : null;

            $stmt = $db->prepare("
                INSERT INTO user_action_logs
                (
                    user_id,
                    action,
                    level,
                    route,
                    method,
                    context,
                    ip_address,
                    user_agent
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            if (!$stmt) {
                return; // falha silenciosa
            }

            $stmt->bind_param(
                'isssssss',
                $userId,
                $action,
                $level,
                $route,
                $method,
                $contextJson,
                $ip,
                $ua
            );

            $stmt->execute();

        } catch (Throwable $e) {
            // 🔕 REGRA DE OURO:
            // Logging nunca pode quebrar o sistema
        }
    }

    /* =========================
       Métodos auxiliares
       (opcionais, mas úteis)
    ========================= */

    public static function info(string $action, ?array $context = null): void
    {
        self::log($action, self::INFO, $context);
    }

    public static function security(string $action, ?array $context = null): void
    {
        self::log($action, self::SECURITY, $context);
    }

    public static function error(string $action, ?array $context = null): void
    {
        self::log($action, self::ERROR, $context);
    }
}

