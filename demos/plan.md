# Plan de Implementación: Importador de Demos

Este documento detalla los pasos necesarios para construir el sistema de importación de demos para el theme.

---

## Objetivo

Crear un sistema modular y escalable que permita a los usuarios importar contenido de demostración (productos, páginas, imágenes y configuraciones del theme) con un solo clic desde el panel de administración de WordPress.

---

### Paso 1: Crear la Estructura de Archivos del Importador

El primer paso es establecer una estructura de directorios organizada dentro de la carpeta `/demos`.

1.  **Crear archivo principal del importador:**
    -   `demos/importer.php`: Este archivo contendrá la lógica principal para crear la página de administración del importador, registrar los scripts de AJAX y manejar las solicitudes de importación.

2.  **Crear archivos de configuración para cada demo:**
    -   `demos/demo-libreria.php`: Contendrá un array de PHP con toda la data para la demo de la librería (productos, páginas, opciones de ACF, etc.).
    -   `demos/demo-zapateria.php`: Ídem para la demo de la zapatería.
    -   `demos/demo-bazar.php`: Ídem para la demo del bazar chino.

3.  **Crear directorios para las imágenes de cada demo:**
    -   `demos/libreria/images/`
    -   `demos/zapateria/images/`
    -   `demos/bazar/images/`
    -   *(Nota: Estos directorios servirán como contenedores para las imágenes que se generen con los prompts. El importador las copiará a la librería de medios de WordPress durante el proceso).*

4.  **Integrar el importador en el theme:**
    -   Añadir `require_once get_template_directory() . '/demos/importer.php';` al inicio del archivo `functions.php` para que el sistema de importación se cargue con el theme.

---

### Paso 2: Desarrollar el Backend del Importador (`importer.php`)

Esta fase se centra en la lógica del lado del servidor.

1.  **Crear la Página de Administración:**
    -   Usar la función `add_menu_page` o `add_submenu_page` para crear una nueva página en el panel de WordPress llamada "Importar Demos".
    -   Diseñar la interfaz de esta página: un título, texto de bienvenida/advertencia y tres botones, uno por cada demo.

2.  **Implementar el Manejador de AJAX:**
    -   Registrar una acción AJAX de WordPress (ej. `wp_ajax_chow_import_demo`).
    -   Crear la función PHP que se ejecutará al recibir la llamada AJAX. Esta función:
        -   Verificará los permisos del usuario y un nonce de seguridad.
        -   Recibirá el identificador de la demo a importar (ej. 'libreria').
        -   Llamará a la función principal de importación `chow_do_import( $demo_id )`.
        -   Devolverá una respuesta JSON para indicar si el proceso fue exitoso o si hubo un error.

3.  **Desarrollar la Lógica de Importación de Contenido (`chow_do_import`):**
    -   La función cargará el archivo de configuración correspondiente (ej. `demos/demo-libreria.php`).
    -   **Importación de Imágenes:**
        -   Iterará sobre los archivos de imagen de la carpeta de la demo.
        -   Usará `wp_upload_bits` y `wp_insert_attachment` para copiar cada imagen a la librería de medios de WordPress.
    -   **Creación de Categorías:**
        -   Usará `wp_insert_term` para crear las categorías de productos de la demo si no existen.
    -   **Creación de Productos y Páginas:**
        -   Iterará sobre los datos de productos y páginas del archivo de configuración.
        -   Usará `wp_insert_post` para crear cada item.
        -   Usará `update_post_meta` o `update_field` (ACF) para añadir los metadatos (precio, SKU, etc.).
        -   Asignará la imagen destacada correspondiente (`set_post_thumbnail`).
        -   Asignará los posts a sus categorías/templates correctos.
    -   **Actualización de Opciones del Theme:**
        -   Iterará sobre las opciones del theme definidas en el archivo de configuración.
        -   Usará `update_field` de SCF (Smart Custom Fields - fork de ACF) para establecer todas las configuraciones globales: colores, datos del slider, configuración de los bloques de la home, CSS personalizado, etc.

---

### Paso 3: Definir la Estructura de Datos de las Demos (archivos `demo-*.php`)

Cada archivo de configuración de demo devolverá un array PHP estructurado.

```php
<?php
// demos/demo-libreria.php

return array(
    'id' => 'libreria',
    'name' => 'Librería "Páginas de Tinta"',
    'theme_options' => array(
        'color_principal' => '#2c3e50',
        'color_secundario' => '#fdf5e6',
        'custom_css_field' => 'body { font-family: "Lora", serif; }',
        // ... otras opciones de ACF
    ),
    'slider_home' => array(
        // ... datos del slider
    ),
    'home_blocks' => array(
        // ... configuración de los bloques de productos
    ),
    'categories' => array(
        array('name' => 'Novelas', 'slug' => 'novelas'),
        array('name' => 'Poesía', 'slug' => 'poesia'),
    ),
    'products' => array(
        array(
            'title' => 'El Eco de los Tiempos',
            'price' => 22.50,
            'image' => 'producto-01.jpg',
            'category' => 'novelas',
            'is_featured' => true,
        ),
        // ... 11 productos más
    ),
    'pages' => array(
        array(
            'title' => 'Sobre Nosotros',
            'template' => 'flexible-page.php',
            'content' => array(
                // ... datos para los campos flexibles de ACF
            )
        ),
        // ... más páginas
    ),
);
```

---

### Paso 4: Implementar el Frontend (`importer.php` + JS)

1.  **HTML de la Página de Administración:**
    -   Estructurar el HTML con los botones y un área de notificaciones para mostrar el progreso o el resultado.
2.  **JavaScript para AJAX:**
    -   Crear un archivo JS que se encolará solo en la página del importador.
    -   Añadir event listeners a los botones.
    -   Al hacer clic, deshabilitar los botones para prevenir múltiples envíos.
    -   Mostrar un mensaje de "Importando...".
    -   Realizar la llamada AJAX a la función PHP del backend.
    -   Manejar la respuesta (éxito o error) y mostrar el mensaje correspondiente al usuario.

---
Este plan cubre de principio a fin la creación de un sistema de importación de demos robusto y fácil de mantener.
