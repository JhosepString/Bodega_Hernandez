# Plan de pruebas

## 1. Objetivo y alcance

### Objetivo
El objetivo de este plan es verificar que el sistema de registro de ventas de la bodega cumple los requisitos funcionales descritos en `requirements.md` y la arquitectura propuesta en `design.md`, con especial foco en las reglas de negocio R1 a R4.

### Alcance probado
Se probaran los siguientes módulos y comportamientos:
- `ServicioVenta`: validacion de cantidad, validacion de stock, calculo de subtotal, descuento por volumen y total.
- `RepositorioMySQL`: acceso a datos, consulta de productos, guardado de venta, reduccion de stock y transaccion de base de datos.
- `venta.php` y `guardar_venta.php`: flujo de ingreso de una venta desde la interfaz web, validacion del formulario y confirmacion del resultado.

### Alcance no probado
No se incluyen en este plan pruebas de:
- autenticacion o autorizacion,
- reportes ni facturacion electronica,
- trazabilidad avanzada ni auditoria,
- integracion con otros sistemas externos,
- carga, rendimiento ni seguridad de red.

## 2. Matriz de reglas de negocio

| Regla | Nivel de prueba | Tecnica | Datos de prueba | Criterio de aceptacion |
|---|---|---|---|---|
| R1: No vender una cantidad mayor al stock disponible | Unitaria y integracion | Valores limite y particion de equivalencia | cantidad = stock, cantidad < stock, cantidad > stock, stock = 0 | La venta se acepta solo cuando cantidad <= stock. Si cantidad > stock, se rechaza y no se modifica el stock. |
| R2: Descuento del 5 % si el subtotal supera S/ 100.00 | Unitaria | Tabla de decision y valores limite | subtotal 99.99, 100.00, 100.01, 250.00 | Si subtotal > 100.00, se aplica 5% de descuento; si subtotal <= 100.00, no se aplica descuento. |
| R3: La cantidad debe ser un entero mayor que cero | Unitaria y E2E | Particion de equivalencia y valores limite | 0, -1, 1, 2, 2.5, "3" | Solo se aceptan cantidades enteras mayores que cero. Cualquier cantidad invalida produce error y no modifica stock. |
| R4: Stock se descuenta en la misma transaccion | Integracion y E2E | Tabla de decision y valores limite | venta valida, una linea con stock suficiente, venta invalida por stock insuficiente, varios productos | Si la venta se confirma, todas las reducciones de stock se guardan en la misma transaccion. Si falla, no se persiste ningun cambio. |

## 3. Niveles de prueba que se ejecutaran hoy

### 3.1 Pruebas unitarias con PHPUnit sobre ServicioVenta
Se ejecutaran pruebas unitarias sobre la clase `ServicioVenta` para verificar:
- validacion de cantidad entera positiva,
- comparacion de stock disponible,
- calculo correcto del subtotal,
- descuento por volumen,
- total final.

### 3.2 Pruebas de integracion sobre RepositorioMySQL con base bodega_test
Se ejecutaran pruebas de integracion con MySQL sobre la base `bodega_test` para verificar:
- lectura del catalogo de productos,
- consulta de un producto por id,
- registro de venta con transaccion,
- reduccion del stock al confirmar.

### 3.3 Pruebas E2E manuales sobre venta.php
Se ejecutara un flujo manual en la interfaz web para validar:
- ingresar cliente,
- seleccionar producto,
- introducir cantidad,
- agregar varias lineas,
- confirmar venta,
- observar mensaje de exito o error.

## 4. Criterio de salida

La version se considera lista para desplegar cuando se cumplen todas las condiciones siguientes:
- Las pruebas unitarias de `ServicioVenta` pasan en verde.
- Las pruebas de integracion con `RepositorioMySQL` y `bodega_test` pasan sin errores de transaccion ni stock.
- El flujo E2E de `venta.php` confirma que la venta se registra correctamente y los errores de validacion se muestran de forma legible.
- Se cumple la regla R1 a la R4 en los escenarios de validacion y en la base de datos.
- No existen errores funcionales observados en el flujo principal del registro de productos, consulta del catalogo y registro de ventas.

El despliegue solo procede si la evidencia de estas pruebas confirma que el sistema cumple los requisitos establecidos en `requirements.md` y la arquitectura especificada en `design.md`.
