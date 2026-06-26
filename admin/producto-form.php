<?php
$page_title = 'Formulario producto';
$active_page = 'admin';
$extra_css = ['assets/css/admin.css'];
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/product-repository.php';

datauno_require_admin();
$pdo = datauno_pdo();
$error = '';
$message = '';
$id = (int) ($_GET['id'] ?? 0);
$producto = null;
$categorias = [];

if (!$pdo) {
    $error = 'No hay conexión a la base de datos. Revisa el archivo privado config.php.';
} else {
    try {
        $categorias = $pdo->query('SELECT id, nombre FROM categorias WHERE activo = 1 ORDER BY orden ASC, nombre ASC')->fetchAll();
        if ($id) {
            $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = ? LIMIT 1');
            $stmt->execute([$id]);
            $producto = $stmt->fetch();
        }
    } catch (Throwable $exception) {
        $error = 'No se pudo preparar el formulario. ¿Importaste el schema.sql?';
    }
}

function admin_upload_product_image(string $fieldName, ?string $currentPath = null): ?string
{
    if (empty($_FILES[$fieldName]['name']) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return $currentPath;
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('La imagen no se pudo subir correctamente.');
    }

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $mime = mime_content_type($_FILES[$fieldName]['tmp_name']);
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Formato no permitido. Usa JPG, PNG o WEBP.');
    }

    $uploadDir = dirname(__DIR__) . '/uploads/productos';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = 'producto-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
    $target = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $target)) {
        throw new RuntimeException('No se pudo mover la imagen subida.');
    }

    return 'uploads/productos/' . $filename;
}

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombre = trim($_POST['nombre'] ?? '');
        $slug = trim($_POST['slug'] ?? '') ?: datauno_slugify($nombre);
        $categoriaId = (int) ($_POST['categoria_id'] ?? 0);
        $descripcionCorta = trim($_POST['descripcion_corta'] ?? '');
        $descripcionLarga = trim($_POST['descripcion_larga'] ?? '');
        $detalle = trim($_POST['detalle'] ?? '');
        $badge = trim($_POST['badge'] ?? 'Validación DataUno');
        $precioTipo = $_POST['precio_tipo'] ?? 'cotizar';
        $precioValor = $_POST['precio_valor'] !== '' ? (float) $_POST['precio_valor'] : null;
        $stockEstado = trim($_POST['stock_estado'] ?? 'Consultar');
        $orden = (int) ($_POST['orden'] ?? 0);
        $destacado = isset($_POST['destacado']) ? 1 : 0;
        $activo = isset($_POST['activo']) ? 1 : 0;
        $instalacion = isset($_POST['instalacion_disponible']) ? 1 : 0;
        $imagenManual = trim($_POST['imagen_principal'] ?? '');
        $imagenActual = $producto['imagen_principal'] ?? 'assets/img/placa-tech.jpg';
        $imagen = $imagenManual ?: $imagenActual;
        $imagen = admin_upload_product_image('imagen_upload', $imagen);

        if (!$nombre || !$categoriaId || !$descripcionCorta) {
            throw new RuntimeException('Nombre, categoría y descripción corta son obligatorios.');
        }

        if ($id && $producto) {
            $stmt = $pdo->prepare('\n                UPDATE productos SET\n                    categoria_id = ?, slug = ?, nombre = ?, descripcion_corta = ?, descripcion_larga = ?, detalle = ?,\n                    precio_tipo = ?, precio_valor = ?, imagen_principal = ?, badge = ?, stock_estado = ?,\n                    destacado = ?, activo = ?, instalacion_disponible = ?, orden = ?, updated_at = NOW()\n                WHERE id = ?\n            ');
            $stmt->execute([$categoriaId, $slug, $nombre, $descripcionCorta, $descripcionLarga, $detalle, $precioTipo, $precioValor, $imagen, $badge, $stockEstado, $destacado, $activo, $instalacion, $orden, $id]);
            $message = 'Producto actualizado correctamente.';
        } else {
            $stmt = $pdo->prepare('\n                INSERT INTO productos\n                (categoria_id, slug, nombre, descripcion_corta, descripcion_larga, detalle, precio_tipo, precio_valor, imagen_principal, badge, stock_estado, destacado, activo, instalacion_disponible, orden)\n                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)\n            ');
            $stmt->execute([$categoriaId, $slug, $nombre, $descripcionCorta, $descripcionLarga, $detalle, $precioTipo, $precioValor, $imagen, $badge, $stockEstado, $destacado, $activo, $instalacion, $orden]);
            $id = (int) $pdo->lastInsertId();
            $message = 'Producto creado correctamente.';
        }

        $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $producto = $stmt->fetch();
    } catch (Throwable $exception) {
        $error = $exception->getMessage();
    }
}

