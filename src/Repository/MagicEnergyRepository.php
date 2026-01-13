<?php
declare(strict_types=1);

final class MagicEnergyRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::get();
    }

    public function findAll(): array
    {
        $result = $this->db->query(
            "SELECT id, code FROM magic_energies ORDER BY code"
        );

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = new MagicEnergy(
                (int)$row['id'],
                $row['code']
            );
        }
        return $items;
    }


    public function findById(int $id): ?MagicEnergy
    {
        $stmt = $this->db->prepare(
            "SELECT id, code, description FROM magic_energies WHERE id = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        if (!$row) {
            return null;
        }

        return new MagicEnergy(
            (int)$row['id'],
            $row['code'],
            $row['description']
        );
    }

    public function create(MagicEnergy $energy): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO magic_energies (code) VALUES (?)"
        );
        $stmt->bind_param('s', $energy->code);
        $stmt->execute();

        return $stmt->insert_id;
    }


    public function update(MagicEnergy $energy): void
    {
        $stmt = $this->db->prepare(
            "UPDATE magic_energies SET code = ? WHERE id = ?"
        );
        $stmt->bind_param('si', $energy->code, $energy->id);
        $stmt->execute();
    }


}

