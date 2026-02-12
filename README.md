# Chow theme v2.0 - Quick Commerce

Tema de WordPress personalizado para e-commerce, construido con Bootstrap 5 y WooCommerce.

## Filosofía del Tema

Este tema no compite con constructores de páginas como Elementor o Avada. Su enfoque es radicalmente distinto: se centra en la **velocidad de implementación** y en la **facilidad de gestión para el cliente final**.

El objetivo es que un emprendedor o una pyme pueda tener su tienda online funcionando en cuestión de horas, no semanas, y que pueda gestionar su contenido y productos desde un panel de control simplificado, sin necesidad de conocimientos técnicos.

## Características

- ✅ Compatible con **WooCommerce 9.x**
- ✅ Diseño responsivo con **Bootstrap 5.3.0**
- ✅ Campos personalizados con **SCF (fork de ACF mantenido por WordPress)**
- ✅ Templates WooCommerce actualizados (v9.4.0)
- ✅ Sistema de productos dinámicos (categorías, destacados, ofertas, últimos)
- ✅ Template de página flexible con componentes reutilizables
- ✅ Navegación con WP Bootstrap Navwalker
- ✅ Soporte para sliders con Owl Carousel
- ✅ Accesibilidad mejorada (ARIA labels, screen readers)

## Instalación

1. Sube el tema a `/wp-content/themes/chow-theme/`
2. Activa el tema en el panel de WordPress
3. Instala y activa los plugins requeridos:
  - **WooCommerce** (9.0+)
  - **SCF (fork de ACF)**
  - **Contact Form 7** (para formularios)
4. Los grupos de campos se importan automáticamente desde `acf-json/`
5. Configura las opciones del tema en **Apariencia > Chow theme**

## Configuración SCF

El tema utiliza un sistema centralizado de opciones (SCF) con sub-páginas:

### **Apariencia > Chow theme**

#### 1. **Empresa**
- Logo header desktop
- Logo header mobile
- Logo footer
- Dirección, teléfonos, email
- Redes sociales (Facebook, Instagram, WhatsApp, TikTok)
- **Colores del sitio** (gestión centralizada):
  - Color Principal (botones, enlaces, acentos)
  - Color Secundario
  - Color del Texto
  - Color de Fondo

#### 2. **Slider Home**
- 5 slides configurables
- Cada slide: imagen, título, bajada, texto y enlace

#### 3. **Contenido Home**
- Newsletter (fondo + formulario)
- Redes sociales (fondo)
- Carrusel de productos destacados
- Carrusel de logos de clientes
- Bloques de productos dinámicos (repeater)

#### 4. **Formularios**
- Formulario de consulta productos (shortcode)

#### 5. **Avanzado**
- Google Analytics
- Facebook Pixel
- Scripts personalizados

## Templates

### **Templates de Página**

- **flexible-page.php**: Template flexible con componentes activables
  - Encabezado con imagen de portada
  - Contenido de texto enriquecido
  - Collapses/FAQ (acordeón)
  - Logos de clientes
  - Formularios (Contact Form 7)
  - Banners con enlaces
  - Control de orden dinámico

### **Templates WooCommerce** (Actualizados a v9.x)

- `woocommerce/checkout/form-checkout.php` - v9.4.0
- `woocommerce/loop/add-to-cart.php` - v9.2.0 (con accesibilidad ARIA)
- `woocommerce/content-single-product.php` - v3.6.4
- `woocommerce/single-product.php` - v1.6.5

### **Partials Home**

- `home/slide.php` - Slider principal
- `home/news.php` - Newsletter con fondo
- `home/redes.php` - Redes sociales con fondo
- `home/productos.php` - Carrusel de productos destacados
- `home/clientes.php` - Carrusel de logos
- `home/productos-1.php` - Bloques dinámicos de productos

## Sistema de Productos Dinámicos

El template `home/productos-1.php` permite crear múltiples bloques de productos:

**Tipos de filtrado:**
- **Últimos**: Productos más recientes
- **Categoría**: Filtrar por categoría específica
- **Destacados**: Productos marcados como destacados (taxonomía `product_visibility`)
- **Ofertas**: Productos con precio de oferta activo

**Layouts de contenedor:**
- **Grid**: Columnas configurables (col-lg-3, col-lg-4, etc.)
- **Carousel**: Carrusel Owl Carousel

## Sistema Modular de Tarjetas de Producto

Las tarjetas de producto ahora son **componentes reutilizables** que funcionan en:
- Bloques dinámicos del home (`home/productos-1.php`)
- Página de tienda (`woocommerce/archive-product.php`)
- Páginas de categoría
- Búsqueda de productos
- Productos relacionados

**Estilos de tarjeta disponibles:**

