<?php
declare(strict_types=1);

require __DIR__ . '/../src/Bootstrap.php';

/* =========================
   Segurança
========================= */
Acl::check('view_audit_logs');

/* =========================
   Validação de entrada
========================= */
$entity = $_GET['entity'] ?? null;
$id     = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$entity || !$id) {
    http_response_code(400);
    echo 'Invalid parameters.';
    exit;
}

/* =========================
   Consulta
========================= */
$db = DatabaseConnection::get();

$stmt = $db->prepare("
    SELECT
        l.action,
        l.changes,
        l.created_at,
        u.username
    FROM entity_audit_logs l
    LEFT JOIN users u ON u.id = l.user_id
    WHERE (l.entity_type LIKE ? OR l.entity_type LIKE CONCAT(?,'_translation'))
      AND l.entity_id = ?
    ORDER BY l.created_at ASC;
");

$stmt->bind_param('ssi', $entity, $entity, $id);
$stmt->execute();

$result = $stmt->get_result();
$logs   = $result->fetch_all(MYSQLI_ASSOC);

/* =========================
   View
========================= */
$title = I18n::t('history');

$content = function () use ($entity, $id, $logs) {
    ?>
    <h1><?= I18n::t('history') ?></h1>

    <p>
        <strong><?= I18n::t('entity') ?>:</strong>
        <?= htmlspecialchars($entity) ?>
        |
        <strong>ID:</strong>
        <?= $id ?>
    </p>

    <p>
        <a href="javascript:history.back()">
            ← <?= I18n::t('back') ?>
        </a>
    </p>

    <?php if (!$logs): ?>
        <p><?= I18n::t('no_history_found') ?></p>
    <?php else: ?>

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th><?= I18n::t('date') ?></th>
                    <th><?= I18n::t('user') ?></th>
                    <th><?= I18n::t('action') ?></th>
                    <th><?= I18n::t('details') ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= htmlspecialchars($log['created_at']) ?></td>
                    <td><?= htmlspecialchars($log['username'] ?? I18n::t('system')) ?></td>
                    <td><?= htmlspecialchars($log['action']) ?></td>
                    <td>
                        <?php if ($log['changes']): ?>
                            <?php
                            $changes = json_decode($log['changes'], true);
                            ?>

                            <?php if (isset($changes['before']) || isset($changes['after'])): ?>

                                <?php if (!empty($changes['before'])): ?>
                                    <strong><?= I18n::t('before') ?>:</strong>
                                    <pre><?= htmlspecialchars(
                                        json_encode($changes['before'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                                    ) ?></pre>
                                <?php endif; ?>

                                <?php if (!empty($changes['after'])): ?>
                                    <strong><?= I18n::t('after') ?>:</strong>
                                    <pre><?= htmlspecialchars(
                                        json_encode($changes['after'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                                    ) ?></pre>
                                <?php endif; ?>

                            <?php else: ?>
                                <pre><?= htmlspecialchars(
                                    json_encode($changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                                ) ?></pre>
                            <?php endif; ?>

                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>
    <?php
};

require __DIR__ . '/../src/View/layout.php';

