# 🎨 Prompt Base para Rediseño de Demos — Chow Theme

## Propósito

Este documento define un **prompt estructurado y reutilizable** para solicitar rediseños de demos del Chow Theme a herramientas de diseño generativo (v0, Bolt, Lovable, Open Design, etc.).

El prompt tiene dos caras:

1. **Prompt para la herramienta de IA de diseño** (v0, Bolt, Open Design): genera HTML/CSS/JS.
2. **Prompt para el agente implementador**: convierte ese output en archivos reales del Chow Theme (template parts, demo PHP, CSS custom).

---

## 📋 Instrucciones de código para la herramienta de diseño

**IMPORTANTE:** Incluí este bloque al inicio del prompt para la herramienta de IA. Define cómo debe generar el código para que sea integrable con el Chow Theme sin fricción.

```
### 🚨 Instrucciones de código obligatorias

No uses Tailwind CSS, no uses componentes de UI libraries (shadcn, Material UI, Bootstrap components JS, etc.). 
Usá únicamente:
- **Grid**: clases de Bootstrap 5 (`container`, `row`, `col-*`, `d-flex`, `justify-content-*`, `align-items-*`, `gap-*`). No uses CSS Grid de Bootstrap ni otras librerías de grid.
- **CSS**: CSS vanilla, plano, sin preprocesadores. Archivo único o separado por secciones con comentarios.
- **JS**: JavaScript vanilla. Sin jQuery (el theme ya lo carga, pero no dependas de él para funcionalidad crítica). Sin frameworks (React, Vue, etc.).
- **Tipografía**: Google Fonts via `@import` en CSS.

### 🚨 Regla de líneas de código

Mantené el código lo más mínimo posible. Si algo se puede resolver en 3 líneas de CSS, no escribas 20. Preferí selectores simples y propiedades shorthand. 
- No agregues estilos redundantes (ej: si Bootstrap ya pone `display: flex` con `d-flex`, no lo repitas en CSS).
- No generes animaciones complejas si el componente funciona bien con transiciones simples de 300ms.

### 🚨 Nomenclatura de clases

Usá el prefijo `chow-` para todas las clases CSS custom que crees.
Ejemplo: `.chow-hero-title`, `.chow-feature-card`, `.chow-section-about`.
Esto evita colisiones con Bootstrap y con plugins de WordPress.

### 🚨 Referencia de componentes existentes del Chow Theme

Antes de crear un componente desde cero, revisá si ya existe en el theme. Si existe, usalo y solo ajustá con CSS custom. Si no existe, recién ahí crealo.

#### Componentes disponibles (NO crear desde cero):

| Componente | Archivo | Clases CSS | Descripción | Cuándo se usa |
|---|---|---|---|---|
| **Card Classic** | `woocommerce/loop/card-classic.php` | `.chow-product-card-01` | Imagen, título, precio, botón "Agregar al carrito". Layout vertical. | Grid de productos estándar, catálogo simple |
| **Card Hover Visual** | `woocommerce/loop/card-hover_visual.php` | `.chow-product-card-02` | Imagen full-bleed, overlay oscuro al hover con título, precio y botón. Escala 1.03x. | Productos visuales (pastelería, decoración, moda) |
| **Card Flip** | `woocommerce/loop/card-flip.php` | `.chow-product-card-03` | Card 3D con rotación Y 180°. Frente: imagen+título overlay. Dorso: nombre, categorías, extracto, precio, botones. | Catálogos interactivos, productos con mucha info |
| **Slider Home** | `home/slide.php` | `.chow-home-carousel` | Carrusel Bootstrap full-width con overlay de texto y CTA. 5 slides máx. | Hero de homepage |
| **Bloque Productos Grid** | `home/productos-1.php` | `.productos-section` | Grid de productos con título, descripción y columnas configurables. | Secciones de productos en home |
| **Bloque Productos Carrusel** | `home/productos-1.php` (layout carousel) | `.productos-carousel` | Owl Carousel de productos. | Carrusel de recomendados/destacados |
| **Carrusel de imágenes** | `home/carrusel.php` | `#slide-prod` | Carrusel de imágenes con título y link. | Tentaciones, destacados visuales |
| **Newsletter** | `home/news.php` | `#suscripcion-news` | Sección con fondo de imagen, overlay y formulario CF7 inline. | Captación de suscriptores |
| **Redes Sociales** | `home/redes.php` | `#seccion-redes` | Sección con fondo, título e íconos de redes. | Footer social |
| **Sección Clientes/Partners** | `home/clientes.php` | `#clientes-botonera` | Carrusel Owl de logos de clientes. | Páginas institucionales |
| **Sección Blog** | `home/blog.php` | `#posts-home` | Grid de posts con thumbnail, título y extracto. | Homepages con blog |
| **Flexible Page** | `flexible-page.php` | — | Template con header de portada, contenido WYSIWYG y accordion FAQ. | Páginas internas (About, FAQ, Contacto) |
| **Header** | `header.php` | — | Topbar con logo, redes, carrito + navbar con menú WP. | Todas las páginas |
| **Footer** | `footer.php` | — | 3 columnas: logo+datos, menú footer, logos legales. | Todas las páginas |

