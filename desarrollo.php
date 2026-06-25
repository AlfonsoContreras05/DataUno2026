<?php
$page_title = 'Desarrollo de software';
$active_page = 'desarrollo';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero development-hero">
    <div class="container hero-grid">
        <div class="hero-copy reveal">
            <span class="eyebrow">Soluciones digitales</span>
            <h1>Desarrollo de software para negocios que necesitan ordenarse, vender y crecer.</h1>
            <p class="hero-lead">Landing pages, catálogos administrables, sistemas internos, dashboards y automatizaciones con enfoque práctico: menos caos, más control.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="<?= $contacto['whatsapp_link']; ?>?text=Hola%20DataUno,%20quiero%20cotizar%20un%20proyecto%20de%20software" target="_blank" rel="noopener">Cotizar proyecto</a>
                <a class="btn btn-ghost" href="catalogo.php">Ver catálogo técnico</a>
            </div>
        </div>
        <div class="legacy-code-card reveal" aria-label="Panel visual de desarrollo DataUno">
            <pre><code>const negocio = {
  presencia: 'web profesional',
  ventas: 'WhatsApp + catálogo',
  gestion: 'panel administrativo',
  datos: 'reportes claros',
  soporte: 'acompañamiento real'
};</code></pre>
        </div>
    </div>
</section>

<section class="section section-dark">
    <div class="container legacy-split">
        <div class="legacy-copy reveal">
            <span class="eyebrow">En síntesis</span>
            <h2>Soluciones tecnológicas integrales, con enfoque real.</h2>
        </div>
        <div class="lead-box dark reveal">
            <p>Antes de escribir código, entendemos el flujo del negocio: qué se vende, cómo se registra, dónde se pierde tiempo y qué herramienta ayudaría de verdad. La meta no es hacer software grande: es hacer software útil.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-title reveal">
            <span class="eyebrow">Servicios de desarrollo</span>
            <h2>Herramientas web con foco práctico.</h2>
            <p>La idea no es hacer software por hacerlo: es construir soluciones que ayuden a vender, ordenar, registrar o automatizar.</p>
        </div>
        <div class="cards-grid three">
            <?php foreach ($serviciosDesarrollo as $servicio): ?>
                <article class="service-card reveal">
                    <div class="card-icon"><?= $servicio['icono']; ?></div>
                    <h3><?= htmlspecialchars($servicio['titulo']); ?></h3>
                    <p><?= htmlspecialchars($servicio['descripcion']); ?></p>
                    <div class="card-footer">
                        <strong>Cotizar</strong>
                        <a href="<?= $contacto['whatsapp_link']; ?>?text=Hola%20DataUno,%20quiero%20cotizar:%20<?= urlencode($servicio['titulo']); ?>" target="_blank" rel="noopener">Hablar</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="tech-strip">
    <div class="container tech-strip-card reveal">
        <div>
            <span class="eyebrow">Enfoque DataUno</span>
            <h2>La tecnología no debe ser un problema: debe ser una ventaja.</h2>
            <p>Partimos pequeño, dejamos una base funcional y preparamos el camino para crecer sin rehacer todo.</p>
        </div>
        <a class="btn btn-primary" href="<?= $contacto['whatsapp_link']; ?>?text=Hola%20DataUno,%20quiero%20una%20soluci%C3%B3n%20digital%20para%20mi%20negocio" target="_blank" rel="noopener">Hablar sobre mi proyecto</a>
    </div>
</section>

<section class="section">
    <div class="container legacy-split">
        <div class="legacy-copy reveal">
            <span class="eyebrow">Pymes y emprendimientos</span>
            <h2>Tu negocio necesita más que una página bonita: necesita una herramienta que trabaje contigo.</h2>
            <p>Podemos empezar con una landing, un catálogo o un formulario, y luego crecer hacia paneles, reportes o automatizaciones.</p>
            <div class="trust-row">
                <span>Catálogo + WhatsApp</span>
                <span>Panel admin</span>
                <span>Reportes simples</span>
                <span>Automatización</span>
            </div>
        </div>
        <div class="business-grid">
            <article class="service-card reason-card reveal"><span class="card-category">01</span><h3>Más orden</h3><p>Información clara para trabajar sin perder datos.</p></article>
            <article class="service-card reason-card reveal"><span class="card-category">02</span><h3>Más ventas</h3><p>Canales directos para mostrar servicios y cotizar.</p></article>
            <article class="service-card reason-card reveal"><span class="card-category">03</span><h3>Menos tareas</h3><p>Flujos simples que reducen trabajo repetitivo.</p></article>
            <article class="service-card reason-card reveal"><span class="card-category">04</span><h3>Más control</h3><p>Indicadores y registros para tomar decisiones.</p></article>
        </div>
    </div>
</section>

<section class="section section-dark">
    <div class="container">
        <div class="section-title reveal">
            <span class="eyebrow">Proceso de trabajo</span>
            <h2>De la necesidad a la solución, paso a paso.</h2>
        </div>
        <div class="process-mini">
            <article class="step-card reveal"><span>01</span><h3>Escuchamos</h3><p>Entendemos el problema y el flujo real.</p></article>
            <article class="step-card reveal"><span>02</span><h3>Diseñamos</h3><p>Definimos pantallas, datos y prioridades.</p></article>
            <article class="step-card reveal"><span>03</span><h3>Prototipamos</h3><p>Armamos una primera versión revisable.</p></article>
            <article class="step-card reveal"><span>04</span><h3>Publicamos</h3><p>Subimos la solución y dejamos instrucciones.</p></article>
            <article class="step-card reveal"><span>05</span><h3>Acompañamos</h3><p>Preparamos mejoras y soporte futuro.</p></article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container contact-grid">
        <div class="contact-copy reveal">
            <span class="eyebrow">Cotización</span>
            <h2>Cuéntanos qué necesitas y armamos una solución contigo.</h2>
            <p>Describe el proceso, la idea o el problema. DataUno puede ayudarte a convertirlo en una herramienta simple y usable.</p>
            <div class="contact-panel">
                <a class="contact-chip" href="<?= $contacto['whatsapp_link']; ?>" target="_blank" rel="noopener"><strong>WhatsApp</strong><?= $contacto['whatsapp']; ?></a>
                <a class="contact-chip" href="mailto:<?= $contacto['email']; ?>"><strong>Correo</strong><?= $contacto['email']; ?></a>
                <span class="contact-chip"><strong>Zona</strong>Victoria, Araucanía · atención remota para desarrollo</span>
            </div>
        </div>
        <form class="contact-form reveal" action="https://formsubmit.co/<?= $contacto['email']; ?>" method="POST">
            <label>Nombre
                <input type="text" name="nombre" placeholder="Tu nombre" required>
            </label>
            <label>Correo o teléfono
                <input type="text" name="contacto" placeholder="Cómo te contactamos" required>
            </label>
            <label>Tipo de proyecto
                <select name="tipo_proyecto">
                    <option>Landing page</option>
                    <option>Catálogo administrable</option>
                    <option>Sistema interno</option>
                    <option>Dashboard / reportes</option>
                    <option>Automatización</option>
                </select>
            </label>
            <label>Idea o necesidad
                <textarea name="mensaje" placeholder="Ej: necesito mostrar productos, recibir pedidos por WhatsApp y administrar precios"></textarea>
            </label>
            <button class="btn btn-primary" type="submit">Enviar solicitud</button>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
