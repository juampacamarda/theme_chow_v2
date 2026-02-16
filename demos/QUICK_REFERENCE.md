# Importador de Demos - Guía de Referencia Rápida

## 🚀 Inicio Rápido

**Accede al importador:**
1. Panel de Administración de WordPress
2. Navega a: **Chow theme > Importar Demo**
3. Haz clic en "Importar Demo" en la tarjeta de Librería
4. Confirma en el modal
5. Observa la barra de progreso
6. ¡Éxito! Tu sitio ha sido actualizado

## 📁 Estructura de Archivos

```
/demos/
├── IMPLEMENTATION_SUMMARY.md      ← Documentación técnica completa
├── demo-libreria.php              ← Configuración de la demo Librería
├── importer.php                   ← Orquestador principal de importación
├── importer-ui.php                ← Renderizado de la interfaz de administración
├── importer.js                    ← Interacciones del lado del cliente
├── importer-styles.css            ← Estilos de administración
├── libreria/
│   └── images/                    ← 16 imágenes de productos y del theme
├── plan.md                        ← Plan de implementación (archivo)
├── prompts.md                     ← Prompts de generación de imágenes (archivo)
└── setup-wizard.md                ← Asistente de configuración futuro (archivo)
```

## 🎯 Qué Se Importa

Cuando importas la demo de Librería, obtienes:

| Elemento | Cantidad | Detalles |
|------|-------|---------|
| Productos | 8 | Libros con precios, stock, imágenes, categorías |
| Categorías | 3 | Novelas, Poesía, Autoayuda |
| Páginas | 4 | Sobre Nosotros, Preguntas Frecuentes, Contacto, Home |
| Formularios | 3 | Formularios de Contacto (creados automáticamente) |
| Elementos de Menú | 6 | Menú de navegación establecido como Principal |
| Imágenes | 16 | Logotipos, diapositivas, productos, fondos |
| Colores | 4 | Paleta de colores del theme |
| CSS Personalizado | 1 | Oculta la sección de clientes |

## 🔐 Características de Seguridad

- ✅ Verificación de Nonce en todas las solicitudes AJAX
- ✅ Comprobación de capacidades de administrador
- ✅ Sanitización de entradas
- ✅ Escape de salidas
- ✅ Seguimiento de contenido con meta `_demo_id`
- ✅ Confirmación del usuario antes de sobrescribir

## 💾 Qué Sucede con el Contenido

### Primera Importación (Sin Contenido Existente)
- Crea los 8 productos, 3 categorías, 4 páginas
- Crea 3 formularios de contacto
- Actualiza todas las opciones del theme
- Crea/actualiza el menú
- Marca todo el contenido con el ID de la demo para seguimiento

### Si el Contenido Existe

**Opción 1: "Importar"**
- Muestra error: "Ya existe contenido"
- Pide al usuario que confirme la sobrescritura

**Opción 2: "Sobrescribir"**
- Elimina el contenido de la demo anterior (marcado con el ID de la demo)
- Importa datos frescos de la demo
- Todo el contenido antiguo es reemplazado por el nuevo

## 🛠️ Detalles Técnicos

### Manejo de Imágenes
- Origen: `/demos/libreria/images/`
- Destino: WordPress `/uploads/`
- Nomenclatura: `libreria-[nombre-original-del-archivo]`
- Procesamiento: Generación automática de miniaturas

### Actualizaciones de la Base de Datos
- **Productos**: Entradas personalizadas con metadatos de WooCommerce
- **Formularios**: Entradas de formulario CF7
- **Páginas**: Páginas estándar de WordPress
- **Categorías**: Taxonomía `product_cat` de WordPress
- **Menú**: Menú de navegación de WordPress con ubicaciones

### Integración con SCF
- Almacena la configuración del slider utilizando SCF (Smart Custom Fields)
- Almacena la configuración de los bloques de productos
- Almacena la referencia del formulario del boletín
- Almacena las opciones de color

## 🎨 Colores del Tema Aplicados

```
Color Principal:     #2c3e50  (Azul Oscuro)
Color Secundario:   #fdf5e6  (Beige)
Color de Texto:        #5f5f5f  (Gris)
Color de Fondo:  #ffffff  (Blanco)
```

## 📊 Cronograma de Importación

| Paso | Tiempo | Proceso |
|------|-------|---------|
| Importación de Imágenes | 5-10s | Copiar 16 imágenes a uploads |
| Creación de Formularios | 2-3s | Crear 3 formularios CF7 |
| Creación de Categorías | 1-2s | Crear 3 categorías de productos |
| Creación de Productos | 15-20s | Crear 8 productos con metadatos |
| Creación de Páginas | 3-5s | Crear 4 páginas con contenido |
| Opciones del Tema | 2-3s | Actualizar colores, slider, formularios |
| Menú | 2-3s | Crear menú con 6 elementos |
| CSS | 1s | Aplicar estilos personalizados |
| **Total** | **30-60s** | Proceso completo de importación |

## ⚙️ Cómo Funciona

### Interfaz de Administración (`importer-ui.php`)
- Rejilla responsiva mostrando las demos disponibles
- Tarjeta de demo con imagen de portada, estadísticas, estado
- El botón de importar activa el modal de confirmación
- El modal muestra opciones de advertencia y confirmación

### Manejador AJAX (`importer.php`)
1. Verifica la seguridad (nonce + capacidades)
2. Comprueba si existe contenido
3. Importa imágenes a la biblioteca
4. Crea formularios
5. Crea categorías y productos
6. Crea páginas
7. Actualiza las opciones del tema
8. Crea/actualiza el menú
9. Aplica el CSS personalizado
10. Devuelve éxito/error al cliente

