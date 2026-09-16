---
name: ssr-openfactura-dte
description: Revisa o modifica la integración OpenFactura y los DTE de SSR, contrastando el código local, el contrato oficial de la operación y los requisitos SII aplicables. Úsala para payloads, emisión, consulta, errores e idempotencia tributaria.
---

# SSR OpenFactura / DTE

## Alcance y coordinación

Aplica las reglas compartidas de `AGENTS.md` de la raíz del proyecto, incluidas
las autorizaciones ya concedidas y la protección de cambios de otros chats.
La fuente de trabajo es `/home/ro/Proyectos/ssr` o la copia que el usuario
haya autorizado expresamente. No sincronices ni sustituyas el código local
por GitHub sin autorización.

Las referencias a otras Skills son apoyos opcionales: consúltalas solo si están
disponibles y aportan un procedimiento necesario para la tarea. No cargues toda
la colección ni repitas una inspección vigente por cada Skill. Si falta una,
continúa con evidencia local y fuentes oficiales; señala solo las limitaciones
que realmente impidan verificar el resultado.

Distingue versiones declaradas en manifests, bloqueadas en archivos lock e
instaladas en el entorno. Un lock no acredita por sí solo lo que se ejecuta.
Ajusta los pasos y la respuesta al riesgo y alcance; las listas son criterios
pertinentes, no una obligación de auditar todo el sistema.

## Fuentes y evidencia

Distingue tres responsabilidades: el código local describe la implementación;
OpenFactura define el contrato de su API; el SII establece los requisitos
tributarios aplicables. Que el código exista o que la API acepte una solicitud
no prueba por sí solo el cumplimiento tributario.

- OpenFactura: https://docsapi-openfactura.haulmer.com/
- SII, formato DTE: https://www.sii.cl/factura_electronica/factura_mercado/formato_dte.pdf
- SII, boletas: https://www.sii.cl/factura_electronica/factura_mercado/boletas_elec_020.pdf

Comprueba vigencia, tipo de documento y operación. Localiza la sección concreta
antes de concluir: abrir la portada no verifica un endpoint, campo o regla.
No inventes TipoDTE, códigos, tasas, folios, campos, endpoints, estados ni reglas.
Clasifica lo relevante como comprobado en SSR, OpenFactura o SII, propuesto o
no confirmado, citando la evidencia concreta.

Si la documentación es inaccesible, ambigua o insuficiente tras una búsqueda
acotada pertinente, indica lo revisado y el dato faltante. No investigues
indefinidamente ni completes el contrato desde memoria. Detén únicamente la
implementación que dependa de ese dato crítico; continúa lo independiente.

## Procedimiento según la tarea

1. Identifica el documento y la operación afectada. Inspecciona el flujo local,
   transporte, mapper, modelos, configuración y pruebas pertinentes.
2. Comprueba tenant emisor, conexión, pertenencia del documento y autorización
   en servidor. No expongas credenciales ni datos tributarios innecesarios.
3. Contrasta campos u operaciones afectados con SII y OpenFactura. Conserva
   la implementación existente salvo que exista una razón comprobable de cambio.
4. Aplica la solución dentro del alcance aprobado según `AGENTS.md`.
5. Verifica con pruebas controladas e informa los límites de la comprobación.

Lee referencias solo según la necesidad:

- Para payloads, emisor, receptor, montos, folios o referencias entre DTE:
  [Documentos y mapeo](references/documentos-y-mapeo.md).
- Para transporte HTTP, respuestas, resultados inciertos, idempotencia,
  persistencia, jobs o pruebas:
  [Transporte y verificación](references/transporte-y-verificacion.md).

No audites todos los documentos ni cargues todas las Skills por una corrección
localizada. Otras Skills SSR son apoyos opcionales cuando aporten a la tarea.

## Invariantes de emisión y pruebas

- Un timeout posterior al envío puede significar que el documento fue procesado.
  No equivale a rechazo ni autoriza reenviar. Verifica mecanismos oficiales de
  consulta, reconciliación e idempotencia antes de reintentar; si no puede
  determinarse el estado, conserva el resultado incierto y solicita revisión.
- Verifica alcance y duración de claves de idempotencia; un ID local puede
  repetirse en otros tenants. No inventes soporte del proveedor.
- Una transacción SQL no revierte una emisión externa ni un folio asignado.
- Un éxito HTTP o un mock exitoso no demuestra aceptación tributaria real.
- No emitas, anules ni envíes DTE reales en pruebas automatizadas. Las llamadas
  de integración requieren autorización y un ambiente oficialmente verificado
  de pruebas, incluidos sus posibles efectos.
- Si el transporte es cURL, no presupongas que `Http::fake()` lo intercepta.
  Comprueba que las pruebas no puedan enviar solicitudes reales accidentalmente.

## Resultado

Presenta el cambio o diagnóstico, evidencia y discrepancias relevantes,
riesgos de aislamiento o duplicación, comprobaciones realizadas y pendientes.
No entregues un payload productivo definitivo con requisitos críticos sin verificar.
