---
name: ssr-livewire-powergrid
description: Analiza, depura y diseña componentes Livewire y tablas PowerGrid en SSR, verificando versiones, lifecycle, estado, datasource, filtros, sorting, paginación, eventos y contexto Central/Tenant.
---

# SSR Livewire + PowerGrid

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

Analizar, diseñar, depurar y modificar componentes Livewire y tablas PowerGrid
en SSR de forma compatible con la implementación real y con las versiones
instaladas.

Esta Skill complementa el `AGENTS.md` del proyecto.

Cuando la tarea dependa del código existente, puedes consultar SSR Repository Analysis.

Cuando exista un error, puedes consultar SSR Debugging.

Cuando intervengan datos o consultas, puedes consultar SSR Database.

Cuando exista contexto multi-tenant, puedes consultar SSR Tenancy.

## CUÁNDO USAR ESTA SKILL

Úsala cuando una tarea implique:

- componentes Livewire;
- propiedades públicas;
- formularios reactivos;
- eventos Livewire;
- lifecycle hooks;
- hidratación;
- validación;
- acciones;
- tablas PowerGrid;
- datasource;
- columnas;
- filtros;
- búsqueda;
- sorting;
- paginación;
- acciones por fila;
- actualización de tablas;
- eventos entre componentes;
- problemas de renderizado;
- pérdida de estado;
- interacción Livewire/Alpine;
- comportamiento distinto entre carga inicial y requests Livewire.

## PRINCIPIO FUNDAMENTAL

No uses ejemplos genéricos de Livewire o PowerGrid sin verificar primero
la versión instalada.

No mezcles APIs de versiones diferentes.

El código real de SSR determina cómo se implementa la funcionalidad.

La documentación oficial compatible determina cómo debe comportarse
la tecnología.

## PROCEDIMIENTO

### 1. Comprender el comportamiento esperado

Determina:

- qué componente participa;
- qué debe mostrar o hacer;
- qué interacción del usuario lo dispara;
- qué resultado se espera;
- qué ocurre actualmente.

No modifiques código antes de entender el flujo.

### 2. Inspeccionar implementación real

Puedes consultar SSR Repository Analysis cuando corresponda.

Revisa únicamente lo necesario:

- clase Livewire;
- Blade asociado;
- PowerGrid relacionado;
- modelo;
- datasource/query;
- rutas;
- controlador si existe;
- servicios;
- eventos;
- listeners;
- Alpine;
- configuración relevante.

Reconstruye el flujo real desde request hasta renderizado.

### 3. Verificar versiones

Cuando el comportamiento dependa de API o lifecycle:

1. revisa `composer.json`;
2. identifica la versión bloqueada en `composer.lock` y contrástala con la instalada si necesitas comprobar la ejecución;
3. identifica versión de Livewire;
4. identifica versión de PowerGrid;
5. consulta documentación oficial compatible.

No utilices sintaxis de otra versión por memoria.

### 4. Livewire: estado y propiedades

Verifica:

- propiedades públicas;
- valores iniciales;
- tipos;
- nullable;
- atributos;
- propiedades calculadas;
- datos derivados;
- persistencia entre requests.

No almacenes estado innecesario si puede derivarse de una fuente confiable.

No asumas que una propiedad conserva exactamente el mismo estado entre
requests sin considerar hidratación y serialización.

### 5. Lifecycle

Cuando exista comportamiento dependiente del ciclo de vida revisa hooks
compatibles con la versión instalada.

Determina si la lógica ocurre en:

- inicialización;
- montaje;
- hidratación;
- actualización;
- renderizado;
- destrucción u otra fase disponible.

No muevas lógica a un hook por preferencia.

Debe existir una razón funcional.

### 6. Render

Mantén `render()` enfocado en la construcción de la respuesta.

Evita cuando sea posible:

- efectos secundarios;
- escrituras en base de datos;
- lógica costosa repetitiva;
- llamadas externas innecesarias.

Recuerda que Livewire puede renderizar múltiples veces.

No coloques una operación destructiva o no idempotente dentro de un flujo
que pueda ejecutarse repetidamente.

### 7. Eventos Livewire

Verifica antes de modificar:

- quién emite;
- quién escucha;
- nombre exacto;
- parámetros;
- scope;
- componente destino;
- versión de Livewire.

No asumas sintaxis de eventos de versiones anteriores.

Comprueba cómo SSR implementa los eventos actualmente.

### 8. Formularios y validación

Verifica:

- bindings;
- reglas;
- mensajes;
- autorización;
- transformación de datos;
- validación del lado servidor.

No confíes únicamente en validación del navegador.

Cuando exista contexto tenant, valida además pertenencia del recurso.

### 9. Alpine.js

Cuando Livewire interactúe con Alpine verifica:

- ownership del estado;
- eventos;
- sincronización;
- bindings;
- DOM administrado por Livewire;
- inicialización repetida.

No dupliques el mismo estado en Livewire y Alpine sin necesidad.

Evita manipular manualmente DOM gestionado por Livewire si puede provocar
inconsistencias.

### 10. PowerGrid: componente

Antes de modificar una tabla identifica:

- clase PowerGrid;
- modelo utilizado;
- datasource;
- columnas;
- fields;
- filtros;
- búsqueda;
- sorting;
- pagination;
- acciones;
- configuración.

No propongas métodos que no existan en la versión instalada.

### 11. Datasource

Analiza la consulta real.

Comprueba:

- modelo correcto;
- namespace;
- conexión;
- contexto Central/Tenant;
- joins;
- selects;
- aliases;
- relaciones;
- scopes;
- eager loading;
- filtros base.

