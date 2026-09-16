---
name: ssr-testing-verification
description: Diseña y ejecuta estrategias de testing y verificación para SSR, inspeccionando primero el framework de pruebas real, versiones, configuración y código afectado, con cobertura de regresiones, autenticación, autorización, base de datos, Livewire y aislamiento Central/Tenant.
---

# SSR Testing & Verification

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

Diseñar pruebas y verificaciones confiables para SSR basadas en el código real,
el comportamiento esperado y las herramientas de testing realmente instaladas.

Esta Skill debe demostrar que:

- una funcionalidad cumple su objetivo;
- una corrección resuelve realmente el defecto;
- no se introducen regresiones relevantes;
- se mantiene seguridad e integridad;
- se conserva el aislamiento Central/Tenant.

No reemplaza:

- SSR Repository Analysis;
- SSR Laravel;
- SSR Architecture;
- SSR Database;
- SSR Tenancy;
- SSR Debugging;
- SSR Livewire + PowerGrid.

Testing verifica. Las otras Skills determinan qué comportamiento debe existir
y cómo funciona SSR.

## CUÁNDO USAR ESTA SKILL

Úsala cuando sea necesario:

- crear tests;
- modificar tests existentes;
- verificar una corrección;
- comprobar una nueva funcionalidad;
- reproducir un bug mediante test;
- crear un test de regresión;
- comprobar autenticación/autorización;
- verificar aislamiento tenant;
- probar queries o persistencia;
- probar rutas/controladores;
- probar Livewire;
- verificar Jobs/Events/Listeners;
- comprobar cambios arquitectónicos;
- validar comportamiento después de un refactor;
- definir estrategia de pruebas antes de implementar.

No es necesario activarla para explicaciones puramente conceptuales.

## PRINCIPIO FUNDAMENTAL

No escribas tests genéricos para un proyecto Laravel imaginario.

Antes de crear o recomendar un test:

1. inspecciona SSR;
2. identifica el comportamiento real;
3. identifica el framework de testing instalado;
4. verifica versiones cuando la API dependa de ellas;
5. revisa tests existentes relacionados;
6. determina el contexto Central/Tenant;
7. diseña la prueba mínima que demuestre el comportamiento.

No asumas PHPUnit o Pest.

No asumas factories, seeders, traits, helpers ni clases base que no hayas
comprobado en SSR.

## 1. INSPECCIÓN INICIAL

Cuando corresponda puedes consultar SSR Repository Analysis.

Revisa:

- composer.json;
- composer.lock;
- phpunit.xml u otra configuración existente;
- tests/;
- TestCase;
- traits/helpers de testing;
- factories;
- seeders;
- configuración de entorno de test;
- código involucrado en la funcionalidad.

Identifica explícitamente:

COMPROBADO:
- framework de testing;
- versión;
- estructura existente;
- convenciones reales de SSR.

PROPUESTO:
- nuevos tests;
- nuevos helpers;
- nuevas factories;
- nueva infraestructura de testing.

Nunca presentes algo propuesto como existente.

## 2. IDENTIFICAR QUÉ SE DEBE DEMOSTRAR

Antes del código define el comportamiento verificable.

Evita objetivos vagos como:

"probar el módulo de usuarios".

Prefiere comportamientos concretos:

"un usuario tenant autenticado puede visualizar únicamente usuarios
pertenecientes a su tenant".

Cada test debe tener una razón verificable.

## 3. ELEGIR EL NIVEL DE PRUEBA

Selecciona el nivel mínimo capaz de demostrar el comportamiento.

Puede corresponder a:

- Unit;
- Feature;
- integración;
- HTTP;
- base de datos;
- Livewire;
- Job/Event;
- regresión.

No conviertas todo en Unit Tests.

No conviertas todo en Feature Tests.

La clasificación depende del comportamiento real.

## 4. TESTS DE REGRESIÓN

Cuando se corrige un bug, intenta reproducir primero el defecto mediante test
cuando sea razonable.

Flujo recomendado:

BUG OBSERVADO
→ TEST QUE REPRODUCE EL FALLO
→ confirmar que falla por la causa esperada
→ aplicar corrección
→ ejecutar nuevamente
→ confirmar que pasa
→ ejecutar pruebas relacionadas.

Un test que falla por otra razón no demuestra el bug.

No modifiques el test simplemente para hacerlo pasar si el comportamiento
esperado sigue siendo correcto.

