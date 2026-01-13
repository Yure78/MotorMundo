<?php 
final class I18nTranslationRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::get();
    }

    public function findAllByLanguage(string $lang): array
    {
        $stmt = $this->db->prepare("
            SELECT k.code, t.value
            FROM i18n_keys k
            LEFT JOIN i18n_translations t
              ON t.i18n_key_id = k.id
             AND t.language_code = ?
            ORDER BY k.code
        ");
        $stmt->bind_param('s', $lang);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function upsertByCode(string $code, string $lang, string $value): void
    {
        // 1. Descobre o ID da chave pelo código
        $stmt = $this->db->prepare("
            SELECT id
            FROM i18n_keys
            WHERE code = ?
        ");
        $stmt->bind_param('s', $code);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        if (!$row) {
            throw new RuntimeException("I18n key not found: $code");
        }

        $keyId = (int)$row['id'];

        // 2. Insere ou atualiza a tradução
        $stmt = $this->db->prepare("
            INSERT INTO i18n_translations (i18n_key_id, language_code, value)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE
                value = VALUES(value)
        ");
        $stmt->bind_param('iss', $keyId, $lang, $value);
        $stmt->execute();
    }

    public function upsert(int $keyId, string $lang, string $value): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO i18n_translations (i18n_key_id, language_code, value)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE value = VALUES(value)
        ");
        $stmt->bind_param('iss', $keyId, $lang, $value);
        $stmt->execute();
    }
}
