# 🎯 Sistema de Demos - Chow Theme v2.0

## 📦 Estructura de un Demo
Cada demo debe seguir esta estructura en `demos/demo-[nombre].php`:

```php
function chow_get_demo_[nombre]() {
    return array(
        'id' => '[nombre]',
        'name' => 'Nombre de la Demo',
        'description' => 'Descripción corta',
        'image' => 'cover.png',
        'version' => '1.0',
        'card_style' => 'hover_visual',
        'company' => array(...),
        'categories' => array(...),
        'products' => array(...),
        'pages' => array(...),
        'forms' => array(...),
        'home' => array(...),
        'menu' => array(...),
        'custom_css' => '/* CSS */'
    );
}
```

## 🔄 Proceso de Importación
1. **Seleccionar Demo:** En el admin, ir a **Chow Theme > Importar Demo**
2. **Elegir Demo:** Seleccionar una de las disponibles (Librería, Pastelería, etc.)
3. **Confirmar:** Mostrar aviso de eliminación de contenido existente
4. **Importar:** Ejecutar `chow_do_import('nombre')`

## 📁 Carpeta de Demos
```
demos/
├── demo-libreria.php
├── demo-pasteleria.php
├── demo-[nombre].php
├── images/
│   ├── cover.png
│   ├── logo.png
│   └── [otros assets]
├── importer.php          # Lógica de importación
└── loader.php            # Carga condicional de hooks
```

## ✅ Checklist Pre-Importación
- [ ] Archivo `demo-[nombre].php` existe
- [ ] Función `chow_get_demo_[nombre]()` definida
- [ ] Todos los campos requeridos (id, name, description, image)
- [ ] IDs únicos
- [ ] Archivos de imagen en `demos/[nombre]/images/`
- [ ] No hay sección `theme_options` duplicada

## 🛠️ Protocolos para Crear Demos
1. **Crear archivo:** `demos/demo-[nombre].php`
2. **Definir estructura básica** con `chow_get_demo_...()`
3. **Agregar configuración de empresa** (colores, logos)
4. **Definir categorías y productos**
5. **Agregar páginas flexibles**
6. **Configurar home blocks**
7. **Agregar CSS personalizado**
8. **Probar con "Restaurar Plantilla"**

## 📌 Buenas Prácticas
- Usar nombres descriptivos para demos
- Mantener archivos de imagen organizados
- Documentar cambios en `DEMO.md`
- Evitar duplicar campos SCF

## 🤖 Rediseño con IA

El Chow Theme tiene un sistema completo para rediseñar demos con herramientas de IA generativa (v0, Bolt, Lovable, Open Design) y luego implementar el resultado.

### Documentos de referencia

| Documento | Contenido |
|---|---|
| [`docs/03-demo-redesign-prompt.md`](03-demo-redesign-prompt.md) | Prompt base para la herramienta de diseño + instrucciones para el agente implementador |

### Flujo de trabajo

```
HERRAMIENTA DE IA (v0/Bolt/Open Design)
    │  Copiar prompt con placeholders reemplazados
    │  Incluye: no Tailwind, componentes existentes, CSS mínimo
    ▼
DISEÑO EXPORTADO (HTML + CSS + JS)
    │
    ▼
AGENTE IMPLEMENTADOR
    │  Sigue las instrucciones en docs/03-demo-redesign-prompt.md
    │  → "Instrucciones para el agente implementador"
    ▼
ARCHIVOS DEL CHOW THEME
    ├── demos/demo-[nombre].php           # Datos + CSS
    ├── demos/demo-[nombre]-functions.php  # Hooks específicos (opcional)
    └── demos/[nombre]/images/             # Assets
```

### Lo que incluye `03-demo-redesign-prompt.md`

- **Instrucciones de código obligatorias** para la IA (sin Tailwind, clases `chow-*`, código mínimo).
- **Catálogo de componentes existentes** del theme con clases CSS, descripción y cuándo usar cada uno.
- **Template de prompt base** con placeholders reutilizable.
- **Ejemplo concreto** con la demo Pastelería "Harina & Miel" listo para copiar y pegar.
- **Pipeline de implementación** (6 pasos: analizar → mapear → CSS → template → demo → functions [opcional]).
- **Reglas de cuándo crear nuevo template part y cuándo NO**.
- **Tabla de mapeo** de secciones del diseño a componentes del Chow Theme.
- **Estructura completa** del array `home` con todos los campos requeridos.
