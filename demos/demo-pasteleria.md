# Demo: Harina & Miel — Pastelería Artesanal

Referencia para la plantilla demo-pasteleria.php y los assets necesarios.

## Resumen
- Nombre: Harina & Miel
- Rubro: Pastelería artesanal, cajas y encargos
- Objetivo: mostrar catálogo de productos (tortas, cupcakes, boxes) + galería inspiracional "Tentaciones" + formularios de encargo y newsletter

## Company / Opciones (ACF `option`)
- `color_principal`: #F2D4B7 (crema)
- `color_secundario`: #E89BB4 (rosa pastel)
- `color_texto`: #5f5f5f
- `color_fondo`: #ffffff
- `logo_header_desktop`: pasteleria-logo-color.png
- `logo_header_mobile`: pasteleria-logo-blanco.png
- `logo_footer`: pasteleria-logo-blanco.png
- `direccion`: Av. Dulce 123, Ciudad
- `telefonos`: +54 (11) 5555-7777
- `mail`: hola@harinaymiel.com
- `wsp_link`: 5491155557777

## Categorías (product_cat)
- Tortas y Tartas (`tortas-tartas`)
- Cupcakes & Muffins (`cupcakes`)
- Mesas Dulces & Boxes (`mesas-dulces`)

## Productos (ejemplos — 8)
Cada producto: `name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `stock`, `image`, `category`, `featured`, `on_sale`, `bestseller`.

1. Torta Red Velvet Clásica — featured
2. Box Desayuno Sorpresa — pack
3. Tarta de Limón y Merengue — bestseller
4. Cupcakes Decorados x12
5. Torta Cumple Personalizada
6. Torta Chocolate Intenso
7. Pack Macarons Gourmet
8. Cheesecake Frutos Rojos

## Páginas
- Inicio (template `index-plantilla`)
- Nuestra Historia
- Encargos Especiales
- Contacto (página con CF7)

## Estructura del Home sugerida
1. `home/slide` — Slider hero (2–3 images)
2. `home/productos-1` — Bloque 1: "Tortas destacadas" (layout carousel)
3. `home/news` — Newsletter
4. `home/productos-1` — Bloque 2: "Boxes & Regalos" (grid)
5. `home/productos.php` — Sección "Tentaciones" (galería de imágenes sin link)
6. `home/redes` — Sección redes sociales

## Campos ACF relevantes
- En `group_contenido_home.json`:
  - `titulo_carrusel_destacados` (text)
  - `descripcion_carrusel_destacados` (textarea)
  - `carrusel_productos_destacados` (repeater)

- Por producto (ACF o meta):
  - `_stock` (usar como cupo)
  - `is_service` (boolean) — opcional para marcar reservable

## Formularios (Contact Form 7)
- `formulario_producto` — Encargo de producto
  - Campos: `product_id` (hidden), `product_name` (hidden), `date` (text), `time` (text), `your-name`, `your-phone`, `notes`
  - Hook en PHP: `wpcf7_mail_sent` para crear CPT `chow_reservation` y decrementar `_stock` si corresponde.

- `formulario_news` — Newsletter (email)

## Galería "Tentaciones"
- Ubicación: `home/productos.php`
- Comportamiento: mostrar imágenes sin link (inspiracional)
- Campo: `carrusel_productos_destacados` usado sin `link` o nuevo `carrusel_tentaciones`

## Assets / Nombres de archivo sugeridos (demos/pasteleria/images/)
- pasteleria-hero-01.png
- pasteleria-hero-02.png
- pasteleria-producto-01.png .. pasteleria-producto-08.png
- pasteleria-tentacion-01.png .. pasteleria-tentacion-06.png
- pasteleria-box-01.png .. pasteleria-box-03.png
- pasteleria-detail-01.png .. pasteleria-detail-02.png
- pasteleria-logo-primary.png
- pasteleria-logo-sticker.png
- pasteleria-social-banner.png

## Prompts asociados
Ver `demos/prompts.md` — sección "Harina & Miel" con prompts sugeridos para cada imagen.

## Importer notes
- `demo-pasteleria.php` debe asignar metadatos `_demo_id = 'pasteleria'` a productos y páginas para permitir limpiar/importar.
- Al finalizar import, ejecutar `update_option('chow_demo_pasteleria_active', 1)` para cargar funciones específicas si las hubiera.

## Flujo de reserva mínimo (sin plugins)
1. En ficha producto, usuario selecciona fecha/hora → botón "Reservar" redirige a `/contacto-producto?p=ID&date=YYYY-MM-DD&time=HH:MM`.
2. Formulario CF7 (`formulario_producto`) recibe valores y al enviar crea `chow_reservation` (post type) y decrece `_stock`.
3. Mostrar link WhatsApp prefilled para que el cliente confirme por chat.

## Notas finales
- El demo se diseña para funcionar con WooCommerce, Contact Form 7 y ACF (o SCF). Evitar dependencias obligatorias de plugins de reservas en la versión base.
- Preparar versión comercial: addon de reservas (WooCommerce Bookings / plugin propio) para gestionar cupos y confirmar automáticamente.
