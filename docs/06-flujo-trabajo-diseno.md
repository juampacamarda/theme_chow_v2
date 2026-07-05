# 🔄 Flujo de Trabajo: OpenDesign ↔ OpenCode

## Propósito

Este documento define el **protocolo exacto** para diseñar y rediseñar demos del Chow Theme usando dos herramientas especializadas:

| Herramienta | Rol | Qué hace |
|---|---|---|
| **OpenDesign** (o v0, Bolt, Lovable) | Diseñador UI | Genera HTML + CSS + JS a partir de un prompt descriptivo |
| **OpenCode** (este agente) | Implementador | Convierte el export de diseño en archivos reales del Chow Theme |

La separación existe porque **cada herramienta es mejor en lo suyo**: OpenDesign pega fuerte en lo visual, OpenCode en la estructura y lógica del theme.

---

## 📋 Índice

1. [Visión General: El Pipeline Completo](#1-visión-general-el-pipeline-completo)
2. [Fase 0: Setup Inicial](#2-fase-0-setup-inicial)
3. [Fase 1: Extraer Inspiración con DESIGN.md](#3-fase-1-extraer-inspiración-con-designmd)
4. [Fase 2: Construir el Prompt para OpenDesign](#4-fase-2-construir-el-prompt-para-opendesing)
5. [Fase 3: Enviar a OpenDesign e Iterar](#5-fase-3-enviar-a-opendesing-e-iterar)
6. [Fase 4: Implementar en el Chow Theme](#6-fase-4-implementar-en-el-chow-theme)
7. [Fase 5: Probar e Iterar](#7-fase-5-probar-e-iterar)
8. [Proceso DESIGN.md → Prompt (Detallado)](#8-proceso-designmd--prompt-detallado)
9. [Protocolo de Handoff (Qué esperar de cada lado)](#9-protocolo-de-handoff-qué-esperar-de-cada-lado)
10. [Casos de Uso Reales](#10-casos-de-uso-reales)

---

## 1. Visión General: El Pipeline Completo

```
FASE 0: SETUP
    │  Elegir identidad: rubro, colores, tipografía, productos
    │  (o re-diseñar una demo existente)
    ▼
FASE 1: INSPIRACIÓN (opcional)
    │  Navegar a sitios de referencia
    │  Extraer DESIGN.md con la extensión de Chrome
    │  Adjuntar DESIGN.md a este agente
    ▼
FASE 2: PROMPT
    │  Este agente procesa DESIGN.md + template base
    │  → Produce el prompt listo para OpenDesign
    ▼
FASE 3: DISEÑO
    │  Copiar prompt → OpenDesign / v0 / Bolt
    │  Iterar hasta que el diseño guste
    │  Exportar HTML + CSS + JS
    ▼
FASE 4: IMPLEMENTACIÓN
    │  Pegar el export aquí (o adjuntar archivo)
    │  Este agente sigue las instrucciones de 03-demo-redesign-prompt.md
    │  → Produce: demo-[nombre].php (+ demo-[nombre]-functions.php solo si necesita hooks)
    ▼
FASE 5: PRUEBA
    │  Subir archivos al theme
    │  Ir a Chow Theme > Importar Demo > Restaurar Plantilla
    │  Verificar en frontend
    ▼
FASE 6: ITERAR (si hace falta)
    │  Pedir ajustes → volver a Fase 3 o Fase 4
```

---

## 2. Fase 0: Setup Inicial

### Si es un diseño nuevo (demo desde cero)

Definir estos datos antes de empezar. Si no los tenés, los definimos juntos:

| Dato | Ejemplo | ¿Obligatorio? |
|---|---|---|
| Rubro / Tipo de negocio | Pastelería, Librería, Zapatería | Sí |
| Nombre del negocio | Harina & Miel | Sí |
| Paleta de colores (4 colores) | #d4a574, #f5e6d3, #5f4a42, #ffffff | Sí |
| Tipografía (headings + body) | Playfair Display + Lato | Sí |
| Productos (mínimo 6-8) | Nombre, precio, categoría, imagen | Sí |
| Personalidad de marca | Cálida y artesanal / Urbana y juvenil | Recomendado |
| Páginas necesarias | Inicio, Tienda, Sobre Nosotros, FAQ, Contacto | Sí |
| Cantidad de slides del slider | 2-5 | Recomendado |

### Si es un rediseño (demo existente)

Simplemente decinos qué demo querés rediseñar. Usamos los datos que ya existen en el archivo `demos/demo-[nombre].php`.

---

## 3. Fase 1: Extraer Inspiración con DESIGN.md

### ¿Qué es DESIGN.md?

Es un archivo Markdown generado por la extensión de Chrome **"DESIGN.md Generator"**. Escaneá cualquier sitio web y extrae automáticamente:

- **Paleta de colores** (con valores HEX)
- **Tipografía** (font families, tamaños, pesos)
- **Espaciados y medidas**
- **Componentes UI** (botones, cards, formularios)
- **Estilo visual general**

### ¿Para qué sirve en este flujo?

Para **alimentar de inspiración el prompt de OpenDesign**. Si ves un sitio que te gusta (una pastelería francesa, una librería minimalista, etc.), extraés su DESIGN.md y lo adjuntás. El agente lo procesa y lo fusiona con los datos de tu demo para generar un prompt más rico.

### Cómo hacerlo

1. Navegá al sitio que te sirve de inspiración (no tiene que ser de WooCommerce, puede ser cualquier sitio con buen diseño).
2. Hacé clic en la extensión **"DESIGN.md Generator"** → se descarga un archivo `DESIGN.md`.
3. En esta conversación, **adjuntá el archivo** o copiá su contenido y decí: *"Adjunto DESIGN.md de inspiración para una [pastelería/librería/etc]"*.
4. El agente lo procesa y lo incorpora al prompt.

### Si NO usás DESIGN.md

No hay problema. El agente puede construir el prompt igual con los datos de la Fase 0. El DESIGN.md es un **plus** para darle más dirección visual a OpenDesign.

---

## 4. Fase 2: Construir el Prompt para OpenDesign

### ¿Quién hace esto?

**Este agente (OpenCode)**. Vos solo tenés que pedirlo.

### ¿Cómo se hace?

El agente toma:
1. Los **datos de la demo** (de la Fase 0 o del archivo `demo-[nombre].php` existente)
2. El **DESIGN.md** (si lo adjuntaste)
3. El **template base** de `docs/03-demo-redesign-prompt.md`

Y produce un prompt completo y listo para copiar, con:
- Instrucciones de código obligatorias (sin Tailwind, clases `chow-*`, etc.)
- Los colores, tipografías y datos del negocio
- Los productos, categorías y páginas
- Las secciones del home
- La recomendación de card style según el rubro

### Cómo pedirlo

**Para una demo nueva:**
> "Preparame el prompt para OpenDesign de una [rubro] llamada [nombre]. Los colores son [X], tipografía [Y], [N] productos."

**Para un rediseño:**
> "Preparame el prompt para rediseñar la demo [nombre] en OpenDesign."

**Con DESIGN.md adjunto:**
> "Adjunto DESIGN.md. Preparame el prompt para OpenDesign de una [rubro] inspirada en esto."

### Qué recibís como respuesta

Un bloque de texto formateado listo para **copiar y pegar en OpenDesign**. Incluye:

```
Quiero diseñar/rediseñar la demo "[NOMBRE]" para el Chow Theme...

### 🚨 Instrucciones de código obligatorias
...
### Contexto del Chow Theme
...
### Datos de la Demo
...
### Lo que necesito
...
```

---

## 5. Fase 3: Enviar a OpenDesign e Iterar

1. Copiá el prompt que te dio el agente.
2. Pegalo en **OpenDesign** (o v0, Bolt, Lovable).
3. Revisá el resultado. Si algo no gusta, **iterá directamente en OpenDesign**:
   - *"Cambiá el color principal a #XXXXXX"*
   - *"Hacé el hero más alto"*
   - *"Cambiá las cards a estilo flip"*
4. Cuando el diseño esté OK → **Exportar**.
   - En OpenDesign: botón Export → HTML + CSS + JS.
   - En v0: botón Copy Code o Download.
   - En Bolt: exportar como archivo.

### Tips para mejores resultados en OpenDesign

- **Sé específico con las iteraciones**: en vez de "no me gusta", decí "cambiar fondo del hero a color secundario, reducir padding a 3rem".
- **Pedí que respete los componentes existentes**: si el prompt incluye la tabla de componentes, mencioná "no crear nuevo header, footer, slider, newsletter, redes — ya existen".
- **Una sola cosa por iteración**: pedí un cambio por vez para no confundir a la IA.
- **Usá referencias visuales**: si tenés una imagen de referencia, adjuntala.

---

## 6. Fase 4: Implementar en el Chow Theme

### ¿Quién hace esto?

**Este agente (OpenCode)**. Vos solo tenés que pasarnos el export de OpenDesign.

### Cómo pedirlo

> "Acá está el export de OpenDesign de la demo [nombre]. Implementalo."

Y adjuntás:
- El **HTML + CSS + JS** (como texto o archivo)
- **Capturas de pantalla** del diseño (opcional, ayuda a verificar)
- Cualquier **nota adicional** que quieras que respetemos

### Qué hace el agente

Sigue paso a paso las **"Instrucciones para el agente implementador"** de `docs/03-demo-redesign-prompt.md`:

| Paso | Acción | Archivos que produce/modifica |
|---|---|---|
| 1. Analizar | Revisa el export, identifica componentes CHOW-EXISTING y CHOW-NEW | — |
| 2. Mapear | Asigna cada sección a un template del Chow Theme | — |
| 3. CSS | Extrae estilos, convierte a `var(--chow_*)` | CSS inline en `demo-[nombre].php` |
| 4. Template | Crea template parts NUEVOS solo si es necesario | `home/demo-[nombre]/seccion.php` |
| 5. Demo | Arma el archivo de datos | `demos/demo-[nombre].php` |
| 6. Functions | Arma hooks específicos (opcional) | `demos/demo-[nombre]-functions.php` |

### Qué recibís como respuesta

El agente te entrega:
1. **`demos/demo-[nombre].php`** — Archivo de datos + CSS custom (obligatorio)
2. **`demos/demo-[nombre]-functions.php`** — Hooks específicos del demo (**opcional**, solo si el demo necesita comportamiento extra)
3. **`home/demo-[nombre]/`** (solo si creó template parts nuevos)
- Un resumen de lo que se creó y cómo instalarlo

---

## 7. Fase 5: Probar e Iterar

### Instalación

1. Copiá los archivos que te entregó el agente a las carpetas correspondientes del theme:
   - `demos/demo-[nombre].php` (obligatorio)
   - `demos/demo-[nombre]-functions.php` (opcional — si no existe no pasa nada, el loader lo ignora)
   - Si hay template parts nuevos: `home/demo-[nombre]/`
   - Si hay imágenes: `demos/[nombre]/images/`
2. Andá a **Chow Theme > Importar Demo** en el admin de WordPress.
3. Si es un demo nuevo → debería aparecer en la lista. Seleccioná **Importar**.
4. Si es una demo existente → hacé clic en **Restaurar Plantilla**.
5. Revisá el frontend: home, shop, páginas, productos.

### Iteraciones

Si hay que ajustar algo después de verlo en vivo:

**Ajustes visuales (colores, espaciados, tipografía):**
> "El hero se ve muy bajo. Aumentale la altura a 500px."
> → El agente edita el CSS custom en `demo-[nombre].php`.

**Ajustes de contenido (productos, textos, imágenes):**
> "Agregá un producto más: Mate Cocido Orgánico — Bebidas — $12.50"
> → El agente edita el array en `demo-[nombre].php`.

**Ajustes de layout (orden de secciones, columnas):**
> "Mové el newsletter antes del carrusel de productos."
> → El agente ajusta el array `home[sections]` o el functions.

Después de cada ajuste → volver a **Restaurar Plantilla** para ver los cambios.

---

## 8. Proceso DESIGN.md → Prompt (Detallado)

Esta sección explica **cómo este agente procesa un DESIGN.md** para generar el prompt de OpenDesign. Es la lógica interna; no necesitás hacer nada salvo adjuntar el archivo.

### 8.1 Estructura típica de un DESIGN.md

```markdown
# DESIGN.md
> Generated by DESIGN.md Generator

## Brand
- Name: [Nombre del sitio analizado]

## Colors
- Primary: #FF6B35
- Secondary: #004E89  
- Accent: #FFD700
- Text: #1A1A1A
- Background: #F5F5F5

## Typography
- Headings: Playfair Display (700, 2.5rem)
- Body: Lato (400, 1rem)
- Font URL: https://fonts.googleapis.com/...

## Spacing
- Base unit: 8px
- Section padding: 80px 0
- Container max-width: 1200px

## Components
### Buttons
- Border-radius: 50px
- Padding: 12px 32px
- Font-weight: 600

### Cards
- Border-radius: 12px
- Box-shadow: 0 4px 20px rgba(0,0,0,0.1)

### Navigation
- Height: 80px
- Sticky: true
- Background: white

## Imagery
- Style: clean, minimalist, warm lighting
- Photo style: flat lay, natural light

## Layout
- Grid columns: 12
- Breakpoints: mobile, tablet, desktop
```

No todos los DESIGN.md tienen exactamente esta estructura; varía según la extensión. El agente sabe adaptarse.

### 8.2 Mapping: DESIGN.md → Template Prompt

| Sección del DESIGN.md | Placeholder en el template | Qué hace el agente |
|---|---|---|
| `Brand.Name` | `[NOMBRE_NEGOCIO]` | Lo usa si no hay un nombre definido |
| `Colors.Primary` | `[COLOR_PRINCIPAL]` | → `--chow_ppal` |
| `Colors.Secondary` | `[COLOR_SECUNDARIO]` | → `--chow_secundario` |
| `Colors.Text` | `[COLOR_TEXTO]` | → `--chow_txt` |
| `Colors.Background` | `[COLOR_FONDO]` | → `--chow_blanco` |
| `Typography.Headings` | `[FUENTE_HEADINGS]` | Heading font family |
| `Typography.Body` | `[FUENTE_BODY]` | Body font family |
| `Components.Cards` | Card style recommendation | El agente analiza si las cards del DESIGN.md tienen bordes redondeados grandes (→ hover_visual), info densa (→ flip) o son simples (→ classic) |
| `Imagery.Style` | `[DESCRIPCION_ESTILO_VISUAL]` | Se combina con la personalidad de marca |
| `Layout / Spacing` | Secciones del home | Aporta la descripción de cómo estructurar el layout |

### 8.3 Reglas de fusión (cuando hay DESIGN.md + datos propios)

| Situación | Regla |
|---|---|
| El DESIGN.md tiene un color primary y vos también definiste uno | **Gana el tuyo** (los datos de la demo tienen prioridad) |
| El DESIGN.md tiene una tipografía que te gusta | La usamos como sugerencia; la podemos cambiar |
| El DESIGN.md no tiene ciertos datos (ej: tipografía) | Usamos defaults: Playfair Display + Lato |
| El DESIGN.md tiene componentes que no existen en el Chow Theme | Los anotamos como `CHOW-NEW` para que OpenDesign los diseñe y después el agente decide si crear template part o solo CSS |

### 8.4 Ejemplo: DESIGN.md → Prompt (Pastelería)

**DESIGN.md recibido** (de una pastelería francesa de referencia):
```yaml
Colors:
  Primary: "#c4956a"
  Secondary: "#faf0e6"
  Text: "#4a3729"
  Background: "#ffffff"
Typography:
  Headings: Cormorant Garamond
  Body: Nunito Sans
Imagery:
  Style: warm, natural light, rustic wood tables
```

**Datos de la demo** (definidos por el usuario):
- Nombre: Harina & Miel
- Productos: 8 productos de pastelería
- Rubro: Pastelería artesanal

**Prompt resultante** (lo que produce el agente):
- Colores: #d4a574, #f5e6d3, #5f4a42, #ffffff (mezcla entre inspiración y datos propios)
- Tipografía: Cormorant Garamond + Nunito Sans (de la inspiración)
- Estilo visual: "Fotografías cálidas con luz natural suave, fondos crema y madera clara..." (inspiración + personalidad de marca)
- Card style: hover_visual (porque pastelería es rubro visual)

---

## 9. Protocolo de Handoff (Qué esperar de cada lado)

### 9.1 Lo que vos (el usuario) me das a mí (OpenCode)

| Para | Qué necesito |
|---|---|
| **Crear prompt de diseño** | Los datos de la demo (rubro, colores, tipografía, productos) + opcional: DESIGN.md |
| **Rediseñar demo existente** | El nombre de la demo + opcional: DESIGN.md |
| **Implementar export** | El HTML + CSS + JS de OpenDesign + opcional: capturas de pantalla |
| **Ajustar demo implementada** | Descripción clara del cambio |

### 9.2 Lo que yo (OpenCode) te devuelvo a vos

| Para | Qué recibís |
|---|---|
| **Prompt de diseño** | Texto formateado listo para copiar a OpenDesign |
| **Implementación completa** | `demo-[nombre].php` (+ `demo-[nombre]-functions.php` opcional) + (si aplica) `home/demo-[nombre]/` + instrucciones de instalación |
| **Ajuste** | Archivos modificados + resumen del cambio |

### 9.3 Lo que vos (el usuario) le das a OpenDesign

| Qué | Formato |
|---|---|
| El prompt que armamos | Texto plano (copiar y pegar) |
| Imágenes de referencia (opcional) | Archivos adjuntos |
| Iteraciones | Texto en el chat de OpenDesign |

### 9.4 Lo que OpenDesign te devuelve a vos

| Qué | Formato |
|---|---|
| HTML + CSS + JS | Bloque de código o archivo descargable |
| Preview visual | Renderizado en el navegador |

---

## 10. Casos de Uso Reales

### Caso 1: Diseñar una demo nueva desde cero

1. *Usuario:* "Quiero crear una demo de zapatería urbana llamada 'Paso Firme'. Colores: negro, amarillo neón, gris."
2. *Agente:* Pide más datos (tipografía, productos, etc.) y prepara el prompt.
3. *Usuario:* Copia el prompt a OpenDesign.
4. *OpenDesign:* Genera el diseño.
5. *Usuario:* "El hero está muy oscuro, usá más amarillo neón." (itera en OpenDesign).
6. *Usuario:* Adjunta el export + screenshots.
7. *Agente:* Implementa: `demo-paso-firme.php` (+ `demo-paso-firme-functions.php` si necesita hooks).
8. *Usuario:* Sube los archivos, va a Chow Theme > Importar Demo, Restaurar Plantilla.
9. *Usuario:* "El footer quedó desordenado, los íconos de redes están uno abajo del otro."
10. *Agente:* Ajusta el CSS. Repite paso 8.

### Caso 2: Rediseñar una demo existente con inspiración externa

1. *Usuario:* "Rediseñame la demo de pastelería. Adjunto DESIGN.md de una pastelería parisina que me gusta."
2. *Agente:* Toma los datos de `demo-pasteleria.php`, los fusiona con el DESIGN.md, produce el prompt.
3. *Usuario:* Copia a OpenDesign. El diseño ahora tiene tintes parisinos pero con los mismos productos.
4. *Usuario:* Adjunta el export rediseñado.
5. *Agente:* Actualiza `demo-pasteleria.php` con el nuevo CSS y ajustes.

### Caso 3: Diseño exprés (sin inspiración, sin iteración)

1. *Usuario:* "Preparame el prompt para una academia de yoga. Colores: #7CB342, #F5F5F5, #333. Tipografía: Montserrat. 6 productos."
2. *Agente:* Produce el prompt en segundos.
3. *Usuario:* Va directo a OpenDesign, genera, exporta, vuelve.
4. *Agente:* Implementa en un solo ciclo.

---

## Apéndice A: Frases Útiles para cada Fase

| Si querés... | Decí... |
|---|---|
| Crear prompt desde cero | "Preparame el prompt para OpenDesign de una [rubro]." |
| Crear prompt con inspiración | "Acá va un DESIGN.md. Preparame el prompt para una demo de [rubro] inspirada en esto." |
| Rediseñar demo existente | "Rediseñame la demo [nombre]. Preparame el prompt." |
| Implementar export | "Acá está el export de OpenDesign. Implementalo." |
| Iterar diseño | "Llevá este cambio al prompt: [cambio]." |
| Ajustar demo implementada | "En la demo [nombre], cambá [X] por [Y]." |

## Apéndice B: Checklist de cada Fase

### Fase 0 - Setup
- [ ] Rubro definido
- [ ] Nombre del negocio
- [ ] Paleta de 4 colores (principal, secundario, texto, fondo)
- [ ] Tipografía (headings + body)
- [ ] Mínimo 6 productos con nombre, precio, categoría
- [ ] Páginas definidas (mínimo: Inicio, Tienda, FAQ, Contacto)

### Fase 1 - Inspiración (opcional)
- [ ] Sitio de referencia identificado
- [ ] DESIGN.md extraído con la extensión de Chrome
- [ ] Archivo adjunto en la conversación

### Fase 2 - Prompt
- [ ] Prompt recibido del agente
- [ ] Revisado y OK

### Fase 3 - OpenDesign
- [ ] Prompt pegado en OpenDesign
- [ ] Diseño generado
- [ ] Iteraciones aplicadas (si hicieron falta)
- [ ] Export descargado (HTML + CSS + JS)

### Fase 4 - Implementación
- [ ] Export + screenshots enviados al agente
- [ ] Archivos recibidos: `demo-[nombre].php` (+ `demo-[nombre]-functions.php` opcional)
- [ ] Archivos copiados al theme

### Fase 5 - Prueba
- [ ] "Restaurar Plantilla" ejecutado
- [ ] Home OK
- [ ] Shop OK
- [ ] Páginas internas OK
- [ ] Header y Footer OK

---

## Referencias

- [`docs/03-demo-redesign-prompt.md`](03-demo-redesign-prompt.md) — Template base para prompts e instrucciones de implementación
- [`demos/DEMO.md`](../demos/DEMO.md) — Guía de creación de demos (estructura de arrays, campos SCF)
- [`demos/demo-pasteleria.php`](../demos/demo-pasteleria.php) — Demo de referencia (496 líneas)
- [`demos/demo-pasteleria-functions.php`](../demos/demo-pasteleria-functions.php) — Functions de referencia (201 líneas)
