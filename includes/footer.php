<?php require_once __DIR__ . '/data.php'; $base_path = $base_path ?? ''; ?>
</main>

<a class="whatsapp-float" href="<?= $contacto['whatsapp_link']; ?>?text=Hola%20DataUno,%20necesito%20ayuda%20con%20mi%20equipo" target="_blank" rel="noopener" aria-label="Contactar por WhatsApp">
    <span>☏</span>
</a>

<footer class="site-footer datauno-footer-pro">
    <div class="container footer-showcase">
        <div class="footer-identity">
            <a class="footer-brand footer-brand-clean" href="<?= $base_path; ?>index.php">DataUno</a>
            <h2>DataUno</h2>
            <p>Servicio técnico de computadores · Productos TI · Soluciones digitales · Victoria, Chile</p>
            <strong>Desarrollado con ⚡ por Orfheres</strong>
        </div>

        <div class="footer-social-zone">
            <div class="footer-socials footer-socials-pro" aria-label="Redes sociales de DataUno">
                <a class="social-bubble social-facebook" href="<?= $contacto['facebook']; ?>" target="_blank" rel="noopener" aria-label="Facebook"></a>
                <a class="social-bubble social-instagram" href="<?= $contacto['instagram']; ?>" target="_blank" rel="noopener" aria-label="Instagram"></a>
                <a class="social-bubble social-whatsapp" href="<?= $contacto['whatsapp_link']; ?>" target="_blank" rel="noopener" aria-label="WhatsApp"></a>
            </div>
            <nav class="footer-mini-links" aria-label="Accesos rápidos">
                <a href="<?= $base_path; ?>catalogo.php">Catálogo</a>
                <a href="<?= $base_path; ?>desarrollo.php">Desarrollo</a>
                <a href="<?= $base_path; ?>index.php#opiniones">Opiniones</a>
            </nav>
        </div>
    </div>

    <div class="footer-bottom container footer-bottom-pro">
        <span>© <?= date('Y'); ?> DataUno. Todos los derechos reservados.</span>
        <a href="<?= $base_path; ?>admin/index.php">Admin</a>
    </div>
</footer>

<script src="<?= $base_path; ?>assets/js/main.js"></script>
</body>
</html>
