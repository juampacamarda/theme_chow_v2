<?php
/**
 * Demo Importer UI
 * 
 * Renders the admin page for importing demos
 * - Displays available demos
 * - Shows demo information and status
 * - Provides import buttons and confirmation modal
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check user capabilities
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'No tienes permisos para acceder a esta página.' );
}

// Get active demo if any
$active_demo = get_option( 'chow_active_demo' );

// Build demos array
$demos = array();
$demos[] = chow_get_demo_libreria();

?>

<div class="wrap chow-importer-wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    
    <!-- Header Description -->
    <div class="chow-importer-header">
        <p><?php echo wp_kses_post( __( 
            'Elige un demo para importar y personaliza tu sitio. Los demos incluyen productos, páginas, formularios de contacto y estilos predefinidos.', 
            'chow-theme' 
        ) ); ?></p>
    </div>
    
    <!-- Warning Alert -->
    <div class="notice notice-warning is-dismissible chow-importer-warning">
        <p>
            <strong><?php esc_html_e( 'Importante:', 'chow-theme' ); ?></strong>
            <?php esc_html_e( 
                'Importar un demo modificará la configuración del sitio, agregará contenido y puede sobrescribir estilos personalizados. Se recomienda hacer una copia de seguridad antes de continuar.', 
                'chow-theme' 
            ); ?>
        </p>
    </div>
    
    <!-- Demos Grid -->
    <div class="chow-demos-grid">
        <?php foreach ( $demos as $demo ) : 
            $is_active = ( $active_demo === $demo['id'] );
            $has_image = ! empty( $demo['image'] );
        ?>
            <div class="demo-card">
                <!-- Demo Image -->
                <?php if ( $has_image ) : ?>
                    <div class="demo-card-image">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/demos/' . $demo['id'] . '/images/' . $demo['image'] ); ?>" 
                             alt="<?php echo esc_attr( $demo['name'] ); ?>">
                    </div>
                <?php else : ?>
                    <div class="demo-card-image demo-card-image-placeholder">
                        <span><?php echo esc_html( $demo['name'] ); ?></span>
                    </div>
                <?php endif; ?>
                
                <!-- Demo Info -->
                <div class="demo-card-content">
                    <h3><?php echo esc_html( $demo['name'] ); ?></h3>
                    <p class="demo-description"><?php echo wp_kses_post( $demo['description'] ); ?></p>
                    
                    <!-- Stats -->
                    <div class="demo-stats">
                        <span class="stat">
                            <strong><?php echo count( $demo['products'] ); ?></strong>
                            <?php echo count( $demo['products'] ) === 1 ? 'Producto' : 'Productos'; ?>
                        </span>
                        <span class="stat">
                            <strong><?php echo count( $demo['categories'] ); ?></strong>
                            <?php echo count( $demo['categories'] ) === 1 ? 'Categoría' : 'Categorías'; ?>
                        </span>
                        <span class="stat">
                            <strong><?php echo count( $demo['pages'] ); ?></strong>
                            <?php echo count( $demo['pages'] ) === 1 ? 'Página' : 'Páginas'; ?>
                        </span>
                        <span class="stat">
                            <strong><?php echo count( $demo['forms'] ); ?></strong>
                            <?php echo count( $demo['forms'] ) === 1 ? 'Formulario' : 'Formularios'; ?>
                        </span>
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="demo-status">
                        <?php if ( $is_active ) : ?>
                            <span class="badge badge-success">
                                <?php esc_html_e( 'Activo', 'chow-theme' ); ?>
                            </span>
                        <?php else : ?>
                            <span class="badge badge-secondary">
                                <?php esc_html_e( 'Disponible', 'chow-theme' ); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                 <!-- Action Button -->
                 <div class="demo-card-footer">
                     <?php if ( $is_active ) : ?>
                         <div class="demo-card-actions">
                             <button type="button" 
                                     class="button button-secondary" 
                                     disabled
                                     title="<?php esc_attr_e( 'Este demo ya está activo', 'chow-theme' ); ?>">
                                 <?php esc_html_e( 'Activo', 'chow-theme' ); ?>
                             </button>
                             <button type="button" 
                                     class="button button-primary demo-restore-btn" 
                                     data-demo-id="<?php echo esc_attr( $demo['id'] ); ?>"
                                     data-demo-name="<?php echo esc_attr( $demo['name'] ); ?>"
                                     title="<?php esc_attr_e( 'Restaurar plantilla original - sobrescribe todo el contenido', 'chow-theme' ); ?>">
                                 <?php esc_html_e( 'Restaurar Plantilla', 'chow-theme' ); ?>
                             </button>
                         </div>
                     <?php else : ?>
                         <button type="button" 
                                 class="button button-primary demo-import-btn" 
                                 data-demo-id="<?php echo esc_attr( $demo['id'] ); ?>"
                                 data-demo-name="<?php echo esc_attr( $demo['name'] ); ?>">
                             <?php esc_html_e( 'Importar Demo', 'chow-theme' ); ?>
                         </button>
                     <?php endif; ?>
                 </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Import Confirmation Modal -->
<div id="chow-import-modal" class="chow-modal" style="display: none;">
    <div class="chow-modal-overlay"></div>
    <div class="chow-modal-content">
        <div class="chow-modal-header">
            <h2><?php esc_html_e( 'Confirmar Importación', 'chow-theme' ); ?></h2>
            <button type="button" class="close-modal">&times;</button>
        </div>
        
        <div class="chow-modal-body">
            <div id="modal-content-initial" class="modal-content-section">
                <p><?php esc_html_e( 
                    '¿Deseas importar el siguiente demo?', 
                    'chow-theme' 
                ); ?></p>
                <p class="demo-name-display" style="font-weight: bold; margin: 15px 0;"></p>
                <p class="warning-text" style="color: #dc3545; margin-top: 15px;">
                    <?php esc_html_e( 
                        'Esta acción puede cambiar la configuración del sitio, incluidos colores, imágenes y estructura de menú.', 
                        'chow-theme' 
                    ); ?>
                </p>
            </div>
            
            <div id="modal-content-existing" class="modal-content-section" style="display: none;">
                <p><?php esc_html_e( 
                    'Ya existe contenido en tu sitio. ¿Deseas sobrescribirlo con el demo?', 
                    'chow-theme' 
                ); ?></p>
                <p class="demo-name-display" style="font-weight: bold; margin: 15px 0;"></p>
                <div class="alert alert-danger">
                    <strong><?php esc_html_e( 'Advertencia:', 'chow-theme' ); ?></strong><br>
                    <?php esc_html_e( 
                        'Este demo sobrescribirá tu contenido actual. Asegúrate de tener una copia de seguridad.', 
                        'chow-theme' 
                    ); ?>
                </div>
            </div>
        </div>
        
        <div class="chow-modal-footer">
            <button type="button" class="button button-secondary cancel-modal">
                <?php esc_html_e( 'Cancelar', 'chow-theme' ); ?>
            </button>
            <button type="button" class="button button-primary confirm-import" id="confirm-import-btn">
                <?php esc_html_e( 'Confirmar Importación', 'chow-theme' ); ?>
            </button>
            <button type="button" class="button button-primary confirm-import-overwrite" id="confirm-overwrite-btn" style="display: none; background-color: #dc3545; border-color: #dc3545;">
                <?php esc_html_e( 'Sobrescribir y Continuar', 'chow-theme' ); ?>
            </button>
        </div>
    </div>
</div>

<!-- Spinner and Progress -->
<div id="chow-import-spinner" class="chow-spinner" style="display: none;">
    <div class="spinner-overlay"></div>
    <div class="spinner-content">
        <div class="spinner"></div>
        <p><?php esc_html_e( 'Importando demo...', 'chow-theme' ); ?></p>
        <div class="progress-bar">
            <div class="progress-fill" id="progress-fill"></div>
        </div>
    </div>
</div>

<!-- Success/Error Message -->
<div id="chow-import-message" class="chow-message" style="display: none;">
    <div class="message-overlay"></div>
    <div class="message-content">
        <div class="message-icon"></div>
        <h2 id="message-title"></h2>
        <p id="message-text"></p>
        <button type="button" class="button button-primary message-close-btn">
            <?php esc_html_e( 'Cerrar', 'chow-theme' ); ?>
        </button>
    </div>
</div>

<style>
/* Inline styles for the importer UI */
.chow-importer-wrap {
    max-width: 1200px;
    margin: 20px auto;
}

