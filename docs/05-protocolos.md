# 🛠️ Protocolos para Extender el Theme Chow-Theme v2.0

## 1. Crear una Nueva Demo
### Requisitos
- WordPress 5.0+
- WooCommerce 9.0+
- SCF configurado
- Bootstrap 5.3.0

### Pasos
1. **Crear archivo:** `demos/demo-[nombre].php`
2. **Definir estructura básica:**
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
3. **Agregar configuración de empresa** (colores, logos)
4. **Definir categorías y productos**
5. **Agregar páginas flexibles**
6. **Configurar home blocks**
7. **Agregar CSS personalizado**
8. **Probar con "Restaurar Plantilla"**

### Buenas Prácticas
- Usar nombres descriptivos
- Documentar cambios en `docs/02-demos.md`
- Evitar duplicar campos SCF

## 2. Agregar Nuevo Estilo de Tarjeta de Producto
### Requisitos
- Archivo PHP en `woocommerce/loop/card-[nombre].php`
- Estilos CSS en `assets/css/chow-wc.css`
- Campos ACF actualizados

### Pasos
1. **Crear archivo:** `woocommerce/loop/card-[nombre].php`
2. **Implementar lógica HTML/PHP**
   - Incluir `global $product`
   - Validar `$product` es un objeto WC_Product
   - Usar `get_template_part`
3. **Crear CSS:**
   - Clase `.chow-product-card-XX`
   - Estilos en `chow-wc.css`
4. **Actualizar ACF:**
   - Campos `card_style_default` (Empresa)
   - Campo `card_style` (Bloques de Productos)
5. **Probar:**
   - Importar demo
   - Verificar en tienda y homepage

### Checklist
- [ ] Archivo PHP creado
- [ ] Estilos CSS definidos
- [ ] Campos ACF actualizados
- [ ] Pruebas completadas

## 3. Crear Página Flexible
### Requisitos
- Template `flexible-page.php`
- Campos ACF definidos en `group_flexible_page.json`

### Pasos
1. **Crear/editar template:**
   - Usar `flexible-page.php` como base
   - Definir secciones (encabezado, contenido, collapses, etc.)
2. **Configurar ACF:**
   - Campos `texto_contenido`, `collapses`
   - Estructura de collapses: `titulo_collapse`, `contenido_collapse`
3. **Asignar a página:**
   - En SCF > Páginas Flexibles
4. **Probar:**
   - Importar demo
   - Verificar secciones activas

### Protocolos Adicionales
- Usar `update_field()` para guardar datos
- Validar estructura de collapses
- Documentar en `docs/02-demos.md`

## 4. Extender el Theme
### Opciones Comunes
- **Agregar secciones nuevas:**
   - Modificar `home.php` o `flexible-page.php`
- **Personalizar existentes:**
   - Ajustar CSS en `chow-base-style.css`
   - Actualizar campos SCF
- **Integrar nuevos plugins:**
   - Asegurar compatibilidad con WooCommerce/SCF

### Protocolos Específicos
1. **Agregar sección a homepage:**
   - Modificar `home.php`
   - Actualizar bloques en SCF
2. **Crear nuevo campo ACF:**
   - En `group_empresa.json` o nuevo grupo
3. **Actualizar JavaScript:**
   - Si se requiere interactividad

### Checklist
- [ ] Documentar cambios en `docs/05-protocolos.md`
- [ ] Probar en todas las secciones
- [ ] Verificar compatibilidad con SCF
