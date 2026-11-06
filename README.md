# Bodega

Aplicacion PHP 8 sin framework para registrar ventas de una bodega con stock y descuentos.

## Ejecutar en local con Laragon

1. Asegurate de tener MySQL 8.4 en Laragon y que el puerto web sea 81.
2. Inicia Apache y MySQL desde Laragon.
3. Crea la base bodega y ejecuta el script de estructura y datos en `sql/bodega.sql`.
4. Desde la raiz del proyecto, abre la aplicacion en http://localhost:81/bodega/.
5. La base usa los valores locales por defecto: host 127.0.0.1, puerto 3307, usuario root, sin clave, base bodega.

## Pruebas

Ejecuta las pruebas con:

```bash
php phpunit.phar --testsuite unitarias
php phpunit.phar tests/integracion
```

## Despliegue en InfinityFree

1. Crea una cuenta en InfinityFree y crea una base MySQL desde el panel.
2. En phpMyAdmin, importa `sql/bodega_produccion.sql` en la base creada.
3. Subi la aplicacion a la carpeta `htdocs` del hosting por File Manager. No subas `.git`, `tests/`, `phpunit.phar`, `config/env.php`, `config/env_test.php` ni la carpeta `sql/`.
4. En el servidor, crea `config/env.php` con los datos reales del panel de InfinityFree.
5. Verifica que la URL publica del proyecto quede disponible.

URL publica: (pendiente)
