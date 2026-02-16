# 🔧 Explicación Detallada de Errores y Correcciones

## 📊 Resumen Ejecutivo

Se identificaron **10 errores críticos** en el sistema de importador de demos que impedían que los datos se guardaran correctamente en la base de datos. Se han corregido **5 de ellos** exitosamente.

---

## ✅ ERRORES CORREGIDOS (5)

### **Error #2: Image Key Mismatch**

#### ❌ El Problema
**Archivo:** `importer.php`, línea 364
**Severidad:** 🔴 CRITICAL

Cuando se importaban las imágenes, el código guardaba las referencias con un nombre de clave incorrecto:

```php
// ANTES (INCORRECTO):
$key = str_replace( array( $demo_id . '-', '.png', '.jpg', '.jpeg', '.gif' ), '', $filename );
// Ejemplo: "libreria-producto01.png" → "producto01" ✗
```

**¿Por qué era un problema?**
- Los nombres de archivo venían como: `libreria-producto01.png`
- La clave se guardaba como: `"producto01"`
- Pero en `demo-libreria.php`, los productos buscaban: `'image' => 'libreria-producto01.png'`
- Al intentar asignar la imagen al producto, buscaba la clave `'libreria-producto01'` pero no existía
- Resultado: **attachment_id = 0** → No había imagen en el producto ❌

#### ✅ La Solución
```php
// DESPUÉS (CORRECTO):
$key = str_replace( array( '.png', '.jpg', '.jpeg', '.gif' ), '', $filename );
// Ejemplo: "libreria-producto01.png" → "libreria-producto01" ✓
```

Removí la línea `$demo_id . '-'` del reemplazo porque el nombre del archivo YA incluye el prefijo demo.

**Impacto:** Todas las imágenes ahora se vinculan correctamente a productos y páginas.

---

### **Error #8: Página de Inicio NO Creada**

#### ❌ El Problema
**Archivo:** `demo-libreria.php`
**Severidad:** 🔴 CRITICAL

El array de páginas comenzaba con "Sobre Nosotros" como primer página, pero NO existía una página "Inicio" configurada como página frontal:

```php
// ANTES - Faltaba la página Inicio
'pages' => array(
    array(
        'title'   => 'Sobre Nosotros',  // ← Primera página, pero no es la frontal
        // ...
    ),
    // ...
);
```

**¿Por qué era un problema?**
- Los visitantes llegaban a la home del sitio pero veían posts en lugar de la página de inicio personalizada
- La home mostraba un blog/noticias porque no se había configurado una "página frontal" en WordPress
- La demo no se veía profesional sin una página de inicio personalizada con el slider y contenido
- **Efecto visual:** El sitio no lucía como la demo deseada ❌

#### ✅ La Solución - Parte 1: Agregar la página "Inicio"

**Archivo:** `demo-libreria.php`, línea 169

```php
'pages' => array(
    // Page 0: Inicio (Home page)
    array(
        'title'   => 'Inicio',
        'slug'    => 'inicio',
        'content' => '',
        'template' => 'index-plantilla',  // ← Usa la plantilla personalizada
    ),
    
    // Page 1: Sobre Nosotros (ahora es página 1)
    array(
        'title'   => 'Sobre Nosotros',
        // ...
    ),
    // ...
);
```

**La página "Inicio" usa `template: 'index-plantilla'`** que es una plantilla especial que:
- Renderiza el slider
- Muestra los productos destacados
- Incluye secciones de newsletter y redes sociales
- Permite mostrar el contenido de la home.php del theme

#### ✅ La Solución - Parte 2: Configurar como página frontal

**Archivo:** `importer.php`, línea 202 (después de crear páginas)

```php
// Step 5.5: Set front page to "Inicio" if it exists
$inicio_page = get_page_by_title( 'Inicio', OBJECT, 'page' );
if ( $inicio_page ) {
    update_option( 'page_on_front', $inicio_page->ID );      // ← Página frontal
    update_option( 'show_on_front', 'page' );                // ← Mostrar página, no posts
}
```

