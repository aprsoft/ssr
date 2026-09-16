---
name: ssr-debugging
description: Diagnostica errores en SSR de forma sistemática, basada en evidencia, inspeccionando código, logs, stack traces, dependencias y contexto antes de proponer correcciones.
---

# SSR Debugging

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

## OBJETIVO

Diagnosticar y corregir errores en SSR de forma sistemática, reproducible
y basada en evidencia.

Esta Skill complementa el `AGENTS.md` del proyecto.

Cuando el error dependa del código existente, puedes consultar SSR Repository Analysis.

Cuando afecte base de datos, puedes consultar SSR Database.

Cuando involucre contexto multi-tenant, puedes consultar SSR Tenancy.

Cuando la corrección implique cambios estructurales relevantes, puedes consultar SSR Architecture.

## CUÁNDO USAR ESTA SKILL

Úsala cuando exista:

- excepción PHP;
- error Laravel;
- stack trace;
- error SQL;
- comportamiento inesperado;
- pantalla 500;
- fallo Livewire;
- error PowerGrid/DataTables;
- error de autenticación/autorización;
- problema de tenancy;
- fallo de migration;
- error Composer;
- error Artisan;
- fallo de Job/Event/Listener;
- consulta incorrecta;
- regresión después de un cambio;
- diferencia entre entorno local y producción.

## PRINCIPIO FUNDAMENTAL

No corrijas antes de diagnosticar.

No atribuyas una causa por similitud con errores conocidos.

Un mensaje de error es evidencia, no necesariamente la causa raíz.

Distingue siempre entre:

- HECHO COMPROBADO;
- HIPÓTESIS;
- CAUSA CONFIRMADA;
- CORRECCIÓN PROPUESTA.

No conviertas una hipótesis en conclusión sin comprobarla.

## PROCEDIMIENTO

### 1. Capturar el error

Analiza literalmente:

- mensaje;
- clase de excepción;
- archivo;
- línea;
- stack trace;
- código SQL;
- request;
- comando ejecutado;
- momento en que ocurre.

No ignores partes del mensaje porque parezcan secundarias.

Si el usuario entrega solo una descripción, identifica qué evidencia mínima
falta para continuar.

### 2. Determinar alcance

Establece si el fallo afecta:

- una ruta;
- un componente;
- un módulo;
- un tenant;
- todos los tenants;
- base Central;
- base Tenant;
- CLI;
- cola;
- producción;
- entorno local.

El alcance ayuda a descartar hipótesis.

### 3. Inspeccionar código real

Cuando corresponda puedes consultar SSR Repository Analysis.

Localiza:

- archivo señalado por la excepción;
- código que lo invoca;
- dependencias inmediatas;
- modelo/query relacionada;
- rutas;
- middleware;
- configuración;
- componentes relacionados.

No analices únicamente la línea que lanza la excepción.

Reconstruye el flujo que llega hasta ella.

### 4. Formular hipótesis

Genera únicamente hipótesis compatibles con la evidencia disponible.

Ordénalas por probabilidad y capacidad de comprobación.

Para cada hipótesis identifica una prueba concreta.

Ejemplo:

HIPÓTESIS:
la columna no existe.

COMPROBACIÓN:
revisar migration/esquema real.

No presentes listas extensas de causas genéricas.

### 5. Confirmar causa

Antes de proponer una corrección importante confirma la causa mediante
una o más evidencias:

- código real;
- esquema;
- logs;
- stack trace;
- configuración;
- versión instalada;
- reproducción;
- consulta;
- salida de comando;
- documentación oficial.

Si no puede confirmarse, mantén explícitamente el estado de hipótesis.

### 6. Verificar versiones

Cuando el error pueda depender de compatibilidad:

1. revisa versión instalada;
2. identifica API/configuración utilizada;
3. consulta documentación oficial compatible;
4. confirma si existe incompatibilidad real.

No atribuyas automáticamente errores a cambios de versión.

### 7. Base de datos

Para errores SQL o Eloquent analiza cuando corresponda:

- conexión;
- contexto Central/Tenant;
- tabla;
- columnas;
- aliases;
- joins;
- relaciones;
- tipos;
- nullability;
- FK;
- índices;
- migration;
- datos reales;
- consulta generada.

No asumas que una migration fue ejecutada únicamente porque existe en Git.

### 8. Tenancy

Si el error involucra datos o contexto multi-tenant determina:

- tenant activo;
- conexión utilizada;
- middleware;
- modelo Central/Tenant;
- inicialización;
- proceso síncrono/asíncrono.

Un error aparentemente SQL puede ser en realidad una conexión tenant
incorrecta.

Nunca ignores tenancy durante el diagnóstico cuando sea relevante.

### 9. Livewire / PowerGrid / DataTables

