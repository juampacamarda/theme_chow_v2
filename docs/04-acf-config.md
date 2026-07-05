# 🎨 Configuración SCF - Chow Theme v2.0

## 🧩 Sistema SCF (Secure Custom Fields)
El tema utiliza SCF (Secure Custom Fields) para gestionar todas las opciones del sitio. Los campos se organizan en grupos y se sincronizan automáticamente desde la carpeta `acf-json/`.

## 📁 Estructura de Grupos de Campos
```
acf-json/
├── group_empresa.json      # Configuración de empresa (colores, logos, contacto)
├── group_contenido_home.json # Homepage: sliders, newsletter, carruseles
├── group_formularios.json   # Formularios personalizados
├── group_slider_home.json   # Configuración de sliders
├── group_flexible_page.json # Páginas flexibles
└── group_avanzado.json      # Opciones avanzadas
```

## 🔄 Auto-Sync de JSON
- Los archivos JSON en `acf-json/` se sincronizan automáticamente con el panel de SCF
- Cualquier cambio en los JSON se refleja en tiempo real en el admin
- Para forzar sincronización: ir a **SCF > Grupos de campos > Sync available**

## 🎨 Variables CSS desde SCF
Los colores del tema se gestionan desde campos SCF:
```php
// En functions.php
$color_ppal = get_field('color_principal', 'option') ?: '#D60B52';
```
Estas variables se inyectan en el CSS mediante:
```css
:root {
    --chow_ppal: <?php echo esc_attr($color_ppal); ?>;
    --chow_secundario: <?php echo esc_attr($color_secundario); ?>;
    --chow_txt: <?php echo esc_attr($color_texto); ?>;
    --chow_blanco: <?php echo esc_attr($color_fondo); ?>;
}
```

## 📄 Páginas de Opciones en Admin
- **Página principal:** Apariencia > Chow theme
- **Sub-páginas:**
  - Empresa
  - Slider Home
  - Contenido Home
  - Formularios
  - Avanzado

## 🛠️ Protocolos para Gestionar SCF
1. **Agregar nuevo grupo de campos:**
   - Crear archivo JSON en `acf-json/`
   - Definir campos con claves únicas
   - Activar sincronización en SCF
2. **Actualizar campo existente:**
   - Modificar el JSON
   - Forzar sincronización en SCF
3. **Eliminar campo:**
   - Eliminar del JSON
   - Desactivar en SCF
4. **Validar campos:**
   - Usar validaciones en SCF (ej: formato email, números)

## ✅ Checklist Pre-Actualización
- [ ] Campos únicos en cada grupo
- [ ] Validaciones adecuadas
- [ ] Sincronización forzada después de cambios
- [ ] Documentar cambios en `docs/04-acf-config.md`
