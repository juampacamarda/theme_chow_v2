<?php
/**
 * Chow Theme - Image WebP Optimizer (CLI Script)
 * 
 * Convierte imágenes PNG/JPG a WebP de una sola vez
 * Se ejecuta desde línea de comandos ANTES de usar el importer
 * 
 * Uso:
 * php optimize-images.php
 * 
 * O con argumentos:
 * php optimize-images.php libreria
 * php optimize-images.php pasteleria
 */

// No cargar WordPress, trabajar directamente con archivos
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

// Obtener demo desde argumentos CLI
$demo_id = isset( $argv[1] ) ? $argv[1] : null;
$demos_to_process = $demo_id ? array( $demo_id ) : array( 'libreria', 'pasteleria' );

// Colores para terminal
$colors = array(
    'reset'   => "\e[0m",
    'bold'    => "\e[1m",
    'green'   => "\e[32m",
    'red'     => "\e[31m",
    'yellow'  => "\e[33m",
    'blue'    => "\e[34m",
    'cyan'    => "\e[36m",
);

echo $colors['bold'] . $colors['cyan'] . "\n";
echo "╔════════════════════════════════════════════════╗\n";
echo "║   Chow Theme - Image WebP Optimizer            ║\n";
echo "║   Convertir PNG/JPG → WebP (70-80% menos)     ║\n";
echo "╚════════════════════════════════════════════════╝\n";
echo $colors['reset'];

// Detectar capacidades
$capabilities = detect_image_capabilities();

echo "\n📊 Detectando capacidades del sistema...\n";
echo "   ImageMagick: " . ( $capabilities['imagemagick'] ? $colors['green'] . "✓" : $colors['red'] . "✗" ) . $colors['reset'] . "\n";
echo "   GD Library:  " . ( $capabilities['gd'] ? $colors['green'] . "✓" : $colors['red'] . "✗" ) . $colors['reset'] . "\n";
echo "   cwebp CLI:   " . ( $capabilities['cwebp'] ? $colors['green'] . "✓" : $colors['red'] . "✗" ) . $colors['reset'] . "\n";

if ( ! $capabilities['imagemagick'] && ! $capabilities['gd'] && ! $capabilities['cwebp'] ) {
    echo "\n" . $colors['red'] . "❌ Error: No hay librería de conversión disponible\n" . $colors['reset'];
    echo "   Instala ImageMagick o habilita GD en php.ini\n";
    exit( 1 );
}

// Procesar cada demo
$total_original = 0;
$total_optimized = 0;
$all_converted = 0;
$grand_total_errors = 0;

foreach ( $demos_to_process as $demo ) {
    echo "\n" . $colors['bold'] . $colors['blue'] . "▶ Procesando demo: " . strtoupper( $demo ) . $colors['reset'] . "\n";
    
    $demo_path = __DIR__ . '/' . $demo . '/images/';
    
    if ( ! is_dir( $demo_path ) ) {
        echo $colors['red'] . "   ✗ Carpeta no encontrada: $demo_path\n" . $colors['reset'];
        continue;
    }
    
    // Obtener archivos
    $image_files = glob( $demo_path . '*.{png,jpg,jpeg,gif}', GLOB_BRACE );
    
    if ( empty( $image_files ) ) {
        echo $colors['yellow'] . "   ⚠ No hay imágenes en esta carpeta\n" . $colors['reset'];
        continue;
    }
    
    echo "   📁 Encontradas " . count( $image_files ) . " imágenes\n";
    
    $demo_original = 0;
    $demo_optimized = 0;
    $demo_converted = 0;
    $demo_errors = 0;
    
    foreach ( $image_files as $i => $source_file ) {
        $filename = basename( $source_file );
        $file_size = filesize( $source_file );
        $demo_original += $file_size;
        
        // Generar WebP
        $webp_file = preg_replace( '/\.(png|jpg|jpeg|gif)$/i', '.webp', $source_file );
        
        echo "   " . str_pad( ( $i + 1 ) . "/" . count( $image_files ), 5 ) . " $filename... ";
        
        $result = convert_image_to_webp( $source_file, $webp_file, $capabilities );
        
        if ( $result === true && file_exists( $webp_file ) ) {
            $webp_size = filesize( $webp_file );
            $demo_optimized += $webp_size;
            $demo_converted++;
            $all_converted++;
            
            $reduction = ( ( $file_size - $webp_size ) / $file_size ) * 100;
            echo $colors['green'] . "✓ " . format_size( $file_size ) . " → " . format_size( $webp_size ) . " (-" . number_format( $reduction, 1 ) . "%)" . $colors['reset'] . "\n";
        } else {
            $demo_errors++;
            $grand_total_errors++;
            echo $colors['red'] . "✗ Error: " . $result . $colors['reset'] . "\n";
        }
    }
    
    // Resumen del demo
    $total_original += $demo_original;
    $total_optimized += $demo_optimized;
    $demo_savings = $demo_original - $demo_optimized;
    $demo_savings_pct = ( $demo_savings / $demo_original ) * 100;
    
    echo "\n" . $colors['bold'] . "   Resumen $demo:" . $colors['reset'] . "\n";
    echo "   ✓ Convertidas: " . $demo_converted . " / " . count( $image_files ) . "\n";
    echo "   Tamaño original:  " . format_size( $demo_original ) . "\n";
    echo "   Tamaño optimizado: " . format_size( $demo_optimized ) . "\n";
    echo "   " . $colors['green'] . "Ahorrado: " . format_size( $demo_savings ) . " (" . number_format( $demo_savings_pct, 1 ) . "%)" . $colors['reset'] . "\n";
    
    if ( $demo_errors > 0 ) {
        echo "   " . $colors['yellow'] . "Errores: $demo_errors" . $colors['reset'] . "\n";
    }
}

