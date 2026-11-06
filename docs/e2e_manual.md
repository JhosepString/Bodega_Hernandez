# Prueba manual E2E: registrar venta

Objetivo: validar el flujo completo de venta en `http://localhost:81/bodega/` usando los precios del script SQL de la base `bodega`.

Base de datos inicial (segun `sql/bodega.sql`): arroz 18, aceite 25, leche 30, azucar 12, atun 0, gaseosa 40.

| Paso | Accion exacta en pantalla | Dato a ingresar | Resultado esperado visible | Resultado |
|---|---|---|---|---|
| 1 | Abrir `http://localhost:81/bodega/` y entrar a `Registrar venta` desde el catalogo | - | Se muestra el formulario con campos `Cliente`, `Producto` y `Cantidad` |  |
| 2 | En el formulario, completar cliente con nombre valido | `Cliente: Ana` | El campo queda cargado y el formulario permite continuar |  |
| 3 | Escenario A - Venta valida con dos lineas y subtotal > 100 | `Linea 1: aceite, cantidad 5` y `Linea 2: arroz, cantidad 10` | Se acepta la venta y se muestra un mensaje de exito con subtotal, descuento y total |  |
| 4 | Verificar mensaje del descuento en escenario A | `Subtotal: 60.00 + 48.00 = 108.00` | El mensaje debe mostrar descuento `5%` porque `108.00 > 100.00` y total `102.60` |  |
| 5 | Verificar reduccion de stock en index.php | volver a `index.php` | En `index.php` aparece `aceite` con stock `20` y `arroz` con stock `8` |  |
| 6 | Escenario B - Intento de vender mas que el stock | `Producto: aceite`, `Cantidad: 26` | Se muestra error de stock insuficiente y la venta no se registra |  |
| 7 | Verificar stock intacto en index.php para escenario B | volver a `index.php` | `aceite` sigue en `25` y no cambia su stock |  |
| 8 | Escenario C - Cantidad 0 | `Producto: gaseosa`, `Cantidad: 0` | Se rechaza la venta con mensaje de cantidad invalida y no se registra |  |
| 9 | Verificar que no cambia el stock para escenario C | volver a `index.php` | `gaseosa` conserva su stock inicial |  |
| 10 | Escenario D - Subtotal exacto 100,00 | `Linea 1: aceite, cantidad 5` y `Linea 2: gaseosa, cantidad 16` | La venta se acepta porque `5 * 12.00 = 60.00` y `16 * 2.50 = 40.00`, subtotal `100.00` |  |
| 11 | Verificar descuento en escenario D | `Subtotal 100.00` | El mensaje debe indicar que no aplica descuento y el total es `100.00` |  |
| 12 | Verificar stock en index.php para escenario D | volver a `index.php` | `aceite` queda en `20` y `gaseosa` queda en `24` |  |
| 13 | Escenario E - Subtotal 100,01 | `Linea 1: aceite, cantidad 8` y `Linea 2: leche, cantidad 1` | La venta se acepta porque `8 * 12.00 = 96.00` y `1 * 4.01 = 4.01`, subtotal `100.01` |  |
| 14 | Verificar descuento en escenario E | `Subtotal 100.01` | El mensaje debe mostrar descuento del 5% y total `95.01` (100.01 - 5.00 = 95.01) |  |
| 15 | Verificar stock en index.php para escenario E | volver a `index.php` | `aceite` queda en `17` y `leche` queda en `29` |  |
| 16 | Validacion final general | Revisar catalogo de productos | Los productos se muestran con su nombre, precio y stock actual, sin errores visibles |  |

Notas:
- Los datos de precio usados son exactamente los del script SQL: arroz 4.80, aceite 12.00, leche 4.01, azucar 3.90, atun 6.50, gaseosa 2.50.
- El flujo A valida la regla R2 con subtotal mayor a 100.00 y la reduccion de stock en la misma operacion.
- El flujo B valida rechazo por stock insuficiente y no modificacion del stock.
- El flujo C valida rechazo por cantidad 0.
- Los escenarios D y E validan ambos bordes del umbral de descuento: `100.00` exacto y `100.01` inmediato.
