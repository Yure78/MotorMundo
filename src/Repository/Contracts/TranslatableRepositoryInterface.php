<?php
declare(strict_types=1);

interface TranslationRepositoryInterface
{
    /**
     * Retorna tradução de uma entidade
     */
    public function findOne(int $entityId, string $language): ?object;

    /**
     * Cria ou atualiza tradução
     */
    public function upsert(object $translation): void;

    /**
     * Retorna todas as traduções de uma entidade
     */
    public function findAllByEntity(int $entityId): array;
}