.chow-importer-header {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    padding: 15px 20px;
    margin: 20px 0;
}

.chow-importer-warning {
    background-color: #fff3cd;
    border-color: #ffc107;
}

.chow-demos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
    margin: 30px 0;
}

.demo-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.demo-card:hover {
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.demo-card-image {
    width: 100%;
    height: 200px;
    background: #f5f5f5;
    overflow: hidden;
}

.demo-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.demo-card-image-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 18px;
    font-weight: bold;
    padding: 20px;
    text-align: center;
}

.demo-card-content {
    flex: 1;
    padding: 20px;
}

.demo-card-content h3 {
    margin: 0 0 10px;
    font-size: 18px;
    color: #333;
}

.demo-description {
    color: #666;
    font-size: 14px;
    line-height: 1.6;
    margin: 10px 0 20px;
}

.demo-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin: 20px 0;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.stat {
    font-size: 13px;
    color: #666;
}

.stat strong {
    color: #333;
    font-size: 16px;
    display: block;
}

.demo-status {
    margin-top: 15px;
}

.badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-secondary {
    background: #e2e3e5;
    color: #383d41;
}

.demo-card-footer {
    padding: 20px;
    border-top: 1px solid #eee;
}

.demo-card-actions {
    display: flex;
    gap: 10px;
    align-items: stretch;
}

