<?php
$page_title = 'Detalle de producto';
$active_page = 'catalogo';
$extra_css = ['assets/css/catalogo.css', 'assets/css/producto.css'];
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/product-repository.php';

$product_id = $_GET['id'] ?? '';
$productoActual = datauno_get_producto($product_id);
$productosCatalogo = datauno_get_productos_catalogo();

if (!$productoActual) {
    http_response_code(404);
}

function detalleProductoPorCategoria(array $producto): array
{
    $categoria = mb_strtolower($producto['categoria']);

    $base = [
        'Qué revisamos antes' => [
            'Modelo exacto del equipo y compatibilidad real.',
            'Necesidad del cliente: reparar, mejorar o extender vida útil.',
            'Disponibilidad del producto y alternativa recomendada si aplica.',
        ],
        'Ideal para' => [
            'Clientes que quieren evitar compras incorrectas.',
            'Equipos con lentitud, fallas o componentes agotados.',
            'Personas y negocios que buscan una orientación técnica clara.',
        ],
    ];

    if (str_contains($categoria, 'almacenamiento')) {
        $base['Qué mejora'] = ['Arranque más rápido del sistema.', 'Apertura más fluida de programas y archivos.', 'Puede combinarse con respaldo e instalación limpia.'];
        $base['Instalación DataUno'] = ['Validamos interfaz y formato.', 'Respaldamos información si corresponde.', 'Dejamos el equipo probado antes de entrega.'];
    } elseif (str_contains($categoria, 'memorias')) {
        $base['Qué mejora'] = ['Más estabilidad con varias aplicaciones abiertas.', 'Mejor respuesta en navegación y programas de oficina.', 'Menos saturación cuando el equipo trabaja al límite.'];
        $base['Instalación DataUno'] = ['Validamos DDR, frecuencia y límite del equipo.', 'Revisamos ranuras disponibles.', 'Probamos estabilidad posterior al cambio.'];
    } elseif (str_contains($categoria, 'repuestos')) {
        $base['Qué mejora'] = ['Recupera piezas dañadas o agotadas.', 'Evita cambiar el equipo completo cuando aún puede repararse.', 'Permite extender la vida útil del notebook o PC.'];
        $base['Instalación DataUno'] = ['Se cotiza según modelo exacto.', 'Revisamos origen de la falla antes de cambiar.', 'Instalación disponible según compatibilidad.'];
    } elseif (str_contains($categoria, 'accesorios') || str_contains($categoria, 'periféricos')) {
        $base['Qué mejora'] = ['Mejor conectividad y uso diario.', 'Reemplazo rápido de elementos esenciales.', 'Opciones según presupuesto y necesidad real.'];
        $base['Instalación DataUno'] = ['Validamos conectores, voltaje o compatibilidad.', 'Recomendamos alternativas cuando conviene.', 'Cotización sujeta a disponibilidad.'];
    } elseif (str_contains($categoria, 'mantención')) {
        $base['Qué mejora'] = ['Temperaturas más controladas.', 'Menos ruido y mejor ventilación.', 'Prevención de fallas por sobrecalentamiento.'];
        $base['Instalación DataUno'] = ['Limpieza técnica del equipo.', 'Cambio de pasta térmica si corresponde.', 'Revisión general posterior.'];
    } else {
        $base['Qué mejora'] = ['Rendimiento más estable.', 'Mejor experiencia de uso.', 'Solución ajustada al diagnóstico.'];
        $base['Instalación DataUno'] = ['Recomendación según diagnóstico.', 'Cotización final según stock y equipo.', 'Puede combinarse con soporte técnico.'];
    }

    return $base;
}

function productoMicroResumen(array $producto): array
{
    $categoria = mb_strtolower($producto['categoria']);
    if (str_contains($categoria, 'almacenamiento')) return ['Velocidad', 'Respaldo opcional', 'Instalación'];
    if (str_contains($categoria, 'memorias')) return ['Multitarea', 'Compatibilidad', 'Estabilidad'];
    if (str_contains($categoria, 'repuestos')) return ['Modelo exacto', 'Cambio técnico', 'Diagnóstico'];
    if (str_contains($categoria, 'mantención')) return ['Temperatura', 'Limpieza', 'Prevención'];
    return ['Cotización', 'Validación', 'Soporte'];
}

$bloquesDetalle = $productoActual ? detalleProductoPorCategoria($productoActual) : [];
$microResumen = $productoActual ? productoMicroResumen($productoActual) : [];
$productosSugeridos = $productoActual ? datauno_get_productos_sugeridos($productoActual, 4) : [];
?>

<?php if (!$productoActual): ?>
    <section class="product-detail-shell not-found-product">
        <div class="container product-not-found-card reveal">
            <span class="eyebrow">Producto no encontrado</span>
            <h1>No encontramos ese producto.</h1>
            <p>Puede que haya cambiado la ruta o que el producto ya no esté disponible.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="catalogo.php">Volver al catálogo</a>
                <button class="cart-open-btn" type="button" data-cart-open><span>Carrito</span><strong data-cart-count>0</strong></button>
            </div>
        </div>
    </section>
