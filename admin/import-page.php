<div class="wrap csv-cct-importador-wrapper">
    <h1>Importar Items CCT</h1>
    <?php if (isset($_GET['import_success']) && $_GET['import_success'] == 1) : ?>
        <div class="notice notice-success is-dismissible">
            <p>Importación completada con éxito.</p>
        </div>
    <?php endif; ?>
    <form id="csv-import-form" method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
        <input type="hidden" name="action" value="import_csv">
        <div class="box-container-csv-import">
		<h3><span class="step-csv-import">1</span>Seleccione el Archivo</h3>
		<label class="custom-file-label">
            <input type="file" name="csv_file" class="custom-file-input" accept=".csv" required="">
            <span class="custom-file-text">Seleccionar archivo</span>
        </label>
		</div>
		<div class="box-container-csv-import">
		<h3><span class="step-csv-import">2</span>Seleccione el CCT</h3>
		<select name="cct_type" required="">
            <option value="">Selecciona el CCT</option>
            <?php
            // Obtener los CCTs
            $cct_types = csv_cct_importador_get_cct_types();
            foreach ( $cct_types as $cct ) {
                echo '<option value="' . esc_attr( $cct ) . '">' . esc_html( $cct ) . '</option>';
            }
            ?>
        </select>
		</div>
		<div class="box-container-csv-import">
		<h3><span class="step-csv-import">3</span>Importe los Datos</h3>
			<input type="submit" name="submit_csv" class="boton-import-csv" value="Importar">
		</div>
    </form>
</div>

<script>
document.querySelector('.custom-file-input').addEventListener('change', function(event) {
    var fileName = event.target.files[0].name;
    var nextSibling = event.target.nextElementSibling;
    nextSibling.innerText = fileName;
});
</script>
