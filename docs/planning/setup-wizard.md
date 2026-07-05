# Setup Wizard - Documentación

## Objetivo

Crear un asistente de configuración paso a paso que guíe al cliente a través de la configuración inicial del sitio, enfocándose en el MVP que permite la **importación de demos prefabricadas**.

---

## Visión General del Setup Wizard

El Setup Wizard es una experiencia de onboarding que aparece la primera vez que un usuario accede al panel de WordPress. Su propósito es:

1. Recopilar información de la empresa.
2. Permitir al cliente elegir una demo o cargar contenido personalizado.
3. Configurar opciones básicas del tema.
4. Importar todo automáticamente.

### Flujo Básico (MVP)

```
Cliente accede por primera vez al panel
         ↓
Setup Wizard se activa
         ↓
Step 1: Información de la Empresa
         ↓
Step 2: Branding (Colores y Logo)
         ↓
Step 3: Importar Demo (MVP Focus)
         ↓
Step 4: Configuración de Redes Sociales
         ↓
Step 5: Resumen y Confirmación
         ↓
Importación automática
         ↓
Acceso al panel normal
```

---

## Detalles de Cada Step (MVP)

### Step 1: Información de la Empresa

**Campos a recopilar:**
- Nombre de la empresa (text)
- Descripción corta (textarea)
- Correo de contacto (email)
- Teléfono (tel)
- Dirección (text)
- País (select)

**Destino de los datos:**
- Estos datos se guardan en los campos SCF de la página de opciones (grupo `group_empresa.json`)
- Los campos del array `company` se mapean directamente: `direccion`, `telefonos`, `mail`, etc.

**Validación:**
- Campos obligatorios: nombre, email, teléfono.
- Email debe ser válido.
- Teléfono debe tener formato correcto.

---

### Step 2: Branding (Colores y Logo)

**Campos a recopilar:**
- Color principal (color picker)
- Color secundario (color picker)
- Logo (file upload)

**Destino de los datos:**
- `color_principal` → SCF field `color_principal` (guarda valor hex, ej: `#2c3e50`)
- `color_secundario` → SCF field `color_secundario`
- Logo → Se carga a la librería de medios de WordPress y se asigna como custom logo del sitio

**Validación:**
- Los colores deben ser valores hex válidos.
- El logo debe ser una imagen (JPG, PNG, SVG).
- Tamaño máximo: 2MB.

---

### Step 3: Importar Demo (MVP FOCUS)

**Opciones disponibles:**

```
¿Cómo deseas cargar tu contenido?

[ ] Importar Demo Prefabricada
    - Librería "Páginas de Tinta" (existente)
    - Pastelería "Harina & Miel" (existente)

[ ] Cargar CSV Personalizado (Fase 2 - No implementado en MVP)
    - Descargar template
    - Subir CSV
    - Subir imágenes
```

**Para MVP, solo se implementa la importación de demos.**

**Función:**
- El usuario selecciona una demo de la lista.
- El sistema obtiene todos los datos de la demo desde `demos/demo-nombre.php`.
- Se ejecuta `chow_do_import('nombre')` que:
  1. Lee el archivo de configuración de la demo.
  2. Crea las categorías de productos.
  3. Copia las imágenes a la librería de medios.
  4. Crea los 12 productos con sus imágenes.
  5. Crea las 3 páginas flexibles de ejemplo.
  6. Actualiza todas las opciones del tema (slider, bloques, etc.).

**Aviso Importante:**
```
⚠️ ATENCIÓN
Esta acción importará 12 productos, 3 páginas y configuraciones predefinidas.
Si ya tienes contenido, será sobreescrito.
¿Deseas continuar?

[ Cancelar ]  [ Importar ]
```

---

### Step 4: Redes Sociales y Contacto

**Campos a recopilar:**
- Facebook (URL)
- Instagram (URL)
- WhatsApp (número)
- LinkedIn (URL)
- Twitter (URL)
- YouTube (URL)

**Destino:**
- Se guardan en los campos SCF de `group_empresa.json`
- Campos: `facebook_link`, `instagram_link`, `twitter_link`, `wsp_link`

**Validación:**
- Verificar que sean URLs válidas (si se completan).
- Números de WhatsApp con formato internacional.

---

### Step 5: Resumen y Confirmación

**Mostrar:**
- Nombre de la empresa
- Demo seleccionada
- Resumen de lo que se va a importar:
  - "12 productos"
  - "3 páginas"
  - "Slider con imágenes"
  - "Bloques de la home configurados"
  - "Colores y branding aplicados"

**Botón Final:**
- "Finalizar Setup" → Ejecuta la importación y redirige al panel.

---

## Arquitectura del Setup Wizard

### Estructura de Archivos

