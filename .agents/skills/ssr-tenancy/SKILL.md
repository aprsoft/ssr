---
name: ssr-tenancy
description: Analiza y diseña funcionalidades multi-tenant en SSR, verificando contexto Central/Tenant, conexiones, modelos, middleware, jobs, eventos, autenticación y aislamiento de datos.
---

# SSR Tenancy

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

Analizar, diseñar y validar funcionalidades multi-tenant en SSR garantizando
aislamiento de datos, uso correcto del contexto Central/Tenant y compatibilidad
con la implementación real del proyecto.

Esta Skill complementa el `AGENTS.md` del proyecto.

Cuando una tarea dependa del código existente, puedes consultar SSR Repository Analysis.

Cuando implique cambios estructurales relevantes, puedes consultar SSR Architecture.

Cuando afecte esquema, relaciones, migrations o consultas, puedes consultar SSR Database.

## CUÁNDO USAR ESTA SKILL

Úsala cuando una tarea implique:

- modelos o datos tenant;
- modelos o datos centrales;
- identificación o inicialización de tenant;
- dominios de tenant;
- middleware de tenancy;
- autenticación en contexto multi-tenant;
- consultas Central/Tenant;
- migrations tenant;
- jobs, eventos o listeners con contexto tenant;
- archivos, cache, sesiones o colas dependientes de tenant;
- creación, actualización o eliminación de tenants;
- acceso desde Central hacia Tenant o viceversa;
- errores relacionados con aislamiento de datos.

## PRINCIPIO FUNDAMENTAL

El aislamiento entre tenants es CRÍTICO.

Nunca asumas que el contexto tenant está activo.

Nunca asumas que un modelo utiliza automáticamente la conexión correcta.

Nunca propongas una consulta que pueda mezclar datos de distintos tenants
sin comprobar primero cómo SSR implementa tenancy.

Antes de acceder o modificar datos determina explícitamente:

- contexto Central o Tenant;
- tenant actual;
- conexión utilizada;
- modelo involucrado;
- middleware o inicialización activa;
- riesgo de acceso cruzado.

## PROCEDIMIENTO

### 1. Comprender la operación

Determina:

- qué acción se quiere realizar;
- sobre qué datos;
- quién la ejecuta;
- desde qué contexto;
- qué tenant o conjunto de tenants participa.

No diseñes el acceso antes de responder estas preguntas.

### 2. Inspeccionar implementación real

Puedes consultar SSR Repository Analysis cuando corresponda.

Revisa únicamente lo necesario:

- configuración de tenancy;
- modelo Tenant;
- modelos Central y Tenant;
- middleware;
- rutas;
- providers;
- migrations;
- listeners;
- events;
- jobs;
- comandos;
- autenticación;
- servicios relacionados.

Identifica el mecanismo real utilizado por SSR.

### 3. Verificar paquete y versión

Cuando el comportamiento dependa de Tenancy for Laravel:

1. revisa `composer.json`;
2. identifica la versión bloqueada en `composer.lock` y contrástala con la instalada cuando corresponda;
3. identifica APIs realmente disponibles;
4. consulta documentación oficial compatible.

No mezcles ejemplos de versiones distintas.

### 4. Clasificar los datos

Para cada entidad involucrada determina si pertenece a:

- Central;
- Tenant;
- compartida explícitamente;
- otra conexión existente.

No clasifiques una entidad solo por su nombre.

Comprueba:

- modelo;
- conexión;
- migration;
- ubicación;
- consultas existentes;
- configuración real.

### 5. Verificar inicialización de tenancy

Antes de ejecutar lógica tenant comprueba cómo SSR determina e inicializa
el tenant.

Analiza cuando corresponda:

- dominio;
- subdominio;
- identificador;
- middleware;
- código manual de inicialización;
- eventos del paquete.

No asumas que una ruta está tenantizada únicamente porque su controlador
se encuentre bajo un namespace Tenant.

### 6. Modelos Tenant

Verifica:

- conexión;
- tabla;
- primary key;
- casts;
- relaciones;
- timestamps;
- SoftDeletes;
- scopes;
- atributos físicos;
- atributos dinámicos.

En el modelo Tenant distingue especialmente entre columnas físicas
y atributos almacenados en `data` u otro mecanismo dinámico.

No utilices en consultas SQL atributos dinámicos como si fueran columnas
físicas sin comprobar cómo están almacenados.

### 7. Consultas

Antes de proponer una consulta determina:

- conexión real;
- tenant activo;
- modelo;
- filtros;
- relaciones;
- posibilidad de acceso cruzado.

Evita consultas globales cuando deban estar limitadas a un tenant.

No utilices identificadores entregados por el cliente como única garantía
de pertenencia al tenant.

La pertenencia debe validarse mediante el contexto y las relaciones
reales del sistema.

### 8. Central hacia Tenant

Cuando código Central necesite operar sobre datos Tenant:

1. identifica tenant objetivo;
2. inicializa contexto mediante mecanismo compatible con SSR;
3. ejecuta únicamente la operación necesaria;
4. finaliza/restaura contexto correctamente;
5. evita mantener referencias o conexiones incorrectas entre tenants.