// Resumen total
echo "\n" . $colors['bold'] . $colors['cyan'] . "═══════════════════════════════════════════════════\n";
echo "📊 RESUMEN TOTAL\n";
echo "═══════════════════════════════════════════════════" . $colors['reset'] . "\n";
echo "Imágenes convertidas: " . $colors['green'] . $all_converted . $colors['reset'] . "\n";
echo "Tamaño original total:  " . format_size( $total_original ) . "\n";
echo "Tamaño optimizado total: " . format_size( $total_optimized ) . "\n";
$grand_savings = $total_original - $total_optimized;
$grand_savings_pct = ( $grand_savings / $total_original ) * 100;
echo $colors['green'] . "Espacio ahorrado: " . format_size( $grand_savings ) . " (" . number_format( $grand_savings_pct, 1 ) . "%)" . $colors['reset'] . "\n";

if ( $grand_total_errors > 0 ) {
    echo $colors['yellow'] . "Errores totales: $grand_total_errors" . $colors['reset'] . "\n";
}

echo "\n✅ Optimización completada.\n";
echo "   Las imágenes WebP están listas para usar.\n";
echo "   El importer las detectará automáticamente.\n\n";

exit( 0 );

// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
// FUNCIONES AUXILIARES
// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

/**
 * Detecta capacidades de conversión disponibles
 */
function detect_image_capabilities() {
    return array(
        'imagemagick' => extension_loaded( 'imagick' ),
        'gd'          => extension_loaded( 'gd' ) && function_exists( 'imagewebp' ),
        'cwebp'       => function_exists( 'shell_exec' ) && trim( shell_exec( 'which cwebp 2>/dev/null' ) ) !== '',
    );
}

/**
 * Convierte imagen a WebP
 */
function convert_image_to_webp( $source_file, $dest_file, $capabilities ) {
    // Si ya existe, saltar
    if ( file_exists( $dest_file ) ) {
        return true;
    }
    
    set_time_limit( 60 );
    
    // Intentar con ImageMagick (mejor calidad)
    if ( $capabilities['imagemagick'] ) {
        $result = convert_imagick( $source_file, $dest_file );
        if ( $result === true ) {
            return true;
        }
    }
    
    // Intentar con cwebp CLI (más rápido)
    if ( $capabilities['cwebp'] ) {
        $result = convert_cwebp_cli( $source_file, $dest_file );
        if ( $result === true ) {
            return true;
        }
    }
    
    // Fallback a GD
    if ( $capabilities['gd'] ) {
        $result = convert_gd( $source_file, $dest_file );
        if ( $result === true ) {
            return true;
        }
    }
    
    return "Sin librería disponible";
}

/**
 * Convierte con Imagick
 */
function convert_imagick( $source_file, $dest_file ) {
    try {
        $image = new Imagick( $source_file );
        $image->setImageFormat( 'webp' );
        $image->setImageCompressionQuality( 80 );
        $image->stripImage();
        
        if ( ! $image->writeImage( $dest_file ) ) {
            return "Error escribiendo archivo";
        }
        
        $image->clear();
        return true;
    } catch ( Exception $e ) {
        return "Imagick: " . $e->getMessage();
    }
}

/**
 * Convierte con cwebp CLI
 */
function convert_cwebp_cli( $source_file, $dest_file ) {
    $output = array();
    $return_code = 0;
    
    $command = sprintf(
        'cwebp -q 80 -m 6 "%s" -o "%s" 2>&1',
        escapeshellarg( $source_file ),
        escapeshellarg( $dest_file )
    );
    
    exec( $command, $output, $return_code );
    
    if ( $return_code !== 0 || ! file_exists( $dest_file ) ) {
        return "cwebp CLI fallo";
    }
    
    return true;
}

/**
 * Convierte con GD
 */
function convert_gd( $source_file, $dest_file ) {
    $mime = mime_content_type( $source_file );
    
    switch ( $mime ) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg( $source_file );
            break;
        case 'image/png':
            $image = imagecreatefrompng( $source_file );
            if ( $image === false ) {
                return "No se pudo cargar PNG";
            }
            imagepalettetotruecolor( $image );
            imagealphablending( $image, false );
            imagesavealpha( $image, true );
            break;
        case 'image/gif':
            $image = imagecreatefromgif( $source_file );
            break;
        default:
            return "Formato no soportado: $mime";
    }
    
    if ( $image === false ) {
        return "No se pudo cargar imagen";
    }
    
    if ( ! function_exists( 'imagewebp' ) ) {
        imagedestroy( $image );
        return "GD sin soporte WebP";
    }
    
    $result = imagewebp( $image, $dest_file, 80 );
    imagedestroy( $image );
    
    if ( ! $result ) {
        return "Error guardando WebP";
    }
    
    return true;
}

/**
 * Formatea tamaño de archivo
 */
function format_size( $bytes ) {
    $units = array( 'B', 'KB', 'MB', 'GB' );
    $bytes = max( $bytes, 0 );
    $pow = floor( ( $bytes ? log( $bytes ) : 0 ) / log( 1024 ) );
    $pow = min( $pow, count( $units ) - 1 );
    $bytes /= ( 1 << ( 10 * $pow ) );
    
    return round( $bytes, 2 ) . ' ' . $units[ $pow ];
}

?>
