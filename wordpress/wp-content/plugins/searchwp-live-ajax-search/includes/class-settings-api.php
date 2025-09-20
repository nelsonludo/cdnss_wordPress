<?php

use SearchWP_Live_Search_Utils as Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Settings_Api.
 *
 * The SearchWP Live Ajax Search settings API.
 *
 * @since 1.7.0
 */
class SearchWP_Live_Search_Settings_Api {

	/**
	 * WP option name to save settings.
	 *
	 * @since 1.7.0
	 */
	const OPTION_NAME = 'searchwp_live_search_settings';

	/**
	 * Capability requirement for managing settings.
	 *
	 * @since 1.7.0
	 */
	const CAPABILITY = 'manage_options';

	/**
	 * Stores the settings.
	 *
	 * @since 1.8.0
	 *
	 * @var array Settings.
	 */
	private $settings = [];

	/**
	 * Hooks.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function hooks() {

		add_action( 'wp_ajax_' . utils::SEARCHWP_LIVE_SEARCH_PREFIX . 'save_settings',  [ $this, 'save_settings_ajax' ] );
	}

	/**
	 * Save settings AJAX callback.
	 *
	 * @since 1.8.0
	 */
	public function save_settings_ajax() {

		Utils::check_ajax_permissions();

		if ( ! isset( $_POST['settings'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			wp_send_json_error();
		}

		$settings = json_decode( sanitize_text_field( wp_unslash( $_POST['settings'] ) ), true ); // phpcs:ignore WordPress.Security.NonceVerification

		$this->set( $settings );

		wp_send_json_success();
	}

	/**
	 * Validate settings array against expected values.
	 *
	 * @since 1.8.0
	 *
	 * @param array $dirty_settings Settings to validate.
	 *
	 * @return array
	 */
	public function validate( $dirty_settings ) {

		return [
			// General settings.
			'enable-live-search'               => self::validate_setting( 'enable-live-search', $dirty_settings, 'bool', true ),
			'include-frontend-css'             => self::validate_setting( 'include-frontend-css', $dirty_settings, 'sanitize_key', 'all' ),
			'results-pane-position'            => self::validate_setting( 'results-pane-position', $dirty_settings, 'sanitize_key', 'bottom' ),
			'results-pane-auto-width'          => self::validate_setting( 'results-pane-auto-width', $dirty_settings, 'bool', true ),
			'hide-announcements'               => self::validate_setting( 'hide-announcements', $dirty_settings, 'bool', false ),
			// Theme settings.
			'swp-layout-theme'                 => self::validate_setting( 'swp-layout-theme', $dirty_settings, 'sanitize_key', 'minimal' ),
			'swp-image-size'                   => self::validate_setting( 'swp-image-size', $dirty_settings, 'sanitize_key', 'none' ),
			'swp-no-results-message'           => self::validate_setting( 'swp-no-results-message', $dirty_settings, 'sanitize_text_field', 'No results found.' ),
			'swp-description-enabled'          => self::validate_setting( 'swp-description-enabled', $dirty_settings, 'bool', false ),
			'swp-results-per-page'             => self::validate_setting( 'swp-results-per-page', $dirty_settings, 'intval', 7 ),
			'swp-min-chars'                    => self::validate_setting( 'swp-min-chars', $dirty_settings, 'absint', 3 ),
			'swp-title-color'                  => self::validate_setting( 'swp-title-color', $dirty_settings, 'sanitize_hex_color', '' ),
			'swp-title-font-size'              => self::validate_setting( 'swp-title-font-size', $dirty_settings, 'absint', 16 ),
			// eCommerce settings.
			'swp-price-enabled'                => self::validate_setting( 'swp-price-enabled', $dirty_settings, 'bool', false ),
			'swp-price-color'                  => self::validate_setting( 'swp-price-color', $dirty_settings, 'sanitize_hex_color', '' ),
			'swp-price-font-size'              => self::validate_setting( 'swp-price-font-size', $dirty_settings, 'absint', 14 ),
			'swp-add-to-cart-enabled'          => self::validate_setting( 'swp-add-to-cart-enabled', $dirty_settings, 'bool', false ),
			'swp-add-to-cart-background-color' => self::validate_setting( 'swp-add-to-cart-background-color', $dirty_settings, 'sanitize_hex_color', '' ),
			'swp-add-to-cart-font-color'       => self::validate_setting( 'swp-add-to-cart-font-color', $dirty_settings, 'sanitize_hex_color', '' ),
			'swp-add-to-cart-font-size'        => self::validate_setting( 'swp-add-to-cart-font-size', $dirty_settings, 'absint', 14 ),
		];
	}

	/**
	 * Validate a single setting.
	 *
	 * @since 1.8.0
	 *
	 * @param string $setting_key           The setting key.
	 * @param array  $settings              The settings array.
	 * @param string $sanitization_function The sanitization function to use.
	 * @param mixed  $default_value         The default value.
	 *
	 * @return mixed|null
	 */
	private static function validate_setting( $setting_key, $settings, $sanitization_function, $default_value = null ) {

		if ( isset( $settings[ $setting_key ] ) ) {
			if ( $sanitization_function === 'bool' ) {
				return filter_var( $settings[ $setting_key ], FILTER_VALIDATE_BOOLEAN );
			}

			return call_user_func( $sanitization_function, $settings[ $setting_key ] );
		}

		return $default_value;
	}

	/**
	 * Getter for settings.
	 *
	 * @since 1.8.0
	 *
	 * @param string $key Settings key.
	 *
	 * @return array
	 */
	public function get( $key = null ) {

		if ( empty( $this->settings ) ) {
			$this->settings = $this->validate( $this->get_option() );
		}

		if ( ! empty( $key ) ) {
			return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : null;
		}

		return $this->settings;
	}

	/**
	 * Setter for settings.
	 *
	 * @since 1.8.0
	 *
	 * @param array $settings Settings to set.
	 *
	 * @return array
	 */
	public function set( $settings ) {

		$this->settings = $this->validate( $settings );
		$this->set_option( $this->settings );

		return $this->settings;
	}

	/**
	 * Get settings option.
	 *
	 * @since 1.8.0
	 *
	 * @return mixed Option value.
	 */
	public function get_option() {

		return get_option( self::OPTION_NAME );
	}

	/**
	 * Set settings option.
	 *
	 * @since 1.8.0
	 *
	 * @param mixed $value Option value.
	 *
	 * @return bool
	 */
	private function set_option( $value ) {

		return update_option( self::OPTION_NAME, $value );
	}

	/**
	 * Get settings capability.
	 *
	 * @since 1.8.0
	 *
	 * @return string
	 */
	public static function get_capability() {

		/**
		 * Filter the capability required to manage settings.
		 *
		 * @since 1.8.0
		 *
		 * @param string $capability Capability.
		 */
		return (string) apply_filters( 'searchwp_live_search_settings_capability', self::CAPABILITY );
	}
}