$producto = $producto ?: [
    'categoria_id' => $categorias[0]['id'] ?? '',
    'slug' => '',
    'nombre' => '',
    'descripcion_corta' => '',
    'descripcion_larga' => '',
    'detalle' => '',
    'precio_tipo' => 'cotizar',
    'precio_valor' => '',
    'imagen_principal' => 'assets/img/placa-tech.jpg',
    'badge' => 'Validación DataUno',
    'stock_estado' => 'Consultar',
    'destacado' => 0,
    'activo' => 1,
    'instalacion_disponible' => 1,
    'orden' => 0,
];
?>

<section class="admin-section admin-crud-section">
    <div class="container">
        <div class="admin-crud-head reveal">
            <div>
                <span class="eyebrow"><?= $id ? 'Editar producto' : 'Nuevo producto'; ?></span>
                <h1><?= $id ? 'Actualizar producto.' : 'Crear producto.'; ?></h1>
                <p>Completa los datos comerciales y técnicos que verá el cliente en el catálogo.</p>
            </div>
            <div class="admin-actions">
                <a class="btn btn-ghost" href="productos.php">Volver</a>
                <?php if ($id): ?><a class="btn btn-primary" href="../producto.php?id=<?= urlencode($producto['slug']); ?>" target="_blank" rel="noopener">Ver ficha</a><?php endif; ?>
            </div>
        </div>

        <?php if ($message): ?><div class="admin-alert success"><?= htmlspecialchars($message); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="admin-alert error"><?= htmlspecialchars($error); ?></div><?php endif; ?>

        <form class="admin-form-grid reveal" method="POST" enctype="multipart/form-data">
            <div class="admin-form-card">
                <h2>Información principal</h2>
                <label>Nombre
                    <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']); ?>" required>
                </label>
                <label>Slug / URL
                    <input type="text" name="slug" value="<?= htmlspecialchars($producto['slug']); ?>" placeholder="se-crea-automatico-si-lo-dejas-vacio">
                </label>
                <label>Categoría
                    <select name="categoria_id" required>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= (int) $categoria['id']; ?>" <?= (int) $producto['categoria_id'] === (int) $categoria['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($categoria['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Descripción corta
                    <textarea name="descripcion_corta" rows="3" required><?= htmlspecialchars($producto['descripcion_corta']); ?></textarea>
                </label>
                <label>Detalle / validación DataUno
                    <textarea name="detalle" rows="3"><?= htmlspecialchars($producto['detalle']); ?></textarea>
                </label>
                <label>Descripción larga
                    <textarea name="descripcion_larga" rows="5"><?= htmlspecialchars($producto['descripcion_larga']); ?></textarea>
                </label>
            </div>

            <div class="admin-form-card">
                <h2>Venta y presentación</h2>
                <label>Tipo de precio
                    <select name="precio_tipo">
                        <option value="cotizar" <?= $producto['precio_tipo'] === 'cotizar' ? 'selected' : ''; ?>>Cotizar</option>
                        <option value="desde" <?= $producto['precio_tipo'] === 'desde' ? 'selected' : ''; ?>>Desde</option>
                        <option value="fijo" <?= $producto['precio_tipo'] === 'fijo' ? 'selected' : ''; ?>>Precio fijo</option>
                    </select>
                </label>
                <label>Valor precio
                    <input type="number" min="0" step="100" name="precio_valor" value="<?= htmlspecialchars((string) $producto['precio_valor']); ?>">
                </label>
                <label>Badge
                    <input type="text" name="badge" value="<?= htmlspecialchars($producto['badge']); ?>">
                </label>
                <label>Stock / disponibilidad
                    <input type="text" name="stock_estado" value="<?= htmlspecialchars($producto['stock_estado']); ?>">
                </label>
                <label>Imagen actual o ruta manual
                    <input type="text" name="imagen_principal" value="<?= htmlspecialchars($producto['imagen_principal']); ?>">
                </label>
                <label>Subir imagen nueva
                    <input type="file" name="imagen_upload" accept="image/jpeg,image/png,image/webp">
                </label>
                <label>Orden
                    <input type="number" name="orden" value="<?= (int) $producto['orden']; ?>">
                </label>
                <div class="admin-checks">
                    <label><input type="checkbox" name="destacado" <?= $producto['destacado'] ? 'checked' : ''; ?>> Destacado</label>
                    <label><input type="checkbox" name="activo" <?= $producto['activo'] ? 'checked' : ''; ?>> Activo</label>
                    <label><input type="checkbox" name="instalacion_disponible" <?= $producto['instalacion_disponible'] ? 'checked' : ''; ?>> Instalación disponible</label>
                </div>
                <button class="btn btn-primary" type="submit"><?= $id ? 'Guardar cambios' : 'Crear producto'; ?></button>
            </div>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
