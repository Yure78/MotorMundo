<?php
final class I18nKeyRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::get();
    }

    public function findAll(): array
    {
        return $this->db
            ->query("SELECT * FROM i18n_keys ORDER BY code")
            ->fetch_all(MYSQLI_ASSOC);
    }

    public function create(string $code): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO i18n_keys (code) VALUES (?)"
        );
        $stmt->bind_param('s', $code);
        $stmt->execute();
        return $stmt->insert_id;
    }
}
