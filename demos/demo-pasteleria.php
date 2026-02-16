<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function chow_demo_pasteleria_manifest_path() {
    return get_template_directory() . '/demos/pasteleria/manifest.json';
}

function chow_demo_pasteleria_get_manifest() {
    $path = chow_demo_pasteleria_manifest_path();
    if ( ! file_exists( $path ) ) {
        return null;
    }
    $json = file_get_contents( $path );
    return json_decode( $json, true );
}

function chow_demo_pasteleria_register() {
    if ( ! function_exists( 'chow_register_demo' ) ) {
        return;
    }
    chow_register_demo( array(
        'id' => 'pasteleria',
        'title' => 'Harina & Miel',
        'manifest' => chow_demo_pasteleria_manifest_path(),
        'import_callback' => 'chow_demo_pasteleria_importer_callback'
    ) );
}

function chow_demo_pasteleria_importer_callback( $demo_id ) {
    $manifest = chow_demo_pasteleria_get_manifest();
    if ( ! $manifest ) {
        return new WP_Error( 'no_manifest', 'Manifest no encontrado para demo pasteleria' );
    }
    update_option( 'chow_demo_' . $demo_id . '_active', 1 );
    return true;
}

add_action( 'init', 'chow_demo_pasteleria_register' );
