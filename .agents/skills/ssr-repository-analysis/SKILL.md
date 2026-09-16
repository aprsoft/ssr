---
name: ssr-repository-analysis
description: Inspecciona el repositorio SSR, identifica código, dependencias, versiones y flujo real antes de analizar, depurar o proponer modificaciones.
---

# SSR Repository Analysis

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

Inspeccionar sistemáticamente el proyecto SSR antes de analizar, explicar,
depurar o proponer modificaciones sobre su código.

Esta Skill complementa el `AGENTS.md` del proyecto y no sustituye
sus reglas.

Directorio principal: `/home/ro/Proyectos/ssr`.
Los archivos locales actuales, incluidos cambios sin commit, son la fuente primaria.

## CUÁNDO USAR ESTA SKILL

Úsala cuando una tarea requiera conocer o modificar la implementación real
de SSR, incluyendo:

- localizar funcionalidades;
- analizar código existente;
- investigar errores;
- modificar funcionalidades;
- crear funcionalidades relacionadas con código existente;
- analizar arquitectura;
- revisar modelos, relaciones, tablas, rutas o componentes;
- verificar dependencias o versiones;
- determinar cómo funciona actualmente una parte del sistema.

No es necesaria para preguntas conceptuales generales que no dependan
del código de SSR.

## PRINCIPIO FUNDAMENTAL

El repositorio real es la fuente primaria para determinar qué existe en SSR.

Nunca deduzcas la estructura del proyecto únicamente por convenciones
de Laravel.

No afirmes que una clase, método, tabla, columna, ruta, relación,
componente o configuración existe hasta haberlo comprobado.

Distingue siempre entre:

- COMPROBADO: observado en SSR.
- PROPUESTO: modificación o código nuevo.
- CONCEPTUAL: ejemplo que no pertenece necesariamente a SSR.

## PROCEDIMIENTO

### 1. Comprender la tarea

Identifica exactamente qué quiere analizar, corregir o implementar el usuario.

Determina qué parte del sistema podría estar involucrada sin asumir todavía
su implementación.

### 2. Inspeccionar SSR

Verifica la ruta de trabajo, la rama activa y `git status --short --branch`.
Inspecciona los diffs pertinentes y localiza los archivos directamente
relacionados con la tarea. No presupongas la rama ni consultes el remoto
automáticamente. Usa búsquedas acotadas, evitando dependencias generadas
salvo que sean necesarias para diagnosticar.

Amplía la inspección únicamente a archivos necesarios para comprender
dependencias y flujo.

Según el caso revisa:

- rutas;
- controladores;
- componentes Livewire;
- modelos;
- migrations;
- requests;
- policies;
- middleware;
- services/actions;
- views;
- configuración;
- providers;
- tests;
- código relacionado.

No revises archivos indiscriminadamente.

### 3. Reconstruir el flujo

Determina cómo funciona realmente la característica:

entrada
→ ruta/evento
→ componente/controlador
→ lógica
→ modelo/consulta
→ base de datos
→ respuesta/vista

Adapta este flujo a la implementación encontrada.

Identifica dependencias relevantes entre archivos.

### 4. Verificar dependencias

Cuando la solución dependa de un paquete o versión:

1. revisa `composer.json`;
2. identifica la versión bloqueada en `composer.lock`;
3. contrasta la versión instalada mediante metadatos locales o `composer show` cuando importe el entorno de ejecución;
4. utiliza únicamente APIs compatibles.

No deduzcas versiones por conocimiento previo.

### 5. Considerar Tenancy

Si la tarea involucra datos, modelos, autenticación, usuarios,
consultas o persistencia, determina si opera en contexto:

- central;
- tenant;
- ambos.

Comprueba conexiones, middleware y mecanismos relevantes antes de
proponer cambios.

Nunca asumas aislamiento correcto entre tenants.

### 6. Consultar documentación

Consulta documentación oficial cuando sea necesario confirmar:

- API;
- comportamiento;
- configuración;
- sintaxis;
- compatibilidad;
- diferencias entre versiones.

La documentación explica cómo funciona la tecnología.

El repositorio determina cómo SSR la utiliza.

No confundas ambas fuentes.

### 7. Diagnóstico

Antes de modificar código establece:

- qué existe actualmente;
- qué archivos intervienen;
- cómo funciona;
- cuál es el problema o necesidad;
- qué evidencia respalda el diagnóstico.

Si falta evidencia, continúa investigando antes de concluir.

### 8. Propuesta

Solo después de comprender la implementación actual propone cambios.

Indica:

- solución;
- motivo;
- archivos afectados;
- impacto;
- riesgos relevantes;
- alternativas cuando aporten valor.

Prefiere la modificación mínima que resuelva correctamente el problema.

## CAMBIOS LOCALES

Inspecciona directamente los cambios sin commit y los archivos nuevos relevantes.
Antes de editar, vuelve a leer los archivos si han cambiado durante el análisis.
Conserva cambios ajenos. Si hay una superposición incompatible, detén esa edición
y solicita una decisión concreta; continúa las partes independientes.

No pidas al usuario que copie archivos o diffs disponibles localmente.
El historial Git puede ayudar a comparar; no sustituye el estado del directorio
de trabajo ni autoriza operaciones de sincronización o descarte.

## SI NO PUEDES ACCEDER AL REPOSITORIO

No inventes su contenido.

Indica claramente qué información no pudo verificarse.

Solicita solamente los archivos o datos mínimos necesarios para continuar.

## RESULTADO ESPERADO

Después de aplicar esta Skill debes poder responder:

1. ¿Qué existe realmente en SSR?
2. ¿Qué archivos participan?
3. ¿Cómo funciona actualmente?
4. ¿Qué versiones relevantes utiliza?
5. ¿Existe impacto de tenancy?
6. ¿Cuál es la evidencia del diagnóstico?
7. ¿Qué debería modificarse y por qué?

No propongas una implementación importante hasta poder responder las
preguntas relevantes para la tarea.
