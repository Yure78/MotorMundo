<?php
declare(strict_types=1);

interface RepositoryInterface
{
    /**
     * Retorna todos os registros
     */
    public function findAll(): array;

    /**
     * Retorna um registro pelo ID
     */
    public function findById(int $id): ?object;

    /**
     * Persiste um novo registro
     */
    public function create(object $entity): int;

    /**
     * Atualiza um registro existente
     */
    public function update(object $entity): void;

    /**
     * Remove um registro
     */
    public function delete(int $id): void;
}