El datasource debe devolver exactamente el tipo compatible con la versión
de PowerGrid instalada.

No agregues joins o relaciones solo para solucionar visualmente un problema
sin analizar el impacto SQL.

### 12. Columnas y fields

Distingue entre:

- columna física;
- atributo Eloquent;
- alias SQL;
- relación;
- field calculado;
- valor transformado.

No trates un valor calculado como columna real de base de datos.

Antes de habilitar sorting o filtering comprueba que el campo pueda ser
traducido correctamente a SQL.

### 13. Filtros

Cuando un filtro falle determina:

- campo visible;
- campo real;
- alias;
- tipo;
- query generada;
- relación;
- versión de PowerGrid.

No cambies filtros a ciegas.

Verifica primero qué consulta producen.

### 14. Sorting

Antes de habilitar ordenamiento determina si el valor corresponde a:

- columna de tabla;
- alias;
- relación;
- expresión calculada.

No asumas que un valor mostrado puede ordenarse automáticamente.

Si requiere SQL adicional, evalúa impacto e índices.

### 15. Paginación

Cuando exista duplicación, pérdida de filas o resultados inesperados analiza:

- ORDER BY;
- unicidad del orden;
- JOIN;
- GROUP BY;
- DISTINCT;
- clave primaria;
- filtros;
- cambios concurrentes;
- configuración de PowerGrid.

No atribuyas automáticamente problemas de paginación a Livewire.

Una paginación inconsistente puede originarse en la consulta.

### 16. Relaciones y N+1

Cuando la tabla muestre datos relacionados verifica:

- relación Eloquent;
- eager loading;
- join;
- subquery;
- número de consultas.

No agregues `with()` automáticamente.

Primero comprueba si existe N+1 real y cuál estrategia es compatible con
filtrado y sorting.

### 17. Acciones por fila

Cuando existan acciones analiza:

- identificador del registro;
- autorización;
- contexto tenant;
- estado actual;
- confirmación;
- actualización posterior de tabla.

Nunca confíes en un ID recibido desde el frontend como prueba suficiente
de autorización.

Vuelve a validar el recurso en servidor.

### 18. Actualización de tablas

Cuando otro componente o acción modifique datos determina cómo debe
actualizarse PowerGrid.

Comprueba mecanismos compatibles como eventos, refresh u otros disponibles
en la versión instalada.

No fuerces recarga completa de página si existe mecanismo reactivo correcto.

### 19. Tenancy

En componentes tenant verifica:

- inicialización en request inicial;
- contexto durante requests Livewire posteriores;
- middleware;
- conexión del modelo;
- pertenencia de recursos.

No des por hecho que el contexto se mantiene correctamente.

Comprueba la configuración real de SSR.

### 20. Rendimiento

Evalúa:

- cantidad de filas;
- consulta datasource;
- relaciones;
- selects;
- joins;
- filtros;
- sorting;
- índices;
- frecuencia de renders.

Evita cargar colecciones completas si PowerGrid puede trabajar mediante query
paginada.

No optimices sin evidencia.

### 21. Depuración

Cuando exista fallo utiliza el ciclo:

OBSERVAR
→ INSPECCIONAR
→ HIPÓTESIS
→ COMPROBAR
→ CAUSA
→ CORREGIR
→ VERIFICAR

No cambies simultáneamente Livewire, query y PowerGrid para intentar
"ver si funciona".

Aísla primero la causa.

### 22. Implementación

Después de confirmar la solución:

- identifica archivos exactos;
- aplica cambio mínimo;
- conserva convenciones SSR;
- evita refactors no relacionados;
- usa API compatible con versiones instaladas;
- considera Central/Tenant.

## TESTING

Verifica según corresponda:

- render inicial;
- actualización reactiva;
- filtros;
- búsqueda;
- sorting;
- paginación;
- reset de filtros;
- acciones;
- autorización;
- aislamiento tenant;
- registros relacionados;
- ausencia de duplicados;
- estado después de múltiples requests.

Una tabla no está verificada únicamente porque renderiza.

## DOCUMENTACIÓN

Cuando exista duda sobre:

- lifecycle;
- atributos;
- eventos;
- binding;
- validación;
- datasource;
- columnas;
- filtros;
- sorting;
- paginación;
- acciones;

verifica documentación oficial compatible con las versiones instaladas.

No uses snippets de blogs o respuestas antiguas como fuente principal cuando
la API pueda haber cambiado.

## FORMATO RECOMENDADO

Para cambios relevantes presenta:

### COMPORTAMIENTO ACTUAL
Qué hace SSR realmente.

### EVIDENCIA
Archivos, clases, queries y versiones comprobadas.

### PROBLEMA
Qué comportamiento es incorrecto.

### CAUSA
Solo si está confirmada.

### SOLUCIÓN
Cambio mínimo compatible.

### ARCHIVOS
Rutas exactas.

### CÓDIGO
Código necesario.

### VERIFICACIÓN
Cómo comprobar Livewire, PowerGrid, datos y tenancy.

## RESULTADO ESPERADO

Antes de aprobar una modificación debes poder responder:

1. ¿Qué componente Livewire participa?
2. ¿Qué versión está instalada?
3. ¿Qué tabla PowerGrid participa?
4. ¿Cuál es su datasource real?
5. ¿Qué modelo y conexión utiliza?
6. ¿Cómo se mantiene el estado?
7. ¿Qué ocurre entre requests Livewire?
8. ¿Filtros/sorting/paginación operan sobre campos reales?
9. ¿Existe impacto Central/Tenant?
10. ¿Cómo se verificará el comportamiento completo?

No corrijas el síntoma visual sin entender el flujo reactivo y la consulta
que lo producen.
