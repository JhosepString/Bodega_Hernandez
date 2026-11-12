# Flujo fijo frente a agente ReAct

1. Mi flujo fijo (v1) siempre hace lo mismo: Webhook → Basic LLM Chain → Respond to Webhook; el modelo solo genera texto y no puede consultar nada, por eso a la pregunta «¿Qué productos tienen descuento?» inventó «descuentos en vinos y piscos».
2. Un agente ReAct alterna razonamiento y acción: piensa qué le falta, llama a una herramienta, observa el resultado y vuelve a razonar hasta poder responder (Yao et al., 2023).
3. El ciclo ocurrió en la versión 2: en los Logs de «¿Cuánto cuestan 3 leches?» se ve Gemini → productos → Gemini → Calculator → Gemini (respuesta S/ 12,60), mientras que en «¿Tienen aceite?» solo llamó a productos y no a Calculator, lo que muestra que el agente decide qué herramienta usar.
