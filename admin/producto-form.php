<?php
$page_title = 'Formulario producto';
$active_page = 'admin';
$extra_css = ['assets/css/admin.css'];

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

function admin_upload_product_image(string $fieldName, ?string $currentPath = null): ?string
{
    if (empty($_FILES[$fieldName]['name']) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return $currentPath;
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('La imagen no se pudo subir correctamente. Intenta con una imagen JPG, PNG o WEBP más liviana.');
    }

    if ((int) $_FILES[$fieldName]['size'] > 8 * 1024 * 1024) {
        throw new RuntimeException('La imagen supera 8 MB. Para el catálogo conviene usar imágenes livianas.');
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    $extension = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions, true)) {
        throw new RuntimeException('Formato no permitido. Usa JPG, PNG o WEBP.');
    }

    $extension = $extension === 'jpeg' ? 'jpg' : $extension;
    $uploadDir = dirname(__DIR__) . '/uploads/productos';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        throw new RuntimeException('No se pudo crear la carpeta uploads/productos. Revisa permisos del hosting.');
    }

    $filename = 'producto-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
    $target = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $target)) {
        throw new RuntimeException('No se pudo mover la imagen subida. Revisa permisos de uploads/productos.');
    }

    return 'uploads/productos/' . $filename;
}

function admin_product_slug_exists(PDO $pdo, string $slug, int $ignoreId = 0): bool
{
    $stmt = $pdo->prepare('SELECT id FROM productos WHERE slug = ? AND id <> ? LIMIT 1');
    $stmt->execute([$slug, $ignoreId]);
    return (bool) $stmt->fetch();
}

if (!$pdo) {
    $error = 'No hay conexión a la base de datos. Revisa el archivo privado config.php.';
} else {
    try {
        $categorias = $pdo->query('SELECT id, nombre FROM categorias WHERE activo = 1 ORDER BY orden ASC, nombre ASC')->fetchAll();
        if ($id) {
            $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = ? LIMIT 1');
            $stmt->execute([$id]);
            $producto = $stmt->fetch();
            if (!$producto) {
                $error = 'No encontramos el producto que intentas editar.';
                $id = 0;
            }
        }
    } catch (Throwable $exception) {
        $error = 'No se pudo preparar el formulario. Confirma que importaste database/schema.sql.';
    }
}

