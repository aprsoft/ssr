---
name: ssr-laravel
description: Analiza y aplica correctamente Laravel en SSR según la versión instalada, revisando routing, middleware, container, providers, validación, autorización, jobs, events, queues, cache, filesystem, Artisan y configuración.
---

# SSR Laravel

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

Aplicar correctamente Laravel dentro de SSR según la versión instalada,
las convenciones reales del proyecto y la documentación oficial compatible.

Esta Skill complementa el `AGENTS.md` del proyecto.

No reemplaza:

- SSR Repository Analysis;
- SSR Architecture;
- SSR Database;
- SSR Tenancy;
- SSR Debugging;
- SSR Livewire + PowerGrid.

Úsala como capa especializada en el framework Laravel.

## CUÁNDO USAR ESTA SKILL

Úsala cuando una tarea implique:

- rutas;
- route model binding;
- middleware;
- requests;
- validación;
- controllers;
- Service Container;
- dependency injection;
- Service Providers;
- facades;
- configuración;
- Policies;
- Gates;
- autorización;
- Events;
- Listeners;
- Jobs;
- queues;
- notifications;
- mail;
- commands Artisan;
- scheduler;
- cache;
- filesystem;
- sessions;
- logging;
- lifecycle HTTP;
- bootstrap de Laravel;
- convenciones específicas del framework;
- compatibilidad entre versiones de Laravel.

## PRINCIPIO FUNDAMENTAL

No uses Laravel por memoria cuando el comportamiento pueda variar por versión.

Primero:

1. identifica la versión instalada;
2. inspecciona cómo SSR implementa la funcionalidad;
3. consulta documentación oficial compatible cuando corresponda.

El repositorio determina cómo SSR usa Laravel.

La documentación oficial determina cómo funciona Laravel.

## PROCEDIMIENTO

### 1. Comprender la tarea

Determina:

- qué comportamiento se necesita;
- qué capa Laravel participa;
- qué flujo actual existe;
- qué componentes están involucrados.

No propongas estructura antes de revisar SSR cuando la implementación actual
sea relevante.

### 2. Inspeccionar implementación real

Cuando corresponda puedes consultar SSR Repository Analysis.

Revisa únicamente lo necesario:

- routes;
- controllers;
- middleware;
- requests;
- models;
- policies;
- providers;
- services/actions existentes;
- events/listeners;
- jobs;
- commands;
- config;
- bootstrap;
- tests relacionados.

No asumas que SSR sigue exactamente la estructura de un proyecto Laravel nuevo.

### 3. Verificar versión

Cuando una API, configuración o comportamiento pueda depender de versión:

- revisa composer.json;
- identifica la versión bloqueada en composer.lock;
- comprueba la versión instalada de Laravel cuando analices su ejecución;
- consulta documentación oficial compatible.

No mezcles Laravel 10, 11, 12 o versiones futuras.

## ROUTING

Antes de crear o modificar una ruta verifica:

- archivo de rutas correcto;
- middleware aplicado;
- prefijo;
- dominio;
- nombre;
- parámetros;
- contexto Central/Tenant;
- controlador o componente destino.

Evita duplicación de nombres o rutas ambiguas.

No asumas que una ruta tenant pertenece al contexto tenant únicamente por
su URL.

## ROUTE MODEL BINDING

Antes de utilizar binding verifica:

- modelo correcto;
- clave de resolución;
- SoftDeletes;
- scopes;
- contexto de conexión;
- autorización.

En contexto multi-tenant asegúrate de que el binding no permita resolver
recursos pertenecientes a otro tenant.

## CONTROLLERS

Mantén controllers enfocados en coordinación HTTP.

Evita cuando sea posible:

- lógica de negocio extensa;
- queries complejas;
- múltiples responsabilidades;
- acceso cruzado Central/Tenant;
- validación manual duplicada.

No extraigas lógica a Services/Actions solo por preferencia.

Puedes consultar SSR Architecture cuando exista una decisión estructural real.

## REQUESTS Y VALIDACIÓN

Cuando la validación sea relevante verifica:

- Form Requests existentes;
- reglas;
- autorización del request;
- mensajes;
- normalización;
- validación condicional;
- existencia de recursos;
- pertenencia tenant.

No confíes únicamente en validación frontend.

Distingue:

- validación de entrada;
- autorización;
- integridad de base de datos.

No las mezcles.

