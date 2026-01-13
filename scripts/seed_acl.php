<?php
declare(strict_types=1);

require __DIR__ . '/../src/Bootstrap.php';

$db = DatabaseConnection::get();

/* =========================
   ROLES
========================= */
$roles = [
    'admin'      => 'Administrador do sistema',
    'editor'     => 'Editor de conteúdo',
    'translator' => 'Tradutor'
];

foreach ($roles as $code => $desc) {
    $stmt = $db->prepare(
        "INSERT IGNORE INTO roles (code, description) VALUES (?, ?)"
    );
    $stmt->bind_param('ss', $code, $desc);
    $stmt->execute();
}

/* =========================
   PERMISSIONS
========================= */
$permissions = [
    'manage_i18n'              => 'Gerenciar traduções',
    'rebuild_i18n_cache'       => 'Regerar cache de traduções',
    'manage_biological_sexes'  => 'Gerenciar sexos biológicos',
    'manage_magic_energies'    => 'Gerenciar Energias Mágicas',
    'manage_age_groups'        => 'Gerenciar faixas etárias',
    'manage_species'           => 'Gerenciar espécies',
    'manage_users'             => 'Gerenciar usuários',
    'view_audit_logs'          => 'Visualizar Logs'
];

foreach ($permissions as $code => $desc) {
    $stmt = $db->prepare(
        "INSERT IGNORE INTO permissions (code, description) VALUES (?, ?)"
    );
    $stmt->bind_param('ss', $code, $desc);
    $stmt->execute();
}

/* =========================
   MAPA ROLE → PERMISSIONS
========================= */
$map = [
    'admin' => array_keys($permissions),

    'translator' => [
        'manage_i18n'
    ],

    'editor' => [
        'manage_biological_sexes',
        'manage_age_groups',
        'manage_species'
    ]
];

foreach ($map as $roleCode => $perms) {

    $roleId = $db
        ->query("SELECT id FROM roles WHERE code = '$roleCode'")
        ->fetch_assoc()['id'];

    foreach ($perms as $permCode) {

        $permId = $db
            ->query("SELECT id FROM permissions WHERE code = '$permCode'")
            ->fetch_assoc()['id'];

        $stmt = $db->prepare(
            "INSERT IGNORE INTO role_permissions (role_id, permission_id)
             VALUES (?, ?)"
        );
        $stmt->bind_param('ii', $roleId, $permId);
        $stmt->execute();
    }
}

echo "ACL seed concluído com sucesso\n";
