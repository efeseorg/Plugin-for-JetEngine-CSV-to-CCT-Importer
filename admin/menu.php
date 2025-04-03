<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function csv_cct_importador_menu() {
    add_menu_page(
        'Importar Items CCT',
        'Importar Items CCT',
        'manage_options',
        'csv-cct-importador',
        'csv_cct_importador_page',
        'dashicons-upload',
        3
    );
}
add_action( 'admin_menu', 'csv_cct_importador_menu' );

function csv_cct_importador_page() {
    if (isset($_GET['step']) && $_GET['step'] == 'mapping') {
        include plugin_dir_path( __FILE__ ) . 'import-page-mapping.php';
    } else {
        include plugin_dir_path( __FILE__ ) . 'import-page.php';
    }
}
?>
