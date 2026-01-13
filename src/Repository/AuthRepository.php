<?php
declare(strict_types=1);

final class AuthRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::get();
    }

    public function authenticate(string $username, string $password): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE username = ? AND is_active = 1"
        );
        $stmt->bind_param('s', $username);
        $stmt->execute();

        $user = $stmt->get_result()->fetch_assoc();

        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }
}
