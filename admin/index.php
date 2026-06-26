<?php
$page_title = 'Panel DataUno';
$active_page = 'admin';
$extra_css = ['assets/css/admin.css'];
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

datauno_require_admin();
$pdo = datauno_pdo();
$stats = [
    'productos' => 0,
    'activos' => 0,
    'categorias' => 0,
    'destacados' => 0,
];

if ($pdo) {
    try {
        $stats['productos'] = (int) $pdo->query('SELECT COUNT(*) FROM productos')->fetchColumn();
        $stats['activos'] = (int) $pdo->query('SELECT COUNT(*) FROM productos WHERE activo = 1')->fetchColumn();
        $stats['categorias'] = (int) $pdo->query('SELECT COUNT(*) FROM categorias')->fetchColumn();
        $stats['destacados'] = (int) $pdo->query('SELECT COUNT(*) FROM productos WHERE destacado = 1')->fetchColumn();
    } catch (Throwable $exception) {
        $stats = array_map(fn() => 0, $stats);
    }
}
?>

<section class="admin-hero admin-dashboard-hero">
    <div class="container admin-dashboard-top reveal">
        <div>
            <span class="eyebrow">Panel DataUno</span>
            <h1>Administración del catálogo.</h1>
            <p>Gestiona productos, categorías, destacados y disponibilidad desde una base MySQL en cPanel.</p>
        </div>
        <div class="admin-user-box">
            <strong><?= htmlspecialchars(datauno_admin_user()['nombre']); ?></strong>
            <span>Sesión activa</span>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
</section>

<section class="admin-section">
    <div class="container">
        <div class="admin-stat-grid reveal">
            <article><strong><?= $stats['productos']; ?></strong><span>Productos</span></article>
            <article><strong><?= $stats['activos']; ?></strong><span>Activos</span></article>
            <article><strong><?= $stats['categorias']; ?></strong><span>Categorías</span></article>
            <article><strong><?= $stats['destacados']; ?></strong><span>Destacados</span></article>
        </div>

        <div class="admin-module-grid admin-module-grid-main reveal">
            <a class="admin-module" href="productos.php">
                <span>🧩</span>
                <h3>Productos</h3>
                <p>Crear, editar, activar/desactivar, ordenar y destacar productos del catálogo.</p>
                <small>Entrar →</small>
            </a>
            <a class="admin-module" href="producto-form.php">
                <span>➕</span>
                <h3>Nuevo producto</h3>
                <p>Agrega un repuesto, accesorio, upgrade o producto TI disponible para cotización.</p>
                <small>Crear →</small>
            </a>
            <a class="admin-module" href="categorias.php">
                <span>🏷️</span>
                <h3>Categorías</h3>
                <p>Administra almacenamiento, RAM, accesorios, repuestos, periféricos y combos.</p>
                <small>Gestionar →</small>
            </a>
            <a class="admin-module" href="../catalogo.php" target="_blank" rel="noopener">
                <span>👁️</span>
                <h3>Ver catálogo</h3>
                <p>Revisa cómo queda publicado el catálogo para clientes.</p>
                <small>Abrir →</small>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
