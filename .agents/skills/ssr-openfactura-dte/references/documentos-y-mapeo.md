# Documentos, contexto y mapeo DTE

Consulta solo las secciones pertinentes al documento o campo afectado.

# 1. IDENTIFICAR EL DOCUMENTO

Antes de construir o modificar cualquier payload determina:

- operación;
- DTE requerido;
- TipoDTE;
- afecto/exento cuando corresponda;
- emisor;
- receptor;
- detalle;
- totales;
- descuentos/recargos;
- referencias;
- impuestos;
- contexto Central/Tenant.

No seleccionar TipoDTE por intuición.

Verificarlo en documentación oficial SII.

No asumir que las reglas de un tipo de DTE son aplicables a otro.

---

# 2. INSPECCIONAR SSR

Puedes consultar SSR Repository Analysis.

Antes de proponer cambios inspecciona según corresponda:

- implementación DTE existente;
- factories/resolvers de DTE;
- mappers;
- transporte HTTP;
- modelos;
- migrations;
- Services;
- Actions;
- Jobs;
- Events;
- Controllers;
- Livewire;
- rutas;
- middleware;
- configuración;
- credenciales;
- clientes HTTP;
- tablas de documentos;
- estados;
- almacenamiento de respuestas;
- tests.

Reconstruye el flujo real antes de modificarlo.

No asumir que SSR carece de una implementación sin inspeccionarlo.

Distingue claramente lo existente de lo propuesto.

---

# 3. AUDITORÍA DE IMPLEMENTACIÓN EXISTENTE

Si SSR ya contiene un mapper, cliente, servicio o implementación DTE:

NO reemplazarla automáticamente.

Primero realizar una auditoría:

CÓDIGO SSR
→ CAMPO/OPERACIÓN
→ REGLA SII
→ CONTRATO OPENFACTURA
→ RESULTADO.

Clasificar cada elemento como:

CORRECTO
INCOMPATIBLE
DUDOSO
NO CONFIRMADO
NO APLICA.

No modificar constantes, campos, endpoints o estructuras solamente porque
parezcan extraños.

Primero demostrar por qué son correctos o incorrectos.

---

# 4. CONTEXTO TENANT

La facturación debe analizarse respecto del tenant emisor cuando el flujo sea
tenant.

Determina explícitamente:

- tenant actual;
- modelo y conexión;
- empresa emisora;
- RUT emisor;
- configuración tributaria;
- credenciales OpenFactura;
- sucursal cuando corresponda;
- documento;
- cliente;
- detalles;
- resultados de emisión;
- middleware/contexto que inicializa tenancy.

Nunca permitir que un tenant emita, consulte o manipule documentos de otro.

No asumir aislamiento únicamente porque los modelos pertenezcan conceptualmente
al contexto Tenant.

Reconstruir:

request
→ route
→ middleware
→ tenancy
→ auth
→ document
→ DTE
→ OpenFactura.

Puedes consultar SSR Tenancy cuando corresponda.

---

# 5. CREDENCIALES

Las credenciales OpenFactura son secretos.

Nunca:

- almacenarlas directamente en código;
- escribirlas en Git;
- mostrarlas completas en logs;
- incluirlas en excepciones;
- exponerlas al frontend;
- incluirlas en tests;
- devolverlas en respuestas HTTP.

Determina cómo SSR almacena actualmente la configuración sensible antes de
proponer modificaciones.

---

# 6. MAPEO SSR → OPENFACTURA → SII

Para cada dato relevante identifica:

CAMPO SSR
→ CAMPO OPENFACTURA
→ SIGNIFICADO/REGLA SII.

No asumir equivalencias solamente porque los nombres sean similares.

Verifica:

- tipo;
- formato;
- longitud;
- obligatoriedad;
- condición de uso;
- valores permitidos;
- cálculo;
- origen del dato.

No acoplar innecesariamente el modelo interno completo de SSR al formato
externo.

Si un mapper/DTO/transformador ya existe, utilizarlo como punto de análisis.

