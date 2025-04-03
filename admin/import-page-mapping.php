<div class="wrap csv-cct-importador-wrapper">
    <h1>Mapear Campos del CSV</h1>
    <form id="csv-field-mapping-form" method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
        <input type="hidden" name="action" value="handle_mapping">
        <?php wp_nonce_field('csv_cct_importador_mapping_nonce', '_wpnonce_csv_cct_importador_mapping'); ?>
        <table>
            <tr>
                <th>Cabecera del CSV</th>
                <th>Campo del CCT</th>
            </tr>
            <?php
            $transient_key = 'csv_cct_import_' . get_current_user_id();
            $import_data = get_transient( $transient_key );

            if ( $import_data ) {
                $csv_headers = $import_data['csv_headers'];
                $cct_fields = $import_data['cct_fields'];

                // Añadir _id a los campos del CCT si no está presente
                if (!array_key_exists('_id', $cct_fields)) {
                    $cct_fields['_id'] = '_ID';
                }

                foreach ( $csv_headers as $header ) {
                    echo '<tr>';
                    echo '<td>' . esc_html( $header ) . '</td>';
                    echo '<td>';
                    echo '<select name="field_mapping[' . esc_attr( $header ) . ']">';
                    echo '<option value="">Selecciona el campo</option>';
                    foreach ( $cct_fields as $field_key => $field_label ) {
                        $selected = ($field_key === $header) ? 'selected' : '';
                        echo '<option value="' . esc_attr( $field_key ) . '" ' . $selected . '>' . esc_html( $field_label ) . '</option>';
                    }
                    echo '</select>';
                    echo '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="2">No se encontraron datos para el mapeo. Por favor, vuelve a cargar el archivo CSV.</td></tr>';
            }
            ?>
        </table>
        <input type="submit" name="submit_mapping" value="Importar">
    </form>
</div>
