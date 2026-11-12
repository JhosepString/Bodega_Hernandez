# Tarea 11 · Registro de uso de IA (Claude)

| # | Paso | Consulta a la IA | Decisión tomada |
|---|---|---|---|
| 1 | 1 | Cómo crear la cuenta en n8n Cloud y llegar al lienzo vacío | Usé la opción A (n8n Cloud): hernandezzz.app.n8n.cloud |
| 2 | 3 | Dónde va el System Message en Basic LLM Chain (no aparecía «System Message») | Lo puse en Chat Messages → Type: System |
| 3 | 3 | El Response Body quedó con el ejemplo `myField` y en modo Fixed | Borré el ejemplo y lo pasé a modo Expression |
| 4 | 3 | Error 404 «webhook "bodega" is not registered» | Hay que pulsar Execute workflow antes de cada POST a la Test URL |
| 5 | 3 | Tildes y «¿» en PowerShell | Envié el Body como bytes UTF-8 con charset=utf-8 |
| 6 | 3 | El modelo respondió «descuentos en vinos y piscos» | Lo registré como alucinación del flujo fijo (evidencia para react.md) |
| 7 | 4 | El Code Tool no tiene campo «Name» en mi versión | Renombré el nodo a «productos», que es el nombre que ve el agente |
| 8 | 4 | El AI Agent devuelve `output` y no `text` | Cambié el Response Body a `JSON.stringify($json.output)` |
| 9 | 4 | Flujo v2 en JSON | Con autorización de la docente, importé el flujo v2 desde un JSON generado con Claude y elegí a mano la credencial y el modelo Gemini 3.1 Flash Lite |
| 10 | 4 | Interpretar los Logs de las 3 preguntas | P1 usó productos + Calculator; P2 solo productos; P3 consultó productos por decisión propia |
| 11 | 5 | Por qué falla MySQL desde n8n | InfinityFree bloquea conexiones externas; la pieza faltante es una API REST intermedia |
| 12 | 6 | Redacción del corpus de 20 preguntas | Usé un borrador de Claude y revisé que cubra los 7 temas y coincida con las reglas de la app (5 % sobre S/ 100) |
| 13 | 7 | Redacción de react.md | Revisé las 3 líneas y las ajusté a la evidencia de mis Logs |
