<?php
$page_title = 'Categorías admin';
$active_page = 'admin';
$extra_css = ['assets/css/admin.css'];

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/product-repository.php';

datauno_require_admin();
$pdo = datauno_pdo();
$message = '';
$error = '';
$editId = (int) ($_GET['edit'] ?? 0);
$categoriaEdit = null;

function admin_categoria_slug(string $nombre): string
{
    return datauno_slugify($nombre);
}

function admin_categoria_slug_exists(PDO $pdo, string $slug, int $ignoreId = 0): bool
{
    $stmt = $pdo->prepare('SELECT id FROM categorias WHERE slug = ? AND id <> ? LIMIT 1');
    $stmt->execute([$slug, $ignoreId]);
    return (bool) $stmt->fetch();
}

if (!$pdo) {
    $error = 'No hay conexión a la base de datos. Revisa el archivo privado config.php.';
}

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $action = $_POST['action'] ?? 'create';
        $id = (int) ($_POST['id'] ?? 0);

        if ($action === 'create' || $action === 'update') {
            $nombre = trim($_POST['nombre'] ?? '');
            $orden = (int) ($_POST['orden'] ?? 0);
            $activo = isset($_POST['activo']) ? 1 : 0;

            if (!$nombre) {
                throw new RuntimeException('El nombre de la categoría es obligatorio. Ejemplo: Repuestos, Almacenamiento, Accesorios.');
            }

            $slug = admin_categoria_slug($nombre);
            if (admin_categoria_slug_exists($pdo, $slug, $action === 'update' ? $id : 0)) {
                throw new RuntimeException('Ya existe una categoría con un slug parecido. Usa otro nombre para evitar duplicados.');
            }

            if ($action === 'update') {
                if (!$id) {
                    throw new RuntimeException('No se recibió la categoría a editar.');
                }
                $stmt = $pdo->prepare('UPDATE categorias SET nombre = ?, slug = ?, orden = ?, activo = ?, updated_at = NOW() WHERE id = ?');
                $stmt->execute([$nombre, $slug, $orden, $activo, $id]);
                $message = 'Categoría actualizada correctamente.';
                $editId = 0;
            } else {
                $stmt = $pdo->prepare('INSERT INTO categorias (nombre, slug, orden, activo) VALUES (?, ?, ?, ?)');
                $stmt->execute([$nombre, $slug, $orden, $activo]);
                $message = 'Categoría creada correctamente.';
            }
        }

        if ($action === 'toggle' && $id) {
            $stmt = $pdo->prepare('UPDATE categorias SET activo = IF(activo = 1, 0, 1), updated_at = NOW() WHERE id = ?');
            $stmt->execute([$id]);
            $message = 'Estado actualizado. Si quedó oculta, no aparecerá como filtro público.';
        }

        if ($action === 'delete' && $id) {
            $stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM productos WHERE categoria_id = ?');
            $stmt->execute([$id]);
            $totalProductos = (int) ($stmt->fetch()['total'] ?? 0);

            if ($totalProductos > 0) {
                throw new RuntimeException('No se puede eliminar esta categoría porque tiene ' . $totalProductos . ' producto(s). Primero mueve o elimina esos productos. Puedes ocultarla mientras tanto.');
            }

            $stmt = $pdo->prepare('DELETE FROM categorias WHERE id = ?');
            $stmt->execute([$id]);
            $message = 'Categoría eliminada correctamente.';
        }
    } catch (Throwable $exception) {
        $error = $exception->getMessage();
    }
}