Cuando corresponda verifica:

- versión;
- lifecycle;
- propiedades;
- eventos;
- hidratación;
- bindings;
- query datasource;
- paginación;
- filtros;
- sorting;
- keys;
- estado del componente.

No mezcles APIs de Livewire, PowerGrid o DataTables de otras versiones.

### 10. Logs

Utiliza logs como evidencia.

Cuando sea necesario indica exactamente qué log revisar o qué información
capturar.

Evita agregar logging indiscriminado.

Si propones logging temporal:

- limita su alcance;
- evita secretos o datos sensibles;
- indica retirarlo después del diagnóstico.

### 11. Comandos de diagnóstico

Prefiere comandos no destructivos.

Ejemplos según corresponda:

- `php artisan about`
- `php artisan route:list`
- inspección acotada de configuración, ocultando secretos; no vuelques `config:show` indiscriminadamente
- `php artisan migrate:status`
- `composer show`
- consultas SQL de inspección
- `git diff`
- `git status`

No ejecutes ni recomiendes limpieza destructiva como primera prueba.

### 12. Cachés

No uses automáticamente:

- `config:clear`;
- `cache:clear`;
- `route:clear`;
- `view:clear`;
- `optimize:clear`;

como solución universal.

Úsalos solo cuando exista evidencia de que el error puede depender
de estado cacheado.

Explica qué hipótesis se está comprobando.

### 13. Dependencias

No recomiendes como primera acción:

- `composer update`;
- eliminar `vendor`;
- reinstalar Composer;
- actualizar Laravel;
- cambiar versiones;

sin evidencia.

Antes determina si realmente existe un problema de dependencia.

### 14. Cambios destructivos

No propongas para diagnosticar:

- borrar base de datos;
- `migrate:fresh`;
- eliminar datos;
- eliminar tenants;
- borrar migrations;
- reinstalar el proyecto;
- resetear producción.

Si excepcionalmente una acción destructiva es necesaria, advierte
claramente impacto y alternativas.

### 15. Corregir

Después de confirmar la causa propone la corrección mínima necesaria.

Indica:

- causa raíz;
- archivo afectado;
- ubicación;
- cambio;
- motivo;
- impacto;
- comandos necesarios;
- forma de verificar.

No aproveches una corrección para refactorizar código no relacionado.

### 16. Verificación

Después del cambio comprueba:

- que el error original desaparece;
- que el comportamiento esperado funciona;
- que no aparecen errores relacionados;
- tests relevantes;
- contexto Central/Tenant;
- regresiones.

No consideres resuelto un error únicamente porque dejó de aparecer la
excepción si el comportamiento funcional continúa incorrecto.

## MÉTODO DE DIAGNÓSTICO

Utiliza preferentemente este ciclo:

OBSERVAR
→ LOCALIZAR
→ HIPÓTESIS
→ COMPROBAR
→ CAUSA
→ CORREGIR
→ VERIFICAR

Si una prueba descarta una hipótesis, vuelve a la fase de hipótesis.

No encadenes modificaciones al azar.

## FORMATO RECOMENDADO

Para errores relevantes responde preferentemente:

### ERROR OBSERVADO
Mensaje y contexto comprobado.

### EVIDENCIA
Archivos, líneas, logs, consultas o configuración relevantes.

### HIPÓTESIS
Solo mientras no exista causa confirmada.

### CAUSA CONFIRMADA
Explicación precisa respaldada por evidencia.

### SOLUCIÓN
Cambio mínimo recomendado.

### ARCHIVOS
Rutas exactas involucradas.

### COMANDOS
Solo los necesarios y en orden.

### VERIFICACIÓN
Cómo demostrar que quedó resuelto.

## SI FALTA INFORMACIÓN

No inventes.

Primero intenta obtenerla desde:

- repositorio;
- logs disponibles;
- stack trace;
- configuración;
- archivos del proyecto;
- documentación.

Pregunta únicamente por información que no pueda obtenerse de esas fuentes,
por ejemplo una salida de un entorno remoto al que no tienes acceso.
Los cambios locales sin publicar deben inspeccionarse directamente.

## RESULTADO ESPERADO

Antes de afirmar que un error está diagnosticado debes poder responder:

1. ¿Qué ocurrió exactamente?
2. ¿Dónde ocurrió?
3. ¿Qué flujo llevó al error?
4. ¿Qué evidencia existe?
5. ¿Cuál es la causa raíz?
6. ¿Está confirmada o sigue siendo hipótesis?
7. ¿Cuál es el cambio mínimo que la corrige?
8. ¿Existe impacto Central/Tenant?
9. ¿Puede provocar una regresión?
10. ¿Cómo verificamos que quedó resuelto?

Nunca confundas desaparición del síntoma con resolución de la causa.
