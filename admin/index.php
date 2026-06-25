<?php
$page_title = 'Admin';
$active_page = 'admin';
$extra_css = ['assets/css/admin.css'];
require_once __DIR__ . '/../includes/header.php';
?>

<section class="admin-hero">
    <div class="container admin-hero-grid">
        <div class="admin-copy reveal">
            <span class="eyebrow">Panel DataUno</span>
            <h1>Administración preparada para productos, catálogo y cotizaciones.</h1>
            <p>Esta vista ya queda con estética DataUno. En la siguiente fase conectamos login real, MySQL de cPanel y CRUD de productos para manejar el catálogo sin tocar código.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="../catalogo.php">Ver catálogo</a>
                <a class="btn btn-ghost" href="../index.php">Volver al inicio</a>
            </div>
        </div>

        <form class="admin-login-card reveal" action="#" method="post">
            <div class="admin-card-head">
                <span>🔐</span>
                <div>
                    <strong>Login privado</strong>
                    <small>Próxima conexión con sesión PHP</small>
                </div>
            </div>
            <label>Usuario
                <input type="text" placeholder="admin@datauno.cl" disabled>
            </label>
            <label>Contraseña
                <input type="password" placeholder="••••••••" disabled>
            </label>
            <button class="btn btn-primary" type="button" disabled>Ingreso reservado</button>
            <p>Panel visual listo. La autenticación real se activa al conectar la base de datos.</p>
        </form>
    </div>
</section>

<section class="admin-section">
    <div class="container">
        <div class="section-title reveal">
            <span class="eyebrow">Módulos próximos</span>
            <h2>Lo que administrará DataUno.</h2>
            <p>El catálogo de productos quedará controlado desde este panel: productos, categorías, precios, estado, imágenes y destacados.</p>
        </div>

        <div class="admin-module-grid">
            <article class="admin-module reveal">
                <span>🧩</span>
                <h3>Productos</h3>
                <p>Crear, editar, ocultar, destacar y ordenar productos del catálogo.</p>
                <small>Nombre · descripción · precio · estado · imagen</small>
            </article>
            <article class="admin-module reveal">
                <span>🏷️</span>
                <h3>Categorías</h3>
                <p>Organizar SSD, RAM, repuestos, accesorios, periféricos y combos.</p>
                <small>Filtros visibles para clientes</small>
            </article>
            <article class="admin-module reveal">
                <span>🛒</span>
                <h3>Cotizaciones</h3>
                <p>Preparado para recibir solicitudes desde carrito y WhatsApp.</p>
                <small>Fase posterior: historial y seguimiento</small>
            </article>
            <article class="admin-module reveal">
                <span>🖼️</span>
                <h3>Imágenes</h3>
                <p>Subida de fotos de productos, repuestos, trabajos y destacados.</p>
                <small>Carpeta uploads/productos</small>
            </article>
        </div>
    </div>
</section>

<section class="admin-section admin-roadmap">
    <div class="container admin-roadmap-card reveal">
        <div>
            <span class="eyebrow">Siguiente fase</span>
            <h2>De maqueta a panel real.</h2>
            <p>Cuando activemos MySQL en cPanel, este admin dejará de ser visual y pasará a controlar el catálogo completo.</p>
        </div>
        <ol>
            <li>Crear base de datos en cPanel.</li>
            <li>Configurar conexión segura PHP.</li>
            <li>Crear login de administrador.</li>
            <li>Activar CRUD de productos.</li>
        </ol>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
