<?php
declare(strict_types=1);

final class BiologicalSexRepository extends AbstractRepository
{
    /**
     * @return BiologicalSex[]
     */
    public function findAll(): array
    {
        $result = $this->db->query(
            'SELECT id, code, can_gestate, can_fertilize
             FROM biological_sexes
             ORDER BY id'
        );

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $this->mapRowToEntity($row);
        }

        return $items;
    }

    public function findById(int $id): ?BiologicalSex
    {
        $this->assertValidId($id);

        $stmt = $this->db->prepare(
            'SELECT id, code, can_gestate, can_fertilize
             FROM biological_sexes
             WHERE id = ?'
        );
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ? $this->mapRowToEntity($row) : null;
    }

    public function create(object $entity): int
    {
        if (!$entity instanceof BiologicalSex) {
            throw new InvalidArgumentException('Expected BiologicalSex entity.');
        }

        $stmt = $this->db->prepare(
            'INSERT INTO biological_sexes (code, can_gestate, can_fertilize)
             VALUES (?, ?, ?)'
        );

        $stmt->bind_param(
            'sii',
            $entity->code,
            $this->boolToInt($entity->canGestate),
            $this->boolToInt($entity->canFertilize)
        );

        $this->executeOrFail(
            $stmt,
            'Failed to create BiologicalSex'
        );

        $id = $this->db->insert_id;
        $stmt->close();

        return $id;
    }

    public function update(object $entity): void
    {
        if (!$entity instanceof BiologicalSex || $entity->id === null) {
            throw new InvalidArgumentException('Invalid BiologicalSex entity.');
        }

        $stmt = $this->db->prepare(
            'UPDATE biological_sexes
             SET code = ?, can_gestate = ?, can_fertilize = ?
             WHERE id = ?'
        );

        $stmt->bind_param(
            'siii',
            $entity->code,
            $this->boolToInt($entity->canGestate),
            $this->boolToInt($entity->canFertilize),
            $entity->id
        );

        $this->executeAndRequireAffect(
            $stmt,
            'BiologicalSex not found or unchanged.'
        );

        $stmt->close();
    }

    public function delete(int $id): void
    {
        $this->assertValidId($id);

        $stmt = $this->db->prepare(
            'DELETE FROM biological_sexes WHERE id = ?'
        );
        $stmt->bind_param('i', $id);

        $this->executeAndRequireAffect(
            $stmt,
            'BiologicalSex not found.'
        );

        $stmt->close();
    }

    protected function mapRowToEntity(array $row): BiologicalSex
    {
        return new BiologicalSex(
            (int)$row['id'],
            $row['code'],
            (bool)$row['can_gestate'],
            (bool)$row['can_fertilize']
        );
    }
}