```
demos/
├── setup-wizard.php              # Lógica principal del wizard
├── setup-wizard-frontend.php     # HTML/JS del wizard
├── setup-wizard-styles.css       # Estilos del wizard
├── setup-wizard-handler.php      # Manejador AJAX
└── ...
```

### Cómo Funciona Internamente

1. **Detección del Primer Acceso:**
   - En `functions.php`, se añade un hook `admin_init` que verifica si el wizard ya fue completado.
   - Si no existe la opción `chow_setup_completed`, muestra el wizard.

2. **Carga del Wizard:**
   - Se carga `setup-wizard-frontend.php` que renderiza el HTML del wizard.
   - Se encolsan los estilos CSS y scripts JavaScript.

3. **Navegación Entre Steps:**
   - JavaScript maneja la navegación del wizard.
   - Cada step tiene un validador antes de proceder al siguiente.
   - Los datos se guardan en el servidor progresivamente (vía AJAX).

4. **Importación:**
   - En Step 5, al hacer clic en "Finalizar Setup", se ejecuta una llamada AJAX.
   - Esta llama a la función `chow_handle_setup_wizard_import()`.
   - La función:
     1. Obtiene los datos guardados temporalmente.
     2. Ejecuta `chow_do_import()` con la demo seleccionada.
     3. Marca la opción `chow_setup_completed` como `true`.
     4. Devuelve JSON con éxito/error.
   - JavaScript redirige al panel principal.

---

## Datos de las Demos

Cada demo (`demo-libreria.php`, `demo-pasteleria.php`) devuelve un array estructurado con este formato actual:

```php
return array(
    'id'          => 'libreria',
    'name'        => 'Librería "Páginas de Tinta"',
    'description' => 'Una demo elegante para librerías y editoriales',
    'image'       => 'libreria-cover.webp',
    'version'     => '1.0',
    'card_style'  => 'hover_visual',

    'company' => array(
        'color_principal'       => '#2c3e50',
        'color_secundario'      => '#aebcbe',
        'color_texto'           => '#5f5f5f',
        'color_fondo'           => '#ffffff',
        'logo_header_desktop'   => 'libreria-logo-color.webp',
        'logo_header_mobile'    => 'libreria-logo-blanco.webp',
        'logo_footer'           => 'libreria-logo-blanco.webp',
        'direccion'             => 'Av. Corrientes 1234, Buenos Aires',
        'telefonos'             => '+54 11 4567-8900',
        'mail'                  => 'info@paginasdetinta.com',
        'facebook_link'         => '#',
        'instagram_link'        => '#',
        'twitter_link'          => '#',
        'wsp_link'              => '5491145678900',
        'logos_legales'         => '',
    ),

    'categories' => array(
        array('name' => 'Novelas', 'slug' => 'novelas', 'description' => '...'),
        array('name' => 'Poesía', 'slug' => 'poesia', 'description' => '...'),
    ),

    'products' => array(
        array(
            'name'              => 'El Quijote',
            'slug'              => 'el-quijote',
            'price'             => '45.99',
            'sale_price'        => '',
            'stock'             => 50,
            'category'          => 'Novelas',
            'image'             => 'libreria-producto01.webp',
            'featured'          => true,
            'on_sale'           => false,
            'description'       => 'Una clásica novela de aventuras',
            'short_description' => 'Clásico de la literatura',
        ),
    ),

    'pages' => array(
        array(
            'title'    => 'Sobre Nosotros',
            'slug'     => 'sobre-nosotros',
            'template' => 'flexible-page',
            'content'  => '<p>Historia de la marca...</p>',
            'collapses' => array(
                array(
                    'titulo_collapse'    => '¿Pregunta?',
                    'contenido_collapse' => 'Respuesta...',
                ),
            ),
        ),
    ),

    'forms' => array(
        array(
            'name'     => 'Contacto Librería',
            'form_tag' => '<label>Nombre [text* nombre]</label>...',
        ),
    ),

    'home' => array(
        'slider_1' => array(
            'imagen' => 'libreria-slide01.webp',
            'texto'  => 'Descubre Nuevos Mundos',
            'link'   => '/shop',
        ),
        'product_blocks' => array(
            array(
                'titulo'      => 'Novedades',
                'tipo'        => 'ultimos',
                'cantidad'    => 4,
                'layout'      => 'columnas',
                'columnas'    => 'col-lg-3',
                'card_style'  => 'hover_visual',
            ),
        ),
        'newsletter' => array(
            'titulo'          => 'Newsletter',
            'news_bg'         => 'libreria-fondo-news.webp',
            'formulario_news' => 'Newsletter Librería',
        ),
        'redes_seccion' => array(
            'titulo'      => 'Síguenos',
            'fondo_redes' => 'libreria-fondo-redes.webp',
        ),
        'sections' => array(
            'slide'              => true,
            'productos-1'        => true,
            'news'               => true,
            'redes'              => true,
        ),
    ),

    'menu' => array(
        'name'  => 'Menú Librería',
        'items' => array(
            array('title' => 'Inicio',  'url' => '/',        'parent' => null),
            array('title' => 'Tienda',  'url' => '/shop',    'parent' => null),
            array('title' => 'Contacto','url' => '/contacto','parent' => null),
        ),
    ),

    'custom_css' => ':root { --slider-height: 600px; }',
);
```