## MIDDLEWARE

Antes de crear o modificar middleware identifica:

- responsabilidad;
- orden de ejecución;
- grupo;
- aliases;
- contexto HTTP;
- interacción con autenticación y tenancy.

No agregues middleware global si el problema corresponde a una ruta o grupo
específico.

En SSR verifica especialmente interacción entre middleware Central/Tenant.

## SERVICE CONTAINER

Utiliza el container cuando exista una necesidad real de resolución
de dependencias.

Antes de crear bindings determina:

- interfaz o clase;
- implementación;
- ciclo de vida;
- singleton/scoped/transient según API disponible;
- contexto tenant;
- estado compartido.

No agregues interfaces sin necesidad concreta.

Evita singletons con estado dependiente de tenant salvo que el diseño
garantice aislamiento correcto.

## DEPENDENCY INJECTION

Prefiere dependencias explícitas cuando mejoren claridad y testabilidad.

No utilices inyección para convertir clases simples en estructuras
innecesariamente complejas.

Comprueba cómo SSR resuelve actualmente las dependencias relacionadas.

## SERVICE PROVIDERS

Antes de modificar providers determina:

- qué se registra;
- qué se ejecuta en boot;
- cuándo se ejecuta;
- dependencia del entorno;
- impacto Central/Tenant.

Evita realizar operaciones costosas o dependientes de request durante
bootstrap si no corresponde.

No registres comportamiento global para resolver un problema local.

## CONFIGURACIÓN

Antes de usar valores configurables verifica:

- archivo config correspondiente;
- variable de entorno;
- valor por defecto;
- entorno;
- cache de configuración.

No utilices `env()` directamente fuera de archivos config salvo que exista
una razón compatible con Laravel.

No recomiendes limpiar cache automáticamente si no existe evidencia de
configuración cacheada obsoleta.

## AUTENTICACIÓN

Cuando intervenga autenticación identifica:

- guard;
- provider;
- modelo de usuario;
- middleware;
- contexto Central/Tenant.

No asumas que `auth()` utiliza el guard correcto.

Comprueba implementación SSR.

## POLICIES Y GATES

Distingue autenticación de autorización.

Antes de crear/modificar una Policy verifica:

- modelo;
- usuario;
- guard;
- relación con el recurso;
- tenant;
- permisos/roles;
- registro o autodiscovery compatible.

Nunca utilices únicamente IDs provenientes del request para autorizar acceso.

En recursos tenant valida pertenencia dentro del contexto correcto.

## EVENTS Y LISTENERS

Antes de utilizar eventos determina:

- productor;
- consumidor;
- propósito;
- sincronía;
- orden;
- datos enviados;
- contexto tenant.

No utilices Events para ocultar flujo crítico difícil de seguir.

Si un Listener se ejecuta en cola, evalúa pérdida o recuperación del contexto
tenant.

Coordina con SSR Tenancy cuando corresponda.

## JOBS Y QUEUES

Antes de crear/modificar Jobs determina:

- información serializada;
- conexión/queue;
- retries;
- timeout;
- idempotencia;
- errores;
- contexto tenant.

No serialices información innecesaria.

No asumas que el contexto HTTP o tenant permanece disponible en el worker.

Evita operaciones que puedan duplicarse peligrosamente durante retries.

## TRANSACTIONS

Cuando una operación Laravel modifique varios recursos relacionados evalúa
uso de transacciones.

No envíes efectos externos irreversibles dentro de una transacción sin
considerar rollback y consistencia.

Si intervienen datos complejos puedes consultar SSR Database.

## CACHE

Antes de usar cache determina:

- qué se almacena;
- duración;
- invalidación;
- scope;
- contexto tenant;
- riesgo de datos obsoletos.

En multi-tenancy las claves deben evitar colisiones entre tenants cuando
corresponda.

No agregues cache antes de demostrar necesidad.

No utilices cache para ocultar consultas incorrectas.

## FILESYSTEM

Antes de almacenar archivos determina:

- disk;
- ruta;
- visibilidad;
- permisos;
- nombre;
- eliminación;
- contexto tenant;
- exposición pública.

Evita confiar en nombres proporcionados directamente por usuarios.

En tenancy analiza separación de archivos entre tenants.

## SESSIONS

Cuando el comportamiento dependa de sesión verifica:

