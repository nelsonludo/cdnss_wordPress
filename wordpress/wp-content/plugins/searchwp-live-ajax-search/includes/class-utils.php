<?php

use SearchWP_Live_Search_Settings_Api as Settings_Api;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Utils.
 *
 * @since 1.7.0
 */
class SearchWP_Live_Search_Utils {

	/**
	 * Plugin general prefix.
	 *
	 * @since 1.7.3
	 */
	const SEARCHWP_LIVE_SEARCH_PREFIX = 'searchwp_live_search_';

	/**
	 * SearchWP Live Ajax Search slug.
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	const SEARCHWP_LIVE_SEARCH_SLUG = 'searchwp_live_search';

	/**
	 * SearchWP slug.
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	const SEARCHWP_PREFIX = 'searchwp_';

	/**
	 * Check if SearchWP plugin is active.
	 *
	 * @since 1.7.0
	 */
	public static function is_searchwp_active() {

		return class_exists( 'SearchWP' );
	}

	/**
	 * Check if SearchWP Live Ajax Search plugin is active.
	 *
	 * @since 1.8.0
	 */
	public static function is_searchwp_installed() {

		return file_exists( WP_PLUGIN_DIR . '/searchwp/index.php' );
	}

	/**
	 * Check if SearchWP Modal Form plugin is active.
	 *
	 * @since 1.8.5
	 */
	public static function is_modal_form_active() {

		return class_exists( 'SearchWP_Modal_Form' );
	}

