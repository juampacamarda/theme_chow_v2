# Documentación de Proyecto — Migración Traveler → Chow Theme + WooCommerce

> **Proyecto:** proyecto-ecommerce (Local WP)
> **Período:** Mayo–Junio 2026
> **Autor:** Juampa Camarda / Chow Dev

---

## 1. Objetivo del Proyecto

Migrar un sitio de turismo construido sobre el theme de pago **Traveler (st_tour)** hacia una solución propia, sostenible y libre de licencias, compuesta por:

| Componente | Decisión |
|---|---|
| Theme | **chow-theme** (custom, Bootstrap 5 + WooCommerce) |
| Custom Fields | **SCF** — Secure Custom Fields (fork oficial de ACF) |
| Productos | **WooCommerce Simple Products** (en lugar del CPT `st_tour`) |
| Booking | MVP custom vía metadatos + hooks WooCommerce |

El sitio original exportaba dos tipos de contenido:
- **`st_tour`** → Paquetes turísticos (días completos, itinerarios)
- **`st_activity`** → Excursiones y actividades (duraciones cortas)

---

## 2. Arquitectura Resultante

### 2.1 Estructura de Categorías WooCommerce

```
Viajes (raíz)
├── Paquetes
│   ├── Buenos Aires        ← Nivel 2: Provincias (st_tour)
│   ├── Patagonia
│   └── ...
└── Excursiones
    ├── Bariloche           ← Nivel 3: Ciudades (st_activity)
    ├── El Calafate
    └── ...
```

El mapeo se basa en el CPT `location` del XML original (17 posts), usando el meta `multi_location` con formato `_ID_`.

### 2.2 Campos SCF por tipo de producto

**Grupo `group_travel_common` — Campos comunes de viajes:**

| Field Key | Nombre | Tipo |
|---|---|---|
| `field_travel_destination` | Destino principal | text |
| `field_travel_season` | Temporada | text (default: "Todo el año") |
| `field_travel_min_age` | Edad mínima | number |
| `field_travel_max_participants` | Máx. participantes | number |
| `field_travel_featured` | Producto destacado | true_false |
| `field_travel_availability` | Disponibilidad | text |
| `field_travel_tour_type` | Tipo de tour | select |
| `field_travel_languages` | Idiomas | checkbox |

**Grupo `group_travel_excursion` — Campos de excursión:**

| Field Key | Nombre | Tipo |
|---|---|---|
| `field_excursion_duration` | Duración | text |
| `field_excursion_difficulty` | Dificultad | select |
| `field_excursion_meeting_point` | Punto de encuentro | text |
| `field_excursion_includes` | Incluye | textarea |
| `field_excursion_not_includes` | No incluye | textarea |
| `field_excursion_recommendations` | Recomendaciones | textarea |
| `field_excursion_gallery` | Galería | gallery |
| `field_69a8e152acfd4` | Precio | number |

**Repeater de Itinerario:**

| Field Key | Nombre |
|---|---|
| `field_package_itinerary` | Itinerario (repeater raíz) |
| `field_itinerary_row_day` | Día |
| `field_itinerary_row_title` | Título del día |
| `field_itinerary_row_description` | Descripción |
| `field_itinerary_row_meals` | Comidas |
| `field_itinerary_row_accommodation` | Alojamiento |

---

## 3. Sesiones de Trabajo

### Sesión 1 — 17 Mayo 2026 (18:37 → ~02:00 del 18)

**Tema:** Desarrollo del plugin `chow-migrator` (ETL XML → WooCommerce)

#### Tareas completadas:

- **Arquitectura del plugin** con clases modulares:
  - `XmlParser` — parseo del XML de exportación WordPress
  - `ChunkProcessor` — procesamiento por lotes para evitar timeouts
  - `ScfMapper` — mapeo de meta del theme Traveler a Field Keys de SCF
  - `ImageSideloader` — descarga e importación de imágenes al Media Library

- **Parseo del XML** con CPTs `st_tour` y `st_activity`

- **Migración de 69 productos** con:
  - Sideloading de imágenes destacadas
  - Sideloading de galerías
  - Fix de deduplicación para no re-importar imágenes ya existentes

- **Diagnóstico y resolución de errores HTTP 500** durante la migración (entorno sin Docker, sin acceso a logs del servidor)

- **Creación de subcategorías por destino/location** previa a la migración:
  - `st_tour` → subcategorías nivel 2 (provincias)
  - `st_activity` → subcategorías nivel 3 (ciudades)

- **Mapeo de campos Traveler → SCF:**
  - `location` del original → `field_travel_destination` / `field_excursion_meeting_point`
  - Temporada → default "Todo el año" si no hay equivalente
  - `product_featured` (SCF) → activa también la visibilidad destacada nativa de WooCommerce
  - `detalle` del post original → `short_description` del producto WooCommerce

- **Parser de itinerarios** (último ítem de la sesión):
  - Detecta `tab_itinerary_N_*` (ACF repeater), PHP serialize, JSON
  - Caso especial del cliente: todos los días en un solo tab → `split_days_from_html()` con regex `/<strong>Día N: Título<\/strong>/`
  - `ScfMapper::map_itinerary()` escribe el repeater usando `update_field('field_package_itinerary', $rows, $post_id)`
  - Log: `"[ChowMigrator] Itinerario mapeado: producto X — N día(s)"`

