<?php 
interface RepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?object;
    public function create(object $entity): int;
    public function update(object $entity): void;
    public function delete(int $id): void;
}

