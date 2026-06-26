<?php
$page_title = 'Servicio técnico de computadores en Victoria';
$active_page = 'inicio';
require_once __DIR__ . '/includes/header.php';
$serviciosDestacados = array_filter($serviciosTecnicos, fn($servicio) => $servicio['destacado']);
?>

<section class="hero-section">
    <div class="container hero-grid">
        <div class="hero-copy reveal">
            <span class="eyebrow">Victoria, Araucanía · Servicio técnico confiable</span>
            <h1>Reparamos y optimizamos tu computador para que vuelva a rendir.</h1>
            <p class="hero-lead">Servicio técnico de computadores y notebooks con foco real en diagnóstico, mantención, formateo, cambio de piezas, upgrades y soluciones claras antes de invertir.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="<?= $contacto['whatsapp_link']; ?>?text=Hola%20DataUno,%20necesito%20cotizar%20un%20servicio%20t%C3%A9cnico" target="_blank" rel="noopener">Cotizar por WhatsApp</a>
                <a class="btn btn-ghost" href="catalogo.php">Ver servicios</a>
            </div>
            <div class="trust-row" aria-label="Servicios destacados">
                <span>TI profesional</span>
                <span>Mantención</span>
                <span>SSD/RAM</span>
                <span>Soporte empresas</span>
            </div>
        </div>
        <div class="hero-panel reveal" aria-label="Identidad DataUno">
            <div class="hero-orbit" aria-hidden="true">
                <span class="orbit-ring orbit-ring-one"></span>
                <span class="orbit-ring orbit-ring-two"></span>
                <span class="orbit-dot"></span>
                <span class="orbit-core"></span>
            </div>
            <div class="datauno-identity-card">
                <div class="identity-noise"></div>
                <div class="identity-ring ring-one"></div>
                <div class="identity-ring ring-two"></div>
                <img class="identity-logo" src="assets/img/version_2.png" alt="DataUno Soluciones Informáticas" loading="lazy">
                <h2>Diagnóstico +<br>Solución + Evolución</h2>
                <p>Unimos servicio técnico, desarrollo de software y asesoría digital en una experiencia integral.</p>
                <div class="hero-service-list">
                    <span><b></b> Reparación y mantención</span>
                    <span><b></b> Upgrades SSD / RAM</span>
                    <span><b></b> Desarrollo de sistemas</span>
                    <span><b></b> Soporte para negocios</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section-dark">
    <div class="container legacy-split">
        <div class="legacy-copy reveal">
            <span class="eyebrow">En síntesis</span>
            <h2>Soluciones tecnológicas integrales, sin vueltas y con enfoque real.</h2>
        </div>
        <div class="lead-box dark reveal">
            <p>DataUno trabaja desde el problema real: lentitud, fallas, equipos desactualizados, pérdida de datos o necesidad de ordenar un negocio con herramientas digitales. Primero entendemos, después proponemos.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-title reveal">
            <span class="eyebrow">Líneas de trabajo</span>
            <h2>Tres frentes para cubrir toda la operación tecnológica.</h2>
            <p>Servicio técnico, desarrollo web y soporte para que la tecnología no sea una carga: sea una ventaja.</p>
        </div>
        <div class="line-grid">
            <article class="service-card line-card reveal">
                <div class="card-icon">🛠️</div>
                <h3>Servicio técnico</h3>
                <p>Diagnóstico, formateo, mantención, respaldo, reparación y mejora de equipos.</p>
                <ul>
                    <li>Notebook y PC escritorio</li>
                    <li>Software y hardware</li>
                    <li>Diagnóstico claro</li>
                </ul>
            </article>
            <article class="service-card line-card reveal">
                <div class="card-icon">⚡</div>
                <h3>Desarrollo web</h3>
                <p>Landing pages, catálogos, paneles, formularios y sistemas simples para negocios.</p>
                <ul>
                    <li>Web profesional</li>
                    <li>Ventas por WhatsApp</li>
                    <li>Automatización básica</li>
                </ul>
            </article>
            <article class="service-card line-card reveal">
                <div class="card-icon">🏢</div>
                <h3>Soporte empresas</h3>
                <p>Asesoría para mantener equipos, procesos y herramientas digitales en orden.</p>
                <ul>
                    <li>Soporte preventivo</li>
                    <li>Mejora de procesos</li>
                    <li>Acompañamiento TI</li>
                </ul>
            </article>
        </div>
    </div>
</section>

