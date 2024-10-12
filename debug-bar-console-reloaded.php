<?php
/**
 * Plugin Name: Debug Bar Console Reloaded
 * Plugin URI: http://wordpress.org/extend/plugins/debug-bar-console-reloaded/
 * Description: Adds a PHP/SQL console to the debug bar. Requires the debug bar plugin.
 * Author: Drew Jaynes
 * Author URI: https://werdswords.com
 * Version: 1.0.0
 * License: GPLv2
 * Requires PHP: 7.4
 * Text Domain: debug-bar-console-reloaded
 * Domain Path: /languages/
 */

use WW\DebugBarConsoleReloaded\Integration;

/**
 * Main plugin class.
 *
 * @since 1.0.0
 */
class DebugBarConsoleReloaded
{
	const VERSION = '1.0.0';
	const FILE = __FILE__;

	/**
	 * Initializes the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init() : void
	{
		require_once __DIR__ . '/vendor/autoload.php';

		(new Integration())->start();
	}
}

add_action('init', fn() => (new DebugBarConsoleReloaded())->init());
