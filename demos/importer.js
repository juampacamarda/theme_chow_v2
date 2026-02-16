/**
 * Chow Theme Demo Importer - JavaScript
 * 
 * Handles client-side interactions:
 * - Modal open/close
 * - AJAX import requests
 * - Spinner and progress display
 * - Success/error messages
 */

(function($) {
    'use strict';
    console.log('Chow Importer JS loaded.');
    
    // Demo import data
    let currentDemoId = null;
    let currentDemoName = null;
    let hasExistingContent = false;
    
    // Initialize on document ready
    $(document).ready(function() {
        console.log('Chow Importer DOM ready.');
        initializeImporter();
    });
    
     /**
      * Initialize all event listeners
      */
     function initializeImporter() {
          // Import button clicks
          $(document).on('click', '.demo-import-btn', function(e) {
              console.log('Demo import button clicked!');
              e.preventDefault();
              currentDemoId = $(this).data('demo-id');
              currentDemoName = $(this).data('demo-name');
              openModal();
          });
          
          // Restore button clicks (for active demos - always overwrite)
          $(document).on('click', '.demo-restore-btn', function(e) {
              console.log('Demo restore button clicked!');
              e.preventDefault();
              currentDemoId = $(this).data('demo-id');
              currentDemoName = $(this).data('demo-name');
              openModal(true, true); // Pass true to indicate this is a restore (overwrite with template)
          });
          
          // Modal close buttons
          $(document).on('click', '.close-modal, .cancel-modal, .chow-modal-overlay', function(e) {
              if ($(this).hasClass('chow-modal-overlay') && !$(e.target).hasClass('chow-modal-overlay')) {
                  return;
              }
              closeModal();
          });
          
          // Confirm import buttons
          $(document).on('click', '#confirm-import-btn', function(e) {
              e.preventDefault();
              performImport('import');
          });
          
          $(document).on('click', '#confirm-overwrite-btn', function(e) {
              e.preventDefault();
              performImport('overwrite');
          });
          
          // Message close
          $(document).on('click', '.message-close-btn', function(e) {
              e.preventDefault();
              closeMessage();
              // Optionally redirect
              if (window.location.href.indexOf('admin.php') > -1) {
                  window.location.reload();
              }
          });
          
          // Dismiss warnings
          $(document).on('click', '.chow-importer-warning .notice-dismiss', function(e) {
              $(this).closest('.notice').slideUp(200);
          });
      }
    
      /**
       * Open the confirmation modal
       */
      function openModal(isReimport, isRestore) {
          const modal = $('#chow-import-modal');
          
          // Update demo name in modal
          modal.find('.demo-name-display').text(currentDemoName);
          
          // If this is a restore or reimport, show the overwrite dialog directly
          if (isReimport || isRestore) {
              modal.find('#confirm-import-btn').hide();
              modal.find('#confirm-overwrite-btn').show();
              
              // Customize message based on action type
              if (isRestore) {
                  // Show restore-specific warning
                  modal.find('#modal-content-initial').hide();
                  modal.find('#modal-content-existing').hide();
                  modal.find('#modal-content-restore').show();
                  modal.find('#confirm-overwrite-btn').text('Restaurar Plantilla').css({
                      'background-color': '#dc3545',
                      'border-color': '#dc3545'
                  });
              } else {
                  // Show reimport warning (less aggressive)
                  modal.find('#modal-content-initial').hide();
                  modal.find('#modal-content-existing').show();
                  modal.find('#modal-content-restore').hide();
                  modal.find('#modal-content-existing p:first').text('¿Deseas reimportar este demo? El contenido anterior será eliminado y reemplazado con la versión actualizada.');
                  modal.find('#confirm-overwrite-btn').text('Sobrescribir y Continuar').css({
                      'background-color': '#dc3545',
                      'border-color': '#dc3545'
                  });
              }
          } else {
              // Check if there's existing content by sending an AJAX check
              checkExistingContent(function(hasContent) {
                  hasExistingContent = hasContent;
                  
                  if (hasContent) {
                      // Show the "existing content" version
                      modal.find('#modal-content-initial').hide();
                      modal.find('#modal-content-existing').show();
                      modal.find('#modal-content-restore').hide();
                      modal.find('#confirm-import-btn').hide();
                      modal.find('#confirm-overwrite-btn').show();
                      modal.find('#modal-content-existing p:first').text('Ya existe contenido en tu sitio. ¿Deseas sobrescribirlo con el demo?');
                      modal.find('#confirm-overwrite-btn').text('Sobrescribir y Continuar').css({
                          'background-color': '#dc3545',
                          'border-color': '#dc3545'
                      });
                  } else {
                      // Show the regular version
                      modal.find('#modal-content-initial').show();
                      modal.find('#modal-content-existing').hide();
                      modal.find('#modal-content-restore').hide();
                      modal.find('#confirm-import-btn').show();
                      modal.find('#confirm-overwrite-btn').hide();
                  }
              });
          }
          
          // Show modal with fade
          modal.fadeIn(300);
          modal.find('.chow-modal-content').css('opacity', '0').animate({opacity: 1}, 300);
      }
    
    /**
     * Close the modal
     */
    function closeModal() {
        const modal = $('#chow-import-modal');
        modal.find('.chow-modal-content').animate({opacity: 0}, 200, function() {
            modal.fadeOut(300);
        });
    }
    
    /**
     * Check if there's existing content
     */
    function checkExistingContent(callback) {
        // Since we're checking in the AJAX handler, we'll use a simple heuristic
        // In production, you could send a separate AJAX call
        // For now, we'll check in the perform import function
        callback(false); // Default to no content
    }
    
       /**
        * Perform the import
        */
       function performImport(actionType) {
           closeModal();
           showSpinner();
           
           // Disable buttons while importing
           $('.demo-import-btn, .demo-restore-btn').prop('disabled', true);
           
           $.ajax({
               url: chowImporter.ajax_url,
               type: 'POST',
               dataType: 'json',
               data: {
                   action: 'chow_import_demo',
                   nonce: chowImporter.nonce,
                   demo_id: currentDemoId,
                   action_type: actionType,
               },
               success: function(response) {
                   hideSpinner();
                   
                   if (response.success) {
                       // Build message text
                       let messageText = 'El demo "' + currentDemoName + '" ha sido importado correctamente. Tu sitio ha sido actualizado con los nuevos contenidos y estilos.';
                       
                       // Check if there are skipped plugins
                       if (response.data && response.data.skipped_plugins && Object.keys(response.data.skipped_plugins).length > 0) {
                           const skippedPlugins = response.data.skipped_plugins;
                           const pluginNames = Object.values(skippedPlugins).join(', ');
                           messageText += '\n\n⚠️ Nota: Los siguientes plugins opcionales no están instalados: ' + pluginNames + '. Algunas características pueden no estar completamente configuradas.';
                       }
                       
                       // Show success message
                       showMessage(
                           'success',
                           '¡Demo Importada!',
                           messageText,
                           response.data ? response.data.skipped_plugins : null
                       );
                       
                       // Re-enable buttons
                       $('.demo-import-btn, .demo-restore-btn').prop('disabled', false);
                       
                       // After closing the message, the page will reload
                   } else {
                       // Show error message
                       showMessage(
                           'error',
                           'Error en la Importación',
                           response.data && response.data.message ? response.data.message : 'Ocurrió un error al importar el demo.'
                       );
                       
                       // Re-enable buttons
                       $('.demo-import-btn, .demo-restore-btn').prop('disabled', false);
                   }
               },
               error: function(xhr, status, error) {
                   hideSpinner();
                   
                   let errorMsg = 'No se pudo conectar con el servidor. Por favor, intenta nuevamente.';
                   let detailedMsg = '\n\nDetalles técnicos:\n';
                   
                   // Timeout
                   if (status === 'timeout') {
                       errorMsg = '⏱️ La operación tardó demasiado tiempo (más de 10 minutos)';
                       detailedMsg += '• La importación superó el límite de tiempo\n';
                       detailedMsg += '• Posibles causas:\n';
                       detailedMsg += '  - Local.app está lento\n';
                       detailedMsg += '  - Las imágenes son muy pesadas\n';
                       detailedMsg += '  - No hay suficiente memoria PHP\n\n';
                       detailedMsg += 'Soluciones:\n';
                       detailedMsg += '1. Reinicia Local.app\n';
                       detailedMsg += '2. Revisa WP-Admin > Chow Theme > Importar Demo > Ver Logs\n';
                       detailedMsg += '3. Aumenta max_execution_time en php.ini a 900+ segundos\n';
                   }
                   // Network error
                   else if (xhr.status === 0 && error === 'error') {
                       errorMsg = '🌐 Error de conexión de red';
                       detailedMsg += '• No se pudo establecer conexión con el servidor\n';
                       detailedMsg += '• Verifica que Local.app esté corriendo\n';
                       detailedMsg += '• Intenta navegar al sitio normalmente\n';
                   }
                   // Server error
                   else if (xhr.status === 500) {
                       errorMsg = '❌ Error interno del servidor (500)';
                       detailedMsg += '• El servidor WordPress encontró un error\n';
                       detailedMsg += '• Revisa los logs:\n';
                       detailedMsg += '  - WP-Admin > Chow Theme > Importar Demo > Ver Logs\n';
                       detailedMsg += '  - Local.app: Click derecho > Logs > PHP\n';
                   }
                   // Bad gateway
                   else if (xhr.status === 502 || xhr.status === 503) {
                       errorMsg = '⚠️ Servidor no disponible (' + xhr.status + ')';
                       detailedMsg += '• Local.app puede estar reiniciándose\n';
                       detailedMsg += '• Intenta nuevamente en unos segundos\n';
                   }
                   // Parse error
                   else if (status === 'parsererror') {
                       errorMsg = '📝 Error procesando respuesta del servidor';
                       detailedMsg += '• Respuesta inválida recibida\n';
                       detailedMsg += '• Posible conflicto con otro plugin\n';
                       detailedMsg += '• Revisa los logs para más detalles\n';
                   }
                   
                   if (xhr.responseText) {
                       detailedMsg += '\nRespuesta del servidor:\n' + xhr.responseText.substring(0, 200) + '...\n';
                   }
                   
                   detailedMsg += '\nEstado: ' + status + '\n';
                   detailedMsg += 'Código HTTP: ' + xhr.status + '\n';
                   
                   // Show error message
                   showMessage(
                       'error',
                       'Error de Conexión',
                       errorMsg + detailedMsg
                   );
                   
                   // Re-enable buttons
                   $('.demo-import-btn, .demo-restore-btn').prop('disabled', false);
                   
                   console.error('AJAX Error:', {
                       status: status,
                       error: error,
                       xhr: xhr,
                       statusCode: xhr.status,
                       statusText: xhr.statusText,
                       response: xhr.responseText
                   });
               },
               timeout: 900000, // 15 minutes - necesario para Local.app con muchas imágenes
           });
       }
    
    /**
     * Show the loading spinner
     */
    function showSpinner() {
        const spinner = $('#chow-import-spinner');
        spinner.fadeIn(300);
        
        // Iniciar mensajes dinámicos
        startDynamicMessages();
        
        // Simulate progress
        let progress = 0;
        const progressInterval = setInterval(function() {
            progress += Math.random() * 15;
            if (progress > 85) {
                progress = 85; // Cap at 85% until complete
            }
            $('#progress-fill').css('width', progress + '%');
        }, 800);
        
        // Store interval ID for cleanup
        spinner.data('progressInterval', progressInterval);
    }
    
    /**
     * Hide the loading spinner
     */
    function hideSpinner() {
        const spinner = $('#chow-import-spinner');
        
        // Stop dynamic messages
        stopDynamicMessages();
        
        // Clear progress interval
        const progressInterval = spinner.data('progressInterval');
        if (progressInterval) {
            clearInterval(progressInterval);
        }
        
        // Complete the progress bar
        $('#progress-fill').css('width', '100%');
        
        // Hide spinner after a short delay
        setTimeout(function() {
            spinner.fadeOut(300);
            // Reset progress for next time
            $('#progress-fill').css('width', '0%');
        }, 500);
    }
    
     /**
      * Show success or error message
      */
     function showMessage(type, title, text, skippedPlugins) {
         const messageEl = $('#chow-import-message');
         const iconEl = messageEl.find('.message-icon');
         const titleEl = messageEl.find('#message-title');
         const textEl = messageEl.find('#message-text');
         
         // Update message content
         titleEl.text(title);
         textEl.text(text);
         
         // Update icon
         iconEl.removeClass('success error')
               .addClass(type);
         
         // Show message
         messageEl.fadeIn(300);
         messageEl.find('.message-content').css('opacity', '0').animate({opacity: 1}, 300);
         
         // Show warning banner if there are skipped plugins
         if (type === 'success' && skippedPlugins && Object.keys(skippedPlugins).length > 0) {
             showPluginWarningBanner(skippedPlugins);
         }
     }
     
     /**
      * Show warning banner for missing optional plugins
      */
     function showPluginWarningBanner(skippedPlugins) {
         // Delay banner appearance to after modal is visible
         setTimeout(function() {
             const bannerHtml = buildPluginWarningBanner(skippedPlugins);
             
             // Insert banner after message
             if ($('#chow-importer-plugin-warning').length === 0) {
                 $('#chow-import-message').after(bannerHtml);
             }
             
             $('#chow-importer-plugin-warning').fadeIn(300);
         }, 500);
     }
     
     /**
      * Build HTML for plugin warning banner
      */
     function buildPluginWarningBanner(skippedPlugins) {
         const pluginList = Object.values(skippedPlugins).join(', ');
         return '<div id="chow-importer-plugin-warning" class="notice notice-warning is-dismissible chow-importer-warning" style="margin: 20px 0; padding: 15px; display:none;">' +
                '<p><strong>⚠️ Plugins Recomendados Faltantes:</strong></p>' +
                '<p>Los siguientes plugins son opcionales pero recomendados para una experiencia completa:</p>' +
                '<p><strong>' + pluginList + '</strong></p>' +
                '<p>Puedes instalarlos más tarde desde el panel de administración.</p>' +
                '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Descartar</span></button>' +
                '</div>';
     }
    
    // Mensajes dinámicos para narrar el proceso
    const importMessages = [
        '⏳ Preparando importación...',
        '📥 Descargando imágenes del demo...',
        '🖼️ Procesando galería de productos...',
        '📝 Creando categorías de productos...',
        '🛍️ Importando catálogo de productos...',
        '📄 Generando páginas del sitio...',
        '📋 Configurando formularios de contacto...',
        '🎨 Aplicando estilos y colores...',
        '📱 Configurando menú de navegación...',
        '⚙️ Optimizando base de datos...',
        '✨ Últimos ajustes finales...',
        '🔄 Sincronizando configuración...',
    ];
    
    let messageInterval = null;
    let currentMessageIndex = 0;
    
    function startDynamicMessages() {
        currentMessageIndex = 0;
        clearInterval(messageInterval);
        
        // Cambiar mensajes cada 2.5 segundos para que parezcan diferentes
        messageInterval = setInterval(function() {
            currentMessageIndex = (currentMessageIndex + 1) % importMessages.length;
            const messageEl = $('#dynamic-message');
            
            // Animación de transición
            messageEl.removeClass('fade-in').addClass('fade-out');
            setTimeout(function() {
                messageEl.text(importMessages[currentMessageIndex]);
                messageEl.removeClass('fade-out').addClass('fade-in');
                
                // Animar barra de progreso pseudo-aleatoria
                const currentProgress = Math.min(95, Math.random() * 50 + 80);
                $('#progress-fill').css('width', currentProgress + '%');
            }, 400);
        }, 2500);
    }
    
    function stopDynamicMessages() {
        clearInterval(messageInterval);
    }
    
    /**
     * Close the message
     */
    function closeMessage() {
        const messageEl = $('#chow-import-message');
        messageEl.find('.message-content').animate({opacity: 0}, 200, function() {
            messageEl.fadeOut(300);
        });
    }
    
})(jQuery);
