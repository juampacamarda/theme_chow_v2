# 🛒 Sistema Modular de Tarjetas de Producto - Chow Theme v2.0

## 🎯 Objetivo
Proporcionar un sistema flexible y escalable para mostrar productos en WooCommerce, con tres estilos predefinidos que se pueden personalizar globalmente o por bloque en la homepage.

## 🧱 Estructura del Sistema
- **Tarjetas modulares:** Cada estilo es un componente reutilizable
- **Configuración dual:** Estilo global (empresa) + sobrescritura por bloque (home)
- **Clases CSS numeradas:** `.chow-product-card-01` (clásico), `.chow-product-card-02` (hover visual), `.chow-product-card-03` (flip)

## 🎨 Estilos Disponibles
### 1. Tarjeta Clásica (01)
**Descripción:** Estructura vertical estándar con imagen, título, precio y botón.
**Características:**
- Ideal para catálogos simples
- Menos recursos visuales
- Buen rendimiento
**Código base:**
```php
<div class="woocommerce-product-card chow-product-card-01">
    <h3 class="woocommerce-loop-product__title"><?php the_title(); ?></h3>
    <span class="price"><?php echo $product->get_price_html(); ?></span>
    <a class="add-to-cart-button" href="<?php the_permalink(); ?>">Agregar al carrito</a>
</div>
```

### 2. Tarjeta Hover Visual (02)
**Descripción:** Diseño moderno con imagen a tamaño completo y efectos al pasar el mouse.
**Características:**
- Mayor impacto visual
- Efectos de hover (overlay, zoom)
- Requiere CSS avanzado
**Código base:**
```php
<div class="chow-card-hover-visual" style="background-image: url('<?php echo $image_url; ?>')">
    <a href="<?php the_permalink(); ?>" class="chow-card-hover-overlay">
        <div class="chow-card-content">
            <h3><?php the_title(); ?></h3>
            <span class="price"><?php echo $product->get_price_html(); ?></span>
            <button class="add-to-cart-button">Ver Producto</button>
        </div>
    </a>
</div>
```

### 3. Tarjeta Flip (03)
**Descripción:** Diseño interactivo con cara frontal (imagen) y trasera (detalles).
**Características:**
- Experiencia interactiva
- Requiere JavaScript/CSS avanzado
- Ideal para productos destacados
**Código base:**
```php
<div class="chow-card-flip" role="group" aria-label="<?php the_title(); ?>">
    <div class="chow-card-flip-inner">
        <!-- Cara frontal -->
        <div class="chow-card-front" style="background-image: url('<?php echo $image_url; ?>')">
            <h3><?php the_title(); ?></h3>
        </div>
        <!-- Cara trasera -->
        <div class="chow-card-back">
            <h3><?php the_title(); ?></h3>
            <span class="price"><?php echo $product->get_price_html(); ?></span>
            <a class="add-to-cart-button" href="<?php the_permalink(); ?>">Agregar al carrito</a>
        </div>
    </div>
</div>
```

## ⚙️ Configuración
### Estilo Predeterminado Global
- **Ruta:** Apariencia > Chow theme > Empresa > Estilo de Tarjeta Predeterminado
- **Opciones:**
  - `classic` (por defecto)
  - `hover_visual`
  - `flip`

### Sobrescritura por Bloque en Home
- **Ruta:** Apariencia > Chow theme > Contenido Home
- **Campo:** "Estilo de Tarjeta" en cada bloque de productos
- **Opción "Usar predeterminado":" Sí/No"

## 🔧 Extensibilidad
### Agregar Nuevo Estilo
1. **Crear archivo:** `woocommerce/loop/card-[nombre].php`
2. **Implementar lógica HTML/PHP**
3. **Agregar a SCF:**
   - Campos `card_style_default` en "Empresa"
   - Campo `card_style` en "Bloques de Productos"
4. **Crear CSS:** `.chow-product-card-04` en `assets/css/chow-wc.css`

## 📌 Protocolos para Crear Nuevas Tarjetas
1. **Nombre del archivo:** `card-[nombre].php`
2. **Estructura básica:**
   - Incluir `global $product`
   - Validar que `$product` sea un objeto WC_Product
   - Usar `get_template_part` para carga
3. **CSS:**
   - Crear clase `.chow-product-card-XX`
   - Añadir estilos en `chow-wc.css`
4. **SCF:**
   - Actualizar campos `card_style_default` y `card_style`
5. **Pruebas:**
   - Importar demo
   - Verificar en tienda y homepage

## ✅ Checklist Pre-Extensión
- [ ] Archivo PHP creado en `woocommerce/loop/`
- [ ] Estilos CSS definidos
- [ ] Campos SCF actualizados
- [ ] Pruebas en tienda y homepage
