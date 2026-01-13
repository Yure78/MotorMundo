<?php
declare(strict_types=1);

final class EntityAuditLogger
{
    public static function log(
        string $entityType,
        int $entityId,
        string $action,
        ?array $before = null,
        ?array $after = null
    ): void {
        try {
            $db = DatabaseConnection::get();

            $userId = $_SESSION['user_id'] ?? null;

            $changes = null;

            if ($before || $after) {
                $changes = json_encode([
                    'before' => $before,
                    'after'  => $after
                ], JSON_UNESCAPED_UNICODE);
            }

            $stmt = $db->prepare("
                INSERT INTO entity_audit_logs
                (entity_type, entity_id, action, user_id, changes, route, method, ip_address, user_agent)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $route  = $_SERVER['REQUEST_URI'] ?? null;
            $method = $_SERVER['REQUEST_METHOD'] ?? null;
            $ip     = $_SERVER['REMOTE_ADDR'] ?? null;
            $ua     = $_SERVER['HTTP_USER_AGENT'] ?? null;

            $stmt->bind_param(
                'sisisssss',
                $entityType,
                $entityId,
                $action,
                $userId,
                $changes,
                $route,
                $method,
                $ip,
                $ua
            );

            $stmt->execute();

        } catch (Throwable $e) {
            // Auditoria nunca deve quebrar o sistema
        }
    }
}
