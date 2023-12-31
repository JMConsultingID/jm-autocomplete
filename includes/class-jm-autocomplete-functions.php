<?php
// Add plugin settings page
function jm_autocomplete_plugin_settings_page() {
    add_submenu_page(
        'wpforms',
        'AutoComplete Address',
        'AutoComplete Address',
        'manage_options',
        'jm_autocomplete_plugin',
        'jm_autocomplete_plugin_settings_page_content'
    );
}
add_action('admin_menu', 'jm_autocomplete_plugin_settings_page');

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
        'jm_autocomplete_plugin_sandbox_endpoint_url',
        'Sandbox Endpoint URL',
        'jm_autocomplete_plugin_sandbox_endpoint_url_callback',
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_general'
    );

    add_settings_field(
        'jm_autocomplete_plugin_sandbox_test_key',
        'Sandbox Test Key',
        'jm_autocomplete_plugin_sandbox_test_key_callback',
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_general'
    );

    add_settings_field(
        'jm_autocomplete_plugin_endpoint_url',
        'Live Endpoint URL',
        'jm_autocomplete_plugin_endpoint_url_callback',
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_general'
    );

    add_settings_field(
        'jm_autocomplete_plugin_api_key',
        'Live API Key',
        'jm_autocomplete_plugin_api_key_callback',
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_general'
    );

    add_settings_field(
        'jm_autocomplete_plugin_checkout_form',
        'Checkout Form',
        'jm_autocomplete_plugin_checkout_form_callback',
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_general'
    );    

    add_settings_field(
        'jm_autocomplete_plugin_sellkit_option',
        'SellKit Option',
        'jm_autocomplete_plugin_sellkit_option_callback',
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_general'
    );

    add_settings_field(
        'jm_autocomplete_plugin_mt_version_field',
        'Enable MT Version Field',
        'jm_autocomplete_plugin_mt_version_field_callback',
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_general'
    );

    add_settings_field(
        'jm_autocomplete_plugin_request_method',
        'Request Method',
        'jm_autocomplete_plugin_request_method_callback',
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
        'jm_autocomplete_plugin_sandbox_endpoint_url'
    );

    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_sandbox_test_key'
    );


    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_endpoint_url'
    );

    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_api_key'
    );

    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_checkout_form',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'woocommerce_form'
        )
    );    

    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_sellkit_option',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'sellkit_billing'
        )
    );

    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_mt_version_field',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'disable'
        )
    );

    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_request_method',
        array(
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'wp_remote_post'
        )
    );

    register_setting(
        'jm_autocomplete_plugin_settings',
        'jm_autocomplete_plugin_enable_response_header'
    );
}
add_action('admin_init', 'jm_autocomplete_plugin_settings_fields');