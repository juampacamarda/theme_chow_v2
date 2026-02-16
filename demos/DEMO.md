# 📚 Guía de Creación de Demos - Chow Theme

## Tabla de Contenidos
1. [Introducción](#introducción)
2. [Restaurar Plantilla (Función Rápida de Testing)](#restaurar-plantilla)
3. [Estructura General de un Demo](#estructura-general-de-un-demo)
4. [Problemas Comunes y Soluciones](#problemas-comunes-y-soluciones)
5. [Referencia de Campos ACF](#referencia-de-campos-acf)
6. [Checklist Pre-Importación](#checklist-pre-importación)
7. [Ejemplos de Código Correcto](#ejemplos-de-código-correcto)

---

## Introducción

Esta guía documenta cómo crear demos correctamente para evitar errores comunes de importación. Los demos se definen en archivos como `demos/demo-libreria.php` y se importan usando `demos/importer.php`.

**Archivos clave**:
- `demos/demo-[nombre].php` - Definición del demo (datos)
- `demos/importer.php` - Lógica de importación
- `acf-json/group_*.json` - Esquema de campos ACF

---

## Restaurar Plantilla

### Propósito

La función "Restaurar Plantilla" permite resetear rápidamente el demo a su estado original. Es útil para:

- **Testing rápido**: Probar cambios en demo sin afectar otros datos
- **Validación de cambios**: Verificar que todos los cambios del demo funcionan correctamente
- **Desarrollo iterativo**: Resetear fácilmente entre iteraciones de testing

### Cómo Funciona

1. Ve a **Chow Theme → Importar Demo** en el admin
2. Busca el demo activo (mostrará badge "Activo")
3. Haz clic en el botón **"Restaurar Plantilla"**
4. Confirma la advertencia sobre eliminación de contenido
5. El proceso:
   - ✅ Elimina TODO el contenido actual (productos, páginas, etc.)
   - ✅ Reimporta la plantilla original del demo
   - ✅ Restaura todas las configuraciones

### Advertencias

⚠️ **Este proceso es DESTRUCTIVO**:
- Se eliminarán TODOS los productos actuales
- Se eliminarán TODAS las páginas personalizadas
- Se restablecerá la configuración a valores del demo
- **No se puede deshacer** - asegúrate de tener backup si necesitas preservar algo

### Caso de Uso Real

```
Flujo de desarrollo:
1. Haces cambios en demo-libreria.php
2. Haces commit de cambios
3. Usas "Restaurar Plantilla" en admin
4. Revisas que TODO se importa correctamente
5. Si hay errores, editas demo-libreria.php
6. Vuelves a paso 2 (hasta que todo sea perfecto)
```

---

## Estructura General de un Demo

```php
<?php
function chow_get_demo_nombre() {
    return array(
        'id'          => 'nombre',                    // Identificador único
        'name'        => 'Demo Nombre',               // Nombre visible
        'description' => 'Descripción corta',         // Para admin UI
        'image'       => 'cover.png',                 // Imagen del demo
        'version'     => '1.0',                       // Versión
        'card_style'  => 'hover_visual',              // Estilo de tarjetas de producto
        
        'company'     => array( ... ),                // Configuración de empresa
        'categories'  => array( ... ),                // Categorías de productos
        'products'    => array( ... ),                // Productos
        'pages'       => array( ... ),                // Páginas
        'forms'       => array( ... ),                // Formularios CF7
        'home'        => array( ... ),                // Configuración de homepage
        'menu'        => array( ... ),                // Estructura del menú
        'custom_css'  => '/* CSS */',                 // CSS personalizado
    );
}
```

---

## Problemas Comunes y Soluciones

### ⚠️ PROBLEMA 1: Empresa/Colores - Campos Duplicados

**Síntoma**: Los logos de empresa no se importan aunque los colores sí.

**Causa**: Los colores se definen en dos lugares:
- `'company'` array (correcto) - línea 26-42
- `'theme_options'` array (INCORRECTO) - línea 403-408

**SOLUCIÓN**:
```php
// ❌ INCORRECTO - NO HACER ESTO
'theme_options' => array(
    'color_principal'  => '#2c3e50',
    'color_secundario' => '#fdf5e6',
    'color_texto'      => '#5f5f5f',
    'color_fondo'      => '#ffffff',
),

// ✅ CORRECTO - Los colores van SOLO en 'company'
'company' => array(
    'color_principal'  => '#2c3e50',
    'color_secundario' => '#fdf5e6',
    'color_texto'      => '#5f5f5f',
    'color_fondo'      => '#ffffff',
    'logo_header_desktop'   => 'logo-desktop.png',
    'logo_header_mobile'    => 'logo-mobile.png',
    'logo_footer'           => 'logo-footer.png',
    // ... otros campos
),
```

**Archivos implicados**:
- `demo-[nombre].php` - **ELIMINAR** sección `'theme_options'`
- `importer.php` líneas 657-661 - **ELIMINAR** segundo `update_field()` de colores

---

### ⚠️ PROBLEMA 2: Formularios CF7 - Shortcode Incorrecto

**Síntoma**: El formulario no aparece en la sección newsletter de la homepage.

**Causa**: Se guarda solo el ID del formulario (42) en lugar del shortcode completo `[contact-form-7 id="42" title="..."]`

**SOLUCIÓN**:

El campo `formulario_news` en el GROUP `newsletter` debe contener el **shortcode completo**, no solo el ID.

```php
// ❌ INCORRECTO
'formulario_news' => 'Newsletter Librería',  // Solo el nombre

// ✅ CORRECTO
'formulario_news' => '[contact-form-7 id="42" title="Newsletter Librería"]',  // Shortcode
```

**En importer.php línea 759, generarlo automáticamente**:
```php
// El importer debe convertir el ID a shortcode
$newsletter_data = array(
    'titulo' => $newsletter['titulo'],
    'descripcion' => $newsletter['descripcion'],
    'news_bg' => $news_bg_id,
    // Convertir ID a shortcode completo
    'formulario_news' => '[contact-form-7 id="' . $news_form_id . '" title="' . $newsletter['formulario_news'] . '"]',
);
```

**Campos afectados**:
- `newsletter.formulario_news` - Para formulario en homepage
- `carrusel_productos_destacados[].formulario_producto` - Si existe

---

### ⚠️ PROBLEMA 3: Páginas Flexible - Contenido en Lugar Incorrecto

**Síntoma**: Las páginas con template "flexible-page" no muestran contenido ni collapses.

**Causa**: El contenido se guarda en `post_content` pero el template espera campos ACF `texto_contenido` y `collapses`.

#### 3A: Contenido Principal

**INCORRECTO** (importer.php línea 587):
```php
$page_post = array(
    'post_title'  => $page_data['title'],
    'post_content' => $page_data['content'],  // ❌ Se guarda aquí
    'post_type'   => 'page',
    'post_status' => 'publish',
);
```

**CORRECTO**:
```php
// Para páginas flexible, guardar en ACF field
if ( isset( $page_data['template'] ) && 'flexible-page' === $page_data['template'] ) {
    update_field( 'texto_contenido', $page_data['content'], $page_id );
} else {
    // Para otras páginas, post_content es correcto
}
```

#### 3B: Collapses/Accordion

**INCORRECTO** (demo-libreria.php línea 222):
```php
'flexible_content' => array(    // ❌ Nombre incorrecto
    array(
        'acf_fc_layout' => 'collapse_accordion',
        'items' => array(          // ❌ Estructura anidada
            array(
                'title'   => '¿Pregunta?',
                'content' => 'Respuesta...',
            ),
        ),
    ),
),
```

**CORRECTO**:
```php
'collapses' => array(           // ✅ Nombre correcto (REPEATER)
    array(
        'titulo_collapse'   => '¿Pregunta?',
        'contenido_collapse' => 'Respuesta...',
    ),
    array(
        'titulo_collapse'   => '¿Otra Pregunta?',
        'contenido_collapse' => 'Otra respuesta...',
    ),
),
```

**En importer.php línea 608**:
```php
// ❌ INCORRECTO
update_field( 'flexible_content', $page_data['flexible_content'], $page_id );

// ✅ CORRECTO
if ( isset( $page_data['collapses'] ) ) {
    update_field( 'collapses', $page_data['collapses'], $page_id );
}
```

---

### ⚠️ PROBLEMA 4: URL de Tienda - Slug Incorrecto

**Síntoma**: El menú apunta a `/tienda` pero WooCommerce usa `/shop`.

**Causa**: El slug se define como `/tienda` en lugar de `/shop`.

**SOLUCIÓN**:
```php
// ❌ INCORRECTO
array( 'title' => 'Tienda', 'url' => '/tienda', 'parent' => null ),

// ✅ CORRECTO
array( 'title' => 'Tienda', 'url' => '/shop', 'parent' => null ),
```

---

## Referencia de Campos ACF

### 🏢 GROUP: Empresa

**Ubicación**: `acf-json/group_empresa.json`
**En Demo**: `'company'` array

**Campos y Tipos**:

| Campo | Tipo | Ejemplo | Notas |
|-------|------|---------|-------|
| `color_principal` | color_picker | `#2c3e50` | Color primario |
| `color_secundario` | color_picker | `#fdf5e6` | Color secundario |
| `color_texto` | color_picker | `#5f5f5f` | Color de texto |
| `color_fondo` | color_picker | `#ffffff` | Color de fondo |
| `logo_header_desktop` | image | `logo-desktop.png` | Nombre del archivo de imagen |
| `logo_header_mobile` | image | `logo-mobile.png` | Nombre del archivo de imagen |
| `logo_footer` | image | `logo-footer.png` | Nombre del archivo de imagen |
| `direccion` | text | `Calle 123, Ciudad` | Dirección |
| `telefonos` | text | `+54 11 4567-8900` | Teléfono |
| `mail` | text | `info@sitio.com` | Email |
| `facebook_link` | text | `https://facebook.com/...` | URL Facebook |
| `instagram_link` | text | `https://instagram.com/...` | URL Instagram |
| `twitter_link` | text | `https://twitter.com/...` | URL Twitter |
| `wsp_link` | text | `5491145678900` | Número WhatsApp |
| `logos_legales` | text | `` | Vacío |

**Ejemplo Correcto**:
```php
'company' => array(
    'color_principal'       => '#2c3e50',
    'color_secundario'      => '#fdf5e6',
    'color_texto'           => '#5f5f5f',
    'color_fondo'           => '#ffffff',
    'logo_header_desktop'   => 'libreria-logo-color.png',
    'logo_header_mobile'    => 'libreria-logo-blanco.png',
    'logo_footer'           => 'libreria-logo-blanco.png',
    'direccion'             => 'Av. Corrientes 1234, Buenos Aires',
    'telefonos'             => '+54 (11) 4567-8900',
    'mail'                  => 'info@paginasdetinta.com',
    'facebook_link'         => '#',
    'instagram_link'        => '#',
    'twitter_link'          => '#',
    'wsp_link'              => '5491145678900',
    'logos_legales'         => '',
),
```

---

### 🏠 GROUP: Slider Home

**Ubicación**: `acf-json/group_slider_home.json`
**En Demo**: `'home'` > `'slider_1'`, `'slider_2'`, etc.

**Campos por Slider**:

| Campo | Tipo | Ejemplo |
|-------|------|---------|
| `imagen` | image | `slide-01.png` |
| `texto` | text | `Bienvenido a nuestro sitio` |
| `link` | text | `/tienda` |

**Ejemplo Correcto**:
```php
'home' => array(
    'slider_1' => array(
        'imagen' => 'libreria-slide01.png',
        'texto' => 'Descubre Nuevos Mundos',
        'link' => '/tienda',
    ),
    'slider_2' => array(
        'imagen' => 'libreria-slide02.png',
        'texto' => 'Literatura Premium',
        'link' => '/tienda',
    ),
    // ... slider_3 a slider_5
),
```

---

### 📰 GROUP: Newsletter

**Ubicación**: `acf-json/group_contenido_home.json`
**En Demo**: `'home'` > `'newsletter'`

**Campos**:

| Campo | Tipo | Ejemplo | IMPORTANTE |
|-------|------|---------|-----------|
| `titulo` | text | `Suscribite a Nuestro Newsletter` | - |
| `descripcion` | textarea | `Recibe ofertas exclusivas...` | - |
| `news_bg` | image | `fondo-news.png` | Nombre del archivo |
| `formulario_news` | textarea | `[contact-form-7 id="42" ...]` | **DEBE SER SHORTCODE** |

**INCORRECTO**:
```php
'newsletter' => array(
    'titulo'       => 'Suscribite...',
    'descripcion'  => 'Recibe...',
    'fondo'        => 'fondo-news.png',        // ❌ Campo incorrecto
    'form_id'      => 'Newsletter Librería',   // ❌ Debe ser shortcode
),
```

**CORRECTO**:
```php
'newsletter' => array(
    'titulo'           => 'Suscribite a Nuestro Newsletter',
    'descripcion'      => 'Recibe ofertas exclusivas...',
    'news_bg'          => 'fondo-news.png',    // ✅ Campo correcto
    'formulario_news'  => 'Newsletter Librería', // El importer lo convierte a shortcode
),
```

---

### 🔗 REPEATER: Carrusel Productos Destacados

**Ubicación**: `acf-json/group_contenido_home.json`
**En Demo**: `'home'` > `'carrusel_productos_destacados'`

**Campos por Fila**:

| Campo | Tipo | Ejemplo | Notas |
|-------|------|---------|-------|
| `imagen` | image | `producto01.png` | Nombre de archivo |
| `nombre_del_link` | text | `El Quijote` | Título del producto |
| `link` | link (ACF) | Array con url/title/target | **DEBE SER ARRAY** |

**INCORRECTO**:
```php
'carrusel_productos_destacados' => array(
    array(
        'nombre'      => 'El Quijote',           // ❌ Campo incorrecto
        'descripcion' => 'Novela clásica',       // ❌ No en schema
        'imagen'      => 'producto01.png',
        'link'        => '/?p=1',                // ❌ Debe ser array
        'precio'      => '34.99',                // ❌ No en schema
    ),
),
```

**CORRECTO**:
```php
'carrusel_productos_destacados' => array(
    array(
        'imagen'            => 'producto01.png',
        'nombre_del_link'   => 'El Quijote de la Mancha',
        'link'              => array(
            'url'    => '/?p=1',
            'title'  => 'El Quijote de la Mancha',
            'target' => '',
        ),
    ),
),
```

---

### 📝 GROUP: Redes Sociales

**Ubicación**: `acf-json/group_contenido_home.json`
**En Demo**: `'home'` > `'redes_seccion'`

**Campos**:

| Campo | Tipo | Ejemplo |
|-------|------|---------|
| `titulo` | text | `Síguenos en Redes` |
| `descripcion` | textarea | `Conecta con nosotros...` |
| `fondo_redes` | image | `fondo-redes.png` |

**Ejemplo Correcto**:
```php
'redes_seccion' => array(
    'titulo'      => 'Síguenos en Redes',
    'descripcion' => 'Conecta con nosotros en nuestras redes',
    'fondo_redes' => 'fondo-redes.png',
),
```

---

### 📄 Template: Flexible Page

**Ubicación**: `acf-json/group_flexible_page.json`
**En Demo**: `'pages'` con `'template' => 'flexible-page'`

**Campos esperados**:

| Campo | Tipo | En Demo | Ejemplo |
|-------|------|---------|---------|
| `texto_contenido` | textarea | `'content'` | HTML/texto de página |
| `collapses` | REPEATER | `'collapses'` | Array de collapses |

**Estructura de Collapses**:
```php
// Cada fila del REPEATER tiene:
array(
    'titulo_collapse'   => 'Título de la pregunta',
    'contenido_collapse' => 'Contenido de la respuesta',
)
```

**INCORRECTO**:
```php
array(
    'title'   => 'Sobre Nosotros',
    'slug'    => 'sobre-nosotros',
    'content' => '<p>Contenido...</p>',
    'template' => 'flexible-page',
    'flexible_content' => array(  // ❌ Nombre incorrecto
        array(
            'acf_fc_layout' => 'collapse_accordion',
            'items' => array(      // ❌ Estructura anidada
                array(
                    'title'   => '¿Pregunta?',
                    'content' => 'Respuesta...',
                ),
            ),
        ),
    ),
),
```

**CORRECTO**:
```php
array(
    'title'   => 'Preguntas Frecuentes',
    'slug'    => 'preguntas-frecuentes',
    'content' => '<p>Encuentra respuestas...</p>',
    'template' => 'flexible-page',
    'collapses' => array(  // ✅ Nombre correcto
        array(
            'titulo_collapse'   => '¿Cuáles son los costos?',
            'contenido_collapse' => 'El envío es gratis para compras mayores a $500...',
        ),
        array(
            'titulo_collapse'   => '¿Política de devoluciones?',
            'contenido_collapse' => 'Tenemos política de 30 días sin preguntas...',
        ),
    ),
),
```

---

### 📋 Contact Form 7

**En Demo**: `'forms'` array

**Campos**:

| Campo | Tipo | Ejemplo |
|-------|------|---------|
| `name` | text | `Newsletter Librería` |
| `form_tag` | text | `[email* ...] [submit ...]` |

**Ejemplo Correcto**:
```php
'forms' => array(
    array(
        'name'     => 'Contacto Librería',
        'form_tag' => '[text* nombre placeholder "Tu nombre"][email* email placeholder "Tu email"][text* asunto placeholder "Asunto"][textarea mensaje placeholder "Tu mensaje"]',
    ),
    array(
        'name'     => 'Newsletter Librería',
        'form_tag' => '[email* your-email placeholder "Ingresá tu e-mail"][submit "ENVIAR"]',
    ),
),
```

---

### 🎁 Product Blocks

**Ubicación**: ACF - Bloques de Productos
**En Demo**: `'home'` > `'product_blocks'`

**Campos**:

| Campo | Tipo | Ejemplo | Opciones |
|-------|------|---------|----------|
| `titulo` | text | `Novedades` | - |
| `descripcion` | text | `Las últimas... ` | - |
| `tipo` | select | `ultimos` | `ultimos`, `destacados`, `ofertas` |
| `cantidad` | number | `4` | - |
| `layout` | select | `columnas` | `columnas`, `carousel` |
| `columnas` | select | `col-lg-3` | `col-lg-2`, `col-lg-3`, `col-lg-4` |
| `card_style` | select | `hover_visual` | `classic`, `hover_visual` |

**Ejemplo Correcto**:
```php
'product_blocks' => array(
    array(
        'titulo'      => 'Novedades',
        'descripcion' => 'Las últimas incorporaciones a nuestro catálogo',
        'tipo'        => 'ultimos',
        'cantidad'    => 4,
        'layout'      => 'columnas',
        'columnas'    => 'col-lg-3',
        'card_style'  => 'hover_visual',
    ),
),
```

---

## Checklist Pre-Importación

Antes de crear un nuevo demo, verificar:

### ✅ Estructura Básica
- [ ] Archivo `demos/demo-[nombre].php` existe
- [ ] Función `chow_get_demo_[nombre]()` definida
- [ ] Todos los campos requeridos presentes: id, name, description, image
- [ ] IDs son únicos y no existen otros demos con el mismo nombre

### ✅ Company/Empresa
- [ ] **NO EXISTE** sección `'theme_options'` (eliminar si existe)
- [ ] Colores definidos: `color_principal`, `color_secundario`, `color_texto`, `color_fondo`
- [ ] Logos referenciados: `logo_header_desktop`, `logo_header_mobile`, `logo_footer`
- [ ] Archivos de imagen existen en `demos/[nombre]/images/`
- [ ] Campo `logos_legales` presente (puede estar vacío)

### ✅ Productos
- [ ] Mínimo 3 productos con `'featured' => true`
- [ ] Cada producto tiene: `name`, `slug`, `description`, `short_description`, `price`, `image`, `category`
- [ ] Categorías referenciadas existen en array `'categories'`
- [ ] Nombres de archivos coinciden con archivos en `demos/[nombre]/images/`

### ✅ Categorías
- [ ] Cada categoría tiene: `name`, `slug`, `description`
- [ ] Slugs son únicos

### ✅ Formularios CF7
- [ ] Cada formulario tiene: `name`, `form_tag`
- [ ] `name` es único y descriptivo
- [ ] `form_tag` contiene campos válidos de CF7

### ✅ Páginas
- [ ] Página "Inicio" con `'template' => 'index-plantilla'`
- [ ] Páginas con `'template' => 'flexible-page'` tienen:
  - [ ] Campo `'content'` (para `texto_contenido`)
  - [ ] Campo `'collapses'` (NO `'flexible_content'`)
  - [ ] Estructura de collapses correcta: `'titulo_collapse'` y `'contenido_collapse'`
- [ ] Páginas sin template especificado tienen `'template' => 'default'`

### ✅ Home Configuration
- [ ] Sliders (slider_1 a slider_5) con: `imagen`, `texto`, `link`
- [ ] Newsletter con: `titulo`, `descripcion`, `news_bg`, `formulario_news`
- [ ] Redes con: `titulo`, `descripcion`, `fondo_redes`
- [ ] Carrusel productos con: `imagen`, `nombre_del_link`, `link` (array)
- [ ] Product blocks con card_style válido: `classic` o `hover_visual`

### ✅ Menú
- [ ] Items de menú apuntan a URLs correctas:
  - [ ] `/` para Inicio
  - [ ] `/shop` para Tienda (NO `/tienda`)
  - [ ] `/sobre-nosotros`, `/preguntas-frecuentes`, etc. para páginas
- [ ] No hay referencias a URLs temporales o slugs incorrectos

### ✅ Imágenes
- [ ] Carpeta `demos/[nombre]/images/` existe
- [ ] Todos los archivos referenciados en el demo existen:
  - [ ] Logos: `libreria-logo-*.png`
  - [ ] Cover: `libreria-cover.png`
  - [ ] Productos: `libreria-producto0*.png`
  - [ ] Slides: `libreria-slide0*.png`
  - [ ] Fondos: `fondo-*.png`

### ✅ Importer Logic (importer.php)
- [ ] No hay sección `'theme_options'` que duplique colores
- [ ] Newsletter shortcode se genera correctamente (línea ~759)
- [ ] Páginas flexible guardan contenido en `texto_contenido` (no post_content)
- [ ] Páginas flexible guardan collapses en `collapses` field

---

## Ejemplos de Código Correcto

### ✅ Ejemplo Completo Mínimo

```php
<?php
function chow_get_demo_tienda() {
    return array(
        'id'          => 'tienda',
        'name'        => 'Demo Tienda',
        'description' => 'Una tienda de ejemplo',
        'image'       => 'tienda-cover.png',
        'version'     => '1.0',
        'card_style'  => 'hover_visual',
        
        // EMPRESA (colores y logos)
        'company' => array(
            'color_principal'       => '#2c3e50',
            'color_secundario'      => '#fdf5e6',
            'color_texto'           => '#5f5f5f',
            'color_fondo'           => '#ffffff',
            'logo_header_desktop'   => 'tienda-logo.png',
            'logo_header_mobile'    => 'tienda-logo-mobile.png',
            'logo_footer'           => 'tienda-logo-footer.png',
            'direccion'             => 'Calle 123, Buenos Aires',
            'telefonos'             => '+54 11 1234-5678',
            'mail'                  => 'info@tienda.com',
            'facebook_link'         => '#',
            'instagram_link'        => '#',
            'twitter_link'          => '#',
            'wsp_link'              => '5491112345678',
            'logos_legales'         => '',
        ),
        
        // CATEGORÍAS
        'categories' => array(
            array(
                'name'        => 'Categoría 1',
                'slug'        => 'categoria-1',
                'description' => 'Descripción de categoría 1',
            ),
        ),
        
        // PRODUCTOS (mínimo 3)
        'products' => array(
            array(
                'name'              => 'Producto 1',
                'slug'              => 'producto-1',
                'description'       => 'Descripción larga...',
                'short_description' => 'Descripción corta',
                'price'             => '99.99',
                'sale_price'        => '',
                'stock'             => 50,
                'image'             => 'tienda-producto01.png',
                'category'          => 'Categoría 1',
                'featured'          => true,
                'on_sale'           => false,
            ),
        ),
        
        // PÁGINAS
        'pages' => array(
            array(
                'title'    => 'Inicio',
                'slug'     => 'inicio',
                'content'  => '',
                'template' => 'index-plantilla',
            ),
        ),
        
        // FORMULARIOS
        'forms' => array(
            array(
                'name'     => 'Contacto',
                'form_tag' => '[text* nombre][email* email][textarea mensaje][submit]',
            ),
        ),
        
        // HOME CONFIG
        'home' => array(
            'slider_1' => array(
                'imagen' => 'tienda-slide01.png',
                'texto'  => 'Bienvenido',
                'link'   => '/shop',
            ),
            'slider_2' => array(
                'imagen' => '',
                'texto'  => '',
                'link'   => '',
            ),
            'slider_3' => array(
                'imagen' => '',
                'texto'  => '',
                'link'   => '',
            ),
            'slider_4' => array(
                'imagen' => '',
                'texto'  => '',
                'link'   => '',
            ),
            'slider_5' => array(
                'imagen' => '',
                'texto'  => '',
                'link'   => '',
            ),
            'product_blocks' => array(
                array(
                    'titulo'      => 'Productos',
                    'descripcion' => 'Nuestros mejores productos',
                    'tipo'        => 'destacados',
                    'cantidad'    => 4,
                    'layout'      => 'columnas',
                    'columnas'    => 'col-lg-3',
                    'card_style'  => 'hover_visual',
                ),
            ),
            'newsletter' => array(
                'titulo'           => 'Newsletter',
                'descripcion'      => 'Suscribite a nuestro newsletter',
                'news_bg'          => 'fondo-news.png',
                'formulario_news'  => 'Contacto',  // Nombre del formulario CF7
            ),
            'redes_seccion' => array(
                'titulo'      => 'Síguenos',
                'descripcion' => 'En nuestras redes sociales',
                'fondo_redes' => 'fondo-redes.png',
            ),
            'carrusel_productos_destacados' => array(
                array(
                    'imagen'            => 'tienda-producto01.png',
                    'nombre_del_link'   => 'Producto 1',
                    'link'              => array(
                        'url'    => '/?p=1',
                        'title'  => 'Producto 1',
                        'target' => '',
                    ),
                ),
            ),
            'sections' => array(
                'slide'              => true,
                'productos-1'        => true,
                'productos-carrusel' => true,
                'news'               => true,
                'redes'              => true,
                'clientes'           => false,
            ),
        ),
        
        // MENÚ
        'menu' => array(
            'name'  => 'Menú Tienda',
            'items' => array(
                array( 'title' => 'Inicio', 'url' => '/', 'parent' => null ),
                array( 'title' => 'Tienda', 'url' => '/shop', 'parent' => null ),
                array( 'title' => 'Contacto', 'url' => '/contacto', 'parent' => null ),
            ),
        ),
        
        // CSS PERSONALIZADO
        'custom_css' => '/* Estilos personalizados aquí */',
    );
}
```

---

## Validation Script

Para validar un demo antes de importarlo, ejecutar:

```bash
php -r "
include 'demos/demo-[nombre].php';
\$demo = chow_get_demo_[nombre]();

// Validaciones
echo 'Validando demo: ' . \$demo['name'] . PHP_EOL;
echo 'ID: ' . \$demo['id'] . PHP_EOL;
echo 'Productos: ' . count(\$demo['products']) . PHP_EOL;
echo 'Categorías: ' . count(\$demo['categories']) . PHP_EOL;
echo 'Páginas: ' . count(\$demo['pages']) . PHP_EOL;
echo 'Formularios: ' . count(\$demo['forms']) . PHP_EOL;
echo 'OK!' . PHP_EOL;
"
```

---

## Troubleshooting

### Logos no aparecen
1. Verificar que el archivo existe en `demos/[nombre]/images/`
2. Verificar que el nombre coincide (mayúsculas/minúsculas)
3. Verificar que la sección `'theme_options'` no exista (eliminar si existe)

### Formularios no se muestran
1. Verificar que `formulario_news` contiene el nombre del formulario, no un shortcode
2. El importer debe generar automáticamente el shortcode
3. Si no aparece, revisar que CF7 esté instalado

### Páginas flexible sin contenido
1. Verificar que `'content'` se guarda en `'texto_contenido'` (no post_content)
2. Verificar que `'flexible_content'` se renombró a `'collapses'`
3. Verificar estructura de collapses: `'titulo_collapse'` y `'contenido_collapse'`

### Menú incorrecto
1. Verificar que URLs son `/shop` (no `/tienda`)
2. Verificar que pages existan con los slugs correctos

---

## Historial de Cambios

### v1.0 (2026-02-15)
- Documentación inicial
- 4 problemas comunes identificados y documentados
- Checklist pre-importación
- Ejemplos de código correcto
- Troubleshooting

---

## Contacto y Reportes

Si encuentras nuevos problemas o casos especiales, documenta:
1. El problema exacto observado
2. Los pasos para reproducirlo
3. El código relevante
4. La solución aplicada

Luego actualiza este documento para que otros developers lo eviten.