if ($pdo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombre = trim($_POST['nombre'] ?? '');
        $slug = trim($_POST['slug'] ?? '') ?: datauno_slugify($nombre);
        $slug = datauno_slugify($slug);
        $categoriaId = (int) ($_POST['categoria_id'] ?? 0);
        $descripcionCorta = trim($_POST['descripcion_corta'] ?? '');
        $descripcionLarga = trim($_POST['descripcion_larga'] ?? '');
        $detalle = trim($_POST['detalle'] ?? '');
        $badge = trim($_POST['badge'] ?? 'Validación DataUno');
        $precioTipo = $_POST['precio_tipo'] ?? 'cotizar';
        $precioValor = ($_POST['precio_valor'] ?? '') !== '' ? (float) $_POST['precio_valor'] : null;
        $stockEstado = trim($_POST['stock_estado'] ?? 'Consultar');
        $orden = (int) ($_POST['orden'] ?? 0);
        $destacado = isset($_POST['destacado']) ? 1 : 0;
        $activo = isset($_POST['activo']) ? 1 : 0;
        $instalacion = isset($_POST['instalacion_disponible']) ? 1 : 0;
        $imagenManual = trim($_POST['imagen_principal'] ?? '');
        $imagenActual = $producto['imagen_principal'] ?? 'assets/img/placa-tech.jpg';
        $imagen = $imagenManual ?: $imagenActual;
        $imagen = admin_upload_product_image('imagen_upload', $imagen);

        if (!$nombre) {
            throw new RuntimeException('Falta el nombre del producto. Ejemplo: Disco SSD 480GB.');
        }
        if (!$categoriaId) {
            throw new RuntimeException('Selecciona una categoría antes de guardar.');
        }
        if (!$descripcionCorta) {
            throw new RuntimeException('Falta la descripción corta. Esta aparece en la card del catálogo.');
        }
        if (!in_array($precioTipo, ['cotizar', 'desde', 'fijo'], true)) {
            throw new RuntimeException('Tipo de precio no válido.');
        }
        if (($precioTipo === 'desde' || $precioTipo === 'fijo') && ($precioValor === null || $precioValor <= 0)) {
            throw new RuntimeException('Si eliges “Desde” o “Precio fijo”, debes ingresar un valor mayor a 0.');
        }
        if (admin_product_slug_exists($pdo, $slug, $id)) {
            throw new RuntimeException('Ya existe otro producto con ese slug/URL. Cambia el slug para evitar rutas duplicadas.');
        }

        if ($id && $producto) {
            $stmt = $pdo->prepare('UPDATE productos SET categoria_id = ?, slug = ?, nombre = ?, descripcion_corta = ?, descripcion_larga = ?, detalle = ?, precio_tipo = ?, precio_valor = ?, imagen_principal = ?, badge = ?, stock_estado = ?, destacado = ?, activo = ?, instalacion_disponible = ?, orden = ?, updated_at = NOW() WHERE id = ?');
            $stmt->execute([$categoriaId, $slug, $nombre, $descripcionCorta, $descripcionLarga, $detalle, $precioTipo, $precioValor, $imagen, $badge, $stockEstado, $destacado, $activo, $instalacion, $orden, $id]);
            $message = 'Producto actualizado correctamente.';
        } else {
            $stmt = $pdo->prepare('INSERT INTO productos (categoria_id, slug, nombre, descripcion_corta, descripcion_larga, detalle, precio_tipo, precio_valor, imagen_principal, badge, stock_estado, destacado, activo, instalacion_disponible, orden) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$categoriaId, $slug, $nombre, $descripcionCorta, $descripcionLarga, $detalle, $precioTipo, $precioValor, $imagen, $badge, $stockEstado, $destacado, $activo, $instalacion, $orden]);
            $id = (int) $pdo->lastInsertId();
            $message = 'Producto creado correctamente. Ya puedes verlo en el catálogo.';
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

require_once __DIR__ . '/../includes/header.php';
?>

<section class="admin-section admin-crud-section">
    <div class="container">
        <div class="admin-crud-head reveal">
            <div>
                <span class="eyebrow"><?= $id ? 'Editar producto' : 'Nuevo producto'; ?></span>
                <h1><?= $id ? 'Actualizar producto.' : 'Crear producto.'; ?></h1>
                <p>Completa los datos comerciales y técnicos que verá el cliente. Los campos obligatorios están marcados con ayuda descriptiva.</p>
            </div>
            <div class="admin-actions">
                <a class="btn btn-ghost" href="productos.php">Volver</a>
                <?php if ($id): ?><a class="btn btn-primary" href="../producto.php?id=<?= urlencode($producto['slug']); ?>" target="_blank" rel="noopener">Ver ficha</a><?php endif; ?>
            </div>
        </div>

        <?php if ($message): ?><div class="admin-alert success"><?= htmlspecialchars($message); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="admin-alert error"><?= htmlspecialchars($error); ?></div><?php endif; ?>
        <?php if (!$categorias): ?><div class="admin-alert error">Antes de crear productos debes tener al menos una categoría activa.</div><?php endif; ?>

        <form class="admin-form-grid reveal" method="POST" enctype="multipart/form-data">
            <div class="admin-form-card">
                <h2>Información principal</h2>
                <p>Esta información alimenta la card del catálogo y la ficha individual.</p>
                <label>Nombre del producto *
                    <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']); ?>" placeholder="Ej: Disco SSD 480GB" required>
                    <small>Nombre claro y vendible. Evita textos demasiado largos.</small>
                </label>
                <label>Slug / URL
                    <input type="text" name="slug" value="<?= htmlspecialchars($producto['slug']); ?>" placeholder="se-crea-automatico-si-lo-dejas-vacio">
                    <small>Déjalo vacío si no sabes qué poner. Se genera automáticamente.</small>
                </label>
                <label>Categoría *
                    <select name="categoria_id" required>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= (int) $categoria['id']; ?>" <?= (int) $producto['categoria_id'] === (int) $categoria['id'] ? 'selected' : ''; ?>><?= htmlspecialchars($categoria['nombre']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Descripción corta *
                    <textarea name="descripcion_corta" rows="3" required placeholder="Ej: Mejora velocidad de arranque y carga de programas."><?= htmlspecialchars($producto['descripcion_corta']); ?></textarea>
                    <small>Aparece en la card. Máximo recomendado: 1 o 2 frases.</small>
                </label>
                <label>Detalle / validación DataUno
                    <textarea name="detalle" rows="3" placeholder="Ej: Se valida compatibilidad antes de instalar."><?= htmlspecialchars($producto['detalle']); ?></textarea>
                    <small>Aparece como explicación técnica o comercial.</small>
                </label>
                <label>Descripción larga
                    <textarea name="descripcion_larga" rows="5" placeholder="Explica para qué sirve, cuándo conviene y qué debe saber el cliente."><?= htmlspecialchars($producto['descripcion_larga']); ?></textarea>
                </label>
            </div>

            <div class="admin-form-card">
                <h2>Venta, imagen y publicación</h2>
                <p>Las imágenes subidas quedan en <strong>public_html/uploads/productos/</strong> y la base guarda la ruta pública.</p>
                <label>Tipo de precio
                    <select name="precio_tipo">
                        <option value="cotizar" <?= $producto['precio_tipo'] === 'cotizar' ? 'selected' : ''; ?>>Cotizar</option>
                        <option value="desde" <?= $producto['precio_tipo'] === 'desde' ? 'selected' : ''; ?>>Desde</option>
                        <option value="fijo" <?= $producto['precio_tipo'] === 'fijo' ? 'selected' : ''; ?>>Precio fijo</option>
                    </select>
                    <small>Para productos con stock variable, usa “Cotizar”.</small>
                </label>
                <label>Valor precio
                    <input type="number" min="0" step="100" name="precio_valor" value="<?= htmlspecialchars((string) $producto['precio_valor']); ?>" placeholder="Ej: 25000">
                    <small>Solo necesario si elegiste “Desde” o “Precio fijo”.</small>
                </label>
                <label>Badge
                    <input type="text" name="badge" value="<?= htmlspecialchars($producto['badge']); ?>" placeholder="Ej: Upgrade recomendado">
                </label>
                <label>Stock / disponibilidad
                    <input type="text" name="stock_estado" value="<?= htmlspecialchars($producto['stock_estado']); ?>" placeholder="Ej: Consultar / Sujeto a stock / Disponible">
                </label>
                <label>Imagen actual o ruta manual
                    <input type="text" name="imagen_principal" value="<?= htmlspecialchars($producto['imagen_principal']); ?>">
                    <small>Puedes usar assets/img/... o uploads/productos/...</small>
                </label>
                <?php if (!empty($producto['imagen_principal'])): ?>
                    <div class="admin-image-preview">
                        <small>Vista previa actual</small>
                        <img src="../<?= htmlspecialchars($producto['imagen_principal']); ?>" alt="Vista previa" style="max-width:100%;border-radius:16px;margin:8px 0;border:1px solid rgba(255,255,255,.12);">
                    </div>
                <?php endif; ?>
                <label>Subir imagen nueva
                    <input type="file" name="imagen_upload" accept="image/jpeg,image/png,image/webp">
                    <small>Formatos: JPG, PNG o WEBP. Máximo recomendado: 8 MB.</small>
                </label>
                <label>Orden
                    <input type="number" name="orden" value="<?= (int) $producto['orden']; ?>">
                    <small>Número menor aparece antes dentro del catálogo.</small>
                </label>
                <div class="admin-checks">
                    <label><input type="checkbox" name="destacado" <?= $producto['destacado'] ? 'checked' : ''; ?>> Destacado</label>
                    <label><input type="checkbox" name="activo" <?= $producto['activo'] ? 'checked' : ''; ?>> Activo / visible</label>
                    <label><input type="checkbox" name="instalacion_disponible" <?= $producto['instalacion_disponible'] ? 'checked' : ''; ?>> Instalación disponible</label>
                </div>
                <button class="btn btn-primary" type="submit" <?= !$categorias ? 'disabled' : ''; ?>><?= $id ? 'Guardar cambios' : 'Crear producto'; ?></button>
            </div>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>