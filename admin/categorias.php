<?php
$page_title = 'Categorías admin';
$active_page = 'admin';
$extra_css = ['assets/css/admin.css'];
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

datauno_require_admin();
$pdo = datauno_pdo();
$message = '';
$error = '';

if (!$pdo) {
    $error = 'No hay conexión a la base de datos. Revisa el archivo privado config.php.';
}

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $action = $_POST['action'] ?? 'create';
        if ($action === 'create') {
            $nombre = trim($_POST['nombre'] ?? '');
            $orden = (int) ($_POST['orden'] ?? 0);
            if (!$nombre) {
                throw new RuntimeException('El nombre de la categoría es obligatorio.');
            }
            $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $nombre) ?: $nombre), '-'));
            $stmt = $pdo->prepare('INSERT INTO categorias (nombre, slug, orden, activo) VALUES (?, ?, ?, 1)');
            $stmt->execute([$nombre, $slug, $orden]);
            $message = 'Categoría creada.';
        }
        if ($action === 'toggle') {
            $id = (int) ($_POST['id'] ?? 0);
            $stmt = $pdo->prepare('UPDATE categorias SET activo = IF(activo = 1, 0, 1) WHERE id = ?');
            $stmt->execute([$id]);
            $message = 'Estado actualizado.';
        }
    } catch (Throwable $exception) {
        $error = $exception->getMessage();
    }
}

$categorias = [];
if ($pdo) {
    try {
        $categorias = $pdo->query('SELECT * FROM categorias ORDER BY activo DESC, orden ASC, nombre ASC')->fetchAll();
    } catch (Throwable $exception) {
        $error = 'No se pudieron cargar categorías. ¿Importaste el schema.sql?';
    }
}
?>

<section class="admin-section admin-crud-section">
    <div class="container">
        <div class="admin-crud-head reveal">
            <div>
                <span class="eyebrow">Categorías</span>
                <h1>Orden del catálogo.</h1>
                <p>Organiza productos por almacenamiento, RAM, accesorios, repuestos, periféricos y combos.</p>
            </div>
            <div class="admin-actions">
                <a class="btn btn-ghost" href="index.php">Panel</a>
                <a class="btn btn-primary" href="productos.php">Productos</a>
            </div>
        </div>

        <?php if ($message): ?><div class="admin-alert success"><?= htmlspecialchars($message); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="admin-alert error"><?= htmlspecialchars($error); ?></div><?php endif; ?>

        <div class="admin-form-grid reveal">
            <form class="admin-form-card" method="POST">
                <input type="hidden" name="action" value="create">
                <h2>Nueva categoría</h2>
                <label>Nombre
                    <input type="text" name="nombre" placeholder="Ej: Repuestos" required>
                </label>
                <label>Orden
                    <input type="number" name="orden" value="0">
                </label>
                <button class="btn btn-primary" type="submit">Crear categoría</button>
            </form>

            <div class="admin-form-card">
                <h2>Categorías actuales</h2>
                <div class="admin-category-list">
                    <?php foreach ($categorias as $categoria): ?>
                        <article>
                            <div>
                                <strong><?= htmlspecialchars($categoria['nombre']); ?></strong>
                                <small><?= htmlspecialchars($categoria['slug']); ?> · orden <?= (int) $categoria['orden']; ?></small>
                            </div>
                            <form method="POST">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="id" value="<?= (int) $categoria['id']; ?>">
                                <button type="submit" class="status-pill <?= $categoria['activo'] ? 'on' : 'off'; ?>"><?= $categoria['activo'] ? 'Activa' : 'Oculta'; ?></button>
                            </form>
                        </article>
                    <?php endforeach; ?>
                    <?php if (!$categorias): ?><p>No hay categorías cargadas.</p><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
