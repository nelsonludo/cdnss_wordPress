<?php

use SearchWP_Live_Search_Utils as Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_License.
 *
 * The SearchWP Live Ajax Search license handler.
 *
 * @since 1.8.0
 */
class SearchWP_Live_Search_License {

	const LICENSE_TYPES = [
		0 => 'standard',
		1 => 'pro',
		2 => 'agency',
	];

	/**
	 * Activate SearchWP PRO license.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public static function maybe_activate_license() {

		Utils::check_ajax_permissions();

		$license_key = isset( $_POST['license_key'] ) ? sanitize_text_field( wp_unslash( $_POST['license_key'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( empty( $license_key ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'License key is required.', 'searchwp-live-ajax-search' ) ] );
		}

		$error_message = esc_html__( 'An error occurred, please try again.', 'searchwp-live-ajax-search' );

		$api_params = [
			'edd_action' => 'activate_license',
			'license'    => $license_key,
			'item_name'  => rawurlencode( 'SearchWP 4' ),
			'url'        => home_url(),
		];

		$response = wp_remote_post(
			'https://searchwp.com',
			[
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			]
		);

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( [ 'message' => $error_message ] );
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $license_data === false ) {
			wp_send_json_error( [ 'message' => $error_message ] );
		}

		if ( $license_data->success === false ) {
			update_option( 'searchwp_license', false );
			wp_send_json_error( [ 'message' => $license_data->error ] );
		}

		// Activate the license.
		self::activate_license( $license_key, $license_data );

		// Install SearchWP.
		$plugin_basename = self::install_searchwp( $license_key );

		if ( empty( $plugin_basename ) ) {
			wp_send_json_error( [ 'message' => $error_message ] );
		}

		// Return early if user has no permissions to activate the plugins.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_success(
				[
					'message' => esc_html__( 'SearchWP PRO installed.', 'searchwp-live-ajax-search' ),
				]
			);
		}

		// Activate SearchWP.
		self::activate_searchwp( $plugin_basename );
	}

	/**
	 * Install SearchWP.
	 *
	 * @since 1.8.0
	 *
	 * @param array $license_key The submitted license key.
	 */
	private static function install_searchwp( $license_key ) {

		if ( ! current_user_can( 'install_plugins' ) ) {
			return false;
		}

		// Determine whether file modifications are allowed for SearchWP.
		if ( ! wp_is_file_mod_allowed( 'searchwp_can_install' ) ) {
			return false;
		}

		// Check if SearchWP is already installed but not active.
		if ( Utils::is_searchwp_installed() && ! Utils::is_searchwp_active() ) {
			self::activate_searchwp( 'searchwp/index.php' );
		}

		$error = esc_html__( 'Could not install the extension. Please download it from searchwp.com and install it manually.', 'searchwp-live-ajax-search' );

		// Install SearchWP.
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		$skin          = new \WP_Ajax_Upgrader_Skin();
		$upgrader      = new \Plugin_Upgrader( $skin );
		$download_link = self::get_download_url( $license_key );
		$result        = $upgrader->install( $download_link, [ 'overwrite_package' => true ] );

		self::ajax_process_searchwp_install_errors( $result, $skin );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		$plugin_basename = $upgrader->plugin_info();

		if ( empty( $plugin_basename ) ) {
			wp_send_json_error( $error );
		}

		return $plugin_basename;
	}

	/**
	 * Get download URL for SearchWP PRO.
	 *
	 * @since 1.8.0
	 *
	 * @param string $license_key License key.
	 */
	public static function get_download_url( $license_key ) {

		$url  = 'https://searchwp.com';
		$args = [
			'edd_action' => 'get_version',
			'item_id'    => 216029,
			'license'    => sanitize_key( $license_key ),
		];

		$response = wp_remote_get( $url, [ 'body' => $args ] );
		$body     = json_decode( wp_remote_retrieve_body( $response ), true );

		return ! empty( $body['download_link'] ) ? $body['download_link'] : '';
	}