<section class="tech-strip">
    <div class="container tech-strip-card reveal">
        <div>
            <span class="eyebrow">Nuestra misión</span>
            <h2>La tecnología no debe ser un problema: debe ser una ventaja.</h2>
        </div>
        <a class="btn btn-primary" href="<?= $contacto['whatsapp_link']; ?>?text=Hola%20DataUno,%20quiero%20hacer%20una%20consulta%20t%C3%A9cnica" target="_blank" rel="noopener">Hablar con un técnico</a>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-title reveal">
            <span class="eyebrow">Servicios principales</span>
            <h2>Lo más solicitado.</h2>
            <p>Un punto de partida claro para clientes que necesitan recuperar rendimiento, estabilidad o capacidad en sus equipos.</p>
        </div>

        <div class="cards-grid three">
            <?php foreach ($serviciosDestacados as $servicio): ?>
                <article class="service-card reveal">
                    <div class="card-icon"><?= $servicio['icono']; ?></div>
                    <span class="card-category"><?= htmlspecialchars($servicio['categoria']); ?></span>
                    <h3><?= htmlspecialchars($servicio['titulo']); ?></h3>
                    <p><?= htmlspecialchars($servicio['descripcion']); ?></p>
                    <div class="card-footer">
                        <strong><?= htmlspecialchars($servicio['precio']); ?></strong>
                        <a href="<?= $contacto['whatsapp_link']; ?>?text=Hola%20DataUno,%20quiero%20cotizar:%20<?= urlencode($servicio['titulo']); ?>" target="_blank" rel="noopener">Cotizar</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-dark process-section">
    <div class="container">
        <div class="section-title reveal">
            <span class="eyebrow">De la necesidad a la solución</span>
            <h2>Paso a paso, sin confundir al cliente.</h2>
        </div>
        <div class="process-mini">
            <article class="step-card reveal">
                <span>01</span>
                <h3>Escuchamos</h3>
                <p>Nos cuentas el síntoma, urgencia y tipo de equipo.</p>
            </article>
            <article class="step-card reveal">
                <span>02</span>
                <h3>Diagnosticamos</h3>
                <p>Revisamos si es software, hardware o mantenimiento.</p>
            </article>
            <article class="step-card reveal">
                <span>03</span>
                <h3>Proponemos</h3>
                <p>Entregamos alternativas según presupuesto y prioridad.</p>
            </article>
            <article class="step-card reveal">
                <span>04</span>
                <h3>Ejecutamos</h3>
                <p>Reparamos, optimizamos o instalamos lo necesario.</p>
            </article>
            <article class="step-card reveal">
                <span>05</span>
                <h3>Acompañamos</h3>
                <p>Te explicamos cuidados y próximos pasos.</p>
            </article>
        </div>
    </div>
</section>

<section class="section opiniones-section" id="opiniones">
    <div class="container opiniones-stack">
        <div class="opiniones-info reveal">
            <div class="opiniones-copy">
                <span class="eyebrow">Opiniones reales</span>
                <h2>Comentarios originales de clientes DataUno.</h2>
                <p>Recuperamos el muro original de reseñas y publicaciones para mostrar experiencias reales, ahora en una caja amplia y alineada con la estética DataUno.</p>
            </div>
            <div class="rating-box">
                <strong>★★★★★</strong>
                <span>Experiencias conectadas desde el widget original.</span>
                <a href="https://widget.tagembed.com/69661?view" target="_blank" rel="noopener">Ver muro completo</a>
            </div>
        </div>

        <div class="reviews-widget reveal">
            <div class="widget-frame wide-widget-frame">
                <div class="tagembed-container">
                    <div class="tagembed-socialwall" data-wall-id="69661" view-url="https://widget.tagembed.com/69661?view"></div>
                    <script src="https://widget.tagembed.com/embed.min.js" type="text/javascript"></script>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section contact-section" id="contacto">
    <div class="container contact-head reveal">
        <span class="eyebrow">Contacto</span>
        <h2>Agenda tu revisión técnica.</h2>
        <p>Estamos en Victoria, Araucanía. Completa el formulario o escríbenos directo por WhatsApp para revisar tu computador, notebook o proyecto digital.</p>
    </div>

    <div class="container contact-duo-grid">
        <div class="map-card reveal" aria-label="Mapa DataUno">
            <div class="map-frame">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6267.784741030948!2d-72.33852475320849!3d-38.235604768853804!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x966b152871343fed%3A0x4581e5dae4b67f6d!2sDataUno!5e0!3m2!1ses-419!2scl!4v1666112173525!5m2!1ses-419!2scl" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="map-caption">
                <strong>Atención local DataUno</strong>
                <span>Victoria, Araucanía · Servicio técnico y soluciones digitales.</span>
                <a href="<?= $contacto['whatsapp_link']; ?>?text=Hola%20DataUno,%20quiero%20agendar%20una%20revisi%C3%B3n" target="_blank" rel="noopener">Agendar por WhatsApp</a>
            </div>
        </div>

        <form class="contact-form reveal" action="https://formsubmit.co/<?= $contacto['email']; ?>" method="POST">
            <label>Nombre
                <input type="text" name="nombre" placeholder="Tu nombre" required>
            </label>
            <label>Teléfono
                <input type="tel" name="telefono" placeholder="+56 9 ...">
            </label>
            <label>Tipo de servicio
                <select name="servicio">
                    <option>Servicio técnico</option>
                    <option>Upgrade / cambio de piezas</option>
                    <option>Desarrollo web</option>
                    <option>Soporte empresa</option>
                </select>
            </label>
            <label>Mensaje
                <textarea name="mensaje" placeholder="Cuéntanos el problema o proyecto"></textarea>
            </label>
            <button class="btn btn-primary" type="submit">Enviar solicitud</button>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>