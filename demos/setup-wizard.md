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
- Estos datos se guardan en los campos ACF de la página de opciones `group_empresa.json`
- `empresa_nombre` → ACF field `empresa_nombre`
- `empresa_descripcion` → ACF field `empresa_descripcion`
- etc.

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
- `color_principal` → ACF field `color_principal` (guarda valor hex, ej: `#2c3e50`)
- `color_secundario` → ACF field `color_secundario`
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
    - Librería "Páginas de Tinta"
    - Zapatería "Paso Firme"
    - Bazar "Bazar Dragón"

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
- Se guardan en los campos ACF de `group_empresa.json`
- Campos: `redes_facebook`, `redes_instagram`, etc.

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

Cada demo (`demo-libreria.php`, `demo-zapateria.php`, `demo-bazar.php`) devuelve un array estructurado:

```php
return array(
    'id' => 'libreria',
    'name' => 'Librería "Páginas de Tinta"',
    'description' => 'Una demo elegante para librerías y editoriales',
    'theme_options' => array(
        'color_principal' => '#2c3e50',
        'color_secundario' => '#fdf5e6',
        'custom_css_field' => 'body { font-family: "Lora", serif; }',
    ),
    'slider_home' => array(
        array(
            'titulo' => 'Descubre Historias',
            'descripcion' => 'Nuestras últimas novedades literarias',
            'imagen' => 'demos/libreria/images/slider-01.jpg',
            'enlace' => '/tienda',
        ),
        // ... más slides
    ),
    'home_blocks' => array(
        array(
            'titulo' => 'Novedades',
            'tipo' => 'ultimos',
            'cantidad' => 4,
            'layout' => 'columnas',
            'card_style' => 'classic',
        ),
        // ... más bloques
    ),
    'categories' => array(
        array('name' => 'Novelas', 'slug' => 'novelas'),
        array('name' => 'Poesía', 'slug' => 'poesia'),
    ),
    'products' => array(
        array(
            'title' => 'El Quijote',
            'price' => 45.99,
            'sku' => 'LIB-001',
            'category' => 'novelas',
            'image' => 'demos/libreria/images/producto-01.jpg',
            'description' => 'Una clásica novela de aventuras',
            'is_featured' => true,
        ),
        // ... 11 productos más
    ),
    'pages' => array(
        array(
            'title' => 'Sobre Nosotros',
            'slug' => 'sobre-nosotros',
            'template' => 'flexible-page.php',
            'content' => array(
                'activo_encabezado' => true,
                'imagen_portada' => 'demos/libreria/images/banner.jpg',
                'titulo' => 'Nuestra Historia',
                // ... más campos ACF
            ),
        ),
        // ... más páginas
    ),
);
```

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

4. **Crear Productos:**
   ```php
   foreach ( $demo_config['products'] as $product ) {
       $post_id = wp_insert_post( array(
           'post_title' => $product['title'],
           'post_type' => 'product',
           'post_status' => 'publish',
       ) );
       
       // Guardar metadata del producto (precio, SKU, etc.)
       update_post_meta( $post_id, '_price', $product['price'] );
       update_post_meta( $post_id, '_sku', $product['sku'] );
       
       // Asignar imagen destacada
       set_post_thumbnail( $post_id, $attachment_id );
       
       // Asignar categoría
       wp_set_post_terms( $post_id, $product['category'], 'product_cat' );
   }
   ```

5. **Crear Páginas:**
   ```php
   foreach ( $demo_config['pages'] as $page ) {
       $page_id = wp_insert_post( array(
           'post_title' => $page['title'],
           'post_name' => $page['slug'],
           'post_type' => 'page',
           'post_status' => 'publish',
           'page_template' => $page['template'],
       ) );
       
       // Guardar campos ACF
       foreach ( $page['content'] as $field => $value ) {
           update_field( $field, $value, $page_id );
       }
   }
   ```

6. **Actualizar Opciones del Tema:**
   ```php
   foreach ( $demo_config['theme_options'] as $field => $value ) {
       update_field( $field, $value, 'option' );
   }
   
   // Slider
   update_field( 'slider_home', $demo_config['slider_home'], 'option' );
   
   // Bloques de productos
   update_field( 'bloques_productos', $demo_config['home_blocks'], 'option' );
   ```

7. **Marcar Como Completado:**
   ```php
   update_option( 'chow_setup_completed', true );
   ```

---

## MVP Scope

**Lo que SÍ se implementa:**
- ✅ Setup Wizard UI (5 steps)
- ✅ Importación de demos (Librería, Zapatería, Bazar)
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
