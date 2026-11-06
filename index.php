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
    <title>Bodega Dona Rosa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        table { border-collapse: collapse; width: 100%; max-width: 700px; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
        th { background: #f2f2f2; }
        .nav { margin-bottom: 20px; }
        a { color: #0b57d0; text-decoration: none; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="venta.php">Registrar venta</a>
    </div>

    <h1>Productos en stock</h1>

    <?php if (empty($productos)): ?>
        <p>No hay productos registrados.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= (int) $producto['id']; ?></td>
                        <td><?= htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>S/ <?= number_format((float) $producto['precio'], 2, '.', ''); ?></td>
                        <td><?= (int) $producto['stock']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
