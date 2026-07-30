<?php
/**
 * Plugin Name: Starter Plugin
 * Plugin URI: https://github.com/sks-666/0x9
 * Description: A bare custom plugin scaffold to build from. Registers a sample shortcode and demonstrates activation/deactivation hooks.
 * Version: 0.1.0
 * Author: GeometryCraft Community
 * Author URI: https://github.com/sks-666/0x9
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: starter-plugin
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'STARTER_PLUGIN_VERSION', '0.1.0' );
define( 'STARTER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Runs on activation.
 */
function starter_plugin_activate() {
	// Nothing to set up yet — placeholder for future install-time tasks.
}
register_activation_hook( __FILE__, 'starter_plugin_activate' );

/**
 * Runs on deactivation.
 */
function starter_plugin_deactivate() {
	// Placeholder for cleanup on deactivation.
}
register_deactivation_hook( __FILE__, 'starter_plugin_deactivate' );

/**
 * Sample shortcode: [starter_plugin_hello]
 */
function starter_plugin_hello_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'name' => __( 'World', 'starter-plugin' ) ), $atts, 'starter_plugin_hello' );

	return sprintf( '<p>%s</p>', esc_html( sprintf( __( 'Hello, %s!', 'starter-plugin' ), $atts['name'] ) ) );
}
add_shortcode( 'starter_plugin_hello', 'starter_plugin_hello_shortcode' );
