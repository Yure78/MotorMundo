<?php 
final class Acl
{
    public static function check(string $permission): void
    {
        if (!isset($_SESSION['user_id'])) {
            self::deny();
        }

        $acl = new AclRepository();

        if (!$acl->userHasPermission($_SESSION['user_id'], $permission)) {
            self::deny();
        }
    }
    public static function checkSilent(string $permission): bool
    {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        $acl = new AclRepository();
        return $acl->userHasPermission(
            $_SESSION['user_id'],
            $permission
        );
    }
    private static function deny(): void
    {
        http_response_code(403);
        exit('Acesso negado');
    }
}
