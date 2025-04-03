<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function csv_cct_importador_get_cct_types() {
    global $wpdb;

    $cct_types = $wpdb->get_results( "SELECT slug FROM {$wpdb->prefix}jet_post_types WHERE status = 'content-type'", ARRAY_A );

    $cct_type_slugs = array();
    foreach ( $cct_types as $cct_type ) {
        $cct_type_slugs[] = 'jet_cct_' . $cct_type['slug'];
    }

    return $cct_type_slugs;
}
?>
