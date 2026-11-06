<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../src/RepositorioMySQL.php';
require_once __DIR__ . '/../../src/ServicioVenta.php';

class RegistrarVentaTest extends TestCase
{
    private PDO $pdo;
    private RepositorioMySQL $repositorio;

    protected function setUp(): void
    {
        $rootPdo = new PDO(
            'mysql:host=127.0.0.1;port=3307;charset=utf8mb4',
            'root',
            '',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
        $rootPdo->exec('CREATE DATABASE IF NOT EXISTS bodega_test');

        $this->pdo = conectar(__DIR__ . '/../../config/env_test.php');
        $database = $this->pdo->query('SELECT DATABASE()')->fetchColumn();
        $this->assertSame('bodega_test', $database);

        $sql = file_get_contents(__DIR__ . '/../../sql/bodega_test.sql');
        if ($sql !== false && trim($sql) !== '') {
            $this->pdo->exec($sql);
        }

        $this->pdo->exec('DELETE FROM detalle_venta');
        $this->pdo->exec('DELETE FROM venta');
        $this->pdo->exec('DELETE FROM producto');

        $this->pdo->exec("INSERT INTO producto (id, nombre, precio, stock) VALUES (1, 'arroz', 4.80, 10), (2, 'atun', 6.50, 0)");

        $this->repositorio = new RepositorioMySQL($this->pdo);
    }

    protected function tearDown(): void
    {
        $this->pdo->exec('DELETE FROM detalle_venta');
        $this->pdo->exec('DELETE FROM venta');
        $this->pdo->exec('DELETE FROM producto');
    }

    public function testRegistrarVentaReduceStockYGeneraDetalle(): void
    {
        $ventaId = $this->repositorio->registrarVenta('Cliente prueba', [
            ['producto_id' => 1, 'cantidad' => 3],
        ]);

        $this->assertNotSame(0, $ventaId);

        $stock = (int) $this->pdo->query("SELECT stock FROM producto WHERE id = 1")->fetchColumn();
        $this->assertSame(7, $stock);

        $ventas = (int) $this->pdo->query('SELECT COUNT(*) FROM venta')->fetchColumn();
        $detalles = (int) $this->pdo->query('SELECT COUNT(*) FROM detalle_venta')->fetchColumn();

        $this->assertSame(1, $ventas);
        $this->assertSame(1, $detalles);
    }

    public function testRegistrarVentaConProductoSinStockLanzaExcepcionYNoInsertaNada(): void
    {
        try {
            $this->repositorio->registrarVenta('Cliente prueba', [
                ['producto_id' => 2, 'cantidad' => 1],
            ]);
            $this->fail('Se esperaba que la venta falle por stock insuficiente.');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('stock', strtolower($e->getMessage()));
        }

        $stock = (int) $this->pdo->query("SELECT stock FROM producto WHERE id = 2")->fetchColumn();
        $this->assertSame(0, $stock);

        $ventas = (int) $this->pdo->query('SELECT COUNT(*) FROM venta')->fetchColumn();
        $detalles = (int) $this->pdo->query('SELECT COUNT(*) FROM detalle_venta')->fetchColumn();

        $this->assertSame(0, $ventas);
        $this->assertSame(0, $detalles);
    }

    public function testVentaMixtaConRollbackSiHayUnaLineaSinStock(): void
    {
        try {
            $this->repositorio->registrarVenta('Cliente prueba', [
                ['producto_id' => 1, 'cantidad' => 2],
                ['producto_id' => 2, 'cantidad' => 1],
            ]);
            $this->fail('Se esperaba que la venta falle por stock insuficiente en la segunda linea.');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('stock', strtolower($e->getMessage()));
        }

        $stockProducto1 = (int) $this->pdo->query("SELECT stock FROM producto WHERE id = 1")->fetchColumn();
        $this->assertSame(10, $stockProducto1);

        $ventas = (int) $this->pdo->query('SELECT COUNT(*) FROM venta')->fetchColumn();
        $this->assertSame(0, $ventas);
    }
}
