<?php

use SearchWP\Utils as SearchWP_Utils;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Template.
 *
 * Template loader class based on Pippin Williamson's guide
 * http://pippinsplugins.com/template-file-loaders-plugins/
 *
 * @since 1.0
 */
class SearchWP_Live_Search_Template {

	/**
	 * The templates to load.
	 *
	 * @since 1.8.0
	 *
	 * @var string[] $templates The templates to load in order of precedence.
	 */
	private $template_slugs = [
		'search-results',
		'live-search-results',
	];

	/**
	 * Load the template.
	 *
	 * @since 1.8.0
	 *
	 * @param WP_Query|\SearchWP\Query $query The SearchWP query.
	 * @param array                    $args  The arguments to query the results before loading the template.
	 *
	 * @return void
	 */
	public function load_template( $query, $args = [] ) {

		/**
		 * Fires before the template is loaded.
		 *
		 * @since 1.8.0
		 *
		 * @param WP_Query|\SearchWP\Query $query The search query.
		 * @param array $args  The arguments to query the results before loading the template.
		 */
		do_action( 'searchwp_live_search_before_load_template', $query, $args );

		$engine       = '';
		$source_query = 'wp_query';

		if ( $query instanceof SearchWP\Query ) {
			$engine       = $args['engine'];
			$source_query = 'searchwp_query';
		}

		foreach ( $this->template_slugs as $template_slug ) {

			$template = $this->get_template_part( $template_slug, $engine, false );

			if ( empty( $template ) ) {
				continue;
			}

			$template_loader = "load_template__{$template_slug}__{$source_query}";
			$template_loader = str_replace( '-', '_', $template_loader );

			if ( is_callable( [ $this, $template_loader ] ) ) {

				call_user_func(
					[ $this, $template_loader ],
					[
						'template'   => $template,
						'query'      => $query,
						'query_args' => $args,
					]
				);
				break;
			}
		}

		/**
		 * Fires after the template is loaded.
		 *
		 * @since 1.8.0
		 *
		 * @param WP_Query|\SearchWP\Query $query The search query.
		 * @param array $args  The arguments to query the results before loading the template.
		 */
		do_action( 'searchwp_live_search_after_load_template', $query, $args );
	}

	/**
	 * Load the live-search-results.php template if SearchWP is not active.
	 *
	 * @since 1.8.0
	 *
	 * @param array $data The necessary data to load the template.
	 *
	 * @return void
	 */
	public function load_template__live_search_results__wp_query( $data ) {

		$live_search_results = $data['query']->posts;
		$container_classes   = self::get_container_classes();
		require_once $data['template'];
	}

	/**
	 * Load the live-search-results.php template if SearchWP is active.
	 *
	 * @since 1.8.0
	 *
	 * @param array $data The necessary data to load the template.
	 *
	 * @return void
	 */
	public function load_template__live_search_results__searchwp_query( $data ) {

		$live_search_results = $data['query']->results;
		$container_classes   = self::get_container_classes();
		require_once $data['template'];
	}

	/**
	 * Load the search-results.php template if SearchWP is not active.
	 *
	 * @since 1.8.0
	 *
	 * @param array $data The necessary data to load the template.
	 *
	 * @return void
	 */
	public function load_template__search_results__wp_query( $data ) {

		global $wp_query;

		$results = $data['query']->posts;

		$data['query_args']['post__in'] = ! empty( $results ) ? wp_list_pluck( $results, 'ID' ) : [ 0 ];

		query_posts( $data['query_args'] ); // phpcs:ignore WordPress.WP.DiscouragedFunctions.query_posts_query_posts

		$wp_query->found_posts = $data['query']->found_posts;

		load_template( $data['template'], true, false );
	}

	/**
	 * Load the search-results.php template if SearchWP is active.
	 *
	 * @since 1.8.0
	 *
	 * @param array $data The necessary data to load the template.
	 *
	 * @return void
	 */
	public function load_template__search_results__searchwp_query( $data ) {

		global $wp_query;

		$args = [
			'post_status'      => 'any',
			'post_type'        => $this->get_engine_post_types(),
			'posts_per_page'   => $data['query_args']['per_page'],
			'orderby'          => 'post__in',
			'suppress_filters' => true,
		];

		$results = $data['query']->get_results();

		$args['post__in'] = ! empty( $results ) ? wp_list_pluck( $results, 'ID' ) : [ 0 ];

		query_posts( $args ); // phpcs:ignore WordPress.WP.DiscouragedFunctions.query_posts_query_posts

		$wp_query->found_posts = $data['query']->found_results;

		load_template( $data['template'], true, false );
	}

