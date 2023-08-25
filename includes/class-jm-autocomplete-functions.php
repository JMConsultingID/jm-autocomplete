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
        <form method="post" action="options.php">
            <?php
            settings_fields('jm_autocomplete_plugin_settings');
            do_settings_sections('jm_autocomplete_plugin_settings');
            submit_button();
            ?>
        </form>       
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
        'jm_autocomplete_plugin_form_field',
        'Select WPForm',
        'jm_autocomplete_plugin_form_field_callback',
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
        'jm_autocomplete_plugin_form_field'
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

// Render Form WP Form
function jm_autocomplete_plugin_form_field_callback() {
    $form_field = get_option('jm_autocomplete_plugin_form_field');
    echo '<div class="pickup-fields">';
    echo '<select name="jm_autocomplete_plugin_form_field">';
        $forms = wpforms()->form->get('', array('number' => -1));
        $selected_form_id = get_option('selected_wpform_id', '');
        foreach ($forms as $form) {
            echo '<option value="' . esc_attr($form->ID) . '" ' . selected($selected_form_id, $form->ID, false) . '>' . esc_html($form->post_title) . '</option>';
        }
    echo '</select>';  
}

// Render pickup field
function jm_autocomplete_plugin_pickup_field_callback() {
    $pickup_field = get_option('jm_autocomplete_plugin_pickup_field');
    echo '<div class="pickup-fields">';
    echo '<select name="jm_autocomplete_plugin_pickup_field">';
        $form_id = get_option('jm_autocomplete_plugin_form_field');
        $form = wpforms()->form->get($form_id);
        if ($form) {
            $fields = wpforms_decode($form->post_content);
            foreach ($fields['fields'] as $field) {
                echo '<option value="' . esc_attr($field['id']) . '" ' . selected($pickup_field, $field['id'], false) . '>' . esc_html($field['label']) . '</option>';
            }
        }
    echo '</select>';
}

// Render pickup field
function jm_autocomplete_plugin_destination_field_callback() {
    $destination_field = get_option('jm_autocomplete_plugin_destination_field');
    echo '<div class="pickup-fields">';
    echo '<select name="jm_autocomplete_plugin_destination_field">';
        $form_id = get_option('jm_autocomplete_plugin_form_field');
        $form = wpforms()->form->get($form_id);
        if ($form) {
            $fields = wpforms_decode($form->post_content);
            foreach ($fields['fields'] as $field) {
                echo '<option value="' . esc_attr($field['id']) . '" ' . selected($destination_field, $field['id'], false) . '>' . esc_html($field['label']) . '</option>';
            }
        }
    echo '</select>';
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

function add_hidden_fields_to_wpforms($form_data) {
 
    // Check if the form ID is 461
    if (absint($form_data['id']) !== 461) {
        return;
    }
 
    // Add the hidden fields
    echo '
    <label id="error-message" style="color: red; display: none;">Destination city must be different from the pickup city!</label>
    <input type="hidden" id="wpforms-461-field_4-city">
    <input type="hidden" id="wpforms-461-field_4-state">
    <input type="hidden" id="wpforms-461-field_4-zip">

    <input type="hidden" id="wpforms-461-field_5-city">
    <input type="hidden" id="wpforms-461-field_5-state">
    <input type="hidden" id="wpforms-461-field_5-zip">
    ';
}
 
add_action('wpforms_frontend_output', 'add_hidden_fields_to_wpforms', 10, 1);

function add_autocomplete_results_to_wpforms($form_data) {
 
    // Check if the form ID is 461
    if (absint($form_data['id']) !== 461) {
        return;
    }
 
    // Add the autocomplete results div after the Pickup input
    ?>
     <style>
    .mapboxgl-ctrl-geocoder {
        width: 100%;
        border-radius: 4px;
    }
    .autocomplete-results {
        border: 1px solid #ccc;
        max-height: 150px;
        overflow-y: auto;
        position: absolute;
        width: 100%;
        z-index: 1000;
        display: none;
    }

    .autocomplete-results div {
        padding: 5px;
        cursor: pointer;
    }

    .autocomplete-results div:hover {
        background-color: #f0f0f0;
    }

</style>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            var pickupInput = document.getElementById('wpforms-461-field_4');
            var destinationInput = document.getElementById('wpforms-461-field_5');
            if (pickupInput) {
                var resultsDiv = document.createElement('div');
                resultsDiv.id = 'pickup-results';
                resultsDiv.className = 'autocomplete-results';
                pickupInput.parentNode.insertBefore(resultsDiv, pickupInput.nextSibling);
            }
            if (destinationInput) {
                var resultsDiv = document.createElement('div');
                resultsDiv.id = 'destination-results';
                resultsDiv.className = 'autocomplete-results';
                destinationInput.parentNode.insertBefore(resultsDiv, destinationInput.nextSibling);
            }
        });
    </script>
    <?php
}
 
add_action('wpforms_frontend_output', 'add_autocomplete_results_to_wpforms', 10, 1);


function enqueue_autocomplete_address_plugin_assets() {
        // Enqueue styles
        wp_enqueue_style('mapbox-gl', 'https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.css');
        wp_enqueue_style('mapbox-gl-geocoder', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.css');

        // Enqueue scripts
        wp_enqueue_script('jquery');
        wp_enqueue_script('mapbox-gl', 'https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js', array(), null, true);
        wp_enqueue_script('mapbox-gl-geocoder', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.7.0/mapbox-gl-geocoder.min.js', array('mapbox-gl'), null, true);
        wp_enqueue_script('autocomplete-address-plugin-script', plugin_dir_url(__FILE__) . '../public/js/jm-autocomplete-main.js', array('jquery', 'mapbox-gl', 'mapbox-gl-geocoder'), null, true);
        // Pass the API key to JavaScript
        $mapbox_api_key = get_option('jm_autocomplete_plugin_mapbox_api_key');
        wp_localize_script('jm-autocomplete-main', 'jmAutocompleteData', array(
            'mapboxApiKey' => $mapbox_api_key
        ));
    }
add_action('wp_enqueue_scripts', 'enqueue_autocomplete_address_plugin_assets', 1000);

function add_inline_script() {
    $mapbox_api_key = get_option('jm_autocomplete_plugin_mapbox_api_key');
    echo "<script>window.jmAutocompleteData = { mapboxApiKey: '" . esc_js($mapbox_api_key) . "' };</script>";
}
add_action('wp_footer', 'add_inline_script', 1); // Prioritas 1 untuk memastikannya dimuat sebelum script lainnya
