<?php
declare(strict_types=1);

final class LanguageRepository
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::get();
    }

    /**
     * Retorna todos os idiomas, com o padrão em primeiro lugar
     */
    public function findAll(): array
    {
        $result = $this->db->query(
            'SELECT code, name, is_default
             FROM languages
             ORDER BY is_default DESC, name'
        );

        $languages = [];

        while ($row = $result->fetch_assoc()) {
            $languages[] = $row;
        }

        return $languages;
    }

    /**
     * Retorna o idioma padrão do sistema
     */
    public function getDefault(): string
    {
        $result = $this->db->query(
            'SELECT code FROM languages WHERE is_default = 1 LIMIT 1'
        );

        $row = $result->fetch_assoc();

        return $row ? $row['code'] : 'en';
    }
}
