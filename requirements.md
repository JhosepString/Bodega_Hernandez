# Requirements Document

## Introduction

El Sistema de registro de ventas de la bodega «Doña Rosa» permite registrar productos, consultar stock, registrar ventas con una o varias líneas, aplicar el descuento por volumen y descontar el stock al confirmar cada venta. El sistema funciona en una sola computadora con PHP 8 + MySQL sobre Laragon, sin framework y sin librerías externas salvo PHPUnit. No incluye autenticación, reportes ni facturación electrónica.

## Glossary

- **Sistema**: Aplicación de registro de ventas de la bodega «Doña Rosa».
- **Producto**: Artículo del catálogo con nombre, precio unitario y stock disponible.
- **Venta**: Operación de cobro compuesta por una o varias líneas de venta.
- **Línea de venta**: Elemento de una Venta que referencia un Producto y una cantidad vendida.
- **Stock**: Cantidad disponible de un Producto en el inventario.
- **Subtotal**: Suma de (precio unitario × cantidad) de todas las líneas de una Venta, antes del descuento.
- **Descuento por volumen**: Reducción del 5 % aplicada sobre el Subtotal cuando este supera S/ 100.00.
- **Total**: Monto final de la Venta después de aplicar el descuento por volumen, si corresponde.

## Requirements

### Requirement 1: Registrar productos

**User Story:** Como bodeguera quiero registrar productos con nombre, precio y stock inicial para mantener un catálogo actualizado.

#### Acceptance Criteria

1. WHEN se registra un Producto con nombre (entre 1 y 100 caracteres), precio unitario (entre S/ 0.01 y S/ 999,999.99) y stock inicial (entero entre 0 y 999,999,999), THE Sistema SHALL crear el registro del Producto y confirmar el registro.
2. IF un Producto se registra con nombre vacío o solo espacios, precio menor o igual a cero, o stock inicial negativo o decimal, THEN THE Sistema SHALL rechazar el registro indicando el campo inválido y no persistir el Producto.

### Requirement 2: Consultar productos y stock

**User Story:** Como bodeguera quiero consultar los productos y su stock disponible para saber qué hay para vender durante el día.

#### Acceptance Criteria

1. WHEN la bodeguera consulta el catálogo de Productos, THE Sistema SHALL mostrar todos los Productos registrados con nombre, precio unitario y stock actual.
2. WHILE el stock de un Producto es cero, THE Sistema SHALL indicar que el Producto no tiene unidades disponibles.
3. IF no hay Productos registrados, THEN THE Sistema SHALL mostrar un mensaje indicando que no hay productos en el catálogo.

### Requirement 3: Registrar venta con una o varias líneas

**User Story:** Como bodeguera quiero registrar una venta con una o varias líneas de productos para cobrar varios productos en una sola operación.

#### Acceptance Criteria

1. WHEN se registra una Venta con al menos una línea de venta, THE Sistema SHALL crear el registro de la Venta con todas sus líneas de venta y el Subtotal.
2. IF la cantidad de una línea de venta no es un entero mayor que cero [R3], THEN THE Sistema SHALL rechazar la Venta indicando el error de cantidad y sin modificar el stock de ningún Producto.
3. IF la cantidad de una línea de venta es mayor al stock disponible del Producto [R1], THEN THE Sistema SHALL rechazar la Venta indicando el error de stock insuficiente y sin modificar el stock de ningún Producto.
4. IF la cantidad de una línea de venta es igual al stock disponible del Producto [R1], THEN THE Sistema SHALL aceptar la línea de venta y el stock del Producto quedará en cero.
5. IF se intenta registrar una Venta sin líneas de venta, THEN THE Sistema SHALL rechazar la Venta con un mensaje de error indicando que debe incluir al menos una línea.

### Requirement 4: Aplicar descuento por volumen

**User Story:** Como bodeguera quiero que el descuento por volumen se aplique automáticamente para evitar errores al calcularlo.

#### Acceptance Criteria

1. IF el Subtotal de la Venta es mayor a S/ 100.00 [R2], THEN THE Sistema SHALL aplicar un 5 % de descuento sobre el Subtotal y calcular el Total igual al Subtotal menos el monto del descuento.
2. IF el Subtotal de la Venta es menor o igual a S/ 100.00 [R2], THEN THE Sistema SHALL calcular el Total igual al Subtotal sin aplicar descuento.

### Requirement 5: Descontar stock al confirmar la venta

**User Story:** Como bodeguera quiero que el stock de cada producto se descuente al confirmar la venta para mantener el control del inventario.

#### Acceptance Criteria

1. WHEN se confirma una Venta, THE Sistema SHALL reducir el stock de cada Producto en la cantidad vendida [R4], permitiendo que el stock llegue a cero según [R1].
2. WHILE se actualiza el stock al confirmar una Venta, THE Sistema SHALL realizar todas las reducciones de stock en la misma transacción [R4].
3. IF al confirmar la Venta la cantidad de un Producto excede el stock disponible [R1], THEN THE Sistema SHALL rechazar la Venta indicando el stock insuficiente y preservar el stock sin cambios [R4].
