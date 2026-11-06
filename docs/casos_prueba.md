# Casos de prueba

| ID | Regla | Tecnica | Entrada exacta | Resultado esperado | Nivel |
|---|---|---|---|---|---|
| CP-01 | R1 | Valores limite | Producto con stock 10, cantidad 10 | La venta se acepta y el stock final queda en 0 | Unitaria |
| CP-02 | R1 | Valores limite | Producto con stock 10, cantidad 9 | La venta se acepta y el stock final queda en 1 | Unitaria |
| CP-03 | R1 | Valores limite | Producto con stock 10, cantidad 11 | La venta se rechaza con error de stock insuficiente y el stock no cambia | Unitaria |
| CP-04 | R1 | Valores limite | Producto con stock 0, cantidad 1 | La venta se rechaza porque no hay stock disponible | Unitaria |
| CP-05 | R2 | Valores limite | Subtotal 100,00 exacto | No se aplica descuento y el total es 100,00 | Unitaria |
| CP-06 | R2 | Valores limite | Subtotal 100,01 | Se aplica un descuento del 5% sobre 100,01 y el total queda en 95,01 (aprox.) | Unitaria |
| CP-07 | R2 | Valores limite | Subtotal 99,99 | No se aplica descuento y el total es 99,99 | Unitaria |
| CP-08 | R3 | Valores limite | Cantidad 0 | La venta se rechaza por cantidad invalida y no se modifica el stock | Unitaria |
| CP-09 | R3 | Valores limite | Cantidad -1 | La venta se rechaza por cantidad invalida y no se modifica el stock | Unitaria |
| CP-10 | R3 | Valores limite | Cantidad 1 | La venta se acepta, porque es un entero mayor que cero | Unitaria |
| CP-11 | R3 | Valores limite | Cantidad 1,5 | La venta se rechaza por cantidad invalida y no se modifica el stock | Unitaria |
| CP-12 | R3 | Valores limite | Cantidad igual al stock mas uno | Si el stock es 5, cantidad 6; la venta se rechaza por stock insuficiente | Unitaria |
| CP-13 | R4 | Tabla de decision | Venta valida con dos productos, stock inicial 5 y 7, cantidades 3 y 2 | La venta se confirma y ambos stocks se reducen en la misma transaccion | Integracion |
| CP-14 | R4 | Tabla de decision | Venta invalida por stock insuficiente | Un producto tiene stock 2 y la cantidad solicitada es 3 | La venta se rechaza y el stock de todos los productos permanece igual | Integracion |
| CP-15 | R1 y R4 | Valores limite | Venta con cantidad igual al stock y stock inicialmente 5 | La venta se acepta y el stock final queda en 0 | Integracion |
| CP-16 | R2 y R4 | Valores limite | Venta con subtotal 100,01 y stock suficiente | Se aplica descuento y la venta confirma con stock actualizado | E2E |
| CP-17 | R3 | E2E manual | Cliente valido, producto valido, cantidad 0 en formulario | Se muestra error de cantidad invalida y no se registra la venta | E2E |
| CP-18 | R1 | E2E manual | Cliente valido, producto con stock 0, cantidad 1 | Se muestra error de stock insuficiente y no se registra la venta | E2E |

Notas:
- Los casos CP-01 a CP-12 cubren las fronteras de cantidad y descuento en forma directa.
- Los casos CP-13 a CP-18 validan la integracion y la experiencia de usuario en los flujos de negocio principales.
- Para el caso R2 con subtotal 100,01, el total esperado se calcula como 100,01 - (100,01 * 0,05) = 95,0095, redondeado a dos decimales como 95,01.
