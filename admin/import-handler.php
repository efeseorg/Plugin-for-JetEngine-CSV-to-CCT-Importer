<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action('admin_post_import_csv', 'csv_cct_importador_handle_import');

function csv_cct_importador_handle_import() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die('No tienes permisos para acceder a esta página.');
    }

    if ( ! isset( $_FILES['csv_file'] ) || ! isset( $_POST['cct_type'] ) ) {
        wp_die('Falta el archivo CSV o el tipo de CCT.');
    }

    $csv_file = $_FILES['csv_file'];
    $cct_type = sanitize_text_field( $_POST['cct_type'] );

    // Validar el archivo CSV
    if ( $csv_file['error'] != UPLOAD_ERR_OK ) {
        wp_die('Error al subir el archivo CSV.');
    }

    $file_type = wp_check_filetype( $csv_file['name'] );
    if ( $file_type['ext'] != 'csv' ) {
        wp_die('El archivo subido no es un archivo CSV válido.');
    }

    // Guardar el archivo CSV temporalmente
    $upload_dir = wp_upload_dir();
    $tmp_file_path = $upload_dir['path'] . '/' . basename( $csv_file['tmp_name'] );
    move_uploaded_file( $csv_file['tmp_name'], $tmp_file_path );

    // Procesar el archivo CSV y mapear los campos
    $csv_data = array_map( 'str_getcsv', file( $tmp_file_path ) );
    if (empty($csv_data)) {
        wp_die('No se pudo leer el archivo CSV.');
    }
    $csv_headers = array_shift( $csv_data );

    $cct_fields = csv_cct_importador_get_cct_fields( $cct_type );
    if (empty($cct_fields)) {
        wp_die('No se encontraron campos para el CCT seleccionado.');
    }

    // Guardar datos en un transitorio para usar en la página de mapeo
    $transient_key = 'csv_cct_import_' . get_current_user_id();
    set_transient( $transient_key, [
        'csv_headers' => $csv_headers,
        'csv_data'    => $csv_data,
        'cct_fields'  => $cct_fields,
        'cct_type'    => $cct_type,
        'csv_file_path' => $tmp_file_path,
    ], 60 * 60 ); // 1 hora

    // Redirigir a la página de mapeo de campos
    wp_redirect(admin_url('admin.php?page=csv-cct-importador&step=mapping'));
    exit;
}

function csv_cct_importador_get_cct_fields( $cct_type ) {
    global $wpdb;

    // Obtener los metadatos de los campos desde la tabla jet_post_types
    $meta_fields = $wpdb->get_var( $wpdb->prepare(
        "SELECT meta_fields FROM {$wpdb->prefix}jet_post_types WHERE slug = %s",
        str_replace('jet_cct_', '', $cct_type)
    ));

    $field_names = array();

    if ($meta_fields) {
        $meta_fields = maybe_unserialize($meta_fields);

        foreach ($meta_fields as $field) {
            $field_key = $field['name'];
            $field_label = $field['title'];
            $field_names[$field_key] = $field_label;
        }
    }

    return $field_names;
}

add_action('admin_post_handle_mapping', 'csv_cct_importador_handle_mapping');

function csv_cct_importador_handle_mapping() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die('No tienes permisos para acceder a esta página.');
    }

    $transient_key = 'csv_cct_import_' . get_current_user_id();
    $import_data = get_transient( $transient_key );

    if ( ! $import_data ) {
        wp_die('Los datos de importación no se encontraron o expiraron.');
    }

    $csv_headers = $import_data['csv_headers'];
    $csv_data    = $import_data['csv_data'];
    $cct_fields  = $import_data['cct_fields'];
    $cct_type    = $import_data['cct_type'];
    $csv_file_path = $import_data['csv_file_path'];

    if ( ! isset( $_POST['field_mapping'] ) ) {
        wp_die('Faltan los mapeos de los campos.');
    }

    $field_mapping = $_POST['field_mapping'];

    global $wpdb;

    // Identificar el campo único (_id)
    $unique_field = '_id';

    // Insertar o actualizar los datos en el CCT
    foreach ( $csv_data as $row ) {
        $data = array();
        $unique_value = '';

        foreach ( $csv_headers as $index => $header ) {
            if ( isset( $field_mapping[ $header ] ) && ! empty( $field_mapping[ $header ] ) ) {
                $data[ $field_mapping[ $header ] ] = $row[ $index ];
                if ( $field_mapping[ $header ] === $unique_field ) {
                    $unique_value = $row[ $index ];
                }
            }
        }

        // Verificar si el registro ya existe
        $existing = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}{$cct_type} WHERE {$unique_field} = %s",
            $unique_value
        ));

        if ( $existing ) {
            // Actualizar el registro existente si ya existe
            $wpdb->update(
                $wpdb->prefix . $cct_type,
                $data,
                array( $unique_field => $unique_value )
            );
        } else {
            // Insertar el nuevo item en el CCT
            $wpdb->insert(
                $wpdb->prefix . $cct_type,
                $data
            );
        }
    }

    delete_transient( $transient_key ); // Eliminar los datos del transitorio después de la importación

    // Redirigir a la página del plugin con un mensaje de éxito
    wp_redirect(admin_url('admin.php?page=csv-cct-importador&import_success=1'));
    exit;
}
?>
