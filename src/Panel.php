<?php
/**
 * Console panel registration class
 */

namespace WW\DebugBarConsoleReloaded;

use DebugBarConsoleReloaded;

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

		add_action('wp_ajax_debug_bar_console', [&$this, 'ajaxCallback']);
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
		$modes = array(
			'php' => __('PHP', 'debug-bar-console-reloaded'),
			'sql' => __('SQL', 'debug-bar-console-reloaded'),
		);

		$mode = 'php';
		?>
		<form id="debug-bar-console" class="debug-bar-console-mode-<?php echo esc_attr($mode); ?>">
		<input id="debug-bar-console-iframe-css" type="hidden" value="<?php echo plugins_url('assets/css/iframe.dev.css', DebugBarConsoleReloaded::FILE); ?>" />
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
				<span><?php _e('Shift + Enter', 'debug-bar-console-reloaded'); ?></span>
				<a href="#"><?php esc_html_e('Run', 'debug-bar-console-reloaded'); ?></a>
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
				<li class="debug-bar-console-tab debug-bar-console-tab-active" data-output-mode="formatted"><?php esc_html_e('Formatted', 'debug-bar-console-reloaded'); ?></li>
				<li class="debug-bar-console-tab" data-output-mode="raw"><?php esc_html_e('Raw', 'debug-bar-console-reloaded'); ?></li>
			</ul>
			<div class="debug-bar-console-panel">
				<iframe title="<?php esc_attr_e('Output', 'debug-bar-console-reloaded'); ?>"></iframe>
			</div>
		</div>
		</form>
		<?php
	}

	/**
	 * Ajax callback for the panel.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function ajaxCallback() {
		global $wpdb;

		if ( false === check_ajax_referer('Debug_Bar_Console_Reloaded', 'nonce', false)) {
			die();
		}

		if (!is_super_admin()) {
			die();
		}

		$data = stripslashes($_REQUEST['data'] ?? '');
		$mode = $_REQUEST['mode'] ?? 'php';


		if ( 'php' === $mode ) {
			// Trim the data
			$data = '?>' . trim($data);

			// Do we end the string in PHP?
			$open  = strrpos($data, '<?php');
			$close = strrpos($data, '?>');

			// If we're still in PHP, ensure we end with a semicolon.
			if ($open > $close) {
				$data = rtrim($data, ';') . ';';
			}

			eval($data);
			die();

		} elseif ('sql' === $mode) {
			$data = explode(";\n", $data);
			foreach ($data as $query ) {
				$query = str_replace('$wpdb->', $wpdb->prefix, $query);
				$this->printMysqlTable($wpdb->get_results($query, ARRAY_A), $query);
			}
			die();
		}
	}

	/**
	 * Prints the MySQL table.
	 *
	 * @param array<mixed> $data Found rows.
	 * @param string $query Optional. Query. Default empty string.
	 * @return void
	 */
	function printMysqlTable( $data, $query = '')
	{
		if ( empty($data) )
			return;

		$keys = array_keys( $data[0] );

		?>
		<table class="mysql" cellpadding="0">
			<thead>
				<tr class="query">
					<td colspan="<?php echo esc_attr(count($keys)); ?>"><?php echo $query; ?></td>
				</tr>
				<tr>
					<?php foreach ($keys as $key): ?>
					<th class="<?php echo esc_attr($key); ?>"><?php echo esc_html($key); ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $row): ?>
				<tr>
					<?php foreach ($row as $key => $value): ?>
					<td class="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></td>
					<?php endforeach; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		</div>
		<?php
	}
}

