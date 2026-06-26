<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/db.php';

function datauno_price_label(?string $tipo, $valor = null): string
{
    if ($tipo === 'desde' && $valor !== null && $valor !== '') {
        return 'Desde $' . number_format((float) $valor, 0, ',', '.');
    }

    if ($tipo === 'fijo' && $valor !== null && $valor !== '') {
        return '$' . number_format((float) $valor, 0, ',', '.');
    }

    return 'Cotizar';
}

function datauno_normalize_product(array $row): array
{
    return [
        'id' => $row['slug'] ?? $row['id'] ?? '',
        'categoria' => $row['categoria_nombre'] ?? $row['categoria'] ?? 'General',
        'nombre' => $row['nombre'] ?? '',
        'descripcion' => $row['descripcion_corta'] ?? $row['descripcion'] ?? '',
        'detalle' => $row['detalle'] ?? $row['descripcion_larga'] ?? '',
        'precio' => $row['precio_label'] ?? datauno_price_label($row['precio_tipo'] ?? 'cotizar', $row['precio_valor'] ?? null),
        'imagen' => $row['imagen_principal'] ?: 'assets/img/placa-tech.jpg',
        'badge' => $row['badge'] ?? 'Validación DataUno',
        'destacado' => !empty($row['destacado']),
        'stock_estado' => $row['stock_estado'] ?? 'Consultar',
        'instalacion_disponible' => !empty($row['instalacion_disponible']),
        'activo' => isset($row['activo']) ? (bool) $row['activo'] : true,
        'orden' => (int) ($row['orden'] ?? 0),
    ];
}

function datauno_get_productos_catalogo(bool $includeInactive = false): array
{
    global $productosCatalogo;

    if (!datauno_db_ready()) {
        return $productosCatalogo ?? [];
    }

    try {
        $pdo = datauno_pdo();
        $where = $includeInactive ? '' : 'WHERE p.activo = 1 AND c.activo = 1';
        $stmt = $pdo->query("\n            SELECT p.*, c.nombre AS categoria_nombre\n            FROM productos p\n            INNER JOIN categorias c ON c.id = p.categoria_id\n            {$where}\n            ORDER BY p.destacado DESC, p.orden ASC, p.nombre ASC\n        ");

        return array_map('datauno_normalize_product', $stmt->fetchAll());
    } catch (Throwable $exception) {
        return $productosCatalogo ?? [];
    }
}

function datauno_get_producto(string $slug): ?array
{
    foreach (datauno_get_productos_catalogo(true) as $producto) {
        if ($producto['id'] === $slug) {
            return $producto;
        }
    }

    return null;
}

function datauno_get_categorias_productos(bool $includeInactive = false): array
{
    if (!datauno_db_ready()) {
        return array_values(array_unique(array_column(datauno_get_productos_catalogo(), 'categoria')));
    }

    try {
        $pdo = datauno_pdo();
        $where = $includeInactive ? '' : 'WHERE activo = 1';
        $stmt = $pdo->query("SELECT nombre FROM categorias {$where} ORDER BY orden ASC, nombre ASC");
        return array_column($stmt->fetchAll(), 'nombre');
    } catch (Throwable $exception) {
        return array_values(array_unique(array_column(datauno_get_productos_catalogo(), 'categoria')));
    }
}

function datauno_get_productos_sugeridos(array $productoActual, int $limit = 4): array
{
    $productos = datauno_get_productos_catalogo();
    $sugeridos = [];

    foreach ($productos as $producto) {
        if ($producto['id'] !== $productoActual['id'] && $producto['categoria'] === $productoActual['categoria']) {
            $sugeridos[] = $producto;
        }
    }

    foreach ($productos as $producto) {
        if (count($sugeridos) >= $limit) {
            break;
        }
        if ($producto['id'] !== $productoActual['id'] && !in_array($producto, $sugeridos, true) && !empty($producto['destacado'])) {
            $sugeridos[] = $producto;
        }
    }

    return array_slice($sugeridos, 0, $limit);
}

function datauno_slugify(string $text): string
{
    $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text) ?: $text;
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    return $text ?: uniqid('producto-', false);
}
