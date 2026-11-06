<?php

require_once __DIR__ . '/../config/conexion.php';

class RepositorioMySQL
{
    public function __construct(private PDO $conexion)
    {
    }

    public function listarProductos(): array
    {
        $sql = 'SELECT * FROM producto ORDER BY id ASC';
        $stmt = $this->conexion->query($sql);
        return $stmt->fetchAll();
    }

    public function obtenerProducto($id): ?array
    {
        $sql = 'SELECT * FROM producto WHERE id = :id LIMIT 1';
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['id' => (int) $id]);
        $producto = $stmt->fetch();

        return $producto ?: null;
    }

    public function registrarVenta($cliente, $items): int
    {
        $cliente = trim((string) $cliente);
        if (!ServicioVenta::validarCliente($cliente)) {
            throw new InvalidArgumentException('El nombre del cliente es obligatorio.');
        }

        if (!is_array($items) || $items === []) {
            throw new InvalidArgumentException('Debe registrar al menos una linea de venta.');
        }

        $subtotal = 0.0;
        foreach ($items as $item) {
            $productoId = (int) ($item['producto_id'] ?? $item['id'] ?? 0);
            $cantidad = (int) ($item['cantidad'] ?? 0);

            if (!ServicioVenta::validarProductoId($productoId) || !ServicioVenta::validarCantidad($cantidad)) {
                throw new InvalidArgumentException('La cantidad de cada producto debe ser un entero mayor que cero.');
            }

            $producto = $this->obtenerProducto($productoId);
            if (!$producto) {
                throw new RuntimeException('El producto seleccionado no existe.');
            }

            if (!ServicioVenta::hayStock($cantidad, (int) $producto['stock'])) {
                throw new RuntimeException('No hay stock suficiente para el producto: ' . $producto['nombre']);
            }

            $subtotal += (float) $producto['precio'] * $cantidad;
        }

        $subtotal = round($subtotal, 2);
        $descuento = ServicioVenta::calcularDescuento($subtotal);
        $total = ServicioVenta::calcularTotal($subtotal);

        $this->conexion->beginTransaction();

        try {
            $sqlVenta = 'INSERT INTO venta (subtotal, descuento, total, fecha) VALUES (:subtotal, :descuento, :total, NOW())';
            $stmtVenta = $this->conexion->prepare($sqlVenta);
            $stmtVenta->execute([
                'subtotal' => number_format($subtotal, 2, '.', ''),
                'descuento' => number_format($descuento, 2, '.', ''),
                'total' => number_format($total, 2, '.', ''),
            ]);

            $ventaId = (int) $this->conexion->lastInsertId();

            foreach ($items as $item) {
                $productoId = (int) ($item['producto_id'] ?? $item['id'] ?? 0);
                $cantidad = (int) ($item['cantidad'] ?? 0);
                $producto = $this->obtenerProducto($productoId);

                if (!$producto) {
                    throw new RuntimeException('El producto no existe al momento de guardar la venta.');
                }

                $stockActual = (int) $producto['stock'];
                if ($cantidad > $stockActual) {
                    throw new RuntimeException('La venta excede el stock disponible.');
                }

                $sqlDetalle = 'INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio_unitario) VALUES (:venta_id, :producto_id, :cantidad, :precio_unitario)';
                $stmtDetalle = $this->conexion->prepare($sqlDetalle);
                $stmtDetalle->execute([
                    'venta_id' => $ventaId,
                    'producto_id' => $productoId,
                    'cantidad' => $cantidad,
                    'precio_unitario' => number_format((float) $producto['precio'], 2, '.', ''),
                ]);

                $sqlUpdateStock = 'UPDATE producto SET stock = stock - :cantidad WHERE id = :id';
                $stmtUpdate = $this->conexion->prepare($sqlUpdateStock);
                $stmtUpdate->execute([
                    'cantidad' => $cantidad,
                    'id' => $productoId,
                ]);
            }

            $this->conexion->commit();
            return $ventaId;
        } catch (Throwable $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }
}
