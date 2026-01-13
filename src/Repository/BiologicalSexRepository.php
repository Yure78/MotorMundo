<?php
declare(strict_types=1);

final class BiologicalSexRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::get();
    }

    /* =========================
       CREATE
    ==========================*/
    public function create(BiologicalSex $sex): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO biological_sexes (code, can_gestate, can_fertilize)
             VALUES (?, ?, ?)'
        );

        $canGestate   = $sex->canGestate ? 1 : 0;
        $canFertilize = $sex->canFertilize ? 1 : 0;

        $stmt->bind_param(
            'sii',
            $sex->code,
            $canGestate,
            $canFertilize
        );

        $stmt->execute();

        if ($stmt->errno) {
            throw new RuntimeException($stmt->error);
        }

        return $stmt->insert_id;
    }

    /* =========================
       READ (by id)
    ==========================*/
    public function findById(int $id): ?BiologicalSex
    {
        $stmt = $this->db->prepare(
            'SELECT id, code, can_gestate, can_fertilize
             FROM biological_sexes
             WHERE id = ?'
        );

        $stmt->bind_param('i', $id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return null;
        }

        return new BiologicalSex(
            (int)$row['id'],
            $row['code'],
            (bool)$row['can_gestate'],
            (bool)$row['can_fertilize']
        );
    }

    /* =========================
       READ (by code)
    ==========================*/
    public function findByCode(string $code): ?BiologicalSex
    {
        $stmt = $this->db->prepare(
            'SELECT id, code, can_gestate, can_fertilize
             FROM biological_sexes
             WHERE code = ?'
        );

        $stmt->bind_param('s', $code);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return null;
        }

        return new BiologicalSex(
            (int)$row['id'],
            $row['code'],
            (bool)$row['can_gestate'],
            (bool)$row['can_fertilize']
        );
    }

    /* =========================
       READ (all)
    ==========================*/
    public function findAll(): array
    {
        $result = $this->db->query(
            'SELECT id, code, can_gestate, can_fertilize
             FROM biological_sexes
             ORDER BY code'
        );

        $list = [];

        while ($row = $result->fetch_assoc()) {
            $list[] = new BiologicalSex(
                (int)$row['id'],
                $row['code'],
                (bool)$row['can_gestate'],
                (bool)$row['can_fertilize']
            );
        }

        return $list;
    }

    /* =========================
       UPDATE
    ==========================*/
    public function update(BiologicalSex $sex): void
    {
        if ($sex->id === null) {
            throw new InvalidArgumentException('BiologicalSex ID obrigatório');
        }

        $canGestate   = $sex->canGestate ? 1 : 0;
        $canFertilize = $sex->canFertilize ? 1 : 0;

        $stmt = $this->db->prepare(
            'UPDATE biological_sexes
             SET code = ?, can_gestate = ?, can_fertilize = ?
             WHERE id = ?'
        );

        $stmt->bind_param(
            'siii',
            $sex->code,
            $canGestate,
            $canFertilize,
            $sex->id
        );

        $stmt->execute();

        if ($stmt->errno) {
            throw new RuntimeException($stmt->error);
        }
    }

    /* =========================
       DELETE
    ==========================*/
    public function delete(int $id): void
    {
        $stmt = $this->db->prepare(
            'DELETE FROM biological_sexes WHERE id = ?'
        );

        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->errno) {
            throw new RuntimeException($stmt->error);
        }
    }
}