$categorias = [];
if ($pdo) {
    try {
        if ($editId) {
            $stmt = $pdo->prepare('SELECT * FROM categorias WHERE id = ? LIMIT 1');
            $stmt->execute([$editId]);
            $categoriaEdit = $stmt->fetch();
        }

        $categorias = $pdo->query('SELECT c.*, (SELECT COUNT(*) FROM productos p WHERE p.categoria_id = c.id) AS total_productos FROM categorias c ORDER BY c.activo DESC, c.orden ASC, c.nombre ASC')->fetchAll();
    } catch (Throwable $exception) {
        $error = 'No se pudieron cargar categorías. Confirma que importaste database/schema.sql.';
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="admin-section admin-crud-section">
    <div class="container">
        <div class="admin-crud-head reveal">
            <div>
                <span class="eyebrow">Categorías</span>
                <h1>Orden del catálogo.</h1>
                <p>Organiza los productos por familias. Si una categoría tiene productos, es más seguro ocultarla que eliminarla.</p>
            </div>
            <div class="admin-actions">
                <a class="btn btn-ghost" href="index.php">Panel</a>
                <a class="btn btn-primary" href="productos.php">Productos</a>
            </div>
        </div>

        <div class="admin-alert success">
            Guía rápida: <strong>Editar</strong> cambia nombre y orden; <strong>Ocultar</strong> la saca del filtro público; <strong>Eliminar</strong> solo funciona si no tiene productos asociados.
        </div>
        <?php if ($message): ?><div class="admin-alert success"><?= htmlspecialchars($message); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="admin-alert error"><?= htmlspecialchars($error); ?></div><?php endif; ?>

        <div class="admin-form-grid reveal">
            <form class="admin-form-card" method="POST">
                <input type="hidden" name="action" value="<?= $categoriaEdit ? 'update' : 'create'; ?>">
                <?php if ($categoriaEdit): ?><input type="hidden" name="id" value="<?= (int) $categoriaEdit['id']; ?>"><?php endif; ?>
                <h2><?= $categoriaEdit ? 'Editar categoría' : 'Nueva categoría'; ?></h2>
                <p><?= $categoriaEdit ? 'Estás editando una categoría existente. Revisa que el nombre siga siendo claro para el cliente.' : 'Crea familias simples para ordenar el catálogo.'; ?></p>
                <label>Nombre
                    <input type="text" name="nombre" placeholder="Ej: Repuestos" value="<?= htmlspecialchars($categoriaEdit['nombre'] ?? ''); ?>" required>
                    <small>El slug se genera automáticamente desde este nombre.</small>
                </label>
                <label>Orden
                    <input type="number" name="orden" value="<?= (int) ($categoriaEdit['orden'] ?? 0); ?>">
                    <small>Número menor aparece antes en los filtros.</small>
                </label>
                <div class="admin-checks">
                    <label><input type="checkbox" name="activo" <?= ($categoriaEdit['activo'] ?? 1) ? 'checked' : ''; ?>> Activa / visible</label>
                </div>
                <button class="btn btn-primary" type="submit"><?= $categoriaEdit ? 'Guardar categoría' : 'Crear categoría'; ?></button>
                <?php if ($categoriaEdit): ?><a class="btn btn-ghost" href="categorias.php">Cancelar edición</a><?php endif; ?>
            </form>

            <div class="admin-form-card">
                <h2>Categorías actuales</h2>
                <div class="admin-category-list">
                    <?php foreach ($categorias as $categoria): ?>
                        <article>
                            <div>
                                <strong><?= htmlspecialchars($categoria['nombre']); ?></strong>
                                <small><?= htmlspecialchars($categoria['slug']); ?> · orden <?= (int) $categoria['orden']; ?> · <?= (int) $categoria['total_productos']; ?> producto(s)</small>
                            </div>
                            <div class="table-actions">
                                <a href="categorias.php?edit=<?= (int) $categoria['id']; ?>">Editar</a>
                                <form method="POST" onsubmit="return confirm('¿Cambiar visibilidad de esta categoría?')">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="id" value="<?= (int) $categoria['id']; ?>">
                                    <button type="submit" class="status-pill <?= $categoria['activo'] ? 'on' : 'off'; ?>"><?= $categoria['activo'] ? 'Ocultar' : 'Activar'; ?></button>
                                </form>
                                <form method="POST" onsubmit="return confirm('¿Eliminar definitivamente esta categoría? Solo funcionará si no tiene productos asociados.')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) $categoria['id']; ?>">
                                    <button class="danger" type="submit">Eliminar</button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                    <?php if (!$categorias): ?><p>No hay categorías cargadas.</p><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>