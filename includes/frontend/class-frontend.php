<?php
/**
 * Frontend manager.
 *
 * @package TradeSphareAds
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles frontend advertisement functionality.
 */
class TSA_Frontend {

	/**
	 * Register frontend hooks.
	 *
	 * @return void
	 */
	public static function init() {

	/*
	 * Register frontend shortcodes.
	 */
	TSA_Shortcodes::init();

	/*
	 * Register sidebar advertisement widget.
	 */
	TSA_Sidebar::init();
}
}