---
name: ssr-database
description: Analiza y diseña cambios de base de datos en SSR, considerando MySQL, Eloquent, migrations, relaciones, índices, integridad, rendimiento y separación Central/Tenant.
---

# SSR Database

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

Analizar y diseñar cambios de base de datos en SSR de forma segura,
eficiente y compatible con la implementación real del proyecto.

Esta Skill complementa el `AGENTS.md` del proyecto.

Cuando la tarea dependa del código existente, inspecciona los archivos locales; puedes apoyarte en
SSR Repository Analysis si resulta útil.

Cuando la decisión tenga impacto estructural significativo, puedes consultar SSR Architecture.

## CUÁNDO USAR ESTA SKILL

Úsala cuando una tarea implique:

- crear o modificar tablas;
- crear o modificar migrations;
- diseñar relaciones Eloquent;
- agregar o modificar columnas;
- definir PK, FK, índices o constraints;
- analizar consultas lentas;
- optimizar listados;
- revisar integridad referencial;
- corregir problemas de persistencia;
- evaluar volumen de datos;
- diseñar consultas complejas;
- analizar duplicados;
- revisar eager loading o N+1;
- evaluar almacenamiento Central/Tenant;
- migrar o transformar datos.

No es necesaria para tareas que no involucren persistencia ni consultas.

## PRINCIPIO FUNDAMENTAL

No diseñes la base de datos por convención o intuición.

Primero determina:

- qué existe realmente;
- qué tablas y columnas participan;
- qué modelos las utilizan;
- qué relaciones existen;
- si los datos son Central o Tenant;
- qué volumen y patrón de acceso tienen.

No presentes como existentes tablas, columnas, claves, índices o relaciones
sin verificarlas. Identifica los elementos nuevos como PROPUESTOS.

## PROCEDIMIENTO

### 1. Comprender la necesidad

Determina:

- qué información debe almacenarse o consultarse;
- cómo se utiliza actualmente;
- qué problema se intenta resolver;
- si el cambio afecta datos existentes.

No propongas estructura antes de comprender el caso de uso.

### 2. Inspeccionar implementación real

Cuando corresponda, puedes consultar SSR Repository Analysis.

Revisa únicamente lo necesario:

- migrations;
- modelos Eloquent;
- relaciones;
- queries;
- scopes;
- componentes que consumen datos;
- controladores;
- servicios;
- configuración de conexiones;
- tests relacionados.

Reconstruye el flujo real de lectura y escritura.

### 3. Determinar contexto de datos

Antes de diseñar cualquier cambio establece si los datos pertenecen a:

- base Central;
- base Tenant;
- ambas;
- otra conexión existente.

Comprueba cómo SSR inicializa y utiliza dichas conexiones.

Nunca asumas que una tabla pertenece al contexto tenant solo por su nombre
o ubicación.

### 4. Analizar estructura

Evalúa cuando corresponda:

- clave primaria;
- claves foráneas;
- tipos de datos;
- nullability;
- valores por defecto;
- índices;
- índices compuestos;
- unique constraints;
- integridad referencial;
- timestamps;
- SoftDeletes;
- cardinalidad;
- crecimiento esperado.

No agregues índices indiscriminadamente.

Cada índice debe responder a una necesidad real de búsqueda, relación,
ordenamiento o restricción.

### 5. Analizar Eloquent

Comprueba:

- modelo correcto;
- conexión utilizada;
- table si está personalizada;
- fillable/guarded;
- casts;
- relaciones;
- scopes;
- SoftDeletes;
- timestamps;
- eager loading;
- comportamiento central/tenant.

No des por existentes relaciones porque parezcan naturales; distingue
las relaciones verificadas de las nuevas propuestas.

Verifica FK, columnas y código existente.

### 6. Evaluar consultas

Cuando exista un problema de consulta o rendimiento analiza:

- WHERE;
- JOIN;
- ORDER BY;
- GROUP BY;
- HAVING;
- subqueries;
- EXISTS;
- agregaciones;
- paginación;
- eager loading;
- N+1;
- número aproximado de filas;
- índices relevantes.

No optimices únicamente por apariencia del código.

Identifica primero el cuello de botella real.

Cuando sea necesario, recomienda comprobar el plan de ejecución con
herramientas apropiadas como EXPLAIN.

