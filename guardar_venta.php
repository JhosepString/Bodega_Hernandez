<?php
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/src/ServicioVenta.php';
require_once __DIR__ . '/src/RepositorioMySQL.php';

$cliente = trim((string) ($_POST['cliente'] ?? ''));
$productosPost = $_POST['productos'] ?? [];
$cantidadesPost = $_POST['cantidades'] ?? [];

$repo = new RepositorioMySQL(conectar());

try {
    if (!ServicioVenta::validarCliente($cliente)) {
        throw new InvalidArgumentException('El cliente es obligatorio.');
    }

    if (!is_array($productosPost) || !is_array($cantidadesPost) || empty($productosPost)) {
        throw new InvalidArgumentException('Debe seleccionar al menos un producto.');
    }

    $items = [];
    foreach ($productosPost as $index => $productoId) {
        if (!isset($cantidadesPost[$index])) {
            continue;
        }

        $cantidad = (int) $cantidadesPost[$index];
        $productoId = (int) $productoId;

        if (!ServicioVenta::validarProductoId($productoId) || !ServicioVenta::validarCantidad($cantidad)) {
            throw new InvalidArgumentException('Cada cantidad debe ser un entero mayor que cero.');
        }

        $producto = $repo->obtenerProducto($productoId);
        if (!$producto) {
            throw new RuntimeException('El producto seleccionado no existe.');
        }

        if (!ServicioVenta::hayStock($cantidad, (int) $producto['stock'])) {
            throw new RuntimeException('No hay stock suficiente para ' . $producto['nombre'] . '.');
        }

        $items[] = [
            'producto_id' => $productoId,
            'cantidad' => $cantidad,
            'precio' => (float) $producto['precio'],
            'nombre' => $producto['nombre'],
        ];
    }

    if (empty($items)) {
        throw new InvalidArgumentException('Debe ingresar al menos una linea de venta valida.');
    }

    $subtotal = ServicioVenta::calcularSubtotal($items);
    $descuento = ServicioVenta::calcularDescuento($subtotal);
    $total = ServicioVenta::calcularTotal($subtotal);

    $ventaId = $repo->registrarVenta($cliente, $items);

    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Venta registrada</title></head><body>';
    echo '<h1>Venta registrada correctamente</h1>';
    echo '<p>Cliente: ' . htmlspecialchars($cliente, ENT_QUOTES, 'UTF-8') . '</p>';
    echo '<p>Subtotal: S/ ' . number_format($subtotal, 2, '.', '') . '</p>';
    echo '<p>Descuento: S/ ' . number_format($descuento, 2, '.', '') . '</p>';
    echo '<p>Total: S/ ' . number_format($total, 2, '.', '') . '</p>';
    echo '<p>ID de venta: ' . (int) $ventaId . '</p>';
    echo '<p><a href="index.php">Volver al catalogo</a> | <a href="venta.php">Nueva venta</a></p>';
    echo '</body></html>';
} catch (Throwable $e) {
    error_log('Error al guardar la venta: ' . $e->getMessage());

    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Error en venta</title></head><body>';
    echo '<h1>Error al guardar la venta</h1>';
    echo '<p>No se pudo guardar la venta. Por favor, intente nuevamente.</p>';
    echo '<p><a href="venta.php">Intentar nuevamente</a></p>';
    echo '</body></html>';
}