### 1. **Tarjeta Clásica** (`card-classic.php`)
Estilo estándar con estructura vertical:
- Imagen del producto
- Título
- Precio
- Botón "Agregar al carrito"

### 2. **Tarjeta Hover Visual** (`card-hover_visual.php`)
Estilo moderno con imagen a tamaño completo:
- Imagen ocupa todo el espacio de la tarjeta
- Al hacer hover: aparece overlay oscuro
- Sobre el overlay: se revelan título, precio y botón
- Efecto zoom suave en la imagen
- Transiciones animate smoothly

**Configuración:**

1. **Predeterminada (Global)**: Ir a **Apariencia > Chow theme > Empresa** y seleccionar "Estilo de Tarjeta Predeterminado"
   - Se usa en toda la tienda (shop, categorías, búsqueda, productos relacionados)

2. **Por Bloque (Home)**: En **Apariencia > Chow theme > Contenido Home**, en cada bloque de productos:
   - Campo "Estilo de Tarjeta": permite sobrescribir el predeterminado para ese bloque específico
   - Opción "Usar predeterminado": hereda la configuración global

**Arquitectura PHP:**

```php
// Funciones helper disponibles:

// 1. Obtener el card_style correcto (local o global)
$card_style = chow_get_card_style($card_style_override);

// 2. Cargar el template de tarjeta
chow_load_product_card($product, $card_style);
```

**Para agregar nuevos estilos de tarjeta:**

1. Crear archivo: `woocommerce/loop/card-NOMBRE.php`
2. Implementar la lógica HTML/PHP en el template
3. Agregar opción en ACF (campos `card_style_default` en "Empresa" y `card_style` en "Bloques de Productos")
4. Agregar estilos CSS en `assets/css/chow-wc.css`

## Desarrollo

### **Requisitos**
- WordPress 5.0+
- PHP 7.4+
- WooCommerce 9.0+
- SCF (fork de ACF)

### **Dependencias Frontend**
- Bootstrap 5.3.0
- jQuery 3.7.1
- Owl Carousel 2.3.4
- AOS (Animate On Scroll)

### **Estructura de Archivos**
```
chow-theme/
├── acf-json/                 # SCF/ACF field groups (auto-sync)
├── assets/                   # CSS, JS, imágenes
├── home/                     # Partials específicos del home
├── inc/                      # Configuración modular
│   └── acf-config.php       # Configuración SCF
├── woocommerce/             # Templates WooCommerce
├── flexible-page.php        # Template página flexible
├── functions.php            # Funcionalidades del tema
└── README.md
```

## Changelog

### v2.0 (Febrero 2026)

- **🎨 Gestión de Colores**
- Migración de colores de WordPress Customizer a SCF
- 4 colores configurables desde "Apariencia > Chow theme > Empresa"
- Variables CSS centralizadas: `--chow_ppal`, `--chow_secundario`, `--chow_txt`, `--chow_blanco`
- Interfaz unificada para toda la configuración del tema

**🛒 WooCommerce 9.x**
- Actualización de templates obsoletos a versiones actuales
- `form-checkout.php` → v9.4.0 (estructura col2-set)
- `add-to-cart.php` → v9.2.0 (atributos ARIA, data-product_sku)
- `content-single-product.php` → v3.6.4
- `single-product.php` → v1.6.5
- Mejoras de accesibilidad con ARIA labels y descripciones para lectores de pantalla

**📄 Template Flexible**
- Nuevo `flexible-page.php` con 6 componentes modulares
- Sistema de activación por checkbox para cada sección
- Orden dinámico personalizable
- Componentes: encabezado, contenido, collapses, clientes, formulario, banner

**🏷️ Sistema de Productos**
- Corrección query productos destacados: migrado de meta `_featured` a taxonomía `product_visibility`
- Corrección query productos en oferta: uso de `wc_get_product_ids_on_sale()` nativo
- Compatible con WooCommerce 3.0+
- Filtrado por: categoría, destacados, ofertas, últimos

**🔧 Estructura SCF**
- Reorganización completa en 6 grupos centralizados
- Todos los campos como hijos directos de 'option'
- Sub-páginas en "Apariencia > Chow theme"
- Auto-sync mediante acf-json/ (compatible con SCF)
- Eliminación de templates obsoletos (destaque.php)

**✨ Mejoras Generales**
- Procesamiento correcto de shortcodes en campos SCF
- Código modularizado (acf-config.php)
- Documentación completa actualizada

---

## Soporte

Para dudas o problemas, consulta la documentación de:
- [WooCommerce](https://woocommerce.com/documentation/)
- SCF (fork de ACF)
- [Bootstrap 5](https://getbootstrap.com/docs/5.3/)

## Licencia

Este tema es propiedad del cliente y de uso exclusivo para el proyecto.