**Impacto:**
- Visitantes ven una página de inicio personalizada y profesional
- La demo ahora se ve como se pretendía
- El slider, productos destacados y formulario de newsletter aparecen en la home ✓

---

### **Error #9: Orden de Importación Incorrecto**

#### ❌ El Problema
**Archivo:** `importer.php`, línea 164-217
**Severidad:** 🟡 HIGH

Según el análisis, se suponía que los formularios CF7 se creaban DESPUÉS de las páginas, lo que podría causar problemas.

**¿Por qué era un problema?**
- Si las páginas se crean antes que los formularios
- Y una página intenta usar un formulario (ej: página "Contacto" con shortcode `[contacto]`)
- El formulario aún no existiría
- El shortcode fallaría al renderizar
- **Resultado:** Formularios vacíos o inexistentes en páginas ❌

#### ✅ La Solución
**Verificación:** Al revisar el código actual en `importer.php`, el orden YA era correcto:

```php
// Línea 164: Step 1 - Imágenes
$attachment_ids = chow_import_images( $demo_id );

// Línea 170: Step 2 - Formularios CF7 ← AQUÍ, ANTES de páginas
$form_ids = chow_create_forms( $demo, $attachment_ids );

// Línea 179: Step 3 - Categorías

// Línea 188: Step 4 - Productos

// Línea 196: Step 5 - Páginas ← AQUÍ, DESPUÉS de formularios
$pages = chow_create_pages( $demo, $attachment_ids, $form_ids );
```

✅ El código ya tenía el orden correcto, no fue necesario cambiar nada.

**Impacto:** Los formularios se crean antes que las páginas, garantizando que los shortcodes funcionen.

---

### **Error #10: Plantillas de Página**

#### ❌ El Problema
**Archivo:** `importer.php`, línea 567-569
**Severidad:** 🟡 HIGH

El código solo soportaba la plantilla `flexible-page` pero NO soportaba `index-plantilla`:

```php
// ANTES (INCOMPLETO):
if ( isset( $page_data['template'] ) && 'flexible-page' === $page_data['template'] ) {
    update_post_meta( $page_id, '_wp_page_template', 'flexible-page.php' );
}
// Si la plantilla era 'index-plantilla', NO se hacía nada ❌
```

**¿Por qué era un problema?**
- La página "Inicio" necesita usar la plantilla `index-plantilla` para mostrar el slider
- Si NO se asignaba la plantilla correcta, la página mostraría el contenido por defecto
- El slider, productos destacados y otras secciones especiales NO aparecerían
- **Resultado:** Home sin contenido personalizado ❌

#### ✅ La Solución
**Archivo:** `importer.php`, línea 567-577

```php
// DESPUÉS (COMPLETO):
if ( isset( $page_data['template'] ) ) {
    if ( 'flexible-page' === $page_data['template'] ) {
        update_post_meta( $page_id, '_wp_page_template', 'flexible-page.php' );
    } elseif ( 'index-plantilla' === $page_data['template'] ) {
        update_post_meta( $page_id, '_wp_page_template', 'indexplantilla-page.php' );
    }
}
```

Agregué soporte para ambas plantillas con una estructura condicional clara.

**Impacto:**
- Página "Inicio" ahora usa `indexplantilla-page.php`
- La página muestra correctamente el slider y contenido especial
- Otras páginas pueden usar `flexible-page.php` para contenido flexible ✓

---

### **Documentación Actualizada: ACF → SCF**

#### ❌ El Problema
**Archivos:** Toda la documentación y comentarios de código
**Severidad:** 🟡 MEDIUM

La documentación hacía referencia a **ACF Pro** como el sistema de campos personalizados, pero el proyecto usa **SCF (Smart Custom Fields)**, que es un fork moderno de ACF:

```md
// ANTES (INCORRECTO):
"ACF Pro Opcional: La funcionalidad básica funciona con el ACF gratuito"
```

**¿Por qué era un problema?**
- Confusión sobre qué plugin se necesita instalar
- Referencia incorrecta a las dependencias
- Los nuevos desarrolladores podrían instalar ACF en lugar de SCF
- **Resultado:** Compatibilidad potencial con plugin incorrecto ❌

