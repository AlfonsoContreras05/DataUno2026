<?php
// TEMPORAL: diagnóstico de login DataUno. Eliminar cuando se resuelva.
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: text/html; charset=utf-8');

function h($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function ok($value): string {
    return $value ? '✅ OK' : '❌ NO';
}

function mask_value($value): string {
    $value = (string) $value;
    if ($value === '') return '(vacío)';
    if (strlen($value) <= 4) return str_repeat('*', strlen($value));
    return substr($value, 0, 2) . str_repeat('*', max(2, strlen($value) - 4)) . substr($value, -2);
}

$paths = function_exists('datauno_config_paths') ? datauno_config_paths() : [];
$config = function_exists('datauno_load_config') ? datauno_load_config() : null;
$pdo = null;
$pdoError = '';
$user = null;
$userError = '';
$verifyResult = null;
$newHash = '';

if (is_array($config)) {
    try {
        $pdo = new PDO(
            'mysql:host=' . ($config['db_host'] ?? 'localhost') . ';dbname=' . ($config['db_name'] ?? '') . ';charset=utf8mb4',
            $config['db_user'] ?? '',
            $config['db_pass'] ?? '',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    } catch (Throwable $e) {
        $pdoError = $e->getMessage();
    }
}

if ($pdo) {
    try {
        $stmt = $pdo->prepare('SELECT id, username, password_hash, nombre, activo FROM admin_users WHERE username = ? LIMIT 1');
        $stmt->execute(['admin']);
        $user = $stmt->fetch();
    } catch (Throwable $e) {
        $userError = $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $password = $_POST['password'] ?? '';
    $verifyResult = password_verify($password, $user['password_hash'] ?? '');
    $newHash = password_hash($password, PASSWORD_DEFAULT);
}

?><!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Debug login DataUno</title>
<style>
body{font-family:Arial,sans-serif;background:#07101f;color:#eef6ff;margin:0;padding:28px;line-height:1.45} .box{max-width:980px;margin:auto;background:#0d1d33;border:1px solid #123b5b;border-radius:16px;padding:22px} h1,h2{margin-top:0} code,pre{background:#03101d;border:1px solid #123b5b;border-radius:10px;padding:10px;display:block;white-space:pre-wrap;color:#aeeaff} .row{padding:8px 0;border-bottom:1px solid rgba(255,255,255,.08)} .bad{color:#ff9aa8}.ok{color:#74f0a3} input,button{padding:12px;border-radius:10px;border:1px solid #24506d;background:#06111f;color:white} button{background:#00b7ff;color:#00111f;font-weight:700;cursor:pointer}.warn{background:#38210b;border:1px solid #6b4411;border-radius:12px;padding:12px;margin:14px 0}</style>
</head>
<body>
<div class="box">
<h1>Diagnóstico login DataUno</h1>
<div class="warn">Archivo temporal. Cuando terminemos, eliminar <code>admin/debug-login.php</code>.</div>

<h2>1) PHP</h2>
<div class="row">Versión PHP: <strong><?= h(PHP_VERSION); ?></strong></div>
<div class="row">password_verify existe: <strong><?= ok(function_exists('password_verify')); ?></strong></div>

<h2>2) Rutas de config</h2>
<?php foreach ($paths as $path): if (!$path) continue; ?>
<div class="row"><code><?= h($path); ?></code> Existe: <strong><?= ok(is_file($path)); ?></strong> | Legible: <strong><?= ok(is_readable($path)); ?></strong></div>
<?php endforeach; ?>

<h2>3) Config cargado</h2>
<div class="row">Config array: <strong><?= ok(is_array($config)); ?></strong></div>
<?php if (is_array($config)): ?>
<div class="row">DB host: <strong><?= h($config['db_host'] ?? '(sin db_host)'); ?></strong></div>
<div class="row">DB name: <strong><?= h($config['db_name'] ?? '(sin db_name)'); ?></strong></div>
<div class="row">DB user: <strong><?= h($config['db_user'] ?? '(sin db_user)'); ?></strong></div>
<div class="row">DB pass largo: <strong><?= strlen((string)($config['db_pass'] ?? '')); ?></strong> caracteres</div>
<?php endif; ?>

<h2>4) Conexión BD</h2>
<div class="row">PDO conecta: <strong><?= ok((bool)$pdo); ?></strong></div>
<?php if ($pdoError): ?><pre><?= h($pdoError); ?></pre><?php endif; ?>

<h2>5) Usuario admin</h2>
<div class="row">Consulta admin: <strong><?= ok((bool)$user); ?></strong></div>
<?php if ($userError): ?><pre><?= h($userError); ?></pre><?php endif; ?>
<?php if ($user): ?>
<div class="row">ID: <strong><?= h($user['id']); ?></strong></div>
<div class="row">Username: <strong><?= h($user['username']); ?></strong></div>
<div class="row">Activo: <strong><?= h($user['activo']); ?></strong></div>
<div class="row">Hash inicio: <strong><?= h(substr($user['password_hash'], 0, 4)); ?></strong></div>
<div class="row">Hash largo: <strong><?= h(strlen($user['password_hash'])); ?></strong></div>
<div class="row">Hash info: <code><?= h(json_encode(password_get_info($user['password_hash']), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></code></div>
<?php endif; ?>

<h2>6) Probar contraseña</h2>
<form method="post">
    <input type="password" name="password" placeholder="Escribe la clave admin a probar" required>
    <button type="submit">Probar con password_verify</button>
</form>
<?php if ($verifyResult !== null): ?>
<div class="row">Resultado password_verify: <strong class="<?= $verifyResult ? 'ok' : 'bad'; ?>"><?= $verifyResult ? '✅ COINCIDE' : '❌ NO COINCIDE'; ?></strong></div>
<?php if (!$verifyResult && $newHash): ?>
<p>SQL para actualizar el hash con la clave que acabas de probar:</p>
<code>UPDATE admin_users SET password_hash = '<?= h($newHash); ?>', activo = 1 WHERE username = 'admin';</code>
<?php endif; ?>
<?php endif; ?>
</div>
</body>
</html>
