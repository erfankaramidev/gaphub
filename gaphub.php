<?php

/**
 * Gaphub
 * 
 * @author Erfan Karami <erfankaramidev@proton.me>
 * @copyright 2025 Erfan Karami
 * @license GPL-2.0-or-later
 * 
 * @wordpress-plugin
 * Plugin Name:  Gaphub
 * Description:  Enhance the WordPress comments with Gaphub - a plugin that adds styling. Fully customizable, lightweight, and responsive.
 * Requires PHP: 7.4
 * Text Domain:  gaphub
 * Domain Path:  /languages
 * Version:      1.0.0
 * Author:       Erfan Karami
 * Author URI:   https://erfankarami.dev
 * License:      GPL-2.0-or-later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin Constants
define( 'GH_VERSION', '1.0.0' );
define( 'GH_DB_VERSION', '1.0' );
define( 'GH_PATH', plugin_dir_path( __FILE__ ) );
define( 'GH_URL', plugin_dir_url( __FILE__ ) );

// Check for basic requirements.
if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
	add_action( 'admin_notices', function () {
		echo '<div class="notice notice-error"><p>';
		printf(
			/* translators: %s: Current PHP version */
			esc_html__( 'Gaphub plugin requires PHP 7.4 or higher. You are running %s.', 'gaphub' ),
			esc_html( PHP_VERSION )
		);
		echo '</p></div>';
	} );

	return;
}

// Ensure Composer autoloader is loaded.
if ( ! file_exists( GH_PATH . 'vendor/autoload.php' ) ) {
	add_action( 'admin_notices', function () {
		echo '<div class="notice notice-error"><p>';
		echo esc_html__( 'Gaphub plugin requires Composer autoloader. Please run composer install.', 'gaphub' );
		echo '</p></div>';
	} );

	return;
}
require_once GH_PATH . 'vendor/autoload.php';

// Initialize the plugin.
function gh_run_plugin() {
	return \Gaphub\Plugin::instance();
}
add_action( 'plugins_loaded', 'gh_run_plugin' );