	/**
	 * Get the post types to query.
	 *
	 * @since 1.8.1
	 *
	 * @return array
	 */
	private function get_engine_post_types() {

		$global_engine_sources = SearchWP_Utils::get_global_engine_source_names();

		$post_types = [];

		foreach ( $global_engine_sources as $global_engine_source ) {
			$indicator = 'post' . SEARCHWP_SEPARATOR;
			if ( $indicator === substr( $global_engine_source, 0, strlen( $indicator ) ) ) {
				$post_types[] = substr( $global_engine_source, strlen( $indicator ) );
			}
		}

		return $post_types;
	}

	/**
	 * Set up the proper template part array and locate it.
	 *
	 * @since 1.0
	 *
	 * @param string $slug The template slug (without file extension).
	 * @param null   $name The template name (appended to $slug if provided).
	 * @param bool   $load Whether to load the template part.
	 *
	 * @return bool|string The location of the applicable template file
	 */
	public function get_template_part( $slug, $name = null, $load = true ) {

		/**
		 * Fires before the specified template part file is loaded.
		 *
		 * @since 1.0
		 *
		 * @param string $slug The template slug.
		 * @param string $name The template name.
		 */
		do_action( 'get_template_part_' . $slug, $slug, $name );

		$template_names = [];

		if ( isset( $name ) ) {
			$template_names[] = $slug . '-' . $name . '.php';
		}

		$template_names[] = $slug . '.php';

		/**
		 * Filter the template part names.
		 *
		 * @since 1.0
		 *
		 * @param array  $template_names The template names.
		 * @param string $slug           The template slug.
		 * @param string $name           The template name.
		 */
		$template_names = apply_filters( 'searchwp_live_search_get_template_part', $template_names, $slug, $name );

		return $this->locate_template( $template_names, $load, false );
	}

	/**
	 * Retrieve the template directory within this plugin.
	 *
	 * @since 1.0
	 *
	 * @return string The template directory within this plugin
	 */
	private function get_template_directory() {

		return SEARCHWP_LIVE_SEARCH_PLUGIN_DIR . 'templates';
	}

	/**
	 * Check for the applicable template in the child theme, then parent theme,
	 * and in the plugin dir as a last resort and output it if it was located.
	 *
	 * @since 1.0
	 *
	 * @param array $template_names The potential template names in order of precedence.
	 * @param bool  $load           Whether to load the template file.
	 * @param bool  $load_once      Whether to require the template file once.
	 *
	 * @return bool|string The location of the applicable template file
	 */
	private function locate_template( $template_names, $load = false, $load_once = true ) {

		// Default to not found.
		$located = false;

		/**
		 * Filter the template directory.
		 *
		 * @since 1.0
		 *
		 * @param string $template_dir The template directory.
		 */
		$template_dir = apply_filters( 'searchwp_live_search_template_dir', 'searchwp-live-ajax-search' );

		// Try to find the template file.
		foreach ( (array) $template_names as $template_name ) {
			$located = self::locate_template_single( $template_dir, $template_name );
			if ( $located ) {
				break;
			}
		}

		/**
		 * Filter the located template file.
		 *
		 * @since 1.0
		 *
		 * @param string $located The located template file.
		 * @param object $this    The current instance of the template class.
		 */
		$located = apply_filters( 'searchwp_live_search_results_template', $located, $this );

		if ( $load && ! empty( $located ) ) {
			load_template( $located, $load_once );
		}

		return $located;
	}

