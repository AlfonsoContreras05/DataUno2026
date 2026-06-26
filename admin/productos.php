<?php
$page_title = 'Productos admin';
$active_page = 'admin';
$extra_css = ['assets/css/admin.css'];

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
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    try {
        if ($action === 'toggle' && $id) {
            $stmt = $pdo->prepare('UPDATE productos SET activo = IF(activo = 1, 0, 1), updated_at = NOW() WHERE id = ?');
            $stmt->execute([$id]);
            $message = 'Estado actualizado. Si quedó oculto, ya no se verá en el catálogo público.';
        }
        if ($action === 'delete' && $id) {
            $stmt = $pdo->prepare('DELETE FROM productos WHERE id = ?');
            $stmt->execute([$id]);
            $message = 'Producto eliminado definitivamente.';
        }
    } catch (Throwable $exception) {
        $error = 'No se pudo completar la acción: ' . $exception->getMessage();
    }
}

$productos = [];
if ($pdo) {
    try {
        $productos = $pdo->query('SELECT p.*, c.nombre AS categoria_nombre FROM productos p INNER JOIN categorias c ON c.id = p.categoria_id ORDER BY p.activo DESC, p.orden ASC, p.nombre ASC')->fetchAll();
    } catch (Throwable $exception) {
        $error = 'No se pudieron cargar productos. Confirma que importaste database/schema.sql.';
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="admin-section admin-crud-section">
    <div class="container">
        <div class="admin-crud-head reveal">
            <div>
                <span class="eyebrow">Productos</span>
                <h1>Catálogo administrable.</h1>
                <p>Edita productos sin tocar código. Los cambios se reflejan en catálogo y ficha individual.</p>
            </div>
            <div class="admin-actions">
                <a class="btn btn-primary" href="producto-form.php">Nuevo producto</a>
                <a class="btn btn-ghost" href="index.php">Panel</a>
            </div>
        </div>

        <div class="admin-alert success">
            Consejo: usa <strong>Ocultar</strong> cuando no estés seguro. Usa <strong>Eliminar</strong> solo si el producto está duplicado o fue creado por error.
        </div>
        <?php if ($message): ?><div class="admin-alert success"><?= htmlspecialchars($message); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="admin-alert error"><?= htmlspecialchars($error); ?></div><?php endif; ?>

        <div class="admin-table-wrap reveal">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($producto['nombre']); ?></strong>
                                <small><?= htmlspecialchars($producto['slug']); ?></small>
                            </td>
                            <td><?= htmlspecialchars($producto['categoria_nombre']); ?></td>
                            <td><?= htmlspecialchars($producto['precio_tipo'] === 'cotizar' ? 'Cotizar' : '$' . number_format((float) $producto['precio_valor'], 0, ',', '.')); ?></td>
                            <td><?= htmlspecialchars($producto['stock_estado']); ?></td>
                            <td><span class="status-pill <?= $producto['activo'] ? 'on' : 'off'; ?>"><?= $producto['activo'] ? 'Activo' : 'Oculto'; ?></span></td>
                            <td class="table-actions">
                                <a href="producto-form.php?id=<?= (int) $producto['id']; ?>">Editar</a>
                                <a href="../producto.php?id=<?= urlencode($producto['slug']); ?>" target="_blank" rel="noopener">Ver</a>
                                <form method="POST" onsubmit="return confirm('¿Cambiar visibilidad del producto? Si lo ocultas, no aparecerá en el catálogo público.')">
                                    <input type="hidden" name="id" value="<?= (int) $producto['id']; ?>">
                                    <input type="hidden" name="action" value="toggle">
                                    <button type="submit"><?= $producto['activo'] ? 'Ocultar' : 'Activar'; ?></button>
                                </form>
                                <form method="POST" onsubmit="return confirm('¿Eliminar definitivamente este producto? Esta acción no se puede deshacer.')">
                                    <input type="hidden" name="id" value="<?= (int) $producto['id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button class="danger" type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$productos): ?>
                        <tr><td colspan="6">Aún no hay productos cargados. Usa “Nuevo producto” para crear el primero.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>