	/**
	 * Process SearchWP PRO install errors if any.
	 *
	 * @since 1.8.0
	 *
	 * @param bool|WP_Error          $result Uprgrader install method result.
	 * @param \WP_Ajax_Upgrader_Skin $skin   AJAX upgrader skin.
	 */
	private static function ajax_process_searchwp_install_errors( $result, \WP_Ajax_Upgrader_Skin $skin ) {

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$status['debug'] = $skin->get_upgrade_messages();
		}

		if ( is_wp_error( $result ) ) {
			$status['errorCode'] = $result->get_error_code();
			$status['message']   = $result->get_error_message();

			wp_send_json_error( $status );
		} elseif ( is_wp_error( $skin->result ) ) {
			$status['errorCode'] = $skin->result->get_error_code();
			$status['message']   = $skin->result->get_error_message();

			wp_send_json_error( $status );
		} elseif ( $skin->get_errors()->has_errors() ) {
			$status['message'] = $skin->get_error_messages();

			wp_send_json_error( $status );
		} elseif ( is_null( $result ) ) {
			global $wp_filesystem;

			$status['errorCode'] = 'unable_to_connect_to_filesystem';
			$status['message']   = __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'searchwp-live-ajax-search' );

			// Pass through the error from WP_Filesystem if one was raised.
			if ( $wp_filesystem instanceof \WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors() ) {
				$status['message'] = esc_html( $wp_filesystem->errors->get_error_message() );
			}
			wp_send_json_error( $status );
		}
	}

	/**
	 * Return whether the license is active.
	 *
	 * @since 1.8.0
	 *
	 * @return bool
	 */
	public static function is_active() {

		$license = self::get_license();

		return ! empty( $license ) && ! empty( $license['status'] ) && $license['status'] === 'valid';
	}

	/**
	 * Getter for license data.
	 *
	 * @since 1.8.0
	 *
	 * @return array The license data.
	 */
	public static function get_license() {

		return get_option( 'searchwp_license', false );
	}

	/**
	 * Getter for license key.
	 *
	 * @since 1.8.0
	 *
	 * @return array The license data.
	 */
	public static function get_license_key() {

		$license = self::get_license();

		return ! empty( $license ) && ! empty( $license['key'] ) ? $license['key'] : '';
	}

	/**
	 * Activate the license.
	 *
	 * @since 1.8.0
	 *
	 * @param string $license_key  The license key.
	 * @param array  $license_data The license data.
	 */
	public static function activate_license( $license_key, $license_data ) {

		// Generate human-readable remaining time.
		if ( $license_data->expires === 'lifetime' ) {
			$expiration = __( 'Lifetime license', 'searchwp-live-ajax-search' );
		} else {
			$expiration = sprintf(
			// Translators: First placeholder is the date of license expiration, second placeholder is the time until the license expires.
				__( 'Active until %1$s (%2$s)', 'searchwp-live-ajax-search' ),
				date( 'M j, Y', strtotime( $license_data->expires ) ), // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date
				human_time_diff( strtotime( $license_data->expires ), current_time( 'timestamp' ) ) // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
			);
		}

		// Assign the lowest license type as a fallback.
		$type = self::LICENSE_TYPES[0];

		// Translate the EDD Price ID into the license type.
		if ( isset( $license_data->price_id ) && array_key_exists( $license_data->price_id, self::LICENSE_TYPES ) ) {
			$type = self::LICENSE_TYPES[ $license_data->price_id ];
		}

		$license_data = [
			'status'    => $license_data->license,
			'expires'   => $license_data->expires,
			'remaining' => $expiration,
			'type'      => $type,
			'key'       => $license_key,
		];

		return update_option( 'searchwp_license', $license_data );
	}

	/**
	 * Activate SearchWP.
	 *
	 * @since 1.8.0
	 *
	 * @param string $plugin_basename The plugin basename.
	 */
	private static function activate_searchwp( $plugin_basename ) {

		// Activate the plugin silently.
		$activated = activate_plugin( $plugin_basename );

		if ( is_wp_error( $activated ) ) {
			wp_send_json_error( $activated );
		}

		wp_send_json_success(
			[
				'swp_activated' => true,
				'redirect'      => add_query_arg(
					[ 'page' => 'searchwp-welcome' ],
					esc_url( admin_url( 'index.php' ) )
				),
			]
		);
	}
}