### 7. Diseñar migrations

Antes de crear una migration determina:

- base/conexión objetivo;
- estado actual del esquema;
- datos existentes;
- riesgo de bloqueo;
- compatibilidad con producción;
- reversibilidad.

No modifiques migrations históricas posiblemente ejecutadas en producción,
salvo que exista una razón explícita y controlada.

Prefiere una nueva migration.

Una migration debe tener un objetivo claro y limitado.

### 8. Cambios destructivos

Considera destructivos, entre otros:

- drop table;
- drop column;
- renombrados con impacto;
- reducción de tipo;
- cambios de nullability incompatibles;
- eliminación de índices críticos;
- modificación masiva de datos.

Antes de proponerlos:

1. advierte consecuencias;
2. determina si existen datos;
3. plantea respaldo o estrategia segura;
4. considera despliegue gradual cuando corresponda.

Nunca utilices pérdida de datos como solución rápida.

### 9. Integridad

Siempre que sea pertinente analiza si una regla debe garantizarse mediante:

- FK;
- UNIQUE;
- NOT NULL;
- CHECK compatible;
- validación de aplicación;
- transacción.

No dependas exclusivamente de validación de interfaz cuando la integridad
deba protegerse en la base de datos.

### 10. Transacciones y concurrencia

Cuando una operación modifique múltiples registros relacionados evalúa si
requiere transacción.

Considera problemas de concurrencia cuando existan:

- contadores;
- saldos;
- secuencias;
- inventario;
- estados dependientes;
- procesos simultáneos.

No agregues locking sin necesidad demostrable.

### 11. Rendimiento

Evalúa rendimiento proporcionalmente al problema.

Considera:

- volumen actual;
- crecimiento;
- frecuencia de consultas;
- selectividad;
- índices;
- cantidad de columnas;
- paginación;
- carga de relaciones;
- procesamiento en PHP vs SQL.

Evita optimización prematura.

La solución debe equilibrar claridad, integridad y rendimiento.

### 12. Propuesta

Antes de implementar presenta:

- diagnóstico;
- estructura actual relevante;
- contexto Central/Tenant;
- cambio propuesto;
- archivos afectados;
- migration necesaria;
- modelos afectados;
- índices/constraints;
- riesgos;
- verificación.

Si existen alternativas relevantes, compáralas brevemente.

## TENANCY

Toda modificación de base de datos debe identificar explícitamente su
contexto Central/Tenant.

Comprueba:

- ubicación y tipo de migration;
- conexión usada;
- modelos asociados;
- inicialización de tenancy;
- consultas que podrían cruzar contexto.

Nunca propongas relaciones o queries capaces de mezclar información entre
tenants sin un mecanismo explícito y seguro.

Cuando el análisis requiera conocimiento especializado de tenancy,
puedes consultar SSR Tenancy si está disponible.

## DATOS EXISTENTES

No asumas que una tabla está vacía.

Si el cambio afecta datos existentes determina cómo se migrarán.

Cuando sea necesario separa:

1. cambio de esquema;
2. migración/backfill de datos;
3. activación de nueva lógica;
4. limpieza posterior.

Prefiere cambios compatibles con despliegues graduales cuando el riesgo
lo justifique.

## DOCUMENTACIÓN

Cuando una decisión dependa del comportamiento de:

- Laravel migrations;
- Eloquent;
- MySQL;
- Tenancy;
- paquete externo;

verifica primero la versión instalada y utiliza documentación oficial compatible.

El repositorio define cómo SSR usa la tecnología.
La documentación define cómo la tecnología funciona.

## RESULTADO ESPERADO

Antes de un cambio importante de base de datos debes poder responder:

1. ¿Qué estructura existe realmente?
2. ¿Qué datos se almacenan?
3. ¿Central o Tenant?
4. ¿Qué modelos y consultas participan?
5. ¿Qué integridad debe garantizarse?
6. ¿Qué índices son necesarios y por qué?
7. ¿Existe riesgo para datos existentes?
8. ¿La migration es segura y reversible?
9. ¿Existe impacto de rendimiento?
10. ¿Cómo se verificará el cambio?

La base de datos debe proteger la integridad del sistema y soportar
eficientemente sus casos de uso reales.