### Lado del Cliente (`importer.js`)
- Maneja la apertura/cierre del modal
- Muestra el spinner durante la importación
- Envía solicitud AJAX con el ID de la demo
- Muestra mensaje de éxito/error
- Recarga la página automáticamente después del éxito

### Estilo (`importer-styles.css`)
- Rejilla responsiva para tarjetas de demo
- Animaciones y transiciones suaves
- Estilo del modal y su contenido
- Animación de la barra de progreso
- Diseño adaptado a móviles

## 🐛 Solución de Problemas

### "Demo no encontrada"
- Verifica el ID de la demo en la URL
- Confirma que el archivo de configuración de la demo existe

### "Carpeta de imágenes del demo no encontrada"
- Asegúrate de que `/demos/libreria/images/` existe
- Comprueba que los archivos de imagen estén presentes

### "Contact Form 7 no está activado"
- Instala y activa el plugin Contact Form 7
- Intenta importar de nuevo

### ¿Menú no se muestra?
- Revisa la configuración de las ubicaciones del tema
- Asegúrate de que la ubicación "Menú Principal" existe
- Puede que necesites asignarlo manualmente en Apariencia > Menús

### ¿Estilos no aplicados?
- Revisa el CSS personalizado en Admin > CSS Adicional
- Limpia la caché de WordPress si usas un plugin de caché
- Verifica que no haya estilos en conflicto que sobrescriban el CSS de la demo

## 📝 Referencia de Nombres de Imágenes

**Imágenes de Productos** (8):
- libreria-producto01.png a libreria-producto08.png

**Imágenes del Slider** (3):
- libreria-slide01.png, libreria-slide02.png, libreria-slide03.png

**Logotipo** (2):
- libreria-logo-blanco.png, libreria-logo-color.png

**Imágenes de Fondo** (2):
- fondo-news.png (Sección de Newsletter)
- fondo-redes.png (Sección Social)

**Otros** (1):
- libreria-bannerflexible.png (Banner de página flexible)

**Total**: 16 imágenes

## 🔄 Flujo de Trabajo para Usuarios

```
Panel de Administración
    ↓
Navegar a: Chow theme → Importar Demo
    ↓
Ver tarjetas de demo
    ↓
Hacer clic en "Importar Demo"
    ↓
Leer advertencia en el modal
    ↓
Hacer clic en "Confirmar Importación"
    ↓
Observar el spinner (30-60 segundos)
    ↓
Ver mensaje de éxito "¡Demo Importada!"
    ↓
La página se recarga automáticamente
    ↓
Navegar al frontend
    ↓
¡La demo de Librería está activa!
```

## 📋 Lista de Verificación: Después de la Importación

- [ ] Visitar la página de inicio del frontend (el contenido de la demo debería mostrarse)
- [ ] Revisar /tienda para el catálogo de productos
- [ ] Verificar la página "Sobre Nosotros"
- [ ] Verificar la página "Preguntas Frecuentes" (el acordeón de preguntas frecuentes funciona)
- [ ] Verificar la página "Contacto" con el formulario de contacto
- [ ] Revisar el formulario de suscripción al boletín en la página de inicio
- [ ] Verificar que todas las imágenes de los productos se cargaron
- [ ] Revisar los colores aplicados (principal, secundario, texto)
- [ ] Probar la navegación del slider
- [ ] Verificar que los elementos del menú se muestran correctamente

## 🚨 Notas Importantes

1. **Primero la Copia de Seguridad**: Siempre haz una copia de seguridad antes de importar, especialmente si tienes contenido existente
2. **Probar en Staging**: Prueba el proceso de importación primero en un sitio de staging
3. **Tamaño de las Imágenes**: Algunas imágenes son grandes (6-11MB); la importación puede tardar
4. **WooCommerce Requerido**: El plugin debe estar activo para los productos
5. **SCF Requerido**: Smart Custom Fields (fork de ACF) debe estar activo para los campos personalizados
6. **Reemplazo del Menú**: La importación reemplazará el menú principal (se puede deshacer)

## 🔧 Mantenimiento

### Para Eliminar Contenido de la Demo
- Eliminar productos marcados con `_demo_id = 'libreria'`
- Eliminar páginas marcadas con `_demo_id = 'libreria'`
- Eliminar categorías marcadas con `_demo_id = 'libreria'`
- Eliminar menú marcado con `_demo_id = 'libreria'`
- Eliminar CSS personalizado de la sección CSS Adicional
- Restaurar el menú original

### Para Volver a Importar
- Simplemente haz clic en "Importar Demo" de nuevo
- Elige "Sobrescribir y Continuar" si se te solicita
- Todo el contenido se actualizará a la última versión de la demo

## 🎓 Para Desarrolladores

### Extender con Nuevas Demos

1. Crear `/demos/demo-[nombre].php` con la configuración de la demo
2. Añadir al array de demos en `importer.php`
3. Crear la carpeta `/demos/[nombre]/images/` con las imágenes
4. Colocar imágenes que coincidan con los nombres de configuración
5. Actualizar la interfaz de usuario para mostrar la nueva tarjeta de demo
6. Desplegar y probar

### Añadir Lógica de Importación Personalizada

1. Extender la función `chow_do_import()`
2. Añadir pasos de importación personalizados
3. Almacenar referencias con meta `_demo_id`
4. Devolver `WP_Error` en caso de fallo

### Engancharse al Proceso de Importación

```php
// Antes de la importación
do_action('chow_before_import_demo', $demo_id);

// Después de una importación exitosa
do_action('chow_after_import_demo', $demo_id);
```

---

**Estado**: ✅ Listo para Producción
**Versión**: 1.0
**Última Actualización**: Feb 15, 2026