<?php else: ?>
    <section class="product-detail-shell">
        <div class="container product-detail-layout reveal">
            <div class="product-detail-main-card">
                <a class="back-link" href="catalogo.php">← Volver al catálogo</a>
                <div class="product-detail-kicker">
                    <span class="eyebrow"><?= htmlspecialchars($productoActual['categoria']); ?></span>
                    <span class="product-detail-status">Validación previa</span>
                </div>
                <h1><?= htmlspecialchars($productoActual['nombre']); ?></h1>
                <p><?= htmlspecialchars($productoActual['descripcion']); ?></p>

                <div class="product-detail-pills">
                    <?php foreach ($microResumen as $meta): ?>
                        <span><?= htmlspecialchars($meta); ?></span>
                    <?php endforeach; ?>
                </div>

                <div class="product-detail-actions">
                    <button class="btn btn-primary" type="button"
                        data-add-cart
                        data-id="<?= htmlspecialchars($productoActual['id']); ?>"
                        data-name="<?= htmlspecialchars($productoActual['nombre']); ?>"
                        data-category="<?= htmlspecialchars($productoActual['categoria']); ?>"
                        data-price="<?= htmlspecialchars($productoActual['precio']); ?>">
                        Agregar al carrito
                    </button>
                    <a class="btn btn-ghost" href="<?= $contacto['whatsapp_link']; ?>?text=<?= urlencode('Hola DataUno, quiero cotizar el producto: ' . $productoActual['nombre'] . '. Necesito validar compatibilidad y disponibilidad.'); ?>" target="_blank" rel="noopener">Cotizar este producto</a>
                    <button class="cart-open-btn compact-cart" type="button" data-cart-open><span>🛒</span><strong data-cart-count>0</strong></button>
                </div>
            </div>

            <aside class="product-detail-media-panel">
                <div class="product-detail-image-card">
                    <img src="<?= htmlspecialchars($productoActual['imagen']); ?>" alt="<?= htmlspecialchars($productoActual['nombre']); ?>">
                    <span><?= htmlspecialchars($productoActual['badge']); ?></span>
                </div>
                <div class="product-mini-specs">
                    <div><strong><?= htmlspecialchars($productoActual['precio']); ?></strong><span>Precio</span></div>
                    <div><strong>Sujeto a stock</strong><span>Disponibilidad</span></div>
                    <div><strong>Disponible</strong><span>Instalación</span></div>
                </div>
            </aside>
        </div>
    </section>

    <section class="section product-detail-body product-body-v10">
        <div class="container product-info-layout">
            <article class="product-summary-card product-summary-v10 reveal">
                <span class="eyebrow">Ficha técnica comercial</span>
                <h2>Compra asistida, no compra a ciegas.</h2>
                <p><?= htmlspecialchars($productoActual['detalle']); ?></p>
                <div class="summary-list">
                    <span><strong>Categoría</strong><?= htmlspecialchars($productoActual['categoria']); ?></span>
                    <span><strong>Estado</strong>Sujeto a disponibilidad</span>
                    <span><strong>Instalación</strong>Según equipo y diagnóstico</span>
                </div>
            </article>

            <div class="product-detail-blocks product-detail-blocks-v10">
                <?php foreach ($bloquesDetalle as $titulo => $items): ?>
                    <article class="detail-block detail-block-v10 reveal">
                        <h3><?= htmlspecialchars($titulo); ?></h3>
                        <ul>
                            <?php foreach ($items as $item): ?>
                                <li><?= htmlspecialchars($item); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php if ($productosSugeridos): ?>
        <section class="section suggested-section suggested-section-v10">
            <div class="container">
                <div class="section-title reveal">
                    <span class="eyebrow">También podría servirte</span>
                    <h2>Productos sugeridos.</h2>
                    <p>Recomendaciones relacionadas para completar reparación, mantención o upgrade.</p>
                </div>
                <div class="product-grid product-grid-v10 suggested-grid">
                    <?php foreach ($productosSugeridos as $producto): ?>
                        <article class="product-card product-card-v10 compact-suggested-card reveal" data-category="<?= htmlspecialchars($producto['categoria']); ?>">
                            <a class="product-card-main product-card-main-v10" href="producto.php?id=<?= urlencode($producto['id']); ?>">
                                <div class="product-image-wrap product-image-wrap-v10">
                                    <img src="<?= htmlspecialchars($producto['imagen']); ?>" alt="<?= htmlspecialchars($producto['nombre']); ?>" loading="lazy">
                                    <span class="product-badge product-badge-v10"><?= htmlspecialchars($producto['badge']); ?></span>
                                </div>
                                <div class="product-content product-content-v10">
                                    <span class="card-category"><?= htmlspecialchars($producto['categoria']); ?></span>
                                    <h2><?= htmlspecialchars($producto['nombre']); ?></h2>
                                    <p><?= htmlspecialchars($producto['descripcion']); ?></p>
                                </div>
                            </a>
                            <div class="product-footer product-actions-row product-actions-v10">
                                <strong><?= htmlspecialchars($producto['precio']); ?></strong>
                                <button class="btn-add-cart" type="button"
                                    data-add-cart
                                    data-id="<?= htmlspecialchars($producto['id']); ?>"
                                    data-name="<?= htmlspecialchars($producto['nombre']); ?>"
                                    data-category="<?= htmlspecialchars($producto['categoria']); ?>"
                                    data-price="<?= htmlspecialchars($producto['precio']); ?>">
                                    Agregar
                                </button>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/cart-drawer.php'; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