## 5. ARRANGE → ACT → ASSERT

Mantén una estructura comprensible:

ARRANGE
- preparar estado mínimo necesario.

ACT
- ejecutar comportamiento real.

ASSERT
- comprobar resultado observable.

Evita tests que reproduzcan internamente la misma implementación que están
intentando verificar.

Prueba comportamiento, no detalles internos innecesarios.

## 6. TESTS HTTP / LARAVEL

Cuando pruebes un flujo Laravel verifica según corresponda:

- método HTTP;
- ruta;
- middleware;
- autenticación;
- guard;
- validación;
- autorización;
- redirect;
- response;
- session/errors;
- persistencia;
- eventos;
- efectos secundarios.

Utiliza APIs compatibles con la versión Laravel instalada.

Coordina con SSR Laravel cuando el comportamiento dependa del framework.

## 7. AUTENTICACIÓN

Antes de probar autenticación identifica:

- modelo de usuario;
- guard;
- provider;
- contexto;
- middleware.

No uses `actingAs()` suponiendo automáticamente el guard correcto.

Prueba cuando corresponda:

- usuario autenticado;
- usuario no autenticado;
- guard correcto;
- guard incorrecto si constituye un riesgo real.

## 8. AUTORIZACIÓN

Cuando exista autorización verifica tanto casos positivos como negativos.

Ejemplos:

- usuario autorizado puede ejecutar acción;
- usuario no autorizado recibe rechazo;
- usuario autenticado no implica automáticamente usuario autorizado;
- recurso ajeno no puede ser manipulado.

No inventes roles o permisos.

Utiliza únicamente roles/permisos existentes o claramente PROPUESTOS.

## 9. BASE DE DATOS

Cuando el test modifique datos determina:

- conexión;
- base Central/Tenant;
- estrategia de limpieza;
- transacciones;
- migrations;
- datos mínimos requeridos.

Puedes consultar SSR Database cuando corresponda.

Comprueba resultados mediante comportamiento observable y, cuando sea
adecuado, mediante assertions de base de datos.

No dependas exclusivamente de que no se haya lanzado una excepción.

## 10. SEGURIDAD DE LA BASE DE TEST

Antes de ejecutar cualquier comando que:

- migre;
- elimine;
- trunque;
- refresque;
- reconstruya;
- haga rollback;
- borre bases de datos,

confirma que el entorno corresponde realmente a TESTING.

Nunca ejecutes:

- migrate:fresh;
- db:wipe;
- RefreshDatabase;
- DatabaseMigrations;
- comandos equivalentes destructivos,

sobre una base compartida de desarrollo o producción como parte de estas
pruebas. Usa exclusivamente bases aisladas y desechables de pruebas.

No asumas que `.env.testing` existe ni que está correctamente configurado.

## 11. CENTRAL / TENANT

Todo test relacionado con tenancy debe identificar explícitamente:

- contexto inicial;
- tenant utilizado;
- conexión;
- modelo;
- inicialización;
- finalización del contexto;
- efecto esperado.

Puedes consultar SSR Tenancy para análisis especializado.

No basta con comprobar que Tenant A accede a sus datos.

Cuando exista riesgo de aislamiento, prueba también:

TENANT A
→ puede acceder a datos A

TENANT B
→ puede acceder a datos B

TENANT A
→ NO puede acceder a datos B.

Un test multi-tenant debe usar al menos dos tenants cuando el objetivo sea
demostrar aislamiento entre tenants.

## 12. CAMBIO DE CONTEXTO

Si un test cambia entre Central y Tenant:

- identifica conexión antes del cambio;
- inicializa contexto explícitamente según SSR;
- ejecuta comportamiento;
- finaliza contexto;
- comprueba que no persista estado incorrecto.

Evita tests cuyo resultado dependa del orden de ejecución.

## 13. LIVEWIRE

Cuando corresponda puedes consultar SSR Livewire + PowerGrid.

Antes de escribir tests Livewire verifica:

- versión instalada;
- API compatible;
- componente real;
- propiedades;
- eventos;
- acciones;
- validación;
- contexto tenant.

Prueba comportamiento observable.

Ejemplos posibles según implementación real:

- render;
- cambio de propiedades;
- validación;
- eventos;
- acciones;
- autorización;
- cambios persistidos.

Utiliza sintaxis compatible con la versión de Livewire realmente instalada,
aunque no sea la más reciente.

