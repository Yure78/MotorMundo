<?php
declare(strict_types=1);

/**
 * BiologicalSexRepository
 * ----------------------
 * Repository canônico para entidades simples.
 *
 * Responsabilidades:
 * - Persistir e recuperar dados de biological_sexes
 * - Converter dados relacionais em objetos de domínio
 * - Garantir fronteira explícita entre OO e Banco de Dados
 *
 * Este arquivo é MODELO para todos os outros Repositories do projeto.
 */
final class BiologicalSexRepository implements RepositoryInterface
{
    /**
     * Conexão ativa com o banco de dados.
     * Deve ser obtida exclusivamente via DatabaseConnection.
     */
    private mysqli $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::get();
    }

    /**
     * Retorna todos os registros de biological_sexes.
     *
     * @return BiologicalSex[]
     */
    public function findAll(): array
    {
        $sql = '
            SELECT id, code, can_gestate, can_fertilize
            FROM biological_sexes
            ORDER BY id
        ';

        $result = $this->db->query($sql);

        if ($result === false) {
            throw new RuntimeException(
                'Failed to fetch BiologicalSex list: ' . $this->db->error
            );
        }

        $items = [];

        while ($row = $result->fetch_assoc()) {
            $items[] = $this->mapRowToEntity($row);
        }

        return $items;
    }

    /**
     * Retorna um BiologicalSex pelo ID.
     */
    public function findById(int $id): ?BiologicalSex
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('Invalid BiologicalSex ID.');
        }

        $stmt = $this->db->prepare(
            'SELECT id, code, can_gestate, can_fertilize
             FROM biological_sexes
             WHERE id = ?'
        );
        $stmt->bind_param('i', $id);

        if (!$stmt->execute()) {
            throw new RuntimeException(
                'Failed to fetch BiologicalSex: ' . $stmt->error
            );
        }

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ? $this->mapRowToEntity($row) : null;
    }

    /**
     * Cria um novo BiologicalSex.
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function create(object $entity): int
    {
        if (!$entity instanceof BiologicalSex) {
            throw new InvalidArgumentException('Expected BiologicalSex entity.');
        }

        // Fronteira explícita OO → DB
        $canGestate   = $entity->canGestate ? 1 : 0;
        $canFertilize = $entity->canFertilize ? 1 : 0;

        $stmt = $this->db->prepare(
            'INSERT INTO biological_sexes (code, can_gestate, can_fertilize)
             VALUES (?, ?, ?)'
        );

        $stmt->bind_param(
            'sii',
            $entity->code,
            $canGestate,
            $canFertilize
        );

        if (!$stmt->execute()) {
            throw new RuntimeException(
                'Failed to create BiologicalSex: ' . $stmt->error
            );
        }

        $id = $this->db->insert_id;
        $stmt->close();

        return $id;
    }

    /**
     * Atualiza um BiologicalSex existente.
     */
    public function update(object $entity): void
    {
        if (!$entity instanceof BiologicalSex || $entity->id === null) {
            throw new InvalidArgumentException('Invalid BiologicalSex entity.');
        }

        $canGestate   = $entity->canGestate ? 1 : 0;
        $canFertilize = $entity->canFertilize ? 1 : 0;

        $stmt = $this->db->prepare(
            'UPDATE biological_sexes
             SET code = ?, can_gestate = ?, can_fertilize = ?
             WHERE id = ?'
        );

        $stmt->bind_param(
            'siii',
            $entity->code,
            $canGestate,
            $canFertilize,
            $entity->id
        );

        if (!$stmt->execute()) {
            throw new RuntimeException(
                'Failed to update BiologicalSex: ' . $stmt->error
            );
        }

        $stmt->close();
    }

    /**
     * Remove um BiologicalSex pelo ID.
     */
    public function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('Invalid BiologicalSex ID.');
        }

        $stmt = $this->db->prepare(
            'DELETE FROM biological_sexes WHERE id = ?'
        );
        $stmt->bind_param('i', $id);

        if (!$stmt->execute()) {
            throw new RuntimeException(
                'Failed to delete BiologicalSex: ' . $stmt->error
            );
        }

        if ($stmt->affected_rows === 0) {
            throw new RuntimeException(
                'BiologicalSex not found or already deleted.'
            );
        }

        $stmt->close();
    }

    /**
     * Converte uma linha do banco em entidade de domínio.
     *
     * 🔒 ÚNICO local autorizado a conhecer a estrutura da tabela.
     */
    private function mapRowToEntity(array $row): BiologicalSex
    {
        return new BiologicalSex(
            (int) $row['id'],
            $row['code'],
            (bool) $row['can_gestate'],
            (bool) $row['can_fertilize']
        );
    }
}
