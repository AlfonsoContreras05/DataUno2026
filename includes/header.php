<?php
require_once __DIR__ . '/data.php';
$page_title = $page_title ?? 'DataUno';
$active_page = $active_page ?? 'inicio';
$script_name = $_SERVER['SCRIPT_NAME'] ?? '';
$base_path = strpos($script_name, '/admin/') !== false ? '../' : '';
$extra_css = $extra_css ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DataUno: servicio técnico de computadores en Victoria, Araucanía. Mantención, formateo, reparación, upgrades y soluciones digitales.">
    <title><?= htmlspecialchars($page_title) ?> | DataUno</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base_path; ?>assets/css/styles.css">
    <?php foreach ($extra_css as $css_file): ?>
        <link rel="stylesheet" href="<?= $base_path . htmlspecialchars($css_file); ?>">
    <?php endforeach; ?>
</head>
<body>
<header class="site-header" id="top">
    <nav class="navbar container" aria-label="Navegación principal">
        <a class="brand brand-full" href="<?= $base_path; ?>index.php" aria-label="Volver al inicio de DataUno">
            <img src="<?= $base_path; ?>assets/img/datauno-brand.png" alt="DataUno Soluciones Informáticas" loading="lazy">
            <span class="brand-subline">Servicio técnico & software</span>
        </a>

        <button class="nav-toggle" type="button" aria-label="Abrir menú" aria-expanded="false">☰</button>

        <div class="nav-menu" data-nav-menu>
            <a class="<?= $active_page === 'inicio' ? 'active' : '' ?>" href="<?= $base_path; ?>index.php">Inicio</a>
            <a class="<?= $active_page === 'catalogo' ? 'active' : '' ?>" href="<?= $base_path; ?>catalogo.php">Catálogo</a>
            <a class="<?= $active_page === 'desarrollo' ? 'active' : '' ?>" href="<?= $base_path; ?>desarrollo.php">Desarrollo</a>
            <a class="nav-cta" href="<?= $contacto['whatsapp_link']; ?>?text=Hola%20DataUno,%20quiero%20cotizar%20un%20servicio" target="_blank" rel="noopener">WhatsApp</a>
        </div>
    </nav>
</header>
<main>