#### Estilos de card: guía de selección

| Rubro | Card recomendada | Motivo |
|---|---|---|
| Pastelería, decoración, regalos | **hover_visual** | Lo visual vende; el overlay al hover es elegante |
| Librería, tecnología, hogar | **classic** | La info es importante; el usuario necesita ver precio y botón siempre |
| Moda, indumentaria | **hover_visual** o **flip** | La imagen domina; el flip da info extra sin cambiar de página |
| Electrónica, equipamiento | **flip** | Mucha información en poco espacio; el dorso muestra specs |
| Productos básicos, commodites | **classic** | Simple, rápido, directo |

#### Variables CSS del theme (usar SIEMPRE en lugar de colores fijos):

```css
:root {
    --chow_ppal: #D60B52;    /* Color principal (se sobreescribe por demo) */
    --chow_secundario: #1d71b8; /* Color secundario */
    --chow_txt: #5f5f5f;      /* Color de texto */
    --chow_blanco: #ffffff;   /* Color de fondo */
}
```

No definas colores hardcodeados en CSS. Usá `var(--chow_ppal)`, `var(--chow_secundario)`, etc. Esto permite que el theme los controle dinámicamente.

#### Dimensiones de imágenes de referencia:

| Elemento | Ratio | Tamaño sugerido |
|---|---|---|
| Slider hero | 16:9 | 1920×1080 |
| Producto (grid/carrusel) | 3:4 | 900×1200 |
| Banner flexible page | 21:9 | 1920×820 |
| Fondo newsletter | 16:9 | 1920×1080 |
| Fondo redes | 16:9 | 1920×1080 |
| Logo desktop | variable | ~400×120 |
| Logo mobile/sticker | 1:1 | ~200×200 |
| Cover (tarjeta demo) | 16:9 | 1280×720 |

```
---

## Template de Prompt Base

Copiá el bloque completo — incluye las instrucciones de código de arriba — y reemplazá los placeholders `[ENTRE_CORCHETES]` con los datos de tu demo.

```
Quiero rediseñar la demo "[NOMBRE_DE_LA_DEMO]" para el Chow Theme, un theme de WordPress + WooCommerce para e-commerce.

### 🚨 Instrucciones de código obligatorias

No uses Tailwind CSS, no uses componentes de UI libraries (shadcn, Material UI, Bootstrap components JS, etc.). 
Usá únicamente:
- **Grid**: clases de Bootstrap 5 (`container`, `row`, `col-*`, `d-flex`, `justify-content-*`, `align-items-*`, `gap-*`).
- **CSS**: CSS vanilla, plano, sin preprocesadores. Archivo único o separado por secciones con comentarios.
- **JS**: JavaScript vanilla. Sin jQuery. Sin frameworks (React, Vue, etc.).
- **Tipografía**: Google Fonts via `@import` en CSS.

Mantené el código mínimo. Si algo se resuelve en 3 líneas de CSS, no escribas 20. Preferí selectores simples y propiedades shorthand.

Usá el prefijo `chow-` para todas las clases CSS custom que crees (ej: `.chow-hero-title`, `.chow-feature-card`).

### 🚨 Componentes existentes (NO crear desde cero)

El Chow Theme ya tiene estos componentes. Reutilizalos y ajustalos con CSS:

| Componente | Clases/ID | Descripción |
|---|---|---|
| Card producto classic | `.chow-product-card-01` | Imagen, título, precio, botón "Agregar al carrito" |
| Card producto hover_visual | `.chow-product-card-02` | Imagen full-bleed + overlay hover con título/precio/botón |
| Card producto flip | `.chow-product-card-03` | Card 3D, frente imagen, dorso info completa |
| Slider home | `.chow-home-carousel` | Bootstrap carousel full-width con overlay texto + CTA |
| Bloque productos grid | `.productos-section` | Grid de productos con título y columnas |
| Newsletter | `#suscripcion-news` | Sección con fondo imagen, overlay, formulario CF7 |
| Redes sociales | `#seccion-redes` | Sección con fondo, título, íconos de redes |
| Header | `header.php` | Topbar (logo, redes, carrito) + navbar |
| Footer | `footer.php` | 3 columnas (logo+datos, menú, logos legales) |

### Variables CSS del theme (usar SIEMPRE):

```css
:root {
    --chow_ppal: [COLOR_PRINCIPAL];
    --chow_secundario: [COLOR_SECUNDARIO];
    --chow_txt: [COLOR_TEXTO];
    --chow_blanco: [COLOR_FONDO];
}
```

No hardcodees colores. Usá `var(--chow_ppal)`, `var(--chow_secundario)`, etc.

## Contexto del Chow Theme

El Chow Theme es un theme para WooCommerce con las siguientes características técnicas:
- Usa Bootstrap 5 como framework CSS base (solo grid y utilidades).
- Las tarjetas de producto tienen tres estilos (ver tabla arriba).
- El slider del home usa Bootstrap Carousel.
- Las páginas internas usan un template "flexible-page" con: header con imagen de portada, contenido WYSIWYG, y accordion FAQ.
- La homepage se arma por secciones: slider, bloques de productos (grid o carousel), newsletter, carrusel de imágenes, y sección de redes sociales.
- Los formularios son Contact Form 7.
- El menú de navegación es un menú de WordPress estándar con ubicación "superior".
- CSS custom se inyecta inline en el <head> (sin archivos extra) y usa las variables CSS del theme.
- Las cards de producto se renderizan con `chow_load_product_card()` que carga el template correcto según el estilo configurado.

## Datos de la Demo Actual

### Identidad
- **Nombre del negocio:** [NOMBRE_NEGOCIO]
- **Rubro:** [RUBRO]
- **Descripción:** [DESCRIPCION_CORTA]
- **Público objetivo:** [PUBLICO_OBJETIVO]
- **Personalidad de marca:** [PERSONALIDAD_MARCA] (ej: elegante y minimalista / cálida y artesanal / urbana y juvenil)

### Paleta de Colores
- **Color principal:** [COLOR_PRINCIPAL]
- **Color secundario:** [COLOR_SECUNDARIO]
- **Color de texto:** [COLOR_TEXTO]
- **Color de fondo:** [COLOR_FONDO]

### Tipografía deseada
- **Headings:** [FUENTE_HEADINGS] (ej: Playfair Display, Cormorant Garamond, Montserrat)
- **Body:** [FUENTE_BODY] (ej: Lato, Nunito Sans, Open Sans, Inter)

### Estilo visual general
[DESCRIPCION_ESTILO_VISUAL]
(Ej: "Fotografías cálidas con luz natural suave, fondos crema y madera clara, estética artesanal pero prolija, detalles en dorado.")

### Productos ([N] productos)
[Listar los productos con nombre, categoría, precio, y si es destacado/en oferta]

Ejemplo:
1. Croissant de Mantequilla — Pastas — $8.50 (destacado)
2. Lemon Pie Casero — Tortas — $25.99 (destacado, oferta $19.99)

### Categorías
[Listar categorías con nombre y descripción]

### Páginas
- **Inicio** (home con slider, productos, newsletter, redes, carrusel)
- **Sobre Nosotros** (historia de la marca)
- **Preguntas Frecuentes** (FAQ con accordion)
- **Contacto** (formulario + datos de contacto)

### Secciones del Home
- **Slider principal:** [N] slides con imágenes hero y calls-to-action
- **Bloque de productos destacados:** grid de [N] columnas con [N] productos
- **Carrusel de productos:** carrusel con [N] productos
- **Newsletter:** sección con fondo, título, descripción y formulario de suscripción
- **Redes sociales:** sección con fondo, título, descripción e íconos de redes

### Imágenes disponibles
[Listar las imágenes que existen en la carpeta de la demo, con su propósito]

Ejemplo:
- `hero-01.webp` — Slider 1: interior de pastelería
- `producto-01.webp` a `producto-08.webp` — Fotos de productos

## Lo que necesito

Rediseñame la UI completa de esta demo con las siguientes páginas:

