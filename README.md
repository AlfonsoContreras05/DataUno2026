# DataUno Pro Max - V1 Visual

Primera versión de renovación visual para DataUno.

## Qué incluye

- Landing principal enfocada en servicio técnico de computadores.
- Catálogo visual con filtros en JavaScript.
- Vista separada para desarrollo de software.
- Ruta admin reservada para fase 2.
- Esquema SQL inicial para futura base de datos.
- Archivo `.cpanel.yml` inicial para despliegue con Git en cPanel.

## Datos oficiales usados

- WhatsApp: +56 9 9439 2133
- Correo: victordiaz.pc@gmail.com
- Dirección: Chorrillos 1012, Victoria, Araucanía, Chile

## Cómo probar localmente

Con PHP instalado, abrir terminal en la carpeta del proyecto y ejecutar:

```bash
php -S localhost:8000
```

Luego abrir:

```text
http://localhost:8000
```

## Antes de subir a cPanel

Editar `.cpanel.yml` y cambiar:

```text
/home/USUARIO_CPANEL/public_html/
```

por la ruta real del hosting.

## Nota

Esta versión todavía no conecta el catálogo a MySQL. El catálogo carga desde `includes/data.php` para revisar diseño y contenido primero.

## V2 - Ajuste de identidad DataUno

Esta iteración recupera el protagonismo visual del logo original `DataUno Soluciones Informáticas` y transforma el panel lateral del hero en una tarjeta de marca inspirada en la versión anterior:

- Logo completo en navegación y footer.
- Tarjeta lateral con concepto `Diagnóstico + Solución + Evolución`.
- Servicios destacados dentro del panel visual.
- Mantiene estructura moderna de la V1.

## V3 - Identidad DataUno reforzada

Esta iteración recupera el ADN visual del sitio antiguo:

- Fondo tecnológico oscuro con imagen de placa y patrón animado.
- Tarjetas tipo glass con borde azul/celeste.
- Secciones oscuras continuas para evitar cortes blancos bruscos.
- Hero con tarjeta DataUno protagonista.
- Más presencia del logo `DataUno Soluciones Informáticas`.
- Microanimaciones: scanline, grid animado, tarjetas con brillo y flotación suave.
- Nueva estructura de inicio y desarrollo inspirada en la panorámica antigua.
- Formulario de contacto visual conectado a FormSubmit.

Archivos más modificados en V3:

- `index.php`
- `desarrollo.php`
- `assets/css/styles.css`


## V9 - Catálogo con detalle y admin estilizado

Cambios principales:

- `catalogo.php` ahora enlaza cada producto a una ficha individual.
- Nuevo archivo `producto.php` para vista exclusiva de producto.
- Vista de detalle con:
  - imagen grande,
  - botón agregar al carrito,
  - cotización directa por WhatsApp,
  - ficha técnica comercial,
  - productos sugeridos.
- Nuevo include `includes/cart-drawer.php` para reutilizar el carrito en catálogo y detalle.
- Corrección de rutas en `includes/header.php` y `includes/footer.php` para que el admin cargue CSS/JS correctamente desde `/admin`.
- Admin visual renovado con `assets/css/admin.css`.

Archivos tocados:

- `catalogo.php`
- `producto.php`
- `admin/index.php`
- `includes/header.php`
- `includes/footer.php`
- `includes/cart-drawer.php`
- `assets/css/styles.css`
- `assets/css/admin.css`
