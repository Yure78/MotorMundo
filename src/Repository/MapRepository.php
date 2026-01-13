<?php
declare(strict_types=1);

final class MapRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::get();
    }

    public function create(Map $map): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO maps (name, seed, description)
             VALUES (?, ?, ?)'
        );

        $stmt->bind_param(
            'sss',
            $map->name,
            $map->seed,
            $map->description
        );

        $stmt->execute();

        if ($stmt->errno) {
            throw new RuntimeException($stmt->error);
        }

        return $stmt->insert_id;
    }

    public function findById(int $id): ?Map
    {
        $stmt = $this->db->prepare(
            'SELECT id, name, seed, description, created_at
             FROM maps WHERE id = ?'
        );

        $stmt->bind_param('i', $id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return null;
        }

        return new Map(
            (int)$row['id'],
            $row['name'],
            $row['seed'],
            $row['description'],
            $row['created_at']
        );
    }

    public function findAll(): array
    {
        $result = $this->db->query(
            'SELECT id, name, seed, description, created_at
             FROM maps ORDER BY created_at DESC'
        );

        $maps = [];

        while ($row = $result->fetch_assoc()) {
            $maps[] = new Map(
                (int)$row['id'],
                $row['name'],
                $row['seed'],
                $row['description'],
                $row['created_at']
            );
        }

        return $maps;
    }

    public function update(Map $map): void
    {
        if ($map->id === null) {
            throw new InvalidArgumentException('Map ID obrigatório');
        }

        $stmt = $this->db->prepare(
            'UPDATE maps SET name = ?, seed = ?, description = ? WHERE id = ?'
        );

        $stmt->bind_param(
            'sssi',
            $map->name,
            $map->seed,
            $map->description,
            $map->id
        );

        $stmt->execute();

        if ($stmt->errno) {
            throw new RuntimeException($stmt->error);
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare(
            'DELETE FROM maps WHERE id = ?'
        );

        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->errno) {
            throw new RuntimeException($stmt->error);
        }
    }
}
