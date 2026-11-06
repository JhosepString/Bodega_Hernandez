# Informe de seguridad

## Hallazgos

| Archivo | Linea | Categoria | Evidencia | Severidad | Confirmado? | Correccion |
|---|---:|---|---|---|---|---|
| config/env.php | 3-9 | A05 - Configuracion insegura | `'user' => 'root'`, `'password' => ''` | Media | No (no esta versionado; `git ls-files config` mostro solo `config/conexion.php`) | Mover estas credenciales a variables de entorno o a un gestor de secretos, y usar un usuario con permisos minimizados para la aplicacion. |
| config/env_test.php | 3-9 | A05 - Configuracion insegura | `'user' => 'root'`, `'password' => ''` | Media | No (no esta versionado; `git ls-files config` mostro solo `config/conexion.php`) | Reemplazar por un usuario de prueba aislado y un secreto fuera del repositorio. |
| guardar_venta.php | 68-75 | A09 - Fuga de errores / logging insuficiente | `catch (Throwable $e) { echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>'; }` | Media | Si | Registrar la excepcion en el servidor con `error_log()` y mostrar un mensaje generico al usuario. |

## Resultado de la prueba manual de inyeccion

| Entrada | Campo | Resultado observado |
|---|---|---|
| `productos[]=1 OR 1=1` | `productos[]` | Se acepto la peticion y la aplicacion registro la venta como si el producto fuera el id 1; no hubo inyeccion SQL ni una condicion `OR 1=1` ejecutada en la base de datos. |
| `productos[]=' OR '1'='1` | `productos[]` | Se rechazo la venta con `Cada cantidad debe ser un entero mayor que cero.`; el valor no fue aceptado como id de producto y se bloqueo antes de DB. |
| `cliente=<script>alert(1)</script>` con producto valido y cantidad 1 | `cliente` | La salida se mostro escapada como `&lt;script&gt;alert(1)&lt;/script&gt;`, por lo que el script no se ejecuta en la respuesta HTML. |

## Observaciones

- La validacion de SQL en `src/RepositorioMySQL.php` usa sentencias preparadas con `prepare()` y parametros, por lo que no se detectaron consultas con concatenacion de variables ni inyeccion directa.
- La salida HTML en `index.php`, `venta.php` y `guardar_venta.php` usa `htmlspecialchars`, por lo que no hay XSS reflejado en los nombres de productos o cliente.
- La prueba de `git ls-files config` mostro solo `config/conexion.php`; los archivos `config/env.php` y `config/env_test.php` no estan versionados en git, por lo que el riesgo de A05 aparece como local y no como dato comprometido en el repositorio.
