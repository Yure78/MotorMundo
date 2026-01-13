<?php
declare(strict_types=1);

final class MagicEnergyTranslationRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::get();
    }

    public function findOne(int $energyId, string $language): ?MagicEnergyTranslation
    {
        $stmt = $this->db->prepare("
            SELECT magic_energy_id, language_code, name, description
            FROM magic_energy_translations
            WHERE magic_energy_id = ? AND language_code = ?
        ");
        $stmt->bind_param('is', $energyId, $language);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        if (!$row) {
            return null;
        }

        return new MagicEnergyTranslation(
            (int)$row['magic_energy_id'],
            $row['language_code'],
            $row['name'],
            $row['description']
        );
    }

    public function upsert(MagicEnergyTranslation $t): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO magic_energy_translations
              (magic_energy_id, language_code, name, description)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
              name = VALUES(name),
              description = VALUES(description)
        ");
        $stmt->bind_param(
            'isss',
            $t->magicEnergyId,
            $t->languageCode,
            $t->name,
            $t->description
        );
        $stmt->execute();
    }
}