1. **Homepage** — Con slider hero, grid de productos destacados, carrusel de productos tentación, sección newsletter, y sección de redes sociales.
2. **Página de Tienda (Shop)** — Grid de productos con filtros por categoría.
3. **Página de Producto Individual** — Con galería de imágenes, descripción, precio, botón de compra, y productos relacionados.
4. **Página Sobre Nosotros** — Con header con imagen de portada, contenido institucional.
5. **Página de Preguntas Frecuentes** — Con header y accordion de preguntas.
6. **Página de Contacto** — Con formulario y datos de contacto.
7. **Header y Footer** — Header sticky con logo, menú, carrito contador. Footer con logo, datos, enlaces, redes.

## Requisitos técnicos

- **Mobile-first responsive** (breakpoints: 576px, 768px, 992px, 1200px) — solo donde sea necesario.
- **Grid con Bootstrap 5** — clases como `container`, `row`, `col-*`, `d-flex`, etc.
- **CSS custom** usando exclusivamente las variables `var(--chow_ppal)`, `var(--chow_secundario)`, `var(--chow_txt)`, `var(--chow_blanco)`.
- **Tarjetas de producto** existentes del theme: classic, hover_visual o flip (ver tabla de componentes).
- **Slider** Bootstrap Carousel full-width con overlay de texto y botón CTA.
- **Newsletter** con fondo de imagen, overlay semi-transparente, formulario email + botón.
- **Redes sociales** con fondo de imagen o color sólido e íconos.
- **FAQ** con accordion de Bootstrap (clases `accordion`, `accordion-item`, etc.).
- **Header** sticky con logo, menú de navegación, ícono de carrito con contador badge.
- **Footer** con 3 zonas: logo + datos de contacto, menú de enlaces, logos legales/redes.
- **Transiciones suaves** (hover 300ms ease).
- **Accesibilidad básica** (contraste de texto mínimo 4.5:1, focus visible, labels en formularios, ARIA attributes donde aplique).
- **Sin Tailwind, sin Material UI, sin shadcn, sin React/Vue/Svelte.**

## Entregable esperado

- HTML semántico con clases de Bootstrap 5.
- CSS organizado por secciones (estilos globales, header, footer, home, shop, product, pages).
- JavaScript vanilla para interacciones (slider, accordion, mobile menu, contador de carrito).
- Las imágenes son placeholders con las dimensiones correctas (usar `https://placehold.co/ANCHOxALTO/COLOR/COLOR?text=DESCRIPCION`).
- Comentarios en el código indicando dónde se conecta con WordPress/WooCommerce (ej: `<!-- WP: loop de productos -->`).
- Marcá con comentarios `<!-- CHOW-EXISTING: componente -->` cuando uses un componente existente del theme, y `<!-- CHOW-NEW: componente -->` cuando crees uno nuevo.
```

---

## Ejemplo Concreto: Demo Pastelería "Harina & Miel"

Este es el prompt ya rellenado con los datos reales de la demo de pastelería, listo para copiar y pegar:

```
Quiero rediseñar la demo "Harina & Miel" para el Chow Theme, un theme de WordPress + WooCommerce para e-commerce.

### 🚨 Instrucciones de código obligatorias

No uses Tailwind CSS, no uses componentes de UI libraries (shadcn, Material UI, Bootstrap components JS, etc.). 
Usá únicamente:
- **Grid**: clases de Bootstrap 5 (`container`, `row`, `col-*`, `d-flex`, `justify-content-*`, `align-items-*`, `gap-*`).
- **CSS**: CSS vanilla, plano, sin preprocesadores. Archivo único o separado por secciones con comentarios.
- **JS**: JavaScript vanilla. Sin jQuery. Sin frameworks (React, Vue, etc.).
- **Tipografía**: Google Fonts via `@import` en CSS.

Mantené el código mínimo. Si algo se resuelve en 3 líneas de CSS, no escribas 20. Preferí selectores simples y propiedades shorthand.

Usá el prefijo `chow-` para todas las clases CSS custom que crees (ej: `.chow-hero-title`, `.chow-feature-card`).

### 🚨 Componentes existentes (NO crear desde cero)

El Chow Theme ya tiene estos componentes. Reutilizalos y ajustalos con CSS:

