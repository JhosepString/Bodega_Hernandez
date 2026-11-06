<?php

class ServicioVenta
{
    public static function validarCliente(string $cliente): bool
    {
        return trim($cliente) !== '';
    }

    public static function validarProductoId(int $productoId): bool
    {
        return $productoId > 0;
    }

    public static function validarCantidad($cantidad): bool
    {
        return is_numeric($cantidad)
            && floor((float) $cantidad) == (float) $cantidad
            && (int) $cantidad > 0;
    }

    public static function hayStock(int $cantidad, int $stock): bool
    {
        return $cantidad <= $stock;
    }

    public static function calcularSubtotal(array $items): float
    {
        $subtotal = 0.0;

        foreach ($items as $item) {
            $precio = (float) ($item['precio'] ?? $item['precio_unitario'] ?? 0);
            $cantidad = (int) ($item['cantidad'] ?? 0);
            $subtotal += $precio * $cantidad;
        }

        return round($subtotal, 2);
    }

    public static function calcularDescuento(float $subtotal): float
    {
        if ($subtotal > 100.00) {
            return round($subtotal * 0.05, 2);
        }

        return 0.0;
    }

    public static function calcularTotal(float $subtotal): float
    {
        $descuento = self::calcularDescuento($subtotal);
        return round($subtotal - $descuento, 2);
    }
}