	/**
	 * Check for the applicable template for a single template name.
	 *
	 * @since 1.7.0
	 *
	 * @param string $template_dir  Theme template dir.
	 * @param string $template_name Template name.
	 *
	 * @return false|string
	 */
	private function locate_template_single( $template_dir, $template_name ) {

		if ( empty( $template_name ) ) {
			return false;
		}

		$template_name = ltrim( $template_name, '/' );

		// Check custom template directory.
		$custom_template_dir = trailingslashit( $template_dir ) . $template_name;
		if ( file_exists( $custom_template_dir ) ) {
			return $custom_template_dir;
		}

		// Check the child theme first.
		$maybe_child_theme = trailingslashit( get_stylesheet_directory() ) . trailingslashit( $template_dir ) . $template_name;
		if ( file_exists( $maybe_child_theme ) ) {
			return $maybe_child_theme;
		}

		// Check parent theme.
		$maybe_parent_theme = trailingslashit( get_template_directory() ) . trailingslashit( $template_dir ) . $template_name;
		if ( file_exists( $maybe_parent_theme ) ) {
			return $maybe_parent_theme;
		}

		// Check theme compat.
		$maybe_theme_compat = trailingslashit( $this->get_template_directory() ) . $template_name;
		if ( file_exists( $maybe_theme_compat ) ) {
			return $maybe_theme_compat;
		}

		return false;
	}


	/**
	 * Get search results page container classes.
	 *
	 * @since 1.8.0
	 *
	 * @return string
	 */
	private static function get_container_classes() {

		$settings = SearchWP_Live_Search()->get( 'Settings_Api' )->get();

		$classes = [
			'searchwp-live-search-results-container',
		];

		switch ( $settings['swp-image-size'] ) {
			case 'small':
				$classes[] = 'swp-ls--img-sm';
				break;

			case 'medium':
				$classes[] = 'swp-ls--img-m';
				break;

			case 'large':
				$classes[] = 'swp-ls--img-l';
				break;
		}

		return implode( ' ', $classes );
	}

	/**
	 * Get the result data to display in the template.
	 *
	 * @since 1.8.0
	 *
	 * @param \WP_Post|\WP_User|\WP_Term|mixed $result Result object.
	 *
	 * @return array
	 */
	public static function get_display_data( $result ) {

		// During a multisite search, results can be from multiple blogs.
		// If the result is from a different blog than the current one, we need to switch to that blog before fetching the result's data.
		$switched_blog = self::maybe_switch_blog( $result );

		// Get the native entry for the result.
		$result = self::maybe_get_native_entry( $result );

		/**
		 * Filter the result object.
		 *
		 * @since 1.8.0
		 *
		 * @param mixed $result The result object.
		 */
		$result = apply_filters( 'searchwp_live_search_results_entry', $result );

		$data = [];

		if ( $result instanceof \WP_Post ) {
			$post_type = get_post_type( $result );

			$data = [
				'id'         => absint( $result->ID ),
				'type'       => get_post_type( $result ),
				'title'      => get_the_title( $result ),
				'permalink'  => get_the_permalink( $result ),
				'image_html' => get_the_post_thumbnail( $result ),
				'content'    => get_the_excerpt( $result ),
			];

			if ( in_array( $post_type , [ 'product', 'download' ], true ) ) {
				$data['price']       = self::get_product_price_html( $result );
				$data['add_to_cart'] = true;
			}
		}

		if ( $result instanceof \WP_User ) {
			$data = [
				'id'         => absint( $result->ID ),
				'type'       => 'user',
				'title'      => $result->data->display_name,
				'permalink'  => get_author_posts_url( $result->data->ID ),
				'image_html' => get_avatar( $result->data->ID ),
				'content'    => get_the_author_meta( 'description', $result->data->ID ),
			];
		}

		if ( $result instanceof \WP_Term ) {
			$data = [
				'id'         => absint( $result->term_id ),
				'type'       => 'taxonomy-term',
				'title'      => $result->name,
				'permalink'  => get_term_link( $result->term_id, $result->taxonomy ),
				'image_html' => '',
				'content'    => $result->description,
			];
		}

		$defaults = [
			'id'         => 0,
			'type'       => 'unknown',
			'title'      => '',
			'permalink'  => '',
			'image_html' => '',
			'content'    => '',
		];

		/**
		 * Filter the result data.
		 *
		 * @since 1.8.0
		 *
		 * @param array $data   The result data.
		 * @param mixed $result The result object.
		 */
		$data = apply_filters( 'searchwp_live_search_results_entry_data', empty( $data ) ? $defaults : $data, $result );

		if ( $switched_blog ) {
			restore_current_blog();
		}

		// Make sure that default array structure is preserved.
		return is_array( $data ) ? array_merge( $defaults, $data ) : $defaults;
	}