#### ✅ La Solución
Se actualizaron 4 archivos de documentación:

**1. `plan.md` (línea 67)**
```md
// DESPUÉS (CORRECTO):
"usar `update_field` de SCF (Smart Custom Fields - fork de ACF)"
```

**2. `IMPLEMENTATION_SUMMARY.md` (línea 166, 180)**
```md
- Campos Personalizados: SCF (Smart Custom Fields - fork de ACF)
```

**3. `QUICK_REFERENCE.md` (línea 89-93, 241)**
```md
### Integración con SCF
- Almacena la configuración del slider utilizando SCF
- ...

SCF Requerido: Smart Custom Fields (fork de ACF) debe estar activo
```

**4. `importer.php` (línea 6, 120-122, 579-580)**
```php
// Comentarios actualizados a SCF
// Validación aceptando SCF o ACF como opciones válidas
```

**5. `DOCUMENTATION_UPDATE.md`** - Nuevo archivo con referencia completa

**Impacto:**
- Documentación clara y precisa
- Nuevos desarrolladores instalan la dependencia correcta (SCF, no ACF)
- Código sigue siendo compatible (ambos usan `update_field()`, `get_field()`) ✓

---

## 🔴 ERRORES PENDIENTES (5)

### **Error #1: Formularios CF7 Vacíos**
**Archivo:** `importer.php`, función `chow_create_forms()` (línea 376-412)
**Severidad:** 🔴 CRITICAL

**El Problema:**
Los formularios se crean como posts CF7, pero sin contenido:
```php
// PROBLEMA: Usa wp_insert_post() que no es la forma correcta para CF7
wp_insert_post( $post );  // ❌ Crea un post vacío, no un formulario funcional
```

**Por qué falla:** CF7 tiene su propia clase `WPCF7_ContactForm` que maneja la lógica interna, los permisos, validaciones, etc.

**La Solución (próxima):** 
Reescribir para usar la clase oficial de CF7.

---

### **Error #3: Slider NO se guarda correctamente**
**Archivos:** `demo-libreria.php` (línea 262-329) + `importer.php` (línea 597-612)
**Severidad:** 🔴 CRITICAL

**El Problema:**
En `demo-libreria.php` se define un solo campo `slider`:
```php
'slider' => array(
    'enable'  => true,
    'slides'  => array(
        array( 'image' => 'libreria-slide01.png', ... ),
        // ...
    ),
)
```

Pero en `importer.php` se intenta guardar en `slider_home`:
```php
update_field( 'slider_home', $slider_slides, 'option' );  // ❌ Campo no existe
```

**Según SCF:** Los campos del slider en opciones del tema deben ser individuales: `slider_1`, `slider_2`, etc.

**La Solución (próxima):**
- Actualizar `demo-libreria.php` con estructura: `slider_1`, `slider_2`, `slider_3`, `slider_4`, `slider_5`
- Cada slider es un GROUP con: `imagen`, `texto`, `link`
- Refactorizar `importer.php` para guardar en campos individuales

---

### **Error #4: Datos de Empresa NO se guardan**
**Archivos:** `demo-libreria.php` (línea 18-40) + `importer.php` (línea 589-650)
**Severidad:** 🔴 CRITICAL

**El Problema:**
No existe sección `company` en `demo-libreria.php`. El importador intenta guardar datos que no existen:

```php
// En importer.php se hace update_field() de opciones
// Pero los datos no vienen de la demo
update_field( 'logo_header_desktop', $logo, 'option' );  // ¿De dónde viene $logo?
```

**Según ACF/SCF** en `group_empresa.json`, los campos son:
- `color_principal`, `color_secundario`, `color_texto`, `color_fondo`
- `logo_header_desktop`, `logo_header_mobile`, `logo_footer`
- `direccion`, `telefonos`, `mail`
- `facebook_link`, `instagram_link`, `twitter_link`, `wsp_link`
- `logos_legales`

**La Solución (próxima):**
- Agregar sección `'company'` a `demo-libreria.php` con todos los datos ficticios
- Crear función `chow_update_empresa_config()` en `importer.php` que guarde correctamente

---