	/**
	 * Helper function to determine if loading a Live Ajax Search admin settings page.
	 *
	 * @since 1.7.0
	 *
	 * @return bool
	 */
	public static function is_settings_page() {

		if ( ! is_admin() ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page = isset( $_REQUEST['page'] ) ? sanitize_key( $_REQUEST['page'] ) : '';

		if ( ! in_array( $page, [ 'searchwp-live-search', 'searchwp-forms' ], true ) ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$view = isset( $_REQUEST['tab'] ) ? sanitize_key( $_REQUEST['tab'] ) : '';

		if ( $page === 'searchwp-forms' && $view !== 'live-search' ) {
			return false;
		}

		return true;
	}

	/**
	 * Helper function to determine if loading a parent Live Ajax Search admin settings page.
	 *
	 * @since 1.7.6
	 *
	 * @return bool
	 */
	public static function is_parent_settings_page() {

		if ( ! is_admin() ) {
			return false;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$page = isset( $_REQUEST['page'] ) ? sanitize_key( $_REQUEST['page'] ) : '';

		if ( empty( $page ) ) {
			return false;
		}

		return $page === 'searchwp-forms';
	}

	/**
	 * Helper function to determine if loading an SearchWP related admin page.
	 *
	 * Here we determine if the current administration page is owned/created by
	 * SearchWP. This is done in compliance with WordPress best practices for
	 * development, so that we only load required SearchWP CSS and JS files on pages
	 * we create. As a result we do not load our assets admin wide, where they might
	 * conflict with other plugins needlessly, also leading to a better, faster user
	 * experience for our users.
	 *
	 * @since 1.8.0
	 *
	 * @param string $slug Slug identifier for a specific SearchWP admin page.
	 * @param string $view Slug identifier for a specific SearchWP admin page view ("subpage").
	 *
	 * @return bool
	 */
	public static function is_swp_live_search_admin_page( $slug = '', $view = '' ) {

		if ( ! is_admin() ) {
			return false;
		}

		$page_prefix = 'searchwp-';

		$current_page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_tab  = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		// Check against basic requirements.
		if ( empty( $current_page ) || strpos( $current_page, $page_prefix ) !== 0 ) {
			return false;
		}

		// Check against page slug identifier.
		if ( ! empty( $slug ) && $page_prefix . $slug !== $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return false;
		}

		if ( ! empty( $view ) && $view !== $current_tab ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if any of the supported ecommerce plugins are active.
	 *
	 * @since 1.8.0
	 *
	 * @return bool
	 */
	public static function is_ecommerce_plugin_active() {

		return class_exists( 'woocommerce' ) || class_exists( 'Easy_Digital_Downloads' );
	}

	/**
	 * Sanitize array/string of CSS classes.
	 *
	 * @since 1.7.0
	 *
	 * @param array|string $classes CSS classes to sanitize.
	 * @param array        $args {
	 *     Optional arguments.
	 *
	 *     @type bool       $convert Whether to suppress filters. Default true.
	 * }
	 *
	 * @return string|array
	 */
	public static function sanitize_classes( $classes, $args = [] ) {

		$is_array = is_array( $classes );
		$convert  = ! empty( $args['convert'] );
		$css      = [];

		if ( ! empty( $classes ) ) {
			$classes = $is_array ? $classes : explode( ' ', trim( $classes ) );
			foreach ( $classes as $class ) {
				if ( ! empty( $class ) ) {
					$css[] = sanitize_html_class( $class );
				}
			}
		}

		if ( $is_array ) {
			return $convert ? implode( ' ', $css ) : $css;
		}

		return $convert ? $css : implode( ' ', $css );
	}

	/**
	 * Localizes a script using a standard set of variables.
	 *
	 * @since 1.7.3
	 *
	 * @param string $handle   The script handle to localize.
	 * @param array  $settings Additional settings to localize.
	 */
	public static function localize_script( string $handle, array $settings = [] ) {

		$capability = Settings_Api::get_capability();

		$l10n = [
			'nonce'  => current_user_can( $capability ) ? wp_create_nonce( self::SEARCHWP_LIVE_SEARCH_PREFIX . 'settings' ) : '',
			'prefix' => self::SEARCHWP_LIVE_SEARCH_PREFIX,
		];

		if ( ! empty( $settings ) && is_array( $settings ) ) {
			$l10n = array_merge( $l10n , $settings );
		}

		wp_localize_script( $handle, '_SEARCHWP_LIVE_SEARCH', $l10n );
	}


	/**
	 * Check if the settings page has all the necessary permissions (nonce and capability).
	 * If not, it will return false.
	 *
	 * @since 1.8.0
	 */
	public static function check_settings_permissions() {

		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ), self::SEARCHWP_PREFIX . 'settings' ) ) {
			return false;
		}

		if ( ! current_user_can( Settings_Api::get_capability() ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if the AJAX call has all the necessary permissions (nonce and capability).
	 *
	 * @since 1.7.3
	 *
	 * @param array $args Arguments to change method's behaviour.
	 *
	 * @return bool
	 */
	public static function check_ajax_permissions( $args = [] ) {

		$defaults = [
			'capability' => Settings_Api::get_capability(),
			'query_arg'  => false,
			'die'        => true,
		];

		$args = wp_parse_args( $args, $defaults );

		$result = check_ajax_referer( self::SEARCHWP_LIVE_SEARCH_PREFIX . 'settings', $args['query_arg'], $args['die'] );

		if ( $result === false ) {
			return false;
		}

		if ( ! current_user_can( $args['capability'] ) ) {
			$result = false;
		}

		if ( $result === false && $args['die'] ) {
			wp_die( -1, 403 );
		}

		return (bool) $result;
	}

	/**
	 * Renders the header if SearchWP is disabled.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public static function header_searchwp_disabled() {

		if ( ! self::is_swp_live_search_admin_page() ) {
			return;
		}

		/**
		 * Fires before the SearchWP Live Search settings header.
		 *
		 * @since 1.8.0
		 */
		do_action( 'searchwp_live_search_settings_header_before' );

		self::header_searchwp_disabled_main();
		self::header_searchwp_disabled_sub();

		echo '<hr class="wp-header-end">';

		/**
		 * Fires after the SearchWP Live Search settings header.
		 *
		 * @since 1.8.0
		 */
		do_action( 'searchwp_live_search_settings_header_after' );
	}

	/**
	 * Renders the main header.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public static function header_searchwp_disabled_main() {

		?>
		<div class="searchwp-settings-header">
			<div class="searchwp-logo" title="SearchWP">
				<?php self::header_logo(); ?>
			</div>
			<div class="searchwp-header-actions">
				<?php
				/**
				 * Fires in the SearchWP Live Search settings header actions.
				 * Used to add custom actions to the header.
				 *
				 * @since 1.8.0
				 */
				do_action( 'searchwp_live_search_settings_header_actions' );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Renders the subheader.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public static function header_searchwp_disabled_sub() {

		/**
		 * Filters the subheader items for the SearchWP Live Search settings.
		 *
		 * @since 1.8.0
		 *
		 * @param array $sub_header_items Subheader items.
		 */
		$sub_header_items = apply_filters( 'searchwp_live_search_settings_sub_header_items', [] );

		$current_page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$current_tab  = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( empty( $current_page ) || ! isset( $sub_header_items[ $current_page ] ) ) {
			return;
		}

		echo '<div class="searchwp-settings-subheader">';
		echo '<nav class="searchwp-settings-header-nav">';
		echo '<ul>';

		foreach ( $sub_header_items[ $current_page ] as $sub_header_item ) {
			$preview_pages      = [ 'search-modal', 'searchwp-algorithm' ];
			$query_args         = [];
			$query_args['page'] = $sub_header_item['page'];

			if ( ! empty( $sub_header_item['tab'] ) ) {
				$query_args['tab'] = $sub_header_item['tab'];
			}

			$page_url = esc_url(
				add_query_arg(
					$query_args,
					admin_url( 'admin.php' )
				)
			);

			$label = $sub_header_item['label'];

			$is_active  = $sub_header_item['tab'] === $current_tab ? ' searchwp-settings-nav-tab-active' : '';
			$is_preview = in_array( $sub_header_item['tab'], $preview_pages, true ) ? ' searchwp-settings-nav-tab-preview' : '';
			?>
			<li class="searchwp-settings-nav-tab-wrapper searchwp-settings-nav-tab-searchwp-live-search-wrapper<?php echo esc_attr( $is_active ); ?>">
				<a href="<?php echo esc_url( $page_url ); ?>" class="searchwp-settings-nav-tab searchwp-settings-nav-tab-searchwp-live-search<?php echo esc_attr( $is_active ); ?> <?php echo esc_attr( $is_preview ); ?>">
					<span><?php echo esc_html( $label ); ?></span>
				</a>
			</li>
			<?php
		}

		echo '</ul>';
		echo '</nav>';
		echo '</div>';
	}

	/**
	 * Renders the header logo.
	 *
	 * @since 1.7.3
	 *
	 * @return void
	 */
	public static function header_logo() {
		?>
		<svg fill="none" height="40" viewBox="0 0 186 40" width="186" xmlns="http://www.w3.org/2000/svg"
			xmlns:xlink="http://www.w3.org/1999/xlink">
			<clipPath id="a">
				<path d="m0 0h26.2464v40h-26.2464z"/>
			</clipPath>
			<g fill="#456b47">
				<path d="m51.2968 15.3744c-.1125.2272-.225.4544-.45.568-.1126.1136-.3376.1136-.5626.1136-.2251 0-.4501-.1136-.7876-.2272-.2251-.2272-.5626-.3408-1.0127-.568s-.7876-.4544-1.3502-.568c-.4501-.2272-1.1252-.2272-1.8003-.2272s-1.1251.1136-1.5752.2272-.9001.3408-1.1252.6816c-.3375.2272-.5625.568-.6751.9088-.1125.3409-.225.7953-.225 1.2497 0 .568.1125 1.0224.4501 1.4768.3375.3408.7876.6817 1.2377 1.0225.5625.2272 1.1251.4544 1.8002.6816l2.0253.6816c.6751.2272 1.3502.568 2.0253.7953.6751.3408 1.2377.6816 1.8003 1.2496.5626.4544.9001 1.136 1.2377 1.8177.3375.6816.45 1.5904.45 2.6129 0 1.136-.225 2.1584-.5625 3.0673-.3376.9088-.9002 1.8176-1.5753 2.4993-.6751.6816-1.5752 1.2496-2.5879 1.704-1.0126.4544-2.2503.568-3.488.568-.7876 0-1.4627-.1136-2.2503-.2272s-1.4627-.3408-2.1378-.6816c-.6751-.2272-1.3502-.568-1.9128-1.0224-.1125-.1136-.2251-.2272-.4501-.2272-.6751-.5681-.9001-1.4769-.4501-2.2721l.5626-.9089c.1125-.1136.2251-.3408.4501-.3408.225-.1136.3375-.1136.5626-.1136.225 0 .5626.1136.9001.3408.3376.2272.6751.4545 1.1252.7953s.9001.568 1.5752.7952c.5626.2272 1.3502.3408 2.1378.3408 1.2377 0 2.2504-.3408 2.9255-.9088s1.0126-1.4769 1.0126-2.6129c0-.6816-.1125-1.1361-.45-1.5905-.3376-.4544-.7877-.7952-1.2377-1.0224-.5626-.2272-1.1252-.4544-1.8003-.6816s-1.3502-.3408-2.0253-.5681c-.6751-.2272-1.3502-.4544-2.0253-.7952s-1.2377-.6816-1.8003-1.2496c-.5626-.4544-.9001-1.1361-1.2377-1.9313-.3375-.7952-.45-1.7041-.45-2.8401 0-.9088.225-1.7041.5626-2.6129.3375-.7952.9001-1.5905 1.5752-2.2721s1.4627-1.136 2.4754-1.5904c1.0126-.3408 2.1378-.5681 3.3755-.5681 1.4627 0 2.7004.2273 3.9381.6817.7876.3408 1.5752.6816 2.1378 1.136.5626.3408.6751 1.1361.3375 1.7041z"/>
				<path d="m62.4361 17.7601c1.1252 0 2.0253.2272 2.9255.5681.9001.3408 1.6877.9088 2.3628 1.4768s1.1252 1.4769 1.5753 2.4993c.3375 1.0224.5625 2.0449.5625 3.2945v.7952c0 .2273-.1125.3409-.1125.4545-.1125.1136-.225.2272-.3375.2272s-.2251.1136-.4501.1136h-10.5766c.1125 1.8176.5626 3.0673 1.4627 3.8625.7877.7952 1.9128 1.2497 3.263 1.2497.6751 0 1.2377-.1136 1.6878-.2273.4501-.1136.9001-.3408 1.2377-.568.3375-.2272.6751-.3408.9001-.568.225-.1136.5626-.2272.7876-.2272.1125 0 .3376 0 .4501.1136s.225.1136.3375.3408l.2251.3408c.5626.7953.45 1.8177-.3376 2.3857-.1125 0-.1125.1136-.225.1136-.5626.3408-1.1252.6816-1.8003.9088-.5626.2273-1.2377.3409-1.9128.4545s-1.2376.1136-1.8002.1136c-1.2377 0-2.2504-.2272-3.263-.5681-1.0127-.3408-1.9128-1.0224-2.7004-1.8176s-1.3502-1.7041-1.8003-2.8401c-.4501-1.1361-.6751-2.4993-.6751-3.9762 0-1.136.225-2.272.5626-3.2945.3375-1.0224.9001-1.9312 1.5752-2.7265.6751-.7952 1.5753-1.3632 2.5879-1.8176 1.0127-.4545 2.1378-.6817 3.488-.6817zm0 2.9537c-1.2377 0-2.1378.3408-2.8129 1.0225-.6751.6816-1.1251 1.704-1.3502 2.9537h7.6512c0-.568-.1126-1.0225-.2251-1.4769s-.3375-.9088-.6751-1.2496c-.3375-.3408-.6751-.6816-1.1251-.7952-.4501-.1137-.7877-.4545-1.4628-.4545z"/>
				<path d="m85.277 33.5511c0 .9089-.7877 1.7041-1.6878 1.7041h-.225c-.3376 0-.6751-.1136-.9002-.2272-.225-.1136-.3375-.3408-.45-.6817l-.3376-1.2496c-.45.3408-.9001.6816-1.2377 1.0224-.45.3409-.9001.5681-1.2376.7953-.4501.2272-.9002.3408-1.4628.4544-.45.1136-1.0126.1136-1.6877.1136s-1.3502-.1136-2.0253-.3408c-.5626-.2272-1.1252-.4544-1.5752-.9089-.4501-.3408-.7877-.9088-1.0127-1.4768s-.3375-1.2497-.3375-2.0449c0-.6816.1125-1.2496.5625-1.9313.3376-.6816.9002-1.2496 1.6878-1.704s1.8003-.9088 3.1505-1.2497c1.3502-.3408 2.9254-.4544 4.8382-.4544v-1.0224c0-1.1361-.2251-2.0449-.6751-2.6129-.4501-.568-1.2377-.7952-2.1378-.7952-.6751 0-1.2377.1136-1.6878.2272s-.7876.3408-1.1252.568c-.3375.2272-.6751.3408-.9001.568-.3375.1136-.6751.2272-1.0126.2272-.2251 0-.5626-.1136-.6751-.2272-.2251-.1136-.3376-.3408-.4501-.568v-.1136c-.4501-.7952-.225-1.7041.4501-2.1585 1.6877-1.136 3.713-1.8177 5.9633-1.8177 1.0127 0 1.9128.1137 2.7004.4545.7877.3408 1.4628.7952 2.0253 1.3632.5626.568.9002 1.2497 1.2377 2.1585.3376.7952.4501 1.7041.4501 2.7265v9.2019zm-7.9887-.9088c.45 0 .7876 0 1.1251-.1136.3376-.1136.6751-.2272 1.0127-.3408.3375-.1136.6751-.3408.9001-.5681.3376-.2272.5626-.4544.9002-.7952v-2.8401c-1.2377 0-2.2504.1136-3.038.2272s-1.4627.3408-1.9128.568c-.45.2273-.7876.5681-1.0126.7953-.2251.3408-.3376.6816-.3376 1.0224 0 .6816.2251 1.2497.6751 1.5905.4501.3408 1.0127.4544 1.6878.4544z"/>
				<path d="m88.2024 33.8918v-13.8597c0-.9088.7876-1.704 1.6877-1.704h.7877c.45 0 .6751.1136.9001.2272.1125.1136.225.4544.3375.7952l.2251 2.0449c.5626-1.0225 1.3502-1.9313 2.1378-2.4993s1.8003-.9089 2.8129-.9089h.4501c.9001.1136 1.5752 1.0225 1.4627 1.9313l-.3375 1.7041c0 .2272-.1126.3408-.2251.4544s-.225.1136-.4501.1136c-.1125 0-.3375 0-.6751-.1136-.3375-.1136-.6751-.1136-1.1251-.1136-.9002 0-1.5753.2272-2.1378.6816-.5626.4544-1.1252 1.1361-1.5753 2.0449v9.0883c0 .9088-.7876 1.7041-1.6877 1.7041h-.9002c-.9001 0-1.6877-.6817-1.6877-1.5905z"/>
				<path d="m112.619 21.7363c-.113.1136-.225.2272-.338.3408s-.338.1136-.563.1136-.45-.1136-.562-.2272c-.225-.1136-.45-.2272-.675-.4544-.225-.1136-.563-.3408-1.013-.4544-.337-.1137-.9-.2273-1.463-.2273-.675 0-1.35.1136-1.912.3409-.563.2272-1.013.6816-1.351 1.136-.337.4544-.675 1.136-.787 1.8177-.225.6816-.225 1.4768-.225 2.3856 0 .9089.112 1.7041.337 2.4993.225.6817.45 1.3633.788 1.8177.337.4544.788.9088 1.35 1.136.563.2273 1.125.3409 1.8.3409s1.126-.1136 1.576-.2273c.45-.1136.787-.3408 1.012-.568s.563-.3408.675-.568c.225-.1136.45-.2272.675-.2272.338 0 .563.1136.788.3408l.225.3408c.563.6816.45 1.8177-.337 2.3857-.113.1136-.225.2272-.225.2272-.563.3408-1.126.6816-1.688.9088-.563.2273-1.125.3409-1.8.4545-.563.1136-1.238.1136-1.801.1136-1.012 0-2.025-.2272-2.925-.5681-.9-.3408-1.688-1.0224-2.476-1.704-.675-.7952-1.237-1.7041-1.687-2.8401-.4504-1.1361-.5629-2.3857-.5629-3.7489 0-1.2497.225-2.3857.5629-3.5218.337-1.0224.9-2.0448 1.575-2.8401.675-.7952 1.575-1.3632 2.588-1.8176 1.012-.4545 2.25-.6817 3.6-.6817 1.238 0 2.363.2272 3.376.5681.45.2272.787.3408 1.238.6816.787.568 1.012 1.5904.45 2.3857z"/>
				<path d="m115.094 33.8919v-21.5848c0-.9088.787-1.7041 1.688-1.7041h.787c.9 0 1.688.7953 1.688 1.7041v7.9523c.675-.6816 1.35-1.1361 2.138-1.5905.787-.3408 1.687-.568 2.813-.568.9 0 1.8.1136 2.475.4544s1.35.7952 1.8 1.3633c.45.568.9 1.2496 1.125 2.0448.225.7953.338 1.7041.338 2.6129v9.3156c0 .9088-.788 1.704-1.688 1.704h-.787c-.901 0-1.688-.7952-1.688-1.704v-9.3156c0-1.0224-.225-1.8176-.675-2.3857-.45-.568-1.238-.9088-2.138-.9088-.788 0-1.463.2272-2.138.568-.562.3408-1.125.7953-1.688 1.2497v10.7924c0 .9088-.787 1.704-1.687 1.704h-.788c-.788-.1136-1.575-.7952-1.575-1.704z"/>
				<path d="m132.197 12.9886c-.338-1.136.45-2.1585 1.575-2.1585h1.575c.45 0 .675.1136 1.013.2272.225.2273.45.4545.562.7953l4.163 14.8821c.113.3408.225.7952.225 1.1361.113.4544.113.9088.225 1.3632.113-.4544.225-.9088.338-1.3632.112-.4545.225-.7953.338-1.1361l4.838-14.8821c.112-.2272.225-.4544.562-.6816.225-.2273.563-.3409 1.013-.3409h1.35c.45 0 .675.1136 1.013.2272.225.2273.45.4545.562.7953l4.839 14.8821c.225.6816.45 1.5905.675 2.3857.112-.4544.112-.9088.225-1.2496.112-.4545.225-.7953.225-1.1361l4.163-14.8821c.112-.3408.225-.568.562-.6816.226-.2273.563-.3409 1.013-.3409h1.238c1.125 0 1.913 1.1361 1.575 2.1585l-6.526 21.3576c-.225.6816-.9 1.2496-1.575 1.2496h-1.688c-.788 0-1.35-.4544-1.575-1.136l-5.176-15.9046c-.112-.2272-.112-.4544-.225-.6816-.112-.2272-.112-.568-.225-.7952-.112.3408-.112.568-.225.7952s-.113.4544-.225.6816l-5.063 15.791c-.225.6816-.9 1.136-1.576 1.136h-1.687c-.788 0-1.35-.4544-1.576-1.2496z"/>
				<path d="m172.69 26.8484v7.0435c0 .9088-.788 1.704-1.688 1.704h-1.238c-.9 0-1.687-.7952-1.687-1.704v-21.4712c0-.9089.787-1.7041 1.687-1.7041h6.301c1.688 0 3.038.2272 4.276.568s2.138.9089 2.925 1.5905c.788.6816 1.351 1.4768 1.688 2.4993.338 1.0224.563 2.0449.563 3.1809 0 1.2496-.225 2.2721-.563 3.2945-.45 1.0225-1.012 1.8177-1.8 2.6129-.788.6816-1.8 1.2497-2.926 1.7041-1.237.4544-2.587.568-4.163.568h-3.375zm0-3.6353h3.375c.788 0 1.576-.1136 2.138-.3408.675-.2273 1.125-.5681 1.575-.9089s.675-.9088.901-1.4768c.225-.5681.337-1.2497.337-1.9313s-.112-1.2496-.337-1.8177c-.226-.568-.563-1.0224-.901-1.3632-.45-.3408-.9-.6816-1.575-.9089-.675-.2272-1.35-.3408-2.138-.3408h-3.375z"/>
			</g>
			<g clip-path="url(#a)">
				<g clip-rule="evenodd" fill="#456b47" fill-rule="evenodd">
					<path d="m24.5846 16.0458c0-.7083-.6797-1.4326-1.6619-1.4326v-1.7192c1.7686 0 3.3811 1.3387 3.3811 3.1518v16.5043c0 1.8132-1.6125 3.1519-3.3811 3.1519h-2.4068v-1.7192h2.4068c.9822 0 1.6619-.7243 1.6619-1.4327z"/>
					<path d="m.057373 16.0458c0-1.8131 1.612567-3.1518 3.381087-3.1518v1.7192c-.98219 0-1.66189.7243-1.66189 1.4326v16.5043c0 .7084.6797 1.4327 1.66189 1.4327h2.29226v1.7192h-2.29226c-1.76852 0-3.381087-1.3387-3.381087-3.1519z"/>
					<path d="m5.94932 16.2056c.25995-.6058.52876-1.2322.83483-1.8954l1.56096.7205c-.26042.5642-.51794 1.1623-.77746 1.765-.38453.893-.77343 1.7962-1.18259 2.6145-.87996 1.7599-1.36619 3.5097-1.06846 5.1968l.00446.0254.00295.0255c.31338 2.716 1.65716 4.9094 4.10687 6.6135l-.98178 1.4113c-2.81518-1.9584-4.45017-4.5703-4.83002-7.8025-.38037-2.2007.27811-4.3385 1.22828-6.2389.40036-.8007.7427-1.5985 1.10196-2.4357z"/>
					<path d="m21.13 17.6879c.1672.3555.3376.718.5103 1.0923l.0036.0078.0035.0078c.8235 1.8824 1.467 3.8834 1.2129 6.1702l-.0008.0075c-.376 3.1333-2.0144 5.7413-4.8125 7.8094l-1.0219-1.3825c2.473-1.8278 3.8144-4.0323 4.127-6.628.2029-1.8351-.2978-3.4985-1.0763-5.2796-.1596-.3457-.3213-.6894-.4829-1.0329-.519-1.1035-1.0373-2.2055-1.4838-3.3662l1.6046-.6172c.422 1.0971.9038 2.1216 1.4163 3.2114z"/>
					<path d="m4.46831 17.2516c.28355-.3807.82208-.4595 1.20285-.176l16.16044 12.0344c.3808.2835.4596.8221.176 1.2028-.2835.3808-.822.4596-1.2028.1761l-16.16046-12.0344c-.38077-.2836-.45958-.8221-.17603-1.2029z"/>
					<path d="m22.0076 17.2516c.2836.3808.2048.9193-.176 1.2029l-16.16044 12.0344c-.38077.2835-.9193.2047-1.20285-.1761-.28355-.3807-.20474-.9193.17603-1.2028l16.16046-12.0344c.3808-.2835.9193-.2047 1.2028.176z"/>
				</g>
				<path d="m18.1089 11.2321h-9.74213c-.34384 0-.57307-.2292-.57307-.5731v-1.48995c0-1.60458 1.26075-2.75072 2.7507-2.75072h5.2722c1.6046 0 2.7507 1.26075 2.7507 2.75072v1.37535c.1147.4585-.1146.6877-.4584.6877z" fill="#77a872"/>
				<path clip-rule="evenodd" d="m10.5444 7.27791c-1.03889 0-1.89112.7846-1.89112 1.89112v1.20347h9.05442v-1.20347c0-1.03889-.7846-1.89112-1.8911-1.89112zm-3.61032 1.89112c0-2.10264 1.66926-3.61031 3.61032-3.61031h5.2722c2.1026 0 3.6103 1.66925 3.6103 3.61031v1.28517c.0656.3566.0348.7697-.2292 1.1217-.2951.3934-.7352.5158-1.0888.5158h-9.74215c-.36726 0-.74011-.1262-1.0233-.4094-.2832-.2832-.40937-.656-.40937-1.0233z" fill="#456b47" fill-rule="evenodd"/>
				<path clip-rule="evenodd" d="m5.04306 12.3209c-.32755 0-.51576.1883-.51576.5158v.6304h17.192v-.6304c0-.3275-.1882-.5158-.5158-.5158zm-2.23495.5158c0-1.277.95792-2.235 2.23495-2.235h16.16044c1.2771 0 2.235.958 2.235 2.235v.9169c0 .3673-.1262.7401-.4094 1.0233s-.656.4094-1.0233.4094h-17.76503c-.36726 0-.74011-.1262-1.0233-.4094s-.40936-.656-.40936-1.0233z" fill="#456b47" fill-rule="evenodd"/>
				<path clip-rule="evenodd" d="m7.73657 5.61605c0-3.11085 2.44793-5.558738 5.55873-5.558738 3.1109 0 5.5587 2.447888 5.5587 5.558738 0 .46159-.1409 1.12803-.2548 1.58384l-.2599 1.0396-.9585-.47923c-.4293-.21463-.854-.3677-1.2202-.3677h-5.8452c-.43253 0-.69723.07877-.97424.28653l-1.09117.81838-.2675-1.33748c-.02047-.10234-.04319-.21143-.06586-.32022-.03398-.16313-.06783-.32558-.0937-.46359-.04491-.23951-.08636-.50482-.08636-.76013zm5.55873-3.83954c-2.1613 0-3.83953 1.67818-3.83953 3.83954 0 .03853.003.08603.00978.14544.27521-.06286.55735-.08813.84985-.08813h5.8452c.3357 0 .6605.05655.9604.1404.009-.07754.0139-.14461.0139-.19771 0-2.16136-1.6782-3.83954-3.8396-3.83954z" fill="#456b47" fill-rule="evenodd"/>
				<path d="m19.9427 39.1977h-13.63894c-1.14613 0-2.06304-.9169-2.06304-2.063v-2.7508c0-1.2607 1.03152-2.2922 2.29227-2.2922h13.18051c1.2607 0 2.2923 1.0315 2.2923 2.2922v2.7508c0 1.1461-.9169 2.063-2.0631 2.063z" fill="#77a872"/>
				<path clip-rule="evenodd" d="m6.53297 32.9513c-.78601 0-1.43267.6467-1.43267 1.4327v2.7507c0 .6714.53205 1.2034 1.20344 1.2034h13.63896c.6714 0 1.2034-.532 1.2034-1.2034v-2.7507c0-.786-.6466-1.4327-1.4326-1.4327zm-3.15187 1.4327c0-1.7355 1.41638-3.1519 3.15187-3.1519h13.18053c1.7355 0 3.1518 1.4164 3.1518 3.1519v2.7507c0 1.6209-1.3017 2.9226-2.9226 2.9226h-13.63896c-1.62088 0-2.92264-1.3017-2.92264-2.9226z" fill="#456b47" fill-rule="evenodd"/>
			</g>
		</svg>
		<?php
	}

	/**
	 * Register the styling framework scripts.
	 *
	 * @since 1.8.0
	 */
	public static function register_framework_scripts() {

		$scripts = [
			'choices',
			'collapse',
			'color-picker',
			'copy-input-text',
			'pills',
			'modal',
			'settings-toggle',
		];

		foreach ( $scripts as $script ) {
			wp_register_script(
				self::SEARCHWP_LIVE_SEARCH_SLUG . $script,
				SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/js/admin/components/' . $script . '.js',
				[ 'jquery' ],
				SEARCHWP_LIVE_SEARCH_VERSION,
				true
			);
		}
	}

	/**
	 * Register framework styles.
	 *
	 * @since 1.8.0
	 */
	public static function register_framework_styles() {

		$styles = [
			'style',
			'buttons',
			'card',
			'choicesjs',
			'collapse-layout',
			'color-picker',
			'colors',
			'content-header',
			'draggable',
			'header',
			'input',
			'layout',
			'license-banner',
			'modal',
			'nav-menu',
			'pills',
			'radio-img',
			'toggle-switch',
			'tooltip',
			'upload-file',
		];

		foreach ( $styles as $style ) {
			wp_register_style(
				self::SEARCHWP_LIVE_SEARCH_SLUG . $style,
				SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/styles/admin/framework/' . $style . '.css',
				[],
				SEARCHWP_LIVE_SEARCH_VERSION
			);
		}
	}

	/**
	 * Enqueue the framework scripts.
	 *
	 * @since 1.8.0
	 *
	 * @param array $scripts Scripts to enqueue.
	 */
	public static function enqueue_framework_scripts( array $scripts = [] ) {

		if ( empty( $scripts ) || ! is_array( $scripts ) ) {
			return;
		}

		foreach ( $scripts as $script ) {
			wp_enqueue_script(
				self::SEARCHWP_LIVE_SEARCH_SLUG . $script,
				SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/js/admin/components/' . $script . '.js'
			);
		}
	}

	/**
	 * Enqueue the framework styles.
	 *
	 * @since 1.8.0
	 *
	 * @param array $styles Styles to enqueue.
	 */
	public static function enqueue_framework_styles( array $styles = [] ) {

		if ( empty( $styles ) || ! is_array( $styles ) ) {
			return;
		}

		foreach ( $styles as $style ) {
			wp_enqueue_style(
				self::SEARCHWP_LIVE_SEARCH_SLUG . $style,
				SEARCHWP_LIVE_SEARCH_PLUGIN_URL . 'assets/styles/admin/framework/' . $style . '.css'
			);
		}
	}

	/**
	 * Get the debug suffix for asset URLs.
	 *
	 * Returns an empty string if SCRIPT_DEBUG is true or if script_debug is set in the URL,
	 * otherwise returns '.min'.
	 *
	 * @since 1.8.6
	 *
	 * @return string
	 */
	public static function get_debug_assets_suffix() {

		return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG === true ) || ( isset( $_GET['script_debug'] ) ) ? '' : '.min'; // phpcs:ignore WordPress.Security.NonceVerification
	}
}
