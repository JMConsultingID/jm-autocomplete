<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://haicreative.com
 * @since             1.0.0
 * @package           Jm_Autocomplete
 *
 * @wordpress-plugin
 * Plugin Name:       AutoComplete Address - WPForm Add on
 * Plugin URI:        https://haicreative.com
 * Description:       This Plugin WP-Form Add on to Create Field with Autocomplete Address with API Google Maps or Mapbox.
 * Version:           1.0.0
 * Author:            Ardika JM Consulting
 * Author URI:        https://haicreative.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       jm-autocomplete
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'JM_AUTOCOMPLETE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-jm-autocomplete-activator.php
 */
function activate_jm_autocomplete() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jm-autocomplete-activator.php';
	Jm_Autocomplete_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-jm-autocomplete-deactivator.php
 */
function deactivate_jm_autocomplete() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jm-autocomplete-deactivator.php';
	Jm_Autocomplete_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_jm_autocomplete' );
register_deactivation_hook( __FILE__, 'deactivate_jm_autocomplete' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-jm-autocomplete.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-jm-autocomplete-functions.php';

function filter_action_jm_autocomplete_links( $links ) {
     $links['settings'] = '<a href="#">' . __( 'Settings', 'jm-autocomplete' ) . '</a>';
     $links['support'] = '<a href="#">' . __( 'Doc', 'jm-autocomplete' ) . '</a>';
     return $links;
}
add_filter( 'plugin_action_links_jm-autocomplete/jm-autocomplete.php', 'filter_action_jm_autocomplete_links', 10, 1 );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_jm_autocomplete() {

	$plugin = new Jm_Autocomplete();
	$plugin->run();

}
run_jm_autocomplete();
