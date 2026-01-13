<?php
declare(strict_types=1);

final class SpeciesRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::get();
    }

    /* =========================
       CREATE
    ==========================*/
    public function create(Species $species): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO species (name, avg_lifespan, maturity_age, description)
             VALUES (?, ?, ?, ?)'
        );

        $stmt->bind_param(
            'siis',
            $species->name,
            $species->avgLifespan,
            $species->maturityAge,
            $species->description
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
    public function findById(int $id): ?Species
    {
        $stmt = $this->db->prepare(
            'SELECT id, name, avg_lifespan, maturity_age, description
             FROM species WHERE id = ?'
        );

        $stmt->bind_param('i', $id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            return null;
        }

        return new Species(
            (int)$row['id'],
            $row['name'],
            $row['avg_lifespan'] !== null ? (int)$row['avg_lifespan'] : null,
            $row['maturity_age'] !== null ? (int)$row['maturity_age'] : null,
            $row['description']
        );
    }

    /* =========================
       READ (all)
    ==========================*/
    public function findAll(): array
    {
        $result = $this->db->query(
            'SELECT id, name, avg_lifespan, maturity_age, description
             FROM species ORDER BY name'
        );

        $speciesList = [];

        while ($row = $result->fetch_assoc()) {
            $speciesList[] = new Species(
                (int)$row['id'],
                $row['name'],
                $row['avg_lifespan'] !== null ? (int)$row['avg_lifespan'] : null,
                $row['maturity_age'] !== null ? (int)$row['maturity_age'] : null,
                $row['description']
            );
        }

        return $speciesList;
    }

    /* =========================
       UPDATE
    ==========================*/
    public function update(Species $species): void
    {
        if ($species->id === null) {
            throw new InvalidArgumentException('Species ID obrigatório para update');
        }

        $stmt = $this->db->prepare(
            'UPDATE species
             SET name = ?, avg_lifespan = ?, maturity_age = ?, description = ?
             WHERE id = ?'
        );

        $stmt->bind_param(
            'siisi',
            $species->name,
            $species->avgLifespan,
            $species->maturityAge,
            $species->description,
            $species->id
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
            'DELETE FROM species WHERE id = ?'
        );

        $stmt->bind_param('i', $id);
        $stmt->execute();

        if ($stmt->errno) {
            throw new RuntimeException($stmt->error);
        }
    }
}
