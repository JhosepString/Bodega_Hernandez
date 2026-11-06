# Casos de prueba

| ID | Regla | Técnica | Entrada exacta | Resultado esperado | Nivel |
|---|---|---|---|---|---|
| CP-01 | R1 | Valor límite (en el borde) | hayStock(cantidad 10, stock 10) | true (venta válida) | Unitaria |
| CP-02 | R1 | Valor límite (debajo) | hayStock(cantidad 9, stock 10) | true | Unitaria |
| CP-03 | R1 | Valor límite (encima) | hayStock(cantidad 11, stock 10) | false (rechazo) | Unitaria |
| CP-04 | R1 | Partición (agotado) | hayStock(cantidad 1, stock 0) | false (rechazo) | Unitaria |
| CP-05 | R2 | Valor límite (en el borde) | subtotal 100,00 | descuento 0,00; total 100,00 | Unitaria |
| CP-06 | R2 | Valor límite (encima) | subtotal 100,01 | descuento 5,00; total 95,01 | Unitaria |
| CP-07 | R2 | Valor límite (debajo) | subtotal 99,99 | descuento 0,00; total 99,99 | Unitaria |
| CP-08 | R3 | Valor límite | validarCantidad("0") | false | Unitaria |
| CP-09 | R3 | Partición (negativos) | validarCantidad("-1") | false | Unitaria |
| CP-10 | R3 | Valor límite (mínimo válido) | validarCantidad("1") | true | Unitaria |
| CP-11 | R3 | Partición (decimales) | validarCantidad("1.5") y validarCantidad("1,5") | false | Unitaria |
| CP-12 | R3 | Partición (no numérico) | validarCantidad("") y validarCantidad("abc") | false | Unitaria |
| CP-13 | R4 | Tabla de decisión (venta válida) | bodega_test: producto A stock 5, B stock 7; vender A×3 y B×2 | stock A = 2, B = 5; 1 fila en venta y 2 en detalle_venta | Integración |
| CP-14 | R4 | Tabla de decisión (una línea falla) | bodega_test: A stock 5, B stock 2; vender A×1 y B×3 | excepción; stock A = 5, B = 2; 0 filas en venta y detalle_venta | Integración |
| CP-15 | R1 + R4 | Valor límite | bodega_test: producto stock 5; vender 5 | venta registrada; stock final 0 | Integración |
| CP-16 | R2 + R4 | Valor límite | Navegador: Aceite×8 + Leche×1 (subtotal 100,01) | mensaje: descuento S/ 5,00, total S/ 95,01; en index.php el aceite baja 8 y la leche 1 | E2E |
| CP-17 | R3 | Valor límite | Navegador: cliente Ana, Arroz, cantidad 0 | «Venta rechazada…cantidad…»; stock del arroz igual en index.php | E2E |
| CP-18 | R1 | Partición (agotado) | Navegador: cliente Ana, Atún (stock 0), cantidad 1 | «Venta rechazada…stock insuficiente…»; atún sigue en 0 | E2E |

Notas:
- Los casos CP-01 a CP-12 cubren las fronteras de cantidad y descuento en forma directa.
- Los casos CP-13 a CP-18 validan la integracion y la experiencia de usuario en los flujos de negocio principales.
- Para el caso R2 con subtotal 100,01, el total esperado se calcula como 100,01 - (100,01 * 0,05) = 95,0095, redondeado a dos decimales como 95,01.