No recorras múltiples tenants indiscriminadamente si existe una alternativa
más segura o eficiente.

### 9. Tenant hacia Central

Cuando lógica Tenant necesite información Central:

- identifica si el acceso está permitido por diseño;
- utiliza mecanismo/conexión explícita;
- evita cambiar contexto innecesariamente;
- no expongas datos de otros tenants.

No crees relaciones Eloquent cross-database sin verificar soporte,
integridad y comportamiento real.

### 10. Jobs y colas

Los procesos asíncronos requieren atención especial.

Antes de crear o modificar un Job determina:

- desde qué contexto se despacha;
- cómo se conserva el tenant;
- cómo se inicializa al ejecutarse;
- qué ocurre si el tenant ya no existe;
- qué conexión utiliza el job.

No asumas que el worker conserva el contexto de la petición original.

Verifica el mecanismo real del paquete y de SSR.

### 11. Events y Listeners

Para eventos/listeners verifica:

- contexto en que se dispara el evento;
- contexto en que corre el listener;
- datos serializados;
- ejecución síncrona o en cola;
- posibilidad de perder contexto tenant.

No serialices objetos innecesarios si basta con identificadores seguros.

### 12. Autenticación y autorización

No confundas autenticación con aislamiento tenant.

Que un usuario esté autenticado no demuestra que tenga acceso al tenant
o recurso solicitado.

Verifica:

- usuario;
- tenant actual;
- membresía o relación real;
- permisos/roles;
- Policy/Gate cuando exista.

Toda autorización debe ejecutarse dentro del contexto correcto.

### 13. Migrations

Antes de crear una migration determina si corresponde a:

- Central;
- Tenant.

Comprueba la estructura real usada por SSR.

No coloques una migration basándote únicamente en convenciones genéricas
del paquete.

Considera tenants existentes y cómo se aplicará la migration a ellos.

Cuando corresponda coordina este análisis con SSR Database.

### 14. Creación y eliminación de tenants

Cuando una operación cree o elimine tenants evalúa:

- registro Central;
- base de datos;
- dominios;
- migrations;
- datos iniciales;
- archivos;
- jobs;
- rollback;
- errores parciales.

Las operaciones destructivas deben advertirse explícitamente.

Nunca elimines automáticamente datos tenant como solución a un error.

### 15. Cache, sesión y archivos

Cuando una funcionalidad utilice recursos compartidos evalúa si requieren
separación tenant:

- cache;
- session;
- filesystem;
- claves Redis;
- temporales;
- exports/imports.

No supongas separación automática.

Verifica la configuración real.

### 16. Seguridad

Considera como riesgo crítico:

- IDOR entre tenants;
- consultas sin contexto;
- tenant manipulable desde request;
- rutas Central accesibles desde Tenant;
- jobs sin tenant correcto;
- cache compartida incorrectamente;
- almacenamiento compartido no aislado;
- autorización ejecutada fuera del contexto correcto.

Si detectas riesgo de fuga de datos, indícalo antes de cualquier mejora
secundaria.

## PROPUESTA

Antes de implementar una modificación relevante presenta:

- contexto actual comprobado;
- Central/Tenant;
- flujo de inicialización;
- modelos y conexiones involucradas;
- riesgo de aislamiento;
- solución propuesta;
- archivos afectados;
- impacto en datos;
- tests necesarios.

No implementes cambios importantes antes de la aprobación requerida por
el `AGENTS.md`.

## TESTING

Toda funcionalidad crítica de tenancy debería verificar cuando corresponda:

- Tenant A puede acceder a sus datos;
- Tenant B puede acceder a los suyos;
- Tenant A NO puede acceder a datos de Tenant B;
- contexto Central continúa funcionando;
- acceso no autorizado es rechazado;
- jobs/listeners mantienen contexto correcto;
- ausencia de tenant se maneja de forma segura.

No consideres completa una solución de aislamiento solo porque funciona
para un único tenant.

## DOCUMENTACIÓN

Cuando el comportamiento dependa de Tenancy for Laravel consulta
documentación oficial compatible con la versión instalada.

El repositorio determina cómo SSR implementa tenancy.

La documentación determina cómo funciona el paquete.

Nunca reemplaces la inspección de SSR por ejemplos genéricos.

## RESULTADO ESPERADO

Antes de aprobar una funcionalidad multi-tenant debes poder responder:

1. ¿La operación ocurre en Central o Tenant?
2. ¿Cómo se identifica el tenant?
3. ¿Cómo se inicializa el contexto?
4. ¿Qué conexión utiliza cada modelo?
5. ¿Puede acceder accidentalmente a otro tenant?
6. ¿Cómo se autoriza el acceso?
7. ¿Jobs/eventos conservan contexto?
8. ¿Las migrations están en el contexto correcto?
9. ¿Existe impacto sobre tenants existentes?
10. ¿Cómo se prueba explícitamente el aislamiento?

Si alguna respuesta relevante no puede verificarse, continúa investigando
antes de implementar.
