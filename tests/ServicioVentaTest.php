<?php

require_once __DIR__ . '/../src/ServicioVenta.php';

use PHPUnit\Framework\TestCase;

final class ServicioVentaTest extends TestCase
{
    public function testAceptaCantidadIgualAlStock(): void
    {
        $this->assertSame(true, ServicioVenta::hayStock(10, 10));
    }

    public function testAceptaCantidadMenorAlStock(): void
    {
        $this->assertSame(true, ServicioVenta::hayStock(9, 10));
    }

    public function testRechazaCantidadMayorAlStock(): void
    {
        $this->assertSame(false, ServicioVenta::hayStock(11, 10));
    }

    public function testRechazaCantidadCuandoStockEsCero(): void
    {
        $this->assertSame(false, ServicioVenta::hayStock(1, 0));
    }

    public function testDescuentoCeroCuandoSubtotalEsExactamente100(): void
    {
        $this->assertEqualsWithDelta(0.0, ServicioVenta::calcularDescuento(100.00), 0.001);
        $this->assertEqualsWithDelta(100.00, ServicioVenta::calcularTotal(100.00), 0.001);
    }

    public function testDescuentoDelCincoPorCientoCuandoSubtotalEs10001(): void
    {
        $this->assertEqualsWithDelta(5.00, ServicioVenta::calcularDescuento(100.01), 0.001);
        $this->assertEqualsWithDelta(95.01, ServicioVenta::calcularTotal(100.01), 0.001);
    }

    public function testDescuentoCeroCuandoSubtotalEs99_99(): void
    {
        $this->assertEqualsWithDelta(0.0, ServicioVenta::calcularDescuento(99.99), 0.001);
        $this->assertEqualsWithDelta(99.99, ServicioVenta::calcularTotal(99.99), 0.001);
    }

    public function testRechazaCantidadCero(): void
    {
        $this->assertSame(false, ServicioVenta::validarCantidad("0"));
        $this->assertSame(false, ServicioVenta::validarCantidad(""));
        $this->assertSame(false, ServicioVenta::validarCantidad("abc"));
    }

    public function testRechazaCantidadNegativa(): void
    {
        $this->assertSame(false, ServicioVenta::validarCantidad("-1"));
    }

    public function testAceptaCantidadUno(): void
    {
        $this->assertSame(true, ServicioVenta::validarCantidad("1"));
    }

    public function testRechazaCantidadDecimal(): void
    {
        $this->assertSame(false, ServicioVenta::validarCantidad("1.5"));
        $this->assertSame(false, ServicioVenta::validarCantidad("1,5"));
        $this->assertSame(false, ServicioVenta::validarCantidad("1e3"));
    }

    public function testRechazaCantidadCuandoEsMayorAlStockDisponible(): void
    {
        $this->assertSame(false, ServicioVenta::hayStock(6, 5));
    }
}
