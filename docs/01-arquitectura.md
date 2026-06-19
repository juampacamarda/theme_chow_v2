# 🏗️ Arquitectura del Theme Chow-Theme v2.0

## 🧱 Stack Tecnológico
- **WordPress 5.0+**
- **WooCommerce 9.0+**
- **Bootstrap 5.3.0** (CDN)
- **SCF (Secure Custom Fields)** - Fork oficial de ACF
- **JavaScript:** jQuery 3.7.1, Owl Carousel 2.3.4, AOS
- **CSS:** Bootstrap 5, CSS personalizado (chow-base-style.css, chow-wc.css)

## 🎯 Filosofía del Theme
- Enfoque en **velocidad de implementación**
- Diseño para **PYMEs** (sin necesidad de técnicos)
- **Modularidad** en todos los componentes
- **Extensibilidad** mediante SCF y templates personalizables

## 📁 Estructura de Archivos
```
chow-theme/
├── acf-json/              # Grupos de campos SCF (auto-sync)
├── assets/                # CSS, JS, imágenes
│   ├── css/
│   ├── js/
│   └── img/
├── demos/                 # Demos prefabricadas
│   ├── libreria/
│   ├── pasteleria/
│   ├── importer.php       # Lógica de importación
│   └── loader.php         # Carga condicional de demos
├── home/                  # Partials del homepage
├── inc/                   # Configuraciones modulares
│   └── acf-config.php     # Páginas de opciones SCF
├── login/                 # Estilos del login de WP
├── woocommerce/           # Templates WooCommerce
│   └── loop/              # Tarjetas de producto
├── functions.php          # Hooks y funciones principales
├── flexible-page.php      # Template de página flexible
├── style.css              # Hoja de estilos principal
└── README.md              # Readme original
```

## 🔧 Dependencias Críticas
- WooCommerce 9.0+ (versión mínima)
- SCF configurado con auto-sync desde `acf-json/`
- Bootstrap 5.3.0 (sin alternativas locales)
- Owl Carousel 2.3.4 para carruseles

## 📄 Templates Clave
- `flexible-page.php`: Página flexible con 6 componentes modulares
- `home.php`: Homepage con secciones dinámicas
- `woocommerce/loop/card-*.php`: Tarjetas de producto modulares
- `woocommerce/single-product.php`: Página de producto individual

## 🧩 Arquitectura Modular
- **Tarjetas de producto:** Sistema de 3 estilos (Classic, Hover Visual, Flip)
- **Homepage:** Bloques dinámicos de productos, sliders, newsletter
- **SCF:** Gestión centralizada de opciones en `apariencia > chow theme`

## 📌 Notas Técnicas
- Las tarjetas usan clases CSS numeradas (`.chow-product-card-01`, `.chow-product-card-02`)
- Los colores se gestionan desde SCF (`color_principal`, `color_secundario`)
- Las demos se importan mediante `importer.php`
- El theme es compatible con child themes
