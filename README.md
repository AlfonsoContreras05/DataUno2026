# DataUno ProMax V11

Versión con landing, catálogo de productos, ficha individual, carrito por WhatsApp y panel admin conectado a MySQL/cPanel.

## Producción

- Dominio: `datauno.cl`
- Deploy por cPanel Git Version Control
- Ruta pública: `/home/datauno1/public_html/`
- Config privada esperada: `/home/datauno1/datauno_private/config.php`

## Config privada

Crear fuera de `public_html`:

```php
<?php
return [
    'db_host' => 'localhost',
    'db_name' => 'datauno1_datauno_db',
    'db_user' => 'datauno1_datauno_admin',
    'db_pass' => 'CLAVE_PRIVADA',
];
```

Este archivo no debe subirse a GitHub.

## Importar base de datos

En phpMyAdmin seleccionar `datauno1_datauno_db` e importar:

```text
database/schema.sql
```

## Admin

Ruta:

```text
/admin/login.php
```

Usuario inicial:

```text
admin
```

La contraseña inicial corresponde a la clave temporal acordada para instalación. Cambiarla después de validar el panel.

## Flujo de deploy

```bash
git add .
git commit -m "Mensaje del cambio"
git push
```

Luego en cPanel:

```text
Git Version Control → DataUno2026 → Pull or Deploy → Update from Remote → Deploy HEAD Commit
```

## Archivos clave V11

```text
includes/db.php
includes/auth.php
includes/product-repository.php
admin/login.php
admin/logout.php
admin/index.php
admin/productos.php
admin/producto-form.php
admin/categorias.php
database/schema.sql
.htaccess
```
