/* ACF Config */

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
        'page_title' 	=> 'Editar Footer del Sitio',
        'menu_title'	=> 'Footer',
        'parent_slug'	=> 'Chow-theme',
    ));


    acf_add_options_sub_page(array(
        'page_title' 	=> 'Slide Home',
        'menu_title'	=> 'slide',
        'parent_slug'	=> 'Chow-theme',
    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Formulario Consulta Productos',
        'menu_title'	=> 'Formulario Productos',
        'parent_slug'	=> 'Chow-theme',
    ));

}