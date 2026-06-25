<?php
$page_title = 'Catálogo de productos';
$active_page = 'catalogo';
$extra_css = ['assets/css/catalogo.css'];
require_once __DIR__ . '/includes/header.php';
$categoriasProductos = array_values(array_unique(array_column($productosCatalogo, 'categoria')));

function productCardMeta(array $producto): array
{
    $categoria = mb_strtolower($producto['categoria']);

    if (str_contains($categoria, 'almacenamiento')) {
        return ['Acelera arranque', 'Instalación disponible', 'Respaldo opcional'];
    }
    if (str_contains($categoria, 'memorias')) {
        return ['Más multitarea', 'Compatibilidad previa', 'Rendimiento diario'];
    }
    if (str_contains($categoria, 'repuestos')) {
        return ['Según modelo', 'Revisión técnica', 'Cambio asistido'];
    }
    if (str_contains($categoria, 'accesorios')) {
        return ['Conexión correcta', 'Stock variable', 'Uso diario'];
    }
    if (str_contains($categoria, 'mantención')) {
        return ['Control temperatura', 'Limpieza técnica', 'Vida útil'];
    }
    if (str_contains($categoria, 'periféricos')) {
        return ['Oficina / hogar', 'Reemplazo rápido', 'Cotización simple'];
    }
    if (str_contains($categoria, 'combos')) {
        return ['Diagnóstico primero', 'Upgrade completo', 'Mejor inversión'];
    }

    return ['Validación técnica', 'Cotización asistida', 'Soporte DataUno'];
}
?>

<section class="catalog-showcase">
    <div class="container catalog-showcase-grid reveal">
        <div class="catalog-showcase-copy">
            <span class="eyebrow">Catálogo DataUno</span>
            <h1>Productos TI para reparar, mejorar y extender la vida de tu equipo.</h1>
            <p>Elige productos, agrégalos al carrito y envía tu cotización por WhatsApp. DataUno valida compatibilidad, disponibilidad e instalación antes de recomendar una compra.</p>
            <div class="catalog-quick-tags" aria-label="Beneficios del catálogo">
                <span>Compatibilidad previa</span>
                <span>Instalación disponible</span>
                <span>Asesoría técnica</span>
            </div>
        </div>

        <aside class="catalog-command-panel" aria-label="Panel de cotización DataUno">
            <div class="command-topline">
                <span></span><span></span><span></span>
            </div>
            <h2>Carrito técnico</h2>
            <p>Arma una solicitud y la revisamos como cotización asistida, no como compra a ciegas.</p>
            <button class="cart-open-btn catalog-cart-button" type="button" data-cart-open>
                <span class="cart-icon">🛒</span>
                <span>Ver carrito</span>
                <strong data-cart-count>0</strong>
            </button>
        </aside>
    </div>
</section>

<section class="section catalog-products-section catalog-v10-section">
    <div class="container">
        <div class="catalog-toolbar catalog-toolbar-v10 reveal">
            <div>
                <span class="eyebrow">Productos disponibles</span>
                <h2>Catálogo ordenado por necesidad.</h2>
                <p>Cards técnicas con uso recomendado, validación e instalación cuando corresponde.</p>
            </div>
        </div>

        <div class="catalog-controls catalog-controls-v10 reveal">
            <label class="catalog-search">
                <span>Buscar producto</span>
                <input type="search" placeholder="SSD, RAM, cargador, teclado..." data-product-search>
            </label>
            <div class="filter-bar" aria-label="Filtros de catálogo">
                <button class="filter-btn active" data-filter="todos">Todos</button>
                <?php foreach ($categoriasProductos as $categoria): ?>
                    <button class="filter-btn" data-filter="<?= htmlspecialchars($categoria); ?>"><?= htmlspecialchars($categoria); ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="product-grid product-grid-v10" data-catalog-grid>
            <?php foreach ($productosCatalogo as $producto): ?>
                <?php $metas = productCardMeta($producto); ?>
                <article class="product-card product-card-v10 reveal"
                    data-category="<?= htmlspecialchars($producto['categoria']); ?>"
                    data-product-card
                    data-search="<?= htmlspecialchars(strtolower($producto['nombre'] . ' ' . $producto['categoria'] . ' ' . $producto['descripcion'] . ' ' . $producto['detalle'])); ?>">

                    <a class="product-card-main product-card-main-v10" href="producto.php?id=<?= urlencode($producto['id']); ?>" aria-label="Ver detalle de <?= htmlspecialchars($producto['nombre']); ?>">
                        <div class="product-image-wrap product-image-wrap-v10">
                            <img src="<?= htmlspecialchars($producto['imagen']); ?>" alt="<?= htmlspecialchars($producto['nombre']); ?>" loading="lazy">
                            <span class="product-badge product-badge-v10"><?= htmlspecialchars($producto['badge']); ?></span>
                            <span class="product-scanline" aria-hidden="true"></span>
                        </div>

                        <div class="product-content product-content-v10">
                            <div class="product-kicker-row">
                                <span class="card-category"><?= htmlspecialchars($producto['categoria']); ?></span>
                                <?php if (!empty($producto['destacado'])): ?>
                                    <span class="featured-dot">Destacado</span>
                                <?php endif; ?>
                            </div>
                            <h2><?= htmlspecialchars($producto['nombre']); ?></h2>
                            <p><?= htmlspecialchars($producto['descripcion']); ?></p>
                            <div class="product-meta-pills">
                                <?php foreach ($metas as $meta): ?>
                                    <span><?= htmlspecialchars($meta); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </a>

                    <div class="product-tech-strip">
                        <span>Validación DataUno</span>
                        <small><?= htmlspecialchars($producto['detalle']); ?></small>
                    </div>

                    <div class="product-footer product-actions-row product-actions-v10">
                        <strong><?= htmlspecialchars($producto['precio']); ?></strong>
                        <a class="btn-detail" href="producto.php?id=<?= urlencode($producto['id']); ?>">Ficha</a>
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

<section class="section cta-band catalog-help-band">
    <div class="container cta-card reveal">
        <div>
            <span class="eyebrow">Cotización asistida</span>
            <h2>¿No sabes qué pieza necesita tu equipo?</h2>
            <p>Envíanos el modelo, síntoma o una foto. Revisamos compatibilidad antes de recomendar compra, cambio o upgrade.</p>
        </div>
        <a class="btn btn-primary" href="<?= $contacto['whatsapp_link']; ?>?text=Hola%20DataUno,%20necesito%20ayuda%20para%20elegir%20un%20producto%20o%20repuesto" target="_blank" rel="noopener">Pedir orientación</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/cart-drawer.php'; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
