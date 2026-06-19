# 📚 Chow Theme v2.0 - Quick Commerce

> **Stack:** WordPress + WooCommerce 9.x + Bootstrap 5.3 + SCF (Secure Custom Fields)
> **Versión del theme:** 2.0 — Quick Commerce
> **Última actualización:** Junio 2026

---

## 📚 Documentación Unificada
Toda la documentación completa del tema se encuentra en la carpeta `/docs/`. Incluye guías detalladas sobre:

- **[Arquitectura del Theme](docs/01-arquitectura.md)**: Estructura del theme, stack tecnológico (WordPress, WooCommerce 9.x, SCF), filosofía y dependencias.
- **[Sistema de Demos](docs/02-demos.md)**: Cómo crear, importar y gestionar demos prefabricadas. Incluye checklist pre-importación, protocolos y ejemplos de código.
- **[Tarjetas de Producto (WooCommerce)](docs/03-woocommerce-cards.md)**: Sistema modular de 3 estilos (Classic, Hover Visual, Flip) con configuración global y por bloque en la homepage.
- **[Configuración SCF/ACF](docs/04-acf-config.md)**: Gestión de grupos de campos, variables CSS (`--chow_ppal`, `--chow_secundario`), auto-sync de JSON y personalización de opciones.
- **[Protocolos de Extensión](docs/05-protocolos.md)**: Pasos para crear nuevas demos, tarjetas de producto, páginas flexibles o extender el theme.

---

## 🚀 Instalación Rápida
1. **Sube el theme** a `/wp-content/themes/chow-theme/`.
2. **Activa el tema** en Apariencia > Temas.
3. **Instala plugins requeridos**:
   - **WooCommerce 9.0+**
   - **SCF (Secure Custom Fields)**
   - **Contact Form 7**
4. **Configura opciones** en Apariencia > Chow theme (los grupos SCF se importan automáticamente desde `acf-json/`).

---

## 🔧 Características Clave
- **Tarjetas de producto modulares**: 3 estilos personalizables (Classic, Hover Visual, Flip) con soporte para configuración global y por bloque.
- **Gestión de colores**: Variables CSS desde SCF (`--chow_ppal`, `--chow_secundario`, `--chow_txt`, `--chow_blanco`).
- **Extensibilidad**: Protocolos para agregar demos, tarjetas personalizadas o páginas flexibles.
- **Modularidad**: Componentes reutilizables en homepage, tienda y páginas individuales.

---

## 📂 Estructura del Theme
```bash
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

---

## 🔗 Referencias Rápidas
- **Panel de administración**: Apariencia > Chow theme
- **Importar demo**: Chow Theme > Importar Demo
- **Documentación completa**: [Índice de Documentos](docs/INDEX.md)
- **Variables CSS**: `--chow_ppal`, `--chow_secundario`, `--chow_txt`, `--chow_blanco`

---

## 📝 Changelog
Ver [docs/INDEX.md](docs/INDEX.md) para el historial completo de versiones.

### v2.0 (Febrero 2026)
- Sistema modular de tarjetas de producto (3 estilos)
- Gestión de colores centralizada vía SCF
- Templates WooCommerce 9.x actualizados
- Template de página flexible con 6 componentes
- Sistema de productos dinámicos (últimos, destacados, ofertas, categoría)
