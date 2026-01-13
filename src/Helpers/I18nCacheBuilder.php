<?php
// src/Helpers/I18nCacheBuilder.php

final class I18nCacheBuilder
{
    public static function build(string $lang): void
    {
        $db = DatabaseConnection::get();

        $stmt = $db->prepare("
          SELECT k.code, t.value
          FROM i18n_keys k
          JOIN i18n_translations t
            ON t.i18n_key_id = k.id
          WHERE t.language_code = ?
        ");
        $stmt->bind_param('s', $lang);
        $stmt->execute();

        $data = [];
        foreach ($stmt->get_result() as $row) {
            $data[$row['code']] = $row['value'];
        }

        file_put_contents(
            __DIR__ . "/../I18n/$lang.php",
            "<?php\nreturn " . var_export($data, true) . ";"
        );
    }
}
