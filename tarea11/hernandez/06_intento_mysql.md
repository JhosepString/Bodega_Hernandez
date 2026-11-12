# Intento de conexión MySQL desde n8n (paso 5, bonificación)

**Configuración probada:** MySQL Tool / credencial MySQL en n8n Cloud
- Host: sql211.infinityfree.com · Puerto: 3306 · Base: if0_43374435_bodega · Usuario: if0_43374435
- Query: `SELECT nombre, precio, stock FROM producto`

**Error exacto:**
```
Couldn't connect with these settings
getaddrinfo ENOTFOUND sql211.infinityfree.com
```

Captura: 06_error_mysql.png (credencial MySQL en n8n Cloud, prueba de conexión al guardar).

**Análisis:**
1. ENOTFOUND significa que desde internet ni siquiera se resuelve el nombre sql211.infinityfree.com: InfinityFree solo publica su MySQL dentro de su propia red, para los PHP alojados en el hosting, y no acepta conexiones externas como la de n8n Cloud.
2. La pieza que falta es una API REST intermedia en el hosting: un endpoint PHP que consulte la base y devuelva JSON, que el agente llamaría con un HTTP Request Tool.
3. Así la base nunca queda expuesta: la API decide qué consultas se permiten (solo lectura de productos) y puede exigir un token, igual que el principio de mantener las credenciales fuera del cliente.