	/**
	 * Switch to the blog of the result.
	 *
	 * @since 1.8.4
	 *
	 * @param mixed $result Result object.
	 */
	private static function maybe_switch_blog( $result ) {

		// Only switch to the blog if SearchWP is active.
		if ( ! class_exists( 'SearchWP' ) ) {
			return false;
		}

		if (
			$result instanceof \stdClass &&
			property_exists( $result, 'site' ) &&
			absint( $result->site ) !== get_current_blog_id()
		) {
			switch_to_blog( absint( $result->site ) );

			return true;
		}

		return false;
	}

	/**
	 * Get the native entry of the result.
	 *
	 * @since 1.8.4
	 *
	 * @param mixed $result Result object.
	 *
	 * @return \WP_Post|\WP_User|\WP_Term|mixed
	 */
	private static function maybe_get_native_entry( $result ) {

		// If SearchWP is not active, the result is already the native entry.
		if ( ! class_exists( 'SearchWP\Entry' ) ) {
			return $result;
		}

		if ( $result instanceof \stdClass && property_exists( $result, 'source' ) ) {

			$id = absint( $result->id );

			if ( strpos( $result->source, 'post' . SEARCHWP_SEPARATOR ) === 0 ) {
				$result = get_post( $id );
			} elseif ( strpos( $result->source, 'taxonomy' . SEARCHWP_SEPARATOR ) === 0 ) {
				$result = get_term( $id );
			} elseif ( $result->source === 'user' ) {
				$result = get_user_by( 'ID', $id );
			}
		} elseif ( $result instanceof \SearchWP\Entry ) {

			$result = $result->native();
		}

		return $result;
	}

	/**
	 * Get the product price HTML.
	 *
	 * @since 1.8.0
	 *
	 * @param \WP_Post $result The product post object.
	 *
	 * @return string The product price HTML.
	 */
	private static function get_product_price_html( $result ) {

		// WooCommerce.
		if ( $result->post_type === 'product' && function_exists( 'wc_get_product' ) ) {

			$product = wc_get_product( $result->ID );
			if ( ! $product ) {
				return '';
			}

			return $product->get_price_html();
		}

		// Easy Digital Downloads.
		if ( $result->post_type === 'download' && function_exists( 'edd_price' ) ) {
			return edd_price( $result->ID, false );
		}

		return '';
	}

	/**
	 * Get the product add to cart button HTML.
	 *
	 * @since 1.8.0
	 *
	 * @param \WP_Post $result The post object.
	 *
	 * @return string The product add to cart button HTML.
	 */
	public static function render_add_to_cart( $result ) {

		// WooCommerce.
		if ( $result->post_type === 'product' && function_exists( 'wc_get_product' ) ) {

			global $product;

			$product = wc_get_product( $result->ID );

			if ( ! $product ) {
				return '';
			}

			woocommerce_template_loop_add_to_cart();
		}

		// Easy Digital Downloads.
		if ( $result->post_type === 'download' && function_exists( 'edd_get_purchase_link' ) ) {
			echo edd_get_purchase_link( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				[
					'download_id' => $result->ID,
					'price'       => false,
					'color'       => false,
				]
			);
		}

		return '';
	}

	/**
	 * Render the no results message.
	 *
	 * @since 1.8.0
	 */
	public static function render_no_results_message() {

		$settings = SearchWP_Live_Search()->get( 'Settings_Api' )->get();

		$message = $settings['swp-no-results-message'] ?? __( 'No results found.', 'searchwp-live-ajax-search' );

		/**
		 * Filter the no results message.
		 *
		 * @since 1.8.0
		 *
		 * @param string $message The no results message.
		 */
		$message = apply_filters( 'searchwp_live_search_no_results_message', $message );

		echo '<div class="searchwp-live-search-no-results">' . esc_html( $message ) . '</div>';
	}
}
