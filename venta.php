<?php
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/src/RepositorioMySQL.php';

$repo = new RepositorioMySQL(conectar());
$productos = $repo->listarProductos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar venta</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        form { max-width: 760px; }
        .linea { display: flex; gap: 12px; margin-bottom: 12px; align-items: center; }
        .linea select, .linea input { padding: 8px; }
        .linea select { min-width: 220px; }
        .linea input { width: 120px; }
        button { padding: 8px 12px; cursor: pointer; }
        .nav { margin-bottom: 20px; }
        a { color: #0b57d0; text-decoration: none; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="index.php">Volver al catalogo</a>
    </div>

    <h1>Registrar venta</h1>

    <form action="guardar_venta.php" method="post">
        <p>
            <label for="cliente">Cliente:</label><br>
            <input type="text" id="cliente" name="cliente" required placeholder="Nombre del cliente">
        </p>

        <div id="lineas">
            <div class="linea">
                <select name="productos[]" required>
                    <option value="">Seleccione producto</option>
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?= (int) $producto['id']; ?>">
                            <?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?> (Stock: <?= (int) $producto['stock']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="cantidades[]" min="1" step="1" placeholder="Cantidad" required>
            </div>
        </div>

        <p>
            <button type="button" id="agregarLinea">Agregar otra linea</button>
            <button type="submit">Guardar venta</button>
        </p>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const contenedor = document.getElementById('lineas');
            const boton = document.getElementById('agregarLinea');
            const plantilla = `
                <div class="linea">
                    <select name="productos[]" required>
                        <option value="">Seleccione producto</option>
                        <?php foreach ($productos as $producto): ?>
                            <option value="<?= (int) $producto['id']; ?>"><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?> (Stock: <?= (int) $producto['stock']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="cantidades[]" min="1" step="1" placeholder="Cantidad" required>
                </div>
            `;

            boton.addEventListener('click', function () {
                contenedor.insertAdjacentHTML('beforeend', plantilla);
            });
        });
    </script>
</body>
</html>
