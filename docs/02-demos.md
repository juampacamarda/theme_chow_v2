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