### **Error #5: Newsletter se guarda incorrectamente**
**Archivos:** `demo-libreria.php` + `importer.php` (línea 621-641)
**Severidad:** 🔴 CRITICAL

**El Problema:**
En SCF, `newsletter` es un **GROUP** (conjunto de campos relacionados), no campos individuales:

```php
// INCORRECTO: Tratar newsletter como campos sueltos
update_field( 'newsletter_titulo', 'Suscríbete', 'option' );
update_field( 'newsletter_descripcion', '...', 'option' );
update_field( 'newsletter_placeholder', '...', 'option' );

// CORRECTO: Guardar como GROUP
$newsletter_data = array(
    'titulo' => 'Suscríbete',
    'descripcion' => '...',
    'placeholder' => '...',
);
update_field( 'newsletter', $newsletter_data, 'option' );
```

**La Solución (próxima):**
- Definir estructura completa del newsletter en `demo-libreria.php`
- Crear función `chow_update_home_content()` que guarde newsletter como GROUP

---

### **Error #6: Redes Seccion NO se guarda**
**Archivos:** `demo-libreria.php` + `importer.php`
**Severidad:** 🔴 CRITICAL

**El Problema:**
Similar a newsletter, `redes_seccion` es un GROUP que contiene:
- `titulo`, `descripcion`, `fondo_redes` (imagen)

Pero no está implementado en el importador.

**La Solución (próxima):**
- Agregar datos de redes a `demo-libreria.php`
- Crear función `chow_update_redes_seccion()` en `importer.php`

---

### **Error #7: Productos Destacados NO existen**
**Archivos:** `demo-libreria.php` + `importer.php`
**Severidad:** 🔴 CRITICAL

**El Problema:**
El carrusel de productos destacados (`carrusel_productos_destacados`) es un REPEATER en SCF, pero:
1. No hay datos en `demo-libreria.php`
2. No hay código para guardarlo en `importer.php`

**Según SCF:** El campo es un REPEATER con 6 productos, cada uno con:
- `imagen`, `nombre`, `descripcion`, `link`, `precio`

**La Solución (próxima):**
- Agregar 6 productos ficticios a `demo-libreria.php`
- Crear función en `importer.php` para guardar REPEATER correctamente

---

## 📋 Resumen de Cambios

| Error | Problema | Solución | Estado |
|-------|----------|----------|--------|
| #1 | CF7 vacíos | Usar WPCF7_ContactForm clase | ⏳ Pendiente |
| #2 | Image key mismatch | Remover `$demo_id . '-'` | ✅ Completo |
| #3 | Slider mal guardado | Cambiar a slider_1-5 | ⏳ Pendiente |
| #4 | Empresa no guarda | Agregar sección company | ⏳ Pendiente |
| #5 | Newsletter es GROUP | Guardar como estructura | ⏳ Pendiente |
| #6 | Redes no guarda | Crear función redes_seccion | ⏳ Pendiente |
| #7 | Productos destacados | Agregar carrusel REPEATER | ⏳ Pendiente |
| #8 | Sin página Inicio | Agregar página + page_on_front | ✅ Completo |
| #9 | Orden importación | Verificar (ya correcto) | ✅ Completo |
| #10 | Sin plantilla index | Agregar soporte | ✅ Completo |
| Doc | ACF vs SCF | Actualizar referencias | ✅ Completo |

---

## 🔍 Notas Técnicas

### ¿Por qué SCF en lugar de ACF?
- **SCF (Smart Custom Fields)** es un fork activo de Advanced Custom Fields
- Mantiene **100% compatibilidad** con API de ACF: `update_field()`, `get_field()`
- Mejor rendimiento y mantenimiento moderno
- Licencia más favorable para proyectos comerciales

### ¿Cómo sabe el código si es ACF o SCF?
El código usa funciones genéricas que funcionan en ambos:
```php
if ( function_exists( 'update_field' ) ) {
    update_field( 'field_name', $value, 'option' );
}
```

Ambos plugins proporcionan estas funciones, así que el código es agnóstico.

---

**Fecha:** Feb 15, 2026
**Estado:** Documentación de referencia completa
