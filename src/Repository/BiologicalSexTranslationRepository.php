<?php
declare(strict_types=1);

final class BiologicalSexTranslationRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::get();
    }

    public function upsert(BiologicalSexTranslation $t): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO biological_sex_translations
             (biological_sex_id, language_code, label, description)
             VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
               label = VALUES(label),
               description = VALUES(description)'
        );

        $stmt->bind_param(
            'isss',
            $t->biologicalSexId,
            $t->languageCode,
            $t->label,
            $t->description
        );

        $stmt->execute();

        if ($stmt->errno) {
            throw new RuntimeException($stmt->error);
        }
    }

    public function find(
        int $sexId,
        string $languageCode
    ): ?BiologicalSexTranslation {
        $stmt = $this->db->prepare(
            'SELECT biological_sex_id, language_code, label, description
             FROM biological_sex_translations
             WHERE biological_sex_id = ? AND language_code = ?'
        );

        $stmt->bind_param('is', $sexId, $languageCode);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return null;
        }

        return new BiologicalSexTranslation(
            (int)$row['biological_sex_id'],
            $row['language_code'],
            $row['label'],
            $row['description']
        );
    }
    public function findOne(int $sexId, string $languageCode): ?BiologicalSexTranslation
    {
        $stmt = $this->db->prepare("
            SELECT
                biological_sex_id,
                language_code,
                label,
                description
            FROM biological_sex_translations
            WHERE biological_sex_id = ?
              AND language_code = ?
            LIMIT 1
        ");

        $stmt->bind_param('is', $sexId, $languageCode);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            return null;
        }

        return new BiologicalSexTranslation(
            (int)$row['biological_sex_id'],
            $row['language_code'],
            $row['label'],
            $row['description']
        );
    }

}