Solo proponer nuevas abstracciones cuando exista una necesidad demostrable.

---

# 7. ENCABEZADO

Cuando corresponda verificar según el DTE:

- TipoDTE;
- folio;
- fecha de emisión;
- emisor;
- receptor;
- sucursal;
- forma de pago;
- tipo de transacción;
- indicadores;
- totales;
- cualquier campo condicional.

No asumir que todos los DTE poseen los mismos campos ni obligatoriedad.

---

# 8. EMISOR

Los datos del emisor deben provenir del tenant/configuración real correspondiente.

No hardcodear:

- RUT;
- razón social;
- giro;
- actividad económica;
- dirección;
- comuna;
- sucursal;
- otros datos tributarios.

Verificar dónde obtiene SSR cada dato y contrastarlo con SII/OpenFactura.

---

# 9. RECEPTOR

Determinar los campos requeridos según:

- TipoDTE;
- operación;
- SII;
- OpenFactura.

No aplicar automáticamente reglas de boletas a facturas o viceversa.

No exigir campos que no correspondan.

No omitir campos condicionalmente obligatorios.

Verificar que el receptor pertenezca al documento y tenant correctos.

---

# 10. DETALLE

Para cada línea verificar según documentación vigente:

- número de línea;
- nombre/descripción;
- cantidad;
- unidad cuando corresponda;
- precio;
- descuentos;
- recargos;
- exención;
- impuestos adicionales cuando corresponda;
- monto de línea;
- límites de cantidad de líneas;
- longitudes;
- indicadores tributarios.

No reconstruir fórmulas tributarias desde memoria.

---

# 11. TOTALES

Los totales deben ser coherentes con:

- detalle;
- descuentos;
- recargos;
- neto;
- exento;
- IVA;
- impuestos adicionales;
- total final;

según el documento correspondiente.

No corregir silenciosamente diferencias numéricas.

Si SSR y OpenFactura calculan valores de forma diferente, identificar primero
la fuente de verdad aplicable.

---

# 12. BOLETAS

Para boletas utilizar como fuente base el documento oficial SII de Formato
Boletas Electrónicas y cualquier actualización oficial aplicable.

Verificar explícitamente el TipoDTE.

No generalizar sus reglas a facturas u otros documentos.

---

# 13. FACTURAS Y OTROS DTE

Para facturas, notas y demás documentos utilizar el formato DTE oficial
correspondiente.

No copiar automáticamente estructuras de:

boleta → factura
factura → nota de crédito
nota de crédito → nota de débito.

Cada documento debe analizarse según sus reglas.

---

# 14. REFERENCIAS

Cuando un documento requiera referencia verificar:

- documento referenciado;
- TipoDTE;
- folio;
- fecha;
- código de referencia;
- razón;
- obligatoriedad;
- reglas específicas.

Especial atención a:

- notas de crédito;
- notas de débito;
- anulaciones;
- correcciones.

No inventar códigos de referencia.

---

# 15. FOLIOS

No generar ni asignar folios arbitrariamente.

Determinar mediante documentación oficial:

- quién asigna el folio;
- cómo se solicita;
- qué valor debe enviar SSR;
- cómo responde OpenFactura;
- cómo se persiste;
- cómo se evita reutilización.

No asumir funcionamiento CAF, folio automático, `Folio = 0` o asignación local
sin verificar la integración vigente.

Si SSR ya utiliza una estrategia, auditarla antes de modificarla.

---

# 16. VALIDACIÓN PREVIA

Antes de enviar un DTE valida localmente aquello que pueda determinarse con
certeza:

- TipoDTE;
- campos obligatorios;
- tipos;
- formatos;
- longitudes;
- valores permitidos;
- relaciones;
- emisor;
- receptor;
- detalles;
- límites;
- montos;
- tenant;
- estado del documento;
- referencias cuando correspondan.

No duplicar ciegamente toda la validación del proveedor o SII.

La validación local debe prevenir errores determinables sin crear una
implementación paralela innecesaria del proveedor.

---