| Componente | Clases/ID | Descripción |
|---|---|---|
| Card producto classic | `.chow-product-card-01` | Imagen, título, precio, botón "Agregar al carrito" |
| Card producto hover_visual | `.chow-product-card-02` | Imagen full-bleed + overlay hover con título/precio/botón |
| Card producto flip | `.chow-product-card-03` | Card 3D, frente imagen, dorso info completa |
| Slider home | `.chow-home-carousel` | Bootstrap carousel full-width con overlay texto + CTA |
| Bloque productos grid | `.productos-section` | Grid de productos con título y columnas |
| Newsletter | `#suscripcion-news` | Sección con fondo imagen, overlay, formulario CF7 |
| Redes sociales | `#seccion-redes` | Sección con fondo, título, íconos de redes |
| Header | `header.php` | Topbar (logo, redes, carrito) + navbar |
| Footer | `footer.php` | 3 columnas (logo+datos, menú, logos legales) |

## Contexto del Chow Theme

El Chow Theme es un theme para WooCommerce con las siguientes características técnicas:
- Usa Bootstrap 5 como framework CSS base (solo grid y utilidades).
- Las tarjetas de producto tienen tres estilos (ver tabla arriba).
- El slider del home usa Bootstrap Carousel.
- Las páginas internas usan un template "flexible-page" con: header con imagen de portada, contenido WYSIWYG, y accordion FAQ.
- La homepage se arma por secciones: slider, bloques de productos (grid o carousel), newsletter, carrusel de imágenes, y sección de redes sociales.
- Los formularios son Contact Form 7.
- El menú de navegación es un menú de WordPress estándar con ubicación "superior".
- CSS custom se inyecta inline en el <head> (sin archivos extra) y usa las variables CSS del theme.
- Las cards de producto se renderizan con `chow_load_product_card()` que carga el template correcto según el estilo configurado.

## Datos de la Demo

### Identidad
- **Nombre del negocio:** Harina & Miel
- **Rubro:** Pastelería artesanal
- **Descripción:** Pastelería artesanal con productos premium, tortas personalizadas y servicio de catering para eventos.
- **Público objetivo:** Mujeres y hombres 25-50 años, amantes de la repostería de calidad.
- **Personalidad de marca:** Cálida, artesanal, delicada, premium pero accesible. Tonos crema, rosa pálido y dorado.

### Paleta de Colores
- **Color principal:** #d4a574
- **Color secundario:** #f5e6d3
- **Color de texto:** #5f4a42
- **Color de fondo:** #ffffff

### Variables CSS
```css
:root {
    --chow_ppal: #d4a574;
    --chow_secundario: #f5e6d3;
    --chow_txt: #5f4a42;
    --chow_blanco: #ffffff;
}
```

### Tipografía
- **Headings:** Playfair Display (serif elegante)
- **Body:** Lato (sans-serif limpia)

### Estilo visual
Fotografías cálidas con luz natural suave, fondos crema y madera clara, detalles en dorado/rosa pálido. Estética artesanal, casera pero prolija.

### Productos (8)
1. Croissant de Mantequilla — Pastas — $8.50 (destacado)
2. Lemon Pie Casero — Tortas — $25.99 (destacado, oferta $19.99)
3. Torta Red Velvet Lujo — Tortas — $45.50
4. Torta Chocolate Intenso — Tortas — $52.99 (destacado, oferta $39.99)
5. Tortitas Mendocinas Tradicionales — Pastas — $15.00 (destacado, bestseller)
6. Chipa Paraguaya Receta Original — Pastas — $5.50 (destacado)
7. Sanguchitos de Miga Surtidos — Pastas — $28.00
8. Tarta de Frutas de Estación — Tortas — $35.50 (destacado)

### Categorías
- **Tortas** — Deliciosas tortas para todas las ocasiones
- **Pastas** — Pastas y postres artesanales

### Páginas y Secciones
- **Slider principal:** 2 slides "Delicias Artesanales" y "Tortas Personalizadas"
- **Productos destacados:** grid de 3 columnas con 6 productos
- **Carrusel:** "Nuestras Tentaciones" con 5 imágenes
- **Newsletter:** fondo pasteleria-newsbg.webp
- **Redes:** fondo pasteleria-redesbg.webp, íconos Instagram, Facebook, WhatsApp
- **Sobre Nosotros:** flexible-page con cover
- **FAQ:** 6 collapses (envíos, personalizados, sin gluten, devoluciones, catering, contacto)
- **Contacto:** formulario + datos

