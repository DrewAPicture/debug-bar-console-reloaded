<?php

namespace WW\DebugBarConsoleReloaded;

use \Debug_Bar_Panel;

/**
 * Sets up the integration with Debug Bar.
 *
 * @since 1.0.0
 */
class Integration
{
	/**
	 * Starts the Debug Bar integration.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function start()
	{
		add_filter('debug_bar_panels', [$this, 'registerPanel']);
		add_action('debug_bar_enqueue_scripts', [$this, 'enqueueScripts']);
	}

	/**
	 * Registers the panel.
	 *
	 * @param array<string, Debug_Bar_Panel> $panels
	 * @return array
	 */
	public function registerPanel($panels)
	{
		$panels[] = new Panel();
		return $panels;
	}

	/**
	 * Enqueues scripts and styles.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueueScripts()
	{
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.dev' : '';
		$basePath = "src/assets/codemirror";

		// Codemirror
		wp_enqueue_style( 'debug-bar-console-reloaded-cm', plugins_url( "{$basePath}/lib/codemirror.css", __FILE__ ), array(), '2.22' );
		wp_enqueue_script( 'debug-bar-console-reloaded-cm', plugins_url( "{$basePath}/debug-bar-codemirror.js", __FILE__ ), array(), '2.22' );

		wp_enqueue_style( 'debug-bar-console-reloaded', plugins_url( "../assets/css/debug-bar-console-reloaded$suffix.css", __FILE__ ), array( 'debug-bar', 'debug-bar-console-reloaded-cm' ), '20241011' );
		wp_enqueue_script( 'debug-bar-console-reloaded', plugins_url( "../assets/js/debug-bar-console-reloaded$suffix.js", __FILE__ ), array( 'debug-bar', 'debug-bar-console-reloaded-cm' ), '20241011' );

	}
}
