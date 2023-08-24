<?php
// Add plugin settings page
// Add plugin settings page
function jm_autocomplete_plugin_settings_page() {
    add_menu_page(
        'Hai Plugin',                // Judul pada menu utama
        'Hai Plugin',                // Judul pada menu utama
        'manage_options',            // Capability yang dibutuhkan untuk mengakses menu
        'hai_plugin',                // Slug menu utama
        'hai_plugin_settings_page_content', // Callback function untuk halaman konten
        'dashicons-location-alt',    // Ikona menu (Anda dapat mengganti dengan ikon lain)
        45                           // Urutan di dalam menu
    );

    add_submenu_page(
        'hai_plugin',    // Slug menu utama ('jm_autocomplete_plugin' dari menu utama di atas)
        'Auto Complete Address',      // Judul submenu
        'Auto Complete Address',      // Judul submenu
        'manage_options',            // Capability yang dibutuhkan untuk mengakses submenu
        'jm_autocomplete_plugin', // Slug submenu
        'jm_autocomplete_plugin_settings_page_content' // Callback function untuk halaman konten submenu
    );
}

add_action('admin_menu', 'jm_autocomplete_plugin_settings_page');

// Render settings page content
function hai_plugin_settings_page_content() {
    ?>
    <div class="wrap">
        <h2>Dashboard Hai Creative Plugin</h2>        
    </div>
    <?php
}

// Render settings page content
function jm_autocomplete_plugin_settings_page_content() {
    ?>
    <div class="wrap">
        <h2>AutoComplete Address Plugin Settings</h2>        
    </div>
    <?php
}