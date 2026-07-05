# 📚 Chow Theme v2.0 — Documentación Unificada

> **Stack:** WordPress + WooCommerce 9.x + Bootstrap 5.3 + SCF (Secure Custom Fields)
> **Versión del theme:** 2.0 — Quick Commerce
> **Última actualización:** Junio 2026

---

## 🗂️ Índice de Documentos

| # | Documento | Contenido |
|---|---|---|
| 1 | [Arquitectura del Theme](01-arquitectura.md) | Estructura de archivos, stack tecnológico, filosofía, dependencias, templates |
| 2 | [Sistema de Demos](02-demos.md) | Cómo crear, importar y gestionar demos prefabricadas. Referencia de campos SCF. Checklist pre-importación. |
| 3 | [Tarjetas de Producto (WooCommerce)](03-woocommerce-cards.md) | Sistema modular de cards: Classic, Hover Visual, Flip. Configuración global y por bloque. Extensibilidad. |
| 4 | [Configuración SCF](04-acf-config.md) | Grupos de campos, páginas de opciones, auto-sync JSON, variables CSS desde SCF |
| 5 | [Protocolos](05-protocolos.md) | Protocolos paso a paso para: crear una demo, agregar un estilo de card, crear página flexible, extender el theme |
| 6 | [Flujo OpenDesign ↔ OpenCode](06-flujo-trabajo-diseno.md) | Pipeline completo diseño→implementación con IA. Proceso DESIGN.md → prompt. Handoff protocol entre herramientas. |

---

## 🚀 Inicio Rápido

### Instalación
1. Subir el theme a `/wp-content/themes/chow-theme/`
2. Activar en Apariencia > Temas
3. Instalar plugins requeridos: **WooCommerce 9.0+**, **SCF**, **Contact Form 7**
4. Los grupos de campos SCF se importan automáticamente desde `acf-json/`
5. Configurar en **Apariencia > Chow theme**

### Plugins Requeridos
- WooCommerce 9.0+
- SCF (Secure Custom Fields)
- Contact Form 7

### Dependencias Frontend
- Bootstrap 5.3.0 (CDN)
- jQuery 3.7.1 (CDN)
- Owl Carousel 2.3.4
- AOS (Animate On Scroll)
- Font Awesome (kit)

---

## 📂 Estructura General

```
chow-theme/
├── acf-json/              # Grupos de campos SCF (auto-sync)
├── assets/                # CSS, JS, imágenes del theme
│   ├── css/
│   ├── js/
│   └── img/
├── demos/                 # Sistema de demos prefabricadas
│   ├── libreria/          # Demo: Páginas de Tinta (librería)
│   ├── pasteleria/        # Demo: Harina & Miel (pastelería)
│   ├── importer.php       # Lógica de importación de demos
│   ├── loader.php         # Carga condicional de hooks por demo
│   └── DEMO.md            # Guía detallada de creación de demos
├── docs/                  # 📁 Documentación unificada (ESTÁS AQUÍ)
├── home/                  # Partials del homepage
├── inc/                   # Configuraciones modulares
│   └── acf-config.php     # Páginas de opciones SCF
├── login/                 # Estilos del login de WP
├── woocommerce/           # Templates WooCommerce sobrescritos
│   └── loop/              # Tarjetas de producto
├── functions.php          # Hooks y funciones principales
├── flexible-page.php      # Template de página flexible
├── style.css              # Hoja de estilos principal
└── README.md              # Readme original del theme
```

---

## 🔗 Referencias Rápidas

- **Panel de administración:** Apariencia > Chow theme
- **Importar demo:** Chow Theme > Importar Demo
- **Campos SCF:** Grupos de Campos (sync automático desde `acf-json/`)
- **Estilos CSS:** `assets/css/chow-base-style.css` + `assets/css/chow-wc.css`
- **Variables CSS:** `--chow_ppal`, `--chow_secundario`, `--chow_txt`, `--chow_blanco`

---

## 📝 Changelog

Ver `README.md` en la raíz del theme para el historial completo de versiones.

### v2.0 (Febrero 2026)
- Sistema modular de tarjetas de producto (3 estilos)
- Gestión de colores centralizada vía SCF
- Templates WooCommerce 9.x actualizados
- Template de página flexible con 6 componentes
- Sistema de productos dinámicos (últimos, destacados, ofertas, categoría)
