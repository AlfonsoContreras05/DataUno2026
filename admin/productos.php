<?php
$page_title = 'Productos admin';
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
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    try {
        if ($action === 'toggle' && $id) {
            $stmt = $pdo->prepare('UPDATE productos SET activo = IF(activo = 1, 0, 1), updated_at = NOW() WHERE id = ?');
            $stmt->execute([$id]);
            $message = 'Estado actualizado.';
        }
        if ($action === 'delete' && $id) {
            $stmt = $pdo->prepare('DELETE FROM productos WHERE id = ?');
            $stmt->execute([$id]);
            $message = 'Producto eliminado.';
        }
    } catch (Throwable $exception) {
        $error = 'No se pudo completar la acción: ' . $exception->getMessage();
    }
}

$productos = [];
if ($pdo) {
    try {
        $productos = $pdo->query('\n            SELECT p.*, c.nombre AS categoria_nombre\n            FROM productos p\n            INNER JOIN categorias c ON c.id = p.categoria_id\n            ORDER BY p.activo DESC, p.orden ASC, p.nombre ASC\n        ')->fetchAll();
    } catch (Throwable $exception) {
        $error = 'No se pudieron cargar productos. ¿Importaste el schema.sql?';
    }
}
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
                                <form method="POST" onsubmit="return confirm('¿Cambiar estado del producto?')">
                                    <input type="hidden" name="id" value="<?= (int) $producto['id']; ?>">
                                    <input type="hidden" name="action" value="toggle">
                                    <button type="submit"><?= $producto['activo'] ? 'Ocultar' : 'Activar'; ?></button>
                                </form>
                                <form method="POST" onsubmit="return confirm('¿Eliminar definitivamente este producto?')">
                                    <input type="hidden" name="id" value="<?= (int) $producto['id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button class="danger" type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$productos): ?>
                        <tr><td colspan="6">Aún no hay productos cargados.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
