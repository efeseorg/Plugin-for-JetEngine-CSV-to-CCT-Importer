# Plugin for JetEngine: CSV to CCT Importer

## Description

The "CSV to CCT Importer" plugin allows WordPress administrators to import items from CSV files into Custom Content Types (CCTs) created with JetEngine. It provides an interface to map the CSV columns to the CCT fields and supports updating existing records based on a unique field (`_id`).

## Features

1.  **CSV File Upload:** Users can upload CSV files from the WordPress backend.
2.  **CCT Detection:** The plugin automatically detects Custom Content Types (CCTs) created with JetEngine.
3.  **Field Mapping:** Enables mapping of CSV columns to CCT fields.
4.  **Automatic Field Matching:** If CSV headers match the names of CCT fields, they are automatically selected for mapping.
5.  **Existing Record Update:** If the CSV contains an `_id` field that matches an existing `_id` in the database, the data is updated instead of creating a new record.
6.  **Success Notification:** After the import, a success notification is displayed on the plugin page.

## Required Resources

### 1. PHP Files

* `csv-a-cct-importador.php`: Main plugin file that includes necessary scripts and registers the menu.
* `admin/menu.php`: Handles the menu logic and loading of plugin pages.
* `admin/import-handler.php`: Handles CSV file upload and processing, field mapping, and data import/update into the CCT.
* `admin/import-page.php`: Contains the CSV file upload form.
* `admin/import-page-mapping.php`: Contains the field mapping form.
* `includes/cct-detection.php`: Detects Custom Content Types (CCTs) created with JetEngine.

### 2. CSS Files

* `csv-cct-importador.css`: Stylesheet for the plugin.

### 3. JavaScript Files

* Included directly in `admin/import-page.php` to handle the display of the selected file name.

## File Structure

csv-a-cct-importador/
│
├── admin/
│   ├── import-handler.php
│   ├── import-page.php
│   ├── import-page-mapping.php
│   └── menu.php
│
├── includes/
│   └── cct-detection.php
│
├── csv-cct-importador.php
└── csv-cct-importador.css

## Plugin Workflow

1.  **CSV File Upload**
    * The user uploads a CSV file from the WordPress backend via the provided form.
    * The CSV file is validated to ensure it's a valid CSV file.
2.  **CCT Detection**
    * The plugin detects available Custom Content Types (CCTs) created with JetEngine and displays them in a selector on the upload form.
3.  **Field Mapping**
    * After uploading the CSV file and selecting the CCT, the plugin processes the file and displays an interface to map the CSV headers to the CCT fields.
    * If CSV headers match the names of CCT fields, they are automatically selected. Otherwise, the user can manually select the corresponding field.
4.  **Data Import**
    * Upon clicking "Import," the plugin checks if the data already exists in the database based on the unique `_id` field.
    * If a record with the same `_id` exists, the existing data is updated with the information from the CSV.
    * If a record with the same `_id` does not exist, a new record is created in the CCT.
5.  **Success Notification**
    * After the import is complete, the user is redirected to the plugin page with a success notification.

## Additional Notes

* The plugin utilizes WordPress transients to temporarily store CSV file data and field mappings, ensuring data security during the import process.
* CSS styles ensure that the plugin interface is consistent and user-friendly.
* The included JavaScript handles the display of the selected file name in the file upload field.
