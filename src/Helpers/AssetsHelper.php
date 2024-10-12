<?php
/**
 * Assets Helpers
 *
 * @since 1.0.0
 *
 * @package DebugBarConsoleReloaded/Helpers
 */
namespace WW\DebugBarConsoleReloaded\Helpers;

/**
 * Assets helper class.
 *
 * @since 1.0.0
 */
class AssetsHelper
{
	/**
	 * Gets a stylesheet URL relative to the main plugin file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $assetPath Relative stylesheet path.
	 * @param bool $allowDev Optional. Whether to allow returning development asset URLs.
	 *                       Default true.
	 * @return string
	 */
	public static function getStyleUrl(string $assetPath, bool $allowDev = true) : string
	{
		return self::getAssetUrl($assetPath, 'stylesheet', $allowDev);
	}

	/**
	 * Gets a script URL relative to the main plugin file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $assetPath Relative script path.
	 * @param bool $allowDev Optional. Whether to allow returning development asset URLs.
	 *                       Default true.
	 * @return string
	 */
	public static function getScriptUrl(string $assetPath, bool $allowDev = true) : string
	{
		return self::getAssetUrl($assetPath, 'script', $allowDev);
	}

	/**
	 * Gets a SCRIPT_DEBUG-aware asset URL.
	 *
	 * @since 1.0.0
	 *
	 * @param string $assetPath Relative asset path.
	 * @param string $type Asset type. Accepts 'stylesheet' or 'script'.
	 * @param bool $allowDev Whether to allow returning development asset URLs.
	 * @return string
	 */
	protected static function getAssetUrl(string $assetPath, string $type, bool $allowDev = true) : string
	{
		$url = '';

		switch($type) {
			case 'stylesheet':
				$extension = '.css';
				$offset = -8;
				break;
			case 'script':
				$extension = '.js';
				$offset = -7;
				break;
			default:
				return $url;
		}

		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.dev' : '';

		if (true === $allowDev && $suffix && false === strpos($assetPath, "{$suffix}{$extension}", $offset)) {
			$assetPath = str_replace($extension, "{$suffix}{$extension}", $assetPath);
		}

		return plugins_url($assetPath, \DebugBarConsoleReloaded::FILE);
	}
}
