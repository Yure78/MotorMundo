<?php 
final class AclRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::get();
    }

    public function userHasPermission(int $userId, string $permission): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM permissions p
            JOIN role_permissions rp ON rp.permission_id = p.id
            JOIN user_roles ur ON ur.role_id = rp.role_id
            WHERE ur.user_id = ?
              AND p.code = ?
            LIMIT 1
        ");
        $stmt->bind_param('is', $userId, $permission);
        $stmt->execute();
        return (bool) $stmt->get_result()->num_rows;
    }
}