---

### Sesión 2 — 17 Mayo 2026 (20:34 → 20:55)

**Tema:** Configuración de SCF Local JSON / Synchronized JSON en el child theme

#### Problema identificado:

Los archivos JSON de grupos de campos (`campos_comunes_de_viajes.json`, `campos_excursion.json`) estaban envueltos en un array `[{...}]` en lugar de ser objetos planos `{...}`. La función `scan_files()` interna de SCF hace `$json['key']` y descartaba silenciosamente los archivos sin lanzar ningún error.

#### Solución aplicada:

1. Ambos JSON corregidos a objeto plano en el nivel raíz
2. Campo `modified` agregado (timestamp `1779061674`) para activar el trigger de sincronización
3. Filtro `acf/settings/load_json` corregido para evitar rutas duplicadas con `in_array()`
4. Filtros redundantes limpiados (SCF ya registra `get_stylesheet_directory() . '/acf-json'` automáticamente)

#### Resultado:

Los grupos `group_travel_common` y `group_travel_excursion` quedan listos para aparecer con botón **"Sync available"** en SCF → Grupos de Campos.

#### Decisión abierta al cierre:

El usuario planteó si conviene usar un XML completo del sitio (no solo tours) para la migración. **Pendiente de decisión.**

---

## 4. Estado Actual del Workspace (Junio 2026)

### Theme principal: `chow-theme`
- **Version:** 2.0 — Quick Commerce
- **Stack:** Bootstrap 5.3 + WooCommerce 9.x + SCF
- **ACF JSON:** almacenado en `chow-theme/acf-json/` con 6 grupos activos:
  - `group_avanzado.json`
  - `group_contenido_home.json`
  - `group_empresa.json`
  - `group_flexible_page.json`
  - `group_formularios.json`
  - `group_slider_home.json`
- **Templates WooCommerce customizados:**
  - `woocommerce/loop/card-flip.php` — Tarjeta con efecto flip (frente: imagen+título / dorso: descripción+precio+acciones)
  - `woocommerce/loop/card-classic.php`
  - `woocommerce/loop/card-hover_visual.php`
  - `woocommerce/single-product.php`
  - `woocommerce/content-single-product.php`
  - `woocommerce/checkout/`

### Child theme: `chow-theme-child`
- Estructura mínima (style.css + functions.php)
- `functions.php` encola correctamente los estilos del padre
- **Nota:** Los filtros Local JSON de SCF deben vivir aquí según la Sesión 2. Al cierre de la sesión 2 estaban en `inc/acf-config.php` del theme padre. **Verificar ubicación definitiva.**

### Plugin `chow-migrator`
- **Estado:** No encontrado en `/wp-content/plugins/` en el workspace actual
- Puede haber sido desactivado/eliminado tras la migración inicial de los 69 productos, o puede estar en una rama/carpeta fuera del workspace indexado

### Plugins activos detectados:
- `woocommerce/` — WooCommerce
- `secure-custom-fields/` — SCF (fork de ACF)
- `advanced-custom-fields-pro/` — ACF Pro (coexistencia a confirmar)
- `contact-form-7/`
- `click-to-chat-for-whatsapp/`
- `acf-theme-code/`

---

## 5. Tareas Pendientes

### Alta prioridad
- [ ] Verificar si `chow-migrator` existe y si los 69 productos están correctamente migrados en la DB
- [ ] Confirmar que `package_itinerary` está poblado en los productos migrados (admin WP)
- [ ] Confirmar shortcode `[ct_itinerary]` funcional en página de producto
- [ ] Sincronizar grupos de campos SCF desde admin: **SCF → Grupos de campos → "Sync available"**
- [ ] Resolver decisión: ¿usar XML completo del sitio o solo tours/actividades para la migración?

### Media prioridad
- [ ] Mover filtros Local JSON al child theme o confirmar que el tema padre los maneja correctamente
- [ ] Verificar coexistencia de `secure-custom-fields` y `advanced-custom-fields-pro` (posible conflicto)
- [ ] Crear/confirmar grupos de campos SCF de viajes en `chow-theme/acf-json/` (al momento solo existen los 6 grupos del theme base)

### Baja prioridad / Backlog
- [ ] MVP de Booking: recolección de datos de pasajeros post-compra via `woocommerce_thankyou`
- [ ] Templates de producto individual adaptados para paquetes vs excursiones
- [ ] Sistema de búsqueda/filtrado por destino, tipo de tour, duración

---

## 6. Convenciones del Proyecto

- **Crear productos:** siempre via `WC_Product->save()`, nunca `$wpdb->insert`
- **Actualizar campos SCF:** siempre con Field Key (`field_XXXXXX`), nunca con el nombre del campo
- **Manejo de errores:** `WP_Error` + `error_log()`
- **PHP:** 8.1+ con tipado estricto
- **WP-CLI:** priorizar sobre scripts PHP directos para operaciones de mantenimiento
