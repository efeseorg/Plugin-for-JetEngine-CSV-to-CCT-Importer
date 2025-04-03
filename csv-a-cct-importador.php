<?php
/**
 * Plugin Name: CSV a CCT Importador
 * Description: Un plugin para importar items desde archivos CSV a Custom Content Types (CCT) creados con JetEngine.
 * Version: 2.0
 * Author: Francisco Sánchez
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function csv_cct_importador_enqueue_scripts($hook) {
    if ($hook != 'toplevel_page_csv-cct-importador') {
        return;
    }
    wp_enqueue_style( 'csv-cct-importador-css', plugin_dir_url( __FILE__ ) . 'csv-cct-importador.css' );
}
add_action( 'admin_enqueue_scripts', 'csv_cct_importador_enqueue_scripts' );

include plugin_dir_path( __FILE__ ) . 'admin/menu.php';
include plugin_dir_path( __FILE__ ) . 'admin/import-handler.php';
include plugin_dir_path( __FILE__ ) . 'includes/cct-detection.php';
?>