> **Nota:** La estructura actual NO usa `theme_options`, `slider_home` ni `home_blocks`. Esos nombres son de una versión anterior. Ver la guía completa en `demos/DEMO.md` para la referencia actualizada de campos.

---

## Flujo de Importación (MVP)

Cuando el usuario selecciona una demo y confirma:

1. **Leer Configuración:**
   ```php
   $demo_config = include 'demos/demo-libreria.php';
   ```

2. **Copiar Imágenes:**
   - Iterar sobre todas las imágenes de `demos/libreria/images/`.
   - Copiar cada una a `wp-content/uploads/`.
   - Crear attachment post en WordPress.
   - Guardar el ID del attachment para vincularlo después.

3. **Crear Categorías:**
   ```php
   foreach ( $demo_config['categories'] as $cat ) {
       wp_insert_term( $cat['name'], 'product_cat', array( 'slug' => $cat['slug'] ) );
   }
   ```

4. **Crear Productos (usando WC_Product):**
   ```php
   foreach ( $demo_config['products'] as $product ) {
       $wc_product = new WC_Product_Simple();
       $wc_product->set_name( $product['name'] );
       $wc_product->set_slug( $product['slug'] );
       $wc_product->set_description( $product['description'] );
       $wc_product->set_short_description( $product['short_description'] );
       $wc_product->set_regular_price( floatval( str_replace( ',', '.', $product['price'] ) ) );
       $wc_product->set_sku( $product['slug'] );
       $wc_product->set_stock_quantity( $product['stock'] ?? 50 );
       $wc_product->set_stock_status( 'instock' );
       $wc_product->set_featured( $product['featured'] ?? false );
       
       $product_id = $wc_product->save();
       
       // Asignar imagen destacada
       if ( ! empty( $attachment_id ) ) {
           set_post_thumbnail( $product_id, $attachment_id );
       }
       
       // Asignar categoría
       wp_set_post_terms( $product_id, $product['category'], 'product_cat' );
   }
   ```

5. **Crear Páginas:**
   ```php
   foreach ( $demo_config['pages'] as $page ) {
       $page_id = wp_insert_post( array(
           'post_title'   => $page['title'],
           'post_name'    => $page['slug'],
           'post_type'    => 'page',
           'post_status'  => 'publish',
           'page_template' => $page['template'],
       ) );
       
       // Guardar contenido en SCF según el template
       if ( 'flexible-page' === $page['template'] ) {
           update_field( 'texto_contenido', $page['content'], $page_id );
           if ( isset( $page['collapses'] ) ) {
               update_field( 'collapses', $page['collapses'], $page_id );
           }
       } else {
           wp_update_post( array( 'ID' => $page_id, 'post_content' => $page['content'] ) );
       }
   }
   ```

6. **Actualizar Opciones del Tema (usando el importer existente):**
   ```php
   // Delegar toda la importación al importer probado
   chow_do_import( $demo_id );
   ```

7. **Marcar Como Completado:**
   ```php
   update_option( 'chow_setup_completed', true );
   ```

---

## MVP Scope

**Lo que SÍ se implementa:**
- ✅ Setup Wizard UI (5 steps)
- ✅ Importación de demos (Librería, Pastelería)
- ✅ Carga de imágenes a la librería de medios
- ✅ Creación de productos y páginas
- ✅ Actualización de opciones del tema
- ✅ AJAX para importación sin recargar página

**Lo que NO se implementa (Fase 2):**
- ❌ CSV Importer
- ❌ Múltiples uploads de imágenes
- ❌ Validación avanzada de datos
- ❌ Rollback/Undo de importaciones
- ❌ Histórico de setup wizard ejecutados

---

## Próximos Pasos

1. Crear `demos/setup-wizard.php` con la lógica principal.
2. Crear `demos/setup-wizard-frontend.php` con el HTML/JS.
3. Crear `demos/setup-wizard-styles.css` con los estilos.
4. Integrar en `functions.php` para que se cargue automáticamente.
5. Crear/completar los archivos `demos/demo-*.php` con la data.
6. Crear la estructura de carpetas `demos/[demo]/images/`.
7. Añadir las imágenes generadas en cada carpeta.
8. Probar el flujo completo.
