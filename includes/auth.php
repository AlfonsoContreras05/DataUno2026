<?php
require_once __DIR__ . '/db.php';

function datauno_session_start(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_name('datauno_admin');
        session_start();
    }
}

function datauno_admin_user(): ?array
{
    datauno_session_start();
    return $_SESSION['datauno_admin'] ?? null;
}

function datauno_admin_logged_in(): bool
{
    return datauno_admin_user() !== null;
}

function datauno_require_admin(): void
{
    if (!datauno_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function datauno_attempt_login(string $username, string $password): bool
{
    $pdo = datauno_pdo();
    if (!$pdo) {
        return false;
    }

    try {
        $stmt = $pdo->prepare('SELECT id, username, password_hash, nombre, activo FROM admin_users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user || !(int) $user['activo']) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        datauno_session_start();
        $_SESSION['datauno_admin'] = [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'nombre' => $user['nombre'] ?: $user['username'],
        ];
        return true;
    } catch (Throwable $exception) {
        return false;
    }
}

function datauno_logout(): void
{
    datauno_session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}
