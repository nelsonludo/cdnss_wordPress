<?php

use SearchWP_Live_Search_Utils as Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search.
 *
 * The main SearchWP Live Ajax Search Class properly routes searches and all other requests/utilization
 *
 * @since 1.0
 */
class SearchWP_Live_Search {

	/**
	 * Initial setup.
	 *
	 * @since 1.7.0
	 */
	public function setup() {

		searchwp_live_search()
			->incl( 'class-utils.php' );

		searchwp_live_search()
			->incl( 'class-install.php' )
			->register( 'Install' )
			->setup();

		$this->hooks();
	}

	/**
	 * Run hooks.
	 *
	 * @since 1.7.0
	 */
	public function hooks() {

		add_action( 'init', [ $this, 'init' ] );
		add_action( 'admin_init', [ $this, 'register_improve_your_search_notice' ] );
		add_action( 'widgets_init', [ $this, 'register_widget' ] );
		add_action( 'in_admin_header', [ __CLASS__, 'admin_header' ], 100 );

		add_filter( 'plugin_action_links_' . plugin_basename( SEARCHWP_LIVE_SEARCH_PLUGIN_FILE ), [ $this, 'settings_link' ] );
	}

	/**
	 * Init hook callback.
	 *
	 * @since 1.7.0
	 */
	public function init() {

		$this->load_textdomain();

		searchwp_live_search()
			->incl( 'class-settings.php' )
			->register( 'Settings' )
			->hooks();

		searchwp_live_search()
			->incl( 'class-notice.php' )
			->register( 'Notice' )
			->hooks();

		searchwp_live_search()
			->incl( 'class-settings-api.php' )
			->register( 'Settings_Api' )
			->hooks();

		searchwp_live_search()
			->incl( 'class-notifications.php' )
			->register( 'Notifications' )
			->init();

		if ( ! Utils::is_searchwp_active() ) {

			searchwp_live_search()
				->incl( 'SearchForms/Storage.php' )
				->register( 'Storage' );

			searchwp_live_search()
				->incl( 'SearchForms/Frontend.php' )
				->register( 'Frontend' )
				->init();
		}

		// if an AJAX request is taking place, it's potentially a search, so we'll want to
		// prepare for that else we'll prep the environment for the search form itself.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX &&
			! empty( $_REQUEST['action'] ) && $_REQUEST['action'] === 'searchwp_live_search' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$this->setup_search_client();
		} else {
			$this->setup_search_form();
		}
	}

	/**
	 * Load text domain.
	 *
	 * @since 1.7.0
	 */
	private function load_textdomain() {

		load_plugin_textdomain( 'searchwp-live-ajax-search', false, dirname( plugin_basename( SEARCHWP_LIVE_SEARCH_PLUGIN_FILE ) ) . '/languages/' );
	}

	/**
	 * Bootstrap an environment for an AJAX request.
	 *
	 * @since 1.7.0
	 */
	public function setup_search_client() {

		searchwp_live_search()
			->incl( 'class-template.php' )
			->register( 'Template' );

		searchwp_live_search()
			->incl( 'class-relevanssi-bridge.php' )
			->register( 'Relevanssi_Bridge' )
			->hooks();

		searchwp_live_search()
			->incl( 'class-client.php' )
			->register( 'Client' )
			->setup();
	}

	/**
	 * Bootstrap an environment for a search form.
	 *
	 * @since 1.7.0
	 */
	public function setup_search_form() {

		if ( ! Utils::is_searchwp_active() ) {
			searchwp_live_search()
				->incl( 'SearchForms/SearchFormsView.php' )
				->register( 'SearchFormsView' );

			searchwp_live_search()
				->incl( 'Algorithm/EnginesPreview.php' )
				->register( 'EnginesPreview' );

			if ( ! Utils::is_modal_form_active() ) {
				searchwp_live_search()
					->incl( 'ModalForm/ModalFormPreview.php' )
					->register( 'ModalFormPreview' );
			}
		}

		SearchWP_Live_Search()
			->incl( 'class-license.php' )
			->register( 'License' );

		searchwp_live_search()
			->incl( 'class-menu.php' )
			->register( 'Menu' )
			->hooks();

		searchwp_live_search()
			->incl( 'class-form.php' )
			->register( 'Form' )
			->setup();

		Utils::register_framework_scripts();

		Utils::register_framework_styles();
	}

	/**
	 * Output the SearchWP admin header.
	 *
	 * @since 1.8.0
	 */
	public static function admin_header() {

		// Bail if SearchWP is active.
		if ( utils::is_searchwp_active() ) {
			return;
		}

		// Bail if we're not on the SearchWP Live Search settings page.
		if ( ! Utils::is_swp_live_search_admin_page() ) {
			return;
		}

		utils::header_searchwp_disabled();
	}

	/**
	 * Register "Improve your search" call to action notice.
	 *
	 * @since 1.7.0
	 */
	public function register_improve_your_search_notice() {

		// If SearchWP is installed, bail out.
		if ( Utils::is_searchwp_active() ) {
			return;
		}

		$last_update = get_option( 'searchwp_live_search_last_update' );
		if ( empty( $last_update ) ) {
			return;
		}

		// If it's been less than 3 days since the last update, bail out.
		if ( current_time( 'timestamp' ) < absint( $last_update ) + ( DAY_IN_SECONDS * 3 ) ) {
			return;
		}

		$message =
			sprintf(
				wp_kses( /* translators: %1$s - SearchWP.com URL, %2$s - SearchWP.com URL. */
					__( '<strong>SearchWP Live Ajax Search</strong><br><a href="%1$s" target="_blank" rel="noopener noreferrer">Personalize your search results</a> and discover what your visitors are searching for at the same time with <a href="%2$s" target="_blank" rel="noopener noreferrer">SearchWP Pro!</a>', 'searchwp-live-ajax-search' ),
					[
						'a'      => [
							'href'   => [],
							'target' => [],
							'rel'    => [],
						],
						'strong' => [],
						'br'     => [],
					]
				),
				'https://searchwp.com/?utm_source=WordPress&utm_medium=Global+Notice+Impove+Search+Link&utm_content=Improve+your+search+results&utm_campaign=Live+Ajax+Search',
				'https://searchwp.com/?utm_source=WordPress&utm_medium=Global+Notice+Informational+Link&utm_content=SearchWP+Pro&utm_campaign=Live+Ajax+Search'
			);

		SearchWP_Live_Search_Notice::info(
			$message,
			[
				'dismiss' => \SearchWP_Live_Search_Notice::DISMISS['global'],
				'slug'    => 'improve_your_search_cta',
			]
		);
	}

	/**
	 * Register a search form widget.
	 *
	 * @since 1.7.0
	 */
	public function register_widget() {

		searchwp_live_search()
			->incl( 'class-widget.php' );

		register_widget( 'SearchWP_Live_Search_Widget' );
	}

	/**
	 * Add settings link to the Plugins page.
	 *
	 * @since 1.7.0
	 *
	 * @param array $links Plugin row links.
	 *
	 * @return array $links
	 */
	public function settings_link( $links ) {

		if ( ! Utils::is_searchwp_active() ) {
			$custom['pro'] = sprintf(
				'<a href="%1$s" aria-label="%2$s" target="_blank" rel="noopener noreferrer"
				style="color: #1da867; font-weight: 700;"
				onmouseover="this.style.color=\'#008a20\';"
				onmouseout="this.style.color=\'#00a32a\';"
				>%3$s</a>',
				esc_url(
					add_query_arg(
						[
							'utm_content'  => 'Get+SearchWP+Pro',
							'utm_campaign' => 'Live+Ajax+Search',
							'utm_medium'   => 'Plugins+Table+Upgrade+Link',
							'utm_source'   => 'WordPress',
						],
						'https://searchwp.com/'
					)
				),
				esc_attr__( 'Upgrade to SearchWP Pro', 'searchwp-live-ajax-search' ),
				esc_html__( 'Get SearchWP Pro', 'searchwp-live-ajax-search' )
			);
		}

		if ( Utils::is_searchwp_active() ) {
			$settings_url_arg = [
				'page' => 'searchwp-forms',
				'tab'  => 'live-search',
			];
		} else {
			$settings_url_arg = [
				'page' => 'searchwp-live-search',
			];
		}

		$custom['settings'] = sprintf(
			'<a href="%s" aria-label="%s">%s</a>',
			esc_url(
				add_query_arg(
					$settings_url_arg,
					admin_url( 'admin.php' )
				)
			),
			esc_attr__( 'Go to SearchWP Settings page', 'searchwp-live-ajax-search' ),
			esc_html__( 'Settings', 'searchwp-live-ajax-search' )
		);

		$custom['docs'] = sprintf(
			'<a href="%1$s" aria-label="%2$s" target="_blank" rel="noopener noreferrer">%3$s</a>',
			esc_url(
				add_query_arg(
					[
						'utm_content'  => 'Docs',
						'utm_campaign' => 'Live+Ajax+Search',
						'utm_medium'   => 'Plugins+Table+Docs+Link',
						'utm_source'   => 'WordPress',
					],
					'https://searchwp.com/extensions/live-search/'
				)
			),
			esc_attr__( 'Read the documentation', 'searchwp-live-ajax-search' ),
			esc_html__( 'Docs', 'searchwp-live-ajax-search' )
		);

		return array_merge( $custom, (array) $links );
	}
}
