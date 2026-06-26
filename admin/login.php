<?php
$page_title = 'Ingreso admin';
$active_page = 'admin';
$extra_css = ['assets/css/admin.css'];

require_once __DIR__ . '/../includes/auth.php';

if (datauno_admin_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (datauno_attempt_login($username, $password)) {
        header('Location: index.php');
        exit;
    }

    $error = 'Usuario o contraseña incorrectos. Verifica usuario, clave y conexión a la base de datos.';
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="admin-hero admin-login-page">
    <div class="container admin-hero-grid">
        <div class="admin-copy reveal">
            <span class="eyebrow">Panel privado</span>
            <h1>Ingreso administrativo DataUno.</h1>
            <p>Desde aquí podrás administrar productos, categorías, destacados, stock e imágenes del catálogo.</p>
        </div>

        <form class="admin-login-card reveal" method="POST" autocomplete="off">
            <div class="admin-card-head">
                <span>🔐</span>
                <div>
                    <strong>Login privado</strong>
                    <small>Acceso protegido por sesión PHP.</small>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="admin-alert error"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <label>Usuario
                <input type="text" name="username" placeholder="admin" required autofocus value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>">
            </label>
            <label>Contraseña
                <input type="password" name="password" placeholder="••••••••" required>
            </label>
            <button class="btn btn-primary" type="submit">Entrar al panel</button>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>