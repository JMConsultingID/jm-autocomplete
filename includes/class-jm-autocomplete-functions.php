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
        <?php
            settings_fields('jm_autocomplete_plugin_settings');
            do_settings_sections('jm_autocomplete_plugin_settings');
            submit_button();
        ?>        
    </div>
    <?php
}

// Add plugin settings fields
function jm_autocomplete_plugin_settings_fields() {
    add_settings_section(
        'jm_autocomplete_plugin_general',
        'General Settings',
        'jm_autocomplete_plugin_general_section_callback',
        'jm_autocomplete_plugin_settings'
    );

    add_settings_field(
        'jm_autocomplete_plugin_enabled',
        'Enable Plugin',
        'jm_autocomplete_plugin_enabled_callback',
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_general'
    );

    add_settings_field(
        'jm_autocomplete_plugin_environment',
        'Environment',
        'jm_autocomplete_plugin_environment_callback',
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_general'
    );

    add_settings_field(
        'jm_autocomplete_plugin_mapbox_api_key',
        'MapBox Api Key',
        'jm_autocomplete_plugin_mapbox_api_key_callback',
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_general'
    );


    add_settings_field(
        'jm_autocomplete_plugin_google_api_key',
        'Google Maps API Key',
        'jm_autocomplete_plugin_google_api_key_callback',
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_general'
    );

    add_settings_field(
        'jm_autocomplete_plugin_pickup_field',
        'Pick Up Field',
        'jm_autocomplete_plugin_pickup_field_callback',
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_general'
    ); 

    add_settings_field(
        'jm_autocomplete_plugin_destination_field',
        'Destination Field',
        'jm_autocomplete_plugin_destination_field_callback',
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_general'
    );   

    add_settings_field(
        'jm_autocomplete_plugin_enable_response_header',
        'Display Response Header in Console Log',
        'jm_autocomplete_plugin_enable_response_header_callback',
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_general'
    );

    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_enabled',
        array(
            'sanitize_callback' => 'sanitize_text_field'
        )
    );

    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_environment',
        array(
            'sanitize_callback' => 'sanitize_text_field'
        )
    );

    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_mapbox_api_key'
    );

    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_google_api_key'
    );


    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_pickup_field'
    );

    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_destination_field'
    );


    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_enable_response_header'
    );
}
add_action('admin_init', 'jm_autocomplete_plugin_settings_fields');

// Render general settings section callback
function jm_autocomplete_plugin_general_section_callback() {
    echo 'Configure the general settings for Auto Complete Address Plugin.';
}

// Render enable plugin field
function jm_autocomplete_plugin_enabled_callback() {
    $plugin_enabled = get_option('jm_autocomplete_plugin_enabled');
    ?>
    <select name="jm_autocomplete_plugin_enabled">
        <option value="enable" <?php selected($plugin_enabled, 'enable'); ?>>Enabled</option>
        <option value="disable" <?php selected($plugin_enabled, 'disable'); ?>>Disabled</option>
    </select>
    <?php
}

// Render environment field
function jm_autocomplete_plugin_environment_callback() {
    $environment = get_option('jm_autocomplete_plugin_environment');
    if (empty($environment)) {
        $environment = 'mapbox'; // Set default value to 'sandbox'
    }
    ?>
    <label width="100px">
        <input type="radio" name="jm_autocomplete_plugin_environment" value="mapbox" <?php checked($environment, 'mapbox'); ?> />
        MapBox
    </label>
    <label width="100px">
        <input type="radio" name="jm_autocomplete_plugin_environment" value="google" <?php checked($environment, 'google'); ?> />
        Google
    </label>

    <?php
}

// Render mapbox key field
function jm_autocomplete_plugin_mapbox_api_key_callback() {
    $mapbox_key = get_option('jm_autocomplete_plugin_mapbox_api_key');
    echo '<div class="mapbox-fields">';
    echo '<input type="text" name="jm_autocomplete_plugin_mapbox_api_key" value="' . $mapbox_key. '" style="width: 400px;" />';
    echo '</div>';
}

// Render google maps key field
function jm_autocomplete_plugin_google_api_key_callback() {
    $google_key = get_option('jm_autocomplete_plugin_google_api_key');
    echo '<div class="google-fields">';
    echo '<input type="text" name="jm_autocomplete_plugin_google_api_key" value="' . $google_key. '" style="width: 400px;" />';
    echo '</div>';
}

// Render pickup field
function jm_autocomplete_plugin_pickup_field_callback() {
    $pickup_field = get_option('jm_autocomplete_plugin_pickup_field');
    echo '<div class="pickup-fields">';
    echo '<input type="text" name="jm_autocomplete_plugin_pickup_field" value="' . $pickup_field. '" style="width: 400px;" />';
    echo '</div>';
}

// Render destination field
function jm_autocomplete_plugin_destination_field_callback() {
    $destination_field = get_option('jm_autocomplete_plugin_destination_field');
    echo '<div class="destination-fields">';
    echo '<input type="text" name="jm_autocomplete_plugin_destination_field" value="' . $destination_field. '" style="width: 400px;" />';
    echo '</div>';
}


// Render enable response header field
function jm_autocomplete_plugin_enable_response_header_callback() {
    $enable_response_header = get_option('jm_autocomplete_plugin_enable_response_header');
    ?>
    <label>
        <input type="radio" name="jm_autocomplete_plugin_enable_response_header" value="1" <?php checked($enable_response_header, 1); ?> />
        Yes
    </label>
    <label>
        <input type="radio" name="jm_autocomplete_plugin_enable_response_header" value="0" <?php checked($enable_response_header, 0); ?> />
        No
    </label>
    <?php
}