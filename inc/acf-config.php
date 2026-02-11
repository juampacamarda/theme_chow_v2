<?php /* ACF Config */

add_filter('acf/settings/save_json', 'chow_acf_json_save_point');

function chow_acf_json_save_point( $path ) {

    // update path
    $path = get_stylesheet_directory() . '/acf-json';

    // return
    return $path;

}

add_filter('acf/settings/load_json', 'chow_acf_json_load_point');

function chow_acf_json_load_point( $paths ) {

    // remove original path (optional)
    unset($paths[0]);

    // append path
    $paths[] = get_stylesheet_directory() . '/acf-json';

    // return
    return $paths;

}


if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title' 	=> 'Opciones Chow theme',
        'menu_title'	=> 'Chow theme',
        'menu_slug' 	=> 'Chow-theme',
        'redirect'		=> false,
        'position'		=> 5.4
    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Empresa',
        'menu_title'	=> 'Empresa',
        'parent_slug'	=> 'Chow-theme',
    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Slider Home',
        'menu_title'	=> 'Slider Home',
        'parent_slug'	=> 'Chow-theme',
    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Contenido Home',
        'menu_title'	=> 'Contenido Home',
        'parent_slug'	=> 'Chow-theme',
    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Formularios',
        'menu_title'	=> 'Formularios',
        'parent_slug'	=> 'Chow-theme',
    ));

    

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Avanzado',
        'menu_title'	=> 'Avanzado',
        'parent_slug'	=> 'Chow-theme',
    ));

}