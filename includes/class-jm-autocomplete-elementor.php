<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://haicreative.com
 * @since      1.0.0
 *
 * @package    Jm_Autocomplete
 * @subpackage Jm_Autocomplete/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Jm_Autocomplete
 * @subpackage Jm_Autocomplete/includes
 * @author     Ardika JM Consulting <ardi@jm-consulting.id>
 */
function add_jm_autocomplete_plugin_categories( $elements_manager ) {

    $elements_manager->add_category(
        'hai-plugin-category',
        [
            'title' => esc_html__( 'Hai Plugin Widget', 'jm-autocomplete' ),
            'icon' => 'fa fa-plug',
        ]
    );
}
add_action( 'elementor/elements/categories_registered', 'add_jm_autocomplete_plugin_categories' );

function register_jm_autocomplete_plugin_widget( $widgets_manager ) {

    require_once( __DIR__ . '/widgets/jm-autocomplete-widget-1.php' );

    

    $widgets_manager->register( new \Elementor_JmAutocomplete_Maps_Widget() );
}
add_action( 'elementor/widgets/register', 'register_jm_autocomplete_plugin_widget' );