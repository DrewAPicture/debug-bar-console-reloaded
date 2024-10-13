<?php
/**
 * Console panel registration class
 *
 * @package DebugBarConsoleReloaded
 *
 * @copyright Copyright (c) 2024, Drew Jaynes
 * @copyright Copyright (c) 2011-2024, Daryl Koopersmith
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

namespace WW\DebugBarConsoleReloaded;

use DebugBarConsoleReloaded;
use WW\DebugBarConsoleReloaded\Helpers\AssetsHelper;

/**
 * Registers the panel for Debug Bar.
 *
 * @since 1.0
 */
class Panel extends \Debug_Bar_Panel
{
	/**
	 * {@inheritDoc}
	 */
	function init() {
		$this->title(__('Console', 'debug-bar-console-reloaded'));

		(new PanelAjax())->init();
	}

	/**
	 * {@inheritDoc}
	 */
	function prerender() {
		$this->set_visible(true);
	}

	/**
	 * {@inheritDoc}
	 */
	function render() {
		$modes = [
			'php' => __('PHP', 'debug-bar-console-reloaded'),
			'sql' => __('SQL', 'debug-bar-console-reloaded'),
		];

		$mode = 'php';
		$iframeCss = AssetsHelper::getStyleUrl('assets/css/iframe.css');
		?>
		<form id="debug-bar-console" class="debug-bar-console-mode-<?php echo esc_attr($mode); ?>">
		<input id="debug-bar-console-iframe-css" type="hidden" value="<?php echo esc_attr($iframeCss); ?>" />
		<?php wp_nonce_field('Debug_Bar_Console_Reloaded', '_wpnonce_debug_bar_console'); ?>
		<div id="debug-bar-console-wrap">
			<ul class="debug-bar-console-tabs">
				<?php foreach ( $modes as $slug => $title ):
					$classes = 'debug-bar-console-tab';
					if ( $slug == $mode ) {
						$classes .= ' debug-bar-console-tab-active';
					}
					?>
					<li class="<?php echo esc_attr($classes); ?>">
						<?php echo esc_html($title); ?>
						<input type="hidden" value="<?php echo esc_attr($slug); ?>" />
					</li>
				<?php endforeach; ?>
			</ul>
			<div id="debug-bar-console-submit">
				<span><?php esc_html_e('Shift + Enter', 'debug-bar-console-reloaded'); ?></span>
				<a href="#"><?php echo esc_html_x('Run', 'Run the program', 'debug-bar-console-reloaded'); ?></a>
			</div>
			<div class="debug-bar-console-panel debug-bar-console-on-php">
				<label for="debug-bar-console-input-php" class="screen-reader-text"><?php esc_html_e('Enter PHP code to execute.', 'debug-bar-console-reloaded'); ?></label>
				<textarea id="debug-bar-console-input-php" class="debug-bar-console-input"><?php echo "<?php\n"; ?></textarea>
			</div>
			<div class="debug-bar-console-panel debug-bar-console-on-sql">
				<label for="debug-bar-console-input-sql" class="screen-reader-text"><?php esc_html_e('Enter SQL to execute.', 'debug-bar-console-reloaded'); ?></label>
				<textarea id="debug-bar-console-input-sql" class="debug-bar-console-input"></textarea>
			</div>
		</div>
		<div id="debug-bar-console-output">
			<ul class="debug-bar-console-tabs">
				<li class="debug-bar-console-tab debug-bar-console-tab-active" data-output-mode="formatted"><?php echo esc_html_x('Formatted', 'Formatted output', 'debug-bar-console-reloaded'); ?></li>
				<li class="debug-bar-console-tab" data-output-mode="raw"><?php echo esc_html_x('Raw', 'Raw output', 'debug-bar-console-reloaded'); ?></li>
			</ul>
			<div class="debug-bar-console-panel">
				<iframe title="<?php esc_attr_e('Output', 'debug-bar-console-reloaded'); ?>"></iframe>
			</div>
		</div>
		</form>
		<?php
	}
}