## 14. POWERGRID

PowerGrid puede requerir una combinación de:

- test del componente;
- test del datasource;
- test de query;
- verificación HTTP/Livewire;
- prueba manual en navegador.

No intentes demostrar mediante Unit Test aquello que depende realmente del
comportamiento frontend del componente.

Para datasource verifica cuando corresponda:

- registros incluidos;
- registros excluidos;
- scopes;
- tenant;
- relaciones;
- filtros;
- sorting;
- búsqueda;
- paginación.

No inventes una API de testing específica de PowerGrid sin verificarla.

## 15. EVENTS Y LISTENERS

Cuando un comportamiento dependa de Events verifica:

- evento emitido;
- momento correcto;
- payload relevante;
- Listener correspondiente;
- efecto esperado.

Utiliza fakes solamente cuando permitan demostrar correctamente el
comportamiento.

No uses `Event::fake()` indiscriminadamente si necesitas que listeners reales
se ejecuten para comprobar el flujo.

## 16. JOBS Y QUEUES

Cuando intervengan Jobs verifica según corresponda:

- dispatch;
- payload;
- queue;
- contexto tenant;
- ejecución;
- retries/idempotencia;
- efectos persistidos.

Distingue:

"el Job fue despachado"

de:

"el Job ejecutó correctamente su comportamiento".

Son pruebas diferentes.

## 17. TRANSACCIONES Y EFECTOS PARCIALES

Cuando una operación tenga múltiples etapas comprueba comportamiento ante
fallos intermedios cuando sea relevante.

Ejemplo conceptual:

ETAPA A
→ ETAPA B
→ ETAPA C falla

Verifica qué estado queda realmente.

Esto es especialmente importante en:

- provisioning de tenants;
- operaciones multi-conexión;
- Jobs;
- integraciones;
- procesos con efectos externos.

No supongas que `DB::transaction()` revierte operaciones externas o realizadas
sobre otras conexiones.

## 18. FACTORIES

Antes de utilizar una factory comprueba que exista y represente correctamente
el modelo actual.

No crees factories automáticamente para cada modelo.

Si una factory nueva mejora significativamente claridad y reutilización,
preséntala como PROPUESTA.

Evita factories con datos aleatorios que vuelvan el test no determinista.

## 19. SEEDERS

No ejecutes seeders completos por defecto.

Utiliza únicamente datos necesarios para demostrar el comportamiento.

Si el test depende de un seeder real, identifica:

- cuál;
- por qué;
- qué datos genera;
- impacto sobre aislamiento y determinismo.

## 20. DETERMINISMO

Los tests deben producir el mismo resultado independientemente de:

- orden;
- hora actual, salvo control explícito;
- datos previos;
- ejecución anterior;
- red;
- servicios externos;
- tenant utilizado anteriormente.

Controla el reloj cuando el comportamiento dependa de fechas; fija los datos
necesarios y simula los servicios externos. Restaura tiempo, fakes y contexto
tenant al finalizar, también cuando una prueba falle. No hagas que una prueba
dependa del estado dejado por otra.

## 21. EJECUCIÓN Y RESULTADOS

1. Identifica el comando real del proyecto y ejecuta primero las pruebas
   focalizadas en el comportamiento cambiado.
2. Antes de cualquier escritura, verifica que cada conexión central y tenant
   apunte a una base aislada y desechable de pruebas. `APP_ENV=testing` por sí
   solo no acredita el destino; no muestres credenciales al comprobarlo.
3. Usa fakes o mocks para impedir correo, pagos y emisiones DTE reales.
   Una llamada de integración requiere un entorno de pruebas verificado y
   autorización para sus efectos; no inventes que existe un sandbox.
4. Si falla una prueba, distingue fallo funcional, configuración del entorno
   y fallo preexistente. No cambies el comportamiento esperado para forzar éxito.
5. Amplía a pruebas relacionadas según el impacto; no repitas toda la suite
   si no hubo cambios ni evidencia que lo justifique.

Informa comando ejecutado, resultado y alcance. Si no se puede ejecutar,
explica el impedimento concreto y entrega la comprobación pendiente. Una
revisión estática o una prueba con mocks no demuestra una integración real.

No crees pruebas para cambios triviales que no protejan un comportamiento.
No amplíes la tarea a construir infraestructura de testing sin necesidad o
sin la autorización que corresponda según `AGENTS.md`.
