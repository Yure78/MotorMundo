<?php
declare(strict_types=1);

/**
 * AbstractRepository
 * ------------------
 * Base canônica para todos os repositories do MotorMundo.
 *
 * Responsabilidades:
 * - fornecer conexão com o banco
 * - padronizar execução de statements
 * - centralizar validações críticas
 * - impedir falhas silenciosas
 *
 * NÃO conhece entidades específicas.
 * NÃO contém SQL de domínio.
 */
abstract class AbstractRepository implements RepositoryInterface
{
    protected mysqli $db;

    public function __construct()
    {
        $this->db = DatabaseConnection::get();
    }

    /**
     * Executa um statement preparado e garante que falhas
     * nunca sejam silenciosas.
     */
    protected function executeOrFail(mysqli_stmt $stmt, string $errorMessage): void
    {
        if (!$stmt->execute()) {
            throw new RuntimeException(
                $errorMessage . ' | DB error: ' . $stmt->error
            );
        }
    }

    /**
     * Executa DELETE/UPDATE e valida se houve impacto real.
     */
    protected function executeAndRequireAffect(
        mysqli_stmt $stmt,
        string $notFoundMessage
    ): void {
        if (!$stmt->execute()) {
            throw new RuntimeException('Database error: ' . $stmt->error);
        }

        if ($stmt->affected_rows === 0) {
            throw new RuntimeException($notFoundMessage);
        }
    }

    /**
     * Normaliza boolean para inteiro (regra obrigatória).
     */
    protected function boolToInt(bool $value): int
    {
        return $value ? 1 : 0;
    }

    /**
     * Valida ID padrão (regra global).
     */
    protected function assertValidId(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('Invalid ID.');
        }
    }

    /**
     * Cada repository DEVE implementar seu mapper.
     */
    abstract protected function mapRowToEntity(array $row): object;
}
