---
name: ssr-architecture
description: Analiza y diseña cambios arquitectónicos en SSR, evaluando responsabilidades, dependencias, modularidad, seguridad, tenancy, rendimiento y mantenibilidad antes de implementar.
---

# SSR Architecture

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

Analizar y diseñar cambios en la arquitectura de SSR para mantener el sistema
coherente, seguro, mantenible y compatible con su implementación real.

Esta Skill complementa el `AGENTS.md` del proyecto.

No sustituye la inspección del código real. Cuando una decisión dependa de
cómo está implementado SSR, inspecciona los archivos locales relevantes; puedes apoyarte en
SSR Repository Analysis si necesitas su procedimiento.

## CUÁNDO USAR ESTA SKILL

Úsala cuando una tarea implique:

- crear módulos o funcionalidades relevantes;
- modificar responsabilidades entre componentes;
- decidir dónde debe residir lógica de negocio;
- introducir o evaluar Services, Actions, DTO, Jobs, Events u otros patrones;
- modificar flujos entre capas;
- integrar paquetes o servicios;
- refactorizar código significativo;
- evaluar acoplamiento o duplicación;
- tomar decisiones que afecten múltiples componentes;
- evaluar escalabilidad, mantenibilidad o extensibilidad.

No es necesaria para cambios triviales que no tengan impacto arquitectónico.

## PRINCIPIOS

### 1. Código real antes que arquitectura teórica

No diseñes SSR basándote únicamente en patrones genéricos de Laravel.

Primero comprende:

- estructura existente;
- responsabilidades actuales;
- convenciones del proyecto;
- dependencias;
- flujo de datos;
- tenancy cuando corresponda.

La arquitectura propuesta debe responder a necesidades reales del proyecto.

### 2. Simplicidad

Prefiere la solución más simple que satisfaga correctamente los requisitos.

No introduzcas capas o patrones solo porque sean considerados buenas prácticas.

Evita:

- abstracciones prematuras;
- interfaces con una sola implementación sin necesidad;
- Services que solo delegan una llamada;
- Repository innecesarios sobre Eloquent;
- DTO sin beneficio concreto;
- fragmentación excesiva de clases;
- patrones que aumenten complejidad sin resolver un problema real.

### 3. Responsabilidad

Determina qué componente debe poseer cada responsabilidad.

Evita:

- componentes Livewire con lógica de negocio excesiva;
- controladores responsables de múltiples procesos;
- modelos convertidos en contenedores indiscriminados de lógica;
- vistas realizando consultas o lógica de negocio;
- duplicación de reglas de negocio.

Extrae lógica cuando exista una razón concreta como reutilización,
complejidad, aislamiento, testing o reducción de acoplamiento.

### 4. Compatibilidad

Antes de cambiar arquitectura identifica qué funcionalidad existente
podría verse afectada.

Considera:

- llamadas existentes;
- rutas;
- componentes;
- modelos;
- relaciones;
- eventos;
- permisos;
- tests;
- integraciones;
- procesos central/tenant.

Prefiere cambios incrementales y reversibles frente a reestructuraciones
masivas.

## PROCEDIMIENTO

### 1. Definir el problema

Determina:

- qué se quiere conseguir;
- qué limitación existe actualmente;
- si realmente existe un problema arquitectónico;
- qué requisitos deben conservarse.

No propongas un patrón antes de comprender el problema.

### 2. Inspeccionar implementación actual

Cuando dependa del código existente, puedes consultar SSR Repository Analysis.

Identifica:

- componentes involucrados;
- responsabilidades;
- dependencias;
- flujo actual;
- persistencia;
- contexto central/tenant;
- convenciones existentes.

No continúes con una decisión importante basada en estructura supuesta.

### 3. Evaluar impacto

Analiza cuando corresponda:

- acoplamiento;
- cohesión;
- duplicación;
- seguridad;
- autorización;
- integridad de datos;
- tenancy;
- rendimiento;
- consultas;
- concurrencia;
- mantenibilidad;
- testabilidad;
- extensibilidad.

No todos los criterios deben aplicarse mecánicamente.

### 4. Diseñar alternativas

Si existe más de una solución razonable, presenta las alternativas relevantes.

Para cada una indica brevemente:

- ventajas;
- desventajas;
- complejidad;
- impacto sobre SSR.

Evita enumerar alternativas teóricas sin utilidad práctica.

### 5. Recomendar

Selecciona una solución y explica por qué es la más adecuada para SSR.

La recomendación debe basarse en evidencia del proyecto y no únicamente
en preferencias de diseño.

### 6. Definir cambio mínimo

Determina:

- archivos afectados;
- archivos nuevos si son necesarios;
- responsabilidades modificadas;
- dependencias nuevas;
- migraciones si existen;
- impacto central/tenant;
- tests necesarios.

No implementes todavía si el `AGENTS.md` exige aprobación previa.

### 7. Implementar

Después de la aprobación:

1. realiza cambios por etapas;
2. conserva compatibilidad cuando sea posible;
3. evita refactorizaciones ajenas al objetivo;
4. utiliza APIs compatibles con versiones instaladas;
5. mantiene las convenciones verificadas de SSR.

### 8. Verificar

Comprueba:

- funcionalidad requerida;
- regresiones;
- autorización;
- aislamiento tenant cuando corresponda;
- consultas y rendimiento relevantes;
- tests existentes y nuevos;
- coherencia arquitectónica.

## TENANCY

Toda decisión arquitectónica que involucre datos debe considerar explícitamente
la separación entre contexto central y tenant.

Antes de introducir servicios, jobs, eventos, modelos o procesos asíncronos,
determina cómo se conserva o inicializa el contexto de tenancy.

Nunca sacrifiques aislamiento entre tenants por simplificar arquitectura.

Cuando el problema requiera análisis especializado de tenancy, utiliza también
la Skill SSR Tenancy si está disponible.

## NUEVAS DEPENDENCIAS

No agregues paquetes Composer o dependencias externas sin justificar:

- problema que resuelven;
- necesidad real;
- compatibilidad;
- mantenimiento;
- impacto;
- alternativa usando capacidades ya existentes.

Prefiere capacidades del stack actual cuando resuelvan adecuadamente el problema.

## REFACTORIZACIÓN

No mezcles una nueva funcionalidad con refactorizaciones extensas no necesarias.

Si detectas deuda técnica relevante:

1. identifícala;
2. explica su impacto;
3. separa la recomendación del cambio solicitado cuando sea posible.

## RESULTADO ESPERADO

Antes de una modificación arquitectónica importante debes poder responder:

1. ¿Cómo funciona actualmente?
2. ¿Qué problema concreto queremos resolver?
3. ¿Qué componentes son responsables?
4. ¿Qué impacto tendrá el cambio?
5. ¿Existe riesgo para tenancy, seguridad o datos?
6. ¿Cuál es la solución más simple adecuada?
7. ¿Qué archivos deberán cambiar?
8. ¿Cómo verificaremos que no introdujo regresiones?

La arquitectura debe servir al proyecto, no el proyecto a la arquitectura.
