# Transporte, persistencia y verificación DTE

Consulta solo las secciones pertinentes a la operación afectada.

# 17. ENVÍO HTTP

Antes de implementar o modificar el transporte verifica en OpenFactura:

- endpoint;
- método;
- headers;
- autenticación;
- timeout;
- body;
- formato;
- códigos HTTP;
- respuesta.

Inspecciona primero el mecanismo HTTP actualmente utilizado por SSR.

No reemplazar cURL, Laravel HTTP Client u otro transporte solamente por
preferencia.

Evalúa:

- manejo de errores;
- status HTTP;
- timeout;
- conexión;
- JSON inválido;
- respuesta vacía;
- seguridad;
- testabilidad;
- idempotencia.

No inventar URLs ni endpoints.

---

# 18. RESPUESTA OPENFACTURA

No considerar éxito únicamente porque la petición HTTP no lanzó excepción.

Determina mediante documentación vigente:

HTTP
→ respuesta OpenFactura
→ resultado de operación
→ estado del documento
→ folio/identificador
→ aceptación/rechazo cuando corresponda.

No asumir que la presencia de un campo concreto significa éxito sin verificar
el contrato.

Si SSR actualmente utiliza esa regla, clasificarla como COMPROBADO EN SSR
hasta verificarla contra OpenFactura.

---

# 19. ERRORES

Clasificar cuando corresponda:

- validación SSR;
- conectividad;
- HTTP;
- autenticación API;
- autorización;
- timeout;
- rate limiting;
- error temporal;
- error OpenFactura;
- rechazo tributario;
- formato;
- configuración;
- negocio;
- infraestructura;
- resultado incierto.

No mostrar mensajes técnicos o secretos directamente al usuario.

Puedes consultar SSR Debugging para errores reales.

---

# 20. RESULTADO INCIERTO

Tratar explícitamente el escenario:

SSR envía solicitud
→ OpenFactura recibe/procesa
→ la respuesta no llega a SSR.

Esto NO equivale a un rechazo confirmado.

Clasificar conceptualmente:

ÉXITO CONFIRMADO
RECHAZO CONFIRMADO
FALLO PREVIO CONOCIDO
RESULTADO INCIERTO.

Ante resultado incierto:

NO reenviar automáticamente a ciegas.

Primero determinar mediante documentación oficial cómo:

- consultar;
- reconciliar;
- recuperar;
- verificar;

la operación anterior.

No inventar un endpoint de reconciliación.

---

# 21. IDEMPOTENCIA

Antes de emitir o reintentar determina:

- soporte oficial;
- header/campo;
- formato;
- longitud;
- alcance;
- duración;
- comportamiento ante reutilización;
- respuesta;
- relación con tenant/emisor/documento.

No asumir que una clave basada únicamente en un ID local sea globalmente única.

En sistemas multitenant evaluar posibles colisiones entre IDs equivalentes de
tenants distintos.

No modificar la estrategia existente sin verificar primero el contrato oficial.

---

# 22. REINTENTOS

No aplicar retries genéricos a emisión tributaria.

Antes de reintentar clasifica el fallo.

Un rechazo determinista no debe reintentarse automáticamente sin corregirlo.

Un timeout o desconexión posterior al envío puede representar resultado
incierto.

Un error temporal puede ser reintentable solamente cuando el comportamiento
de idempotencia/reconciliación permita hacerlo con seguridad.

---

# 23. PERSISTENCIA

Determina primero qué persiste actualmente SSR.

Cuando sea necesario poder reconstruir una emisión considera:

- tenant;
- documento local;
- empresa emisora;
- TipoDTE;
- folio;
- fecha/hora;
- estado;
- identificadores externos;
- clave de idempotencia;
- resultado;
- warnings;
- errores relevantes;
- intentos;
- estado confirmado.

No significa automáticamente crear una tabla nueva.

Inspeccionar primero migrations, modelos y flujo posterior a la respuesta.

No almacenar secretos.

No persistir payloads completos indiscriminadamente sin evaluar:

- necesidad;
- seguridad;
- privacidad;
- auditoría;
- datos personales.

---

# 24. LOGGING

Los logs deben permitir diagnosticar sin exponer:

- API keys;
- tokens;
- certificados;
- secretos;
- datos personales innecesarios;
- payload completo innecesario.

Preferir identificadores internos/externos seguros y contexto suficiente.

---

# 25. JOBS Y COLAS

Si la emisión se procesa mediante Job:

- preservar tenant;
- verificar idempotencia;
- definir retries;
- manejar timeout;
- distinguir errores reintentables y definitivos;
- registrar resultado;
- evitar doble emisión.

No asumir que el contexto HTTP/Tenant persiste automáticamente en un Job.

Puedes consultar SSR Laravel y SSR Tenancy.

---

# 26. TRANSACCIONES

No asumir que una transacción MySQL puede revertir:

- una solicitud HTTP ya enviada;
- una emisión procesada;
- un DTE aceptado;
- un folio asignado;
- efectos externos de OpenFactura/SII.

Diseñar consistencia considerando que la API externa puede producir efectos
irreversibles o parcialmente irreversibles.

---

# 27. TESTING

Puedes consultar SSR Testing & Verification.

Separar cuando corresponda:

TESTS CONTROLADOS

- mapper;
- validaciones;
- payload;
- emisor;
- receptor;
- detalle;
- totales;
- respuestas;
- errores;
- status HTTP;
- timeout;
- idempotencia;
- autorización;
- tenant;
- resultado incierto.

TEST DE INTEGRACIÓN

Solo realizar llamadas reales contra un ambiente oficialmente destinado a
pruebas después de verificar documentalmente:

- que existe;
- cómo funciona;
- qué credenciales utiliza;
- si genera o no efectos tributarios reales;
- qué recursos deben limpiarse.

Nunca emitir documentos tributarios reales durante tests automatizados.

---

# 28. MOCKS Y FAKES

Mockear OpenFactura puede demostrar:

- payload;
- headers;
- flujo;
- manejo de respuestas;
- errores;
- reintentos;
- idempotencia local.

NO demuestra que la integración real sea compatible con OpenFactura.

Si SSR utiliza cURL directamente, no asumir que `Http::fake()` de Laravel
interceptará esas solicitudes.

Inspeccionar primero el transporte real.

No refactorizar únicamente para poder usar un fake determinado sin evaluar
arquitectura e impacto.

---

# 29. SEGURIDAD

Verifica según corresponda:

- autenticación;
- autorización;
- tenant propietario;
- ownership del documento;
- credenciales;
- datos tributarios;
- acceso a documentos;
- endpoints internos;
- descargas;
- consultas;
- logs.

Un ID recibido desde frontend nunca demuestra ownership.

Nunca confiar únicamente en el ID para determinar que un documento pertenece
al tenant autenticado.

---

