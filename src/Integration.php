<?php

namespace {

	use WW\DebugBarConsoleReloaded\Panel;

	/**
	 * Globally-namespaced panel class to avoid Debug Bar's incompatibility with
	 * namespaces in panel class names.
	 *
	 * @since 1.0.0
	 */
	class Debug_Bar_Console_Reloaded_Panel extends Panel{}
}

namespace WW\DebugBarConsoleReloaded {

	use DebugBarConsoleReloaded;
	use Debug_Bar_Console_Reloaded_Panel;

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
		public function start() : void
		{
			add_filter('debug_bar_panels', [$this, 'registerPanel']);
			add_action('debug_bar_enqueue_scripts', [$this, 'enqueueScripts']);
		}

		/**
		 * Registers the panel.
		 *
		 * @param array<string, \Debug_Bar_Panel> $panels
		 *
		 * @return array
		 */
		public function registerPanel($panels)
		{
			$panels[] = new Debug_Bar_Console_Reloaded_Panel;

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
			wp_enqueue_style(
				'debug-bar-console-reloaded-cm',
				plugins_url(
					"{$basePath}/lib/codemirror.css",
					DebugBarConsoleReloaded::FILE
				),
				[],
				'2.22'
			);
			wp_enqueue_script(
				'debug-bar-console-reloaded-cm',
				plugins_url(
					"{$basePath}/debug-bar-codemirror.js",
					DebugBarConsoleReloaded::FILE
				),
				[],
				'2.22'
			);

			wp_enqueue_style(
				'debug-bar-console-reloaded',
				plugins_url(
					"assets/css/debug-bar-console-reloaded$suffix.css",
					DebugBarConsoleReloaded::FILE
				),
				['debug-bar', 'debug-bar-console-reloaded-cm'],
				'20241011'
			);
			wp_enqueue_script(
				'debug-bar-console-reloaded',
				plugins_url(
					"assets/js/debug-bar-console-reloaded$suffix.js",
					DebugBarConsoleReloaded::FILE
				),
				['debug-bar', 'debug-bar-console-reloaded-cm'],
				'20241011'
			);
		}
	}
}
