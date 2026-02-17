# 📚 Guía de Creación de Demos - Chow Theme

## Tabla de Contenidos
1. [Introducción](#introducción)
2. [Restaurar Plantilla (Función Rápida de Testing)](#restaurar-plantilla)
3. [Actualizaciones Críticas (2026-02-16)](#actualizaciones-críticas-2026-02-16)
4. [Flujo Simplificado para Crear Demos](#flujo-simplificado-para-crear-demos)
5. [Estructura General de un Demo](#estructura-general-de-un-demo)
6. [Problemas Comunes y Soluciones](#problemas-comunes-y-soluciones)
7. [Referencia de Campos ACF](#referencia-de-campos-acf)
8. [Detalles de Estilos y Ajustes Visuales](#detalles-de-estilos-y-ajustes-visuales)
9. [Guía de Prompts de Imágenes](#guía-de-prompts-de-imágenes)
10. [Checklist Pre-Importación](#checklist-pre-importación)
11. [Ejemplos de Código Correcto](#ejemplos-de-código-correcto)
12. [Validation Script](#validation-script)
13. [Troubleshooting](#troubleshooting)
14. [Contacto y Reportes](#contacto-y-reportes)

---

## Introducción

Esta guía documenta cómo crear demos correctamente para evitar errores comunes de importación. Los demos se definen en archivos como `demos/demo-libreria.php` y se importan usando `demos/importer.php`.

**Archivos clave**:
- `demos/demo-[nombre].php` - Definición del demo (datos)
- `demos/importer.php` - Lógica de importación
- `acf-json/group_*.json` - Esquema de campos ACF

---

## Restaurar Plantilla (Función Rápida de Testing)

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

⚠️ **Este proceso es DESTRUCTIVO - Se eliminarán los siguientes ajustes personalizados**:

**SE PERDERÁ TODO ESTO:**
- ✗ Todos los productos personalizados
- ✗ Todas las páginas personalizadas
- ✗ Todos los ajustes personalizados
- ✗ Colores, imágenes y configuración personalizada
- ✗ Formularios y estructuras de menú personalizadas

**IMPORTANTE SOBRE PRODUCTOS (nuevo comportamiento):**
- Desde la actualización del importador, al importar demo se eliminan todos los productos WooCommerce existentes (`product` y `product_variation`) antes de crear los productos del demo.
- Esto evita catálogos mezclados entre demos.

**SE RESTAURARÁ:**
- ✓ La plantilla original del demo con todos sus contenidos por defecto
- ✓ Estilos y configuración original del demo
- ✓ Estructura de páginas y productos de plantilla

**IMPORTANTE:**
- ⚠️ **No se puede deshacer** - esta acción es irreversible
- 📌 Asegúrate de tener una **copia de seguridad** si necesitas preservar cambios
- 🚫 No recuperarás ediciones personalizadas después de restaurar

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

## Actualizaciones Críticas (2026-02-16)

Estas correcciones ya están aplicadas en el código base y deben tenerse en cuenta al crear nuevos demos.

### 1) Menú "Tienda" sin label (resuelto)
- Se detectó que updates parciales de menú podían dejar items custom sin etiqueta visible (`(no label)`).
- Solución aplicada: cuando se actualiza URL de item, también se conserva título, tipo, posición y parent del item.

### 2) Orden del menú inestable (resuelto)
- El orden podía alterarse después de hooks de post-import.
- Solución aplicada: preservación explícita de `menu_order` en updates de menú y fix específico de orden en demo pastelería.

### 3) Activación cruzada de demos (resuelto)
- Podían quedar varios demos marcados como activos al mismo tiempo, cargando hooks de más de un demo.
- Solución aplicada:
    - Importer: activa solo el demo importado y desactiva los demás.
    - Loader: prioriza `chow_active_demo` y carga solo ese demo.

### 4) Productos mezclados entre imports (resuelto)
- Antes podían coexistir productos viejos y nuevos.
- Solución aplicada: limpieza total de productos WooCommerce antes de crear productos del demo.

---

## Flujo Simplificado para Crear Demos

Usar este flujo evita la mayoría de errores:

1. **Crear/editar** `demos/demo-[nombre].php` con estructura base (company, categories, products, pages, forms, home, menu, custom_css).
2. **Validar nombres de imágenes** contra `demos/[nombre]/images/`.
3. **Importar con "Restaurar Plantilla"** desde admin.
4. **Verificar 4 puntos clave**:
     - Menú: label y orden correctos.
     - Home: sliders, newsletter, redes y carrusel.
     - Páginas flexibles: contenido + collapses.
     - Productos: solo existen los del demo importado.
5. **Iterar**: ajustar demo PHP y volver a restaurar.

Regla práctica: el archivo del demo es la fuente de verdad; la importación reconstruye contenido en BD.

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
- `'company'` array (correcto)
- `'theme_options'` array (INCORRECTO)

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
- `importer.php` - evitar cualquier duplicación de update de colores fuera de `company`

---

### ⚠️ PROBLEMA 2: Formularios CF7 - Shortcode Incorrecto

**Síntoma**: El formulario no aparece en la sección newsletter de la homepage.

**Causa**: Confusión entre el valor de config del demo y el valor final guardado en ACF.

**SOLUCIÓN**:

En el archivo del demo, `formulario_news` debe contener el **nombre del formulario**. El importer lo convierte al shortcode completo automáticamente.

```php
// ❌ INCORRECTO
'formulario_news' => 'Newsletter Librería',  // Solo el nombre

// ✅ CORRECTO en demo-[nombre].php
'formulario_news' => 'Newsletter Librería',  // Nombre del formulario
```

**En importer.php, generarlo automáticamente**:
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

**INCORRECTO**:
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

**INCORRECTO**:
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

**En importer.php**:
```php
// ❌ INCORRECTO
update_field( 'flexible_content', $page_data['flexible_content'], $page_id );

// ✅ CORRECTO
if ( isset( $page_data['collapses'] ) ) {
    update_field( 'collapses', $page_data['collapses'], $page_id );
}
```

---

### ⚠️ PROBLEMA 4: Menú "Tienda" - URL/Label inconsistentes

**Síntoma**: El ítem de menú "Tienda" apunta mal o aparece sin etiqueta.

**Causa**:
- URL hardcodeada incorrecta (`/tienda`)
- O updates parciales del menú sin conservar título/posición.

**SOLUCIÓN**:
```php
// ✅ Recomendado
array( 'title' => 'Tienda', 'parent' => null ),

// ✅ Alternativa válida (si se define URL explícita)
array( 'title' => 'Tienda', 'url' => '/shop', 'parent' => null ),
```

Nota: el importer detecta dinámicamente el slug real de Shop y mantiene el label "Tienda".

---

### ✅ PROBLEMA 5: Imágenes no Cargan (General) - **RESUELTO**

**Síntoma**: Logos, imágenes de productos, sliders y fondos de secciones no se visualizaban.

**Causa**: Inconsistencia en el formato de `keys` al buscar attachment IDs. La función `chow_import_images` incluía `.webp` al generar keys (línea 668), pero las búsquedas en 10 lugares NO incluían `.webp` en sus arrays de extensiones.

**Ejemplo del problema**:
```php
// Al generar keys (línea 668) - INCLUÍA .webp ✅
$key = str_replace( array( '.webp', '.png', '.jpg', '.jpeg', '.gif' ), '', $base_filename );

// Al buscar keys (líneas 1014, 1121, etc.) - NO INCLUÍA .webp ❌
$logo_key = str_replace( array( '.png', '.jpg', '.jpeg', '.gif' ), '', $company['logo_field'] );
```

**Resultado**: Si el archivo era `libreria-logo-color.webp`:
- Key generada: `libreria-logo-color` ✅
- Key buscada: `libreria-logo-color.webp` ❌ (no coincidía)

**SOLUCIÓN APLICADA** (Commit ed4646c):
Agregado `.webp` a todos los `str_replace` que buscan keys de imágenes:
1. **Línea 1014**: Imágenes de portada de páginas
2. **Línea 1075**: Logos de empresa (desktop, mobile, footer)
3. **Líneas 1121-1173**: Sliders 1 a 5 del home
4. **Línea 1193**: Fondo de newsletter
5. **Línea 1224**: Fondo de sección redes sociales
6. **Línea 1243**: Imágenes del carousel de productos

**Archivos Modificados**:
- `demos/importer.php`: 10 líneas actualizadas con soporte completo para `.webp`
- Ahora todas las búsquedas de keys incluyen: `array( '.webp', '.png', '.jpg', '.jpeg', '.gif' )`

**Verificación**:
```bash
# Todas las búsquedas ahora usan el mismo formato
grep -n "str_replace.*\.webp.*\.png" demos/importer.php
# Resultado: 11 coincidencias (generación + 10 búsquedas) ✅
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
| `color_secundario` | color_picker | `#aebcbe` | Color secundario (actualizado para contraste) |
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
    'color_secundario'      => '#aebcbe', // Nuevo color
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
        'link' => '/shop',
    ),
    'slider_2' => array(
        'imagen' => 'libreria-slide02.png',
        'texto' => 'Literatura Premium',
        'link' => '/shop',
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
| `news_bg` | image | `libreria-fondo-news.png` | Nombre del archivo (con prefijo) |
| `formulario_news` | textarea | `[contact-form-7 id="42" ...]` | **DEBE SER SHORTCODE** |

**CORRECTO**:
```php
'newsletter' => array(
    'titulo'           => 'Suscribite a Nuestro Newsletter',
    'descripcion'      => 'Recibe ofertas exclusivas...',
    'news_bg'          => 'libreria-fondo-news.png', // ✅ Con prefijo
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

**CORRECTO**:
```php
'carrusel_productos_destacados' => array(
    array(
        'imagen'            => 'libreria-producto01.png',
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
| `fondo_redes` | image | `libreria-fondo-redes.png` |

**CORRECTO**:
```php
'redes_seccion' => array(
    'titulo'      => 'Síguenos en Redes',
    'descripcion' => 'Conecta con nosotros en nuestras redes',
    'fondo_redes' => 'libreria-fondo-redes.png', // ✅ Con prefijo
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
        'form_tag' => '<label> Tu nombre
    [text* nombre autocomplete:name] </label>

<label> Tu correo electrónico
    [email* email autocomplete:email] </label>

<label> Asunto
    [text* asunto] </label>

<label> Tu mensaje (opcional)
    [textarea mensaje] </label>

[submit "Enviar"]',
    ),
    array(
        'name'     => 'Newsletter Librería',
        'form_tag' => '<div id="news-form"><div class="row">
<div class="col-xs-12 col-lg-9"><label> [email* email placeholder"Ingresá tu e-mail"] </label></div>
<div class="col-xs-12 col-lg-3">[submit "ENVIAR"]</div>
</div></div>',
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

## Detalles de Estilos y Ajustes Visuales

Estos son los ajustes visuales que se aplicaron a la demo `Librería` para mejorar la estética y el contraste.

### Color Secundario Actualizado
- **Cambio**: De `#fdf5e6` (beige muy claro) a `#aebcbe` (gris azulado medio).
- **Razón**: El color `fdf5e6` generaba bajo contraste con textos y logos blancos, dificultando la legibilidad. El nuevo color `#aebcbe` proporciona un contraste mucho mayor y mejora la visibilidad de los elementos claros.

### Altura del Slider Ajustada
- **Cambio**: Altura del slider principal aumentada a `600px`.
- **Implementación**: Añadido CSS personalizado a la sección `custom_css` del `demo-libreria.php`.
- **Código CSS**:
    ```css
    /* Ajuste de altura del slider a 600px */
    /* Reemplaza '.main-slider-class' con la clase o ID real de tu slider si es diferente */
    .main-slider-class {
        height: 600px !important;
    }
    .main-slider-class .slick-list,
    .main-slider-class .slick-track,
    .main-slider-class .slick-slide img {
        height: 100% !important;
        object-fit: cover !important;
    }
    ```
- **Nota**: La clase `.main-slider-class` es un placeholder y puede necesitar ser ajustada (`id="home-slider"`, `.hero-slider`, etc.) si el HTML del tema utiliza una clase o ID diferente para el slider principal.

---

## Guía de Prompts de Imágenes

Este archivo contiene todos los prompts diseñados para ser usados con un generador de imágenes (como Midjourney, DALL-E, etc.) para crear los assets visuales de las demos del theme.

---

### Demo 1: "Páginas de Tinta" (Librería Elegante y Moderna)

**Estilo General:** Fotografías limpias, con luz suave y natural. Paleta de colores cálidos (marrones, beiges, naranjas suaves) con toques de azul oscuro. Minimalista pero acogedor.

#### Prompts

- **Logo:**
  `Logotipo minimalista para una librería elegante llamada "Páginas de Tinta". Utiliza una pluma o un icono de libro abierto, tipografía serif, colores: azul marino oscuro y beige. --style raw`

- **Slider (3 imágenes, relación de aspecto 16:9):**
  1. `Una foto hermosa, brillante y minimalista de una pila de libros de tapa dura con cubiertas artísticas, junto a una taza de café de cerámica sobre una mesa de madera. Luz suave de la mañana. --ar 16:9 --style raw`
  2. `Foto del interior de una librería moderna y limpia con estanterías organizadas, un cómodo sillón de lectura en una esquina y una gran ventana. --ar 16:9 --style raw`
  3. `Foto de primer plano de las manos de una persona sosteniendo un libro abierto, con páginas bellamente diseñadas. El fondo está ligeramente desenfocado. --ar 16:9 --style raw`

- **Productos (12 imágenes, relación de aspecto 3:4):**
  `Genera 12 imágenes. Foto limpia y minimalista de un solo libro de pie sobre un fondo beige liso. La portada del libro debe ser artística y minimalista. --ar 3:4 --style raw`

- **Banner para Página Flexible (relación de aspecto 21:9):**
  `Una foto elegante y plana de un cuaderno abierto, una pluma estilográfica, gafas y una pequeña planta en un escritorio blanco y limpio. --ar 21:9 --style raw`

---

### Demo 2: "Paso Firme" (Zapatería Urbana y Juvenil)

**Estilo General:** Fotografías vibrantes, con estética urbana. Sombras duras, fondos de cemento o ladrillo. Paleta de colores fríos con un color de acento fuerte (ej. amarillo neón).

#### Prompts

- **Logo:**
  `Logotipo moderno, audaz y urbano para una tienda de zapatillas llamada "Paso Firme". Utiliza un icono estilizado de huella de zapatilla, tipografía sans-serif, fuente en negrita. Colores: negro, blanco y amarillo neón. --style raw`

- **Slider (3 imágenes, relación de aspecto 16:9):**
  1. `Foto vibrante y dinámica de una persona usando zapatillas urbanas con estilo, saltando en el aire contra una pared de concreto con grafitis. --ar 16:9 --style raw`
  2. `Foto centrada en el producto de tres pares diferentes de zapatillas coloridas dispuestas ordenadamente en escalones de concreto. Estilo urbano, callejero. --ar 16:9 --style raw`
  3. `Foto de estilo de vida de un grupo de jóvenes sentados en un banco, mostrando sus diferentes estilos de zapatillas. Enfocarse en los zapatos. --ar 16:9 --style raw`

- **Productos (12 imágenes, relación de aspecto 3:4):**
  `Genera 12 imágenes. Foto de estudio profesional de una sola zapatilla urbana sobre un fondo gris sólido. La zapatilla debe tener un diseño moderno y colorido. Vista lateral. --ar 3:4 --style raw`

- **Banner para Página Flexible (relación de aspecto 21:9):**
  `Foto panorámica de una colección de cordones de zapatos en diferentes colores vibrantes, dispersos artísticamente. --ar 21:9 --style raw`

---

### Demo 3: "Bazar Dragón" (Productos Importados de China)

**Estilo General:** Colorido y ecléctico. Fondos con texturas y patrones. Mucho color rojo y dorado. Sensación de abundancia y variedad.

#### Prompts

- **Logo:**
  `Logotipo para una tienda de productos importados de China llamada "Bazar Dragón". Utiliza un icono de dragón amigable y simple combinado con una linterna china. Colores: rojo, dorado y blanco. --style raw`

- **Slider (3 imágenes, relación de aspecto 16:9):**
  1. `Foto colorida y vibrante de un puesto de mercado lleno de una variedad de productos chinos: farolillos de papel, juegos de té, pequeñas estatuas y abanicos. --ar 16:9 --style raw`
  2. `Foto de primer plano de un hermoso juego de té de cerámica china, con vapor saliendo de la tetera. --ar 16:9 --style raw`
  3. `Una foto plana de varios productos chinos divertidos y peculiaños como unidades USB novedosas, papelería colorida y pequeños juguetes sobre un fondo rojo texturizado. --ar 16:9 --style raw`

- **Productos (12 imágenes, relación de aspecto 3:4):**
  `Genera 12 imágenes. Una foto limpia y bien iluminada de un solo producto chino importado e interesante sobre un fondo blanco sólido. Ejemplos: un abanico de mano, una pequeña planta de jade, un tazón de cerámica único. --ar 3:4 --style raw`

- **Banner para Página Flexible (relación de aspecto 21:9):**
  `Foto de una colección de coloridos gatos de la suerte chinos (Maneki-neko) saludando al unísono. --ar 21:9 --style raw`

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
- [ ] Recordar: al importar, se borran todos los productos WooCommerce existentes antes de crear los del demo

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
- [ ] Si el item es "Tienda", priorizar título explícito `'Tienda'` en config y URL resuelta por importer
- [ ] Verificar orden esperado post-import (ejemplo habitual: Inicio, Tienda, Sobre Nosotros, Preguntas Frecuentes, Contacto)

### ✅ Activación de Demo
- [ ] Confirmar que solo un demo quede activo después de importar (`chow_active_demo`)
- [ ] Evitar hooks cruzados entre demos verificando que no haya múltiples `chow_demo_[id]_active` en `1`

### ✅ Imágenes
- [ ] Carpeta `demos/[nombre]/images/` existe
- [ ] Todos los archivos referenciados en el demo existen:
  - [ ] Logos: `libreria-logo-*.png`
  - [ ] Cover: `libreria-cover.png`
  - [ ] Productos: `libreria-producto0*.png`
  - [ ] Slides: `libreria-slide0*.png`
  - [ ] Fondos: `libreria-fondo-*.png` (¡Con prefijo!)

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
            'color_secundario'      => '#aebcbe', // Ejemplo: nuevo color
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
                'news_bg'          => 'libreria-fondo-news.png', // Ejemplo: con prefijo
                'formulario_news'  => 'Contacto',  // Nombre del formulario CF7
            ),
            'redes_seccion' => array(
                'titulo'      => 'Síguenos',
                'descripcion' => 'En nuestras redes sociales',
                'fondo_redes' => 'libreria-fondo-redes.png', // Ejemplo: con prefijo
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
1. ✅ **RESUELTO (Commit ed4646c)**: Soporte completo para extensión .webp agregado
2. Verificar que el archivo existe en `demos/[nombre]/images/`
3. Verificar que el nombre coincide exactamente (mayúsculas/minúsculas)
4. Verificar formato: debe ser `[demo-id]-[nombre].[extensión]` (ej. `libreria-logo-color.webp`)
5. Verificar que ACF esté guardando el ID de attachment, no la URL

### Formularios no se muestran
1. Verificar que `formulario_news` contiene el nombre del formulario, no un shortcode
2. El importer debe generar automáticamente el shortcode
3. Si no aparece, revisar que CF7 esté instalado

### Páginas flexible sin contenido
1. Verificar que `'content'` se guarda en `\'texto_contenido\'` (no post_content)
2. Verificar que `'flexible_content'` se renombró a `\'collapses\'`
3. Verificar estructura de collapses: `\'titulo_collapse\'` y `\'contenido_collapse\'`

### Menú incorrecto
1. Verificar que URLs son `/shop` (no `/tienda`)
2. Verificar que pages existan con los slugs correctos
3. Si aparece `(no label)`, revisar updates parciales de `wp_update_nav_menu_item` que no incluyan `menu-item-title`
4. Confirmar que solo el demo activo está cargando hooks de menú
5. Ejecutar "Restaurar Plantilla" del demo activo para regenerar menú limpio

### Productos no coinciden con el demo
1. Confirmar que WooCommerce está activo
2. Revisar logs de importación: debe verse limpieza previa de productos antes del PASO 4
3. Si quedan productos viejos, volver a correr "Restaurar Plantilla" y validar errores en log

### Imágenes de fondo no cargan
1. ✅ **RESUELTO (Commit ed4646c)**: Soporte completo para .webp en backgrounds (newsletter, redes)
2. Verificar que el nombre del archivo en `demo-[nombre].php` incluye el prefijo del demo:
   - ✅ Correcto: `'news_bg' => 'libreria-fondo-news.webp'`
   - ❌ Incorrecto: `'news_bg' => 'fondo-news.webp'`
3. Verificar que el archivo existe con ese nombre exacto en `demos/[nombre]/images/`
4. Las extensiones soportadas son: `.webp`, `.png`, `.jpg`, `.jpeg`, `.gif`

---

## Contacto y Reportes

Si encuentras nuevos problemas o casos especiales, documenta:
1. El problema exacto observado
2. Los pasos para reproducirlo
3. El código relevante
4. La solución aplicada

Luego actualiza este documento para que otros developers lo eviten.
