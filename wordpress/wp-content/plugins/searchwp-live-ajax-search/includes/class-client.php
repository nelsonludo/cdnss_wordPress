<?php

use SearchWP_Live_Search_Utils as Utils;
use SearchWP\Query as SearchWP_Query;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Client.
 *
 * The SearchWP Live Ajax Search client that performs searches
 *
 * @since 1.0
 */
class SearchWP_Live_Search_Client {

	/**
	 * Equivalent of __construct() — implement our hooks.
	 *
	 * @since 1.0
	 *
	 * @uses add_action() to utilize WordPress Ajax functionality
	 */
	public function setup() {

		add_action( 'wp_ajax_searchwp_live_search', [ $this, 'search' ] );
		add_action( 'wp_ajax_nopriv_searchwp_live_search', [ $this, 'search' ] );

		add_filter( 'option_active_plugins', [ $this, 'control_active_plugins' ] );
		add_filter( 'site_option_active_sitewide_plugins', [ $this, 'control_active_plugins' ] );
	}

	/**
	 * Perform a search.
	 *
	 * @since 1.8.0
	 */
	public function search() {

		if ( empty( $_REQUEST['swpquery'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			die();
		}

		$this->set_excerpt_length();

		if ( Utils::is_searchwp_active() ) {
			$this->show_results_searchwp_active();
		} else {
			$this->show_results_searchwp_not_active();
		}

		// Short circuit to keep the overhead of an admin-ajax.php call to a minimum.
		die();
	}

	/**
	 * Perform a search using SearchWP.
	 *
	 * @since 1.8.0
	 *
	 * @param array $args WP_Query arguments array.
	 *
	 * @uses SearchWP\Query to retrieve the post IDs
	 * @uses query_posts() to prep the WordPress environment in its entirety for the template loader
	 * @uses load_template() to load the proper results template
	 */
	private function show_results_searchwp_active( $args = [] ) {

		// Get the SearchWP engine if applicable.
		$engine = isset( $_REQUEST['swpengine'] ) ? sanitize_key( $_REQUEST['swpengine'] ) : 'default'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$args = [
			's'        => $this->get_search_string(),
			'engine'   => $engine,
			'fields'   => 'all',
			'per_page' => $this->get_posts_per_page(),
		];

		/**
		 * Filter the search arguments.
		 *
		 * @since 1.8.0
		 *
		 * @param array $args The search arguments.
		 */
		$args = apply_filters( 'searchwp_live_search_searchwp_query_args', $args );

		// Uses SearchWP\Query to retrieve the post IDs.
		$searchwp_query = new SearchWP_Query( $this->get_search_string(), $args );

		searchwp_live_search()->get( 'Template' )->load_template( $searchwp_query, $args );
	}

	/**
	 * Perform a search using WP_Query.
	 *
	 * @since 1.8.0
	 *
	 * @uses query_posts() to prep the WordPress environment in its entirety for the template loader
	 * @uses load_template() to load the proper results template
	 */
	private function show_results_searchwp_not_active() {

		$args = $this->get_wp_query_args();

		// Uses WP_Query to retrieve the post IDs.
		$wp_query = new WP_Query( $args );

		searchwp_live_search()->get( 'Template' )->load_template( $wp_query, $args );
	}

	/**
	 * Get the arguments for a WP_Query search.
	 *
	 * @since 1.8.0
	 *
	 * @return mixed
	 */
	private function get_wp_query_args() {

		$args = [
			's'              => $this->get_search_string(),
			'post_status'    => 'publish',
			'post_type'      => get_post_types(
				[
					'public'              => true,
					'exclude_from_search' => false,
				]
			),
			'posts_per_page' => $this->get_posts_per_page(),
		];

		/**
		 * Filter the search arguments.
		 *
		 * @since 1.0
		 *
		 * @param array $args The search arguments.
		 */
		return apply_filters( 'searchwp_live_search_query_args', $args );
	}

	/**
	 * Get the search string.
	 *
	 * @since 1.8.0
	 *
	 * @uses sanitize_text_field() to sanitize input
	 * @return string
	 */
	private function get_search_string() {

		return isset( $_REQUEST['swpquery'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['swpquery'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Retrieve the number of items to display.
	 *
	 * @since 1.8.0
	 *
	 * @uses apply_filters to ensure the posts per page can be filterable via searchwp_live_search_posts_per_page
	 *
	 * @return int $per_page the number of items to display
	 */
	private function get_posts_per_page() {

		$posts_per_page = searchwp_live_search()->get( 'Settings_Api' )->get( 'swp-results-per-page' );

		$posts_per_page = ! empty( $posts_per_page ) ? (int) $posts_per_page : 7;

		/**
		 * Filter the number of posts to display. The default is 7 posts.
		 *
		 * @since 1.0
		 *
		 * @param int $per_page The number of posts to display.
		 */
		$default_posts_per_page = intval( apply_filters( 'searchwp_live_search_posts_per_page', $posts_per_page ) );

		return isset( $_REQUEST['posts_per_page'] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			? intval( $_REQUEST['posts_per_page'] ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			: $default_posts_per_page;
	}

	/**
	 * Potential (opt-in) performance tweak: skip any plugin that's not SearchWP-related.
	 *
	 * @since 1.0
	 *
	 * @param array $plugins Active plugins list.
	 *
	 * @return array
	 */
	public function control_active_plugins( $plugins ) {

		/**
		 * Filter to control plugins during search.
		 *
		 * @since 1.0
		 *
		 * @param bool $applicable Whether to apply the filter.
		 */
		$applicable = apply_filters( 'searchwp_live_search_control_plugins_during_search', false );

		if ( ! $applicable || ! is_array( $plugins ) || empty( $plugins ) ) {
			return $plugins;
		}

		if ( ! isset( $_REQUEST['swpquery'] ) || empty( $_REQUEST['swpquery'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return $plugins;
		}

		// The default plugin whitelist is anything SearchWP-related.
		$plugin_whitelist = [];
		foreach ( $plugins as $plugin_slug ) {
			if ( 0 === strpos( $plugin_slug, 'searchwp' ) ) {
				$plugin_whitelist[] = $plugin_slug;
			}
		}

		/**
		 * Filter the plugins whitelist.
		 *
		 * @since 1.0
		 *
		 * @param array $plugin_whitelist The plugin whitelist.
		 */
		$active_plugins = (array) apply_filters( 'searchwp_live_search_plugin_whitelist', $plugin_whitelist );

		return array_values( $active_plugins );
	}

	/**
	 * Prevents WordPress from forcing the excerpt length.
	 * /wp-includes/blocks/post-excerpt.php forces the excerpt_length to 100.
	 * We need to remove this forced value on the Live Search requests.
	 *
	 * @since 1.8.0
	 *
	 * @uses apply_filters to ensure the excerpt length can be filterable via searchwp_live_search_excerpt_length
	 * @uses absint()
	 *
	 * @return void
	 */
	public function set_excerpt_length() {

		if ( ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] !== 'searchwp_live_search' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		remove_all_filters( 'excerpt_length', PHP_INT_MAX );

		add_filter(
			'excerpt_length',
			function ( $length ) {
				/**
				 * Filter the excerpt length.
				 *
				 * @since 1.8.0
				 *
				 * @param int $excerpt_length The excerpt length.
				 */
				return apply_filters( 'searchwp_live_search_excerpt_length', $length );
			}
		);
	}
}
