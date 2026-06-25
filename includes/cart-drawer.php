<?php require_once __DIR__ . '/data.php'; ?>
<div class="cart-overlay" data-cart-overlay hidden></div>
<aside class="cart-drawer" data-cart-drawer aria-hidden="true" data-whatsapp="<?= $contacto['whatsapp_link']; ?>">
    <div class="cart-header">
        <div>
            <span class="eyebrow">Cotización</span>
            <h2>Carrito DataUno</h2>
        </div>
        <button class="cart-close" type="button" data-cart-close aria-label="Cerrar carrito">×</button>
    </div>

    <div class="cart-empty" data-cart-empty>
        <strong>Tu carrito está vacío.</strong>
        <p>Agrega productos para armar una cotización y enviarla por WhatsApp.</p>
    </div>

    <div class="cart-items" data-cart-items></div>

    <div class="cart-footer-panel">
        <p>La cotización final depende de compatibilidad, stock e instalación si corresponde.</p>
        <a class="btn btn-primary cart-send disabled" href="#" target="_blank" rel="noopener" data-cart-send>Enviar por WhatsApp</a>
        <button class="cart-clear" type="button" data-cart-clear>Vaciar carrito</button>
    </div>
</aside>