### Imágenes disponibles
- `pasteleria-hero-01.webp`, `pasteleria-hero-02.webp` — Sliders
- `pasteleria-producto-01.webp` a `pasteleria-producto-08.webp` — Productos
- `pasteleria-tentacion-01.webp` a `pasteleria-tentacion-06.webp` — Carrusel
- `pasteleria-newsbg.webp` — Fondo newsletter
- `pasteleria-redesbg.webp` — Fondo redes
- `pasteleria-logo-primary.webp` — Logo principal
- `pasteleria-logo-sticker.webp` — Logo alternativo
- `pasteleria-cover.webp` — Portada páginas internas

## Lo que necesito

Rediseñame la UI completa de esta demo (homepage, shop, producto individual, sobre nosotros, FAQ, contacto, header y footer).

Card style recomendado: **hover_visual** (pastelería es un rubro visual).

## Requisitos técnicos

- Mobile-first responsive.
- Grid Bootstrap 5.
- CSS solo con `var(--chow_*)`.
- Sin Tailwind, sin frameworks JS.
- Código mínimo, clases con prefijo `chow-`.
- Comentarios `<!-- CHOW-EXISTING -->` y `<!-- CHOW-NEW -->` en el código.
```

---

## 🛠️ Instrucciones para el agente implementador

Esta sección es para cuando **vos (el agente implementador)** recibís el export de una herramienta de diseño y tenés que convertirlo a código real del Chow Theme.

### Pipeline de implementación

```
Export de Open Design/v0/Bolt
        │
        ▼
1. ANALIZAR ─── Identificar componentes nuevos vs existentes
        │
        ▼
2. MAPEAR ────── Asignar cada sección del diseño a un template del Chow Theme
        │
        ▼
3. CSS ───────── Extraer estilos → convertirlos a CSS inline en `demo-[nombre].php`
        │
        ▼
4. TEMPLATE ──── Solo si hace falta: crear nuevo template part en `home/` o `woocommerce/loop/`
        │
        ▼
5. DEMO ──────── Armar `demos/demo-[nombre].php` con datos + CSS + config
        │
        ▼
