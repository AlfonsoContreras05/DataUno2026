<?php
/**
 * Conexión privada DataUno.
 *
 * En producción este archivo lee:
 * /home/datauno1/datauno_private/config.php
 *
 * Ese config.php NO debe subirse a GitHub.
 */

function datauno_config_paths(): array
{
    return [
        getenv('DATAUNO_CONFIG') ?: '',
        dirname(__DIR__, 2) . '/datauno_private/config.php',
        __DIR__ . '/config.local.php',
    ];
}

function datauno_load_config(): ?array
{
    foreach (datauno_config_paths() as $path) {
        if ($path && is_file($path)) {
            $config = require $path;
            return is_array($config) ? $config : null;
        }
    }

    return null;
}

function datauno_pdo(): ?PDO
{
    static $pdo = null;
    static $attempted = false;

    if ($attempted) {
        return $pdo;
    }

    $attempted = true;
    $config = datauno_load_config();

    if (!$config) {
        return null;
    }

    $host = $config['db_host'] ?? 'localhost';
    $name = $config['db_name'] ?? '';
    $user = $config['db_user'] ?? '';
    $pass = $config['db_pass'] ?? '';

    if (!$name || !$user) {
        return null;
    }

    try {
        $pdo = new PDO(
            "mysql:host={$host};dbname={$name};charset=utf8mb4",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    } catch (Throwable $exception) {
        $pdo = null;
    }

    return $pdo;
}

function datauno_db_ready(): bool
{
    $pdo = datauno_pdo();
    if (!$pdo) {
        return false;
    }

    try {
        $pdo->query('SELECT 1 FROM productos LIMIT 1');
        return true;
    } catch (Throwable $exception) {
        return false;
    }
}
