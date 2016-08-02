<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://webcodesigner.com/
 * @since             1.0.0
 * @package           Posts_Grid
 *
 * @wordpress-plugin
 * Plugin Name:       Posts Grid Shortcode
 * Plugin URI:        http://webcodesigner.com/project/posts-grid-shortcode/
 * Description:       Displays your websites posts in a grid using the [posts-grid] shortcode
 * Version:           1.0.0
 * Author:            Cristian Ionel
 * Author URI:        http://webcodesigner.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       posts-grid
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-posts-grid-activator.php
 */
function activate_posts_grid() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-posts-grid-activator.php';
	Posts_Grid_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-posts-grid-deactivator.php
 */
function deactivate_posts_grid() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-posts-grid-deactivator.php';
	Posts_Grid_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_posts_grid' );
register_deactivation_hook( __FILE__, 'deactivate_posts_grid' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-posts-grid.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_posts_grid() {

	$plugin = new Posts_Grid();
	$plugin->run();

}
run_posts_grid();