6. FUNCTIONS ─── (opcional) Armar `demos/demo-[nombre]-functions.php` solo si el demo necesita hooks propios (shop columns, clases de carrusel, post-import fixes, etc.)
```

### Paso 1: Analizar el diseño exportado

Recorré el HTML/CSS del export y clasificá cada sección:

| Marca en el export | Qué significa | Qué hacer |
|---|---|---|
| `<!-- CHOW-EXISTING: componente -->` | La IA identificó que acá va un componente del theme | No crear nada nuevo. Asegurate de que el componente existente renderice correctamente con los ajustes de CSS |
| `<!-- CHOW-NEW: componente -->` | La IA creó algo que no existe en el theme | Decidir si va como CSS inline o como nuevo template part |
| (sin marca) | Analizalo vos | Usá la tabla de componentes existentes para decidir |

### Paso 2: Mapear secciones a componentes del Chow Theme

| Sección del diseño | Componente Chow Theme | Archivo |
|---|---|---|
| Hero/Slider principal | `home/slide.php` | Template part existente |
| Grid de productos destacados | `home/productos-1.php` (layout 'columnas') | Template part existente |
| Carrusel de productos | `home/productos-1.php` (layout 'carousel') | Template part existente |
| Carrusel de imágenes (tentaciones) | `home/carrusel.php` | Template part existente |
| Newsletter | `home/news.php` | Template part existente |
| Redes sociales | `home/redes.php` | Template part existente |
| Sección "Sobre Nosotros" en home | `home/nosotros.php` | Template part existente |
| Blog en home | `home/blog.php` o `home/blog-destacado.php` | Template part existente |
| Clientes/Partners | `home/clientes.php` | Template part existente |
| Página interna (About, FAQ, Contacto) | `flexible-page.php` | Template existente |
| Header | `header.php` | Template existente |
| Footer | `footer.php` | Template existente |

**Regla de oro:** Si existe en la tabla de arriba, **no crear nuevo template part**. Ajustá con CSS.

### Paso 3: Cuándo crear un nuevo template part (y cuándo NO)

**NO crear nuevo template part si:**

- El diseño usa la misma estructura que un componente existente pero con colores/espaciados diferentes → solo CSS inline.
- El diseño cambia el orden de elementos dentro de una card existente → solo CSS (flexbox order, etc.).
- El diseño agrega/quita elementos decorativos (bordes, sombras, badges) → solo CSS.

**SÍ crear nuevo template part solo si:**

- El diseño introduce una sección completamente nueva que no existe en `home/` (ej: "sección de testimonios con carrusel de fotos y texto", "timeline de historia", "contador de estadísticas").
- El diseño requiere una variante de card de producto que no puede lograrse con CSS solo (ej: "card horizontal con imagen a la izquierda y texto a la derecha").
- El diseño tiene una interacción JS compleja que justifica su propio archivo.

**Convención para nuevos template parts:**

```
home/demo-[nombre]/[seccion].php
```

Ejemplo:
```
home/demo-pasteleria/testimonios.php
```

Luego en `demo-[nombre]-functions.php`:
```php
add_action( 'chow_home_sections', function() {
    get_template_part( 'home/demo-pasteleria/testimonios' );
});
```

### Paso 4: Extraer y convertir CSS

Del export de la IA, extraé SOLO el CSS que es nuevo o diferente. No copies estilos que ya existen en el theme.

**Reglas:**

1. Reemplazá colores fijos por `var(--chow_*)`.
2. Reemplazá clases de Tailwind por Bootstrap equivalentes + clases `chow-*`.
3. Agregá el CSS en `custom_css` del `demo-[nombre].php`, dentro de un heredoc `<<<CSS`.
4. Si el CSS es muy extenso (>100 líneas), evaluá moverlo a un archivo separado.

**Ejemplo de conversión Tailwind -> Chow:**

```css
/* ❌ Tailwind (como viene en el export) */
.bg-amber-50 { background-color: #fffbeb; }
.p-6 { padding: 1.5rem; }
.rounded-2xl { border-radius: 1rem; }

/* ✅ Chow CSS (convertido) */
.chow-section-featured {
    background-color: var(--chow_secundario);
    padding: 2rem;
    border-radius: 0.5rem;
}
```

### Paso 5: Armar el archivo demo

Usá la estructura probada de las demos existentes como referencia:

- [`demos/demo-pasteleria.php`](../demos/demo-pasteleria.php) — 496 líneas, estructura completa
- [`demos/demo-libreria.php`](../demos/demo-libreria.php) — 511 líneas, estructura completa

El archivo demo debe tener:

```php
<?php
function chow_get_demo_[nombre]() {
    return array(
        'id'          => '[nombre]',
        'name'        => 'Nombre Demo',
        'description' => 'Descripción corta',
        'image'       => '[nombre]-cover.webp',
        'version'     => '1.0',
        'card_style'  => '[classic|hover_visual|flip]',
        'company'     => array( /* colores, logos, datos de contacto */ ),
        'categories'  => array( /* nombre, slug, descripción */ ),
        'products'    => array( /* nombre, slug, precio, imagen, categoría, featured, on_sale */ ),
        'pages'       => array( /* título, slug, contenido, template, collapses */ ),
        'forms'       => array( /* nombre, form_tag (CF7) */ ),
        'home'        => array( /* slider_1..5, product_blocks, newsletter, redes, carrusel, sections visibility */ ),
        'menu'        => array( /* name, items con title, slug, parent */ ),
        'custom_css'  => '/* CSS inline */',
    );
}
```

**Campos de la sección `home` que deben poblarse:**

| Clave | Tipo | Descripción | Ejemplo |
|---|---|---|---|
| `slider_1`..`slider_5` | array | Cada slide con `imagen` (URL), `texto` (string), `link` (URL) | `'slider_1' => array('imagen' => 'hero-01.webp', 'texto' => 'Título', 'link' => '/shop')` |
| `product_blocks` | array de arrays | Bloques de productos con `titulo`, `descripcion`, `tipo` (ultimos/destacados/ofertas/categoria), `cantidad`, `layout` (columnas/carousel), `columnas` (col-lg-4), `card_style` | Ver demo-pasteleria.php línea 358 |
| `newsletter` | array | Grupo con `titulo`, `descripcion`, `news_bg` (imagen fondo), `formulario_news` (nombre del form CF7) | `'newsletter' => array('titulo' => '...', 'news_bg' => 'bg.webp', 'formulario_news' => 'Newsletter X')` |
| `redes_seccion` | array | Grupo con `titulo`, `descripcion`, `fondo_redes` (imagen fondo) | `'redes_seccion' => array('titulo' => 'Síguenos', 'fondo_redes' => 'redes-bg.webp')` |
| `carrusel_productos_destacados` | array de arrays | Cada item con `imagen`, `nombre_del_link`, `link` (array con url, title, target) | Ver demo-pasteleria.php línea 402 |
| `sections` | array | Visibilidad: `slide`, `productos-1`, `productos-carrusel`, `news`, `redes`, `clientes` | `'sections' => array('slide' => true, 'clientes' => false)` |

### Paso 6: Armar el archivo functions específico (opcional)

Solo necesario si el demo requiere hooks propios (personalizar columnas del shop, clases de carrusel, fixes post-import, etc.).
Si el demo no necesita comportamiento extra, **este archivo se omite** — el loader lo detecta automáticamente.

Usá como referencia:

- [`demos/demo-pasteleria-functions.php`](../demos/demo-pasteleria-functions.php) — 201 líneas
- [`demos/demo-libreria-functions.php`](../demos/demo-libreria-functions.php) — 86 líneas

Estructura base:

```php
<?php
function chow_demo_[nombre]_init() {
    add_action( 'wp_enqueue_scripts', 'chow_demo_[nombre]_enqueue_scripts', 20 );
    add_action( 'wp_enqueue_scripts', 'chow_demo_[nombre]_custom_styles', 30 );
    // hooks específicos del demo
    add_filter( 'loop_shop_columns', 'chow_demo_[nombre]_shop_columns', 1000 );
    add_filter( 'chow_slide_prod_classes', 'chow_demo_[nombre]_carrusel_class' );
    add_action( 'chow_demo_imported', 'chow_demo_[nombre]_post_import_fix' );
}

function chow_demo_[nombre]_enqueue_scripts() {
    // JS específico si hace falta
}

function chow_demo_[nombre]_shop_columns( $columns ) {
    return 3; // o el número de columnas que defina el diseño
}
```

### Resumen: qué tocar y qué no

| Qué | Acción | ¿Obligatorio? |
|---|---|---|
| CSS de colores, espaciados, bordes, fondos | CSS inline en `demo-[nombre].php` → `custom_css` | ✅ Sí |
| Tipografías (Google Fonts) | `@import` en `custom_css` | ✅ Sí |
| Tamaños de slider, altos de sección | CSS inline | ✅ Sí |
| Layout de grilla en shop | `demo-[nombre]-functions.php` con `loop_shop_columns` | ⬜ Solo si difiere del default |
| Nueva sección en home que no existe | Nuevo template part en `home/demo-[nombre]/` | ⬜ Solo si necesario |
| Nueva variante de card de producto | Nuevo template en `woocommerce/loop/card-[variante].php` | ⬜ Solo si necesario |
| JS específico | Archivo en `assets/js/demo-[nombre].js` + enqueue desde functions | ⬜ Solo si necesario |
| Datos de productos, páginas, forms | `demo-[nombre].php` en la estructura de array | ✅ Sí |
| Configuración de home (sliders, bloques) | `demo-[nombre].php` → array `home` | ✅ Sí |
| Menú de navegación | `demo-[nombre].php` → array `menu` | ✅ Sí |
| Order de ítems de menú | `demo-[nombre]-functions.php` con `chow_demo_[nombre]_fix_menu_order` | ⬜ Solo si necesario |

---

## Cómo usar este documento

### Flujo completo para crear/rediseñar una demo

```
1. DEFINIR IDENTIDAD
   │  Elegir rubro, colores, tipografía, personalidad de marca
   ▼
2. GENERAR IMÁGENES (opcional)
   │  Usar `demos/prompts.md` con Midjourney/DALL-E
   ▼
3. DISEÑAR UI
   │  Copiar el Template de Prompt Base (con instrucciones de código),
   │  reemplazar placeholders, pegar en v0/Bolt/Open Design
   ▼
4. ITERAR DISEÑO
   │  Ajustar colores, espaciados, responsive hasta que guste
   ▼
5. IMPLEMENTAR (agente implementador)
   │  Seguir "Instrucciones para el agente implementador" (sección arriba)
    │  Extraer CSS, mapear componentes, armar demo-[nombre].php (+ functions solo si necesario)
   ▼
6. PROBAR
   │  Usar "Restaurar Plantilla" en el admin de Chow Theme
   ▼
7. ITERAR
   │  Ajustar y repetir hasta que todo funcione
```

---

## Referencia rápida: Variables CSS del Chow Theme

```css
:root {
    --chow_ppal: #D60B52;       /* Color principal */
    --chow_secundario: #1d71b8; /* Color secundario */
    --chow_txt: #5f5f5f;        /* Color de texto */
    --chow_blanco: #ffffff;     /* Color de fondo */
}
```

Estas variables se definen dinámicamente desde SCF (campo `color_principal`, `color_secundario`, etc. en opciones del theme). Cada demo las sobreescribe en su array `company`.
