# Contexto del proyecto — chow-theme

## ⚠️ Importante: detección del proyecto
Este proyecto vive en `chow-theme/`. Como el repo git abarca todo el sitio, la detección automática puede dar "Local".
**Siempre usar `chow-theme` como nombre de proyecto.**

```bash
PROJECT="chow-theme"
MEMORY_DIR="$HOME/.config/opencode/projects/chow-theme"
```

## Memoria global
Este proyecto tiene memoria en:
```
~/.config/opencode/projects/chow-theme/
```

Archivos:
- `project.json` — metadatos del proyecto
- `progress.md` — progreso, sesiones, deuda técnica
- `context.md` — convenciones, decisiones arquitectónicas

## INICIO OBLIGATORIO DE SESIÓN
Todo agente debe ejecutar al arrancar:
```bash
PROJECT="chow-theme"
MEMORY_DIR="$HOME/.config/opencode/projects/$PROJECT"
cat "$MEMORY_DIR/project.json"
cat "$MEMORY_DIR/progress.md"
cat "$MEMORY_DIR/context.md"
```

## Stack
- WordPress 5.0+ / WooCommerce 9.0+
- Bootstrap 5.3.0 (CDN)
- SCF (Secure Custom Fields) — fork de ACF
- jQuery 3.7.1, Owl Carousel 2.3.4, AOS, Font Awesome
- CSS: chow-base-style.css, chow-wc.css
- JS: theme-chow.js

## Archivos clave
| Archivo | Propósito |
|---------|-----------|
| `functions.php` | Hooks principales del theme |
| `header.php` | Header (nav, logos, carrito, buscador) |
| `footer.php` | Footer (datos empresa, menú, logos) |
| `flexible-page.php` | Template de página flexible |
| `home.php` | Homepage con bloques dinámicos |
| `inc/acf-config.php` | Configuración SCF (options pages) |
| `woocommerce/` | Templates WooCommerce completos |
| `woocommerce/loop/card-*.php` | 3 estilos de tarjetas de producto |
| `woocommerce/content-product.php` | Loop de productos personalizado |
| `demos/importer.php` | Importador de demos |
| `assets/css/chow-base-style.css` | Estilos base |
| `assets/css/chow-wc.css` | Estilos WooCommerce |
| `assets/js/theme-chow.js` | JS principal |
| `class-wp-bootstrap-navwalker.php` | Navwalker Bootstrap 5 |

## CIERRE OBLIGATORIO DE SESIÓN
Antes de terminar, actualizar progress.md:
```bash
PROJECT="chow-theme"
MEMORY_DIR="$HOME/.config/opencode/projects/$PROJECT"
DATE=$(date '+%Y-%m-%d %H:%M')
cat >> "$MEMORY_DIR/progress.md" << EOF

## Sesión $DATE
**Agente**: {nombre del agente}
**Tarea**: {descripción}
**Hecho**:
- {item}
**Pendiente**:
- {item}
**Decisiones tomadas**:
- {decisión}
EOF
```
