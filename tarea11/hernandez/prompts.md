# Tarea 11 · Registro de prompts

| # | Paso | Consulta a la IA | Decisión tomada |
|---|---|---|---|
| 1 | 3 | Dónde va el System Message en Basic LLM Chain (no aparecía «System Message») | Lo puse en Chat Messages → Type: System |
| 2 | 3 | El Response Body quedó con el ejemplo `myField` y en modo Fixed | Borré el ejemplo y lo pasé a modo Expression |
| 3 | 3 | Error 404 «webhook "bodega" is not registered» | Hay que pulsar Execute workflow antes de cada POST a la Test URL |
| 4 | 3 | Tildes y «¿» en PowerShell | Envié el Body como bytes UTF-8 con charset=utf-8 |
| 5 | 3 | El modelo respondió «descuentos en vinos y piscos» | Lo registré como alucinación del flujo fijo (evidencia para react.md) |
| 6 | 4 | El Code Tool no tiene campo «Name» en mi versión | Renombré el nodo a «productos», que es el nombre que ve el agente |
| 7 | 4 | El AI Agent devuelve `output` y no `text` | Cambié el Response Body a `JSON.stringify($json.output)` |
| 8 | 4 | Interpretar los Logs de las 3 preguntas | P1 usó productos + Calculator; P2 solo productos; P3 consultó productos por decisión propia |
| 9 | 5 | Por qué falla MySQL desde n8n | InfinityFree bloquea conexiones externas; la pieza faltante es una API REST intermedia |