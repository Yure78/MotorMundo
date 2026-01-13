<?php
declare(strict_types=1);

final class AgeGroupRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::get();
    }

    public function findAll(): array
    {
        $result = $this->db->query(
            "SELECT id, code, min_age, max_age FROM age_groups ORDER BY min_age"
        );

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = new AgeGroup(
                (int)$row['id'],
                $row['code'],
                (int)$row['min_age'],
                (int)$row['max_age']
            );
        }

        return $items;
    }

    public function findById(int $id): ?AgeGroup
    {
        $stmt = $this->db->prepare(
            "SELECT id, code, min_age, max_age FROM age_groups WHERE id = ?"
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        if (!$row) {
            return null;
        }

        return new AgeGroup(
            (int)$row['id'],
            $row['code'],
            (int)$row['min_age'],
            (int)$row['max_age']
        );
    }

    public function create(AgeGroup $group): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO age_groups (code, min_age, max_age) VALUES (?, ?, ?)"
        );
        $stmt->bind_param('sii', $group->code, $group->minAge, $group->maxAge);
        $stmt->execute();

        return $stmt->insert_id;
    }

    public function update(AgeGroup $group): void
    {
        $stmt = $this->db->prepare(
            "UPDATE age_groups SET code = ?, min_age = ?, max_age = ? WHERE id = ?"
        );
        $stmt->bind_param(
            'siii',
            $group->code,
            $group->minAge,
            $group->maxAge,
            $group->id
        );
        $stmt->execute();
    }
}