.demo-card-actions .button {
    flex: 1;
    padding: 10px;
    font-size: 14px;
    font-weight: 600;
}

.demo-import-btn,
.demo-reimport-btn {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    font-weight: 600;
}

.demo-import-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Modal Styles */
.chow-modal,
.chow-spinner,
.chow-message {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
}

.chow-modal-overlay,
.spinner-overlay,
.message-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
}

.chow-modal-content,
.spinner-content,
.message-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    z-index: 10001;
}

.chow-modal-content {
    width: 90%;
    max-width: 500px;
}

.chow-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #eee;
}

.chow-modal-header h2 {
    margin: 0;
    font-size: 18px;
}

.close-modal {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
}

.close-modal:hover {
    color: #333;
}

.chow-modal-body {
    padding: 20px;
}

.modal-content-section {
    margin-bottom: 15px;
}

.alert {
    padding: 12px 15px;
    border-radius: 4px;
    margin: 10px 0;
}

.alert-danger {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.chow-modal-footer {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    padding: 20px;
    border-top: 1px solid #eee;
}

.chow-modal-footer .button {
    min-width: 120px;
}

/* Spinner */
.spinner-content {
    text-align: center;
    padding: 40px;
    width: 90%;
    max-width: 400px;
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.progress-bar {
    width: 100%;
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    margin-top: 15px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: #667eea;
    width: 0%;
    transition: width 0.3s ease;
}

/* Message */
.message-content {
    text-align: center;
    padding: 40px;
    width: 90%;
    max-width: 400px;
}

.message-icon {
    font-size: 48px;
    margin-bottom: 15px;
}

.message-icon.success::before {
    content: '✓';
    color: #28a745;
}

.message-icon.error::before {
    content: '✕';
    color: #dc3545;
}

#message-title {
    margin: 0 0 10px;
    font-size: 20px;
}

#message-text {
    color: #666;
    margin: 10px 0 20px;
}

.message-close-btn {
    min-width: 100px;
}

/* Responsive */
@media (max-width: 768px) {
    .chow-demos-grid {
        grid-template-columns: 1fr;
    }
    
    .chow-modal-content {
        width: 95%;
    }
}
</style>
