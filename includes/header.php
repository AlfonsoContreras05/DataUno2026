<?php
require_once __DIR__ . '/data.php';
$page_title = $page_title ?? 'Servicio técnico de computadores en Victoria';
$page_description = $page_description ?? 'DataUno ofrece servicio técnico de computadores y notebooks en Victoria, Araucanía: diagnóstico, mantención, formateo, reparación, upgrades, soporte y soluciones digitales.';
$page_keywords = $page_keywords ?? 'DataUno, servicio técnico computadores Victoria, reparación notebooks Victoria, mantención computadores Araucanía, formateo PC, upgrades SSD RAM';
$active_page = $active_page ?? 'inicio';
$script_name = $_SERVER['SCRIPT_NAME'] ?? '';
$base_path = strpos($script_name, '/admin/') !== false ? '../' : '';
$extra_css = $extra_css ?? [];
$canonicalPath = $script_name ? basename($script_name) : 'index.php';
if ($canonicalPath === 'index.php') {
    $canonicalUrl = 'https://www.datauno.cl/';
} elseif ($canonicalPath === 'producto.php' && !empty($_GET['id'])) {
    $canonicalUrl = 'https://www.datauno.cl/producto.php?id=' . urlencode((string) $_GET['id']);
} else {
    $canonicalUrl = 'https://www.datauno.cl/' . $canonicalPath;
}
$seo_title = trim($page_title . ' | DataUno');
?>
<!DOCTYPE html>
<html lang="es-CL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?= htmlspecialchars($page_keywords); ?>">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="author" content="DataUno">
    <meta name="geo.region" content="CL-AR">
    <meta name="geo.placename" content="Victoria, Araucanía, Chile">
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl); ?>">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="es_CL">
    <meta property="og:site_name" content="DataUno">
    <meta property="og:title" content="<?= htmlspecialchars($seo_title); ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_description); ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl); ?>">
    <meta property="og:image" content="https://www.datauno.cl/assets/img/datauno-brand.png">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($seo_title); ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($page_description); ?>">
    <meta name="twitter:image" content="https://www.datauno.cl/assets/img/datauno-brand.png">

    <title><?= htmlspecialchars($seo_title); ?></title>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "DataUno",
      "alternateName": "DataUno Soluciones Informáticas",
      "url": "https://www.datauno.cl/",
      "image": "https://www.datauno.cl/assets/img/datauno-brand.png",
      "description": "Servicio técnico de computadores, notebooks, mantención, reparación, upgrades y soluciones digitales en Victoria, Araucanía.",
      "telephone": "+56994392133",
      "email": "victordiaz.pc@gmail.com",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Chorrillos 1012",
        "addressLocality": "Victoria",
        "addressRegion": "Araucanía",
        "addressCountry": "CL"
      },
      "areaServed": ["Victoria", "Araucanía", "Chile"],
      "sameAs": []
    }
    </script>

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