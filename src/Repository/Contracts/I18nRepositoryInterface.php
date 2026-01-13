<?php
declare(strict_types=1);

interface I18nRepositoryInterface
{
    /**
     * Retorna todas as chaves traduzidas para um idioma
     */
    public function findAllByLanguage(string $language): array;

    /**
     * Atualiza tradução por chave
     */
    public function upsertByCode(string $code, string $language, string $value): void;
}
