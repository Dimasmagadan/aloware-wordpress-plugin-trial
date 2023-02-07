<?php
/**
 * Plugin Name: Aloware Trial 
 * Description: A trial plugin for Aloware.
 * Version: 1.0.0
 * Author: odminstudios
 * License: GPL2
 */

/**
 *
 * @package Aloware
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define the main plugin class.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-aloware-plugin.php';

/**
 * Instantiate the main plugin class.
 */
$aloware_plugin = Aloware_Plugin::get_instance();