- driver;
- datos almacenados;
- autenticación;
- dominio/cookie;
- contexto Central/Tenant.

No almacenes datos sensibles o grandes objetos sin necesidad.

## NOTIFICATIONS Y MAIL

Antes de crear una notificación determina:

- destinatario;
- canal;
- datos;
- cola;
- tenant;
- URL generada;
- contexto de dominio.

Asegúrate de que links enviados desde contexto tenant apunten al dominio
correcto.

## ARTISAN COMMANDS

Antes de crear o modificar un comando determina:

- objetivo;
- argumentos/opciones;
- contexto;
- volumen;
- salida;
- errores;
- idempotencia;
- riesgo destructivo.

Para comandos que recorren tenants:

- no mezcles conexiones;
- inicializa/finaliza contexto correctamente;
- maneja fallos por tenant;
- evita detener todo el proceso por un error aislado sin evaluación.

## SCHEDULER

Cuando exista tarea programada determina:

- frecuencia;
- solapamiento;
- concurrencia;
- entorno;
- timezone;
- locks;
- contexto tenant;
- comportamiento ante fallo.

No programes procesos frecuentes sin considerar costo y duplicación.

## LOGGING

Utiliza logs como herramienta de observabilidad.

Evita registrar:

- passwords;
- tokens;
- secretos;
- datos sensibles innecesarios.

Incluye contexto útil cuando corresponda, por ejemplo identificadores
técnicos seguros.

No uses logging masivo como sustituto de diagnóstico.

## EXCEPTIONS

Cuando corresponda distingue entre:

- error esperado de dominio;
- error de validación;
- autorización;
- recurso inexistente;
- excepción técnica.

No captures `Throwable` indiscriminadamente solo para ocultar errores.

No conviertas excepciones reales en respuestas exitosas.

## ELOQUENT

Cuando la tarea sea principalmente de consultas, relaciones, migrations,
índices o persistencia puedes consultar SSR Database.

Esta Skill solo debe revisar aspectos Eloquent cuando estén relacionados
con comportamiento del framework.

No dupliques análisis especializado de base de datos.

## TENANCY

Cuando exista Central/Tenant puedes consultar SSR Tenancy.

Laravel aporta mecanismos de:

- routing;
- middleware;
- container;
- jobs;
- events;
- auth;
- cache;
- filesystem.

SSR Tenancy determina cómo deben aislarse esos mecanismos dentro del
proyecto multi-tenant.

## LIVEWIRE / POWERGRID

Cuando la tarea dependa específicamente de lifecycle Livewire, PowerGrid,
datasource, filtros, sorting o paginación puedes consultar SSR Livewire + PowerGrid.

No resuelvas APIs específicas de esos paquetes únicamente desde esta Skill.

## DEBUGGING

Cuando exista un error puedes consultar SSR Debugging.

Laravel Skill aporta conocimiento del framework.

Debugging determina el proceso de diagnóstico.

## IMPLEMENTACIÓN

Antes de cambios relevantes presenta:

- comportamiento actual;
- versión Laravel;
- evidencia;
- mecanismo Laravel involucrado;
- solución propuesta;
- archivos afectados;
- impacto;
- verificación.

Aplica el cambio mínimo compatible con SSR.

Evita refactors no relacionados.

## DOCUMENTACIÓN

Usa prioritariamente documentación oficial de Laravel compatible con la
versión instalada cuando la respuesta dependa de:

- API;
- lifecycle;
- configuración;
- comportamiento;
- compatibilidad;
- convenciones del framework.

No uses respuestas antiguas de Stack Overflow, blogs o snippets como
autoridad principal cuando la API pueda haber cambiado.

## RESULTADO ESPERADO

Antes de aprobar una modificación Laravel debes poder responder:

1. ¿Qué versión de Laravel utiliza SSR?
2. ¿Qué mecanismo del framework participa?
3. ¿Cómo lo implementa SSR actualmente?
4. ¿Qué ruta/middleware/guard/contexto interviene?
5. ¿Existe impacto Central/Tenant?
6. ¿La API utilizada es compatible con la versión instalada?
7. ¿La solución respeta las convenciones reales de SSR?
8. ¿Se está agregando complejidad innecesaria?
9. ¿Qué archivos se modifican?
10. ¿Cómo se verificará el comportamiento?

Laravel debe utilizarse como framework del proyecto, no como una colección
de patrones aplicados automáticamente.
